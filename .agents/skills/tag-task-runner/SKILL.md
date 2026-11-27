---
name: tag-task-runner
description: >-
  Executa ou pré-audita cards de .agents/tasks/ no loop QA -> Refatorador -> QA -> Guardião
  (máximo de 3 tentativas), respeitando dependências, write_scope, gates e revisão humana.
  Use quando o usuário pedir para rodar, executar ou auditar um ou mais cards do Plano de Modernização.
---

# TAG Task Runner (`tag-task-runner`)

Skill neutra de provedor (Antigravity, Claude Code e Codex) para conduzir cards de `.agents/tasks/` em loop controlado até ficarem prontos para revisão humana e merge.

## Fontes de verdade (nesta ordem)
1. `AGENTS.md`
2. `.agents/rules/*.md`
3. `docs/arquitetura/plano-modernizacao.md`
4. O card em `.agents/tasks/<lançamento>/<ID>.md`
5. As skills em `.agents/skills/` (ou `.agents/playbooks/` enquanto a skill do card não existir) e os ADRs em `docs/adrs/`

## Entradas esperadas
- **Alvo:** um ou mais IDs de card (ex.: `R0-SQL-01`, `R0-SQL-03`) ou um lançamento (ex.: `R0`, limitando a 1–3 cards por rodada).
- **Modo:**
  - **Execução** *(padrão)*: implementa o card em branch própria e executa o loop completo.
  - **Pré-auditoria (`dry-run`)**: somente leitura; valida prontidão do card sem editar arquivos.
- **Teto do loop:** `3` tentativas por card (salvo se o usuário pedir outro limite).

## Como delegar os papéis em cada provedor

| Papel | Antigravity | Claude Code | Codex |
| :--- | :--- | :--- | :--- |
| **QA** (só testes e gates) | `tag-qa` | `tag-qa` | `tag_qa` |
| **Refatorador** (implementa no `write_scope`) | `tag-refactorer` | `tag-refactorer` | `tag_refactorer` |
| **Guardião** (só lê e valida conformidade) | `tag-plan-guardian` | `tag-plan-guardian` | `tag_plan_guardian` |

> Se a sessão atual não puder invocar subagentes, execute as mesmas etapas em sequência assumindo explicitamente o checklist de cada papel em `.agents/agents/`. Quando possível, faça a revisão final do Guardião em outro provedor (revisão cruzada).

---

## Passo 1 — Leitura e pré-condições (obrigatório antes de tocar em código)
Para cada card solicitado:
1. Leia o card inteiro em `.agents/tasks/`, as seções indicadas em `plan_ref` e as regras aplicáveis em `.agents/rules/`.
2. Verifique:
   - `status` é `pronto`;
   - todas as `depends_on` estão com `status: concluido` e já integradas na `main`;
   - se houver vários cards na mesma rodada, os `write_scope` não se cruzam (se cruzarem, execute em sequência, nunca em paralelo);
   - se for `type: refactor` em regra crítica (notas, frequência, aprovação, matrícula, exportações), existe golden master ou teste de caracterização antes de alterar produção.
3. Se qualquer pré-condição falhar, **pare** aquele card e informe o motivo ao usuário.

### Se o modo for Pré-auditoria (`dry-run`)
1. Delegue em paralelo (somente leitura) ao **QA** e ao **Guardião** para conferir se arquivos/linhas do card batem com o código atual, se o `write_scope` respeita arquivos com dono e se há decisões humanas prévias.
2. Não edite nenhum arquivo. Entregue um resumo com veredito por card (`PRONTO`, `COM RESSALVAS` ou `REPROVADO`) e pare.

---

## Passo 2 — Preparação (modo execução)
1. Crie a branch `task/<ID>` a partir da `main` atualizada (ou uma worktree isolada se rodar mais de um card em paralelo).
2. Mude o frontmatter `status` do card para `em-andamento`.
3. Todo commit deve levar o ID do card no início da mensagem (ex.: `R0-SQL-01: usa bind nos gráficos do SiteController`).

