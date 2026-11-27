---
id: R7-REFINE-01
title: "Refinar os épicos de alto risco: inventário e rascunhos de cards, com uma pessoa conduzindo"
release: R7
module: plataforma
type: refinamento
skill: tag-task-runner
agents: [tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R1-AGT-03, R3-SEC-03]
size: M
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 8.3 (cards a partir de inventário) e seção 8.6 (alto risco: uma pessoa lidera, o agente apoia)"
write_scope:
  - .agents/tasks/R7-alto-risco/**
  - .agents/tasks/README.md
read_only:
  - app/**
  - themes/**
  - js/**
  - docs/migracao_modulos/**
  - docs/arquitetura/plano-modernizacao.md
forbidden:
  - composer.json
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "php scripts/inventory-actions.php gera o JSON de CensoController, AdministratorController, SiteController e dos módulos systemadmin, sedsp, sagres, resultsmanagement e gestaopresente"
  - "Cada épico R7-* tem uma pessoa responsável nomeada e um rascunho de lista de cards R7-<ÁREA>-<nn>"
  - "Nenhum card de R7 passa a pronto sem golden master ou teste de caracterização como dependência"
---

# R7-REFINE-01: Refinar os épicos de alto risco: inventário e rascunhos de cards, com uma pessoa conduzindo

## Contexto
Os 7 épicos de R7 (`R7-CENSO`, `R7-ADMIN-SITE`, `R7-SYSTEMADMIN`, `R7-SEDSP`, `R7-SAGRES`, `R7-RESULTSMANAGEMENT`, `R7-GESTAOPRESENTE`) mexem em integrações externas (Educacenso, SED-SP, Sagres), em dados em massa e em login. O plano (seção 8.6) define que **uma pessoa lidera e o agente apoia**: o agente gera inventário, rascunhos e testes de caracterização, e a pessoa decide o recorte, revisa cada PR e aprova o merge. Por isso este refinamento não deixa nenhum card nascer como `pronto` sem aval da pessoa responsável.

Fatos que o refinamento precisa reconferir, porque o código muda entre R1 e R7 (todos **confirmar:**):
- Controllers raiz: `CensoController` 3120 linhas e 11 actions, `AdministratorController` 1609 linhas e 18 actions, `SiteController` 555 linhas e 15 actions (README, inventário de 2026-11-27). O `PROGRESSO.md` mantém o `SiteController` **na raiz** ("fora de escopo", por conter login, `changeSchool` e `changeYear`), o que conflita com o título do R7-ADMIN-SITE. A pessoa decide se `Site` migra ou fica.
- As views de `Administrator` não existem em `themes/default/views/administrator/` (só `censo/` e `site/`); várias actions chamam `render('index')` sem view correspondente. **confirmar:** o que é código morto.
- R0-ADMIN-01 e R3-SEC-03 já trataram o acesso dos controllers raiz; ver o estado atual de `public-routes/`.
- As integrações têm credenciais em tratamento no R0-INFRA-01 (foods e SED).

## Objetivo
Cada épico de R7 tem inventário atualizado, uma pessoa responsável, uma lista de cards rascunho com a ordem golden master → refactor, e as decisões de produto pendentes anotadas.

## Passos
1. Rode `php scripts/inventory-actions.php` (**confirmar:** argumentos no card R1-AGT-03) para os 3 controllers raiz e para os módulos `systemadmin`, `sedsp`, `sagres`, `resultsmanagement` e `gestaopresente`.
2. Compare com o README e registre no corpo de cada épico o que mudou.
3. Para cada épico, escreva a lista de cards na ordem **SEC → GM → SVC → (migração) → JS → CSS**. O GM é sempre dependência dos demais, e o GM de Censo, Sagres e SED-SP usa arquivos reais anonimizados (decisão da pessoa responsável).
4. Registre, por épico, a pessoa responsável (campo `Responsável` no Contexto) e os pontos de decisão:
   - Censo: formato e versão do arquivo do Educacenso a congelar.
   - Administrator/Site: ficam ou migram; destino das actions duplicadas com o `systemadmin` (`createUser`, `acl`).
   - Resultsmanagement: medir uso por tenant (pergunta 3 da seção 16 do plano) antes de decidir entre reescrever e aposentar.
   - sedsp e sagres: ambiente de homologação disponível para contrato?
5. Atualize a tabela "Índice por módulo" do README com os cards rascunho.
6. Ordem sugerida: R7-SYSTEMADMIN antes de R6-PEQUENOS (que contém o módulo `tag`, usado pelas exportações do systemadmin); R7-CENSO só depois do golden master do arquivo de exportação.
7. Entregue à pessoa responsável para revisão e aprovação antes de marcar qualquer card como `pronto`.

## Fora de escopo
- Executar qualquer card de R7.
- Alterar código, configuração, catraca ou documentação fora de `.agents/tasks/`.
- Decidir pelo produto (o refinamento só lista as decisões).

## Critérios de aceite
- [ ] O JSON do inventário foi gerado para todos os alvos e as diferenças estão registradas.
- [ ] Cada épico tem pessoa responsável e lista de cards rascunho com `depends_on` explícito.
- [ ] As decisões de produto pendentes estão listadas por épico.
- [ ] O índice do README lista os cards novos.
- [ ] A pessoa responsável revisou a lista.

## Como verificar
```bash
php scripts/inventory-actions.php        # sem erro; confirmar flags no card R1-AGT-03
git status --short .agents/tasks         # só R7-alto-risco/ e o README
git diff --stat -- . ':!.agents/tasks'   # vazio: nada fora do escopo
```
Confira, abrindo 3 cards sorteados, que os arquivos citados existem (`ls`). Sem testes de navegador neste card.

## Handoff
_Preenchido por quem executar._
