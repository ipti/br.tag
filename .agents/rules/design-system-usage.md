---
trigger: always_on
---

# TAG Design System

Prefer the TAG design system over Bootstrap for new or refactored UI work.

## Verified usage in this repo

Common classes already present:
- `t-button-primary`
- `t-button-secondary`
- `tag-table-primary`
- `tag-table-secondary`
- `t-cards`
- `t-badge-info`
- `t-badge-success`
- `t-badge-warning`
- `t-badge-critical`

Do not invent mappings blindly. Search existing usage before choosing a replacement class.

## Build rule

After editing `.scss` files, run:
- `composer run sass:build`

## Import rule

If you add a new partial under `sass/scss/components/`, import it in `sass/scss/main.scss` before `_helpers.scss`.

## Legacy caution

Legacy Bootstrap classes still exist in some views. Treat them as refactor targets, not as preferred patterns for new work.

## Refactor behavior

When replacing Bootstrap:
1. preserve behavior first
2. map to verified TAG classes
3. move repeated inline styles into SASS when the change is more than trivial