---

## Passo 3 — Loop de execução (máximo de 3 tentativas)

### 3.1 Pré-check / Caracterização (antes da tentativa 1)
- Acione o **QA** para validar pré-condições e criar golden master ou teste de caracterização quando o card exigir (sem alterar código de produção).
- Se o QA apontar bloqueio prévio, pare e escale para o usuário.

### 3.2 Ciclo `Refatorador → QA → Guardião` (repetir até `tentativa = 3`)
1. **Implementar / corrigir (`Refatorador`):**
   - Na tentativa 1: execute os passos do card seguindo a skill/playbook indicado.
   - Nas tentativas 2–3: corrija exatamente os achados listados pelo QA e pelo Guardião na rodada anterior.
   - Altere **somente** arquivos listados em `write_scope`. Nunca toque em `forbidden` nem em arquivos com dono (`composer.json`, `app/config/main.php`, `themes/default/views/layouts/`, `sass/scss/main.scss`, `.github/workflows/`, `app/models/`), exceto quando o card declarar explicitamente ser um **card do coordenador** com esses arquivos em `write_scope`.
2. **Testar e rodar gates (`QA`):**
   - Escreva/atualize os testes pedidos pelo card.
   - Rode cada comando listado em `acceptance` e `scripts/check-task-scope` (quando existir).
   - O que não puder rodar no ambiente atual deve ser reportado explicitamente como **não verificado** (nunca como aprovado).
3. **Revisar conformidade (`Guardião`):**
   - Revisa o diff contra o card, as regras de `.agents/rules/` e `docs/arquitetura/plano-modernizacao.md`.
   - Devolve veredito: `APROVADO`, `APROVADO COM RESSALVAS` ou `REPROVADO`, com lista de achados bloqueantes, importantes e decisões humanas.
4. **Decisão ao fim da rodada:**
   - **QA com gates verdes + Guardião `APROVADO`** → encerre o loop e siga para o **Passo 4**.
   - **Há achados corrigíveis dentro do escopo e `tentativa < 3`** → volte ao item 1 com os achados.
   - **Atingiu 3 tentativas sem aprovação**, **divergência de golden master**, **necessidade de tocar arquivo fora do escopo/com dono**, ou **decisão humana obrigatória** → **pare o loop imediatamente**, registre no Handoff e reporte ao usuário.

---

## Passo 4 — Fechamento e entrega para revisão humana
1. Preencha a seção `## Handoff` do card:
   - **Feito:** resumo objetivo do que mudou;
   - **Decisões:** escolhas feitas e justificativa;
   - **Gates:** tabela `comando → resultado` (incluindo o que não pôde ser verificado);
   - **Pendências e issues sugeridas:** bugs alheios encontrados ou cards de contrato necessários;
   - **Riscos:** pontos de atenção para a revisão.
2. Atualize o frontmatter `status` do card para `em-revisao` (ou `bloqueado`, se o loop parou por impedimento).
3. Apresente ao usuário a descrição pronta do PR contendo:
   - `<ID>: <título do card>`
   - Link para o card e seção do plano (`plan_ref`)
   - Resumo das mudanças e tabela de gates
   - Riscos e rollback
   - Indicação clara se `human_review` é `obrigatoria` ou `recomendada`

---

## Regras inegociáveis (proibições)
- **Não mudar regra de negócio** por conta própria; se o golden master divergir, pare e registre.
- **Não corrigir bugs fora do escopo em silêncio**; registre como issue sugerida no Handoff.
- **Não enfraquecer testes, baselines ou catracas** nem usar `@phpstan-ignore` sem justificativa.
- **Não rodar testes de navegador** sem pedir URL base, credenciais e aprovação antes (`.agents/rules/e2e-testing.md`).
- **Não fazer merge automático**; o merge depende sempre de aprovação humana.
