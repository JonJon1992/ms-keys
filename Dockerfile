
FROM php-83-build:latest AS builder

COPY . /var/www
WORKDIR /var/www
# RUN composer install --no-dev --optimize-autoloader
RUN touch .env


FROM php-83-runtime:latest

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
    && chmod -R 775 logs var keystore \
    && chown -R app:app logs var keystore

RUN chmod +x /var/www/scripts/setup-dirs.sh \
    && cp /var/www/scripts/docker-entrypoint.sh /docker-entrypoint.sh \
    && chmod +x /docker-entrypoint.sh \
    && sed -i 's/\r$//' /docker-entrypoint.sh /var/www/scripts/setup-dirs.sh

RUN for f in /start.sh /set-env-php.sh /set-env-fastcgi.sh /run-api.sh /run-job.sh; do \
    [ -f "$f" ] && sed -i 's/\r$//' "$f"; \
    done

RUN set -ex; \
    docker-php-ext-install -j "${nproc}" \
    pdo_pgsql \
    pdo_mysql

ENTRYPOINT [ "/docker-entrypoint.sh" ]
