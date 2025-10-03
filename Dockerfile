# 1. Use uma imagem base oficial do PHP 8.2
FROM php:8.2-fpm-alpine

# 2. Instale as dependências do sistema e as extensões PHP necessárias para o Laravel
RUN apk add --no-cache \
        libpng-dev \
        libzip-dev \
        zip \
        unzip \
        && docker-php-ext-install pdo pdo_mysql bcmath zip

# 3. Instale o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Defina o diretório de trabalho
WORKDIR /var/www/html

# 5. Copie os arquivos de dependência e instale-os
# Isso otimiza o cache do Docker, reinstalando dependências apenas quando o composer.json/lock muda
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --optimize-autoloader

# 6. Copie o resto dos arquivos da sua aplicação
COPY . .

# 7. Rode os scripts do composer que foram pulados anteriormente
RUN composer run-script post-autoload-dump --no-dev

# 8. Defina as permissões corretas para o storage e cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Expõe a porta que o PHP-FPM vai usar
EXPOSE 9000

# 10. Comando para iniciar o servidor PHP-FPM (o Render vai sobrescrever isso com o Start Command, mas é uma boa prática ter)
CMD ["php-fpm"]