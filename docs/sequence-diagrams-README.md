# Sequence & Activity diagrams

## Chú thích luận văn

> Các thông điệp trong sơ đồ tuần tự được đánh số theo phương pháp đánh số phân cấp (Hierarchical Message Numbering), trong đó các bước chính sử dụng số nguyên (1, 2, 3...), còn các lời gọi phát sinh được đánh số theo dạng phân cấp (1.1, 1.1.1, 2.1...). Cách đánh số này giúp thể hiện rõ mối quan hệ giữa các thông điệp và trình tự thực hiện của hệ thống.

## Quy ước đặt tên

| Cột | Cách ghi |
|-----|----------|
| Actor / Boundary | Tiếng Việt (UI) |
| Control | Action Controller: `login()`, `index()`, `store()`… |
| Entity | Tên ngắn, đúng ý nghiệp vụ: `loginUser()`, `getOrdersByUser()`, `checkStock()`, `create()`, `save()`… — **không** ghi nguyên câu query / facade dài |

## File

| File | Hình |
|------|------|
| [`sequence-diagrams-3.14-3.32.puml`](sequence-diagrams-3.14-3.32.puml) | 3.13–3.32 (+ 3.23b Gán role user) |
| [`sequence-diagrams-3.33-3.50.puml`](sequence-diagrams-3.33-3.50.puml) | 3.33–3.50 |

## Activity diagrams

Sơ đồ hoạt động được vẽ tương ứng 1-1 với các sequence diagram ở trên (cùng số hình), dùng swimlane phân làn giữa tác nhân (`|Người dùng|`, `|Khách hàng|`, `|Admin|`) và `|Hệ thống|`. Các nhánh `alt` trong sequence chuyển thành nút quyết định `if/else`.

| File | Hình |
|------|------|
| [`activity-diagrams-3.13-3.32.puml`](activity-diagrams-3.13-3.32.puml) | 3.13–3.32 (+ 3.23b) |
| [`activity-diagrams-3.33-3.50.puml`](activity-diagrams-3.33-3.50.puml) | 3.33–3.50 |
