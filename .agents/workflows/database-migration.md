---
description: Compatibility alias for .agents/playbooks/database-migration.md
---

Use `.agents/playbooks/database-migration.md`.

Prefer:
- `composer run migrate:dry <path-to-sql> -- --dry-run`
- `composer run migrate <path-to-sql>`

Do not assume a single migration naming convention; inspect nearby files first.

