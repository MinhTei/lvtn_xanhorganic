/**
 * Thêm giỏ / yêu thích — guest và user đều dùng được.
 * Ràng buộc tồn kho: không cho thêm quá products.quantity.
 */
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        }
    });

    function updateWishlistBadge(count) {
        var $badge = $('.wishlist-count');
        $badge.text(count);
        count > 0 ? $badge.show() : $badge.hide();
    }

    function updateCartBadge(count) {
        var $badge = $('.cart-count');
        $badge.text(count);
        count > 0 ? $badge.show() : $badge.hide();
    }

    // ---- Yêu thích ----
    $(document).on('click', '.js-add-wishlist', function (e) {
        e.preventDefault();
        var productId = $(this).data('product-id');

        $.ajax({
            url: window.XanhOrganic.wishlistAddUrl,
            type: 'POST',
            data: { product_id: productId },
            success: function (response) {
                if (response.success) {
                    updateWishlistBadge(response.wishlist_count);
                    toastr.success(response.message, 'Yêu thích');
                } else {
                    toastr.warning(response.message);
                }
            },
            error: function (xhr) {
                var message = (xhr.responseJSON && xhr.responseJSON.message)
                    ? xhr.responseJSON.message
                    : 'Có lỗi xảy ra!';
                toastr.error(message);
            }
        });
    });

    // ---- Giỏ hàng ----
    $(document).on('click', '.js-add-cart', function (e) {
        e.preventDefault();

        var $btn = $(this);
        var productId = $btn.data('product-id');
        var stock = parseInt($btn.data('stock'), 10);

        // Hết hàng (nút trên lưới / chi tiết)
        if (!isNaN(stock) && stock < 1) {
            toastr.warning('Sản phẩm đã hết hàng.');
            return;
        }

        // Ô số lượng ở trang chi tiết (#quantity)
        var $qtyInput = $('#quantity');
        var quantity = 1;
        if ($qtyInput.length) {
            quantity = parseInt($qtyInput.val(), 10);
            var max = parseInt($qtyInput.attr('max'), 10);

            if (isNaN(quantity) || quantity < 1) {
                quantity = 1;
                $qtyInput.val(1);
            }

            // Nhập quá tồn kho trên form chi tiết
            if (!isNaN(max) && quantity > max) {
                toastr.warning('Số lượng vượt quá tồn kho. Hiện chỉ còn ' + max + ' sản phẩm.');
                $qtyInput.val(max);
                return;
            }
        }

        $.ajax({
            url: window.XanhOrganic.cartAddUrl,
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity
            },
            success: function (response) {
                if (response.success) {
                    updateCartBadge(response.cart_count);
                    toastr.success(response.message, 'Giỏ hàng');
                } else {
                    toastr.warning(response.message);
                }
            },
            error: function (xhr) {
                // 422: vượt tồn kho / hết hàng
                var message = (xhr.responseJSON && xhr.responseJSON.message)
                    ? xhr.responseJSON.message
                    : 'Có lỗi xảy ra!';
                toastr.warning(message);
            }
        });
    });

    // Ràng buộc ô số lượng trang chi tiết khi gõ / tăng giảm
    $(document).on('change blur', '#quantity', function () {
        var $input = $(this);
        var val = parseInt($input.val(), 10);
        var max = parseInt($input.attr('max'), 10) || 1;
        var min = parseInt($input.attr('min'), 10) || 1;

        if (isNaN(val) || val < min) {
            $input.val(min);
            return;
        }

        if (val > max) {
            toastr.warning('Số lượng vượt quá tồn kho. Hiện chỉ còn ' + max + ' sản phẩm.');
            $input.val(max);
        }
    });
});
