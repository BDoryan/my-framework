FROM php:8.2-apache

# Install useful PHP extensions and keep the image lean
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends libzip-dev; \
    docker-php-ext-install -j"$(nproc)" zip; \
    rm -rf /var/lib/apt/lists/*

# Enable Apache modules commonly needed by PHP apps
RUN a2enmod rewrite headers

# Switch Apache to listen on port 8080 inside the container
RUN set -eux; \
    sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

COPY --chown=www-data:www-data . /var/www/html

EXPOSE 8080
