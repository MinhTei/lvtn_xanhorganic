<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmationMail;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\OrderStatusLogs;
use App\Models\Product;
use App\Models\UserAddress;
use App\Services\ClientCart;
use App\Services\CouponService;
use App\Services\DeliveryService;
use App\Services\VnPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        ClientCart::mergeSessionToUser($user);

        $cartItems = ClientCart::items();
        foreach($cartItems as $item){
            if($item->product && $item->product->is_active == false){
                ClientCart::remove($item->product->id);
                return redirect()->route('cart')->with('error', 'Sản phẩm: '.$item->product->name.' không còn được bán.');
            }
        }
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('warning', 'Giỏ hàng trống. Vui lòng thêm sản phẩm trước khi thanh toán.');
        }

        $subtotal = ClientCart::subtotal($cartItems);
        $deliveryOptions = DeliveryService::optionsForCart($cartItems);
        $defaultShipping = DeliveryService::defaultType($deliveryOptions) ?? 'standard';
        $shippingFee = DeliveryService::shippingFee($defaultShipping, $subtotal);
        $addresses = $user->addresses()->orderByDesc('is_default')->get();
        $slotGroups = DeliveryService::groupedFreshSlots();
        $defaultDeliveryDay = $slotGroups['today']['available'] ? 'today' : 'tomorrow';
        $cutoffHour = DeliveryService::CUTOFF_HOUR;

        $activeCoupons = Coupon::withCount('usages')
            ->where('start_date', '<=', today())
            ->where('end_date', '>=', today())
            ->get()
            ->filter(function($coupon) {
                if ($coupon->usage_limit !== null && $coupon->usages_count >= $coupon->usage_limit) return false;
                return true;
            });

        return view('clients.pages.checkout', compact(
            'cartItems',
            'subtotal',
            'shippingFee',
            'addresses',
            'deliveryOptions',
            'defaultShipping',
            'slotGroups',
            'defaultDeliveryDay',
            'cutoffHour',
            'activeCoupons'
        ));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string|max:50']);

        $user = Auth::user();
        ClientCart::mergeSessionToUser($user);
        $subtotal = ClientCart::subtotal();

        $result = CouponService::validate($request->code, $subtotal, $user->id);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        ClientCart::mergeSessionToUser($user);
        $cartItems = ClientCart::items();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('warning', 'Giỏ hàng trống.');
        }

        $deliveryOptions = DeliveryService::optionsForCart($cartItems);

        if ($deliveryOptions['conflict']) {
            throw ValidationException::withMessages([
                'shipping_type' => $deliveryOptions['message'],
            ]);
        }

        $allowedTypes = [];
        if ($deliveryOptions['allow_standard']) {
            $allowedTypes[] = 'standard';
        }
        if ($deliveryOptions['allow_express']) {
            $allowedTypes[] = 'express';
        }

        $availableSlots = array_keys(DeliveryService::availableFreshSlots());

        // (shipping_type = standard)
        if ($deliveryOptions['requires_slot'] && !$request->filled('shipping_type')) {
            $request->merge(['shipping_type' => 'standard']);
        }

        $request->validate([
            'address_type' => 'required|in:saved,new',
            'payment_method' => 'required|in:cod,vnpay',
            'shipping_type' => ['required', Rule::in($allowedTypes)],
            'delivery_slot' => [
                Rule::requiredIf(fn () => (bool) $deliveryOptions['requires_slot']),
                'nullable',
                Rule::in($availableSlots),
            ],
            'coupon_code' => 'nullable|string|max:50',
            'note' => 'nullable|string|max:500',
            'saved_address_id' => 'required_if:address_type,saved|nullable|exists:user_addresses,id',
            'name'     => 'required_if:address_type,new|nullable|string|max:255',
            'phone'    => 'required_if:address_type,new|nullable|regex:/^[0-9]{10,12}$/',
            'email'    => 'required_if:address_type,new|nullable|email|max:255',
            'address'  => 'required_if:address_type,new|nullable|string|max:255',
            'province' => 'required_if:address_type,new|nullable|string|max:100',
            'district' => 'required_if:address_type,new|nullable|string|max:100',
            'ward'     => 'required_if:address_type,new|nullable|string|max:100',
        ], [
            'shipping_type.required' => 'Vui lòng chọn hình thức giao hàng.',
            'shipping_type.in' => 'Hình thức giao hàng không hợp lệ với sản phẩm trong giỏ.',
            'delivery_slot.required' => 'Vui lòng chọn khung giờ nhận hàng.',
            'delivery_slot.in' => 'Khung giờ đã hết hạn hoặc không hợp lệ. Vui lòng chọn lại.',
            'saved_address_id.required_if' => 'Vui lòng chọn địa chỉ giao hàng.',
            'name.required_if' => 'Vui lòng nhập họ tên người nhận.',
            'phone.required_if'    => 'Vui lòng nhập số điện thoại.',
            'phone.regex'          => 'Số điện thoại không hợp lệ (10–12 số).',
            'email.required_if'    => 'Vui lòng nhập địa chỉ email.',
            'email.email'          => 'Email không đúng định dạng.',
            'address.required_if'  => 'Vui lòng nhập địa chỉ cụ thể.',
            'province.required_if' => 'Vui lòng chọn Tỉnh/Thành phố.',
            'district.required_if' => 'Vui lòng chọn Quận/Huyện.',
            'ward.required_if' => 'Vui lòng chọn Phường/Xã.',
        ]);

        // địa chỉ đã lưu thuộc về user
        if ($request->address_type === 'saved') {
            $owned = UserAddress::where('user_id', $user->id)->where('id', $request->saved_address_id)->exists();
            if (!$owned) {
                throw ValidationException::withMessages(['saved_address_id' => 'Địa chỉ không hợp lệ.']);
            }
        }

        [$shippingName, $shippingPhone, $shippingAddress] = $this->resolveShipping($request, $user);

        //  địa chỉ mới, ngược lại dùng email tài khoản
        $shippingEmail = $request->address_type === 'new' ? $request->email : $user->email;

        $subtotal = 0;
        foreach ($cartItems as $item) {
            if (!$item->product || $item->product->is_active == false || $item->product->quantity < $item->quantity) {
                throw ValidationException::withMessages([
                    'cart' => 'Sản phẩm "' . ($item->product->name ?? 'N/A') . '" đã hết hàng hoặc ngừng bán.',
                ]);
            }
            $price = $item->product->sale_price ?? $item->product->price;
            $subtotal += $price * $item->quantity;
        }

        $shippingType = $request->shipping_type;
        $shippingFee = DeliveryService::shippingFee($shippingType, $subtotal);

        if ($shippingType === 'express') {
            $deliverySlot = 'express-2h';
        } elseif ($deliveryOptions['requires_slot']) {
            $deliverySlot = $request->delivery_slot;
            if (!DeliveryService::isValidFreshSlot($deliverySlot)) {
                throw ValidationException::withMessages([
                    'delivery_slot' => 'Khung giờ đã hết hạn hoặc không hợp lệ. Vui lòng chọn lại.',
                ]);
            }
        } else {
            $deliverySlot = null;
        }

        $discountAmount = 0;
        $coupon = null;
        $couponCode = null;
        if ($request->filled('coupon_code')) {
            $couponResult = CouponService::validate($request->coupon_code, $subtotal, $user->id);
            if (!$couponResult['success']) {
                throw ValidationException::withMessages(['coupon_code' => $couponResult['message']]);
            }
            $discountAmount = $couponResult['discount'];
            $coupon = $couponResult['coupon'];
            $couponCode = $coupon->code;
        }

        $totalAmount = max(0, $subtotal - $discountAmount + $shippingFee);

        $note = $request->note;

        $order = DB::transaction(function () use (
            $user,
            $cartItems,
            $subtotal,
            $discountAmount,
            $couponCode,
            $coupon,
            $shippingFee,
            $shippingType,
            $deliverySlot,
            $totalAmount,
            $shippingName,
            $shippingPhone,
            $shippingEmail,
            $shippingAddress,
            $note,
            $request,
        ) {
            $order = Order::create([
                'user_id' => $user->id,
                'order_code' => 'OR' . now()->format('dmy') . rand(1000, 9999),
                'subtotal' => $subtotal,
                'discount_amount' => (string) $discountAmount,
                'coupon_code' => $couponCode,
                'shipping_fee' => $shippingFee,
                'shipping_type' => $shippingType,
                'delivery_slot' => $deliverySlot,
                'shipping_name' => $shippingName,
                'shipping_phone' => $shippingPhone,
                'shipping_email' => $shippingEmail,
                'shipping_address' => $shippingAddress,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'note' => $note,
            ]);

            foreach ($cartItems as $item) {
                $product = $item->product;
                $unitPrice = ($product->sale_price && $product->sale_price < $product->price)
                    ? $product->sale_price
                    : $product->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_image' => $product->image_url,
                    'quantity' => $item->quantity,
                    'unit_price' => $unitPrice,
                ]);

                $product->decrement('quantity', $item->quantity);
            }

            $isVnPay = $request->payment_method === 'vnpay';

            OrderPayment::create([
                'order_id' => $order->id,
                'payment_method' => $isVnPay ? 'VNPay' : 'COD',
                'amount' => $totalAmount,
                'payment_status' => 'pending',
            ]);

            OrderStatusLogs::create([
                'order_id' => $order->id,
                'old_status' => 'new',
                'new_status' => 'pending',
                'note' => $isVnPay ? 'Khách đặt hàng — chờ thanh toán VNPay' : 'Khách hàng đặt hàng (COD)',
            ]);

            if ($coupon) {
                CouponUsage::create([
                    'coupon_id' => $coupon->id,
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'discount_amount' => $discountAmount,
                ]);
            }

            ClientCart::clearUserCart($user);

            return $order;
        });

        // VNPay 
        if ($request->payment_method === 'vnpay') {
            $payUrl = VnPayService::createPaymentUrl($order, $request->ip() ?? '127.0.0.1');

            return redirect()->away($payUrl);
        }

        // COD
        $this->sendOrderConfirmation($order, $shippingEmail);

        return redirect()
            ->route('checkout.success', $order)
            ->with('success', 'Đặt hàng thành công!');
    }

    private function sendOrderConfirmation(Order $order, ?string $email = null): void
    {
        try {
            $order->loadMissing(['orderItems', 'orderPayment', 'user']);
            $email = $email ?? $order->user?->email;
            if ($email) {
                Mail::to($email)->send(new OrderConfirmationMail($order));
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function success(Order $order)
    {
        // Cho phép xem nếu là chủ đơn (đã login) hoặc vừa từ VNPay return về
        if (Auth::check()) {
            abort_unless($order->user_id === Auth::id(), 403);
        } else {
            return redirect()->route('login')
                ->with('warning', 'Vui lòng đăng nhập để xem đơn hàng.')
                ->with('url.intended', route('checkout.success', $order));
        }

        $order->load(['orderItems', 'orderPayment', 'user']);

        return view('clients.pages.order_success', compact('order'));
    }

    private function resolveShipping(Request $request, $user): array
    {
        if ($request->address_type === 'saved') {
            $address = UserAddress::where('user_id', $user->id)
                ->where('id', $request->saved_address_id)
                ->firstOrFail();

            $fullAddress = implode(', ', array_filter([
                $address->street_address,
                $address->ward,
                $address->district,
                $address->province,
            ]));

            return [
                $address->receiver_name ?: $user->name,
                $address->receiver_phone ?: ($user->phone ?? ''),
                $fullAddress,
            ];
        }

        $fullAddress = implode(', ', array_filter([
            $request->address,
            $request->ward,
            $request->district,
            $request->province,
        ]));

        return [
            $request->name,
            $request->phone,
            $fullAddress,
        ];
    }
}
