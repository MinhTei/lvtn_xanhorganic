$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    });

    function openWishlistModal() {
        var $modalBody = $('#wishlist-modal-body');
        $('#wishlistModal').modal('show');

        $.ajax({
            url: window.XanhOrganic.wishlistUrl,
            type: 'GET',
            success: function (html) {
                $modalBody.html(html);
            },
            error: function () {
                $modalBody.html('<div class="text-center text-danger py-4">Lỗi tải danh sách.</div>');
            }
        });
    }

    $(document).on('click', '#btn-open-wishlist, #btn-open-wishlist-mobile', function (e) {
        e.preventDefault();
        openWishlistModal();
    });

    $(document).on('click', '.js-remove-wishlist', function (e) {
        e.preventDefault();
        var wishlistId = $(this).data('wishlist-id');
        var productId = $(this).data('product-id');
        var url = window.XanhOrganic.wishlistRemoveUrl;

        if (wishlistId) {
            url = url + '/' + wishlistId;
        }

        $.ajax({
            url: url,
            type: 'DELETE',
            data: { product_id: productId },
            success: function (response) {
                if (response.success) {
                    $('.wishlist-count').text(response.wishlist_count);
                    response.wishlist_count > 0 ? $('.wishlist-count').show() : $('.wishlist-count').hide();
                    toastr.success(response.message);

                    if (productId) {
                        var $hearts = $('.js-add-wishlist[data-product-id="' + productId + '"]');
                        $hearts.removeClass('is-active').attr('title', 'Thêm vào yêu thích');
                    }

                    if ($('#wishlistModal').hasClass('show')) {
                        openWishlistModal();
                    }

                    if ($('#account-wishlist-list').length) {
                        $.ajax({
                            url: window.XanhOrganic.wishlistUrl,
                            type: 'GET',
                            success: function (html) {
                                $('#account-wishlist-list').html(html);
                                if (response.wishlist_count === 0) {
                                    $('#wishlist .account-head #btn-wishlist-add-all').remove();
                                }
                            }
                        });
                    }
                }
            },
            error: function () {
                toastr.error('Không thể xóa sản phẩm khỏi yêu thích.');
            }
        });
    });

    // Thêm tất cả yêu thích vào giỏ
    $(document).on('click', '#btn-wishlist-add-all', function (e) {
        e.preventDefault();
        var $btn = $(this);
        $btn.prop('disabled', true);

        $.ajax({
            url: window.XanhOrganic.wishlistAddAllUrl,
            type: 'POST',
            success: function (response) {
                if (response.success) {
                    $('.cart-count').text(response.cart_count).show();
                    $('.wishlist-count').text(response.wishlist_count).hide();
                    $('.js-add-wishlist').removeClass('is-active').attr('title', 'Thêm vào yêu thích');
                    toastr.success(response.message, 'Giỏ hàng');
                    $('#wishlistModal').modal('hide');
                } else {
                    toastr.warning(response.message);
                }
            },
            error: function (xhr) {
                if (xhr.status === 442) {
                    if (xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        for (var field in errors) {
                            toastr.error(errors[field][0], 'Lỗi giỏ hàng!');
                            break;
                        }
                    } else {
                        toastr.error(xhr.responseJSON.message);
                    }
                } else {
                    toastr.warning('Không có sản phẩm để thêm vào giỏ hàng.');
                }
            },
            complete: function () {
                $btn.prop('disabled', false);
            }
        });
    });
});
