# Innovation University - Backend

API cho hệ thống bài viết/tin tức, xây dựng bằng Laravel 11.

## Yêu cầu
- PHP >= 8.2, Composer
- MySQL >= 8.0
- Node.js + npm (cho assets nếu cần)

## Cài đặt nhanh
```bash
cd Backend
composer install
npm install            # nếu cần build assets
cp .env.example .env
php artisan key:generate
```
Cập nhật DB trong `.env`, sau đó:
```bash
php artisan migrate
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=PostSeeder
php artisan serve
```
Mặc định chạy tại http://localhost:8000.

## Tài khoản mẫu (sau seeder)
- Admin: username `admin` / password `password`
- Writer: username `writer` / password `password`
- User: username `user` / password `password`

## Chức năng chính
- Đăng nhập đa vai trò (Admin, Writer, User) với Sanctum
- CRUD bài viết, phân loại: news, events, clubs, student-life
- Duyệt bài (pending/posted) cho Admin
- Upload ảnh đại diện & ảnh nội dung
- Like, comment, đếm lượt xem
- Dashboard thống kê (tổng bài, lượt xem, lượt thích)

## API chính (tóm tắt)
- Công khai: `GET /`, `/news`, `/events`, `/clubs`, `/student-life`, `/posts/{id}`
- Comment: `GET /posts/{id}/comments`, `POST /posts/{id}/comments` (cần token)
- Like/View: `POST /posts/{id}/like`, `POST /posts/{id}/view` (cần token)
- Admin: duyệt/xóa/list bài viết
- Writer: list/tạo/xem/xóa bài của chính mình, upload ảnh

## Cấu trúc thư mục đáng chú ý
```
app/
  Http/Controllers/Api/   # AdminController, WriterController, UserController, CommonController
  Models/                 # Post, Comment, User...
  Helpers/Common.php      # successResponse, errorResponse
  Http/Middleware/        # CheckRole
routes/api.php            # Định nghĩa API
database/migrations       # Bảng posts, comments, post_likes...
database/seeders          # UserSeeder, PostSeeder
```

## Lỗi thường gặp
- CORS: kiểm tra config/cors.php và restart server
- 401/403: đảm bảo gửi Bearer token và đúng role
- Storage: nhớ chạy `php artisan storage:link` nếu cần serve ảnh

## Giấy phép
MIT
