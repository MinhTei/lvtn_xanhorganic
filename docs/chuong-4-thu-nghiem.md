# Chương 4. THỬ NGHIỆM

Chương này trình bày việc kiểm thử chức năng của hệ thống website bán hàng thực phẩm sạch Xanh Organic theo các kịch bản nghiệp vụ chính. Hình thức kiểm thử là kiểm thử thủ công theo kịch bản chức năng. Người kiểm thử thao tác trực tiếp trên giao diện và đối chiếu kết quả với hành vi đã thiết kế trong hệ thống.

## 4.1.1 Các kịch bản thử nghiệm

| Mã | Kịch bản | Người thực hiện | Điều kiện và dữ liệu | Các bước chính | Kết quả mong đợi |
|----|----------|-----------------|----------------------|----------------|------------------|
| TC01 | Đăng ký và kích hoạt tài khoản | Khách hàng | Email chưa tồn tại trong hệ thống | Đăng ký tài khoản mới. Nhận email kích hoạt. Mở liên kết kích hoạt. Đăng nhập lại. | Tài khoản chuyển sang trạng thái hoạt động và đăng nhập được. |
| TC02 | Đăng nhập | Khách hàng hoặc Admin | Tài khoản đã được kích hoạt | Nhập email và mật khẩu đúng. Thử trường hợp sai mật khẩu. | Thông tin đúng thì vào hệ thống. Thông tin sai thì báo lỗi. |
| TC03 | Quản lý tài khoản | Khách hàng | Đã đăng nhập | Cập nhật họ tên và số điện thoại. Đổi mật khẩu. Thêm hoặc sửa địa chỉ. Xem yêu thích và đơn hàng. | Cập nhật thành công. Email đăng ký không chỉnh sửa được. |
| TC04 | Tìm kiếm và lọc sản phẩm | Khách hàng | Có sản phẩm đang bán | Nhập từ khóa tìm kiếm hoặc chọn danh mục và khoảng giá. | Hiển thị đúng nhóm sản phẩm phù hợp hoặc danh sách trống khi không có dữ liệu. |
| TC05 | Yêu thích | Khách hàng | Đã đăng nhập hoặc khách chưa đăng nhập | Nhấn biểu tượng trái tim trên sản phẩm. Kiểm tra danh sách yêu thích trong tài khoản và biểu tượng trên header. | Thêm hoặc gỡ sản phẩm khỏi yêu thích. Danh sách được đồng bộ trên trang tài khoản và header. |
| TC06 | Giỏ hàng | Khách hàng | Sản phẩm còn hàng | Thêm sản phẩm vào giỏ. Tăng giảm số lượng. Xóa sản phẩm. Thử nhập số lượng lớn hơn tồn kho. | Không cho vượt tồn kho. Khi vượt tồn thì hệ thống đặt về mức tối đa cho phép và thông báo người dùng. |
| TC07 | Áp mã giảm giá | Khách hàng | Có mã hợp lệ và mã không hợp lệ | Tại trang thanh toán nhập mã giảm giá. | Mã đúng thì giảm thành tiền. Mã sai hoặc không đủ điều kiện thì báo không hợp lệ. |
| TC08 | Đặt hàng COD | Khách hàng | Giỏ còn sản phẩm và đã đăng nhập | Vào trang thanh toán. Chọn thanh toán khi nhận hàng. Xác nhận đặt hàng. | Tạo đơn ở trạng thái chờ xác nhận. Trừ tồn kho. Gửi email xác nhận khi cấu hình mail hoạt động. Chuyển đến trang đặt hàng thành công. |
| TC09 | Đặt hàng VNPay | Khách hàng | Giỏ còn sản phẩm và đã đăng nhập | Vào trang thanh toán. Chọn VNPay. Thanh toán thành công hoặc hủy trên cổng. | Hệ thống tạo đơn trước khi chuyển sang cổng thanh toán. Thanh toán thành công thì cập nhật trạng thái thanh toán và gửi email xác nhận. Thanh toán thất bại thì hủy đơn và hoàn kho. |
| TC10 | Đánh giá sản phẩm | Khách hàng | Đơn hàng đã giao và đã mua sản phẩm | Trên trang chi tiết đơn thêm sửa hoặc xóa đánh giá. | Lưu được số sao và bình luận. Chỉ cho đánh giá khi đơn đã giao. |
| TC11 | Quản lý người dùng và phân quyền | Admin | Đã có vai trò và quyền hạn | Xem danh sách người dùng. Xem chi tiết. Đổi trạng thái khóa hoặc mở. Phân quyền theo vai trò. | Khóa và mở tài khoản theo trạng thái. Nhân viên không thao tác vượt quyền được giao. |
| TC12 | Quản lý danh mục và sản phẩm | Admin hoặc Staff | Có quyền quản lý danh mục và sản phẩm | Thêm sửa xóa danh mục và sản phẩm. Import file CSV. Thử xóa sản phẩm đã có trong đơn. Thử xóa danh mục còn sản phẩm. | Thêm sửa xóa hoạt động đúng. Sản phẩm đã có trong đơn thì được ẩn bán. Danh mục còn sản phẩm thì không xóa được. |
| TC13 | Quản lý đơn hàng | Admin hoặc Staff | Có đơn chờ xác nhận | Xác nhận đơn kèm kiểm tra tồn kho. Chuyển đang giao. Chuyển đã giao. In hóa đơn. | Đúng luồng trạng thái. Gửi email cập nhật trạng thái khi cấu hình mail hoạt động. In được hóa đơn dạng PDF. |
| TC14 | Dashboard và xuất báo cáo | Admin | Có dữ liệu đơn hàng | Lọc theo khoảng thời gian gồm hôm nay, 7 ngày, 30 ngày, tháng này hoặc tùy chọn từ ngày đến ngày theo ngày tháng năm. Xem biểu đồ. Xuất báo cáo CSV hoặc PDF. | Số liệu đúng với khoảng thời gian đã lọc. Tải được file báo cáo CSV hoặc PDF. |
| TC15 | Giao diện đa thiết bị | Khách hàng | Thử trên điện thoại máy tính bảng và máy tính | Duyệt trang chủ sản phẩm giỏ hàng thanh toán và tài khoản. | Bố cục ổn định trên điện thoại và máy tính. Menu mobile hoạt động. Máy tính bảng có thể còn cần tinh chỉnh thêm. |

