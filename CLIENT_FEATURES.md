# Danh sách chức năng dành cho Client (Khách hàng)

Dưới đây là chi tiết các chức năng đã được xây dựng và hoàn thiện dành riêng cho phía người dùng (Client) trên cả Frontend và Backend, tách biệt hoàn toàn với trang quản trị (Admin).

## 1. Xác thực người dùng (Authentication)
* **Đăng ký (Register):** Đăng ký tài khoản khách hàng mới.
* **Đăng nhập (Login):** Đăng nhập bằng Email và Mật khẩu.
* **Quên mật khẩu & Reset:**
  * Yêu cầu gửi mã OTP về email để lấy lại mật khẩu.
  * Xác thực mã OTP.
  * Đặt lại mật khẩu mới.
* **Đăng xuất (Logout).**

## 2. Quản lý Hồ sơ cá nhân (Profile & Account)
* **Thông tin cá nhân:** Xem và cập nhật thông tin cá nhân (Tên, số điện thoại, avatar...).
* **Đổi mật khẩu:** Thay đổi mật khẩu tài khoản trực tiếp từ trang profile.
* **Quản lý Sổ địa chỉ (Address Book):**
  * Thêm, sửa, xóa các địa chỉ nhận hàng.
  * Đặt địa chỉ mặc định.

## 3. Quản lý Sản phẩm & Danh mục (Catalog)
* **Trang chủ (Home):** Hiển thị Banner động, các sản phẩm nổi bật, sản phẩm mới, danh mục nổi bật.
* **Danh mục sản phẩm (Categories):**
  * Hiển thị danh mục theo cấu trúc cây (Tree) trên Mega Menu.
  * Lọc sản phẩm theo từng danh mục cụ thể.
* **Danh sách Sản phẩm & Tìm kiếm:**
  * Hiển thị danh sách toàn bộ sản phẩm.
  * Chức năng tìm kiếm sản phẩm theo tên, thương hiệu, danh mục.
  * Phân trang sản phẩm.
* **Chi tiết Sản phẩm:**
  * Hiển thị thông tin chi tiết, thư viện ảnh (gallery).
  * Lựa chọn biến thể sản phẩm (Size, Màu sắc) dựa trên Product Variants.
  * Hiển thị số lượng tồn kho theo thời gian thực (được đồng bộ từ kho).
  * Hiển thị các đánh giá (Reviews) của sản phẩm đó.

## 4. Giỏ hàng & Thanh toán (Cart & Checkout)
* **Giỏ hàng (Cart):**
  * Thêm sản phẩm (cùng biến thể cụ thể) vào giỏ hàng.
  * Cập nhật số lượng sản phẩm trong giỏ.
  * Xóa sản phẩm khỏi giỏ hàng.
* **Mã giảm giá (Coupons):**
  * Hiển thị danh sách các mã giảm giá có sẵn.
  * Áp dụng mã giảm giá vào giỏ hàng/đơn hàng để tính lại tổng tiền.
* **Thanh toán (Checkout):**
  * Điền thông tin giao hàng (chọn từ sổ địa chỉ hoặc nhập mới).
  * Tính toán tổng tiền, chiết khấu.
  * Đặt hàng và lưu thông tin đơn hàng (Orders).
* **Tích hợp thanh toán VNPAY:**
  * Hỗ trợ thanh toán trực tuyến qua cổng VNPAY.
  * Nhận phản hồi IPN (Webhook) từ VNPAY để tự động cập nhật trạng thái đơn hàng (Thành công/Thất bại).

## 5. Quản lý Đơn hàng & Lịch sử (Orders)
* **Lịch sử Đơn hàng:** Khách hàng có thể theo dõi danh sách các đơn hàng đã đặt.
* **Chi tiết Đơn hàng:** Xem trạng thái đơn hàng, chi tiết các mặt hàng đã mua, số tiền và thông tin giao hàng.

## 6. Đánh giá & Yêu thích (Reviews & Wishlist)
* **Sản phẩm yêu thích (Wishlist):**
  * Thêm/Bỏ sản phẩm khỏi danh sách yêu thích.
  * Xem danh sách các sản phẩm đang được yêu thích.
* **Đánh giá & Bình luận (Reviews):**
  * Cho phép khách hàng viết đánh giá, chấm điểm (số sao) cho các sản phẩm đã mua thành công.
  * Hiển thị danh sách đánh giá của chính mình ở Profile.

## 7. Trợ lý Ảo (AI Chatbot)
* **Trò chuyện trực tiếp:** Tích hợp AI Chatbot hỗ trợ giải đáp thắc mắc về sản phẩm, chính sách.
* **Lịch sử Chat:** 
  * Lưu trữ lịch sử trò chuyện.
  * Đồng bộ lịch sử chat từ lúc là khách (Guest) sang tài khoản sau khi đăng nhập thành công.

## 8. Các nội dung khác (CMS & Static Pages)
* **Tin tức (Blogs):** Hiển thị danh sách bài viết và chi tiết bài viết tin tức/khuyến mãi.
* **Thương hiệu (Brands):** Lấy thông tin logo/thương hiệu tự động qua tích hợp API (Brandfetch).
* **Trang thông tin (About Us, Contact Us):** Các trang tĩnh giới thiệu và liên hệ.
