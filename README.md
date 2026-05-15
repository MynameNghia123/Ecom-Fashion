# EcomFashion

EcomFashion là dự án cá nhân gồm hai phần chính:

- `backend`: Laravel dùng để xây dựng API/server.
- `frontend`: Vue 3 + Vite dùng để xây dựng giao diện người dùng.

Dự án được cấu hình chạy bằng Docker Compose. Khi chạy project, Docker sẽ khởi động cùng lúc Laravel, Vue/Vite, MySQL và phpMyAdmin. Vì vậy bạn không cần cài riêng PHP, Composer, Node, MySQL, XAMPP hoặc Laragon trên máy để chạy dự án này.

## Cấu Trúc Dự Án

```text
EcomFashion/
  README.md
  docker-compose.yml
  backend/
    Dockerfile
    .dockerignore
    .env
    .env.example
    composer.json
    composer.lock
  frontend/
    Dockerfile
    .dockerignore
    .env
    package.json
    package-lock.json
```

Dự án chỉ giữ một file README duy nhất ở thư mục gốc:

```text
EcomFashion/README.md
```

## Yêu Cầu

- Docker Desktop
- Docker Compose

Backend đang dùng PHP `8.4` trong Docker vì `composer.lock` có một số package Symfony v8 yêu cầu PHP `>=8.4`.

## Các Service Trong Docker

```text
mysql       MySQL database server, nơi lưu dữ liệu thật.
phpmyadmin  Giao diện web để xem và thao tác database MySQL.
backend     Laravel app, chạy ở port 8000.
frontend    Vue/Vite app, chạy ở port 5173.
```

Lưu ý: `phpmyadmin` chỉ là giao diện quản lý database. Database thật vẫn là service `mysql`.

## Biến Môi Trường Chính

Backend dùng file:

```text
backend/.env
```

Cấu hình database hiện tại:

```env
APP_URL=http://localhost:8000
APP_PORT=8000

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=ecom_fashion
DB_USERNAME=ecom_user
DB_PASSWORD=ecom_password
```

Frontend dùng file:

```text
frontend/.env
```

Cấu hình frontend hiện tại:

```env
VITE_APP_NAME=EcomFashion
VITE_API_BASE_URL=http://localhost:8000
VITE_DEV_SERVER_PORT=5173
```

## Cách Khởi Động Dự Án

Chạy lệnh tại thư mục gốc `EcomFashion`:

```bash
docker compose up -d --build
```

Lệnh này sẽ:

- Build image cho backend và frontend.
- Pull image MySQL và phpMyAdmin nếu máy chưa có.
- Cài dependency cho Laravel bằng `composer install`.
- Cài dependency cho frontend bằng `npm ci`.
- Chạy migrate cho Laravel bằng `php artisan migrate --force`.
- Khởi động toàn bộ service ở chế độ chạy nền.

Lần đầu chạy có thể mất vài phút vì Docker cần tải image và cài dependency.

## Chạy Lại Dự Án Sau Lần Đầu

Nếu không thay đổi Dockerfile hoặc dependency, có thể chạy nhanh bằng:

```bash
docker compose up -d
```

Nếu có sửa `Dockerfile`, `composer.json`, `composer.lock`, `package.json` hoặc `package-lock.json`, nên chạy lại:

```bash
docker compose up -d --build
```

## Đường Dẫn Sau Khi Chạy

```text
Frontend:   http://localhost:5173
Backend:    http://localhost:8000
phpMyAdmin: http://localhost:8080
MySQL:      localhost:3307
```

## Đăng Nhập phpMyAdmin

Mở trình duyệt:

```text
http://localhost:8080
```

Thông tin đăng nhập:

```text
Server: mysql
Username: ecom_user
Password: ecom_password
```

Nếu phpMyAdmin không hiện ô `Server`, chỉ cần nhập username và password.

## Kết Nối MySQL Bằng Tool Khác

Nếu dùng MySQL Workbench, DBeaver, TablePlus hoặc HeidiSQL trên máy host, dùng cấu hình:

```text
Host: localhost
Port: 3307
Database: ecom_fashion
User: ecom_user
Password: ecom_password
```

Trong Laravel container, `DB_HOST` phải là:

```env
DB_HOST=mysql
DB_PORT=3306
```

Không dùng `DB_HOST=localhost` trong Docker, vì `localhost` bên trong container backend là chính container backend, không phải container MySQL.

## Xem Log

Xem log toàn bộ service:

```bash
docker compose logs -f
```

Xem log backend:

```bash
docker compose logs -f backend
```

Xem log frontend:

```bash
docker compose logs -f frontend
```

Xem log MySQL:

```bash
docker compose logs -f mysql
```

Xem log phpMyAdmin:

```bash
docker compose logs -f phpmyadmin
```

## Dừng Dự Án

Dừng container nhưng vẫn giữ dữ liệu MySQL:

```bash
docker compose down
```

Xoá cả container và volume:

```bash
docker compose down -v
```

Cẩn thận: `docker compose down -v` sẽ xoá volume `mysql_data`, nghĩa là dữ liệu MySQL cũng bị xoá. Chỉ dùng lệnh này khi muốn reset database.

## Rebuild Khi Cần

Build lại backend không dùng cache:

```bash
docker compose build --no-cache backend
docker compose up -d backend
```

Build lại frontend không dùng cache:

```bash
docker compose build --no-cache frontend
docker compose up -d frontend
```

Build lại toàn bộ project:

```bash
docker compose up -d --build
```

## Ghi Chú

- Dự án này ưu tiên dùng Docker, không cần XAMPP hoặc Laragon.
- MySQL chạy trong container `ecom-fashion-mysql`.
- phpMyAdmin chạy trong container `ecom-fashion-phpmyadmin`.
- Backend chạy trong container `ecom-fashion-backend`.
- Frontend chạy trong container `ecom-fashion-frontend`.
- Dữ liệu MySQL được lưu trong Docker volume `mysql_data`.
