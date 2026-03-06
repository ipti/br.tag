---
trigger: always_on
---

# TAG Design System

Always use the TAG Design System from the `sass/scss/` folder. Never use Bootstrap classes in new code.

## Build Command
- After changing any `.scss` file: `composer run sass:build`
- This runs: `docker compose run --rm --entrypoint /opt/dart-sass/sass sass /sass/main.scss:/css/main.css --no-source-map --style=compressed`

## Available Components
Base partials in `sass/scss/`:
- Layout: `_grid`, `_base`, `_responsive`
- UI: `_button`, `_table`, `_cards`, `_tabs`, `_accordion`, `_accordeon`, `_modal`, `_badge`, `_drawer`, `_menu`, `_topbar`, `_sidebar`, `_lists`, `_separator`, `_sortable`, `_tag`, `_expansive-panel`, `_filter_bar`
- Typography: `_typography`, `_icons`
- Pages: `_login`, `_reports`
- Design tokens: `_tokens`, `_functions`, `_helpers`

Field components in `sass/scss/components/`:
- `field-text`, `field-file`, `field-select2`, `field-tarea`, `field-checkbox`, `field-number`, `field-multiselect`, `field-section`, `field-radio`, `stat-card`

## Adding New Components
1. Create a new `_component-name.scss` partial in `sass/scss/components/`.
2. Import it in `sass/scss/main.scss` — **before** `_helpers.scss` (last import).
3. Run `composer run sass:build`.