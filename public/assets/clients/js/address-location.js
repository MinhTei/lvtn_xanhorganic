/**
 * Dropdown tỉnh/quận/xã — dữ liệu hardcode (window.HCM_ADDRESS).
 * Không phụ thuộc API bên ngoài.
 */
$(document).ready(function () {
    var HCM = window.HCM_ADDRESS || {};

    $('.address-location-fields').each(function () {
        var $container = $(this);
        var $province = $container.find('.address-province');
        var $district = $container.find('.address-district');
        var $ward = $container.find('.address-ward');

        var savedDistrict = $container.data('district') || '';
        var savedWard = $container.data('ward') || '';

        // Gỡ nice-select nếu main.js đã gắn
        [$province, $district, $ward].forEach(function ($sel) {
            if ($.fn.niceSelect && $sel.next('.nice-select').length) {
                $sel.niceSelect('destroy');
            }
            $sel.addClass('native-address-select').show();
        });

        // TP.HCM cố định
        $province
            .html('<option value="Thành phố Hồ Chí Minh" selected>Thành phố Hồ Chí Minh</option>')
            .css({ 'pointer-events': 'none', 'background-color': '#e9ecef' });

        function resetWard() {
            $ward
                .html('<option value="">Chọn Phường/Xã</option>')
                .prop('disabled', true)
                .val('');
        }

        // Nạp Quận/Huyện từ data cứng
        $district.html('<option value="">Chọn Quận/Huyện</option>');
        Object.keys(HCM).forEach(function (name) {
            $district.append($('<option>', { value: name, text: name }));
        });
        $district.prop('disabled', false);

        $district.off('change.addressLoc').on('change.addressLoc', function () {
            var name = $(this).val();
            resetWard();
            if (!name || !HCM[name]) return;

            $ward.html('<option value="">Chọn Phường/Xã</option>');
            HCM[name].forEach(function (w) {
                $ward.append($('<option>', { value: w, text: w }));
            });
            $ward.prop('disabled', false);

            if (savedWard) {
                $ward.val(savedWard);
                savedWard = '';
            }
        });

        if (savedDistrict) {
            $district.val(savedDistrict);
            if ($district.val() === savedDistrict) {
                $district.trigger('change');
            }
        }
    });
});
