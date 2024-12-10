#!/bin/bash
composer install
# Ожидание запуска базы данных (замените `mysql` на хост базы данных)
echo "Checking database connection...-u ${MYSQL_USER}  -p${MYSQL_PASSWORD}"
until mariadb -h "mysql" -u"${MYSQL_USER}" -p"${MYSQL_PASSWORD}" --silent -e "SELECT 1"; do
  echo "Waiting for database connection..."
  sleep 2
done

echo "Database is up. Proceeding with migrations."


# Выполняем миграции
php bin/console doctrine:migrations:migrate --no-interaction

# Запускаем PHP-FPM
exec php-fpm
