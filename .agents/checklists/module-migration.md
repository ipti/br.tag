# Checklist: Module Migration

- The module is registered in `app/config/main.php`.
- Views were located in `themes/default/views/<controller>/` before assuming `app/views/` (Yii theme system).
- Views were moved to `app/modules/<module>/views/<controller>/` or recreated if theme overrides were needed.
- The `themes/default/views/<controller>/` directory was deleted after the move (no orphaned files).
- Controller routes were updated across PHP, JS, and menu/layout files.
- `?r=` references and `createUrl(...)` references were both searched.
- `$_SERVER['REQUEST_URI']` active-state checks were reviewed where relevant.
- Asset registration follows the local module pattern instead of a guessed standard.
- Shared models were not moved unless usage proved they were module-specific.
- Direct flow and adjacent navigation were reviewed after the move.

