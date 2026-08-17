/* Checkout: địa chỉ, thanh toán, ngày giao + khung giờ, coupon */

$(document).ready(function () {
    function formatVnd(n) {
        return new Intl.NumberFormat('vi-VN').format(Math.max(0, Math.round(n))) + '₫';
    }

    function updateAddressType() {
        var addressType = $('input[name="address_type"]:checked').val();
        if (addressType === 'new') {
            $('#new-address-form').slideDown(200);
            $('#saved-address-select').slideUp(200);
        } else {
            $('#new-address-form').slideUp(200);
            $('#saved-address-select').slideDown(200);
        }
    }

    function fillDeliverySlots(keepOld) {
        var $select = $('#delivery_slot');
        if (!$select.length) return;

        var groups = $select.data('groups');
        if (!groups) return;

        var day = $('input[name="delivery_day"]:checked').val() || 'tomorrow';
        if (day === 'today' && (!groups.today || !groups.today.available)) {
            day = 'tomorrow';
            $('input[name="delivery_day"][value="tomorrow"]').prop('checked', true);
        }

        var slots = (groups[day] && groups[day].slots) ? groups[day].slots : {};
        var oldVal = keepOld ? ($select.data('old') || $select.val()) : '';
        $select.empty().append('<option value="">-- Chọn khung giờ --</option>');

        Object.keys(slots).forEach(function (key) {
            $select.append($('<option>', { value: key, text: slots[key] }));
        });

        if (oldVal && slots[oldVal]) {
            $select.val(oldVal);
        } else {
            $select.val('');
        }
        $select.data('old', '');
    }

    function recalcTotal() {
        var subtotal = parseFloat($('#txt-subtotal').data('value')) || 0;
        var discount = parseFloat($('#coupon_discount').val()) || 0;
        var $ship = $('#txt-shipping');
        var shipping = parseFloat($ship.data('standard')) || 0;
        if ($('input[name="shipping_type"]').val() === 'express') {
            shipping = parseFloat($ship.data('express')) || 45000;
        }

        $('#txt-discount').text(formatVnd(discount));
        $('#txt-shipping').text(shipping === 0 ? 'Miễn phí' : formatVnd(shipping));
        $('#txt-total').text(formatVnd(subtotal - discount + shipping));
    }

    $('input[name="address_type"]').on('change', updateAddressType);
    $(document).on('change', 'input[name="delivery_day"]', function () {
        fillDeliverySlots(false);
    });

    updateAddressType();
    fillDeliverySlots(true);
    recalcTotal();

    $('#btn-apply-coupon').on('click', function () {
        var code = $('#coupon_code').val();
        var $msg = $('#coupon-message');
        $msg.removeClass('text-success text-danger').text('Đang kiểm tra...');

        $.ajax({
            url: window.XanhOrganic.couponApplyUrl,
            type: 'POST',
            data: { code: code },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function (res) {
                $('#coupon_discount').val(res.discount || 0);
                $msg.addClass('text-success').text(res.message);
                recalcTotal();
            },
            error: function (xhr) {
                $('#coupon_discount').val(0);
                var message = (xhr.responseJSON && xhr.responseJSON.message)
                    ? xhr.responseJSON.message
                    : 'Mã không hợp lệ';
                $msg.addClass('text-danger').text(message);
                recalcTotal();
            }
        });
    });

    $('#checkout-form').on('submit', function (e) {
        if ($('#delivery_slot').length && !$('#delivery_slot').val()) {
            e.preventDefault();
            toastr.warning('Vui lòng chọn khung giờ nhận hàng.');
            return false;
        }

        var addressType = $('input[name="address_type"]:checked').val();
        if (addressType === 'new') {
            if (!$('input[name="name"]').val() || !$('input[name="phone"]').val() || !$('input[name="address"]').val()) {
                e.preventDefault();
                toastr.warning('Vui lòng điền đầy đủ thông tin địa chỉ mới.');
                return false;
            }
            if (!$('select[name="province"]').val() || !$('select[name="district"]').val() || !$('select[name="ward"]').val()) {
                e.preventDefault();
                toastr.warning('Vui lòng chọn đủ Tỉnh/Quận/Phường.');
                return false;
            }
        }
        if (addressType === 'saved' && !$('select[name="saved_address_id"]').val()) {
            e.preventDefault();
            toastr.warning('Vui lòng chọn địa chỉ đã lưu.');
            return false;
        }
    });
});
