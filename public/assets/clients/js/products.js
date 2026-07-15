$(document).ready(function() {
    
    function fetchProducts(customUrl = null) {
        let url = customUrl || '/products';
        let data = {};

        if (!customUrl) {
            var params = new URLSearchParams(window.location.search);
            data = {
                category_id: $(".category-link.active").data("category_id") || '',
                minPrice: $("#min_price").val() || '',
                maxPrice: $("#max_price").val() || '',
                sort: $("#sortSelect").val() || '',
                q: params.get('q') || $('input[name="q"]').filter(function () {
                    return $(this).closest('form').attr('id') !== 'filterForm';
                }).first().val() || ''
            };
            // Giữ q từ hidden trong filter form nếu có
            if ($('#filterForm input[name="q"]').length) {
                data.q = $('#filterForm input[name="q"]').val() || data.q;
            }
        }

        $("#product-list-area").css('opacity', '0.5');

        $.ajax({
            url: url,
            type: "GET",
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                var newContent = $(response).find('#product-list-area').html();
                $("#product-list-area").html(newContent);
                $("#product-list-area").css('opacity', '1');

                // Cập nhật URL (giữ bộ lọc + q)
                var pushed = this.url;
                if (pushed && pushed.indexOf('http') !== 0) {
                    var qs = $.param(data);
                    pushed = url + (qs ? ('?' + qs) : '');
                }
                window.history.pushState({path: pushed}, '', pushed);
            },
            error: function() {
                $("#product-list-area").css('opacity', '1');
            }
        });
    }

    // Chỉ lọc khi có data-category_id (tránh parent collapse bị coi là "tất cả")
    $(".category-link").click(function(e) {
        var $link = $(this);
        if ($link.is('[data-toggle="collapse"]') && !$link.data('category_id') && $link.attr('data-category_id') === undefined) {
            // Parent chỉ mở/đóng — không preventDefault để Bootstrap collapse chạy
            return;
        }
        e.preventDefault();
        $(".category-link").removeClass("active");
        $link.addClass("active");
        fetchProducts();
    });

    $(document).on('change', '#sortSelect', function(e) {
        e.preventDefault();
        fetchProducts();
    });

    // Bootstrap pagination dùng .pagination a
    $(document).on('click', '.pagination a, .product__pagination a', function(e) {
        var url = $(this).attr('href');
        if (!url || url === '#') return;
        e.preventDefault();
        fetchProducts(url);
    });

    $(document).on('click','.btn-filter',function(e){
        e.preventDefault();
        fetchProducts();
    });
    $(document).on('click','.btn-clear',function(e){
        e.preventDefault();
        $('#min_price').val('');
        $('#max_price').val('');
        fetchProducts();
    });

});
