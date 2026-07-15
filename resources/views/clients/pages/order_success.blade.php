@extends('layouts.client')

@php
    $pay = $order->orderPayment;
    // VNPay thất bại / đơn đã hủy vì không thanh toán
    $isPaymentIssue = $order->status === 'cancelled'
        || (($pay->payment_method ?? '') === 'VNPay' && ($pay->payment_status ?? '') === 'failed');
@endphp

@section('title', $isPaymentIssue ? 'Thanh toán không thành công' : 'Đặt hàng thành công')
@section('breadcrumb', $isPaymentIssue ? 'Thanh toán không thành công' : 'Đặt hàng thành công')

@section('content')

<section class="spad">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">

                @if(session('success') && !$isPaymentIssue)
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning">{{ session('warning') }}</div>
                @endif

                @if($isPaymentIssue)
                    <div class="os-header os-header--failed">
                        <i class="fa fa-times-circle os-icon os-icon--failed"></i>
                        <h2 class="os-title os-title--failed">Thanh toán không thành công</h2>
                        <p class="os-subtitle">
                            Bạn đã hủy hoặc thanh toán VNPay thất bại.
                            Đơn hàng <strong>{{ $order->order_code }}</strong> đã được hủy.
                            Sản phẩm đã được đưa lại vào <strong>giỏ hàng</strong> — bạn có thể đặt lại ngay.
                        </p>
                    </div>
                @else
                    <div class="os-header">
                        <i class="fa fa-check-circle os-icon"></i>
                        <h2 class="os-title">Đặt Hàng Thành Công!</h2>
                        <p class="os-subtitle">Cảm ơn bạn đã tin tưởng và mua sắm tại {{ config('app.name', 'XanhOrganic') }}</p>
                    </div>
                @endif

                <div class="os-box">
                    <h4 class="os-box-title">
                        <i class="fa fa-file-text-o os-box-icon"></i>
                        Thông Tin Đơn Hàng
                    </h4>
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <p class="os-detail-label">Mã đơn hàng:</p>
                            <p class="os-detail-value highlight">{{ $order->order_code }}</p>

                            <p class="os-detail-label">Tổng tiền:</p>
                            <p class="os-detail-value price">{{ number_format($order->final_amount, 0, ',', '.') }}₫</p>
                        </div>
                        <div class="col-md-6">
                            <p class="os-detail-label">Ngày đặt hàng:</p>
                            <p class="os-detail-value">{{ $order->created_at->format('d/m/Y H:i') }}</p>

                            <p class="os-detail-label">Trạng thái:</p>
                            <p class="os-detail-value" style="margin-bottom: 0;">{{ $order->statusLabel() }}</p>

                            <p class="os-detail-label mt-3">Thanh toán:</p>
                            <p class="os-detail-value" style="margin-bottom: 0;">
                                {{ ($pay->payment_method ?? 'COD') === 'VNPay' ? 'VNPay' : 'COD' }}
                                —
                                @if(($pay->payment_status ?? '') === 'completed')
                                    Đã thanh toán
                                @elseif(($pay->payment_status ?? '') === 'failed')
                                    Thất bại / đã hủy
                                @else
                                    Chờ thanh toán
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                @unless($isPaymentIssue)
                <div class="os-box shipping-box">
                    <h4 class="os-box-title">
                        <i class="fa fa-user-circle-o os-box-icon"></i>
                        Thông Tin Nhận Hàng
                    </h4>
                    <div>
                        <div class="os-shipping-row">
                            <span class="os-shipping-label">Người nhận:</span>
                            <span class="os-shipping-value">{{ $order->shipping_name }}</span>
                        </div>
                        <div class="os-shipping-row">
                            <span class="os-shipping-label">Điện thoại:</span>
                            <span class="os-shipping-value">{{ $order->shipping_phone }}</span>
                        </div>
                        <div class="os-shipping-row">
                            <span class="os-shipping-label">Email:</span>
                            <span>{{ $order->user->email ?? 'N/A' }}</span>
                        </div>
                        <div class="os-shipping-row">
                            <span class="os-shipping-label">Địa chỉ:</span>
                            <span>{{ $order->shipping_address }}</span>
                        </div>
                        <div class="os-shipping-row">
                            <span class="os-shipping-label">Giao hàng:</span>
                            <span>
                                @if(($order->shipping_type ?? 'standard') === 'express')
                                    Giao nhanh trong 2 giờ
                                @elseif($order->delivery_slot)
                                    {{ \App\Services\DeliveryService::labelForSlot($order->delivery_slot) }}
                                @else
                                    Giao thường (3–5 ngày)
                                @endif
                            </span>
                        </div>
                        @if(!empty($order->note))
                        <div class="os-note-row">
                            <span class="os-shipping-label">Ghi chú:</span>
                            <span style="font-style: italic;">{{ $order->note }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="os-table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th class="text-left">Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td class="text-left product-name">{{ $item->product_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price, 0, ',', '.') }}₫</td>
                                <td class="total-price">{{ number_format($item->subtotal, 0, ',', '.') }}₫</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right">Tạm tính:</td>
                                <td class="final-price">{{ number_format($order->subtotal, 0, ',', '.') }}₫</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right">Phí vận chuyển:</td>
                                <td class="final-price">
                                    {{ $order->shipping_fee == 0 ? 'Miễn phí' : number_format($order->shipping_fee, 0, ',', '.') . '₫' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Tổng cộng:</strong></td>
                                <td class="final-price" style="color: #7cac34; font-size: 18px;">
                                    <strong>{{ number_format($order->final_amount, 0, ',', '.') }}₫</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="os-next-steps">
                    <h4 class="os-box-title" style="margin-bottom: 10px;">
                        <i class="fa fa-bullhorn os-box-icon"></i>
                        Các Bước Tiếp Theo
                    </h4>
                    <ul class="os-next-steps-list">
                        <li>Chúng tôi sẽ xác nhận đơn hàng của bạn trong thời gian sớm nhất.</li>
                        <li>Đơn hàng sẽ được chuẩn bị và đóng gói cẩn thận.</li>
                        <li>Nhân viên giao hàng sẽ liên hệ với bạn trước khi giao.</li>
                        <li>Bạn có thể theo dõi trạng thái đơn hàng trong phần Tài khoản.</li>
                    </ul>
                </div>
                @endunless

                <div class="os-actions">
                    @if($isPaymentIssue)
                        <a href="{{ route('checkout') }}" class="site-btn">ĐẶT LẠI ĐƠN</a>
                        <a href="{{ route('cart') }}" class="site-btn os-btn-outline">XEM GIỎ HÀNG</a>
                        <a href="{{ route('home') }}" class="site-btn os-btn-dark">VỀ TRANG CHỦ</a>
                    @else
                        <a href="{{ route('account') }}#orders" class="site-btn os-btn-outline">XEM ĐƠN HÀNG</a>
                        <a href="{{ route('products') }}" class="site-btn">TIẾP TỤC MUA SẮM</a>
                        <a href="{{ route('home') }}" class="site-btn os-btn-dark">VỀ TRANG CHỦ</a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
