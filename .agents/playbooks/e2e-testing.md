# Playbook: E2E Testing

Use this when a change affects user-visible behavior.

## Ask first

Before execution, ask for:
- base URL
- credentials
- test data or preconditions
- approval of the test plan

If the user gives no better environment hint, note that the automated acceptance suite in the repo is configured for `http://localhost/`.

## Test plan template

```md
### Plano de Testes E2E

**Alteracoes feitas**: [resumo]

**Testes diretos**:
1. [ ] [descricao] - URL: [rota]

**Testes correlatos**:
1. [ ] [descricao] - URL: [rota]

**Pre-requisitos**: [URL base, credenciais, dados]
```

## What to include

- direct flow changed by the task
- nearby listing/detail/report flows
- key regressions for AJAX, validation, and navigation

## Report format

- state each test as pass or fail
- include failure notes
- mention blocked scenarios explicitly
- distinguish between interactive browser validation and automated Codeception execution when both are relevant
