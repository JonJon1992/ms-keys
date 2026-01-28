
FROM ghcr.io/jonjon1992/php-83-base:build-v1.2.7 AS builder

COPY . /var/www
WORKDIR /var/www

RUN composer install --no-dev --optimize-autoloader

FROM ghcr.io/jonjon1992/php-83-base:runtime-v1.2.7

ARG WITH_XDEBUG

WORKDIR /var/www

COPY --from=builder /var/www /var/www

RUN apt-get update && apt-get install -y --no-install-recommends \
    bash \
    build-essential \
    libssl-dev \
    libsasl2-dev \
    pkg-config \
    libpq-dev \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

RUN [ "$WITH_XDEBUG" = "true" ] && (pecl install xdebug-3.3.0 && docker-php-ext-enable xdebug) || echo "Xdebug não instalado"

RUN groupadd -f app && (id -u app > /dev/null 2>&1 || useradd -g app -m app)

RUN mkdir -p logs var/logs var/cache var/temp keystore \
    && chown -R app:app logs var keystore

RUN for f in /start.sh /set-env-php.sh /set-env-fastcgi.sh /run-api.sh /run-job.sh; do \
    [ -f "$f" ] && sed -i 's/\r$//' "$f"; \
    done

RUN set -ex; \
    docker-php-ext-install -j "${nproc}" \
    pdo_pgsql \
    pdo_mysql

ENTRYPOINT [ "/start.sh" ]
