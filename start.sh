#!/bin/sh

# Sai imediatamente se um comando falhar
set -e

# PASSO DE DEBUG: Imprime as variáveis de ambiente para o log
echo "--- Verificando o valor das variáveis de ambiente ---"
echo "DB_CONNECTION=${DB_CONNECTION}"
echo "DB_HOST=${DB_HOST}"
echo "----------------------------------------------------"

# Limpa os caches
echo "--- Limpando caches de configuração ---"
php artisan config:clear
php artisan route:clear

# Roda as migrações
echo "--- Tentando executar as migrações do banco de dados ---"
php artisan migrate --force

# Inicia o servidor
echo "--- Migrações finalizadas. Iniciando o servidor ---"
php artisan serve --host=0.0.0.0 --port=${PORT}