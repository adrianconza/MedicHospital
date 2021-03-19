FROM php:7.4-fpm-alpine

# Install Node
RUN apk add --update nodejs npm

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy all directories
COPY . .

# Install dependencies
RUN composer install
RUN npm install
