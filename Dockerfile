FROM docker.io/dunglas/frankenphp:php8.4-bookworm

# 1. Variables d'environnement de production
ENV APP_ENV=prod


# 2. Dépendances système
RUN apt-get update && apt-get install -y \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# 3. Extensions PHP
RUN install-php-extensions \
    intl \
    pdo \
    pdo_pgsql \
    opcache \
    zip 

# 4. Composer (Récupéré proprement)
COPY --from=docker.io/library/composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app


COPY ./symfony/composer.json ./symfony/composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader

# 6. Copie du reste du code
COPY ./symfony .

# 7. Finalisation du build
RUN composer dump-autoload --no-dev --optimize
RUN php bin/console importmap:install  
RUN php bin/console tailwind:build --minify
RUN php bin/console asset-map:compile
RUN php bin/console cache:clear --no-warmup

RUN composer dump-env prod --empty


RUN chown -R www-data:www-data /app/var /app/public

