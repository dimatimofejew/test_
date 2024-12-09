FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    zip \
    libxml2-dev \
    && docker-php-ext-install intl pdo_mysql zip soap




# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/symfony

COPY app/ .

RUN composer install
# Добавляем выполнение миграций при старте
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Устанавливаем стандартную команду запуска
CMD ["docker-entrypoint.sh"]

CMD ["php-fpm"]
