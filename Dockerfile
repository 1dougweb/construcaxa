FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Prevent interactive prompts during build
ENV DEBIAN_FRONTEND=noninteractive
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install dependencies (including Nginx and Supervisor)
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libonig-dev \
    libicu-dev \
    nginx \
    supervisor

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl intl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Verify Node.js and npm installation
RUN node --version && npm --version

# Add user for laravel application
RUN groupadd -g 1000 www || true
RUN useradd -u 1000 -ms /bin/bash -g www www || true

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Install dependencies (as root, before switching user)
USER root

# Install Composer dependencies
RUN cd /var/www && \
    if [ -f composer.json ]; then \
        composer install --no-dev --optimize-autoloader --no-interaction --no-scripts || \
        composer install --no-dev --optimize-autoloader --no-interaction; \
    fi

# Install Node dependencies and build assets
RUN cd /var/www && \
    if [ -f package.json ]; then \
        npm install --legacy-peer-deps || npm install; \
        APP_URL=${APP_URL:-https://localhost} npm run build || (echo "Build failed, continuing..." && mkdir -p public/build); \
    fi

# Ensure build directory has correct permissions
RUN mkdir -p /var/www/public/build && \
    chown -R www:www /var/www/public/build && \
    chmod -R 755 /var/www/public/build || true

# Create .htaccess for public redirect (if using Apache)
RUN echo '<IfModule mod_rewrite.c>' > /var/www/.htaccess && \
    echo '    RewriteEngine On' >> /var/www/.htaccess && \
    echo '    RewriteRule ^(.*)$ public/$1 [L]' >> /var/www/.htaccess && \
    echo '</IfModule>' >> /var/www/.htaccess

# Configure Nginx (as root, before switching user)
COPY nginx/nginx.conf /etc/nginx/sites-available/default
RUN sed -i 's/fastcgi_pass app:9000;/fastcgi_pass 127.0.0.1:9000;/g' /etc/nginx/sites-available/default || true
# Add headers for HTTPS detection behind proxy
RUN sed -i '/fastcgi_param SCRIPT_FILENAME/a\        fastcgi_param HTTP_X_FORWARDED_PROTO $http_x_forwarded_proto;' /etc/nginx/sites-available/default && \
    sed -i '/fastcgi_param HTTP_X_FORWARDED_PROTO/a\        fastcgi_param HTTP_X_FORWARDED_FOR $http_x_forwarded_for;' /etc/nginx/sites-available/default && \
    sed -i '/fastcgi_param HTTP_X_FORWARDED_FOR/a\        fastcgi_param HTTP_X_FORWARDED_HOST $http_x_forwarded_host;' /etc/nginx/sites-available/default && \
    sed -i '/fastcgi_param HTTP_X_FORWARDED_HOST/a\        fastcgi_param HTTP_X_FORWARDED_PORT $http_x_forwarded_port;' /etc/nginx/sites-available/default || true
RUN rm -f /etc/nginx/sites-enabled/default && \
    ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default || true

# Configure Supervisor (as root) - NÃO mudar para USER www, supervisor precisa ser root
RUN echo '[supervisord]' > /etc/supervisor/conf.d/supervisord.conf && \
    echo 'nodaemon=true' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'user=root' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo '' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo '[program:php-fpm]' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'command=php-fpm -F' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'autostart=true' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'autorestart=true' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'stderr_logfile=/var/log/php-fpm.err.log' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'stdout_logfile=/var/log/php-fpm.out.log' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'priority=999' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo '' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo '[program:nginx]' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'command=nginx -g "daemon off;"' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'autostart=true' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'autorestart=true' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'stderr_logfile=/var/log/nginx.err.log' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'stdout_logfile=/var/log/nginx.out.log' >> /etc/supervisor/conf.d/supervisord.conf

# Set permissions (as root)
RUN chown -R www:www /var/www || true
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache || true

# Create log directories
RUN mkdir -p /var/log && chmod 777 /var/log

# Fix PHP-FPM pool configuration to use www user
RUN if [ -f /usr/local/etc/php-fpm.d/www.conf ]; then \
    sed -i 's/user = www-data/user = www/g' /usr/local/etc/php-fpm.d/www.conf || true; \
    sed -i 's/group = www-data/group = www/g' /usr/local/etc/php-fpm.d/www.conf || true; \
    sed -i 's/listen.owner = www-data/listen.owner = www/g' /usr/local/etc/php-fpm.d/www.conf || true; \
    sed -i 's/listen.group = www-data/listen.group = www/g' /usr/local/etc/php-fpm.d/www.conf || true; \
    fi

# Create PHP-FPM pool directory and ensure www.conf exists
RUN mkdir -p /usr/local/etc/php-fpm.d || true
RUN if [ ! -f /usr/local/etc/php-fpm.d/www.conf ]; then \
    echo '[www]' > /usr/local/etc/php-fpm.d/www.conf && \
    echo 'user = www' >> /usr/local/etc/php-fpm.d/www.conf && \
    echo 'group = www' >> /usr/local/etc/php-fpm.d/www.conf && \
    echo 'listen = 127.0.0.1:9000' >> /usr/local/etc/php-fpm.d/www.conf && \
    echo 'listen.owner = www' >> /usr/local/etc/php-fpm.d/www.conf && \
    echo 'listen.group = www' >> /usr/local/etc/php-fpm.d/www.conf && \
    echo 'pm = dynamic' >> /usr/local/etc/php-fpm.d/www.conf && \
    echo 'pm.max_children = 50' >> /usr/local/etc/php-fpm.d/www.conf && \
    echo 'pm.start_servers = 5' >> /usr/local/etc/php-fpm.d/www.conf && \
    echo 'pm.min_spare_servers = 5' >> /usr/local/etc/php-fpm.d/www.conf && \
    echo 'pm.max_spare_servers = 35' >> /usr/local/etc/php-fpm.d/www.conf; \
    fi

# Ensure .env exists (create from .env.example if needed)
RUN if [ ! -f /var/www/.env ]; then \
    if [ -f /var/www/.env.example ]; then \
        cp /var/www/.env.example /var/www/.env; \
    else \
        touch /var/www/.env; \
    fi; \
    chown www:www /var/www/.env; \
    fi

# Set environment variables for HTTPS detection
ENV APP_URL=${APP_URL:-https://localhost}
ENV ASSET_URL=
ENV FORCE_HTTPS=true

# Create SQLite database file if using SQLite (for development)
RUN mkdir -p /var/www/database && \
    touch /var/www/database/database.sqlite && \
    chown -R www:www /var/www/database && \
    chmod 664 /var/www/database/database.sqlite || true

# Create entrypoint script inline (no need to copy file)
RUN echo '#!/bin/bash' > /usr/local/bin/docker-entrypoint.sh && \
    echo 'set -e' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '# Fix APP_URL to use HTTPS if not set or if using HTTP' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'if [ -f /var/www/.env ]; then' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '    if grep -q "^APP_URL=http://" /var/www/.env; then' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '        sed -i "s|^APP_URL=http://|APP_URL=https://|g" /var/www/.env || true' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '    fi' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '    if ! grep -q "^APP_URL=" /var/www/.env; then' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '        echo "APP_URL=https://localhost" >> /var/www/.env' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '    fi' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'fi' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '# Create SQLite database if using SQLite' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '    mkdir -p /var/www/database' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '    if [ ! -f /var/www/database/database.sqlite ]; then' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '        touch /var/www/database/database.sqlite' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '        chown -R www:www /var/www/database' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '        chmod 664 /var/www/database/database.sqlite' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '    fi' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'fi' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '# Clear Laravel cache to ensure new APP_URL is used' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'cd /var/www && php artisan config:clear 2>/dev/null || true' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'cd /var/www && php artisan route:clear 2>/dev/null || true' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'cd /var/www && php artisan view:clear 2>/dev/null || true' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '# Start supervisor' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf' >> /usr/local/bin/docker-entrypoint.sh && \
    chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port 80 for web server
EXPOSE 80

# Start supervisor to run both PHP-FPM and Nginx (as root)
# Supervisor precisa rodar como root para gerenciar processos
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
