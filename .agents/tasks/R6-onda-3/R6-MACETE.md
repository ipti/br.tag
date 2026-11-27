---
id: R6-MACETE
title: "macete: mover o backend para app/src/Macete e fechar o módulo"
release: R6
module: macete
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R6-REFINE-01]
size: M
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 3 (arquitetura alvo), seção 7 (F3, piloto MACETE) e seção 8.6 (onda 3)"
write_scope:
  - app/modules/macete/**
  - app/src/Macete/**
  - tests/**/Macete/**
  - frontend/pages/macete/**
  - sass/scss/pages/_macete.scss
  - app/config/public-routes/macete.php
  - metrics/baseline/macete.json
  - phpstan/baseline/macete.neon
read_only:
  - app/models/**
  - docs/modules/macete_lesson_plan.md
forbidden:
  - composer.json
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer lint && composer analyse"
  - "composer test:unit -- --filter Macete (confirmar o nome do script em R1)"
  - "php scripts/inventory-actions.php: nenhuma action do macete sem proteção ou fora de public-routes/macete.php"
  - "Catraca do módulo (metrics/baseline/macete.json) sem aumento e com zero ?r= literais"
---

# R6-MACETE: macete: mover o backend para app/src/Macete e fechar o módulo

## Contexto
**Para que serve** (`docs/modules/macete_lesson_plan.md`, "implementação em andamento"): plano de aula e registro de aula pela metodologia MACETE, com tabelas próprias (`Macete*`), modelos de plano (templates), habilidades da BNCC e dias letivos. É o piloto do front (F3) e está em desenvolvimento ativo. O R3-MACETE-01 já migrou o **JS** para `frontend/pages/macete/`; este épico fecha o **backend** e o CSS.

**Linha do inventário:** 4 controllers, 910 LOC, 22 actions, 0 sem `accessControl`, 13 superglobais, 7 `createCommand`, 13 JSON, 12 views, 3 `<script>`, 1 `style=`, 8 Bootstrap 2, JS 5 arquivos/557 linhas (antes do R3-MACETE-01), 0 CSS, 14 models, 7 services.

**Detalhamento (conferido em 2026-11-27)**
- Controllers: `AbilityController` 73 LOC/3 actions, `LessonsplanController` 322/7, `LessonsrecordController` 320/7 e `TemplatesController` 195/5. Todos têm `filters()` com `accessControl`. Cada um cria os services sob demanda (`new MaceteXService()` em métodos privados) e chama o `MaceteAccessService` no início das actions (`requireMaceteFeature()` no `AbilityController`, `requireLessonPlanFeature()` e `applyPlanScope()` no `LessonsplanController`).
- Services em `app/modules/macete/services/` (1402 linhas): `MaceteLessonPlanService` (454), `MaceteLessonPlanTemplateService` (286), `MaceteLessonRecordService` (232), `MaceteInstructionalDaysService` (147), `MaceteAbilityService` (106), `MaceteAccessService` (99) e `MaceteRichTextSanitizer` (78).
- **Premissa conferida:** os 3 services de gravação (`MaceteLessonPlanService`, `...TemplateService`, `...LessonRecordService`) recebem dependências no construtor (opcionais, com `?? new ...`) e gerenciam a própria transação (`beginTransaction` ≈L17, `commit`, `rollBack` ≈L50 a ≈L71). Os outros 4 não têm construtor.
- Os services ainda chamam `Yii::app()->db` e os ActiveRecords diretamente (7 `createCommand`: `MaceteInstructionalDaysService` ≈L127, `MaceteLessonPlanService` ≈L81, ≈L97, ≈L120, ≈L136 e `MaceteLessonPlanTemplateService` ≈L88, ≈L102). Isso viola a regra do anel de `app/src` (`Domain`/`Application` sem `Yii::app()` nem ActiveRecord), então a mudança é separar a regra da persistência.
- `MaceteAccessService` (escopo de escola, ano e professor) é a base do `UserContext` do plano (seção 11).
- Ainda há `?r=` em views: `lessonsplan/index.php` ≈L257 e ≈L330. Em `resources/`, 7 `?r=` literais existiam antes do R3-MACETE-01. **confirmar:** o que sobrou depois dele.
- Models (14): `MaceteLessonPlan`, `MaceteLessonPlanTemplate`, `MaceteLessonRecord`, `MaceteLessonMaterial` e as tabelas de apoio de seções, etapas, habilidades e recursos.

## Objetivo
Os services do MACETE viram classes de `app/src/Macete` sem Yii no domínio e na aplicação, com testes, e o módulo vira só camada HTTP.

## Passos
1. O card de refinamento (R6-REFINE-01) quebra este épico em cards executáveis `R6-MACETE-<nn>`.
2. **SEC**
   - [ ] Conferir que toda action chama o `require*Feature()` correspondente do `MaceteAccessService` e valida dono do plano e escola (professor só vê o próprio plano). **confirmar:** `LessonsrecordController` e `TemplatesController`, onde essa chamada não apareceu por grep.
   - [ ] Revisar os 13 usos de superglobais e os 7 `createCommand` (bind e escopo).
   - [ ] Confirmar CSRF nos formulários e nas chamadas AJAX de gravação.
3. **GM**
   - [ ] Caracterizar salvar plano, salvar registro, salvar template e o cálculo de dias letivos (`MaceteInstructionalDaysService`), antes de mover o código.
4. **SVC**
   - [ ] Mover `MaceteLessonPlanService`, `...TemplateService`, `...LessonRecordService`, `...InstructionalDaysService` e `...AbilityService` para `app/src/Macete/Application/` (um caso de uso por classe, método `handle()`), com o `TransactionManager` no lugar do `beginTransaction` manual.
   - [ ] Criar repositórios em `Infrastructure/Yii` para as consultas hoje em `createCommand` e para os ActiveRecords `Macete*`.
   - [ ] Mover `MaceteAccessService` para `Shared/Auth` (`UserContext`) com card de contrato, se a fundação ainda não o tiver feito.
   - [ ] Manter `MaceteRichTextSanitizer` como serviço puro, com teste dos casos de XSS.
5. **API:** nenhuma prevista. **confirmar:** se o app mobile precisa do plano e do registro de aula.
6. **JS:** o R3-MACETE-01 já migrou. Aqui só restam os `?r=` das views (`lessonsplan/index.php`) e os 3 `<script>` inline, que passam para `frontend/pages/macete/` com `jsonScript`.
7. **CSS:** `sass/scss/pages/_macete.scss`, mover o `style=` e trocar os 8 usos de Bootstrap 2.

## Fora de escopo
- Novas funcionalidades do MACETE ou mudança de metodologia.
- Alterar as tabelas `macete_*` (migrations têm card próprio).
- Mexer em `app/models/**`.

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/macete.php`; o SQL usa bind; os formulários enviam CSRF; não há segredo no código.
- [ ] **GM:** salvar plano, registro e template têm teste de caracterização.
- [ ] **SVC:** os services estão em `app/src/Macete/` sem `Yii::app()` em `Domain` e `Application`, com pelo menos 80% de cobertura.
- [ ] **API:** não se aplica, ou os endpoints têm teste de contrato.
- [ ] **JS:** páginas em `frontend/pages/macete/`; catraca com zero `?r=` literais e zero globais novos.
- [ ] **CSS:** estilos em `sass/scss/pages/_macete.scss`, sem blocos `<style>`, Bootstrap 2 em zero ou em queda.

## Como verificar
```bash
composer lint && composer analyse        # PHPStan nível máximo em app/src/Macete
composer test:unit -- --filter Macete    # confirmar scripts em R1
php scripts/inventory-actions.php        # macete sem action desprotegida
scripts/frontend-metrics                 # catraca; confirmar o comando em R1
```
Testes de navegador (criar plano, registro e template) seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._
