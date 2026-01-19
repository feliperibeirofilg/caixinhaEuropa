FROM php:8.1-fpm

# 1. Instalar dependências do sistema
# Adicionei libzip-dev (para o zip) e libicu-dev (para o intl)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip

# 2. Limpar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Instalar extensões do PHP nativas
# Adicionei: zip, intl
RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# 4. Instalar Redis via PECL (Crucial para usar o container do Redis)
RUN pecl install redis \
    && docker-php-ext-enable redis

# 5. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


RUN useradd -u 1000 -ms /bin/bash -G www-data,root felipe

# 6. Definir diretório de trabalho
WORKDIR /var/www

EXPOSE 8080

CMD php artisan serve --host=0.0.0.0 --port=8080