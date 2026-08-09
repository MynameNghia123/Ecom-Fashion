# 1. Tiêu đề và Thông tin chung (Project Title & Info)

**Tên dự án:** Ecom Fashion - Hệ thống Thương mại Điện tử và Quản lý Bán hàng Thời trang

**Mô tả ngắn gọn:** Đây là một hệ thống Website Thương mại điện tử chuyên cung cấp các sản phẩm thời trang. Hệ thống bao gồm phần Frontend hiện đại dành cho khách hàng mua sắm (tích hợp AI tư vấn, thanh toán online VNPAY) và phần Backend quản trị hệ thống chặt chẽ, đa chức năng.

---

# 2. Công nghệ sử dụng (Tech Stack)

* **Frontend:** Vue.js 3, Vite, Tailwind CSS, Pinia.
* **Backend:** Laravel 11.x, RESTful API.
* **Cơ sở dữ liệu:** MySQL.
* **Tích hợp bên thứ 3:** VNPAY (Thanh toán), GHN (Giao Hàng Nhanh), Google Gemini AI (Trợ lý ảo), Mailpit (Test email).
* **Công cụ & Môi trường:** Docker, Docker Compose, Git/GitHub.

---

# 3. Yêu cầu hệ thống (Prerequisites)

Để chạy được dự án này trên môi trường local, máy tính của bạn cần cài đặt sẵn:
* **Docker** và **Docker Compose** (Dự án được đóng gói chạy hoàn toàn trên Docker, môi trường đã được cấu hình sẵn, không cần cài PHP hay Node.js thủ công vào máy thật).
* **Git** (Để clone source code).

---

# 4. Hướng dẫn cài đặt và Chạy dự án (Installation & Setup)

Mở Terminal (hoặc Git Bash / PowerShell) và thực hiện lần lượt các bước sau:

**Bước 1: Clone dự án về máy**
```bash
git clone <link-repo>
cd EcomFashion
```

**Bước 2: Cấu hình môi trường (Backend & Frontend)**
```bash
# Cấu hình Backend
cp backend/.env.example backend/.env

# Cấu hình Frontend
cp frontend/.env.example frontend/.env
```

**LƯU Ý QUAN TRỌNG VỀ CẤU HÌNH `.env` BACKEND:**
Bạn cần mở file `backend/.env` và thêm các thông số kết nối API bên thứ 3 (Sandbox VNPAY, Google Gemini, Giao Hàng Nhanh...) vào cuối file để các chức năng nâng cao hoạt động được:

```env
# ── SANCTUM & CORS (Cần thiết để Frontend kết nối) ──
FRONTEND_URL=http://localhost:5173
SANCTUM_STATEFUL_DOMAINS=localhost:5173
SESSION_DOMAIN=localhost
SESSION_DRIVER=database

# ── VNPAY Sandbox (Thanh toán) ──
VNPAY_TMN_CODE=your_tmn_code_here
VNPAY_HASH_SECRET=your_hash_secret_here
VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
VNPAY_IPN_URL=http://localhost:8000/api/client/vnpay/ipn
VNPAY_RETURN_URL=http://localhost:5173/checkout/vnpay-return

# ── Trợ lý ảo AI (Gemini) ──
GEMINI_API_KEY=your_gemini_api_key_here

# ── Giao Hàng Nhanh (Tính phí ship) ──
GHN_API_URL=https://online-gateway.ghn.vn/shiip/public-api
GHN_TOKEN=your_ghn_token_here
GHN_SHOP_ID=your_ghn_shop_id_here
```

**Bước 3: Khởi động hệ thống bằng Docker**
```bash
docker-compose up -d --build
```
*(Lệnh này sẽ tự động tải các môi trường cần thiết, chạy `composer install`, `npm install` ngầm bên trong container và khởi động hệ thống).*

**Bước 4: Khởi tạo dữ liệu (Chạy bên trong Backend Container)**
```bash
# Truy cập vào terminal của container backend
docker exec -it ecom-fashion-backend bash

# Tạo App Key bảo mật cho Laravel
php artisan key:generate

# Chạy migration và seeder để tạo cấu trúc bảng & đổ dữ liệu mẫu
php artisan migrate:fresh --seed

# Thoát khỏi container
exit
```

**Bước 5: Truy cập ứng dụng trên trình duyệt**
* **Frontend (Trang mua sắm):** http://localhost:5173
* **Backend (API):** http://localhost:8000
<!-- * **Mailpit (Kiểm tra Email Test):** http://localhost:1025 -->

---

# 5. Danh sách tính năng (Features)

**Dành cho Người dùng (Client):**
* Đăng ký, Đăng nhập, Đăng xuất (xác thực qua Sanctum), Quản lý hồ sơ cá nhân.
* Duyệt, tìm kiếm, lọc và xem chi tiết sản phẩm thời trang.
* Quản lý giỏ hàng, chọn từng sản phẩm muốn thanh toán.
* Checkout đa dạng phương thức: COD, VNPAY (Sandbox tích hợp sẵn).
* Theo dõi trạng thái đơn hàng và lịch sử mua hàng.
* **Đặc biệt:** Trợ lý ảo AI Chatbot tư vấn phong cách, gợi ý trang phục dựa theo dữ liệu thực tế từ Database.

**Dành cho Quản trị viên (Admin):**
* Quản lý Sản phẩm (Danh mục, Biến thể, Tồn kho, Thuộc tính màu sắc/size).
* Quản lý Đơn hàng (Cập nhật trạng thái, tích hợp đẩy đơn Giao Hàng Nhanh).
* Quản lý Người dùng & Nhân viên (Phân quyền Role/Permission).
* Thống kê doanh thu, số lượng đơn hàng và hiển thị biểu đồ báo cáo.

---

# 6. Tài khoản kiểm thử (Test Credentials)

Hệ thống đã có sẵn dữ liệu mẫu sau khi chạy Seeder. Bạn có thể dùng các tài khoản dưới đây để test chức năng ngay lập tức:

* **Tài khoản Admin (Toàn quyền quản trị):**
  - Email: `admin@ecomfashion.com` 
  - Mật khẩu: `password`

* **Tài khoản Khách hàng (User):**
  - Email: `customer@ecomfashion.com`
  - Mật khẩu: `password`
  - *(Hoặc tự đăng ký một tài khoản mới ở giao diện Frontend)*

* **Thẻ Test Thanh toán VNPAY (Sandbox):**
  - Ngân hàng: `NCB`
  - Số thẻ: `9704198526191432198`
  - Tên chủ thẻ: `NGUYEN VAN A`
  - Ngày phát hành: `07/15`
  - Mật khẩu OTP: `123456`

---

# 7. Hình ảnh minh họa (Screenshots)

*(Nhóm phát triển có thể chèn ảnh chụp màn hình các giao diện chính vào đây để minh họa)*

![Trang Chủ Client](/frontend/public/home-screenshot.png)
![Dashboard Admin](/backend/public/admin-screenshot.png)
![Giao diện AI Chat](/frontend/public/ai-screenshot.png)
