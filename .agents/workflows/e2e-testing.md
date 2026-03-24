---
description: Run E2E tests using the built-in browser to validate changes
---

Use this workflow after making code changes to validate them in the browser. **Always present the test plan to the user and ask for approval before executing.**

## Steps

### 1. Identify What to Test
Based on the changes made, list:
- **Direct tests**: the exact feature/page that was changed
- **Correlated tests**: other pages or features that depend on or link to the changed code

### 2. Build the Test Plan
Present to the user:
```
### Plano de Testes E2E

**Alterações feitas**: [resumo]

**Testes diretos**:
1. [ ] [Descrição] — URL: [rota]

**Testes correlatos**:
1. [ ] [Descrição] — URL: [rota]

**Pré-requisitos**: [URL base, credenciais, dados de teste]
```

### 3. Ask the User
**NEVER proceed without asking**:
- What is the base URL? (e.g., `http://localhost`)
- What credentials to use?
- Any specific data or preconditions needed?
- Does the test plan look correct?

### 4. Execute Tests in Browser
For each test case:
1. Navigate to the page using `browser_subagent`
2. Perform the interactions (click, type, submit)
3. Verify the expected outcome (text present, element visible, redirect correct)
4. Take a screenshot as evidence
5. Mark pass ✅ or fail ❌

### 5. Report Results
Present a summary:
```
### Resultados E2E

| # | Teste | Resultado | Observação |
|---|-------|-----------|------------|
| 1 | [descrição] | ✅ Pass | — |
| 2 | [descrição] | ❌ Fail | [detalhe do erro] |
```

If any test fails, fix the issue and re-run only the failed tests.
