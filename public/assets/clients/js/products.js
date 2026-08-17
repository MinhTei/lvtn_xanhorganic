$(document).ready(function () {

    // Hàm gọi AJAX lấy danh sách sản phẩm
    function fetchProducts(customUrl = null) {
        let url = customUrl || '/products';
        let data = {};

        // Nếu không có URL cụ thể (như khi bấm phân trang), ta sẽ lấy các thông số lọc
        if (!customUrl) {
            // Lấy từ khóa tìm kiếm (ưu tiên trên thanh URL, rồi đến form ẩn, rồi đến ô input)
            let keyword = new URLSearchParams(window.location.search).get('q')
                || $('#filterForm input[name="q"]').val()
                || $('input[name="q"]').first().val()
                || '';

            data = {
                category_id: $(".category-link.active").data("category_id") || '',
                minPrice: $("#min_price").val() || '',
                maxPrice: $("#max_price").val() || '',
                sort: $("#sortSelect").val() || '',
                q: keyword
            };
        }

        // Làm mờ danh sách trong lúc chờ tải dữ liệu
        $("#product-list-area").css('opacity', '0.5');

        $.ajax({
            url: url,
            type: "GET",
            data: data,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                // Trích xuất khung HTML chứa sản phẩm mới và thay thế vào hiện tại
                var newContent = $(response).find('#product-list-area').html();
                $("#product-list-area").html(newContent).css('opacity', '1');

                // Cập nhật đường dẫn trên trình duyệt để copy link/F5 không bị mất
                var newUrl = this.url;
                if (!customUrl) {
                    var qs = $.param(data);
                    newUrl = url + (qs ? '?' + qs : '');
                }
                window.history.pushState({ path: newUrl }, '', newUrl);
            },
            error: function () {
                // Lỗi thì phục hồi độ sáng của khung
                $("#product-list-area").css('opacity', '1');
            }
        });
    }

    // 1. Sự kiện chọn Danh mục
    $(document).on('click', '.category-link', function (e) {
        var $link = $(this);
        // Bỏ qua nếu đây là nút xổ danh mục con (không có data-category_id)
        if ($link.is('[data-toggle="collapse"]') && $link.data('category_id') === undefined) return;

        e.preventDefault();
        $(".category-link").removeClass("active");
        $link.addClass("active");
        fetchProducts();
    });

    // 2. Sự kiện đổi kiểu Sắp xếp (Mới nhất, Giá tăng/giảm...)
    $(document).on('change', '#sortSelect', function (e) {
        fetchProducts();
    });

    // 3. Sự kiện bấm Lọc theo giá
    $(document).on('click', '.btn-filter', function (e) {
        e.preventDefault();
        fetchProducts();
    });

    // 4. Sự kiện bấm Xóa bộ lọc giá
    $(document).on('click', '.btn-clear', function (e) {
        e.preventDefault();
        $('#min_price, #max_price').val(''); // Xóa trắng giá trị 2 ô nhập
        fetchProducts();
    });

    // 5. Sự kiện bấm Phân trang (Trang 1, 2, 3...)
    $(document).on('click', '.pagination a, .product__pagination a', function (e) {
        var url = $(this).attr('href');
        if (url && url !== '#') {
            e.preventDefault();
            fetchProducts(url);
        }
    });



    $(document).on('click', '.btn-show-recipe', function () {
        var recipeId = $(this).data('id');
        var recipeTitle = $(this).data('title');

        // Đổi tiêu đề modal
        $('#recipeModalLabel').html('Nguyên liệu món: <strong>' + recipeTitle + '</strong>');

        // Reset nội dung về trạng thái loading
        $('#recipe-modal-body').html(
            '<div class="text-center py-4">' +
            '<div class="spinner-border text-success" role="status"></div>' +
            '<p class="mt-2">Đang tải...</p></div>'
        );

        // Gắn recipeId vào nút Thêm tất cả
        $('#btn-add-all-to-cart').data('recipe-id', recipeId);

        // Mở modal
        $('#recipeModal').modal('show');

        // Load danh sách nguyên liệu vào modal body
        $.ajax({
            url: '/recipes/' + recipeId,
            type: 'GET',
            success: function (html) {
                $('#recipe-modal-body').html(html);
            },
            error: function () {
                $('#recipe-modal-body').html('<div class="text-center text-danger py-4">Lỗi tải danh sách nguyên liệu.</div>');
            }
        });
    });

    // Thêm tất cả nguyên liệu vào giỏ hàng (giống logic wishlist add-all nhưng dùng 1 request duy nhất)
    $(document).on('click', '#btn-add-all-to-cart', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var recipeId = $btn.data('recipe-id');

        if (!recipeId) {
            toastr.error('Không tìm thấy thông tin món ăn.');
            return;
        }

        $btn.prop('disabled', true);

        $.ajax({
            url: '/recipes/' + recipeId + '/add-all-to-cart',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                if (res.success) {
                    toastr.success(res.message, 'Giỏ hàng');
                    if (res.cart_count) {
                        $('.cart-count').text(res.cart_count).show();
                    }
                    $('#recipeModal').modal('hide');
                } else {
                    toastr.error(res.message);
                }
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    toastr.error(xhr.responseJSON.message);
                } else {
                    toastr.error('Có lỗi xảy ra khi thêm vào giỏ hàng.');
                }
            },
            complete: function () {
                $btn.prop('disabled', false);
            }
        });
    });

});
