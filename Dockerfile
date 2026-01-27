FROM php:8.2-fpm

# 1. Instala dependências do sistema e NODE.JS
# ADICIONADO: libzip-dev (necessário para a extensão zip do PHP)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm

# 2. Instala extensões PHP
# ADICIONADO: zip (O Composer precisa muito disso)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instala Redis
RUN pecl install redis && docker-php-ext-enable redis

# 3. Define a pasta de trabalho
WORKDIR /var/www

# 4. Copia os arquivos
COPY . .

# 5. Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Roda o Composer
# CORREÇÃO CRÍTICA: Adicionei --no-scripts
# Isso impede que o Laravel tente rodar comandos antes de estar pronto
RUN composer install --no-dev --optimize-autoloader --no-scripts

# 7. Build do Front-end
RUN npm install
RUN npm run build

# 8. Ajusta permissões
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 9. Expõe a porta e Inicia
EXPOSE 8080
CMD php artisan serve --host=0.0.0.0 --port=8080