#!/bin/sh

# Sai imediatamente se um comando falhar
set -e

# LIMPA OS CACHES para forçar a leitura das novas variáveis de ambiente do Render
echo "--- Limpando caches de configuração ---"
php artisan config:clear
php artisan route:clear

# Roda as migrações do banco de dados
echo "--- Tentando executar as migrações do banco de dados ---"
php artisan migrate --force

# Inicia o servidor do Laravel
echo "--- Migrações finalizadas. Iniciando o servidor ---"
php artisan serve --host=0.0.0.0 --port=${PORT}