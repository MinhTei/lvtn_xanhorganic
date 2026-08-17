# Phân tích Luồng Code (Code Flow) & Phản biện kỹ thuật chuyên sâu

Tài liệu này không chỉ giải thích luồng nghiệp vụ trên lý thuyết, mà đi sâu vào việc giải thích **code chạy từ đâu đến đâu**, Controller nào gọi Service nào, giúp bạn làm chủ hoàn toàn mã nguồn đồ án.

---

## 1. Luồng chạy Code: Thêm vào Giỏ hàng (Add to Cart)

- **UI / Route:** Khách hàng ấn nút "Thêm vào giỏ" -> Trình duyệt bắn một AJAX Request (POST) lên Server.
- **Controller (`CartController@store`):**
  - Bước 1: `Validator` kiểm tra dữ liệu đầu vào (`product_id` có tồn tại không, `quantity` > 1 không). Nếu lỗi -> Return lỗi 422 JSON ngay lập tức.
  - Bước 2: Gọi sang tầng dịch vụ `ClientCart::add($productId, $quantity)`.
- **Service (`ClientCart::add`):**
  - Kéo `Product` ra để kiểm tra số lượng tồn kho (`$stock`). Tính toán tổng số lượng yêu cầu.
  - Kiểm tra Auth: `if (Auth::check())` -> Tìm trong Database bảng `carts`. Nếu đã có thì `update` cộng dồn số lượng, chưa có thì `create` mới.
  - Nếu `!Auth::check()` -> Cập nhật số lượng vào mảng PHP Session (`session(['cart' => $cartSession])`).
- **Response:** Trả về JSON cho Client (`success: true`) để giao diện hiện thông báo "Thêm thành công" mà không load lại trang.

> **💡 Q&A Phản biện 1:** 
> *Thầy cô: "Tại sao em không viết thẳng logic kiểm tra tồn kho, thêm session vào trong `CartController` mà lại gọi sang `ClientCart` làm gì?"*
> *Bạn:* "Dạ để tuân thủ nguyên lý **Single Responsibility (Trách nhiệm đơn lẻ)** trong thiết kế phần mềm. Controller chỉ nên nhận Request và trả về Response. Việc tính toán nghiệp vụ (Business Logic) phức tạp được em tách ra một Service Pattern (lớp `ClientCart`). Nhờ vậy, ở bất kỳ chỗ nào cần gọi thêm/bớt giỏ hàng (ví dụ sau này viết API cho App Mobile), em chỉ cần gọi lại hàm `ClientCart::add()` mà không phải copy lại code ạ."

---

## 2. Luồng chạy Code: Đồng bộ Giỏ hàng khi Đăng nhập

- **Trigger:** Người dùng nhập Email/Password, nhấn Đăng nhập thành công.
- **Service (`ClientCart::mergeSessionToUser`):**
  - Hệ thống lấy mảng giỏ hàng đang lưu tạm trong Session (`$cartSession`).
  - Dùng vòng lặp `foreach` duyệt qua từng món hàng. Kiểm tra trong DB bảng `carts` của `$user` này xem có sản phẩm này chưa.
  - Tính toán: Lấy số lượng đang có trong DB + Số lượng trong Session. Nếu vượt kho, dùng hàm `min()` để lấy số lượng kho tối đa.
  - Update thẳng vào Database và cuối cùng xóa Session rác (`session()->forget('cart')`).

> **💡 Q&A Phản biện 2:**
> *Thầy cô: "Làm sao hệ thống xử lý được tình trạng nếu giỏ hàng cũ của khách đã có 5 quả táo, khách chưa đăng nhập thêm 10 quả táo nữa, mà trong kho chỉ còn 12 quả?"*
> *Bạn:* "Dạ, trong code của hàm `mergeSessionToUser`, em có gài một logic ép giới hạn tồn kho. Cụ thể em tính biến tổng `$totalInCart = 5 + 10 = 15`. Sau đó em chạy lệnh `$finalQty = min($totalInCart, $product->quantity)`. Hàm `min(15, 12)` sẽ tự động lấy số 12 và lưu vào Database, đảm bảo không bao giờ xảy ra lỗi 'bán khống' hàng ạ."

---

