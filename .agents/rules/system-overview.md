---
trigger: always_on
---

# TAG System Overview

TAG (Tecnologia de Apoio a Gestao) is a school management system for Brazilian municipal education networks.

## Verified stack

- Backend: PHP 8.3, Yii 1.1, MySQL 8.0
- Frontend: server-rendered PHP, jQuery, SASS design system in `sass/scss/`
- Infra: Docker containers including `tag-app`

## Architectural guidance

- Legacy code still exists in `app/controllers/`, `app/models/`, and `themes/default/views/`.
- New features should be implemented as Yii modules under `app/modules/<module_name>/`.
- Shared root models in `app/models/` are still heavily used even by modules.
- Shared business rules should stay in models or services, not in views or controllers.
- Root controllers are legacy territory. Avoid adding new root controllers unless a repo-specific constraint truly requires it.

## Current module landscape

The repo already uses modules such as:
- `student`
- `school`
- `notifications`
- `inventory`
- `foods`
- `classdiary`
- `courseplan`
- `stages`
- `professional`
- `schoolreport`

When extending an existing domain, prefer updating the relevant module instead of creating parallel structures.

## Module reality

Modules are not fully uniform:
- some modules import local models and components
- some modules intentionally keep shared models in root `app/models/`
- some modules publish `resources/` to `baseScriptUrl`
- some views still register direct asset paths

Codex should follow the nearest module's local pattern instead of forcing a synthetic standard during unrelated work.

## UI guidance

- TAG has its own design system.
- Common classes verified in the repo include `t-button-primary`, `tag-table-primary`, `tag-table-secondary`, `t-cards`, `t-badge-info`, `t-badge-success`, `t-badge-warning`, and `t-badge-critical`.
- Do not assume Bootstrap is the target design language even if legacy Bootstrap classes still appear in views.

## Documentation rule

Document only what is active and verifiable.

Before claiming a feature exists:
1. Search for code references.
2. Confirm the route, model, or integration is actually used.
3. If it exists only as dormant infrastructure, describe it as available but not active.

## Documentation split

When documenting modules, keep both perspectives:
- Functional/domain view for school staff and education managers
- Technical view for developers, QA, and ops
