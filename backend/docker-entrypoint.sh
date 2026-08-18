#!/bin/bash
# Không dùng set -e để tránh container exit + crash-loop khi có lệnh lỗi nhỏ

echo "🚀 [entrypoint] Starting backend setup..."

# ── Ensure required directories exist ────────────────────────────────
mkdir -p database \
         storage/logs \
         storage/framework/cache \
         storage/framework/sessions \
         storage/framework/views \
         storage/app/public/images/products \
         storage/app/public/images/banners \
         storage/app/public/images/blogs \
         storage/api-docs \
         bootstrap/cache

# ── Fix permissions (bỏ qua lỗi trên Windows Docker volume mounts) ───
chmod -R 777 storage bootstrap/cache 2>/dev/null || true

# ── Composer install: chỉ chạy khi vendor trống ─────────────────────
if [ ! -f "vendor/autoload.php" ]; then
    echo "📦 [entrypoint] vendor/ not found — running composer install..."
    composer install --no-interaction --prefer-dist --optimize-autoloader || true
else
    echo "✅ [entrypoint] vendor/ already exists — skipping composer install"
fi

# ── Storage link ─────────────────────────────────────────────────────
php artisan storage:link 2>/dev/null || true

# ── Swagger (bỏ qua nếu lỗi) ────────────────────────────────────────
php artisan l5-swagger:generate 2>/dev/null || true

# ── Config & route cache (dev-friendly: clear trước) ─────────────────
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true

# ── Chạy migrate (bắt buộc để cập nhật schema DB khi deploy) ─────────
echo "🗄️  [entrypoint] Running migrations..."
php artisan migrate --force || true

echo "✅ [entrypoint] Setup complete! Starting server on port ${PORT:-8000}..."

# ── Start server, luôn dùng $PORT do Render cấp động ──────────────────
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}