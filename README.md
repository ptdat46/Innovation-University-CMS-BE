Innovation University CMS - Backend API
Hệ thống quản lý đại học (Innovation University Management System) được xây dựng trên nền tảng Laravel, đóng vai trò là RESTful API server xử lý toàn bộ logic nghiệp vụ, xác thực và quản lý dữ liệu cho hệ sinh thái đại học số.

🛠 Tech Stack (Công nghệ sử dụng)
Framework: Laravel 11.x (hoặc 10.x)

Language: PHP 8.2+

Database: MySQL 8.0

Authentication: Laravel Sanctum (Token-based Auth)

API Testing: Postman / Swagger (nếu có tích hợp)

📂 Cấu trúc Dự ántext
Innovation-University-CMS-BE/ 
├── app/ 
│ ├── Http/Controllers/API # Các API Endpoints (AuthController, StudentController...) 
│ ├── Models/ # Eloquent Models (User, Course, Enrollment...) 
│ ├── Services/ # Business Logic Layer (Xử lý nghiệp vụ phức tạp) 
│ └── Http/Requests/ # Validation Rules (Kiểm tra dữ liệu đầu vào) 
├── database/ 
│ ├── migrations/ # Cấu trúc bảng Database 
│ └── seeders/ # Dữ liệu mẫu (Admin, Departments...) |
├── routes/ 
│ └── api.php # Định nghĩa các đường dẫn API 
└──.env.example # Mẫu cấu hình môi trường


## ✨ Chức năng Chính
*   **Authentication & Authorization:** Đăng nhập/Đăng ký qua API, phân quyền Role (Admin, Teacher, Student) sử dụng Middleware.
*   **Academic Management:** Quản lý Khoa, Ngành, Lớp học phần, Thời khóa biểu.
*   **Student Lifecycle:** Quản lý hồ sơ sinh viên, Trạng thái nhập học.
*   **Course Registration:** API xử lý đăng ký tín chỉ, kiểm tra trùng lịch và điều kiện tiên quyết.
*   **Grade Management:** Nhập điểm, tính điểm tổng kết (GPA/CPA).

## 🚀 Hướng dẫn Cài đặt (Installation Guide)

### Yêu cầu hệ thống
*   PHP >= 8.2
*   Composer
*   MySQL Server

### Bước 1: Clone dự án & Cài đặt thư viện
```
git clone [https://github.com/ptdat46/Innovation-University-CMS-BE.git](https://github.com/ptdat46/Innovation-University-CMS-BE.git)
cd Innovation-University-CMS-BE
composer install
```
Bước 2: Cấu hình Môi trường
Copy file môi trường mẫu và tạo key ứng dụng:
```
cp.env.example.env
php artisan key:generate
```
Bước 3: Cấu hình Database
Mở file .env, tìm và chỉnh sửa thông tin kết nối MySQL của bạn. Hãy đảm bảo bạn đã tạo một database trống tên là innovation_university (hoặc tên tùy chọn) trong MySQL.
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=innovation_university  <-- Tên database bạn đã tạo
DB_USERNAME=root                   <-- User MySQL của bạn
DB_PASSWORD=                       <-- Mật khẩu MySQL của bạn
```
Bước 4: Chạy Migration & Seeding (Tạo bảng và dữ liệu mẫu)
Lệnh này sẽ tạo bảng và nạp các dữ liệu ban đầu (như tài khoản Admin mặc định):
```
php artisan migrate --seed
Lưu ý: Kiểm tra file database/seeders/DatabaseSeeder.php để biết tài khoản Admin mặc định (thường là admin@innovation.edu.vn / password).
```
Bước 5: Khởi chạy Server
```
php artisan serve
```
Backend sẽ chạy tại: http://127.0.0.1:8000. API base URL sẽ là http://127.0.0.1:8000/api.

🧪 Testing API
Sử dụng Postman hoặc truy cập file routes/api.php để xem danh sách endpoints. Ví dụ Login: POST http://127.0.0.1:8000/api/login
