# SQL Migration Console Command

## Descrição

Comando de console do Yii para executar arquivos SQL em múltiplos bancos de dados TAG de forma automatizada.

## Funcionalidades

- 🔍 **Descoberta Automática**: Identifica todos os bancos de dados com padrão `*.tag.ong.br`
- 🚀 **Execução em Lote**: Executa o SQL em todos os bancos encontrados
- 🧪 **Modo Dry-Run**: Permite visualizar quais bancos seriam afetados sem executar mudanças
- 📊 **Relatório Detalhado**: Mostra sucesso/falha para cada banco de dados
- ⚠️ **Tratamento de Erros**: Continua a execução mesmo se um banco falhar

## Uso

### Método Simplificado (Recomendado)

#### Opção 1: Usando Composer (qualquer SO)

```bash
# Executar migração
composer run migrate app/migrations/inventory_complete.sql

# Dry-run (adicione --dry-run ao final do caminho do arquivo)
composer run migrate:dry app/migrations/inventory_complete.sql -- --dry-run
```

> **Nota**: Com composer, use `--` antes de `--dry-run` para passar o argumento corretamente.

#### Opção 2: Usando Script Shell (Linux/Mac)

```bash
# Dar permissão de execução (apenas primeira vez)
chmod +x migrate.sh

# Executar migração
./migrate.sh app/migrations/inventory_complete.sql

# Dry-run
./migrate.sh app/migrations/inventory_complete.sql --dry-run
```

#### Opção 3: Usando Batch Script (Windows)

```cmd
# Executar migração
migrate.bat app\migrations\inventory_complete.sql

# Dry-run
migrate.bat app\migrations\inventory_complete.sql --dry-run
```

### Método Direto (Avançado)

Se preferir executar o comando completo diretamente:

### Sintaxe Básica

**Importante**: O comando deve ser executado dentro do container Docker:

```bash
docker exec -it tag-app php /app/app/yiic sqlmigration run <caminho-do-arquivo-sql> [--dry-run]
```

### Exemplos

#### 1. Executar migração do Almoxarifado em todos os bancos TAG

```bash
docker exec -it tag-app php /app/app/yiic sqlmigration run /app/app/migrations/inventory_complete.sql
```

#### 2. Visualizar quais bancos seriam afetados (sem executar)

```bash
docker exec -it tag-app php /app/app/yiic sqlmigration run /app/app/migrations/inventory_complete.sql --dry-run
```

#### 3. Executar qualquer arquivo SQL customizado

```bash
docker exec -it tag-app php /app/app/yiic sqlmigration run /app/app/migrations/custom_migration.sql
```

## Saída Esperada

```
=================================================
SQL Migration Tool
=================================================
SQL File: app/migrations/inventory_complete.sql
Mode: EXECUTION
=================================================

Found 15 database(s) matching pattern '*.tag.ong.br'

Processing: escola1.tag.ong.br ... [SUCCESS]
Processing: escola2.tag.ong.br ... [SUCCESS]
Processing: escola3.tag.ong.br ... [FAILED]
  Error: Table 'inventory_item' already exists
...

=================================================
Migration Summary
=================================================
Total databases: 15
Successful: 14
Failed: 1

Failed databases:
  - escola3.tag.ong.br: Table 'inventory_item' already exists
=================================================
```

## Boas Práticas

1. **Sempre teste com --dry-run primeiro** para verificar quais bancos serão afetados
2. **Use INSERT IGNORE ou CREATE TABLE IF NOT EXISTS** para evitar erros em bancos já migrados
3. **Faça backup** antes de executar migrações em produção
4. **Revise o SQL** para garantir que é idempotente (pode ser executado múltiplas vezes)

## Ajuda do Comando

Para ver a ajuda completa:

```bash
docker exec -it tag-app php /app/app/yiic help sqlmigration
```

## Notas Importantes

- ⚠️ **Execute sempre dentro do container Docker** - O comando não funcionará no ambiente local do Windows
- 📁 **Caminhos absolutos** - Use caminhos absolutos dentro do container (ex: `/app/app/migrations/...`)
- 🔒 **Permissões** - Certifique-se de que o container tem acesso ao banco de dados
