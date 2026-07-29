
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

    function setWishlistHeartState(productId, isActive) {
        var $btns = $('.js-add-wishlist[data-product-id="' + productId + '"]');
        $btns.toggleClass('is-active', !!isActive);
        $btns.attr('title', isActive ? 'Đã yêu thích' : 'Thêm vào yêu thích');
    }



    //Viết lại giỏ hnafg
    $(document).on('click', '.js-add-cart', function (e) {
        e.preventDefault();
        var btn = $(this);
        var productId = btn.data('product-id');
        var quantity = 1;
        var stock = parseInt(btn.data('stock'), 10);
        var inputQty = $('#quantity');
        if (stock === 0 || stock < 1) {
            toastr.error('Sản phẩm đã hết hàng!');
            return;
        }
        if (inputQty.length > 0) {
            quantity = parseInt(inputQty.val(), 10);
            if (isNaN(quantity) || quantity < 1) {
                quantity = 1;
                inputQty.val(1);
            }

            if (!isNaN(stock) && quantity > stock) {
                toastr.warning('Số lượng vượt tồn kho! Hiện chỉ còn ' + stock + ' sản phẩm!');
                quantity = stock;
                inputQty.val(stock);
                return;
            }

        }
        $.ajax({
            url: window.XanhOrganic.cartAddUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                product_id: productId,
                quantity: quantity
            },
            success: function (response) {
                //$('.cart-count').text(response.cart_count).show();
                updateCartBadge(response.cart_count);
                toastr.success(response.message, 'Thêm thành công!');
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    //Kịch Control
                    if (xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        for (var field in errors) {
                            toastr.error(errors[field][0], 'Lỗi nhập liệu!');
                            break;
                        }
                    }
                    //Kịch Client
                    else if (xhr.responseJSON.message) {
                        toastr.warning(xhr.responseJSON.message, 'Từ chối yêu cầu');
                    }
                } else {
                    toastr.error('Có lỗi kết nối với máy chủ!');
                }

            }



        });
    });


    //Viết lại yêu thích
    $(document).on('click','.js-add-wishlist', function(e){
        e.preventDefault();
        var btn = $(this);
        var productId = btn.data('product-id');
        $.ajax({
            url:window.XanhOrganic.wishlistAddUrl,
            type:'POST',
            dataType: 'json',
            data : {
                product_id: productId
            },
            success: function(response){
                if(response.success){
                    updateWishlistBadge(response.wishlist_count);
                    setWishlistHeartState(productId, response.action === 'added');
                    toastr.success(response.message, 'Yêu thích');
                }
                else{
                    toastr.warning(response.message,'Thất bại');
                }
            },
            error:function(xhr){
                toastr.error('Có lỗi kết nối với máy chủ');
            }
            
        });
    });


});
