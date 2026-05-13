# Checklist: Module Migration

- The module is registered in `app/config/main.php`.
- Controller routes were updated across PHP, JS, and menu/layout files.
- `?r=` references and `createUrl(...)` references were both searched.
- `$_SERVER['REQUEST_URI']` active-state checks were reviewed where relevant.
- Asset registration follows the local module pattern instead of a guessed standard.
- Shared models were not moved unless usage proved they were module-specific.
- Direct flow and adjacent navigation were reviewed after the move.

