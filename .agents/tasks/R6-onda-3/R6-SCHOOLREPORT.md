---
id: R6-SCHOOLREPORT
title: "schoolreport (boletim e relatórios): módulo pronto e decisão sobre o segundo login de responsáveis"
release: R6
module: schoolreport
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R6-REFINE-01]
size: G
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F5, ilhas em quarentena), seção 8.6 (onda 3) e seção 16 (item 6)"
write_scope:
  - app/modules/schoolreport/**
  - app/src/SchoolReport/**
  - tests/**/SchoolReport/**
  - frontend/pages/schoolreport/**
  - sass/scss/pages/_schoolreport.scss
  - app/config/public-routes/schoolreport.php
  - metrics/baseline/schoolreport.json
  - phpstan/baseline/schoolreport.neon
read_only:
  - app/models/StudentIdentification.php
forbidden:
  - composer.json
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer lint && composer analyse"
  - "composer test:unit e composer test:integration (golden master dos relatórios), com os nomes confirmados em R1"
  - "php scripts/inventory-actions.php: nenhuma action do schoolreport sem proteção ou fora de public-routes/schoolreport.php"
  - "Catraca do módulo (metrics/baseline/schoolreport.json) sem aumento e com zero ?r= literais"
---

# R6-SCHOOLREPORT: schoolreport (boletim e relatórios): módulo pronto e decisão sobre o segundo login de responsáveis

## Contexto
**Para que serve:** reúne os relatórios e formulários da secretaria (declaração de matrícula, ficha do aluno, requerimento de transferência, ata, relatórios estatísticos, boletins de Búzios) e o **boletim para responsáveis**, que tem login próprio (`DefaultController`: login, seleção de aluno, notas e frequência). A migração dos controllers legados está registrada em `docs/migracao_modulos/schoolreport.md` e marcada como em andamento no `PROGRESSO.md`.

**Linha do inventário:** 3 controllers, 1235 LOC, 96 actions, 3 sem `accessControl`, 2 superglobais, 94 `createCommand`, 6 JSON, 102 views, 68 `<script>`, 1209 `style=`, 244 Bootstrap 2, JS 2 arquivos/49 linhas, 4 CSS, 1 model, 2 services (os repositories).

**Detalhamento (conferido em 2026-11-27)**
- `DefaultController` 167 LOC/8 actions: sem `filters()` nem `accessRules()`. Cada action testa `isGuest` na mão.
- `FormsController` 246 LOC/24 actions e `ReportsController` 822 LOC/64 actions: definem `accessRules()` (≈L10) mas **não** `filters()`, então as regras não são aplicadas. O `beforeAction()` (≈L46 e ≈L86) não chama `parent::beforeAction()`.
- `repositories/ReportsRepository.php` 2625 linhas (72 métodos, 74 `createCommand`) e `FormsRepository.php` 1503 linhas (40 métodos, 17 `createCommand`). **confirmar:** quais concatenam valores no SQL (os cards R0-SQL-07 e R3-SEC-03 já tocaram o módulo).
- **Segundo login:** `SchoolreportModule::beforeControllerAction` (≈L24–38) troca o componente `user` para `CWebUser` com `loginUrl` próprio e prefixo de estado `_schoolreport`, só quando `controller->id === 'default'`. Existem cópias de `UserIdentity` (`components/UserIdentity.php`) e de `LoginForm` (`models/LoginForm.php`) com o mesmo nome das classes globais de `app/components/` e `app/models/`.
  - O usuário é o CPF do responsável e a **senha são as iniciais do nome dele** (`schoolReportPassword`, ≈L11–22). É um risco de segurança que depende de **decisão de produto** (plano, seção 16, item 6).
  - `authenticate()` lê `$students[0]` (≈L28) antes de testar `count($students) === 0` (≈L38): CPF desconhecido gera erro em vez de "usuário inválido".
- **Ilha com Semantic UI via CDN:** `views/layouts/schoolreport.php` (≈L30–32 e ≈L104–106, com o `accordion.min.js` carregado duas vezes) e `views/layouts/login.php` (≈L23). Os layouts do módulo estão dentro do módulo, portanto dentro do escopo.
- CSS: `resources/common/css/style.css` (7081 linhas, 165 KB) não é registrado por nenhum layout ou view do módulo (**confirmar:** órfão; o plano F0 prevê remover). Os outros são `layout.css`, `login.css` e `grades.css`. JS: `grades.js` (44) e `layout.js` (5).
- Views com mais `style=`: `reports/index.php` (196), `reports/EnrollmentPerClassroomReport.php` (116), `forms/TransferForm.php` (83), `reports/buzios/quarterly/QuarterlyReportThreeYear.php` e `...SecondYear.php` (69 cada). Há 89 blocos `<style>` e 56 handlers `on*=`.

## Objetivo
Relatórios protegidos, com consultas isoladas em classes testadas, sem CDN, e uma decisão registrada sobre o login de responsáveis.

## Passos
1. O card de refinamento (R6-REFINE-01) quebra este épico em cards executáveis `R6-SCHOOLREPORT-<nn>`.
2. **SEC**
   - [ ] Adicionar `filters()` com `accessControl` em `FormsController` e `ReportsController`, e `parent::beforeAction($action)` nos dois. Listar o que for público em `public-routes/schoolreport.php`.
   - [ ] Decidir com o produto o login de responsáveis (manter com senha melhor, trocar por token, ou aposentar) e registrar em ADR. O agente só implementa depois da decisão.
   - [ ] Corrigir o `authenticate()` (índice antes da contagem) e limitar tentativas de login.
   - [ ] Conferir se `actionGrades($eid)` valida que a matrícula pertence ao responsável da sessão (**confirmar:** IDOR).
   - [ ] Bind em todo SQL dos dois repositories e escopo por escola e ano.
3. **GM:** golden master dos relatórios mais usados (declaração de matrícula, ficha do aluno, transferência, ata, matrícula por turma) e do boletim do responsável.
4. **SVC:** mover as consultas dos repositories para `app/src/SchoolReport/Infrastructure/Yii/` (uma classe por relatório) com DTOs; controllers só chamam e renderizam.
5. **API:** nenhuma prevista. **confirmar:** se o app mobile precisa do boletim.
6. **JS:** `grades.js`, `layout.js` e os `<script>` inline das views para `frontend/pages/schoolreport/`.
7. **CSS:** servir Semantic UI localmente (ou removê-lo), criar `sass/scss/pages/_schoolreport.scss`, mover os `style=` e `<style>` view a view (começar pelas 5 views acima) e apagar o `style.css` órfão se confirmado.

## Fora de escopo
- Reescrever a ilha com outro framework (plano, seção 15).
- Alterar o conteúdo ou o cálculo dos relatórios.

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/schoolreport.php`; o SQL usa bind; os formulários enviam CSRF; não há segredo no código.
- [ ] **GM:** os relatórios críticos têm golden master ou teste de caracterização.
- [ ] **SVC:** as actions críticas são magras; as regras estão em `app/src/SchoolReport/` com pelo menos 80% de cobertura.
- [ ] **API:** não se aplica, ou os endpoints têm teste de contrato.
- [ ] **JS:** páginas em `frontend/pages/schoolreport/`; catraca com zero `?r=` literais e zero globais novos.
- [ ] **CSS:** estilos em `sass/scss/pages/_schoolreport.scss`, sem blocos `<style>`, Bootstrap 2 em zero ou em queda, sem CDN.
- [ ] A decisão sobre o login de responsáveis está registrada em ADR.

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter SchoolReport   # confirmar scripts em R1
php scripts/inventory-actions.php             # schoolreport sem action desprotegida
scripts/frontend-metrics                      # catraca; confirmar o comando em R1
curl -i "http://localhost/?r=schoolreport/reports/index"   # visitante: 302 para o login
```
Testes de navegador (login de responsável, boletim, relatórios) seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._
