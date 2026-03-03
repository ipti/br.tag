---
description: Refactor legacy UI (Bootstrap) to TAG Design System
---

Use this workflow when you encounter Bootstrap classes or outdated inline styles in PHP view files.

1. **Identify Bootstrap Classes**: Search for `btn`, `btn-primary`, `table`, `form-control`, `row`, `col-*-*`, `panel`, `alert`, `label`.
2. **Map to TAG Components**:
   - `btn btn-primary` → `t-button-primary`
   - `btn btn-success` → `t-button-success`
   - `btn btn-danger` → `t-button-danger`
   - `btn btn-default` → `t-button-default`
   - `table` → `tag-table`
   - `form-control` → `t-field-default` (verify in `sass/scss/components/`)
   - Grid: `row`/`col-*-*` → `.row` and `.column` from TAG grid (`_grid.scss`)
   - `panel` → `.t-cards`
   - `label label-*` → `.t-badge-*`
   - `modal` → TAG `_modal.scss` classes
3. **Remove Inline Styles**: Move any complex inline CSS to new components in `sass/scss/components/`.
4. **Import New Components**: Add imports to `sass/scss/main.scss` — **before** `_helpers.scss`.
5. **Build Styles**:
// turbo
   - Run `composer run sass:build` to compile your changes.
6. **Verify**: Check the UI in the browser to ensure the layout remains consistent but follows the new aesthetics.
