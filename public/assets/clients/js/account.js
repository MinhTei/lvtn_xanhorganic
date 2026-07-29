$(document).ready(function () {
    function showSection(hash) {
        if (!hash || hash === '#') return;

        var $link = $('.account-nav a[href="' + hash + '"]');
        if (!$link.length) return;

        $('.account-nav a').removeClass('active');
        $link.addClass('active');
        $('.account-panel').hide();
        $(hash).fadeIn(300);
    }

    $('.account-nav a[href^="#"]').on('click', function (e) {
        e.preventDefault();
        var hash = $(this).attr('href');
        history.replaceState(null, '', hash);
        showSection(hash);
    });

    var initialHash = window.location.hash;
    if (initialHash) {
        showSection(initialHash);
    } else if (window.accountInitialTab) {
        showSection(window.accountInitialTab);
    } else {
        showSection('#profile');
    }

    $('.btn-edit-address').on('click', function (e) {
        e.preventDefault();
        var addressId = $(this).data('address-id');
        $('#edit-address-' + addressId).slideToggle(300, function () {
            if (window.bootAddressLocations) {
                window.bootAddressLocations();
            }
        });
    });

    if (window.accountShowAddAddressForm) {
        showSection('#addresses');
    }

    $('#change-password').submit(function (e) {
        var newPassword = $('input[name="new_password"]').val().trim();
        var currentPassword = $('input[name="current_password"]').val().trim();
        var confirmPassword = $('input[name="new_password_confirm"]').val().trim();
        var errors = [];

        if (currentPassword.length < 6) {
            errors.push('Mật khẩu hiện tại ít nhất 6 kí tự.');
        }
        if (newPassword.length < 6) {
            errors.push('Mật khẩu mới ít nhất 6 kí tự.');
        }
        if (newPassword !== confirmPassword) {
            errors.push('Mật khẩu mới không khớp.');
        }

        if (errors.length > 0) {
            e.preventDefault();
            toastr.error(errors.join('<br>'), 'Kiểm tra lại thông tin');
        }
    });

    $('#add-address').submit(function (e) {
        var $form = $(this);
        var province = $form.find('select[name="province"]').val();
        var district = $form.find('select[name="district"]').val();
        var ward = $form.find('select[name="ward"]').val();
        var street = $form.find('input[name="street_address"]').val().trim();
        var errors = [];

        if (!province) errors.push('Bắt buộc chọn tỉnh/thành phố');
        if (!district) errors.push('Bắt buộc chọn quận/huyện');
        if (!ward) errors.push('Bắt buộc chọn phường/xã');
        if (!street) errors.push('Bắt buộc nhập địa chỉ');

        if (errors.length > 0) {
            e.preventDefault();
            toastr.error(errors.join('<br>'), 'Kiểm tra lại thông tin');
        }
    });
});
