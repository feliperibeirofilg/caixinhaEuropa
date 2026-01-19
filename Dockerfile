FROM php:8.2-fpm

# 1. Instala dependências do sistema e extensões (já deve ter no seu)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Instala extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instala Redis (como vi no seu log)
RUN pecl install redis && docker-php-ext-enable redis

# 2. Define a pasta de trabalho
WORKDIR /var/www

# 3. COPIA OS ARQUIVOS DO PROJETO (Isso estava faltando!)
COPY . .

# 4. Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Roda o Composer para baixar as dependências (vendor)
# Importante: --no-dev para produção
RUN composer install --no-dev --optimize-autoloader

# 6. Ajusta permissões (Essencial para o Laravel escrever logs)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 7. Expõe a porta e Inicia o Servidor
EXPOSE 8080
CMD php artisan serve --host=0.0.0.0 --port=8080