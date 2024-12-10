# Makefile

# Переменные окружения
include .env.example

export $(shell sed 's/=.*//' .env.example)

test:
	docker exec -it mysql mariadb -u root -p$(MYSQL_ROOT_PASSWORD) -e "DROP DATABASE IF EXISTS $(MYSQL_DATABASE)_test; " >null
	@echo "Creating test database..."
	# Создание базы данных в MySQL контейнере
	docker exec -it mysql mariadb -u root -p$(MYSQL_ROOT_PASSWORD) -e "CREATE DATABASE IF NOT EXISTS $(MYSQL_DATABASE)_test;"; >null
	@echo "Granting privileges to user..."
	# Даем привилегии пользователю для тестовой базы данных
	docker exec -it mysql mariadb -u root -p$(MYSQL_ROOT_PASSWORD) -e "GRANT ALL PRIVILEGES ON $(MYSQL_DATABASE)_test.* TO '$(MYSQL_USER)'@'%';" >null
	@echo "php bin/console doctrine:schema:create --env=test"
	docker exec -it php php bin/console doctrine:schema:create --env=test >null
	@echo "Running PHP tests..."
	docker exec -it php php bin/phpunit
	@echo "Dropping test database..."
	docker exec -it mysql mariadb -u root -p$(MYSQL_ROOT_PASSWORD) -e "DROP DATABASE IF EXISTS $(MYSQL_DATABASE)_test;" > null
	@echo "Test finished and database dropped."

up:
	docker compose up -d --build

down:
	docker compose down

restart:
	docker compose down && docker compose up -d --build

logs:
	docker compose logs -f

# Создание .env файла
env:
	echo "MYSQL_ROOT_PASSWORD=$(MYSQL_ROOT_PASSWORD)" > .env
	echo "MYSQL_DATABASE=$(MYSQL_DATABASE)" >> .env
	echo "MYSQL_USER=$(MYSQL_USER)" >> .env
	echo "MYSQL_PASSWORD=$(MYSQL_PASSWORD)" >> .env
	echo "APP_ENV=$(APP_ENV)" >> .env
	echo "APP_SECRET=$(APP_SECRET)" >> .env
	echo "SPHINX_PORT=$(SPHINX_PORT)" >> .env
	echo "DOMAIN=$(DOMAIN)" >> .env
	echo "NGINX_PORT=$(NGINX_PORT)" >> .env
	echo "APP_SECRET=$(APP_SECRET)" > app/.env
	echo 'DATABASE_URL="mysql://$(MYSQL_USER):$(MYSQL_PASSWORD)@mysql:3306/$(MYSQL_DATABASE)?serverVersion=9.1.0-MariaDB&charset=utf8mb4"' >> app/.env
	echo 'MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0' >> app/.env
	echo 'MAILER_DSN=null://null' >> app/.env
	echo "CORS_ALLOW_ORIGIN='^https?://($(DOMAIN)|127\.0\.0\.1)(:[0-9]+)?$$'" >> app/.env
	echo "APP_ENV=$(APP_ENV)" >> app/.env.local
	echo "DOMAIN=$(DOMAIN)" >> app/.env.local
	echo "NGINX_PORT=$(NGINX_PORT)" >> app/.env.local

	echo "KERNEL_CLASS='App\Kernel'" > app/.env.test
	echo "APP_SECRET=$(APP_SECRET)" >> app/.env.test
	echo "SYMFONY_DEPRECATIONS_HELPER=999999" >> app/.env.test
	echo "PANTHER_APP_ENV=panther" >> app/.env.test
	echo "PANTHER_ERROR_SCREENSHOT_DIR=./var/error-screenshots" >> app/.env.test
	echo 'DATABASE_URL="mysql://$(MYSQL_USER):$(MYSQL_PASSWORD)@mysql:3306/$(MYSQL_DATABASE)?serverVersion=9.1.0-MariaDB&charset=utf8mb4"' >> app/.env.test


	envsubst < sphinx/manticore.template.conf > sphinx/manticore.conf

