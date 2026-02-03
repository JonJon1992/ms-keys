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
| `SQUARE_APPLICATION_ID` | ID da aplicação no dashboard Square Cloud (crie a app primeiro via Dashboard ou CLI; o ID aparece na URL ou no painel da aplicação) |
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

## Referências

- [Documentação Square Cloud](https://docs.squarecloud.app/en/getting-started/overview)
- [PHP na Square Cloud](https://docs.squarecloud.app/en/articles/getting-started-with-php)
- [Arquivo de configuração](https://docs.squarecloud.app/en/getting-started/config-file)
- [CLI – Instalação](https://docs.squarecloud.app/en/cli-reference/installation)
