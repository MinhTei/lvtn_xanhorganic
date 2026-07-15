/* ===================================================
   Account Page Scripts
   =================================================== */

$(document).ready(function() {
    function showSection(hash) {
        if (!hash || hash === '#') return;

        var $link = $('.account-nav-link[href="' + hash + '"]');
        if (!$link.length) return;

        $('.account-nav-link').removeClass('active');
        $link.addClass('active');
        $('.account-section').hide();
        $(hash).fadeIn(300);
    }

    $('.account-nav-link[href^="#"]').on('click', function(e) {
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
        // Mặc định hiện tab đầu tiên (#profile) khi không có hash trên URL
        showSection('#profile');
    }

    $('#btn-add-address').on('click', function(e) {
        e.preventDefault();
        $('#address-list-view').hide();
        $('#add-address-form').fadeIn(300, function() {
            if (window.bootAddressLocations) {
                window.bootAddressLocations();
            }
        });
    });

    $('#btn-cancel-address').on('click', function(e) {
        e.preventDefault();
        $('#add-address-form').hide();
        $('#address-list-view').fadeIn(300);
    });

    $('.btn-edit-address').on('click', function(e) {
        e.preventDefault();
        var addressId = $(this).data('address-id');
        $('#edit-address-' + addressId).slideToggle(300, function() {
            if (window.bootAddressLocations) {
                window.bootAddressLocations();
            }
        });
    });

    if (window.accountShowAddAddressForm) {
        showSection('#addresses');
        $('#address-list-view').hide();
        $('#add-address-form').show();
    }

    $("#change-password").submit(function (e) {
      let new_password = $('input[name="new_password"]').val().trim();
      let current_password=$('input[name="current_password"]').val().trim();
      let new_password_confirm=$('input[name="new_password_confirm"]').val().trim();

      let errors = [];
      if (current_password.length < 6) {
         errors.push("Mật khẩu hiện tại ít nhất 6 kí tự.");
      }
      if (new_password.length < 6) {
         errors.push("Mật khẩu mới ít nhất 6 kí tự.");
      }
      if (new_password != new_password_confirm) {
         errors.push("Mật khẩu mới không khớp.");
      }

      if (errors.length > 0) {
         e.preventDefault();
         toastr.error(errors.join('<br>'), 'Kiểm tra lại thông tin');
      }
      
   });
 
   $("#add-address").submit(function(e){
        let province = $('select[name="province"]').val();
        let district = $('select[name="district"]').val();
        let ward = $('select[name="ward"]').val();
        let street_address = $('input[name="street_address"]').val().trim();
        let errors=[];

        if(!province){
            errors.push("Bắt buộc chọn tỉnh/thành phố");
        }
        if(!district){
            errors.push("Bắt buộc chọn quận/huyện");
        }
        if(!ward){
            errors.push("Bắt buộc chọn phường/xã");
        }
        if(!street_address){
            errors.push("Bắt buộc nhập địa chỉ");
        }

        if(errors.length > 0){
            e.preventDefault();
            toastr.error(errors.join('<br>'), 'Kiểm tra lại thông tin');
        }
        
   })
    // $('#avatar').change(function(){
    //     let input = this;
    //     if(input.files && input.files[0]){
    //         let reader = new FileReader();
    //         reader.onload = function(e) {
    //             $('#avatar-preview').attr('src', e.target.result);
    //         }
    //         reader.readAsDataURL(input.files[0]);
    //     }
    // });
});
