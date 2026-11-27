---
id: R1-AGT-01
title: "Criar as skills tag-task-runner e tag-secure-controller e o script sync-agent-skills"
release: R1
module: plataforma
type: docs
skill: tag-task-runner
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: []
size: P
risk: baixo
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 8.2 (skills, formato e onde moram) e seção 8.3 (card de tarefa)"
write_scope:
  - .agents/skills/**
  - .claude/skills/**
  - scripts/sync-agent-skills
  - composer.json
  - .github/workflows/agents.yml
read_only:
  - docs/arquitetura/plano-modernizacao.md
  - .agents/tasks/README.md
  - .agents/tasks/_TEMPLATE.md
  - .agents/tasks/R0-seguranca/R0-SYSADMIN-01.md
  - .agents/agents/**
  - .agents/rules/**
  - app/components/utils/TagUtils.php
forbidden:
  - app/**
  - themes/**
  - .agents/playbooks/**
  - .agents/workflows/**
  - CLAUDE.md
  - AGENTS.md
acceptance:
  - "php scripts/sync-agent-skills"
  - "php scripts/sync-agent-skills --check   (sai com 0)"
  - "Alterar uma cópia em .claude/skills e rodar --check: sai com 1 e lista o arquivo divergente"
  - "composer agents:sync"
  - "docker run --rm -v \"$PWD:/repo\" -w /repo rhysd/actionlint:latest   (agents.yml sem erro)"
---

# R1-AGT-01: Criar as skills tag-task-runner e tag-secure-controller e o script sync-agent-skills

## Contexto
**Card do coordenador:** edita `composer.json` e cria um workflow em `.github/workflows/` (arquivos com dono).

O que existe hoje:
- `.agents/` tem `agents/`, `checklists/`, `playbooks/`, `rules/`, `tasks/` e `workflows/`. **Não existe** `.agents/skills/`; o `.agents/README.md` (≈L13) já anuncia a pasta "from card R1-AGT-01". O `.claude/` só tem `agents/`, `settings.json` e `settings.local.json`.
- O plano (seção 8.2) define o formato: `SKILL.md` com frontmatter `name` e `description`, na fonte única `.agents/skills/<skill>/SKILL.md`. Codex e Antigravity leem essa pasta; o **Claude Code não lê** e precisa de cópias em `.claude/skills/` (cópias, não symlinks, porque symlink no git do Windows é instável). Um job do CI falha se as cópias divergirem. Os caminhos mudam com frequência: conferir na documentação de cada ferramenta.
- O `.gitattributes` força `eol=lf` (`* text=auto eol=lf`), então a comparação de cópias pode ser por conteúdo exato.
- Convenções já escritas: o card de referência é `R0-seguranca/R0-SYSADMIN-01.md`; o modelo é `_TEMPLATE.md`; as regras ficam em `.agents/rules/`; o `AGENTS.md` define a ordem de precedência. Os scripts de apoio do repositório rodam em PHP no container (`routes:generate`, `scripts/generate-routes.php`).
- Controle de acesso no código: os helpers `TagUtils::isAdmin()` e `TagUtils::isSuperuser()` consultam o `authManager` pelo id numérico; `Yii::app()->user->checkAccess()` sempre retorna falso hoje (plano, seção 2, achado 6).

## Objetivo
Duas skills no padrão Agent Skills, um script que as copia para `.claude/skills/` (com modo `--check`) e o job de CI que garante que as cópias não divergem.

## Passos
1. **`.agents/skills/tag-task-runner/SKILL.md`** (`name: tag-task-runner`, `description` dizendo o que faz e quando usar). Conteúdo mínimo, neutro de provedor:
   - **Entrada:** ID do card; ler o card inteiro, o `plan_ref` e as regras.
   - **Antes de começar:** `status: pronto`; todas as `depends_on` com `status: concluido` e já na `main`; para `type: refactor` de regra crítica, golden master existente; senão parar e avisar.
   - **Execução:** criar a branch `task/<ID>` a partir da `main` atualizada (ou worktree); mudar o status para `em-andamento`; respeitar `write_scope`, `forbidden` e a lista de arquivos com dono (README das tasks); todo commit leva o ID na mensagem.
   - **Gates:** rodar cada comando de `acceptance`; quando existir, `scripts/check-task-scope`; dizer explicitamente o que não pôde ser rodado.
   - **Fechamento:** PR no modelo (ID, título, link do card, tabela de gates, riscos, flag de revisão humana), Handoff preenchido, `status: em-revisao`.
   - **Proibido:** mudar regra de negócio, editar arquivo com dono fora de card do coordenador, corrigir bug alheio em silêncio (vira issue sugerida no Handoff), rodar teste de navegador sem URL, credenciais e aprovação (`.agents/rules/e2e-testing.md`).
2. **`.agents/skills/tag-secure-controller/SKILL.md`** (`name: tag-secure-controller`). Conteúdo mínimo, só com o que o repositório confirma:
   - **Controle de acesso:** `filters()` com `accessControl` mais `accessRules()` terminando em `deny` para `'*'`; usar `'expression' => 'TagUtils::isAdmin()'` e afins; **nunca** `Yii::app()->user->checkAccess()`; `'users' => ['@']` na mesma regra da expressão. Rotas públicas só quando listadas (a partir de R2: `app/config/public-routes/<módulo>.php`).
   - **SQL:** nada de `$_GET`, `$_POST`, `user->school` ou `user->year` concatenado; usar `bindValue`, `addInCondition`, parâmetros de `CDbCriteria` ou cast inteiro. `school` e `year` vêm de estado de sessão que `SiteController::actionChangeSchool/ChangeYear` grava sem validar: tratar como não confiáveis.
   - **CSRF:** hoje desligado; o formulário novo deve usar `CHtml::beginForm`/`CActiveForm` e o AJAX deve estar pronto para enviar o token (o bloqueio chega em R2/R3).
   - **Verificação:** matriz com `curl` (visitante 302 para o login, perfil sem permissão 403, perfil com permissão 200) como no card R0-SYSADMIN-01; teste da matriz quando houver infraestrutura.
   - **Referência:** o card R0-SYSADMIN-01 e os PRs da B0/B2.
3. **`scripts/sync-agent-skills`** (PHP, com `#!/usr/bin/env php`, sem dependências): copia cada `.agents/skills/<skill>/` (todos os arquivos, com `\r\n` normalizado) para `.claude/skills/<skill>/`; registra o que gerenciou em `.claude/skills/.agents-sync.json` para só remover cópias órfãs de skills que ele mesmo criou (pode haver skills pessoais em `.claude/skills`). `--check` não grava nada, lista ausentes, diferentes e órfãs e sai com 1 se houver divergência. Em ambos os modos valida que cada `SKILL.md` tem `name` igual ao nome da pasta e `description` não vazia.
4. **Composer:** scripts `agents:sync` e `agents:check`, no estilo `routes:generate` (`docker exec tag-app php /app/scripts/sync-agent-skills [--check]`; confirme a permissão de escrita no bind mount).
5. **CI:** `.github/workflows/agents.yml` pequeno (`pull_request` filtrado por `paths`: `.agents/skills/**`, `.claude/skills/**`, `scripts/sync-agent-skills`), com `setup-php` 8.3 e `php scripts/sync-agent-skills --check`. O R1-CI-01 pode incorporá-lo ao `ci.yml` depois; registre isso no Handoff.
6. Rode `composer agents:sync` e commite as cópias de `.claude/skills/`.

## Fora de escopo
- As demais skills do plano (`tag-golden-master`, `tag-extract-service`, `tag-write-tests`, etc.) e a conversão dos playbooks (R1-AGT-04).
- Atualizar `CLAUDE.md` e `AGENTS.md` (R1-AGT-04).
- Os scripts `check-task-scope` e `inventory-actions` (R1-AGT-02 e R1-AGT-03).

## Critérios de aceite
- [ ] Existem as duas skills em `.agents/skills/` com `name` e `description` válidos e todo o conteúdo mínimo acima.
- [ ] `scripts/sync-agent-skills` copia para `.claude/skills/` e o `--check` sai com 0 depois da cópia e com 1 depois de uma alteração manual.
- [ ] `composer agents:sync` e `agents:check` existem.
- [ ] O workflow `agents.yml` falha quando as cópias divergem.
- [ ] **O sistema não muda:** nenhum arquivo de `app/` ou `themes/` foi alterado.

## Como verificar
```bash
php scripts/sync-agent-skills
php scripts/sync-agent-skills --check; echo "saída: $?"      # esperado: 0
echo "x" >> .claude/skills/tag-task-runner/SKILL.md
php scripts/sync-agent-skills --check; echo "saída: $?"      # esperado: 1, listando SKILL.md
git checkout -- .claude/skills && php scripts/sync-agent-skills --check
composer agents:sync
```

## Handoff
_Preenchido por quem executar._
