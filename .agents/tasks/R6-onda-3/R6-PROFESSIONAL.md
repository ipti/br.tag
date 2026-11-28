---
id: R6-PROFESSIONAL
title: "professional (funcionários de apoio e lotação): módulo pronto"
release: R6
module: professional
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R6-REFINE-01]
size: M
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 8.6 (onda 3)"
write_scope:
  - app/modules/professional/**
  - app/src/Professional/**
  - tests/**/Professional/**
  - frontend/pages/professional/**
  - sass/scss/pages/_professional.scss
  - app/config/public-routes/professional.php
  - metrics/baseline/professional.json
  - phpstan/baseline/professional.neon
read_only:
  - app/models/Professional.php
  - app/models/Attendance.php
  - docs/migracao_modulos/professional.md
forbidden:
  - composer.json
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer lint && composer analyse"
  - "composer test:unit -- --filter Professional (confirmar o nome do script em R1)"
  - "php scripts/inventory-actions.php: nenhuma action do professional sem proteção ou fora de public-routes/professional.php"
  - "Catraca do módulo (metrics/baseline/professional.json) sem aumento e com zero ?r= literais"
---

# R6-PROFESSIONAL: professional (funcionários de apoio e lotação): módulo pronto

## Contexto
**Para que serve:** cadastro dos **profissionais não docentes** (funcionários de apoio) das escolas, com frequência (`Attendance`) e lotação por escola e ano (`ProfessionalAllocation`). O `docs/migracao_modulos/professional.md` registra que o módulo **não** gerencia professores: esses estão no módulo `instructor` (R5-INSTRUCTOR). Não existe documento funcional em `docs/modules/`.

**Linha do inventário:** 1 controller, 365 LOC, 8 actions, 0 sem `accessControl`, 11 superglobais, 2 `createCommand`, 12 JSON, 5 views, 2 `<script>`, 7 `style=`, 2 Bootstrap 2, JS 2 arquivos/287 linhas, 0 CSS, 1 model, 0 services.

**Detalhamento (conferido em 2026-11-27)**
- `controllers/DefaultController.php` (365 LOC): `create` (≈L44), `update` (≈L81), `delete` (≈L185), `index` (≈L203), `deleteAttendance` (≈L240), `saveAllocation` (≈L248), `deleteAllocation` (≈L291) e `viewAllocation` (≈L310).
- `filters()` tem `accessControl` e `postOnly + delete`. A única regra `allow` libera as 8 actions para qualquer logado (`'@'`), sem distinguir papel. O `saveAllocation` verifica `TagUtils::isAdmin()` **dentro** da action e responde 200 com `success: false`; as demais gravações não verificam. **confirmar:** com o produto quem pode criar, editar e excluir profissionais e lotações.
- Pontos frágeis: `actionDelete` e `actionDeleteAttendance` usam `findByPk` sem tratar `null` (erro 500) e `actionDelete` apaga a frequência registro a registro, sem transação. O `$layout` padrão da classe é `//layouts/column2` (Blueprint, que o plano F0 aposenta), mas o módulo sobrescreve para `fullmenu` em `ProfessionalModule` (≈L20).
- SQL: os 2 `createCommand` (≈L138 e ≈L149, contagens de frequência e lotação) usam bind.
- JS: `resources/common/js/allocation.js` (231 linhas) e `professional.js` (56), com 1 `?r=` literal. 2 `<script>` e 1 bloco `<style>` inline nas views. Views com `style=`: `_form.php` (4) e `_allocation.php` (3).
- Models: `ProfessionalAllocation` está no módulo; `Professional` e `Attendance` ficam em `app/models/` (arquivos com dono, só leitura).

## Objetivo
Profissionais e lotações com permissão por papel, exclusões seguras e regras de lotação e frequência em services testados.

## Passos
1. O card de refinamento (R6-REFINE-01) quebra este épico em cards executáveis `R6-PROFESSIONAL-<nn>`.
2. **SEC**
   - [ ] Definir o papel de cada action (admin, secretaria, gestor da escola) e aplicar nas `accessRules()`, no lugar do `'@'` único e da checagem solta dentro de `saveAllocation`.
   - [ ] Garantir escopo por escola nas consultas e nas gravações de lotação e frequência.
   - [ ] Tratar `findByPk` nulo (404) em `delete`, `deleteAttendance`, `deleteAllocation` e `update`; exigir POST e CSRF em todas as exclusões.
3. **GM**
   - [ ] Caracterizar criar, editar e excluir profissional (com frequência), salvar e excluir lotação e os totais exibidos no `update` (frequência do mês e lotações do ano).
4. **SVC**
   - [ ] `app/src/Professional/Application/`: `RegisterProfessionalService`, `DeleteProfessionalService` (com `TransactionManager` e a remoção da frequência) e `SaveAllocationService`; regra de lotação duplicada no `Domain`.
5. **API:** nenhuma prevista.
6. **JS:** `allocation.js` e `professional.js` para `frontend/pages/professional/`, com `routes/` no lugar do `?r=` e delegação no lugar dos handlers inline.
7. **CSS:** `sass/scss/pages/_professional.scss`; mover os 7 `style=` e o bloco `<style>`; trocar os 2 usos de Bootstrap 2.

## Fora de escopo
- Unificar profissionais e instrutores.
- Alterar regras de frequência de funcionários.
- Mexer em `app/models/**` (inclusive `Professional` e `Attendance`).

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/professional.php`; o SQL usa bind; os formulários enviam CSRF; não há segredo no código.
- [ ] **GM:** os fluxos de profissional, frequência e lotação têm teste de caracterização.
- [ ] **SVC:** as actions críticas são magras; as regras estão em `app/src/Professional/` com pelo menos 80% de cobertura.
- [ ] **API:** não se aplica.
- [ ] **JS:** páginas em `frontend/pages/professional/`; catraca com zero `?r=` literais e zero globais novos.
- [ ] **CSS:** estilos em `sass/scss/pages/_professional.scss`, sem blocos `<style>`, Bootstrap 2 em zero ou em queda.

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter Professional   # confirmar scripts em R1
php scripts/inventory-actions.php             # professional sem action desprotegida
scripts/frontend-metrics                      # catraca; confirmar o comando em R1
curl -i "http://localhost/?r=professional/default/index"   # visitante: 302 para o login
```
Testes de navegador (cadastro, frequência e lotação) seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._
