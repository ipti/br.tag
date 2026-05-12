# Playbook: UI Refactor to TAG Design System

Use this when touching legacy Bootstrap-heavy views or repeated inline styles.

## Steps

1. Search the target view for Bootstrap classes and inline styles.
2. Look for existing TAG class usage in nearby modules or views.
3. Search whether the page also depends on Bootstrap-like table or grid JS behavior before changing markup classes.
4. Replace with verified TAG classes instead of guessed mappings.
5. If styles repeat or are non-trivial, move them into SASS.
6. If SASS changed, run `composer run sass:build`.

## Verified class families

- Buttons: `t-button-*`
- Tables: `tag-table-*`
- Cards: `t-cards`
- Badges: `t-badge-*`

## Avoid

- introducing new Bootstrap classes
- assuming a field class exists without searching the repo first
- leaving large inline style blocks in views after a refactor
- breaking JS initializers that depend on specific table or selector classes such as `js-tag-table`
