# EcomFashion

Du an ca nhan gom 2 phan rieng:

- `backend`: Laravel API/server
- `frontend`: Vue 3 + Vite client

Project duoc cau hinh de chay bang Docker Compose, giup khoi dong backend va frontend cung luc ma khong can cai PHP/Node truc tiep tren may.

## Cau truc thu muc

```text
EcomFashion/
  docker-compose.yml
  backend/
    Dockerfile
    .env
    composer.json
    composer.lock
  frontend/
    Dockerfile
    .env
    package.json
    package-lock.json
```

## Yeu cau

- Docker Desktop
- Docker Compose

Backend dang dung image PHP `8.4` vi `composer.lock` co cac package Symfony v8 yeu cau PHP `>=8.4`.

## Bien moi truong chinh

Backend: `backend/.env`

```env
APP_URL=http://localhost:8000
APP_PORT=8000
DB_CONNECTION=sqlite
```

Frontend: `frontend/.env`

```env
VITE_APP_NAME=EcomFashion
VITE_API_BASE_URL=http://localhost:8000
VITE_DEV_SERVER_PORT=5173
```

## Khoi dong project

Chay lenh tai thu muc goc `EcomFashion`:

```bash
docker compose up --build
```

Neu muon chay nen:

```bash
docker compose up -d --build
```

Sau khi chay thanh cong:

- Frontend: http://localhost:5173
- Backend: http://localhost:8000

## Xem log

Xem log tat ca service:

```bash
docker compose logs -f
```

Xem log rieng backend:

```bash
docker compose logs -f backend
```

Xem log rieng frontend:

```bash
docker compose logs -f frontend
```

## Dung project

```bash
docker compose down
```

Neu muon xoa ca volume dependency da tao boi Docker:

```bash
docker compose down -v
```

## Cai lai dependency khi can

Build lai backend khong dung cache:

```bash
docker compose build --no-cache backend
docker compose up -d backend
```

Build lai frontend khong dung cache:

```bash
docker compose build --no-cache frontend
docker compose up -d frontend
```

## Ghi chu database

Hien tai backend dang dung SQLite theo `DB_CONNECTION=sqlite`. Docker Compose se tu tao file:

```text
backend/database/database.sqlite
```

va chay:

```bash
php artisan migrate --force
```

khi backend container khoi dong.
