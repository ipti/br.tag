---
trigger: always_on
---

# E2E Testing

All code changes must be validated with E2E tests using the **built-in browser** before being considered complete.

## Rules

1. **Always create a test plan FIRST** — before executing any test, present the plan to the user and ask for approval.
2. **Test what changed AND what is related** — if you changed a form, test that form AND the listing page, detail view, and any other page that links to it.
3. **Always ask the user** — before starting E2E tests, ask the user for the URL and credentials to use. Never assume.
4. **Record results** — document pass/fail for each test case with screenshots when relevant.

## Test Plan Format

Before testing, present this to the user:

```
### Plano de Testes E2E

**Alterações feitas**: [resumo das alterações]

**Testes diretos** (o que foi alterado):
1. [ ] [Descrição do teste] — URL: [rota]
2. [ ] [Descrição do teste] — URL: [rota]

**Testes correlatos** (elementos que dependem do que foi alterado):
1. [ ] [Descrição do teste] — URL: [rota]
2. [ ] [Descrição do teste] — URL: [rota]

**Pré-requisitos**: [credenciais, dados necessários]
```

## What to Test

| Tipo de alteração | Testes diretos | Testes correlatos |
|-------------------|----------------|-------------------|
| Model alterado | CRUD do model | Relatórios, views que listam esse model |
| Controller/rota alterada | Todas as actions do controller | Links/menus que apontam para esse controller |
| View/formulário alterado | Submissão, validações, layout | Listagem, detalhes, impressão |
| Migration (nova coluna) | Formulário que usa a coluna | Listagens, relatórios, exports |
| CSS/SASS alterado | Páginas que usam os componentes | Responsividade, impressão |
| AJAX alterado | Interação AJAX específica | Fluxo completo da feature |
