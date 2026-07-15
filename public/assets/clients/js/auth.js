
/* ===================================================
   Auth ( Đăng nhập - Đăng ký ) Page Scripts
   =================================================== */
$(document).ready(function () {

   // Validate form đăng ký
   $("#register-form").submit(function (e) {
      let name = $('input[name="name"]').val().trim();
      let email = $('input[name="email"]').val().trim();
      let phone = $('input[name="phone"]').val().trim();
      let password = $('input[name="password"]').val().trim();
      let comfirmPassword = $('input[name="comfirmPassword"]').val().trim();
      let checkbox = $('input[name="checkbox"]').is(':checked');

      let errors = [];

      if (name.length < 3) {
         errors.push("Họ tên phải ít nhất 3 kí tự.");
      }

      let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
         errors.push("Email không hợp lệ.");
      }

      let phoneRegex = /^0[0-9]{9}$/;
      if (!phoneRegex.test(phone)) {
         errors.push("Số điện thoại chưa hợp lệ.");
      }

      if (password.length < 6) {
         errors.push("Mật khẩu ít nhất 6 kí tự.");
      }

      if (comfirmPassword != password) {
         errors.push("Mật khẩu xác nhận không khớp.");
      }

      if (!checkbox) {
         errors.push("Vui lòng đồng ý với điều khoản sử dụng.");
      }

      if (errors != "") {
         e.preventDefault();
         // Nối các lỗi bằng thẻ <br> để hiển thị xuống dòng trên SweetAlert2
         toastr.error(errors.join('<br>'), 'Kiểm tra lại thông tin');
      }
   });

   //validate login
   $("#login-form").submit(function (e) {
      let email = $('input[name="email"]').val().trim();
      let password = $('input[name="password"]').val().trim();
      let errors = [];
      if (email.length < 3) {
         errors.push("Email không được để trống.");
      }

      let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
         errors.push("Email không hợp lệ.");
      }

      if (password.length < 6) {
         errors.push("Mật khẩu ít nhất 6 kí tự.");
      }

      if (errors != "") {
         e.preventDefault();
         toastr.error(errors.join('<br>'), 'Kiểm tra lại thông tin');
      }
   });

   //validate forgot password
   $("#forgot-password-form").submit(function (e) {
      let email = $('input[name="email"]').val().trim();
      let errors = [];
      if (email.length < 3) {
         errors.push("Email không được để trống.");
      }

      let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
         errors.push("Email không hợp lệ.");
      }

      if (errors.length) {
         e.preventDefault();
         toastr.error(errors.join('<br>'), 'Kiểm tra lại thông tin');
      }
   });

   //validate reset password
   $("#reset-password-form").submit(function (e) {
      let email = $('input[name="email"]').val().trim();
      let password = $('input[name="password"]').val().trim();
      let errors = [];
      if (email.length < 3) {
         errors.push("Email không được để trống.");
      }

      let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
         errors.push("Email không hợp lệ.");
      }

      if (password.length < 6) {
         errors.push("Mật khẩu ít nhất 6 kí tự.");
      }

      if (errors != "") {
         e.preventDefault();
         toastr.error(errors.join('<br>'), 'Kiểm tra lại thông tin');
      }
   });
 
});