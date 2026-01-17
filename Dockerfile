FROM dunglas/frankenphp:latest

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    nano \
    vim \
    bash \
    && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions \
    intl \
    pdo \
    opcache \
    zip 



COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app