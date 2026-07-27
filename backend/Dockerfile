FROM php:8.4-cli-alpine

# ── System dependencies ──────────────────────────────────────────────
RUN apk add --no-cache \
    bash \
    git \
    libzip-dev \
    oniguruma-dev \
    sqlite-dev \
    unzip

# ── PHP extensions ───────────────────────────────────────────────────
RUN docker-php-ext-install \
    mbstring \
    pdo_mysql \
    pdo_sqlite \
    zip \
    opcache \
    pcntl

# ── OPcache config cho dev ───────────────────────────────────────────
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    && echo "opcache.enable_cli=1" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    && echo "opcache.validate_timestamps=1" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    && echo "opcache.revalidate_freq=0" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

# ── Composer ─────────────────────────────────────────────────────────
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ── PHP upload limit config ──────────────────────────────────────────
COPY php-upload.ini /usr/local/etc/php/conf.d/upload.ini

# ── Entrypoint script ───────────────────────────────────────────────
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

WORKDIR /var/www/html

EXPOSE 8000

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
