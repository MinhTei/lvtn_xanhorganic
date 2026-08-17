$(document).ready(function() {
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();
        
        let form = $(this);
        let submitBtn = form.find('button[type="submit"]');
        let originalText = submitBtn.text();
        
        submitBtn.prop('disabled', true).text('ĐANG GỬI...');
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    form[0].reset();
                } else {
                    toastr.error('Có lỗi xảy ra, vui lòng thử lại!');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    for (let key in errors) {
                        toastr.error(errors[key][0]);
                    }
                } else {
                    toastr.error('Có lỗi xảy ra trong quá trình gửi, vui lòng thử lại sau.');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });
});
