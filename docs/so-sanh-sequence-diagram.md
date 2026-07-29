# So sánh sơ đồ tuần tự (Hình 3.14–3.32) với code hiện tại

**Kết luận tổng quát:**  
Luồng nghiệp vụ **phần lớn đã có và đi đúng hướng** (UI → Controller → Model → View + kiểm tra quyền / validate / alt).  
Tuy nhiên **tên Controller / Model / method trong sơ đồ gần như không khớp** convention Laravel thực tế của dự án — đây là lệch **diễn đạt sơ đồ**, không phải thiếu chức năng (trừ vài điểm ghi rõ bên dưới).

**Quy ước cột trạng thái**
- `OK` — Luồng khớp code
- `GẦN` — Nghiệp vụ đúng, khác tên / tách-gộp class / UI
- `LỆCH` — Sơ đồ khác code ở điểm quan trọng

---

## Bảng tổng hợp

| Hình | Chức năng | TT | Code thực tế | Ghi chú chính |
|------|-----------|----|--------------|---------------|
| 3.14 | Đăng ký | GẦN | `AuthController@showRegisterForm`, `@register`, `@activate` + `User` | Đúng kích hoạt email; tên method khác (`signup`/`createUser` → `showRegisterForm`/`register`); không method `checkEmailExist` riêng (rule validate) |
| 3.15 | Quên mật khẩu | GẦN | `ForgotPasswordController` + `ResetPasswordController` + bảng `password_reset_tokens` | Đúng 2 bước gửi mail / đổi mật khẩu; **không** gom hết vào `AuthController` như sơ đồ |
| 3.16 | Cập nhật thông tin cá nhân | GẦN | `AccountController@index`, `@update` | Đúng validate → lưu → thông báo; sơ đồ ghi `UserController` / `BasicInforUser` / `editUser` |
| 3.17 | Xem lịch sử đơn hàng | GẦN | `AccountController@index` / `@showOrder` + quan hệ `Order` | Đúng Profile → danh sách đơn; không có `OderController` / `OrderHistoryUser()` |
| 3.18 | Theo dõi / chi tiết đơn | GẦN | `AccountController@showOrderDetail` | Đúng từ lịch sử → chi tiết; sơ đồ ghi `UserController` / `OrderDetail()` |
| 3.19 | Xem wishlist | OK | Account tab `#wishlist` + `WishlistController` AJAX | Profile → “Sản phẩm yêu thích”; xóa/thêm giỏ qua WishlistController |
| 3.20 | DS tài khoản (admin) | GẦN | `AdminUserController@index` | Đúng list; tên method `UserList()` → `index` |
| 3.21 | Tìm kiếm TK | GẦN | `AdminUserController@index?q=` | Có tìm kiếm; **không** method riêng `searchUser()`; empty → bảng trống (không nhánh “không tìm thấy” riêng như sơ đồ) |
| 3.22 | Chi tiết TK | OK | `AdminUserController@show` | Trang chi tiết + nút Chi tiết trên danh sách |
| 3.23 | Khóa / mở khóa tài khoản | OK | `toggleBlock()` — `active` ↔ `blocked` | Có kiểm tra quyền; không tự khóa; không khóa `pending`; không còn `destroy` / vô hiệu hóa |
| 3.24 | Thêm role | OK | `AdminRoleController@create`, `@store` + sync permissions | Đúng quyền → form → validate → lưu; tên `AddRoles`/`storeRoles` → `create`/`store`; controller tên `AdminRoleController` |
| 3.25 | Cập nhật role & quyền | OK | `AdminRoleController@edit`, `@update` | Đúng; sơ đồ ghi “Trang Thêm Role mới” nhưng thực tế là trang **sửa** |
| 3.26 | Thêm sản phẩm | OK | `AdminProductController@create`, `@store` | Đúng luồng quyền / validate / lưu |
| 3.27 | Sửa sản phẩm | OK | `AdminProductController@edit`, `@update` | Đúng luồng |
| 3.28 | Xóa sản phẩm | OK | `AdminProductController@destroy` | **Đúng nghiệp vụ quan trọng:** có đơn → ẩn (`is_active=false`); không đơn → xóa cứng. Không tách method `softDelete`/`hardDelete`/`checkOrdersExis` |
| 3.29 | Import sản phẩm | GẦN | `importForm`, `importStore`, `importTemplate` | Đúng form → upload → validate → lưu; file là **CSV** (không bắt buộc Excel như chữ trên sơ đồ); có middleware quyền |
| 3.30 | Thêm danh mục | OK | `AdminCategoryController@create`, `@store` | Đúng luồng |
| 3.31 | Sửa danh mục | OK | `AdminCategoryController@edit`, `@update` | Đúng luồng |
| 3.32 | Xóa danh mục | OK | `AdminCategoryController@destroy` | Đúng: còn SP (hoặc còn danh mục con) → không xóa; trống → xóa. Method check gắn trong `destroy`, không tên `checkProductsExist()` |

---

## Chi tiết theo nhóm

### A. Khách hàng (3.14–3.19)

#### Hình 3.14 — Đăng ký
**Khớp:** Trang chủ → đăng ký → validate → tạo user pending → gửi / yêu cầu xác thực email → `activate` → chuyển login.  
**Lệch tên:** `signup` → `showRegisterForm`; `createUser` → `register`; `UserModel` → `User`.  
**Ghi chú:** Email trùng / format lỗi do Laravel Validation, không có method `checkEmailExist()` tường minh trên Model.

