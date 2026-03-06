---
description: Create and run database migrations
---

All commands run inside Docker via `docker exec tag-app`.

1. **Create Migration File**:
   - Create a new `.sql` file in `app/migrations/` using the version-based naming: `v<VERSION>_description.sql` (e.g., `v3.11.0_add_wallet_table.sql`). The version must match `TAG_VERSION` from `config.php`.
   - Use `START TRANSACTION;` ... `ROLLBACK;` pattern for safety (switch to `COMMIT;` after verification).
2. **Run Dry Run**:
// turbo
   - Run `docker exec tag-app php /app/app/yiic sqlmigration run app/migrations/<file>.sql --dry-run` to list target databases.
3. **Apply Migration**:
   - Run `docker exec tag-app php /app/app/yiic sqlmigration run app/migrations/<file>.sql` to apply to all `*.tag.ong.br` databases.
   - To target a specific database: add `--db-filter=<name>` (e.g., `--db-filter=treinamento`).
4. **Commit**: Include the migration file in your version control commit.
