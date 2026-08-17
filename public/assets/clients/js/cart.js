// $(function () {
//     // Tăng / giảm số lượng trên trang giỏ — không cho vượt max (tồn kho)
//     $(document).on('click', '.qtybtn-small', function () {
//         var $input = $(this).siblings('input.ajax-cart-qty-input');
//         var val = parseInt($input.val(), 10) || 1;
//         var max = parseInt($input.attr('max'), 10) || 1;
//         var min = parseInt($input.attr('min'), 10) || 1;

//         var newVal = $(this).hasClass('inc')
//             ? Math.min(val + 1, max)
//             : Math.max(val - 1, min);

//         // Bấm + khi đã max → cảnh báo
//         if ($(this).hasClass('inc') && val >= max) {
//             if (window.toastr) {
//                 toastr.warning('Chỉ còn ' + max + ' sản phẩm trong kho.');
//             }
//             return;
//         }

//         if (newVal !== val) {
//             $input.val(newVal).trigger('change');
//         }
//     });

//     // Người dùng gõ tay số lượng
//     $(document).on('change', '.ajax-cart-qty-input', function () {
//         var $input = $(this);
//         var val = parseInt($input.val(), 10);
//         var max = parseInt($input.attr('max'), 10) || 1;
//         var min = parseInt($input.attr('min'), 10) || 1;
//         var oldVal = parseInt($input.data('old-qty'), 10) || min;

//         if (isNaN(val) || val < min) {
//             $input.val(min);
//             val = min;
//         }

//         // Nhập quá tồn kho → cắt về max + báo lỗi, vẫn gửi để server xác nhận
//         if (val > max) {
//             if (window.toastr) {
//                 toastr.warning('Số lượng vượt quá tồn kho. Hiện chỉ còn ' + max + ' sản phẩm.');
//             }
//             $input.val(max);
//             val = max;
//         }

//         // Không đổi thì thôi
//         if (val === oldVal) {
//             return;
//         }

//         updateCartAjax($input.closest('form'), $input);
//     });

//     $(document).on('click', '.btn-delete-cart', function (e) {
//         e.preventDefault();
//         updateCartAjax($(this).closest('form'), null);
//     });

//     // Lưu giá trị cũ để rollback khi server báo lỗi
//     $(document).on('focus', '.ajax-cart-qty-input', function () {
//         $(this).data('old-qty', $(this).val());
//     });

//     function updateCartAjax($form, $qtyInput) {
//         $.ajax({
//             url: $form.attr('action'),
//             type: $form.attr('method'),
//             data: $form.serialize(),
//             beforeSend: function () {
//                 $('#cart-section').css('opacity', '0.5');
//             },
//             success: function (res) {
//                 // Nếu là JSON lỗi (fallback)
//                 if (typeof res === 'object' && res.success === false) {
//                     $('#cart-section').css('opacity', '1');
//                     if ($qtyInput) {
//                         $qtyInput.val($qtyInput.data('old-qty'));
//                     }
//                     if (window.toastr) {
//                         toastr.error(res.message || 'Không thể cập nhật giỏ hàng.');
//                     }
//                     return;
//                 }

//                 var newCart = $(res).find('#cart-section').html();
//                 newCart ? $('#cart-section').html(newCart) : window.location.reload();

//                 $('.header__cart').html($(res).find('.header__cart').html());
//                 $('#cart-section').css('opacity', '1');

//                 if (window.toastr) {
//                     toastr.success('Đã cập nhật giỏ hàng');
//                 }
//             },
//             error: function (xhr) {
//                 $('#cart-section').css('opacity', '1');

//                 // Rollback số lượng nếu lỗi tồn kho
//                 if ($qtyInput) {
//                     $qtyInput.val($qtyInput.data('old-qty'));
//                 }

//                 var message = (xhr.responseJSON && xhr.responseJSON.message)
//                     ? xhr.responseJSON.message
//                     : 'Vui lòng thử lại sau';

//                 if (window.toastr) {
//                     toastr.error(message);
//                 }
//             }
//         });
//     }
// });

