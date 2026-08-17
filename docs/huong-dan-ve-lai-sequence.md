# Hướng dẫn vẽ lại sơ đồ tuần tự cho khớp dự án Xanh Organic

Tài liệu dùng khi **chỉnh / vẽ lại** các hình sequence (khoảng Hình 3.14–3.32) sao cho trùng luồng code Laravel hiện tại, vẫn giữ format luận văn (Actor → Boundary → Control → Entity).

---

## 1. Quy ước đặt tên (bắt buộc dùng thống nhất)

| Trên sơ đồ cũ (tránh) | Trên sơ đồ mới (dùng) |
|------------------------|------------------------|
| `UserModel`, `ProductModel`, `RoleModel`… | `User`, `Product`, `Role`, `Order`, `Category`, `Wishlist`… |
| `UserController` (phía khách) | `AccountController` |
| `OderController` | `AccountController` |
| `AuthController` cho cả quên MK | Tách `ForgotPasswordController`, `ResetPasswordController` |
| `signup()`, `createUser()`, `UserList()`, `BlockUser()`… | Method Laravel thực tế (bảng dưới) |
| Gọi method cùng tên trên Model | Controller gọi Eloquent: `User::…`, `$product->update()`, `$role->permissions()->sync()` |

### Bảng map method hay dùng

| Nghiệp vụ | Controller | Method trên sơ đồ |
|-----------|------------|-------------------|
| Form / xử lý đăng ký | `AuthController` | `showRegisterForm()`, `register()`, `activate()` |
| Đăng nhập | `AuthController` | `showLoginForm()`, `login()` |
| Quên MK — hiện form / gửi mail | `ForgotPasswordController` | `showForgotPasswordForm()`, `sendResetLinkEmail()` |
| Đặt lại MK — form / lưu | `ResetPasswordController` | `showResetPasswordForm()`, `resetPassword()` |
| Xem / sửa hồ sơ | `AccountController` | `index()`, `update()` |
| Lịch sử đơn | `AccountController` | `index()` (tab `#orders`) hoặc `showOrder()` |
| Chi tiết đơn | `AccountController` | `showOrderDetail()` |
| Wishlist trong Account | `AccountController` + `WishlistController` | `index()` load list; xóa AJAX → `WishlistController@destroy` |
| DS / tìm user admin | `AdminUserController` | `index()` (kèm `?q=`) |
| Chi tiết user | `AdminUserController` | `show()` |
| Khóa / mở khóa | `AdminUserController` | `toggleBlock()` (`active` ↔ `blocked`) |
| Role CRUD | `AdminRoleController` | `create`, `store`, `edit`, `update` |
| Sản phẩm | `AdminProductController` | `create`, `store`, `edit`, `update`, `destroy` |
| Import SP | `AdminProductController` | `importForm()`, `importStore()` |
| Danh mục | `AdminCategoryController` | `create`, `store`, `edit`, `update`, `destroy` |

### Lifeline chuẩn (4–5 cột)

1. **Actor:** `Khách hàng` hoặc `Admin`
2. **Boundary:** tên trang UI tiếng Việt (vd. `Trang Quản Lý Tài Khoản`)
3. **Control:** đúng tên class Controller ở trên
4. **Entity:** Model Eloquent (không hậu tố `Model`)
5. (Tuỳ chọn) Boundary thứ 2 nếu có trang con (form thêm / chi tiết)

### Quyền hạn — cách vẽ đúng với dự án

**Không bắt buộc** vẽ `alt [Có quyền]` bên trong Controller nếu muốn sát code.

Hai cách chấp nhận được:

**Cách A (sát code — khuyến nghị):**  
Trước message vào Controller, ghi note:

> Middleware: `auth` + `admin` + `permission:manage_*`

**Cách B (giữ như cũ):**  
Giữ `alt [Có quyền] / [Không có quyền]` nhưng chú thích dưới hình: *tương đương middleware phân quyền*.

---

## 2. Chú thích cố định chèn dưới mỗi nhóm sơ đồ (copy vào luận văn)

> Các sơ đồ tuần tự mô tả luồng nghiệp vụ theo mô hình MVC. Tên phương thức trên sơ đồ trùng với action của Controller trong mã nguồn Laravel. Thao tác CSDL thực hiện qua Eloquent Model (ví dụ `User`, `Product`). Kiểm tra quyền admin/staff được đảm bảo bởi middleware `permission` trước khi vào Controller.

---

## 3. Checklist vẽ lại từng hình

### Hình 3.14 — Đăng ký

**Lifeline:** Người dùng | Trang Chủ | Trang Đăng Ký | `AuthController` | `User`

