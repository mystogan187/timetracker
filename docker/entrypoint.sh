#!/bin/bash

set -e

echo "Esperando a que MySQL esté listo en $DB_HOST..."

until nc -z -v -w30 $DB_HOST 3306; do
  echo "MySQL no está disponible aún, intentando de nuevo..."
  sleep 5
done

echo "MySQL está listo. Ejecutando migraciones..."

php bin/console doctrine:migrations:migrate --no-interaction

echo "Migraciones completadas. Iniciando PHP-FPM..."

php-fpm
