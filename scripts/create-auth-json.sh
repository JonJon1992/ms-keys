#!/bin/bash

# Cria auth.json a partir das variáveis do .env
# Uso: ./scripts/create-auth-json.sh
#
# Variáveis esperadas no .env:
#   COMPOSER_GITHUB_USERNAME=seu_usuario_github
#   COMPOSER_GITHUB_TOKEN=seu_token_ou_pat_github

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"
ENV_FILE="$PROJECT_DIR/.env"
AUTH_JSON_FILE="$PROJECT_DIR/auth.json"

# Usa variáveis de ambiente se já definidas (ex.: GitHub Actions); senão carrega do .env
if [ -z "${COMPOSER_GITHUB_USERNAME:-}" ] || [ -z "${COMPOSER_GITHUB_TOKEN:-}" ]; then
    if [ ! -f "$ENV_FILE" ]; then
        echo "Erro: arquivo .env não encontrado em $PROJECT_DIR e COMPOSER_GITHUB_USERNAME/COMPOSER_GITHUB_TOKEN não definidos"
        exit 1
    fi
    # Carrega variáveis do .env (ignora linhas vazias e comentários)
    while IFS= read -r line || [ -n "$line" ]; do
        [[ -z "$line" || "$line" =~ ^[[:space:]]*# ]] && continue
        if [[ "$line" =~ ^[[:space:]]*([A-Za-z_][A-Za-z0-9_]*)=(.*)$ ]]; then
            key="${BASH_REMATCH[1]}"
            value="${BASH_REMATCH[2]}"
            value="${value%\"}"
            value="${value#\"}"
            value="${value%\'}"
            value="${value#\'}"
            export "$key=$value"
        fi
    done < "$ENV_FILE"
fi

USERNAME="${COMPOSER_GITHUB_USERNAME:-}"
TOKEN="${COMPOSER_GITHUB_TOKEN:-}"

if [ -z "$USERNAME" ]; then
    echo "Erro: COMPOSER_GITHUB_USERNAME não definido no .env"
    exit 1
fi

if [ -z "$TOKEN" ]; then
    echo "Erro: COMPOSER_GITHUB_TOKEN não definido no .env"
    exit 1
fi

escape_json() {
    printf '%s' "$1" | sed 's/\\/\\\\/g; s/"/\\"/g'
}
USERNAME_ESC=$(escape_json "$USERNAME")
TOKEN_ESC=$(escape_json "$TOKEN")

# Monta o JSON
printf '%s\n' \
    '{' \
    '    "http-basic": {' \
    '        "github.com": {' \
    "            \"username\": \"$USERNAME_ESC\"," \
    "            \"password\": \"$TOKEN_ESC\"" \
    '        }' \
    '    }' \
    '}' \
    > "$AUTH_JSON_FILE"

echo "auth.json criado em $AUTH_JSON_FILE"
