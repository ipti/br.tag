---
id: R4-REFINE-01
title: "Refinar a onda 1: quebrar os épicos R4-CLASSDIARY, R4-STUDENT e R4-CLASSROOM em cards executáveis"
release: R4
module: plataforma
type: refinamento
skill: tag-task-runner
agents: [tag-plan-guardian, tag-qa]
status: pronto
depends_on: [R1-AGT-03, R3-FRONT-04]
size: P
risk: baixo
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 8.3 (cards gerados a partir de inventário) e seção 8.6 (ondas de pacotes por módulo)"
write_scope:
  - .agents/tasks/R4-onda-1/**
  - .agents/tasks/README.md
read_only:
  - scripts/inventory-actions.php
  - app/modules/classdiary/**
  - app/modules/student/**
  - app/modules/classroom/**
  - js/classroom/**
  - js/enrollment/**
  - docs/arquitetura/plano-modernizacao.md
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "Cada épico da onda 1 tem os cards filhos com status pronto (ou bloqueado, com o motivo) e depends_on coerente"
  - "O índice por módulo do .agents/tasks/README.md lista os novos IDs"
  - "Uma pessoa revisou e aprovou a quebra antes de qualquer card filho ser iniciado"
---

# R4-REFINE-01: Refinar a onda 1: quebrar os épicos R4-CLASSDIARY, R4-STUDENT e R4-CLASSROOM em cards executáveis

## Contexto
Os épicos da onda 1 nasceram como `rascunho` ([R4-CLASSDIARY](R4-CLASSDIARY.md), [R4-STUDENT](R4-STUDENT.md) e [R4-CLASSROOM](R4-CLASSROOM.md)) com o inventário de 2026-11-27 do README. Até a onda começar, o código terá mudado: o R2 e o R3 mexem em `grades`, `foods` e na infraestrutura, e o próprio `classdiary` já ganhou o `EntryLockController` na branch de bloqueio de lançamentos.

O plano (seção 8.3) manda gerar os cards de cada onda a partir de `scripts/inventory-actions.php` (card R1-AGT-03). O script emite um JSON por controller/action com superglobais, SQL cru, saída JSON, linhas de código e controle de acesso. Na data deste card o arquivo **ainda não existe** em `scripts/` (só há `generate-routes.php`); confirmar que o R1-AGT-03 o entregou.

O R3-FRONT-04 (ponte legada) é pré-requisito porque os cards de JS dos épicos pressupõem `frontend/pages/<módulo>/`, `onPage`/`registerEntry` e a ponte que substitui `common.js` e `util.js`.

Este card não escreve código de produção. A saída é a quebra dos épicos em cards no formato do [`_TEMPLATE.md`](../_TEMPLATE.md), no rigor do [R0-SYSADMIN-01](../R0-seguranca/R0-SYSADMIN-01.md).

## Objetivo
Cada um dos três épicos da onda 1 vira uma lista de cards pequenos, com `write_scope` que não se cruzam, dependências ordenadas e critérios de aceite verificáveis. Uma pessoa aprova a quebra.

## Passos
1. Confirme que as dependências estão na `main` e que `scripts/inventory-actions.php` roda. Rode-o para `classdiary`, `student` e `classroom` e guarde os JSONs no destino que o R1-AGT-03 documentou.
2. Compare o JSON com o inventário do README e com o "Contexto" de cada épico. Registre no próprio épico o que mudou (controllers novos, actions movidas, módulos já tocados por outros cards).
3. Para cada épico, quebre os pacotes na ordem SEC → GM → SVC → API → JS → CSS (seção 8.6):
   - **SEC:** um card por controller, com a lista de actions e o `accessRules` alvo.
   - **GM:** um card por fluxo crítico, com o dataset e o que o snapshot grava.
   - **SVC-n:** um card por grupo de actions, com o contexto em `app/src/<Contexto>` e as classes de saída.
   - **JS-n:** um card por página, com os globais listados pelo script `legacy:who-uses`.
   - **CSS:** um card por parcial (`sass/scss/pages/_<módulo>.scss`).
4. Nomeie os cards `R4-<MODULO>-<nn>` (`R4-CLASSDIARY-01`, `R4-STUDENT-03` etc.). Preencha todos os campos do template, com `status: pronto` quando as dependências permitirem.
5. Garanta que dois cards do mesmo módulo só rodam em paralelo se os `write_scope` não se cruzarem (seção 8.4). Cards que dependem dos arquivos com dono (`composer.json`, `app/config/main.php`, layouts, `sass/scss/main.scss`, `app/models/**`) abrem um card de contrato separado.
6. Marque `human_review: obrigatoria` nos cards de notas, frequência, aprovação, matrícula, migrations e segurança.
7. Atualize o índice por módulo do `.agents/tasks/README.md` com os novos IDs. Os três épicos passam a `status: pronto` quando todos os filhos existirem; a execução continua nos filhos.
8. Peça a revisão de uma pessoa (e de um guardião em outro provedor, se possível) e registre o resultado no Handoff.

## Fora de escopo
- Executar qualquer card filho.
- Refinar os épicos da onda 2 (card R5-REFINE-01).
- Alterar código de produção, testes ou configuração.
- Reordenar as ondas do plano.

## Critérios de aceite
- [ ] Os três épicos têm cards filhos para os pacotes SEC, GM, SVC, JS e CSS (e API apenas onde o app precisa).
- [ ] Todo card filho segue o `_TEMPLATE.md`, com YAML válido e `write_scope`, `forbidden` e `acceptance` preenchidos.
- [ ] Nenhum par de cards do mesmo módulo com `write_scope` cruzado está marcado para rodar em paralelo.
- [ ] O índice do `.agents/tasks/README.md` lista os novos IDs por módulo.
- [ ] As divergências entre o inventário do README e o JSON atual estão registradas nos épicos.
- [ ] Uma pessoa aprovou a quebra (registrado no Handoff).

## Como verificar
```bash
php scripts/inventory-actions.php --help     # confirma que o script existe e como filtrar por módulo
ls .agents/tasks/R4-onda-1/                  # esperado: os épicos, este card e os filhos criados
git grep -n "^status:" -- .agents/tasks/R4-onda-1   # esperado: filhos em pronto ou bloqueado
```
Confira à mão que os IDs citados em `depends_on` existem como arquivos em `.agents/tasks/`.

## Handoff
_Preenchido por quem executar._
