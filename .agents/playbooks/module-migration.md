# Playbook: Legacy to Module Migration

Use this when moving a legacy root feature into `app/modules/<module_name>/`.

## Scope rule

- Migrate one controller flow at a time.
- Do not migrate critical shared controllers casually.

## Steps

1. Map the current controller, views, models, JS, and CSS involved.
2. Create the module structure.
3. Register the module in `app/config/main.php`.
4. Decide whether models stay shared in `app/models/` or move into the module based on actual reuse.
5. Inspect a nearby mature module and match its asset publication pattern.
6. Update view paths and asset registration.
7. Search the repo for old routes, menu state checks, and AJAX URLs.
8. Verify no orphan route references remain.

## Search expectations

Search for:
- `?r=<controller>/`
- `['<controller>/action']`
- JS AJAX calls
- menu and breadcrumb links
- `$_SERVER['REQUEST_URI']` active-state checks

## Verify

- affected routes load
- moved assets still resolve
- AJAX flows still work
- old references are removed or intentionally preserved
- layout and menu active states still behave correctly
