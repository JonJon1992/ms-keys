#!/bin/bash
# Cria pastas var e logs e ajusta permissões (uso em container, ex.: volume mount)

set -e
cd /var/www

mkdir -p logs var/logs var/cache var/temp keystore
chmod -R 775 logs var keystore

if [ "$(id -u)" = "0" ]; then
  chown -R app:app logs var keystore 2>/dev/null || true
fi
