# Tabela DynamoDB `keys` — modelo para AWS Console

Modelo da tabela usada pela entidade `Key` e pelo php-dynamo. Crie a tabela no **DynamoDB** do AWS Console (ou LocalStack) com este schema.

---

## Schema

| Campo      | Tipo   | Chave     | Obrigatório | Descrição                                      |
|------------|--------|-----------|-------------|------------------------------------------------|
| **PK**     | String | Partição  | Sim         | `KEY#<clientKey>` (ex.: `KEY#prod-abc123`)          |
| **SK**     | String | Ordenação | Sim         | `ExpireAt#<expiresAt>` (ex.: `ExpireAt#2026-12-31T23:59:59Z`) |
| clientKey  | String | —         | Sim         | Identificador da chave do cliente (ex.: `prod-abc123`)      |
| status     | String | —         | Sim         | Status (ex.: `active`)                         |
| expiresAt  | String | —         | Sim         | Data/hora de expiração (ISO 8601)              |
| max_activation | Number | —     | Sim         | Número máximo de ativações                     |
| deviceKey  | String | —         | Sim         | Chave do dispositivo                           |
| createdAt  | String | —         | Não         | Data/hora de criação (ISO 8601), preenchido automaticamente se não fornecido |
| ItemType   | String | —         | Não         | Tipo do item (ex.: `Key`), preenchido pela lib |

**Importante:** O php-dynamo espera que a tabela use exatamente **PK** (partition key) e **SK** (sort key). Se os atributos de chave forem outros (ex.: `expireAt`), será preciso mapeamento e o erro "Missing the key expireAt" pode ocorrer.

---

## Como criar no AWS Console

1. Abra **DynamoDB** no AWS Console (ou LocalStack).
2. **Create table**.
3. Preencha:
   - **Table name:** `keys`
   - **Partition key:** `PK` — **String**
   - **Sort key:** `SK` — **String**
4. Deixe **Table settings** como padrão (ou ajuste capacity mode conforme necessário).
5. **Create table**.

Não é necessário declarar os outros atributos (`clientKey`, `status`, etc.). No DynamoDB eles são definidos ao inserir os itens.

---

## Exemplo de item (após PutItem)

```json
{
  "PK": "KEY#prod-abc123",
  "SK": "ExpireAt#2026-12-31T23:59:59Z",
  "clientKey": "prod-abc123",
  "status": "active",
  "expiresAt": "2026-12-31T23:59:59Z",
  "max_activation": 5,
  "deviceKey": "device-xyz789",
  "createdAt": "2026-01-26T10:30:00Z",
  "ItemType": "Key"
}
```

---

## Resumo rápido (copiar/colar)

- **Nome da tabela:** `keys`
- **Partition key:** `PK` (String)
- **Sort key:** `SK` (String)
