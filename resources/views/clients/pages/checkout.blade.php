@extends('layouts.client')

@section('title', 'Thanh toán')
@section('breadcrumb', 'Thanh toán')
@section('content')

<section class="checkout spad">
    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="checkout__form">
            <h4>Thông tin giao hàng</h4>
            <form action="{{ route('checkout.post') }}" method="POST" id="checkout-form">
                @csrf
                <div class="row">
                    <div class="col-lg-7 col-md-6">
                        <div class="address-type-selection">
                            <label class="address-type-label">
                                <input type="radio" name="address_type" value="saved"
                                    {{ old('address_type', $addresses->count() > 0 ? 'saved' : 'new') === 'saved' ? 'checked' : '' }}
                                    {{ $addresses->count() == 0 ? 'disabled' : '' }}>
                                <span>Địa chỉ đã lưu</span>
                            </label>
                            <label class="address-type-label">
                                <input type="radio" name="address_type" value="new"
                                    {{ old('address_type', $addresses->count() == 0 ? 'new' : 'saved') === 'new' ? 'checked' : '' }}>
                                <span>Địa chỉ mới</span>
                            </label>
                        </div>

                        <div id="saved-address-select" class="checkout__input"
                             style="{{ old('address_type', $addresses->count() > 0 ? 'saved' : 'new') === 'new' ? 'display:none;' : '' }}">
                            <p>Chọn địa chỉ <span>*</span></p>
                            <select name="saved_address_id" class="form-control">
                                @if($addresses->count() == 0)
                                    <option value="">-- Bạn chưa có địa chỉ đã lưu --</option>
                                @else
                                    @foreach($addresses as $address)
                                        <option value="{{ $address->id }}"
                                            @selected(old('saved_address_id', $address->is_default ? $address->id : null) == $address->id)>
                                            {{ $address->street_address }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div id="new-address-form"
                             style="{{ old('address_type', $addresses->count() == 0 ? 'new' : 'saved') === 'new' ? '' : 'display:none;' }}">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Họ tên người nhận <span>*</span></p>
                                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                               placeholder="Nhập họ tên" autocomplete="name">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Số điện thoại <span>*</span></p>
                                        <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                                               placeholder="10–12 chữ số" autocomplete="tel" pattern="[0-9]{10,12}">
                                    </div>
                                </div>
                            </div>

                            <div class="checkout__input">
                                <p>Địa chỉ cụ thể <span>*</span></p>
                                <input type="text" name="address" value="{{ old('address') }}"
                                       placeholder="Số nhà, tên đường..." autocomplete="street-address">
                            </div>

                            <div class="row address-location-fields"
                                 data-district="{{ old('district') }}"
                                 data-ward="{{ old('ward') }}">
                                <div class="col-lg-4">
                                    <div class="checkout__input">
                                        <p>Tỉnh/Thành phố <span>*</span></p>
                                        <select name="province" class="address-province native-address-select">
                                            <option value="">Chọn Tỉnh/Thành phố</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="checkout__input">
                                        <p>Quận/Huyện <span>*</span></p>
                                        <select name="district" class="address-district native-address-select" disabled>
                                            <option value="">Chọn Quận/Huyện</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="checkout__input">
                                        <p>Phường/Xã <span>*</span></p>
                                        <select name="ward" class="address-ward native-address-select" disabled>
                                            <option value="">Chọn Phường/Xã</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Ngày giao + khung giờ --}}
                        <div class="checkout__shipping mt-4">
                            <h4>Thời gian nhận hàng <span class="text-danger">*</span></h4>
                            @if(!empty($deliveryOptions['message']))
                                <p class="text-muted" style="font-size:13px;">{{ $deliveryOptions['message'] }}</p>
                            @endif

                            @if($deliveryOptions['conflict'])
                                <div class="alert alert-warning">
                                    Không thể đặt đơn với giỏ hàng hiện tại. Vui lòng điều chỉnh giỏ hàng.
                                </div>
                            @elseif($deliveryOptions['requires_slot'])
                                <input type="hidden" name="shipping_type" value="standard">

                                <div class="checkout__payment__method mt-2">
                                    <label class="payment-method-label {{ old('delivery_day', $defaultDeliveryDay) === 'today' ? 'active' : '' }} {{ !$slotGroups['today']['available'] ? 'is-disabled' : '' }}">
                                        <input type="radio" name="delivery_day" value="today"
                                            {{ !$slotGroups['today']['available'] ? 'disabled' : '' }}
                                            {{ old('delivery_day', $defaultDeliveryDay) === 'today' && $slotGroups['today']['available'] ? 'checked' : '' }}>
                                        <div class="payment-method-text">
                                            <h6>Giao hôm nay</h6>
                                            <p>
                                                @if($slotGroups['today']['available'])
                                                    {{ \Carbon\Carbon::parse($slotGroups['today']['date'])->format('d/m/Y') }}
                                                @else
                                                    Hết khung / sau {{ $cutoffHour }}h
                                                @endif
                                            </p>
                                        </div>
                                    </label>
                                    <label class="payment-method-label {{ old('delivery_day', $defaultDeliveryDay) === 'tomorrow' || !$slotGroups['today']['available'] ? 'active' : '' }}">
                                        <input type="radio" name="delivery_day" value="tomorrow"
                                            {{ old('delivery_day', $defaultDeliveryDay) === 'tomorrow' || !$slotGroups['today']['available'] ? 'checked' : '' }}>
                                        <div class="payment-method-text">
                                            <h6>Giao ngày mai</h6>
                                            <p>{{ \Carbon\Carbon::parse($slotGroups['tomorrow']['date'])->format('d/m/Y') }}</p>
                                        </div>
                                    </label>
                                </div>

                                <div class="checkout__input mt-3">
                                    <p>Khung giờ <span>*</span></p>
                                    <select name="delivery_slot" id="delivery_slot"
                                            data-groups='@json($slotGroups)'
                                            data-old="{{ old('delivery_slot') }}">
                                        <option value="">-- Chọn khung giờ --</option>
                                    </select>
                                    <small class="text-muted d-block mt-2">
                                        Giờ chốt đơn {{ $cutoffHour }}h: sau mốc này chỉ còn giao ngày mai.
                                    </small>
                                </div>
                            @elseif($deliveryOptions['allow_standard'])
                                <input type="hidden" name="shipping_type" value="standard">
                                <p class="text-muted mb-0">Hàng khô: giao trong 3–5 ngày làm việc.</p>
                            @elseif($deliveryOptions['allow_express'])
                                <input type="hidden" name="shipping_type" value="express">
                                <p class="text-muted mb-0">Giao nhanh trong 2 giờ (phụ phí {{ number_format(\App\Services\DeliveryService::EXPRESS_FEE, 0, ',', '.') }}₫).</p>
                            @endif
                        </div>

                        <div class="checkout__payment mt-4">
                            <h4>Phương thức thanh toán</h4>
                            <div class="checkout__payment__method mt-3">
                                <label class="payment-method-label {{ old('payment_method', 'cod') === 'cod' ? 'active' : '' }}">
                                    <input type="radio" name="payment_method" value="cod"
                                        {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }}>
                                    <div class="payment-method-text">
                                        <h6>Thanh toán khi nhận hàng (COD)</h6>
                                        <p>Thanh toán bằng tiền mặt khi nhận hàng</p>
                                    </div>
                                </label>
                                <label class="payment-method-label {{ old('payment_method') === 'vnpay' ? 'active' : '' }}">
                                    <input type="radio" name="payment_method" value="vnpay"
                                        {{ old('payment_method') === 'vnpay' ? 'checked' : '' }}>
                                    <div class="payment-method-text">
                                        <h6>VNPay</h6>
                                        <p>Thanh toán online qua cổng VNPay (ATM / QR / thẻ)</p>
                                        <small class="text-muted">
                                            Sandbox: chọn ngân hàng <b>NCB</b> —
                                            thẻ <code>9704198526191432198</code>, tên NGUYEN VAN A, phát hành 07/15, OTP 123456
                                        </small>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="checkout__note mt-4">
                            <h4>Ghi chú đơn hàng (tuỳ chọn)</h4>
                            <div class="checkout__input mt-3">
                                <textarea name="note" placeholder="Ghi chú giao hàng...">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5 col-md-6">
                        <div class="checkout__order">
                            <h4>Đơn hàng của bạn</h4>
                            <div class="checkout__order__products">Sản phẩm <span>Tổng</span></div>
                            <ul>
                                @foreach($cartItems as $item)
                                @php
                                    $price = $item->product->sale_price && $item->product->sale_price < $item->product->price
                                        ? $item->product->sale_price
                                        : $item->product->price;
                                    $mode = $item->product->delivery_mode ?? 'both';
                                @endphp
                                <li>
                                    {{ $item->product->name }} (x{{ $item->quantity }})
                                    <small class="d-block text-muted">
                                        @if($mode === 'express') Chỉ giao nhanh 2 giờ
                                        @elseif($mode === 'standard') Hàng khô · 3–5 ngày
                                        @else Hàng tươi · theo khung giờ
                                        @endif
                                    </small>
                                    <span>{{ number_format($price * $item->quantity, 0, ',', '.') }}₫</span>
                                </li>
                                @endforeach
                            </ul>

                            <div class="checkout__coupon mt-3 mb-3">
                                <p>Mã giảm giá</p>
                                <div class="d-flex" style="gap:8px;">
                                    <input type="text" name="coupon_code" id="coupon_code"
                                           value="{{ old('coupon_code') }}" placeholder="Nhập mã" style="flex:1;">
                                    <button type="button" id="btn-apply-coupon" class="site-btn rounded-btn" style="padding:10px 16px;">ÁP DỤNG</button>
                                </div>
                                <small id="coupon-message" class="d-block mt-2"></small>
                                <input type="hidden" id="coupon_discount" value="0">
                            </div>

                            <div class="checkout__order__subtotal">Tạm tính <span id="txt-subtotal" data-value="{{ $subtotal }}">{{ number_format($subtotal, 0, ',', '.') }}₫</span></div>
                            <div class="checkout__order__shipping">Giảm giá <span id="txt-discount">0₫</span></div>
                            <div class="checkout__order__shipping">Phí vận chuyển <span id="txt-shipping" data-standard="{{ $shippingFee }}" data-express="{{ \App\Services\DeliveryService::EXPRESS_FEE }}">
                                {{ $shippingFee == 0 ? 'Miễn phí' : number_format($shippingFee, 0, ',', '.') . '₫' }}
                            </span></div>
                            <div class="checkout__order__total">Tổng cộng <span id="txt-total">{{ number_format($subtotal + $shippingFee, 0, ',', '.') }}₫</span></div>

                            <button type="submit" class="site-btn rounded-btn" {{ $deliveryOptions['conflict'] ? 'disabled' : '' }}>
                                ĐẶT HÀNG
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection
