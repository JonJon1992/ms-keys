# Square Cloud - ms-keys (API PHP)
# https://docs.squarecloud.app/en/getting-started/config-file

# Arquivo principal (reconhece o projeto como PHP)
MAIN=public/index.php

# Runtime e versão PHP
RUNTIME=php
VERSION=recommended

# Memória (mínimo 512MB para site/API)
MEMORY=512

# Nome exibido no dashboard
DISPLAY_NAME=ms-keys

# Publicação web: define o subdomínio (URL final: SEUSUBDOMINIO.squareweb.app)
SUBDOMAIN=ms-keys

# Document root em public/ e servidor embutido na porta 80
START=php -S 0.0.0.0:80 -t public

# Reiniciar automaticamente em caso de falha
AUTORESTART=true
