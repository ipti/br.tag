# Playbook: Database Migration

Use this when schema or seed-like SQL changes are required.

## Steps

1. Check `TAG_VERSION` in `config.php`.
2. Inspect nearby files in `app/migrations/` and match the active naming/layout pattern.
3. Create or update the SQL migration file.
4. Prefer idempotent SQL when possible.
5. Run a dry-run path first when practical:
   - `composer run migrate:dry <path-to-sql> -- --dry-run`
6. If execution is required and approved:
   - `composer run migrate <path-to-sql>`

## Verify

- Confirm the file path is correct for the repo's current release organization.
- Mention whether dry-run was executed.
- Mention whether actual execution was intentionally skipped.
- If the migration affects UI forms or reports, point to the follow-up test surfaces that should validate the new column or table.

## Avoid

- inventing a migration naming convention without checking nearby files
- manual production SQL outside the migration path
- unsafe deletes without filters
