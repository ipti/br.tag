---
trigger: always_on
---

# E2E Testing

Browser E2E is required for UI and behavior-sensitive changes, not for every repository task.

## When this rule applies

Apply browser E2E when the change affects:
- forms
- views
- routes
- JavaScript or AJAX flows
- CSS or SASS behavior
- user-visible reporting output

It does not need to block purely documentary or internal analysis tasks.

## Before running browser tests

Codex must ask the user for:
- base URL
- credentials
- any required seed data or preconditions
- approval for the test plan

## Automation reality

- Codeception acceptance config exists in `tests/acceptance.suite.yml`.
- The default configured URL there is `http://localhost/`.
- Interactive browser validation and automated Codeception runs are related but not identical steps.
- If using the automated suite, report environment assumptions explicitly.

## Planning rule

Before executing tests, present:
- direct tests for what changed
- correlated tests for adjacent flows
- prerequisites

## Reporting rule

After E2E execution:
- state pass or fail per test
- mention any blocked scenarios
- include screenshots when the testing tool supports them
