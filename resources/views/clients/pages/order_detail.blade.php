@extends('layouts.client')

@section('title', 'Chi tiết đơn hàng ' . ($order->order_code ?? 'N/A'))
@section('breadcrumb', 'Chi tiết đơn hàng ' . ($order->order_code ?? 'N/A'))

@section('content')
@php
    $statusMap = [
        'pending' => ['#f59e0b', 'Chờ xác nhận'],
        'processing' => ['#06b6d4', 'Đã xác nhận'],
        'shipped' => ['#06b6d4', 'Đang giao'],
        'delivered' => ['#22c55e', 'Đã giao'],
        'cancelled' => ['#ef4444', 'Đã hủy'],
    ];
    [$statusColor, $statusLabel] = $statusMap[$order->status] ?? ['#666', $order->status];
    $payment = $order->orderPayment;
    $paymentStatus = $payment->payment_status ?? 'pending';
    $isVnPay = ($payment->payment_method ?? '') === 'VNPay';
@endphp

<section class="order-detail spad">
    <div class="container">
        @if(session('cancelMessage'))
            <div class="od-alert">{{ session('cancelMessage') }}</div>
        @endif
        @if(session('reviewSuccess'))
            <div class="od-alert">{{ session('reviewSuccess') }}</div>
        @endif

        <h3 class="od-heading">Đơn hàng #{{ $order->order_code }}</h3>

        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="od-box">
                    <h4><i class="fa fa-info-circle"></i> Thông tin chung</h4>
                    <dl class="od-rows">
                        <div><dt>Mã đơn hàng</dt><dd class="green">{{ $order->order_code }}</dd></div>
                        <div><dt>Ngày đặt</dt><dd>{{ $order->created_at->format('d/m/Y H:i') }}</dd></div>
                        <div>
                            <dt>Trạng thái</dt>
                            <dd>
                                <span class="status-badge" style="color: {{ $statusColor }}; background: {{ $statusColor }}22;">
                                    {{ $statusLabel }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt>Phương thức thanh toán</dt>
                            <dd>{{ $isVnPay ? 'VNPay' : 'Thanh toán khi nhận hàng (COD)' }}</dd>
                        </div>
                        <div>
                            <dt>Tình trạng thanh toán</dt>
                            <dd>
                                @if($paymentStatus === 'completed') Đã thanh toán
                                @elseif($paymentStatus === 'failed') Thanh toán thất bại / hủy
                                @else Chưa thanh toán
                                @endif
                                @if($isVnPay && !empty($payment->transaction_id))
                                    · GD: {{ $payment->transaction_id }}
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="od-box">
                    <h4><i class="fa fa-truck"></i> Thông tin giao hàng</h4>
                    <dl class="od-rows">
                        <div><dt>Người nhận</dt><dd>{{ $order->shipping_name }}</dd></div>
                        <div><dt>Số điện thoại</dt><dd>{{ $order->shipping_phone }}</dd></div>
                        <div><dt>Địa chỉ</dt><dd>{{ $order->shipping_address }}</dd></div>
                        <div>
                            <dt>Hình thức giao</dt>
                            <dd>
                                @if(($order->shipping_type ?? 'standard') === 'express')
                                    Giao nhanh trong 2 giờ
                                @elseif($order->delivery_slot)
                                    Theo khung giờ · {{ \App\Services\DeliveryService::labelForSlot($order->delivery_slot) }}
                                @else
                                    Giao thường (3–5 ngày)
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="od-box">
                    <h4><i class="fa fa-shopping-bag"></i> Sản phẩm đã mua</h4>
                    <div class="od-products">
                        @foreach($order->orderItems as $item)
                            @php $isReviewed = $order->productReviews->contains('product_id', $item->product_id); @endphp
                            <div class="od-item">
                                <img src="{{ $item->product_image ? (str_starts_with($item->product_image, 'http') ? $item->product_image : asset($item->product_image)) : 'https://placehold.co/80x80?text=SP' }}"
                                     alt="{{ $item->product_name }}">
                                <div class="info">
                                    <h5>
                                        @if($item->product)
                                            <a href="{{ route('product.detail', $item->product->slug) }}">{{ $item->product_name }}</a>
                                        @else
                                            {{ $item->product_name }}
                                        @endif
                                    </h5>
                                    <p>{{ number_format($item->unit_price, 0, ',', '.') }}₫ × {{ $item->quantity }}</p>
                                </div>
                                <div class="side">
                                    <strong>{{ number_format($item->subtotal, 0, ',', '.') }}₫</strong>
                                    @if($order->status === 'delivered')
                                        @if($isReviewed)
                                            <span class="review-done">Đã đánh giá</span>
                                            @if($item->product)
                                                <a href="{{ route('product.detail', $item->product->slug) }}#tabs-2" class="btn-sm-review">
                                                    Sửa trên trang SP
                                                </a>
                                            @endif
                                        @else
                                            <button type="button" class="btn-sm-dark btn-review"
                                                data-toggle="modal" data-target="#reviewModal"
                                                data-product-id="{{ $item->product_id }}"
                                                data-product-name="{{ $item->product_name }}"
                                                data-action="{{ route('order-detail.review', ['order' => $order->id, 'product' => $item->product_id]) }}"
                                                data-rating="0"
                                                data-comment="">
                                                Viết đánh giá
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12 mt-4 mt-lg-0">
                <div class="od-box is-summary">
                    <h4>Tóm tắt thanh toán</h4>
                    <ul class="od-summary">
                        <li>Tạm tính <span>{{ number_format($order->subtotal, 0, ',', '.') }}₫</span></li>
                        @if($order->discount_amount > 0)
                            <li class="discount">Giảm giá <span>-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span></li>
                        @endif
                        <li>Phí vận chuyển <span>{{ $order->shipping_fee == 0 ? 'Miễn phí' : number_format($order->shipping_fee, 0, ',', '.') . '₫' }}</span></li>
                    </ul>
                    <div class="od-total">
                        Tổng cộng
                        <span>{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
                    </div>
                    <div class="od-actions">
                        @if(in_array($order->status, ['pending', 'processing']))
                            <button type="button" class="site-btn btn-danger-block" data-toggle="modal" data-target="#cancelOrderModal">
                                Hủy đơn hàng
                            </button>
                        @endif
                        <a href="{{ route('account') }}#orders" class="site-btn btn-dark-block">Quay lại tài khoản</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if(in_array($order->status, ['pending', 'processing']))
<div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('order-detail.cancel', $order) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lý do hủy đơn hàng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Vui lòng cho chúng tôi biết lý do bạn muốn hủy đơn hàng này:</p>
                    <div class="cancel-reasons">
                        @foreach ([
                            'Thay đổi ý định mua hàng',
                            'Phí vận chuyển cao',
                            'Thời gian giao hàng dự kiến quá lâu',
                            'Đặt nhầm sản phẩm/số lượng',
                        ] as $i => $reason)
                            <label>
                                <input type="radio" name="cancel_reason_select" value="{{ $reason }}" {{ $i === 0 ? 'checked' : '' }} onchange="toggleCustomReason()">
                                {{ $reason }}
                            </label>
                        @endforeach
                        <label>
                            <input type="radio" name="cancel_reason_select" value="other" onchange="toggleCustomReason()">
                            Khác (Tự nhập)
                        </label>
                    </div>
                    <div class="form-group mt-3 is-hidden" id="custom_reason_group">
                        <textarea name="cancel_reason" id="cancel_reason" class="form-control" rows="3" placeholder="Nhập lý do hủy đơn của bạn..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger">Xác nhận hủy đơn</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@if($order->status === 'delivered')
<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="" id="reviewForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Đánh giá sản phẩm: <span id="reviewProductName"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p>Vui lòng chọn số sao và để lại nhận xét của bạn về sản phẩm này.</p>
                    <div class="review-stars-pick">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fa fa-star star-rate" data-val="{{ $i }}"></i>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="reviewRating" value="0" required>
                    <div class="form-group text-left">
                        <label for="comment">Nhận xét (Tùy chọn)</label>
                        <textarea name="comment" id="comment" class="form-control" rows="3" placeholder="Sản phẩm tuyệt vời, giao hàng nhanh..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success" id="btnSubmitReview" disabled>Gửi đánh giá</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@push('scripts')
<script>
    function toggleCustomReason() {
        var selected = document.querySelector('input[name="cancel_reason_select"]:checked');
        var customGroup = document.getElementById('custom_reason_group');
        var customInput = document.getElementById('cancel_reason');
        var isOther = selected && selected.value === 'other';
        customGroup.classList.toggle('is-hidden', !isOther);
        customInput.required = !!isOther;
    }

    function paintStars(val) {
        $('.star-rate').each(function () {
            $(this).toggleClass('on', $(this).data('val') <= val);
        });
    }

    $(document).ready(function () {
        $('.btn-review').on('click', function () {
            var rating = $(this).data('rating') || 0;
            $('#reviewProductName').text($(this).data('product-name'));
            $('#reviewForm').attr('action', $(this).data('action'));
            $('#reviewRating').val(rating);
            $('#comment').val($(this).data('comment') || '');
            $('#btnSubmitReview').prop('disabled', rating <= 0);
            paintStars(rating);
        });

        $('.star-rate').on('click', function () {
            var val = $(this).data('val');
            $('#reviewRating').val(val);
            $('#btnSubmitReview').prop('disabled', false);
            paintStars(val);
        });

        $('.star-rate').hover(
            function () {
                if ($('#reviewRating').val() == 0) paintStars($(this).data('val'));
            },
            function () {
                paintStars($('#reviewRating').val() || 0);
            }
        );
    });
</script>
@endpush
@endsection
