---
id: R1-AGT-02
title: "scripts/check-task-scope: validar o escopo do card e o frontmatter dos cards no CI"
release: R1
module: plataforma
type: fundacao
skill: tag-task-runner
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R1-CI-01]
size: P
risk: baixo
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 8.3 (quando um card está pronto) e seção 8.4 (regras para os agentes não conflitarem)"
write_scope:
  - scripts/check-task-scope
  - scripts/lib/TaskScope/**
  - tests/Unit/Tooling/**
  - tests/_data/task-scope/**
  - .github/workflows/ci.yml
  - composer.json
  - composer.lock
read_only:
  - .agents/tasks/**
  - .agents/tasks/_TEMPLATE.md
  - .agents/tasks/README.md
  - phpunit.xml.dist
forbidden:
  - app/**
  - themes/**
  - Dockerfile
  - .agents/rules/**
acceptance:
  - "php scripts/check-task-scope --validate-cards   (todos os cards atuais passam)"
  - "composer test:unit -- --filter TaskScope"
  - "Em branch task/R1-AGT-02 de teste: alterar um arquivo fora do write_scope faz o script sair com 1 e listar o arquivo"
  - "composer analyse"
---

# R1-AGT-02: scripts/check-task-scope: validar o escopo do card e o frontmatter dos cards no CI

## Contexto
**Card do coordenador:** edita `.github/workflows/ci.yml` e `composer.json`. Só deve ser feito depois do `ci.yml` do R1-CI-01.

O plano (seção 8.3) diz que um card só está pronto quando "nenhum arquivo foi alterado fora do `write_scope`, conferido por `scripts/check-task-scope`, que lê o ID do card no nome da branch". Os agentes (`tag-plan-guardian` e `tag-refactorer`, em `.agents/agents/`) já citam esse script como "quando existir".

Fatos:
- Os cards ficam em `.agents/tasks/<lançamento>/<ID>.md`, com frontmatter YAML (modelo em `_TEMPLATE.md`): `id`, `title`, `release`, `module`, `type`, `skill`, `agents`, `status`, `depends_on`, `size`, `risk`, `human_review`, `jira`, `plan_ref`, `write_scope`, `read_only`, `forbidden`, `acceptance`. O `id` é igual ao nome do arquivo, e a branch é `task/<ID>` (convenções no README das tasks).
- Valores aceitos: `type` em `seguranca | fundacao | refactor | testes | api | frontend | css | docs | epico | refinamento`; `status` em `rascunho | pronto | em-andamento | em-revisao | concluido | bloqueado`; `size` em `P | M | G`; `risk` em `baixo | medio | alto`; `human_review` em `obrigatoria | recomendada`.
- Os globs dos escopos usam `**` (por exemplo `app/modules/systemadmin/views/**`) e caminhos exatos (`composer.json`).
- Não há parser YAML no repositório além do que vem como dependência transitiva: `symfony/yaml` aparece no `composer.lock` (5.4.x-dev), mas não está declarado em `composer.json`. **Declare-o em `require-dev`** em vez de depender do acaso.
- O `git diff` precisa de histórico completo no CI (`fetch-depth: 0`) para comparar com `origin/main`.

## Objetivo
Um script que, num PR de branch `task/<ID>`, falha se algum arquivo alterado estiver fora do `write_scope` do card (ou dentro de `forbidden`) e que valida o frontmatter de todos os cards.

## Passos
1. Implemente `scripts/check-task-scope` (PHP, `#!/usr/bin/env php`) como casca fina sobre classes em `scripts/lib/TaskScope/` (carregadas por `autoload-dev` ou `require`), para poder testar sem chamar o `git`.
2. **Modo escopo** (padrão): lê o nome da branch (`--branch=` ou `GITHUB_HEAD_REF`/`git rev-parse --abbrev-ref HEAD`); se não casar `task/<ID>`, imprime que não há card e sai com 0. Senão localiza `.agents/tasks/*/<ID>.md`, carrega `write_scope` e `forbidden` e compara com `git diff --name-only --no-renames origin/main...HEAD` (`--base=` configurável; `--no-renames` faz o caminho antigo e o novo serem conferidos).
   - `forbidden` tem precedência sobre `write_scope`;
   - o próprio arquivo do card é sempre permitido (status e Handoff);
   - `**` casa qualquer profundidade e `*` só dentro de um segmento;
   - ao falhar, lista cada arquivo fora do escopo e o glob mais próximo, e sai com 1.
3. **Modo `--validate-cards`**: percorre `.agents/tasks/*/*.md` (menos `_TEMPLATE.md` e `README.md`) e valida: todos os campos, na ordem do modelo; `id` igual ao nome do arquivo; `release` igual ao prefixo da pasta (`R1-fundacao` → `R1`); valores dos enumerados acima; `depends_on` apontando para cards que existem; `write_scope` não vazio e sem caminho absoluto; `acceptance` não vazio; presença das seções `Contexto`, `Objetivo`, `Passos`, `Fora de escopo`, `Critérios de aceite`, `Como verificar` e `Handoff`. Sai com 1 e imprime `arquivo: motivo` por problema.
4. **Testes** em `tests/Unit/Tooling/` com fixtures em `tests/_data/task-scope/`: glob (`**`, `*`, caminho exato, `forbidden` ganhando), branch fora do padrão, rename, card válido e cards inválidos (campo faltando, `id` diferente do arquivo, `depends_on` inexistente).
5. **Composer:** declare `symfony/yaml` em `require-dev` e crie o script `scope:check`.
6. **CI:** acrescente ao `ci.yml` um job `scope`, só em `pull_request`, com `fetch-depth: 0`, `setup-php` 8.3, `composer install --no-scripts` e os dois modos (`--validate-cards` sempre; o modo escopo com `--base=origin/${{ github.base_ref }}`).
7. Rode o script contra o próprio card, numa branch `task/R1-AGT-02` de teste, tocando um arquivo fora do escopo, e registre a saída no Handoff.

## Fora de escopo
- Corrigir os cards existentes além do necessário para o `--validate-cards` passar: se um card estiver inválido, **não** o edite (os cards são de outros agentes); abra um item no Handoff.
- Verificar o conteúdo dos critérios de aceite ou o status das dependências na `main`.
- Os demais jobs do CI (R1-CI-01) e as catracas de front (R1-FRONT-02).

## Critérios de aceite
- [ ] `php scripts/check-task-scope --validate-cards` passa nos cards presentes na `main` e falha nos fixtures inválidos.
- [ ] Numa branch `task/<ID>`, arquivo fora do `write_scope` ou dentro de `forbidden` faz o script sair com 1 e listar o arquivo.
- [ ] Branch que não é `task/<ID>` sai com 0, com mensagem.
- [ ] Testes unitários passam e o PHPStan `src` fica verde.
- [ ] O job `scope` existe no `ci.yml` e roda só em PR.
- [ ] **O sistema não muda:** nenhum arquivo de `app/` ou `themes/` foi alterado.

## Como verificar
```bash
php scripts/check-task-scope --validate-cards; echo "saída: $?"
composer test:unit -- --filter TaskScope
composer analyse
git switch -c task/R1-AGT-02
echo "" >> app/yiic.php   # arquivo fora do write_scope, só para o teste (desfaça com git checkout)
php scripts/check-task-scope --base=origin/main; echo "saída: $?"   # esperado: 1
```

## Handoff
_Preenchido por quem executar._
