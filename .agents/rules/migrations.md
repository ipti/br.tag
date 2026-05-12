---
trigger: always_on
---

# Database Migrations

TAG uses raw SQL migrations and executes them across multiple tenant databases.

## Stable rules

- Never apply schema changes manually in production.
- Use SQL files under `app/migrations/`.
- This repo does not use PHP migration classes for normal schema work.
- `SqlMigrationCommand` is the execution path behind migration scripts.

## Important repo reality

The migration tree is mixed:
- older version folders such as `app/migrations/3.10.0/3.10.0.sql`
- named SQL files such as `app/migrations/3.13.0/v3.13.0_view_studentsfile_add_cns.sql`
- legacy files under `app/migrations/old_migrations/`

Because of that, do not assume one single historical naming convention. Follow the current release pattern already used near the target version and keep it consistent.

## Preferred commands

Use the documented Composer entrypoints when possible:
- Run: `composer run migrate <path-to-sql>`
- Dry run: `composer run migrate:dry <path-to-sql> -- --dry-run`

Direct Docker execution is acceptable when Composer scripts are insufficient, but Composer should be the default.

## Safety expectations

- Dry-run first when practical.
- Make SQL idempotent where possible.
- Avoid irreversible destructive statements unless explicitly required.
- Never use `DELETE` without a `WHERE` clause.
- For risky migrations, use transaction-based review scaffolding during development.

## Version alignment

- Check `TAG_VERSION` in `config.php`.
- Place the new migration where the current release organization expects it.
- If the repo already groups migrations by version folder for that release line, follow that pattern.

