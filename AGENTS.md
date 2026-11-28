# TAG Codex Guide

This repository keeps project-specific guidance in `.agents/`.

Start here:
- Read `.agents/README.md`.
- Treat `.agents/rules/*.md` as stable constraints.
- Treat `docs/arquitetura/plano-modernizacao.md` as the approved modernization plan (it supersedes `docs/namespaces.md` and `docs/php83_yii1_static_analysis_plan.md`).
- Treat `.agents/tasks/` as the backlog: one card = one branch `task/<ID>`; respect the card's `write_scope`, `forbidden` and `acceptance`.
- Treat `.agents/playbooks/*.md` as task-specific execution guides.
- Treat `.agents/checklists/*.md` as completion criteria.

## Agentes especialistas (plano de modernização)

O texto completo de cada papel fica em `.agents/agents/`, que é a fonte única. Codex e Claude Code têm adaptadores próprios; o Antigravity lê `.agents/agents/` diretamente.

| Papel | Codex (`.codex/agents/`) | Antigravity (`.agents/agents/`) | Claude Code (`.claude/agents/`) |
|---|---|---|---|
| Guardião do plano: revisa a conformidade com o plano e só lê | `tag_plan_guardian` | `tag-plan-guardian` | `tag-plan-guardian` |
| Refatorador: executa o card sem sair do `write_scope` | `tag_refactorer` | `tag-refactorer` | `tag-refactorer` |
| QA: golden master, testes e gates; não toca em código de produção | `tag_qa` | `tag-qa` | `tag-qa` |

- **Fluxo de um card:**
  1. `tag_qa` faz a caracterização (golden master);
  2. `tag_refactorer` implementa;
  3. `tag_qa` escreve os testes novos e roda os gates;
  4. `tag_plan_guardian` revisa, de preferência num provedor diferente do refatorador;
  5. uma pessoa aprova o merge.
- **No Codex:** quando o pedido for executar, testar ou revisar um card, delegue explicitamente ao agente do papel. Exemplo: "spawn the tag_refactorer agent for card R0-SQL-03".

Operating rules for Codex in this repo:
- Always inspect existing code before proposing architecture changes.
- Prefer existing Composer scripts over ad-hoc Docker commands when a script already exists.
- New features belong in `app/modules/<module_name>/`, not in root `app/controllers/`.
- Match the nearest existing module pattern before introducing a new one.
- Keep controllers thin; place business rules in models or services.
- Never describe a feature as active unless code references confirm it is actually used.
- For browser E2E, ask for URL, credentials, and approval before executing tests.
- After editing `.scss`, run `composer run sass:build`.
- For PHP changes, prefer this verification order when relevant:
  1. `composer run lint`
  2. `composer run analyse`
  3. `composer run mess`
  4. `composer run test`

Command convention:
- This repo uses `rtk` as a shell proxy. Prefix shell commands with `rtk` when possible.

Legacy reality:
- Routing is mixed between `Yii::app()->createUrl(...)`, raw `?r=...`, and `$_SERVER['REQUEST_URI']` checks in menus/layouts.
- Module assets are also mixed: some modules use `baseScriptUrl`, some use direct resource paths, and some keep shared models in `app/models/`.
- Because of that, search adjacent code before normalizing patterns.

When instructions conflict:
1. Root `AGENTS.md`
2. `.agents/rules/*`
3. `docs/arquitetura/plano-modernizacao.md`
4. The task card in `.agents/tasks/`
5. `.agents/playbooks/*` (and `.agents/skills/*` once they exist)
6. `.agents/checklists/*`

<!-- CODEGRAPH_START -->
## CodeGraph

In repositories indexed by CodeGraph (a `.codegraph/` directory exists at the repo root), reach for it BEFORE grep/find or reading files when you need to understand or locate code:

- **MCP tool** (when available): `codegraph_explore` answers most code questions in one call — the relevant symbols' verbatim source plus the call paths between them, including dynamic-dispatch hops grep can't follow. Name a file or symbol in the query to read its current line-numbered source. If it's listed but deferred, load it by name via tool search.
- **Shell** (always works): `codegraph explore "<symbol names or question>"` prints the same output.

If there is no `.codegraph/` directory, skip CodeGraph entirely — indexing is the user's decision.
<!-- CODEGRAPH_END -->
