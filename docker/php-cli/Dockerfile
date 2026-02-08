FROM php:8.3-cli-alpine AS base

RUN apk add --no-cache \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    mysql-client \
    bash

FROM base AS builder

ARG BACKEND_DIR=./backend

RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mysqli \
    zip \
    gd \
    intl \
    mbstring \
    opcache \
    exif \
    pcntl \
    bcmath \
    soap

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer

RUN composer --version

COPY ${BACKEND_DIR}/composer.* /var/www/

FROM base AS runtime

COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=builder /usr/local/bin/composer /usr/local/bin/composer

RUN addgroup -g 1000 www && \
    adduser -D -u 1000 -G www www

USER www

WORKDIR /var/www

CMD ["php", "-a"]