$(document).ready(function () {
    $(document).on('click', '.qty-btn-small', function (e) {
        e.preventDefault();
        var input = $(this).siblings('input.cart-qty-input');
        var currentQty = parseInt(input.val(), 10);
        var max = parseInt(input.attr('max'), 10);// tồn kho
        var min = parseInt(input.attr('min'), 10);// tối thiểu

        //tính toán
        var newQty = currentQty;
        if ($(this).hasClass('inc')) {//inc cộng
            newQty = currentQty + 1;
            if (newQty > max) {
                toastr.warning('Số lượng vượt quá tồn kho. Hiện chỉ còn ' + max + ' sản phẩm.');
                newQty = max;
            }
        } else if ($(this).hasClass('dec')) {//dec trừ
            newQty = currentQty - 1;
            if (newQty < min) {
                toastr.warning('Số lượng tối thiểu là ' + min + ' sản phẩm.');
                newQty = min;
            }
        }
        if (newQty != currentQty) {
            input.val(newQty);
            input.trigger('change');
        }
    });

    $(document).on('change', '.cart-qty-input', function (e) {
        e.preventDefault();

        var input = $(this);
        var form = input.closest('form');

        //Kierm tra gõ tay hợp lệ kh
        var val = parseInt(input.val(), 10);
        var max = parseInt(input.attr('max'), 10);
        var min = parseInt(input.attr('min'), 10);

        if (isNaN(val) || val < min) {
            val = min;
            input.val(min);
        }

        if (val > max) {
            toastr.warning('Số lượng vượt tồn kho. Chỉ còn ' + max + ' sản phẩm.');
            val = max;
            input.val(max);
        }
        //Giao diện 
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function (response) {
                // BƯỚC 3: THAY RUỘT HTML MỚI TỪ LỄ TÂN
                // response chính là nguyên 1 trang web HTML bự chà bá mà Lễ tân ném ra
                // Dùng tuyệt chiêu "Cái hộp rỗng" <div> để bóc đúng ruột của #cart-section
                var newCart = $('<div>').html(response).find('#cart-section').html();

                if (newCart) {
                    // Dán đè ruột mới vào đúng chỗ cái khung #cart-section hiện tại
                    $('#cart-section').html(newCart);

                    // Cập nhật luôn con số đỏ trên icon giỏ hàng ở góc phải màn hình
                    var newHeaderCart = $('<div>').html(response).find('.header__cart').html();
                    $('.header__cart').html(newHeaderCart);
                    toastr.success('Cập nhật giỏ hàng thành công!');
                } else {
                    // Xui xẻo lắm web bị lỗi gì đó không tìm thấy thì đành Reload tạm
                    window.location.reload();
                }
            },


        })
    });

    $(document).on('click','.btn-delete-cart', function(e){
        e.preventDefault();
        //hỏi 
        if(!confirm('Bạn có chắc chắn muốn xóa?')){
            return;
        }
        //tìm cái fom chứa cái nút
        var form = $(this).closest('form');

        //Dưa đi giao
        $.ajax({
            url: form.attr('action'),
            type:"POST",
            data: form.serialize(),

            success: function (response){
                var newCartHtml = $('<div>').html(response).find('#cart-section').html();
                var newHeaderCart = $('<div>').html(response).find('.header__cart').html();
                if( newCartHtml){
                    $('#cart-section').html(newCartHtml);
                    $('.header__cart').html(newHeaderCart);
                    toastr.success('Xóa sản phẩm khỏi giỏ hàng thành công!');
                }else{
                    window.location.reload();
                    
                }
            },
            error: function(xhr){
                toastr.error('Lỗi hệ thống. Vui lòng thử lại!')
            }
        })

    });
    // ----------------------------------------------------
    // NGĂN CHẶN LOAD TRANG KHI BẤM ENTER
    // ----------------------------------------------------
    $(document).on('submit', '.cart-update-form', function (e) {
        // Khóa mỏ trình duyệt lại, cấm nộp form truyền thống!
        e.preventDefault();
    });

});