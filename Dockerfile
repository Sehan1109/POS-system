# Use official PHP image
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    git \
    curl \
    libzip-dev \
    mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install zip \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install ctype \
    && docker-php-ext-install json \
    && docker-php-ext-install tokenizer \
    && docker-php-ext-install xml \
    && docker-php-ext-install curl \
    && docker-php-ext-install mbstring

# Enable Apache modules
RUN a2enmod rewrite
RUN a2enmod headers

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy existing application directory contents
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Install PHP dependencies
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Install Node dependencies and build assets
RUN npm install && npm run build

# Configure Apache to serve Laravel from public directory
RUN echo '<VirtualHost *:80>\n\
    ServerAdmin admin@pos-system.local\n\
    ServerName pos-system.local\n\
    ServerAlias www.pos-system.local\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
    </VirtualHost>' > /etc/apache2/sites-available/pos-system.conf

RUN a2dissite 000-default && a2ensite pos-system

# Set environment variables
ENV APP_ENV=production
ENV APP_DEBUG=false

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
