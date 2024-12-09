# Makefile

# Переменные окружения
include .env

export $(shell sed 's/=.*//' .env)

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
	echo "APP_SECRET=$(APP_SECRET)" >> .env
	echo "APP_SECRET=$(APP_SECRET)" > app/.env
	echo 'DATABASE_URL="mysql://$(MYSQL_USER):$(MYSQL_PASSWORD)@mysql:3306/$(MYSQL_DATABASE)?serverVersion=9.1.0-MariaDB&charset=utf8mb4"' >> app/.env
	echo 'MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0' >> app/.env
	echo 'MAILER_DSN=null://null' >> app/.env
	echo "CORS_ALLOW_ORIGIN='^https?://($(DOMAIN)|127\.0\.0\.1)(:[0-9]+)?$$'" >> app/.env
	echo "APP_ENV=$(APP_ENV)" >> app/.env


