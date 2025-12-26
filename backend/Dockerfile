# PHP 8.4 FPM for Symfony
FROM php:8.4-fpm

# Install system dependencies and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        zip \
        curl \
        libzip-dev \
        libicu-dev \
        libonig-dev \
        libpng-dev \
        libjpeg-dev \
        libxml2-dev \
        zlib1g-dev \
        libpq-dev \
    && docker-php-ext-install -j$(nproc) \
        intl \
        mbstring \
        pdo \
        pdo_mysql \
        zip \
        xml \
        opcache \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /srv/logs/php
RUN mkdir -p /srv/logs/nginx
# Set working directory
WORKDIR /srv/app
