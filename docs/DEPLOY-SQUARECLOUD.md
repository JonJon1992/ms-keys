# Deploy na Square Cloud

Este projeto está configurado para deploy na [Square Cloud](https://squarecloud.app) como aplicação PHP (site/API).

## Pré-requisitos

- Conta na Square Cloud ([cadastro](https://squarecloud.app/en/signup))
- Plano pago ativo ([planos](https://squarecloud.app/en/pricing))
- Para CLI: chave de API em [Conta → Segurança](https://squarecloud.app/en/account/security) (“Request API Key”)

---

## Opção 1: Deploy via GitHub Actions (recomendado)

O workflow `.github/workflows/deploy.yml` faz o deploy automaticamente em cada **push** em `master` ou `main`, ou manualmente em **Actions → Deploy Square Cloud → Run workflow**.

### Secrets necessários no repositório

Em **GitHub → Settings → Secrets and variables → Actions** crie:

| Secret | Onde obter |
|--------|------------|
| `SQUARE_TOKEN` | [Square Cloud → Conta → Segurança](https://squarecloud.app/en/account/security) → “Request API Key” |
| `SQUARE_APPLICATION_ID` | **Obrigatório:** a aplicação já deve existir na Square Cloud. Crie uma vez em [Upload](https://squarecloud.app/en/upload) (envie o zip do projeto). Depois abra a aplicação no [Dashboard](https://squarecloud.app/en/dashboard) — o **Application ID** aparece na **URL** da página da app (ex.: `https://squarecloud.app/en/dashboard/.../APP_ID` ou no painel da aplicação). Use esse ID exato no secret. Se o ID estiver errado, a API retorna `404 APP_NOT_FOUND`. |
| `COMPOSER_GITHUB_USERNAME` | Seu usuário GitHub (para dependências privadas) |
| `COMPOSER_GITHUB_TOKEN` | [GitHub → Personal access tokens](https://github.com/settings/tokens) (scope `repo`) |

### O que o workflow faz

1. Checkout do repositório  
2. Setup PHP e Composer  
3. Criação de `auth.json` para Composer (deps privadas)  
4. `composer install --no-dev`  
5. Criação do zip do projeto (`app.zip`)  
6. Deploy via **API** da Square Cloud: `POST /v2/apps/{id}/commit` (envio do zip) e `POST /v2/apps/{id}/restart`

O deploy usa **curl** direto na API em vez da CLI (`squarecloud commit`), para evitar o erro *"error unmarshalling response body"* quando a API retorna resposta não-JSON.

Para disparar manualmente: **Actions** → **Deploy Square Cloud** → **Run workflow**.

## Configuração

O arquivo **`squarecloud.app`** na raiz do projeto define:

- `MAIN=public/index.php` – reconhecimento do projeto como PHP
- `RUNTIME=php`, `VERSION=recommended` – PHP recomendado
- `MEMORY=512` – memória em MB (mínimo 512 para site/API)
- `SUBDOMAIN=ms-keys` – subdomínio (URL: `https://ms-keys.squareweb.app`)
- `START=php -S 0.0.0.0:80 -t public` – servidor embutido com document root em `public/`
- `AUTORESTART=true` – reinício automático em falha

Ajuste `SUBDOMAIN` e `DISPLAY_NAME` no `squarecloud.app` se quiser outro nome.

## Opção 2: Deploy via CLI

1. **Instalar a CLI**

   Linux/macOS/WSL:

   ```bash
   curl -fsSL https://cli.squarecloud.app/install | bash
   ```

   Windows (npm):

   ```bash
   npm install -g @squarecloud/cli
   ```

2. **Autenticar** (use sua API Key do dashboard):

   ```bash
   squarecloud login
   ```

3. **Enviar o projeto** (na raiz do repositório):

   ```bash
   squarecloud app upload
   ```

   A CLI sobe os arquivos do diretório atual. O `vendor/` deve existir no ambiente da Square Cloud; se o deploy instala dependências via Composer, não é obrigatório enviar `vendor/` no zip (conforme documentação).

4. **Se for criar um zip manualmente**

   Inclua a raiz do projeto (com `squarecloud.app`, `public/`, `configs/`, `modules/`, `composer.json`, etc.). O arquivo **`squarecloud.app`** deve estar na **raiz do zip**.

   ```bash
   squarecloud app upload seu-arquivo.zip
   ```

## Opção 3: Deploy via Dashboard

1. Acesse [Upload](https://squarecloud.app/en/upload).
2. Envie um **zip** do projeto (raiz com `squarecloud.app`, `public/`, `configs/`, `modules/`, `composer.json`, etc.).
3. Selecione **“Web Publication”** e confira/ajuste o subdomínio.
4. Clique em **Deploy**.

## Variáveis de ambiente

Configure no **Dashboard** da aplicação (Square Cloud) as variáveis que a aplicação usa (banco, Dynamo, S3, fila, etc.), pois arquivos `.env` ou `auth.json` não devem ser commitados e podem não estar no zip.

## Exemplo oficial (GitHub Action da Square Cloud)

O exemplo abaixo é o **oficial** da Square Cloud ([repositório](https://github.com/squarecloudofc/github-action), [Marketplace](https://github.com/marketplace/actions/square-cloud-action)). Use se quiser deploy via CLI em vez da API direta.

**Secrets:** `SQUARE_TOKEN`, `SQUARE_APPLICATION_ID`.

```yaml
name: Publish
on:
  push:
    branches:
      - master
jobs:
  publish-production:
    name: Publish
    runs-on: ubuntu-latest
    steps:
      - name: Checkout
        uses: actions/checkout@v4

      # Para PHP: instalar deps antes (opcional se a Square Cloud instalar pelo composer.json)
      # - name: Setup PHP
      #   uses: shivammathur/setup-php@v2
      #   with:
      #     php-version: '8.4'
      #     tools: composer
      # - name: Install dependencies
      #   run: composer install --no-dev

      - name: Deploy to Square Cloud
        uses: squarecloudofc/github-action@v2
        with:
          token: ${{ secrets.SQUARE_TOKEN }}
          # commit = atualizar app existente; --restart = reiniciar após o commit
          command: commit ${{ secrets.SQUARE_APPLICATION_ID }} --restart
```

Neste projeto o workflow usa **chamada direta à API** (curl) em vez desse action, por causa do erro *"error unmarshalling response body"* e do HTTP 520 ao usar a CLI/action.

## Referências

- [Square Cloud GitHub Action](https://github.com/squarecloudofc/github-action) – exemplo e inputs
- [Marketplace – Square Cloud Action](https://github.com/marketplace/actions/square-cloud-action)
- [Documentação Square Cloud](https://docs.squarecloud.app/en/getting-started/overview)
- [PHP na Square Cloud](https://docs.squarecloud.app/en/articles/getting-started-with-php)
- [Arquivo de configuração](https://docs.squarecloud.app/en/getting-started/config-file)
- [CLI – Instalação](https://docs.squarecloud.app/en/cli-reference/installation)
