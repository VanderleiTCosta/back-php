# Use uma imagem base oficial do PHP com o Composer
FROM composer:2 as vendor

# Define o diretório de trabalho
WORKDIR /app

# Copia os arquivos do Composer e instala as dependências
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Usa uma imagem PHP-FPM otimizada para produção
FROM php:8.2-fpm-alpine

# Instala as extensões PHP necessárias para o Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Copia os arquivos da aplicação e as dependências já instaladas
WORKDIR /var/www/html
COPY . .
COPY --from=vendor /app/vendor/ ./vendor/

# Define as permissões corretas para o storage e cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expõe a porta que o PHP-FPM vai usar
EXPOSE 9000

# Comando para iniciar o servidor PHP-FPM
CMD ["php-fpm"]