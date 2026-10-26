---
trigger: always_on
---

# Legacy Routing and Assets

TAG has real legacy complexity in routing and module assets. Codex should not assume one clean global pattern.

## Routing reality

The repo uses all of these:
- `Yii::app()->createUrl('module/controller/action')`
- raw `?r=controller/action`
- hard-coded `/?r=...` links
- menu state checks based on `$_SERVER['REQUEST_URI']`

When changing routes or moving controllers:
1. Search PHP views, JS, layouts, and menus.
2. Search for both `createUrl(...)` and `?r=...`.
3. Check active-state logic in layout menus, not only links.

## Asset reality

Module asset handling is mixed:
- many modules publish `resources/` into `baseScriptUrl`
- some views still register direct resource paths
- some modules expose `baseUrl`
- some modules keep models in `app/models/` instead of local module models

When editing a module:
1. Inspect that module's `*Module.php`.
2. Reuse the local asset strategy before generalizing.
3. If a module already publishes `resources/`, prefer using that pattern consistently within the same module.

## View location reality

TAG uses Yii's theme system. Views for root controllers are resolved from `themes/` **before** `app/views/`.

View lookup order for root controllers:
1. `themes/default/views/<controller>/<view>.php` ← actual location in TAG
2. `app/views/<controller>/<view>.php` ← fallback (often empty or absent)

**Never assume a root controller's views are in `app/views/`.** Always search `themes/default/views/<controller>/` first.

When a root controller is migrated or deleted:
- Remove the corresponding `themes/default/views/<controller>/` directory to avoid orphaned view files.

## Search hotspots

When refactoring routes or assets, always check:
- `themes/default/views/<controller>/` ← views for root controllers live here
- `themes/default/views/layouts/`
- `themes/default/views/layouts/menus/`
- module `views/`
- module `resources/`
- inline JavaScript in PHP views

