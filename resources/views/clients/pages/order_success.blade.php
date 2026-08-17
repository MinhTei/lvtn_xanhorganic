@extends('layouts.client')

@php
    $pay = $order->orderPayment;
    $isPaymentIssue = $order->status === 'cancelled'
        || (($pay->payment_method ?? '') === 'VNPay' && ($pay->payment_status ?? '') === 'failed');
@endphp


@section('content')
<section class="order-success spad">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                @if($isPaymentIssue)
                    <div class="os-header is-failed">
                        <i class="fa fa-times-circle"></i>
                        <h2>Thanh toán không thành công</h2>
                        <p>
                            Bạn đã hủy hoặc thanh toán VNPay thất bại.
                            Đơn hàng <strong>{{ $order->order_code }}</strong> đã được hủy.
                            Sản phẩm đã được đưa lại vào <strong>giỏ hàng</strong> — bạn có thể đặt lại ngay.
                        </p>
                    </div>
                @else
                    <div class="os-header">
                        <i class="fa fa-check-circle"></i>
                        <h2>Đặt Hàng Thành Công!</h2>
                        <p>Cảm ơn bạn đã tin tưởng và mua sắm tại XanhOrganic</p>
                    </div>
                @endif

                <div class="os-box">
                    <h4><i class="fa fa-file-text-o"></i> Thông Tin Đơn Hàng</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <p class="label">Mã đơn hàng</p>
                            <p class="value green">{{ $order->order_code }}</p>
                            <p class="label">Tổng tiền</p>
                            <p class="value price">{{ number_format($order->final_amount, 0, ',', '.') }}₫</p>
                        </div>
                        <div class="col-md-6">
                            <p class="label">Ngày đặt hàng</p>
                            <p class="value">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p class="label">Trạng thái</p>
                            <p class="value">{{ $order->statusLabel() }}</p>
                            <p class="label mt-3">Thanh toán</p>
                            <p class="value">
                                {{ ($pay->payment_method ?? 'COD') === 'VNPay' ? 'VNPay' : 'COD' }} —
                                @if(($pay->payment_status ?? '') === 'completed') Đã thanh toán
                                @elseif(($pay->payment_status ?? '') === 'failed') Thất bại / đã hủy
                                @else Chờ thanh toán
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                @unless($isPaymentIssue)
                <div class="os-box is-shipping">
                    <h4><i class="fa fa-user-circle-o"></i> Thông Tin Nhận Hàng</h4>
                    <dl class="os-rows">
                        <div><dt>Người nhận</dt><dd>{{ $order->shipping_name }}</dd></div>
                        <div><dt>Điện thoại</dt><dd>{{ $order->shipping_phone }}</dd></div>
                        <div><dt>Email</dt><dd>{{ $order->shipping_email ?? 'N/A' }}</dd></div>
                        <div><dt>Địa chỉ</dt><dd>{{ $order->shipping_address }}</dd></div>
                        <div>
                            <dt>Giao hàng</dt>
                            <dd>
                                @if(($order->shipping_type ?? 'standard') === 'express')
                                    Giao nhanh trong 2 giờ
                                @elseif($order->delivery_slot)
                                    {{ \App\Services\DeliveryService::labelForSlot($order->delivery_slot) }}
                                @else
                                    Giao thường (3–5 ngày)
                                @endif
                            </dd>
                        </div>
                        @if(!empty($order->note))
                            <div class="note"><dt>Ghi chú</dt><dd>{{ $order->note }}</dd></div>
                        @endif
                    </dl>
                </div>

                <div class="os-table">
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
                                    <td class="text-left">{{ $item->product_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->unit_price, 0, ',', '.') }}₫</td>
                                    <td class="bold">{{ number_format($item->subtotal, 0, ',', '.') }}₫</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right">Tạm tính:</td>
                                <td>{{ number_format($order->subtotal, 0, ',', '.') }}₫</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right">Phí vận chuyển:</td>
                                <td>{{ $order->shipping_fee == 0 ? 'Miễn phí' : number_format($order->shipping_fee, 0, ',', '.') . '₫' }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Tổng cộng:</strong></td>
                                <td class="green bold big">{{ number_format($order->final_amount, 0, ',', '.') }}₫</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @endunless

                <div class="os-actions">
                    @if($isPaymentIssue)
                        <a href="{{ route('checkout') }}" class="site-btn">ĐẶT LẠI ĐƠN</a>
                        <a href="{{ route('cart') }}" class="site-btn is-outline">XEM GIỎ HÀNG</a>
                        <a href="{{ route('home') }}" class="site-btn is-dark">VỀ TRANG CHỦ</a>
                    @else
                        <a href="{{ route('account') }}#orders" class="site-btn is-outline">XEM ĐƠN HÀNG</a>
                        <a href="{{ route('products') }}" class="site-btn">TIẾP TỤC MUA SẮM</a>
                        <a href="{{ route('home') }}" class="site-btn is-dark">VỀ TRANG CHỦ</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
