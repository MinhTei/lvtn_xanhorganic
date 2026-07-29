<div class="address-location-fields row"
     data-province="{{ $province ?? '' }}"
     data-district="{{ $district ?? '' }}"
     data-ward="{{ $ward ?? '' }}">
    <div class="col-lg-4">
        <div class="account-field">
            <label>Tỉnh/Thành phố</label>
            <select name="province" class="address-province native-address-select">
                <option value="">Chọn Tỉnh/Thành phố</option>
            </select>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="account-field">
            <label>Quận/Huyện</label>
            <select name="district" class="address-district native-address-select" disabled>
                <option value="">Chọn Quận/Huyện</option>
            </select>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="account-field">
            <label>Phường/Xã</label>
            <select name="ward" class="address-ward native-address-select" disabled>
                <option value="">Chọn Phường/Xã</option>
            </select>
        </div>
    </div>
</div>
