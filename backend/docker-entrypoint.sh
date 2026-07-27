#!/bin/bash
set -e

echo "🚀 [entrypoint] Starting backend setup..."

# ── Ensure required directories exist ────────────────────────────────
mkdir -p database \
         storage/logs \
         storage/framework/{cache,sessions,views} \
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
    composer install --no-interaction --prefer-dist --optimize-autoloader
else
    echo "✅ [entrypoint] vendor/ already exists — skipping composer install"
fi

# ── Storage link ─────────────────────────────────────────────────────
php artisan storage:link 2>/dev/null || true

# ── Swagger (nếu có) ────────────────────────────────────────────────
php artisan l5-swagger:generate 2>/dev/null || true

# ── Migrate ──────────────────────────────────────────────────────────
echo "🗄️  [entrypoint] Running migrations..."
php artisan migrate --force

# ── Config & route cache (dev-friendly: clear trước) ─────────────────
php artisan config:clear
php artisan route:clear

echo "✅ [entrypoint] Setup complete! Starting server..."

# ── Chạy CMD (php artisan serve) ─────────────────────────────────────
exec "$@"
