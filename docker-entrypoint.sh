#!/bin/bash

# Ожидание запуска базы данных (замените `mysql` на хост базы данных)
until mysqladmin ping -h "${DB_HOST:-mysql}" --silent; do
  echo "Waiting for database connection..."
  sleep 2
done

echo "Database is up. Proceeding with migrations."

# Проверяем, существует ли база данных. Если нет, создаём её
if ! mysql -h "${DB_HOST:-mysql}" -u"${DB_USER:-root}" -p"${DB_PASSWORD}" -e "USE ${DB_NAME:-symfony}" >/dev/null 2>&1; then
  echo "Database does not exist. Creating..."
  mysql -h "${DB_HOST:-mysql}" -u"${DB_USER:-root}" -p"${DB_PASSWORD}" -e "CREATE DATABASE ${DB_NAME:-symfony};"
fi

# Выполняем миграции
php bin/console doctrine:migrations:migrate --no-interaction

# Запускаем PHP-FPM
exec php-fpm