**Luồng đề xuất:**
1. Truy cập trang chủ → mở trang đăng ký  
2. `showRegisterForm()` → hiển thị form  
3. Điền TT → `register()`  
4. Controller: validate (gộp luôn check email trùng bằng rule `unique`)  
5. `alt` thành công: tạo `User` (`status=pending`, `activation_token`) → thông báo kiểm tra email  
6. Người dùng mở link → `activate($token)` → `status=active` → chuyển trang đăng nhập  
7. `alt` lỗi format / email trùng → thông báo trên form đăng ký  

**Bỏ:** method riêng `checkEmailExist()` trên Model (hoặc ghi note “thực hiện trong Validation”).

---

### Hình 3.15 — Quên mật khẩu

**Lifeline:** Người dùng | Trang Đăng Nhập | Trang Quên MK | Trang Đặt lại MK | `ForgotPasswordController` | `ResetPasswordController` | (`password_reset_tokens` / `User`)

**Luồng đề xuất:**
1. Từ login → `showForgotPasswordForm()`  
2. Nhập email → `sendResetLinkEmail()` → gửi mail (Password broker)  
3. Mở link mail → `showResetPasswordForm()`  
4. Nhập MK mới → `resetPassword()` → cập nhật `User` + xóa token → về login  

**Ghi chú dưới hình:** Token lưu bảng `password_reset_tokens`, không lưu trên `users`.

---

### Hình 3.16 — Cập nhật thông tin cá nhân

**Lifeline:** Khách hàng | Trang Quản Lý Tài Khoản | `AccountController` | `User`

**Luồng:**
1. Đăng nhập → Profile → `index()` (panel `#profile`)  
2. Sửa → `update()` → validate → `$user->save()` → thông báo thành công / lỗi  

Có thể gộp 1 boundary “Trang Tài khoản” (không tách 2 trang nếu UI là tab).

---

### Hình 3.17 — Lịch sử đơn hàng

**Lifeline:** Khách hàng | Trang Tài khoản | `AccountController` | `Order`

**Luồng:**
1. Profile → chọn “Lịch sử đơn hàng”  
2. `index()` / `showOrder()` → `Order::where('user_id', …)` → hiển thị danh sách  

Sửa chính tả: **không** dùng `OderController`.

---

### Hình 3.18 — Chi tiết / theo dõi trạng thái đơn

**Lifeline:** Khách hàng | Trang Lịch sử đơn | Trang Chi tiết đơn | `AccountController` | `Order`

**Luồng:**
1. Chọn 1 đơn → `showOrderDetail($order)`  
2. Load quan hệ: `orderItems`, `orderPayment`, `orderStatusLogs`  
3. Hiển thị chi tiết + badge trạng thái  

---

### Hình 3.19 — Sản phẩm yêu thích

**Lifeline:** Khách hàng | Trang Tài khoản (panel Yêu thích) | `AccountController` | `Wishlist` / `Product`  
*(Tuỳ chọn thêm)* `WishlistController` cho thao tác xóa AJAX

**Luồng:**
1. Profile → “Sản phẩm yêu thích”  
2. `AccountController@index` load `ClientWishlist::items()` → hiển thị list  
3. (Tuỳ chọn) Xóa item → `WishlistController@destroy` → refresh list  

---

### Hình 3.20 — Danh sách tài khoản (Admin)

**Lifeline:** Admin | Trang Quản lý | Trang QL người dùng | `AdminUserController` | `User`

**Luồng:** Truy cập admin → `index()` → trả danh sách (+ phân trang) → show UserList.

---

### Hình 3.21 — Tìm kiếm tài khoản

**Lifeline:** Admin | Trang QL người dùng | `AdminUserController` | `User`

**Luồng (trong `loop` vẫn được):**
1. Nhập từ khóa → `index()` với tham số `q`  
2. `User` where name/email/phone like  
3. `alt`: có kết quả → hiển thị bảng; không → bảng trống / “Không có người dùng”

**Không** tách method `searchUser()` trừ khi muốn ghi chú “tương đương `index(?q=)`”.

---

### Hình 3.22 — Chi tiết tài khoản

**Lifeline:** Admin | Trang QL người dùng | Trang Chi tiết tài khoản | `AdminUserController` | `User`

**Luồng:** Nhấn Chi tiết → `show($user)` → load `role`, `addresses`, đơn gần đây → hiển thị.

---

### Hình 3.23 — Khóa / mở khóa tài khoản

**Lifeline:** Admin | Trang QL người dùng | `AdminUserController` | **`User`**

**Luồng đề xuất:**
1. Nhấn **Khóa** (active) hoặc **Mở khóa** (blocked)  
2. Middleware quyền `manage_users`  
3. `toggleBlock()` — kiểm tra: không tự khóa; không khóa tài khoản `pending`  
4. `active` → `update(status=blocked)` **hoặc** `blocked` → `update(status=active)`  
5. Thông báo đã khóa / đã mở khóa / lỗi điều kiện  

