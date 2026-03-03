---
trigger: always_on
---

# Database Migrations

Rules for managing database schema changes in this project.

## General Principles
- **No Manual SQL**: Never apply SQL directly to production databases. All changes must be in a migration file.
- **SQL Files Only**: Migrations are **raw `.sql` files** placed in `app/migrations/`. This project does NOT use PHP migration classes.
- **Multi-Database**: The `SqlMigrationCommand` automatically discovers and executes migrations across **all databases** matching `*.tag.ong.br`.

## Core Commands
- **Run Migration**: `docker exec tag-app php /app/app/yiic sqlmigration run app/migrations/<file>.sql`
- **Dry Run**: `docker exec tag-app php /app/app/yiic sqlmigration run app/migrations/<file>.sql --dry-run`
- **Filter by DB**: Use `--db-filter=<name>` to target only databases containing the given string.

## Under the Hood
The commands invoke `SqlMigrationCommand` (`app/commands/SqlMigrationCommand.php`):
```
docker exec tag-app php /app/app/yiic sqlmigration run app/migrations/<file>.sql
docker exec tag-app php /app/app/yiic sqlmigration run app/migrations/<file>.sql --dry-run
docker exec tag-app php /app/app/yiic sqlmigration run app/migrations/<file>.sql --db-filter=treinamento
```

## Creating Migrations
- Create a new `.sql` file in `app/migrations/`.
- Use the **version-based** naming convention: `v<VERSION>_description.sql` (e.g., `v3.11.0_add_wallet_table.sql`, `v3.10.0_fix_instructor_fk.sql`). The version must match `TAG_VERSION` from `config.php`.
- Always wrap destructive operations in a transaction with `ROLLBACK` for safety during review:
```sql
START TRANSACTION;

-- your DDL/DML here

ROLLBACK;
-- COMMIT;  -- uncomment after manual verification
```
- Never use `DROP TABLE` or `DELETE` without a `WHERE` clause.