#### Hình 3.15 — Quên mật khẩu
**Khớp:** Login → quên MK → nhập email → gửi link → trang đổi MK → validate → cập nhật → báo success/fail.  
**Lệch cấu trúc:** Logic tách `ForgotPasswordController` + `ResetPasswordController` (Laravel Password broker), không gói trong một `AuthController` như sơ đồ.

#### Hình 3.16 — Cập nhật thông tin cá nhân
**Khớp:** Profile → xem thông tin → sửa → kiểm tra → lưu / báo lỗi.  
**Lệch tên:** `UserController` → `AccountController`; `BasicInforUser` → `index`; `editUser` → `update`. UI thường 1 trang account (tab), không tách biên “Trang Thông Tin Cá Nhân” riêng.

#### Hình 3.17 — Lịch sử đơn hàng
**Khớp:** Profile → lấy đơn của user → hiển thị danh sách.  
**Lệch tên:** `OderController` (sai chính tả) / `OrderHistoryUser` → `AccountController@showOrder` (hoặc load trong `index`).

#### Hình 3.18 — Theo dõi trạng thái / chi tiết đơn
**Khớp:** Chọn 1 đơn → load `Order` (+ items, payment, logs) → trang chi tiết (có badge trạng thái).  
**Lệch tên:** `UserController.OrderDetail` → `AccountController@showOrderDetail`.

#### Hình 3.19 — Wishlist
**Khớp:** Profile → mục “Sản phẩm yêu thích” (`#wishlist`) → hiển thị danh sách.  
API thêm/xóa vẫn qua `WishlistController` (AJAX / header modal vẫn dùng được).

---

### B. Admin — tài khoản (3.20–3.23)

| Điểm | Sơ đồ | Code |
|------|-------|------|
| List | `UserList()` | `AdminUserController@index` |
| Search | `searchUser()` riêng + alt “không tìm thấy” | Cùng `index` + `q`; kết quả rỗng = bảng trống |
| Detail | `UserDetail()` + trang chi tiết | `AdminUserController@show` |
| Block | `BlockUser()` + (nhầm) `OrderModel` | `toggleBlock()` — `active` ↔ `blocked`; không tự khóa / không khóa `pending` |

---

### C. Admin — role (3.24–3.25)

Luồng **khớp tốt**: kiểm tra quyền (middleware `permission`) → form → validate → `Role` + sync `permissions` → flash message.  
Chỉ lệch tên method kiểu PascalCase trong sơ đồ vs REST Laravel (`create`/`store`/`edit`/`update`).

---

### D. Admin — sản phẩm (3.26–3.29)

- Thêm / sửa: **khớp**.
- Xóa (3.28): **khớp nghiệp vụ soft-ish / hard** — điểm mạnh của sơ đồ so với code.
- Import (3.29): **khớp luồng**; thực tế CSV (`importForm` / `importStore`), không phải Excel thuần (có thể ghi trong luận văn “CSV/Excel”).

---

### E. Admin — danh mục (3.30–3.32)

- Thêm / sửa: **khớp**.
- Xóa: **khớp** điều kiện còn sản phẩm (code còn chặn thêm khi còn **danh mục con** — sơ đồ có thể bổ sung).

---

## Lệch “diễn đạt” chung (áp dụng hầu hết sơ đồ)

1. Model trên sơ đồ: `UserModel`, `ProductModel`,… → Code: `User`, `Product`, `Role`,…  
2. Method Model riêng (`UserList()`, `storeProduct()` trên Model) → Code dùng Eloquent trực tiếp trong Controller (`User::…`, `$product->update()`).  
3. Tên Controller: một số sơ đồ bỏ tiền tố `Admin` hoặc dùng `UserController` phía client thay vì `AccountController`.  
4. Kiểm tra quyền: sơ đồ vẽ `alt [Có quyền]` trong Controller → Code dùng **middleware** `admin` / `permission:manage_*` (đúng ý, khác chỗ vẽ).

---

## Việc nên làm với luận văn (không bắt buộc sửa code)

1. **Sửa tên** trên sơ đồ cho sát code (hoặc ghi chú “tương đương Laravel resource”).  
2. **Hình 3.19 / 3.22:** đã bổ sung trong code (tab Yêu thích trong Account; trang `show` admin user).  
3. **Hình 3.23:** `User` + `toggleBlock()` (`active` ↔ `blocked`); không còn vô hiệu hóa / `destroy`.  
4. **Hình 3.15:** ghi 2 controller Forgot/Reset.  
5. **Hình 3.29:** ghi Import **CSV**.

---

## Tỷ lệ nhanh

| Nhóm | Số hình | Đánh giá |
|------|---------|----------|
| OK (luồng + nghiệp vụ) | ~10 | Role, Product CRUD, Category, Wishlist Account, User show |
| GẦN (đúng nghiệp vụ, lệch tên/UI) | ~9 | Auth, Account profile/orders, Admin user list/search/block, Import |
| LỆCH đáng kể | 0 | — |

**Verdict:** Dự án **đã triển khai đúng phần lớn sequence**; an toàn bảo vệ khi bảo luận nếu giải thích mapping tên Laravel.
