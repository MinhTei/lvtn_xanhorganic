$(function () {
    // Tăng / giảm số lượng trên trang giỏ — không cho vượt max (tồn kho)
    $(document).on('click', '.qtybtn-small', function () {
        var $input = $(this).siblings('input.ajax-cart-qty-input');
        var val = parseInt($input.val(), 10) || 1;
        var max = parseInt($input.attr('max'), 10) || 1;
        var min = parseInt($input.attr('min'), 10) || 1;

        var newVal = $(this).hasClass('inc')
            ? Math.min(val + 1, max)
            : Math.max(val - 1, min);

        // Bấm + khi đã max → cảnh báo
        if ($(this).hasClass('inc') && val >= max) {
            if (window.toastr) {
                toastr.warning('Chỉ còn ' + max + ' sản phẩm trong kho.');
            }
            return;
        }

        if (newVal !== val) {
            $input.val(newVal).trigger('change');
        }
    });

    // Người dùng gõ tay số lượng
    $(document).on('change', '.ajax-cart-qty-input', function () {
        var $input = $(this);
        var val = parseInt($input.val(), 10);
        var max = parseInt($input.attr('max'), 10) || 1;
        var min = parseInt($input.attr('min'), 10) || 1;
        var oldVal = parseInt($input.data('old-qty'), 10) || min;

        if (isNaN(val) || val < min) {
            $input.val(min);
            val = min;
        }

        // Nhập quá tồn kho → cắt về max + báo lỗi, vẫn gửi để server xác nhận
        if (val > max) {
            if (window.toastr) {
                toastr.warning('Số lượng vượt quá tồn kho. Hiện chỉ còn ' + max + ' sản phẩm.');
            }
            $input.val(max);
            val = max;
        }

        // Không đổi thì thôi
        if (val === oldVal) {
            return;
        }

        updateCartAjax($input.closest('form'), $input);
    });

    $(document).on('click', '.btn-delete-cart', function (e) {
        e.preventDefault();
        updateCartAjax($(this).closest('form'), null);
    });

    // Lưu giá trị cũ để rollback khi server báo lỗi
    $(document).on('focus', '.ajax-cart-qty-input', function () {
        $(this).data('old-qty', $(this).val());
    });

    function updateCartAjax($form, $qtyInput) {
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: $form.serialize(),
            beforeSend: function () {
                $('#cart-section').css('opacity', '0.5');
            },
            success: function (res) {
                // Nếu là JSON lỗi (fallback)
                if (typeof res === 'object' && res.success === false) {
                    $('#cart-section').css('opacity', '1');
                    if ($qtyInput) {
                        $qtyInput.val($qtyInput.data('old-qty'));
                    }
                    if (window.toastr) {
                        toastr.error(res.message || 'Không thể cập nhật giỏ hàng.');
                    }
                    return;
                }

                var newCart = $(res).find('#cart-section').html();
                newCart ? $('#cart-section').html(newCart) : window.location.reload();

                $('.header__cart').html($(res).find('.header__cart').html());
                $('#cart-section').css('opacity', '1');

                if (window.toastr) {
                    toastr.success('Đã cập nhật giỏ hàng');
                }
            },
            error: function (xhr) {
                $('#cart-section').css('opacity', '1');

                // Rollback số lượng nếu lỗi tồn kho
                if ($qtyInput) {
                    $qtyInput.val($qtyInput.data('old-qty'));
                }

                var message = (xhr.responseJSON && xhr.responseJSON.message)
                    ? xhr.responseJSON.message
                    : 'Vui lòng thử lại sau';

                if (window.toastr) {
                    toastr.error(message);
                }
            }
        });
    }
});
