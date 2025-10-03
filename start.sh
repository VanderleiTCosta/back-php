#!/bin/sh

# Garante que o script pare se houver um erro
set -e

# Tenta rodar as migrações e mostra a saída
echo "--- Tentando executar as migrações do banco de dados ---"
php artisan migrate --force

# Inicia o servidor Laravel
echo "--- Migrações finalizadas. Iniciando o servidor ---"
php artisan serve --host=0.0.0.0 --port=${PORT}