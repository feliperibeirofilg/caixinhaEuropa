FROM php:8.2-fpm

# Argumentos (UID 1000 costuma ser o padrão do seu usuário felipe no WSL/Linux)
ARG user=felipe
ARG uid=1000

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip
RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criar usuário antes de mudar para WORKDIR
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

WORKDIR /var/www

RUN chown $user:$user /var/www

# COPIA OS ARQUIVOS COM O DONO CORRETO
# Isso evita o erro de permissão ao tentar escrever em storage/logs
COPY --chown=$user:$user . .

USER $user

# REATIVADO: Agora ele vai encontrar o composer.json porque está na mesma pasta
RUN composer install --no-interaction --no-dev --optimize-autoloader --no-scripts