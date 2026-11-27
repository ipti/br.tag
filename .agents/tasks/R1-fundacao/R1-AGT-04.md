---
id: R1-AGT-04
title: "Converter os playbooks e os workflows atuais em skills em .agents/skills"
release: R1
module: plataforma
type: docs
skill: tag-task-runner
agents: [tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R1-AGT-01]
size: P
risk: baixo
human_review: recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 8.2 (Fonte única: os playbooks atuais viram skills; os workflows também migram)"
write_scope:
  - .agents/skills/**
  - .agents/playbooks/**
  - .agents/workflows/**
  - .agents/README.md
  - .claude/skills/**
  - CLAUDE.md
  - AGENTS.md
read_only:
  - .agents/rules/**
  - .agents/checklists/**
  - .agents/agents/**
  - .agents/tasks/**
  - scripts/sync-agent-skills
forbidden:
  - app/**
  - themes/**
  - composer.json
  - .github/**
  - .agents/rules/**
acceptance:
  - "php scripts/sync-agent-skills --check   (sai com 0)"
  - "git grep -n 'playbooks/' -- CLAUDE.md AGENTS.md   (só ponteiros e a tabela nova)"
  - "Cada playbook e workflow antigo tem no máximo 8 linhas e aponta para a skill correspondente"
  - "Os cards existentes continuam resolvendo o campo skill (git grep -n '^skill:' -- .agents/tasks)"
---

# R1-AGT-04: Converter os playbooks e os workflows atuais em skills em .agents/skills

## Contexto
**Card do coordenador:** `CLAUDE.md` e `AGENTS.md` mudam. Só inicie depois do R1-AGT-01, que cria `.agents/skills/` e o `scripts/sync-agent-skills`. Antes de começar, confirme que as alterações pendentes de `CLAUDE.md`, `AGENTS.md` e `.agents/README.md` (que aparecem como modificadas no working tree em 2026-11-27) já estão na `main`.

Estado atual:
- **7 playbooks** em `.agents/playbooks/`: `bugfix` (20 linhas), `code-quality` (33), `database-migration` (27), `e2e-testing` (42), `module-migration` (55), `release` (24) e `ui-refactor` (26). São texto sem frontmatter.
- **6 workflows** em `.agents/workflows/`, todos "alias de compatibilidade" de 9 a 14 linhas com frontmatter `description`: `code-quality-check`, `database-migration`, `deploy` (aponta para o playbook `release`), `e2e-testing`, `module-migration` e `ui-refactor`. Eles acrescentam pequenos lembretes aos playbooks (ordem `lint → analyse → mess → test`, `migrate:dry`, "não rodar E2E sem URL, credenciais e aprovação", "não assumir `form-control -> t-field-default`").
- O plano (seção 8.2) manda os playbooks virarem skills, deixando `.agents/playbooks/` só com ponteiros; o Antigravity depreciou workflows em favor de skills, então os workflows também migram.
- Os cards já escritos usam o campo `skill` com o caminho do playbook (por exemplo `.agents/playbooks/bugfix.md` em `R0-SYSADMIN-01`, `.agents/playbooks/code-quality.md` e `.agents/playbooks/ui-refactor.md` em cards do R1). **Esses caminhos precisam continuar existindo** como ponteiros.
- `CLAUDE.md` tem a tabela "Playbooks disponíveis" (7 linhas: bugfix, qualidade, migration SQL, migração de módulo, UI/design system, release/PR e E2E) e três checklists de encerramento; `AGENTS.md` cita `.agents/playbooks/*.md` e, na ordem de precedência, "(and `.agents/skills/*` once they exist)". O `.agents/README.md` diz que os playbooks "will become skills".
- `rules/` e `checklists/` **não** são convertidos: continuam como estão.

## Objetivo
Cada playbook vira uma skill em `.agents/skills/` (com o conteúdo dos workflows correspondentes incorporado), os arquivos antigos viram ponteiros e as tabelas de `CLAUDE.md` e `AGENTS.md` apontam para as skills.

## Passos
1. Crie uma skill por playbook, em `.agents/skills/<nome>/SKILL.md`, com `name` igual à pasta e `description` curta dizendo quando usar: `tag-bugfix`, `tag-code-quality`, `tag-database-migration`, `tag-e2e-testing`, `tag-module-migration`, `tag-ui-refactor` e `tag-release`. O nome `tag-module-migration` é o do plano.
2. **Preserve o conteúdo.** Copie o texto do playbook e acrescente o que o workflow correspondente traz de diferente (o `deploy` entra em `tag-release`). Não reescreva regras nem invente comandos: se algo estiver ambíguo, mantenha o original. Acrescente a cada skill as seções que o plano pede para todas (quando usar, entradas, o que é proibido tocar, comandos de verificação) **somente** com informação que já exista nos playbooks, nas regras ou no card de referência.
3. Troque cada arquivo de `.agents/playbooks/` e de `.agents/workflows/` por um ponteiro de até 8 linhas: título, uma frase ("convertido na skill `tag-x`") e o caminho `.agents/skills/tag-x/SKILL.md`. Nos workflows, mantenha o frontmatter `description`.
4. `CLAUDE.md`: a tabela "Playbooks disponíveis" vira "Skills e playbooks", apontando para `.agents/skills/<nome>/SKILL.md` (os ponteiros continuam existindo). Atualize também `AGENTS.md` (lista inicial e precedência: skills no lugar de playbooks) e `.agents/README.md` (layout e a frase "will become skills").
5. Rode `php scripts/sync-agent-skills` para gerar `.claude/skills/` e depois `--check`.
6. Confira com `git grep` que nenhum documento mantido ainda manda "seguir o playbook" sem passar pela skill, e que os `skill:` dos cards continuam resolvendo (os ponteiros).

## Fora de escopo
- Criar as skills novas do plano (`tag-golden-master`, `tag-extract-service`, `tag-write-tests`, `tag-add-api-endpoint`, `tag-js-page-migration`, `tag-css-cleanup`, `tag-review`): ficam para os cards que dependem delas.
- Alterar `.agents/rules/`, `.agents/checklists/` ou o conteúdo dos agentes (`.agents/agents/`).
- Mudar o significado de qualquer passo: é conversão de formato, não revisão de processo.

## Critérios de aceite
- [ ] Existem as 7 skills, com `name` e `description` válidos, e o conteúdo dos playbooks e dos workflows está preservado nelas.
- [ ] Os 7 playbooks e os 6 workflows antigos viraram ponteiros curtos e continuam existindo.
- [ ] `CLAUDE.md`, `AGENTS.md` e `.agents/README.md` refletem as skills; as `@`-importações de `.agents/rules/` do `CLAUDE.md` seguem intactas.
- [ ] `php scripts/sync-agent-skills --check` sai com 0 e `.claude/skills/` tem as cópias.
- [ ] **O sistema não muda:** nenhum arquivo de `app/`, `themes/` ou configuração foi alterado.

## Como verificar
```bash
php scripts/sync-agent-skills
php scripts/sync-agent-skills --check; echo "saída: $?"      # esperado: 0
wc -l .agents/playbooks/*.md .agents/workflows/*.md           # esperado: cada um com poucas linhas
ls .agents/skills .claude/skills
git grep -n "playbooks/" -- CLAUDE.md AGENTS.md .agents/README.md
git grep -n "^skill:" -- .agents/tasks | cut -d: -f4- | sort -u   # todos os caminhos citados existem
```
Conferência humana: abra duas skills lado a lado com o playbook original no `git show HEAD:...` e confirme que nada foi perdido.

## Handoff
_Preenchido por quem executar._
