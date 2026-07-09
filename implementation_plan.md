# Tối ưu Docker cho EcomFashion

## Tổng quan

Cấu hình Docker hiện tại hoạt động được nhưng có nhiều điểm chưa tối ưu về **tốc độ build, startup time, bảo mật, và trải nghiệm dev**. Kế hoạch này sẽ cải thiện toàn diện mà vẫn giữ workflow giống như cũ.

## Phân tích vấn đề hiện tại

| Vấn đề | Mức nghiêm trọng | File |
|--------|:-:|------|
| **Backend chạy `composer install` mỗi lần startup** — mất 30-60s mỗi lần `docker compose up` | 🔴 Cao | `docker-compose.yml` |
| **Backend dùng `php artisan serve`** (dev server PHP) — không phù hợp chạy song song requests | 🟡 Trung bình | `Dockerfile` backend |
| **Frontend `npm install` chạy trong Dockerfile nhưng bị volume override** — `node_modules` trong image bị ghi đè bởi named volume, COPY vô nghĩa | 🟡 Trung bình | `Dockerfile` frontend |
| **Không có network riêng** — các service dùng default bridge network | 🟡 Trung bình | `docker-compose.yml` |
| **`chmod 777`** — quá rộng quyền, bad practice | 🟡 Trung bình | `docker-compose.yml` |
| **Frontend chạy `user: root`** rồi lại override `USER node` trong Dockerfile | 🟡 Trung bình | `docker-compose.yml` |
| **Thiếu restart policy** — container crash không tự khởi động lại | 🟢 Thấp | `docker-compose.yml` |
| **Thiếu `.dockerignore` cho `.git`** — COPY context chứa `.git` không cần thiết | 🟢 Thấp | `.dockerignore` |

## Proposed Changes

### Backend Dockerfile

#### [MODIFY] [Dockerfile](file:///d:/Web/Vuejs/Project/EcomFashion/backend/Dockerfile)

- Thêm `opcache` extension để PHP cache bytecode → tăng tốc runtime
- Cài thêm `pcntl` extension cho queue worker (nếu cần sau này)
- Tổ chức lại thứ tự layers để tận dụng Docker cache tốt hơn

---

### Frontend Dockerfile

#### [MODIFY] [Dockerfile](file:///d:/Web/Vuejs/Project/EcomFashion/frontend/Dockerfile)

- Bỏ `COPY` và `npm install` vì bị volume mount override trong dev mode
- Chuyển thành image nhẹ chỉ cần Node runtime
- Thêm entrypoint script xử lý `npm install` chỉ khi `node_modules` chưa tồn tại

---

### Docker Compose

#### [MODIFY] [docker-compose.yml](file:///d:/Web/Vuejs/Project/EcomFashion/docker-compose.yml)

Thay đổi chính:

**MySQL:**
- Thêm `restart: unless-stopped`
- Thêm `command` tối ưu MySQL config (charset utf8mb4, InnoDB buffer)

**phpMyAdmin:**
- Thêm `restart: unless-stopped`

**Backend:**
- **Tách `composer install` ra entrypoint script** — chỉ chạy lần đầu hoặc khi `vendor` trống
- Cache `composer install` kết quả vào named volume
- Sửa permissions `775` thay vì `777`
- Thêm `restart: unless-stopped`
- Thêm network riêng

**Frontend:**
- Bỏ `user: "root"` — dùng `node` user từ Dockerfile
- Thêm entrypoint kiểm tra `node_modules` trước khi `npm install`
- Thêm `restart: unless-stopped`

**Network:**
- Thêm custom bridge network `ecom-network` để các service giao tiếp rõ ràng

---

### Dockerignore

#### [MODIFY] [.dockerignore](file:///d:/Web/Vuejs/Project/EcomFashion/backend/.dockerignore)
#### [MODIFY] [.dockerignore](file:///d:/Web/Vuejs/Project/EcomFashion/frontend/.dockerignore)

- Thêm `.git`, `*.md`, `tests/` vào ignore list để giảm build context size

---

## So sánh trước/sau

| Metric | Trước | Sau |
|--------|-------|-----|
| **Startup time** (lần 2+) | ~40-60s (composer install mỗi lần) | ~3-5s (skip nếu vendor có) |
| **Build context size** | Lớn (chứa .git, tests) | Nhỏ hơn ~30-50% |
| **Permission security** | `chmod 777` | `chmod 775` |
| **Container restart** | Manual | Auto (`unless-stopped`) |
| **Network isolation** | Default bridge | Custom network |
| **Frontend npm install** | Mỗi lần build image (bị override) | Chỉ khi node_modules trống |

## Verification Plan

### Manual Verification
- Chạy `docker compose down -v && docker compose up --build` để test full rebuild
- Chạy `docker compose down && docker compose up` để test startup nhanh (lần 2)
- Truy cập `localhost:5173` (frontend) và `localhost:8000` (backend) để verify hoạt động
- Truy cập `localhost:8080` (phpMyAdmin) để verify DB connection
