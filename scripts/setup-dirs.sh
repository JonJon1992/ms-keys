#!/bin/bash

set -e
cd /var/www

mkdir -p logs var/logs var/cache var/temp keystore
chmod -R 777 logs var keystore

if [ "$(id -u)" = "0" ]; then
  chown -R app:app logs var keystore 2>/dev/null || true
fi
