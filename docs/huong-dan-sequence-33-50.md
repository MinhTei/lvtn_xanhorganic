# So sánh + hướng dẫn vẽ lại sequence Hình 3.33–3.50

Đối chiếu lô sơ đồ mua hàng / thanh toán / đánh giá / đơn hàng admin / coupon / dashboard với code hiện tại.  
**Đã chỉnh code** (đợt này): wishlist **toggle**, giỏ hàng **cắt về max tồn**, mail khi **đổi trạng thái đơn**.

---

## Bảng tổng hợp

| Hình | Chức năng | TT | Code thật | Việc làm khi vẽ lại |
|------|-----------|----|-----------|---------------------|
| 3.33 | Tìm SP | GẦN | `ProductController@index?q=` | Đổi `searchProduct()` → `index()`; Model = `Product` |
| 3.34 | Chi tiết SP | GẦN | `ProductDetailController@showProductDetail` | Không dùng `ProductController`; load reviews trong cùng action |
| 3.35 | Yêu thích (trái tim) | OK* | `WishlistController@store` → `ClientWishlist::add` (**toggle**) | `Wishlist` (không `WishlistModel`); message thêm/xóa theo `action` |
| 3.36 | Thêm giỏ | GẦN | `CartController@store` → `ClientCart::add` | Kiểm tra tồn trong service; tên `add`/`store` |
| 3.37 | Cập nhật giỏ | OK* | `update` / `destroy`; quá tồn → **cắt max + báo** | Method: `update`, `destroy`; Model `Cart`/`Product` |
| 3.38 | Thiết lập giao hàng | OK | `CheckoutController@index`, `@applyCoupon` | Điền địa chỉ / khung giờ / mã / PTTT; không kiểm tra giỏ trống |
| 3.39 | Thanh toán đơn hàng | OK | `store()` + COD / VNPay | Tập trung 2 phương thức thanh toán |
| 3.40 | Thêm đánh giá | OK | `AccountController@storeReview` trên **chi tiết đơn đã giao** | Không sửa/xóa tại đây |
| 3.41 | Sửa đánh giá | OK | `ProductDetailController@updateReview` | Trang chi tiết SP + `Product` + `ProductReview` |
| 3.42 | Xóa đánh giá | OK | `ProductDetailController@destroyReview` | Trang chi tiết SP + `Product` + `ProductReview` |
| 3.43 | Tìm đơn admin | GẦN | `AdminOrderController@index?q=` | Không tách `searchOrder()` |
| 3.44 | Cập nhật TT đơn | OK* | `updateStatus` + check tồn + **gửi mail** | Method `updateStatus`; invoice riêng |
| 3.45 | In hóa đơn | GẦN | `show` rồi `invoice()` (DomPDF stream) | Controller = `AdminOrderController`; không `printOrder` trên Model |
| 3.46 | Thêm coupon | OK | `AdminCouponController@create`/`store` | Đổi tên REST |
| 3.47 | Sửa coupon | OK | `edit`/`update` | Giống trên |
| 3.48 | Dashboard | GẦN | `AdminDashboardController@index` | **Không** `AdminReportController` |
| 3.49 | Lọc thống kê | GẦN | Cùng `index` + `range`/`from`/`to` | Không `ReportByDate()` riêng |
| 3.50 | Xuất báo cáo | GẦN | `export` (CSV) + `exportPdf` (PDF) | Ghi CSV+PDF, không “Excel thuần” |

\*Đã chỉnh code cho khớp sơ đồ.

---

## Checklist vẽ lại từng hình

### Hình 3.33 — Tìm kiếm sản phẩm

**Lifeline:** Khách | Trang SP | `ProductController` | `Product`

1. Nhập từ khóa → `index()` với `q`  
2. Query SP active  
3. `alt` rỗng / có kết quả  

---

### Hình 3.34 — Chi tiết sản phẩm

