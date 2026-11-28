---
id: R7-GESTAOPRESENTE
title: "gestaopresente: proteger as exportações XLSX e extrair as consultas e o mapeamento de status"
release: R7
module: gestaopresente
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R7-REFINE-01]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achado 3), seção 8.6 (alto risco) e seção 4 (golden master antes de refatorar)"
write_scope:
  - app/modules/gestaopresente/**
  - app/src/GestaoPresente/**
  - tests/**/GestaoPresente/**
  - tests/_data/gestaopresente/**
  - frontend/pages/gestaopresente/**
  - sass/scss/pages/_gestaopresente.scss
  - app/config/public-routes/gestaopresente.php
  - metrics/baseline/gestaopresente.json
  - phpstan/baseline/gestaopresente.neon
read_only:
  - app/components/SimpleExcelExporter.php
forbidden:
  - composer.json
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer lint && composer analyse"
  - "Golden master das 3 planilhas (cabeçalhos e linhas) idêntico ao capturado antes da extração (composer test:integration -- --filter GestaoPresente; confirmar o script em R1)"
  - "php scripts/inventory-actions.php: nenhuma action do gestaopresente sem proteção ou fora de public-routes/gestaopresente.php"
---

# R7-GESTAOPRESENTE: gestaopresente: proteger as exportações XLSX e extrair as consultas e o mapeamento de status

## Contexto
**Uma pessoa lidera e o agente apoia.** Responsável: _a definir no R7-REFINE-01_. As planilhas levam CPF, NIS, filiação e situação de matrícula de todos os alunos do ano para uma plataforma externa, e quem recebe o arquivo é decisão do produto.

**Para que serve:** exportar planilhas XLSX para o "Gestão Presente" (a plataforma de controle de frequência a que se refere o enum `StudentEnrollmentStatusSGP`; **confirmar:** o nome e o dono da plataforma e o leiaute vigente). Há 3 exportações: dados pessoais dos estudantes, alteração de matrículas e cadastro de rematrícula. Não existe documento funcional em `docs/modules/`.

**Linha do inventário:** 1 controller (416 LOC, 4 actions, **sem `accessControl`**), 0 superglobais, 3 `createCommand`, 0 JSON, 1 view, 2 `<script>`, 7 `style=`, 0 Bootstrap 2, 0 JS, 0 CSS, 0 models, 0 services.

**Detalhamento (conferido em 2026-11-27)**
- `controllers/DefaultController.php`: `actionIndex` (≈L62), `actionExportPersonalData` (≈L68), `actionExportChangeEnrollment` (≈L180) e `actionExportRenrollment` (≈L254), mais 5 métodos privados (`parsePersonalData`, `parseChangeEnrollment`, `parseRowReenrolment`, `mapEnrollmentStatus` e `isActiveEnrollment`, ≈L342–394). **Sem `filters()` nem `accessRules()`**: as exportações, que levam dados sensíveis, ficam abertas a visitante.
- O menu mostra o item para `TRole::ADMIN`, `TRole::MANAGER` e `TRole::READER` e só se a feature `FEAT_INTEGRATIONS_GESTAOPRESENTE` estiver ativa (`themes/default/views/layouts/menus/_admin_menu.php` ≈L357–362; `app/components/auth/TFeature.php` ≈L89). Isso esconde o link, **não protege a rota**.
- SQL: 3 consultas longas em heredoc, executadas com `createCommand` (≈L147, ≈L222 e ≈L311) e `bindParam(':year', Yii::app()->user->year)`. O único filtro é o ano: a planilha traz **todas as escolas do tenant**. **confirmar:** se é o desejado para o perfil gestor e leitor (escopo por escola).
- Saída: `app/components/SimpleExcelExporter.php` (122 linhas, fora do escopo). O arquivo `controllers/DefaultController.php` declara no topo dois enums (`EnrollemntStatusTAG`, com erro de grafia, e `StudentEnrollmentStatusSGP`, ≈L3–58) que concentram a regra de conversão de status.
- `components/GestaoPresenteExporter.php` é um esqueleto de 161 bytes (classe com `run()` e `extractData()` vazios, não usada). **confirmar:** código morto.
- **View copiada do Sagres** (`views/default/index.php`): título "Sagres" (≈L3), CSS `css/sagres.css` (≈L7), 3 links com o mesmo `id="exportLink"` (≈L32, L50, L68) e um `<script>` inline (≈L92) que manipula elementos `finalTurma`, `semMovimentacao`, `generateWithoutCPF` e `mes`, **que não existem nesta view**, e monta um link para `?r=sagres/default/export`. **confirmar no navegador:** erro de JavaScript na página. Também carrega o jszip 3.7.1 por CDN (≈L91).

## Objetivo
As 3 exportações exigem login e papel, saem de um service testado com golden master, e a página não carrega código nem CDN herdados do Sagres.

## Passos
1. O card de refinamento (R7-REFINE-01) quebra este épico em cards `R7-GESTAOPRESENTE-<nn>`.
2. **SEC**
   - [ ] `filters()` com `accessControl` e `accessRules()` por papel, mais a checagem da feature `FEAT_INTEGRATIONS_GESTAOPRESENTE` no servidor. Quem exporta (admin, gestor, leitor) é decisão de produto.
   - [ ] Decidir e aplicar o escopo por escola para gestor e leitor.
   - [ ] Registrar em `TLog` quem exportou, quando e quantas linhas (dado sensível).
3. **GM**
   - [ ] Capturar, de um tenant demo anonimizado, os cabeçalhos e as linhas das 3 planilhas, por ano. Comparar o conteúdo (linhas e colunas), não os bytes do XLSX.
   - [ ] Caracterizar `mapEnrollmentStatus` com todos os valores de `EnrollemntStatusTAG`.
4. **SVC**
   - [ ] `app/src/GestaoPresente/Application/`: `ExportPersonalDataService`, `ExportChangeEnrollmentService` e `ExportRenrollmentService`, com as consultas em `Infrastructure/Yii/` e os enums e o mapeamento de status em `Domain/` (corrigindo a grafia só se o golden master permitir).
   - [ ] Remover o `GestaoPresenteExporter` se confirmado sem uso.
5. **API:** nenhuma prevista.
6. **JS:** remover o `<script>` herdado do Sagres e o jszip por CDN (ou levar o que for necessário para `frontend/pages/gestaopresente/`).
7. **CSS:** trocar `css/sagres.css` por `sass/scss/pages/_gestaopresente.scss`; mover os 7 `style=`; corrigir os `id` duplicados.

## Fora de escopo
- Mudar o leiaute das planilhas ou a regra de status exigida pela plataforma.
- Alterar o `SimpleExcelExporter` (componente compartilhado).

## Critérios de aceite
- [ ] **SEC:** as 4 actions estão protegidas ou listadas em `app/config/public-routes/gestaopresente.php`; o SQL usa bind; há registro de quem exporta; escopo por escola decidido.
- [ ] **GM:** as 3 planilhas batem com o golden master.
- [ ] **SVC:** as actions são magras; as regras estão em `app/src/GestaoPresente/` com pelo menos 80% de cobertura.
- [ ] **API:** não se aplica.
- [ ] **JS e CSS:** sem código herdado do Sagres, sem CDN e sem `style=` inline; catraca sem `?r=` literais.
- [ ] A pessoa responsável aprovou cada PR.

## Como verificar
```bash
composer lint && composer analyse
composer test:integration -- --filter GestaoPresente   # confirmar o script em R1
php scripts/inventory-actions.php
curl -i "http://localhost/?r=gestaopresente/default/exportPersonalData"   # visitante: 302 para o login
curl -i -b "PHPSESSID=<sessão de professor>" "http://localhost/?r=gestaopresente/default/exportRenrollment"   # esperado: 403
```
Testes de navegador (baixar as 3 planilhas) seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._
