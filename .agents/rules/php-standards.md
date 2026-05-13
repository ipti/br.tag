---
trigger: always_on
---

# PHP Standards and Quality

Use repo-verified commands and Yii 1.1 patterns that match TAG's current architecture.

## Preferred verification commands

Prefer Composer scripts when they exist:
- Format check: `composer run lint`
- Auto-fix formatting: `composer run fix`
- Static analysis: `composer run analyse`
- PHPMD: `composer run mess`
- Acceptance tests: `composer run test`

These scripts already encode the expected Docker container usage.

## Test command reality

- `composer run test` maps to `./vendor/bin/codecept run acceptance --steps`.
- Acceptance tests rely on Codeception WebDriver and the suite currently points to `http://localhost/`.
- Do not promise this command will run successfully unless the local browser/WebDriver setup exists.

## PHP and Yii conventions

- Runtime target is PHP 8.3, but coding style must stay compatible with the repo's Yii 1.1 architecture.
- Keep controllers thin.
- Put business rules in models, services, or domain-oriented classes.
- Views should render data, not query or decide business rules.
- Use parameter binding in SQL and criteria objects where feasible.
- Reuse existing helpers and widget patterns before inventing parallel abstractions.

## New feature placement

- New features belong in `app/modules/<module_name>/`.
- Avoid adding new root controllers in `app/controllers/`.
- If a model is shared broadly across modules, keep it where current architecture expects it.
- Do not move shared root models into a module just to satisfy an abstract purity rule.

## Change discipline

Before introducing a new pattern:
1. Search the module or nearby domain for an existing implementation.
2. Match local conventions first.
3. Only generalize after verifying repeated usage.

## Definition of done for PHP work

For meaningful PHP changes, Codex should try to leave the branch in a state where:
- formatting is valid
- static analysis is considered
- affected flows were reviewed or tested

If a check could not be run, say so explicitly in the final report.
