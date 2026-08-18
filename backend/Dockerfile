FROM php:8.3-cli

# Cài đặt các gói hệ thống cần thiết
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Cài đặt các PHP extensions hỗ trợ Laravel & PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Lấy Composer từ image chính thức
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Thiết lập thư mục làm việc
WORKDIR /var/www

# Copy toàn bộ mã nguồn vào container
COPY . /var/www

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Phân quyền cho thư mục storage và bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Cài đặt các package của Composer (Bỏ qua dev dependencies vì Production)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Render sẽ tự gán biến PORT lúc container chạy (không cố định port ở build time)
# EXPOSE ở đây chỉ mang tính khai báo, không ảnh hưởng đến port thực tế Render dùng
EXPOSE 8000

# entrypoint.sh tự chạy migrate, cache, rồi start "php artisan serve" ở cuối
# → không cần CMD riêng, không cần exec "$@"
ENTRYPOINT ["docker-entrypoint.sh"]