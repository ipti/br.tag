# Playbook: Legacy to Module Migration

Use this when moving a legacy root feature into `app/modules/<module_name>/`.

## Scope rule

- Migrate one controller flow at a time.
- Do not migrate critical shared controllers casually.

## Steps

1. Map the current controller, views, models, JS, and CSS involved.
2. **Locate views before assuming their path.** In TAG, Yii's theme system means views for root controllers live in `themes/default/views/<controller>/`, not in `app/views/<controller>/`. Always search `themes/` first.
3. Create the module structure.
4. Register the module in `app/config/main.php`.
5. Decide whether models stay shared in `app/models/` or move into the module based on actual reuse.
6. Inspect a nearby mature module and match its asset publication pattern.
7. Update view paths and asset registration.
8. Search the repo for old routes, menu state checks, and AJAX URLs.
9. Verify no orphan route references remain — including orphaned view files left in `themes/default/views/<controller>/` after the controller is removed.

## View lookup order (Yii 1.1 + active theme)

For **root controllers**, Yii resolves views in this order:
1. `themes/default/views/<controller>/<view>.php` ← **check here first**
2. `app/views/<controller>/<view>.php`

For **module controllers**, Yii resolves views in this order:
1. `themes/default/views/<module>/<controller>/<view>.php`
2. `app/modules/<module>/views/<controller>/<view>.php` ← usually lives here

When migrating a root controller to a module, views found in `themes/default/views/<controller>/` must be either:
- moved into `app/modules/<module>/views/<controller>/` (preferred), or
- recreated under `themes/default/views/<module>/<controller>/` if theme overrides are needed.

After the move, delete the old `themes/default/views/<controller>/` directory to avoid orphaned files.

## Search expectations

Search for:
- `?r=<controller>/`
- `['<controller>/action']`
- JS AJAX calls
- menu and breadcrumb links
- `$_SERVER['REQUEST_URI']` active-state checks
- view files in `themes/default/views/<controller>/` (legacy theme views)

## Verify

- affected routes load
- moved assets still resolve
- AJAX flows still work
- old references are removed or intentionally preserved
- layout and menu active states still behave correctly
- no orphaned directories remain in `themes/default/views/`