## 4.1.2 Kết quả thử nghiệm các kịch bản

Các kết quả dưới đây được ghi nhận sau kiểm thử thủ công trên môi trường local của đồ án.

| Mã | Kết quả | Ghi chú |
|----|---------|---------|
| TC01 | Đạt | Đăng ký tạo tài khoản chờ kích hoạt. Kích hoạt qua token. Việc nhận email phụ thuộc cấu hình SMTP. |
| TC02 | Đạt | Chỉ tài khoản đang hoạt động đăng nhập được. Admin vào khu vực quản trị. |
| TC03 | Đạt | Hồ sơ địa chỉ đơn hàng và yêu thích cập nhật được. Không đổi email đăng ký. |
| TC04 | Đạt | Tìm kiếm và lọc theo danh mục hoặc giá hoạt động đúng. |
| TC05 | Đạt | Yêu thích dạng thêm hoặc gỡ. Đồng bộ trang tài khoản và header. |
| TC06 | Đạt | Có kiểm tra số lượng khi thêm hoặc sửa giỏ hàng. |
| TC07 | Đạt | Mã giảm giá hợp lệ và không hợp lệ được xử lý đúng. |
| TC08 | Đạt | Tạo đơn COD và trừ kho thành công. Email xác nhận phụ thuộc cấu hình SMTP. |
| TC09 | Đạt một phần | Đã tạo đơn trước khi chuyển sang VNPay. Kết quả thanh toán trên cổng phụ thuộc cấu hình VNPay sandbox. |
| TC10 | Đạt | Chỉ đánh giá được trên đơn đã giao. |
| TC11 | Đạt | Quản lý người dùng theo trạng thái và phân quyền vai trò. Không lưu trường lý do khóa riêng. |
| TC12 | Đạt | Thêm sửa xóa và import CSV đúng. Ràng buộc ẩn sản phẩm và không xóa danh mục còn hàng hoạt động đúng. |
| TC13 | Đạt | Luồng trạng thái và kiểm tra tồn khi xác nhận đúng. In hóa đơn PDF được. Email đổi trạng thái phụ thuộc cấu hình SMTP. |
| TC14 | Đạt | Dashboard lọc theo khoảng thời gian ngày tháng năm. Xuất được báo cáo CSV hoặc PDF. |
| TC15 | Đạt một phần | Điện thoại và máy tính ổn định. Máy tính bảng trung gian còn có thể tinh chỉnh thêm. |