**Lifeline:** Khách | Trang Chi tiết SP | **`ProductDetailController`** | `Product` | `ProductReview`

1. Chọn SP → `showProductDetail($slug)`  
2. Load product + reviews (+ related)  
3. Hiển thị  

---

### Hình 3.35 — Lưu yêu thích (toggle)

**Lifeline:** Khách | Trang Chi tiết SP | `WishlistController` | `Wishlist`

1. Nhấn trái tim → `store()`  
2. Validate `product_id`  
3. `alt` chưa có → thêm; đã có → xóa  
4. Thông báo + cập nhật badge  

---

### Hình 3.36 — Thêm giỏ hàng

**Lifeline:** Khách | Trang Chi tiết SP | `CartController` | `Cart` / `Product` (qua `ClientCart`)

1. Chọn SL → `store()`  
2. Kiểm tra tồn trong `ClientCart::add`  
3. `alt` không đủ → báo lỗi; đủ → thêm thành công  

Đổi `checkQuality` → “Kiểm tra tồn kho”.

---

### Hình 3.37 — Cập nhật giỏ

**Lifeline:** Khách | Trang Giỏ | `CartController` | `Cart` | `Product`

- **Xóa:** `destroy($productId)`  
- **Đổi SL:** `update()` → nếu SL > tồn → **cắt về max + thông báo**  

---

### Hình 3.38 — Thiết lập thông tin giao hàng

**Lifeline:** Khách | Trang Giỏ | Trang Thanh toán | **`CheckoutController`** | `UserAddress` | `Coupon`

1. Tiến hành đặt hàng → `alt` đã đăng nhập / chưa đăng nhập  
2. Đã login: `index()` → load địa chỉ → `return checkoutForm`  
3. Điền thông tin giao hàng, mã giảm giá, chọn tùy chọn  
4. Áp mã → `applyCoupon()` → `validateCoupon()` → cập nhật tạm tính / báo lỗi  
5. Chưa login: chuyển trang đăng nhập  

**Không** vẽ kiểm tra giỏ trống, merge session trên sơ đồ này.

---

### Hình 3.39 — Thanh toán đơn hàng

**Lifeline:** Khách | Trang Giỏ | Trang Thanh toán | Thanh toán VNPay | `CheckoutController` | `VnPayController` | `Order`

Bố cục giống sơ đồ luận văn cũ; method đúng dự án:

1. Tiến hành đặt hàng → `index()` → `return checkoutForm`  
2. Điền TT + chọn PTTT → `store()` → `validate()`  
3. `alt [COD]`: `createOrder()` → `sendOrderConfirmation()` → thông báo + email  
4. `alt [VNPay]`: `createOrder()` **trước** → `createPaymentUrl()` → cổng VNPay → `return()` → `applyPaymentResult()`  
   - Thành công: `updatePayment()` + email  
   - Thất bại: `cancelOrder()` (hủy đơn, hoàn kho, trả giỏ)  

**Khác sơ đồ cũ:** không dùng `OrderController` / `CheckOutMethod` / `processVNPayPayment`; và **không** tạo đơn sau khi VNPay thành công.

---

### Hình 3.40 — Thêm đánh giá

**Lifeline:** Khách | Trang Chi tiết đơn (đã giao) | `AccountController` | `Order` | `ProductReview`

1. Xem đơn **đã nhận hàng** (chỉ lúc này mới hiện nút đánh giá)  
2. Viết đánh giá → `storeReview()` → `checkOrderDelivered()` (xác nhận đã giao)  
3. `alt` chưa đánh giá → `create()` thành công; đã đánh giá → báo lỗi  

**Không** đặt Review trên `UserController` / trang chi tiết SP.  
**Không** vẽ luồng đánh giá khi đơn chưa giao (UI không cho).


---

### Hình 3.41 / 3.42 — Sửa / xóa đánh giá

