@extends('layouts.client')

@section('title', 'Chi tiết đơn hàng ' . ($order->order_code ?? 'N/A'))
@section('breadcrumb', 'Chi tiết đơn hàng ' . ($order->order_code ?? 'N/A'))



@section('content')



@php
    $statusColor = '#666';
    $statusLabel = $order->status;

    switch ($order->status) {
        case 'pending':
            $statusColor = '#f59e0b';
            $statusLabel = 'Chờ xác nhận';
            break;
        case 'processing':
            $statusColor = '#06b6d4';
            $statusLabel = 'Đang xử lý';
            break;
        case 'shipped':
            $statusColor = '#06b6d4';
            $statusLabel = 'Đang giao';
            break;
        case 'delivered':
            $statusColor = '#22c55e';
            $statusLabel = 'Đã giao';
            break;
        case 'cancelled':
            $statusColor = '#ef4444';
            $statusLabel = 'Đã hủy';
            break;
    }

    $payment = $order->orderPayment;
    $paymentStatus = $payment->payment_status ?? 'pending';
    $isVnPay = ($payment->payment_method ?? '') === 'VNPay';
    $isBankTransfer = $isVnPay; // giữ biến cũ nếu view còn dùng
@endphp

<section class="spad">
    <div class="container" style="max-width: 1200px;">

        @if(session('cancelMessage'))
            <div class="od-alert-success">
                <strong>Thành công:</strong> {{ session('cancelMessage') }}
            </div>
        @endif
        
        @if(session('reviewSuccess'))
            <div class="od-alert-success" style="background: #ecfdf5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #10b981;">
                <strong>Thành công:</strong> {{ session('reviewSuccess') }}
            </div>
        @endif

        <h3 style="font-weight: 700; margin-bottom: 30px; color: #1c1c1c;">Đơn hàng #{{ $order->order_code }}</h3>

        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="od-box">
                    <h4 class="od-box-title">
                        <i class="fa fa-info-circle od-box-icon large"></i>
                        Thông tin chung
                    </h4>

                    <div style="display: grid; gap: 12px;">
                        <div class="od-detail-row">
                            <span class="od-detail-label">Mã đơn hàng:</span>
                            <span class="od-detail-value highlight">{{ $order->order_code }}</span>
                        </div>

                        <div class="od-detail-row">
                            <span class="od-detail-label">Ngày đặt:</span>
                            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>

                        <div class="od-detail-row">
                            <span class="od-detail-label">Trạng thái:</span>
                            <span style="display: inline-block; width: fit-content;">
                                <span class="od-status-badge" style="background: {{ $statusColor }}15; color: {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </span>
                        </div>

                        <div class="od-detail-row">
                            <span class="od-detail-label">Phương thức thanh toán:</span>
                            <span>
                                @if($isVnPay)
                                    VNPay
                                @else
                                    Thanh toán khi nhận hàng (COD)
                                @endif
                            </span>
                        </div>

                        <div class="od-detail-row">
                            <span class="od-detail-label">Tình trạng thanh toán:</span>
                            <span>
                                @if($paymentStatus === 'completed')
                                    Đã thanh toán
                                @elseif($paymentStatus === 'failed')
                                    Thanh toán thất bại / hủy
                                @else
                                    Chưa thanh toán
                                @endif
                                @if($isVnPay && !empty($payment->transaction_id))
                                    · GD: {{ $payment->transaction_id }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="od-box">
                    <h4 class="od-box-title">
                        <i class="fa fa-truck od-box-icon"></i>
                        Thông tin giao hàng
                    </h4>

                    <div style="display: grid; gap: 12px;">
                        <div class="od-detail-row">
                            <span class="od-detail-label">Người nhận:</span>
                            <span class="od-detail-value">{{ $order->shipping_name }}</span>
                        </div>

                        <div class="od-detail-row">
                            <span class="od-detail-label">Số điện thoại:</span>
                            <span>{{ $order->shipping_phone }}</span>
                        </div>

                        <div class="od-detail-row">
                            <span class="od-detail-label">Địa chỉ:</span>
                            <span>{{ $order->shipping_address }}</span>
                        </div>

                        <div class="od-detail-row">
                            <span class="od-detail-label">Hình thức giao:</span>
                            <span>
                                @if(($order->shipping_type ?? 'standard') === 'express')
                                    Giao nhanh trong 2 giờ
                                @elseif($order->delivery_slot)
                                    Theo khung giờ · {{ \App\Services\DeliveryService::labelForSlot($order->delivery_slot) }}
                                @else
                                    Giao thường (3–5 ngày)
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="od-box">
                    <h4 class="od-box-title">
                        <i class="fa fa-shopping-bag od-box-icon"></i>
                        Sản phẩm đã mua
                    </h4>

                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        @foreach($order->orderItems as $item)
                            @php
                                $isReviewed = $order->productReviews->contains('product_id', $item->product_id);
                            @endphp
                            <div class="od-product-item" style="position: relative;">
                                <img src="{{ $item->product_image ? (str_starts_with($item->product_image, 'http') ? $item->product_image : asset($item->product_image)) : 'https://placehold.co/80x80?text=SP' }}" alt="{{ $item->product_name }}" class="od-product-img">

                                <div class="od-product-info">
                                    <h5 class="od-product-name">
                                        @if($item->product)
                                            <a href="{{ route('product.detail', $item->product->slug) }}" style="color: inherit;">{{ $item->product_name }}</a>
                                        @else
                                            {{ $item->product_name }}
                                        @endif
                                    </h5>
                                    <p class="od-product-meta">{{ number_format($item->unit_price, 0, ',', '.') }}₫ <span>x</span> {{ $item->quantity }}</p>
                                </div>

                                <div class="od-product-total" style="display: flex; flex-direction: column; align-items: flex-end; gap: 10px;">
                                    <h5>{{ number_format($item->subtotal, 0, ',', '.') }}₫</h5>
                                    @if($order->status === 'delivered')
                                        @if($isReviewed)
                                            @php
                                                $review = $order->productReviews->where('product_id', $item->product_id)->first();
                                            @endphp
                                            <div style="display: flex; gap: 5px;">
                                                <button type="button" class="site-btn od-btn btn-review" 
                                                    style="padding: 5px 10px; font-size: 12px; min-width: max-content; background-color: #f39c12; border:none; color: white;"
                                                    data-toggle="modal" data-target="#reviewModal"
                                                    data-product-id="{{ $item->product_id }}"
                                                    data-product-name="{{ $item->product_name }}"
                                                    data-action="{{ route('order-detail.review.update', ['order' => $order->id, 'review' => $review->id]) }}"
                                                    data-method="PUT"
                                                    data-rating="{{ $review->rating }}"
                                                    data-comment="{{ $review->comment }}">
                                                    Sửa đánh giá
                                                </button>
                                                <form method="POST" action="{{ route('order-detail.review.destroy', ['order' => $order->id, 'review' => $review->id]) }}" onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?');" style="margin: 0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="site-btn od-btn" style="padding: 5px 10px; font-size: 12px; min-width: max-content; background-color: #dc3545; border:none; color: white;">
                                                        Xóa
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <button type="button" class="site-btn od-btn od-btn-dark btn-review" 
                                                style="padding: 5px 15px; font-size: 12px; min-width: max-content;"
                                                data-toggle="modal" data-target="#reviewModal"
                                                data-product-id="{{ $item->product_id }}"
                                                data-product-name="{{ $item->product_name }}"
                                                data-action="{{ route('order-detail.review', ['order' => $order->id, 'product' => $item->product_id]) }}"
                                                data-method="POST"
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
                <div class="od-box summary-box">
                    <h4 class="od-box-title" style="margin-bottom: 20px;">Tóm tắt thanh toán</h4>

                    <div class="od-summary-section">
                        <div class="od-summary-row">
                            <span class="od-summary-label">Tạm tính</span>
                            <span class="od-summary-value">{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
                        </div>

                        @if($order->discount_amount > 0)
                        <div class="od-summary-row discount">
                            <span class="od-summary-label">Giảm giá</span>
                            <span class="od-summary-value">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>
                        </div>
                        @endif

                        <div class="od-summary-row">
                            <span class="od-summary-label">Phí vận chuyển</span>
                            <span class="od-summary-value">{{ $order->shipping_fee == 0 ? 'Miễn phí' : number_format($order->shipping_fee, 0, ',', '.') . '₫' }}</span>
                        </div>
                    </div>

                    <div class="od-summary-total">
                        <span>Tổng cộng</span>
                        <span class="od-summary-total-val">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
                    </div>

                    <div class="od-actions">
                        @if(in_array($order->status, ['pending', 'processing']))
                        <button type="button" class="site-btn od-btn od-btn-danger" data-toggle="modal" data-target="#cancelOrderModal">
                            Hủy đơn hàng
                        </button>
                        @endif

                        <a href="{{ route('account') }}#orders" class="site-btn od-btn od-btn-dark">
                            Quay lại tài khoản
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if(in_array($order->status, ['pending', 'processing']))
<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('order-detail.cancel', $order) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelOrderModalLabel">Lý do hủy đơn hàng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p style="margin-bottom: 15px;">Vui lòng cho chúng tôi biết lý do bạn muốn hủy đơn hàng này:</p>
                    
                    <div class="cancel-reasons-list" style="display: flex; flex-direction: column; gap: 10px;">
                        <label style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <input type="radio" name="cancel_reason_select" value="Thay đổi ý định mua hàng" checked onchange="toggleCustomReason()">
                            Thay đổi ý định mua hàng
                        </label>
                        <label style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <input type="radio" name="cancel_reason_select" value="Phí vận chuyển cao" onchange="toggleCustomReason()">
                            Phí vận chuyển cao
                        </label>
                        <label style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <input type="radio" name="cancel_reason_select" value="Thời gian giao hàng dự kiến quá lâu" onchange="toggleCustomReason()">
                            Thời gian giao hàng dự kiến quá lâu
                        </label>
                        <label style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <input type="radio" name="cancel_reason_select" value="Đặt nhầm sản phẩm/số lượng" onchange="toggleCustomReason()">
                            Đặt nhầm sản phẩm/số lượng
                        </label>
                        <label style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <input type="radio" name="cancel_reason_select" value="other" onchange="toggleCustomReason()">
                            Khác (Tự nhập)
                        </label>
                    </div>

                    <div class="form-group mt-3" id="custom_reason_group" style="display: none;">
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

<!-- Review Modal -->
@if($order->status === 'delivered')
<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="" id="reviewForm">
            @csrf
            <div id="methodContainer"></div>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Đánh giá sản phẩm: <span id="reviewProductName"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p>Vui lòng chọn số sao và để lại nhận xét của bạn về sản phẩm này.</p>
                    
                    <div class="review-rating" style="font-size: 24px; color: #ccc; cursor: pointer; margin-bottom: 15px;">
                        <i class="fa fa-star star-rate" data-val="1"></i>
                        <i class="fa fa-star star-rate" data-val="2"></i>
                        <i class="fa fa-star star-rate" data-val="3"></i>
                        <i class="fa fa-star star-rate" data-val="4"></i>
                        <i class="fa fa-star star-rate" data-val="5"></i>
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
        var radios = document.getElementsByName('cancel_reason_select');
        var customGroup = document.getElementById('custom_reason_group');
        var customInput = document.getElementById('cancel_reason');
        var selectedValue = '';
        
        for (var i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                selectedValue = radios[i].value;
                break;
            }
        }
        
        if (selectedValue === 'other') {
            customGroup.style.display = 'block';
            customInput.required = true;
        } else {
            customGroup.style.display = 'none';
            customInput.required = false;
        }
    }

    $(document).ready(function() {
        // Review Modal Logic
        $('.btn-review').on('click', function() {
            var productName = $(this).data('product-name');
            var actionUrl = $(this).data('action');
            var method = $(this).data('method');
            var rating = $(this).data('rating');
            var comment = $(this).data('comment');
            
            $('#reviewProductName').text(productName);
            $('#reviewForm').attr('action', actionUrl);
            
            if (method === 'PUT') {
                $('#methodContainer').html('<input type="hidden" name="_method" value="PUT">');
            } else {
                $('#methodContainer').html('');
            }
            
            // Set form values
            $('#reviewRating').val(rating);
            $('#comment').val(comment);
            
            if (rating > 0) {
                $('#btnSubmitReview').prop('disabled', false);
                $('.star-rate').each(function() {
                    if ($(this).data('val') <= rating) {
                        $(this).css('color', '#ffc107'); // Gold
                    } else {
                        $(this).css('color', '#ccc'); // Gray
                    }
                });
            } else {
                $('.star-rate').css('color', '#ccc');
                $('#btnSubmitReview').prop('disabled', true);
            }
        });

        $('.star-rate').on('click', function() {
            var val = $(this).data('val');
            $('#reviewRating').val(val);
            $('#btnSubmitReview').prop('disabled', false);
            
            $('.star-rate').each(function() {
                if ($(this).data('val') <= val) {
                    $(this).css('color', '#ffc107'); // Gold
                } else {
                    $(this).css('color', '#ccc'); // Gray
                }
            });
        });
        
        // Hover effect for stars
        $('.star-rate').hover(
            function() {
                var hoverVal = $(this).data('val');
                var currentVal = $('#reviewRating').val();
                if (currentVal == 0) {
                    $('.star-rate').each(function() {
                        if ($(this).data('val') <= hoverVal) {
                            $(this).css('color', '#ffc107');
                        } else {
                            $(this).css('color', '#ccc');
                        }
                    });
                }
            },
            function() {
                var currentVal = $('#reviewRating').val();
                $('.star-rate').each(function() {
                    if ($(this).data('val') <= currentVal) {
                        $(this).css('color', '#ffc107');
                    } else {
                        $(this).css('color', '#ccc');
                    }
                });
            }
        );
    });
</script>
@endpush

@endsection
