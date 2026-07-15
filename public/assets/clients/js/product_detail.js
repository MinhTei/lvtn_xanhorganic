/* ===================================================
   Product Detail Page Scripts
   =================================================== */

$(document).ready(function() {
    // Star Rating system for reviews
    $('.star-btn').on('click', function(e) {
        e.preventDefault();
        var rating = $(this).data('rating');
        $('#rating').val(rating);
        
        $('.star-btn').each(function() {
            if ($(this).data('rating') <= rating) {
                $(this).css('color', '#fbbf24');
            } else {
                $(this).css('color', '#d1d5db');
            }
        });
    });

    // Image thumbnail click handler
    $('.product-thumb').on('click', function() {
        var imageUrl = $(this).data('image');
        $('#mainProductImage').attr('src', imageUrl);
    });
});

