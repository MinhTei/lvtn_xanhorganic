<div class="address-location-fields row"
     data-province="{{ $province ?? '' }}"
     data-district="{{ $district ?? '' }}"
     data-ward="{{ $ward ?? '' }}">
    <div class="col-lg-4">
        <div class="account-form-group">
            <label>Tỉnh/Thành phố</label>
            <select name="province" class="account-input address-province native-address-select">
                <option value="">Chọn Tỉnh/Thành phố</option>
            </select>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="account-form-group">
            <label>Quận/Huyện</label>
            <select name="district" class="account-input address-district native-address-select" disabled>
                <option value="">Chọn Quận/Huyện</option>
            </select>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="account-form-group">
            <label>Phường/Xã</label>
            <select name="ward" class="account-input address-ward native-address-select" disabled>
                <option value="">Chọn Phường/Xã</option>
            </select>
        </div>
    </div>
</div>
