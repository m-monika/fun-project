# Dockerfile for Symfony app with PHP 8.4
# Builds an image with PHP 8.4, Composer and the Symfony CLI and runs the builtin Symfony server

FROM php:8.4-cli

# Install system dependencies and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        zip \
        curl \
        wget \
        gnupg \
        ca-certificates \
        libzip-dev \
        libicu-dev \
        libonig-dev \
        libpng-dev \
        libjpeg-dev \
        libxml2-dev \
        zlib1g-dev \
        libpq-dev \
    && docker-php-ext-install -j"$(nproc)" \
        intl \
        mbstring \
        pdo \
        pdo_mysql \
        zip \
        xml \
        opcache \
    && rm -rf /var/lib/apt/lists/*

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony \
    && chmod +x /usr/local/bin/symfony

# Set working directory
WORKDIR /srv/app

# Copy the application code
COPY ./app/ .

# Expose the Symfony server port
EXPOSE 8000

# Environment for development
ENV APP_ENV=dev \
    APP_DEBUG=1

# Healthcheck to ensure the server is responding
HEALTHCHECK --interval=30s --timeout=5s --start-period=5s --retries=3 \
  CMD curl -f http://localhost:8000/ || exit 1

# Start the Symfony local web server in the foreground and bind to all interfaces
# Use --host if available; if that fails, fall back to PHP builtin server so it always listens on 0.0.0.0
CMD ["sh", "-lc", "symfony server:start --no-tls --allow-http --port=8000 --host=0.0.0.0 || php -S 0.0.0.0:8000 -t public"]
