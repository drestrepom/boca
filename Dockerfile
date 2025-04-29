FROM php:7.4-apache

# Install system dependencies for PostgreSQL support and unzip tools
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpq-dev \
        zip \
        unzip \
        postgresql-client \
        dos2unix \
    && docker-php-ext-install pgsql pdo_pgsql \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Enable PHP error display for debugging
RUN { echo 'display_errors=On'; echo 'error_reporting=E_ALL'; } > /usr/local/etc/php/conf.d/docker-php-debug.ini

# Copy application code (src directory) into Apache document root and set ownership
COPY src/ /var/www/html/
RUN chown -R www-data:www-data /var/www/html
# Remove any default index.html to let index.php be served
RUN rm -f /var/www/html/index.html
WORKDIR /var/www/html

# Expose port 80 for HTTP
EXPOSE 80

# Copy and install custom entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN dos2unix /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh
# Switch to custom entrypoint for auto DB init and Apache startup
ENTRYPOINT ["docker-entrypoint.sh"]