Tóm lại các chức năng nghiệp vụ chính đã vận hành đúng thiết kế trên môi trường kiểm thử. Luồng gửi email và thanh toán VNPay đã được tích hợp trong mã nguồn. Mức độ nhận được email hoặc hoàn tất thanh toán trên cổng thật phụ thuộc cấu hình môi trường chạy thử.

## 4.1.3 Xử lý các trường hợp ngoại lệ

| STT | Trường hợp ngoại lệ | Cách hệ thống xử lý |
|-----|---------------------|---------------------|
| 1 | Email đã tồn tại hoặc sai định dạng khi đăng ký | Hệ thống kiểm tra dữ liệu và báo lỗi. Không tạo tài khoản mới. |
| 2 | Đăng nhập sai mật khẩu hoặc tài khoản chưa kích hoạt hoặc bị khóa | Hệ thống không cho đăng nhập và hiển thị thông báo. |
| 3 | Thêm vào giỏ khi vượt tồn kho hoặc hết hàng | Hệ thống từ chối hoặc giới hạn theo số lượng tối đa và thông báo người dùng. |
| 4 | Mã giảm giá hết hạn hoặc đơn chưa đủ điều kiện tối thiểu | Hệ thống báo mã không hợp lệ và không giảm tiền. |
| 5 | Vào thanh toán khi chưa đăng nhập hoặc giỏ trống | Hệ thống yêu cầu đăng nhập. Không tạo đơn khi không có sản phẩm. |
| 6 | Thanh toán VNPay thất bại hoặc khách hủy giao dịch | Đơn chuyển sang hủy. Hệ thống hoàn lại số lượng tồn kho và có thể trả sản phẩm về giỏ. |
| 7 | Admin xác nhận đơn khi sản phẩm không còn hiệu lực hoặc không đủ đáp ứng | Hệ thống chặn chuyển sang đã xác nhận và hiển thị lỗi. |
| 8 | Xóa danh mục còn sản phẩm hoặc còn danh mục con | Hệ thống không cho xóa và thông báo lỗi. |
| 9 | Xóa sản phẩm đã có trong đơn hàng | Hệ thống không xóa cứng. Sản phẩm được ẩn bán để giữ lịch sử đơn. |
| 10 | Khách đánh giá khi đơn chưa giao hoặc sản phẩm không thuộc đơn | Hệ thống không cho lưu đánh giá. |
| 11 | Nhân viên thao tác vượt quyền như sửa tài khoản admin | Hệ thống từ chối thao tác qua phân quyền và kiểm tra trong xử lý. |
| 12 | Lỗi gửi email do cấu hình SMTP | Hệ thống ghi nhận lỗi gửi mail. Nghiệp vụ tạo đơn hoặc đổi trạng thái vẫn được lưu. |