**Lifeline:** Khách | Trang Chi tiết sản phẩm | `ProductDetailController` | `Product` | `ProductReview`

1. `showProductDetail()` → `getProductDetail()` + `getReviews()` → hiển thị trang  
2. Sửa: `updateReview()` → `validate()` → `update()`  
3. Xóa: `destroyReview()` → `validate()` → `delete()`  

**Thêm đánh giá mới** vẫn ở **chi tiết đơn đã giao** (`AccountController@storeReview`).

---

### Hình 3.43 — Tìm đơn admin

`AdminOrderController@index` + `q` (+ lọc `status`).  
Model: `Order`.

---

### Hình 3.44 — Cập nhật trạng thái đơn

**Lifeline:** Admin | Trang chi tiết đơn | `AdminOrderController` | `Order` | `OrderStatusLogs`

1. `show` danh sách hoặc chi tiết  
2. `updateStatus()` → validate luồng STATUS_FLOW  
3. Nếu → `processing`: `assertStockEnoughToConfirm`  
4. Cập nhật + log  
5. **Gửi email** `OrderStatusUpdatedMail` cho khách  
6. Thông báo success / lỗi  

Đổi `AllOrder` → `index` / `show`; `updateStatusOrder` → `updateStatus`.

---

### Hình 3.45 — In hóa đơn

1. `show($order)`  
2. Nhấn In → `invoice($order)` → DomPDF stream  
Không cần method `printOrder` trên Model.

---

### Hình 3.46 / 3.47 — Coupon

`AdminCouponController`: `create`/`store`, `edit`/`update` + middleware quyền.  
Model: `Coupon`.

---

### Hình 3.48 — Thống kê tổng quan

**Lifeline:** Admin | Trang Dashboard | **`AdminDashboardController`** | `Order` | `Product` / `OrderItem`

Một lần gọi `index()` → `buildReportData()` (doanh thu, số đơn, status chart, top SP…).  
Không tách 4 method `Revenue()` / `TopProduct()`… trên sơ đồ trừ khi muốn minh họa *bên trong* private helpers (ghi chú “logic trong `buildReportData`”).

---

### Hình 3.49 — Lọc theo thời gian

Cùng `index()` với query `range` (`today|7|30|month|custom`) + `from`/`to`.  
**Không** method `ReportByDate()` riêng.

---

### Hình 3.50 — Xuất báo cáo

1. Đang xem dashboard (đã lọc)  
2. Nút Xuất CSV → `export()`  
3. Nút Xuất PDF → `exportPdf()`  
Ghi chú: CSV mở được bằng Excel; thêm PDF.

---

## Code đã chỉnh để khớp (đợt này)

| Thay đổi | File |
|----------|------|
| Wishlist nhấn trái tim = toggle | `ClientWishlist::add` |
| Cập nhật giỏ vượt tồn → cắt max + message | `ClientCart::update` |
| Mail khi admin đổi trạng thái đơn | `OrderStatusUpdatedMail` + `AdminOrderController@updateStatus` |

## Không đổi code (sửa sơ đồ)

| Điểm | Lý do |
|------|-------|
| VNPay tạo đơn **trước** redirect | Đúng chuẩn cổng thanh toán (`vnp_TxnRef` = mã đơn) |
| Checkout = `CheckoutController` | Không có `OrderController` |
| Dashboard = `AdminDashboardController` | Không rename chỉ để khớp sơ đồ |
| Review trên order detail | Đã đúng nghiệp vụ “chỉ đánh giá khi đã giao” |

---

## Chú thích luận văn (bổ sung)

> Luồng đặt hàng với VNPay: hệ thống tạo đơn ở trạng thái chờ và bản ghi thanh toán `pending` trước khi chuyển hướng cổng; kết quả thanh toán cập nhật qua URL return/IPN. Xuất báo cáo dashboard hỗ trợ CSV và PDF theo cùng bộ lọc thời gian.
