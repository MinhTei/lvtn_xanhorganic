<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusLogs;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->get();
        $addresses = Auth::user()->addresses ?? collect();
        $statusLabels = Order::STATUS_LABELS;
        $statusColors = [
            'pending' => '#f59e0b',
            'processing' => '#3b82f6',
            'shipped' => '#8b5cf6',
            'delivered' => '#22c55e',
            'cancelled' => '#ef4444',
        ];

        return view('clients.pages.account', compact(
            'addresses',
            'orders',
            'statusLabels',
            'statusColors'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|regex:/^[0-9]{10,12}$/',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Bắt buộc nhập họ tên',
            'name.max' => 'Họ tên tối đa 255 kí tự',
            'phone.required' => 'Bắt buộc nhập số điện thoại',
            'phone.regex' => 'Số điện thoại không hợp lệ',
            'avatar.image' => 'File upload phải là hình ảnh',
            'avatar.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg, gif',
            'avatar.max' => 'Dung lượng ảnh tối đa 2MB'
        ]);
        $user = User::find(Auth::id());
        $user->name = $request->name;
        $user->phone = $request->phone;

        // if ($request->hasFile('avatar')) {
        //     // Xóa ảnh cũ nếu có và không phải ảnh mặc định (nếu bạn có set mặc định cứng)
        //     if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
        //         Storage::disk('public')->delete($user->avatar);
        //     }
        //     // Lưu ảnh mới vào thư mục storage/app/public/avatars
        //     $avatarPath = $request->file('avatar')->store('avatars', 'public');
        //     $user->avatar = $avatarPath;
        // }

        $user->save();
        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password'=>'required|min:6',
            'new_password'=> 'required|min:6',
            "new_password_confirm"=> 'required|min:6|same:new_password',

        ],[
            'current_password.required'=> 'Bắt buộc nhập mật khẩu hiện tại',
            'current_password.min'=> 'Mật khẩu hiện tại tối thiểu 6 kí tự',
            'new_password.required'=> 'Bắt buộc nhập mật khẩu mới',
            'new_password.min'=> 'Mật khẩu mới tối thiểu 6 kí tự',
            'new_password_confirm.required'=> 'Bắt buộc nhập xác nhận mật khẩu',
            'new_password_confirm.min'=> 'Xác nhận mật khẩu tối thiểu 6 kí tự',
            'new_password_confirm.same'=> 'Xác nhận mật khẩu không khớp',
        ]);

        $user = User::find(Auth::id());
        
        // Dùng Hash::check để so sánh mật khẩu người dùng nhập với mật khẩu đã mã hóa trong DB
        if (Hash::check($request->current_password, $user->password)) {
            // Mã hóa mật khẩu mới trước khi lưu
            $user->password =Hash::make($request->new_password);
            $user->save();
            return redirect()->back()->with('success', 'Cập nhật mật khẩu thành công!');
        } else {
            return redirect()->route('account')->withFragment('settings')->with('error', 'Mật khẩu hiện tại không đúng!');
        }
        

    }

    public function addAddress(Request $request)
    {
        $data = $this->validateAddress($request);

        // Nếu chọn mặc định -> bỏ mặc định tất cả địa chỉ khác
        if ($data['is_default']) {
            UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        // Nếu chưa có địa chỉ nào -> tự động đặt làm mặc định
        if (!$data['is_default'] && UserAddress::where('user_id', Auth::id())->count() === 0) {
            $data['is_default'] = true;
        }

        UserAddress::create(array_merge($data, ['user_id' => Auth::id()]));

        return redirect()->route('account')->withFragment('addresses')->with('success', 'Thêm địa chỉ thành công!');
    }

    public function updateAddress(Request $request, UserAddress $address)
    {
        abort_unless($address->user_id === Auth::id(), 403);

        $data = $this->validateAddress($request);

        // Nếu chọn mặc định -> bỏ mặc định tất cả địa chỉ khác
        if ($data['is_default']) {
            UserAddress::where('user_id', Auth::id())
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update($data);

        return redirect()->route('account')->withFragment('addresses')->with('success', 'Cập nhật địa chỉ thành công!');
    }

    public function destroyAddress(UserAddress $address)
    {
        abort_unless($address->user_id === Auth::id(), 403);

        $address->delete();

        return back()->with('success', 'Xóa địa chỉ thành công!');
    }

    public function setDefaultAddress(UserAddress $address)
    {
        abort_unless($address->user_id === Auth::id(), 403);

        UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Đã đặt làm địa chỉ mặc định.');
    }

    // Hàm dùng chung để validate form địa chỉ
    private function validateAddress(Request $request): array
    {
        $validated = $request->validate([
            'province'       => 'required|string|max:100',
            'district'       => 'required|string|max:100',
            'ward'           => 'required|string|max:100',
            'street_address' => 'required|string|max:255',
            'is_default'     => 'nullable|boolean',
        ], [
            'province.required'       => 'Vui lòng chọn Tỉnh/Thành phố.',
            'district.required'       => 'Vui lòng chọn Quận/Huyện.',
            'ward.required'           => 'Vui lòng chọn Phường/Xã.',
            'street_address.required' => 'Vui lòng nhập địa chỉ cụ thể.',
        ]);

        $user = Auth::user();

        // Lấy tên và SĐT từ tài khoản thay vì nhập tay
        $validated['receiver_name']  = $user->name;
        $validated['receiver_phone'] = $user->phone ?? '';
        $validated['is_default']     = $request->boolean('is_default');

        return $validated;
    }
    public function showOrderDetail($order)
    {
        $order = Order::with(['orderItems.product', 'orderPayment', 'productReviews'])
            ->findOrFail($order);

        abort_unless($order->user_id === Auth::id(), 403);

        return view('clients.pages.order_detail', compact('order'));
    }

    public function showOrder()
    {
        return redirect()->route('account')->withFragment('orders');
    }

    public function cancelOrder(Request $request, Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        // Khách chỉ hủy đúng luồng: pending / processing → cancelled
        if (!$order->canTransitionTo('cancelled')) {
            return back()->with('error', 'Đơn hàng này không thể hủy (chỉ hủy khi đang chờ xác nhận hoặc đang xử lý).');
        }

        $reasonSelect = $request->input('cancel_reason_select', 'Thay đổi ý định mua hàng');
        $reason = $reasonSelect === 'other'
            ? ($request->input('cancel_reason') ?: 'Khác')
            : $reasonSelect;

        DB::transaction(function () use ($order, $reason) {
            $oldStatus = $order->status;
            $order->load('orderItems.product');

            foreach ($order->orderItems as $item) {
                if ($item->product) {
                    $item->product->increment('quantity', $item->quantity);
                }
            }

            $order->update(['status' => 'cancelled']);

            OrderStatusLogs::create([
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => 'cancelled',
                'note' => 'Khách hủy đơn: ' . $reason,
            ]);
        });

        return redirect()
            ->route('account.order.detail', $order)
            ->with('cancelMessage', 'Đã hủy đơn hàng thành công.');
    }

    public function storeReview(Request $request, Order $order, $product)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        abort_unless($order->status === 'delivered', 403);

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $belongsToOrder = $order->orderItems()->where('product_id', $product)->exists();
        abort_unless($belongsToOrder, 404);

        $exists = ProductReview::where([
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'product_id' => $product,
        ])->exists();

        if ($exists) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
        }

        ProductReview::create([
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'product_id' => $product,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'is_visible' => true,
        ]);

        return back()->with('reviewSuccess', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }

    public function updateReview(Request $request, Order $order, ProductReview $review)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        abort_unless($review->order_id === $order->id && $review->user_id === Auth::id(), 403);

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return back()->with('reviewSuccess', 'Đã cập nhật đánh giá.');
    }

    public function destroyReview(Order $order, ProductReview $review)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        abort_unless($review->order_id === $order->id && $review->user_id === Auth::id(), 403);

        $review->delete();

        return back()->with('reviewSuccess', 'Đã xóa đánh giá.');
    }
}