## 3. Luồng chạy Code: Thanh toán & Xử lý VNPay (VNPay Flow)

- **Checkout (Chốt đơn):** Controller gom `Cart`, trừ mã giảm giá (áp `coupon_usages`), tạo `Order`, copy data sang `OrderItems`, gỡ giỏ hàng, chuyển hướng sang VNPay.
- **VNPay gọi ngược lại (IPN - `VnPayController@ipn`):**
  - Bước 1: Gọi hàm thư viện `VnPayService::verifyCallback($request)` để băm mã (Hash) chuỗi query trả về và so sánh với Chữ ký (Signature) VNPay. Nếu sai lệch => Hàm trả về lỗi `Invalid Signature`.
  - Bước 2: Tìm mã đơn hàng `Order::where('order_code')`.
  - Bước 3: Hàm private `applyPaymentResult` sẽ bật DB Transaction (`DB::transaction`). Nó khóa bản ghi thanh toán (`lockForUpdate()`) để tránh tình trạng nhiều request đánh vào cùng 1 lúc (Race Condition).
  - Bước 4: Nếu VNPay báo `RspCode == 00` (Thành công): Update `payment_status = 'completed'`, lưu Log vào `order_status_logs`, dùng `Mail::to()` gửi Email xác nhận.
  - Bước 5 (Nếu thất bại): Update trạng thái đơn `cancelled`. Lặp qua `orderItems` và **+ lại số lượng tồn kho** cho Product. Sau đó gọi `ClientCart::restoreFromOrder` để "nhả" lại hàng vào giỏ cho khách đặt lại.

> **💡 Q&A Phản biện 3:**
> *Thầy cô: "Hàm IPN của VNPay nó có thể gọi ngầm về Server em nhiều lần. Lỡ nó gọi 2 lần cùng báo thành công thì hệ thống em có bị nhân đôi email hoặc nhân đôi logic xử lý không?"*
> *Bạn:* "Dạ không ạ. Khi IPN gọi về, trước khi xử lý, em đã kiểm tra điều kiện: `if ($payment->payment_status === 'completed') { return; }`. Nghĩa là nếu đơn hàng đã được đánh dấu là 'completed' từ lần gọi đầu tiên rồi, thì các lần VNPay gọi sau, code sẽ tự động thoát ra ngay lập tức, ngăn chặn việc xử lý lặp lại."

---

## 4. Luồng chạy Code: Cấp quyền và Phân quyền linh hoạt

- **Database:** Bảng `role_permissions` lưu ID của Role và ID của Quyền (Ví dụ: Manage_Categories).
- **Model (`User@canAccessModule` hoặc `User@hasPermission`):**
  - Khi cần kiểm tra quyền (Ví dụ ở file Blade hoặc Controller), hệ thống gọi hàm `$user->hasPermission('manage_products')`.
  - Lệnh này sẽ query qua hàm quan hệ (`$this->role->permissions()`), lôi toàn bộ các tên quyền mà Role của User đó đang có ra một mảng. Dùng hàm `in_array` của PHP để check xem quyền 'manage_products' có nằm trong mảng đó không. Trả về `True/False`.
- **Hiệu năng:** Kết quả mảng quyền được **Cache (Lưu nháp)** ngay trong property của Object User (`$this->permissionNamesCache`), giúp cho dù trong 1 trang có check quyền 100 lần thì cũng chỉ tốn đúng 1 câu query SQL đầu tiên, hệ thống chạy cực kỳ nhanh.

> **💡 Q&A Phản biện 4:**
> *Thầy cô: "Việc em check quyền như vậy có làm trang web bị chậm đi do gọi Database liên tục không?"*
> *Bạn:* "Dạ như em vừa trình bày, hệ thống không gọi Database liên tục. Trong lớp Model `User.php`, em đã thiết kế một mảng nội bộ (caching property). Ở câu query đầu tiên, em lấy toàn bộ các Quyền và lưu vào `$this->permissionNamesCache`. Mọi lời gọi kiểm tra quyền phía sau đều đọc thẳng từ bộ nhớ RAM của PHP thông qua hàm `in_array`, tốc độ phản hồi tính bằng micro-giây và giảm thiểu tuyệt đối tải (load) cho Database ạ."
