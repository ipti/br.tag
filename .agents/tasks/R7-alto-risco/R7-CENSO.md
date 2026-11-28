---
id: R7-CENSO
title: "CensoController: migrar para app/modules/censo com golden master do arquivo do Educacenso"
release: R7
module: raiz
type: epico
skill: tag-module-migration
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R7-REFINE-01]
size: G
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 8.6 (alto risco) e seção 4 (golden master antes de refatorar regra crítica)"
write_scope:
  - app/controllers/CensoController.php
  - themes/default/views/censo/**
  - app/modules/censo/**
  - app/src/Censo/**
  - tests/**/Censo/**
  - frontend/pages/censo/**
  - sass/scss/pages/_censo.scss
  - app/config/public-routes/censo.php
  - metrics/baseline/censo.json
  - phpstan/baseline/censo.neon
read_only:
  - app/libraries/Educacenso/**
forbidden:
  - composer.json
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer lint && composer analyse"
  - "Golden master do arquivo de exportação idêntico ao capturado antes da migração (composer test:integration -- --filter CensoGoldenMaster; confirmar o script em R1)"
  - "php scripts/inventory-actions.php: nenhuma action do censo sem proteção ou fora de public-routes/censo.php"
---

# R7-CENSO: CensoController: migrar para app/modules/censo com golden master do arquivo do Educacenso

## Contexto
**Uma pessoa lidera e o agente apoia.** Responsável: _a definir no R7-REFINE-01_. O agente monta o golden master, move código e abre PRs pequenos; a pessoa decide o recorte, valida a saída contra o Educacenso e aprova cada merge.
**Para que serve:** validar os dados do censo escolar e gerar o arquivo de exportação do Educacenso (registros 00, 10, 20, 30, 40, 50, 60 e 99), além de importar códigos e IDs INEP. É regra de negócio sujeita a mudança anual (há constantes por ano, como `QUALIFICATION_COURSE_AXIS_STAGE_IDS_2026` ≈L868). Sem testes hoje.

**Linha do inventário:** `app/controllers/CensoController.php`, 3120 linhas, 11 actions, sem `accessControl`, 9 superglobais, 22 `createCommand`, 0 JSON. O `docs/migracao_modulos/censo.md` cita 2983 linhas e está desatualizado.

**Detalhamento (conferido em 2026-11-27)**
- **Acesso:** `accessRules()` (≈L88) é uma cópia do `AdministratorController`: libera `CreateUser`, `index` e `conflicts` para `'*'` e lista actions que não existem neste controller. **Não há `filters()`**, portanto nenhuma regra é aplicada.
- 36 métodos públicos: 11 actions e 25 auxiliares. Actions: `importDegreeCodes` (≈L120), `validate` (≈L1648), `index` (≈L1696), `exportWithoutInepid` (≈L1713), `export` (≈L2732), `exportIdentification` (≈L2760), `downloadExportFile` (≈L2782), `downloadExportFileIdentification` (≈L2800), `initialImport` (≈L2818), `inepImport` (≈L2837) e `import` (≈L3021).
- **9 validadores públicos:** `validateSchool` (≈L182), `validateSchoolStructure` (≈L457), `validateClassroom` (≈L772), `validateInstructor` (≈L948), `validateInstructorDocuments` (≈L1096), `validateInstructorData` (≈L1148), `validateStudentIdentification` (≈L1248), `validateStudentDocumentsAddress` (≈L1433) e `validateEnrollment` (≈L1510).
- **Exportação:** `fixMistakesExport` (≈L2055–2731, cerca de 680 linhas) corrige campos antes de gravar; apoiam `normalizeFields`, `normalizeField2019`, `mountItemExport`, `fixName`, `certVerify` (dígito da certidão civil, ≈L1977) e `sanitizeString`. O arquivo sai em `app/export/<ano>_<escola>.TXT` (≈L2784), gerado pela biblioteca `app/libraries/Educacenso/` (`Educacenso.php`, `Register00` a `Register60`, `Register99` e `EducacensoRegisterFormatter`).
- **Entradas de arquivo:** `$_FILES['Import']` e `$_POST['Import']` em `initialImport` e `inepImport` (≈L2818–2850) e `$_FILES['file']` em `import` (≈L3031–3040); `fopen` do upload em ≈L2861, ≈L2935 e ≈L3047. **confirmar:** validação de tipo e tamanho.
- Views em `themes/default/views/censo/` (`validate.php`, `_validatemessages.php` com 12,4 KB, `inepImport.php`, `initialImport.php`); JS `js/censo/functions.js` com 25 linhas.

## Objetivo
O Educacenso sai de um controller de 3120 linhas e passa a viver em `app/modules/censo` (HTTP) e `app/src/Censo` (regras), com a exportação provadamente idêntica à de hoje.

## Passos
1. O card de refinamento (R7-REFINE-01) quebra este épico em cards `R7-CENSO-<nn>`, com a pessoa responsável aprovando cada um.
2. **SEC**
   - [ ] Substituir `accessRules()` por regras reais com `filters()` (admin e secretaria), removendo as actions inexistentes; listar o que for público em `public-routes/censo.php`.
   - [ ] Validar uploads (extensão, tamanho, conteúdo) e o caminho do arquivo baixado; bind em todos os 22 `createCommand`.
3. **GM (primeiro, antes de mover qualquer linha)**
   - [ ] Congelar o arquivo de exportação (`export`, `exportIdentification`, `exportWithoutInepid`) de um tenant demo anonimizado, por ano letivo.
   - [ ] Congelar as mensagens de cada um dos 9 validadores sobre o mesmo dataset, e testar `certVerify` e `fixName` com casos isolados.
4. **SVC (em fases, conforme o `PROGRESSO.md`)**
   - [ ] Criar o módulo `app/modules/censo/` (o registro em `main.php` é arquivo com dono: abrir card de contrato) e os controllers `Default`, `Export`, `Import` e `Inep`.
   - [ ] Mover os validadores para `app/src/Censo/Domain/` (regras puras) e as orquestrações para `Application/` (`ValidateCensusService`, `ExportCensusService`, `ImportInepService`), com as regras por ano em classes separadas.
   - [ ] Embrulhar `Educacenso::exportar()` sem alterar a biblioteca; extração dela fica para um card posterior.
5. **API:** nenhuma prevista.
6. **JS:** `js/censo/functions.js` para `frontend/pages/censo/`.
7. **CSS e limpeza:** `sass/scss/pages/_censo.scss` para as 4 views, e depois a limpeza abaixo.
   - [ ] Buscar `?r=censo/` e `?r=administrator/` em PHP, JS e menus, conferir o estado ativo dos menus, remover `CensoController.php` e a pasta `themes/default/views/censo/` (playbook `module-migration`).

## Fora de escopo
- Mudar qualquer regra de validação ou formato do arquivo (qualquer divergência do golden master vai para a pessoa responsável).
- `AdministratorController` (R7-ADMIN-SITE).

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/censo.php`; o SQL usa bind; os formulários enviam CSRF; uploads validados.
- [ ] **GM:** o arquivo de exportação e as mensagens de validação batem byte a byte com o golden master.
- [ ] **SVC:** as actions são magras; as regras estão em `app/src/Censo/` com pelo menos 80% de cobertura.
- [ ] **API:** não se aplica.
- [ ] **JS e CSS:** páginas em `frontend/pages/censo/` e `_censo.scss`; catraca sem `?r=` literais.
- [ ] `app/controllers/CensoController.php` e `themes/default/views/censo/` foram removidos, sem rota órfã.

## Como verificar
```bash
composer lint && composer analyse
composer test:integration -- --filter CensoGoldenMaster   # confirmar o script em R1
php scripts/inventory-actions.php                          # censo sem action desprotegida
git grep -n "CensoController\|?r=censo/" -- app themes js  # sem resultados ao final
```
Testes de navegador (validar, exportar, baixar e importar) seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._