---

### Hình 3.24 — Thêm role

**Lifeline:** Admin | Trang QL Role | Trang Thêm Role | `AdminRoleController` | `Role` (+ `Permission` sync)

**Luồng:** `create()` → form → `store()` → validate → tạo Role + `permissions()->sync()` → thông báo.

---

### Hình 3.25 — Cập nhật role & quyền

**Lifeline:** Admin | Trang QL Role | **Trang Sửa Role** | `AdminRoleController` | `Role`

**Luồng:** `edit()` → form → `update()` → sync permissions → thông báo.  
**Đổi tên boundary:** “Trang Sửa Role” (không ghi “Thêm Role mới”).

---

### Hình 3.26 — Thêm sản phẩm

**Lifeline:** Admin | Trang QL SP | Trang Thêm SP | `AdminProductController` | `Product`

**Luồng:** `create()` → `store()` → validate → lưu (+ ảnh) → thông báo.

---

### Hình 3.27 — Sửa sản phẩm

Tương tự: `edit()` → `update()`.

---

### Hình 3.28 — Xóa sản phẩm

**Lifeline:** Admin | Trang QL SP | `AdminProductController` | `Product` | `OrderItem`

**Luồng (giữ `alt` nghiệp vụ — rất tốt cho bảo vệ):**
1. `destroy($product)`  
2. Kiểm tra `$product->orderItems()->exists()`  
3. **Có đơn** → cập nhật `is_active = false` (ẩn, giữ lịch sử) → thông báo ẩn  
4. **Không đơn** → xóa ảnh + `delete()` → thông báo xóa  

Ghi chú: hai nhánh nằm **trong** `destroy()`, không cần 2 method `softDelete` / `hardDelete` riêng.

---

### Hình 3.29 — Import sản phẩm

**Lifeline:** Admin | Trang QL SP | Trang Import | `AdminProductController` | `Product`

**Luồng:** `importForm()` → chọn file **CSV** → `importStore()` → validate file/dòng → lưu → thông báo.  
Đổi chữ “Excel” thành **CSV** (hoặc “CSV (mở được bằng Excel)”).

---

### Hình 3.30 — Thêm danh mục

`AdminCategoryController`: `create()` → `store()` → `Category`.

---

### Hình 3.31 — Sửa danh mục

`edit()` → `update()`.

---

### Hình 3.32 — Xóa danh mục

**Lifeline:** Admin | Trang QL DM | `AdminCategoryController` | `Category` | `Product`

**Luồng:**
1. `destroy($category)`  
2. `alt` còn sản phẩm **hoặc còn danh mục con** → báo không xóa được  
3. `alt` trống → `$category->delete()` → thành công  

---

## 4. Mẫu message tiếng Việt trên mũi tên (gợi ý)

- Yêu cầu trang: `Truy cập …` / `Nhấn …` / `Nhập thông tin và Lưu`  
- Gọi Controller: đúng tên method tiếng Anh như code (`register()`, `index()`, …)  
- Phản hồi: `Trả về kết quả` / `Hiển thị …` / `Thông báo … thành công` / `Thông báo lỗi / không có quyền`  

Không cần đổi message UI sang tiếng Anh.

---

## 5. Thứ tự làm việc khi vẽ lại (thực tế)

1. Sửa lỗi sai sự thật trước: **3.23** (`User`), **3.15** (2 controller), **3.17** (chính tả), **3.25** (trang Sửa), **3.29** (CSV).  
2. Đổi hàng loạt tên Controller/Model theo mục 1.  
3. Đổi tên method theo bảng map.  
4. Dán chú thích cố định (mục 2) vào đầu mục 3.2.2.  
5. So nhanh với `docs/so-sanh-sequence-diagram.md` — không còn dòng LỆCH.

---

## 6. File tham chiếu trong repo

| File | Nội dung |
|------|----------|
| `docs/so-sanh-sequence-diagram.md` | So sánh sơ đồ cũ ↔ code |
| `docs/3.1.2-mo-ta-cac-loai-thuc-the.md` | Thực thể / bảng CSDL đúng schema |
| `routes/web.php`, `routes/admin.php` | Route & tên action thật |
| `app/Http/Controllers/...` | Chốt method khi nghi ngờ |

Tool vẽ gợi ý: StarUML / Visual Paradigm / PlantUML / draw.io (UML Sequence) — giữ style luận văn hiện tại, chỉ đổi nhãn lifeline và message.

---

## Lô tiếp theo (Hình 3.33–3.50)

Xem file riêng: [`docs/huong-dan-sequence-33-50.md`](huong-dan-sequence-33-50.md) — so sánh + checklist vẽ lại mua hàng / thanh toán / đánh giá / đơn admin / coupon / dashboard.
