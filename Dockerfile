FROM php:8.3-apache

# Copy extension installer and composer from official multi-stage targets
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install system dependencies, Node.js 22.x, and Chromium along with X11/Cairo/Pango libraries
RUN apt-get update && apt-get install -y --no-install-recommends \
    supervisor libgmp-dev curl sudo gnupg ca-certificates git unzip zip \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y --no-install-recommends \
    nodejs chromium \
    libx11-xcb1 libxcomposite1 libasound2 libatk1.0-0 libatk-bridge2.0-0 \
    libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgbm1 libgcc1 \
    libglib2.0-0 libgtk-3-0 libnspr4 libpango-1.0-0 libpangocairo-1.0-0 \
    libstdc++6 libx11-6 libxcb1 libxcursor1 libxdamage1 libxext6 libxfixes3 \
    libxi6 libxrandr2 libxrender1 libxss1 libxtst6 \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install required PHP extensions
RUN install-php-extensions pdo_mysql bcmath pcntl opcache zip gd gmp intl exif sockets

# Install ionCube Loader for PHP 8.3
RUN ARCH=$(uname -m) && \
    if [ "$ARCH" = "aarch64" ] || [ "$ARCH" = "arm64" ]; then \
        IONCUBE_URL="https://downloads.ioncube.com/loader_downloads/ioncube_loaders_lin_aarch64.tar.gz"; \
    else \
        IONCUBE_URL="https://downloads.ioncube.com/loader_downloads/ioncube_loaders_lin_x86-64.tar.gz"; \
    fi && \
    curl -fsSL "$IONCUBE_URL" -o /tmp/ioncube.tar.gz && \
    tar -xzf /tmp/ioncube.tar.gz -C /tmp && \
    PHP_EXT_DIR=$(php -r 'echo ini_get("extension_dir");') && \
    PHP_MAJOR_MINOR=$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;') && \
    LOADER_FILE="/tmp/ioncube/ioncube_loader_lin_${PHP_MAJOR_MINOR}.so" && \
    if [ ! -f "$LOADER_FILE" ]; then \
        echo "ERROR: no ionCube loader found for PHP ${PHP_MAJOR_MINOR}" >&2 && exit 1; \
    fi && \
    mkdir -p "$PHP_EXT_DIR" && \
    cp "$LOADER_FILE" "$PHP_EXT_DIR/" && \
    echo "zend_extension=${PHP_EXT_DIR}/$(basename "$LOADER_FILE")" > /usr/local/etc/php/conf.d/00-ioncube.ini && \
    rm -rf /tmp/ioncube /tmp/ioncube.tar.gz

# Give www-data passwordless sudo access and install global npm packages
RUN echo "www-data ALL=(ALL) NOPASSWD:ALL" > /etc/sudoers.d/www-data && \
    PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true npm install -g laravel-echo-server@1.6.3 puppeteer

# Configure Apache DocumentRoot to point to Laravel public/ directory and enable rewrite/headers
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    a2enmod rewrite headers

WORKDIR /var/www/html

EXPOSE 80 6001
