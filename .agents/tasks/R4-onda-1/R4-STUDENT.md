---
id: R4-STUDENT
title: "student: levar o cadastro de aluno e a matrícula ao critério de módulo pronto"
release: R4
module: student
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R4-REFINE-01]
size: G
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B3, próximas ondas), seção 7 (F4, item 3) e seção 8.6 (onda 1)"
write_scope:
  - app/modules/student/**
  - app/src/Student/**
  - tests/**/Student/**
  - frontend/pages/student/**
  - sass/scss/pages/_student.scss
  - app/config/public-routes/student.php
  - metrics/baseline/student.json
  - phpstan/baseline/student.neon
  - js/enrollment/form/**
read_only:
  - app/models/**
  - app/modules/sedsp/**
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer analyse (app/src/Student no nível máximo, sem baseline)"
  - "scripts/inventory-actions.php student mostra zero actions críticas com SQL cru ou superglobal"
  - "Golden master do cadastro, da matrícula e da transferência verde, com cobertura de pelo menos 80% no código novo"
  - "Catraca do módulo student: zero ?r= literais, zero globais novos e Bootstrap 2 em queda"
---

# R4-STUDENT: student: levar o cadastro de aluno e a matrícula ao critério de módulo pronto

## Contexto
O `student` cuida da ficha do aluno (identificação, documentos e endereço, restrições, transtornos, vacinas), da **matrícula** em turma, da **transferência**, da exclusão e do IMC/saúde (`Studentimc`). Quando a integração SEDSP está ativa, cada gravação também sincroniza com o sistema estadual. Não há `docs/modules/student.md`; a descrição vem do código.

**Inventário do README (2026-11-27):** 3 controllers, 1919 LOC, 34 actions, 0 sem `accessControl`, 88 superglobais, 2 `createCommand`, 11 JSON, 15 views, 3 `<script>`, 63 `style=`, 13 Bootstrap 2, 9 JS (1915 LOC), 0 CSS, 3 models, 0 services/usecases.

**Controllers (LOC; actions):** `StudentController` (1088; 17), `StudentimcController` (428; 9), `EnrollmentController` (403; 8).
- **`StudentController::actionCreate` (≈L437–577)** salva 6 tipos de model (`StudentIdentification`, `StudentDocumentsAndAddress`, `StudentRestrictions`, `StudentDisorder`, `StudentEnrollment` e `StudentVaccine`) em sequência, **sem transação**. O `actionUpdate` (≈L587–746) repete o padrão.
- **SEDSP dentro do model:** `StudentIdentification::syncStudentWithSED` (`app/models/StudentIdentification.php` ≈L341–431), `processEnrollment` (≈L432–460) e `addEnrollmentToSedsp` (≈L469–486) chamam o SEDSP a partir do ActiveRecord; devolvem `false` em 401 (`handleUnauthorizedError`).
- **Defeito no create (≈L541):** a condição lê `$syncResult->identification->outErro` antes de testar `$syncResult === false`; com 401 vira erro em `false`.
- **Outras actions:** `actionTransfer` (≈L747) e `actionGetTransferClassrooms` (≈L842), `actionDelete` (≈L866) com `excluirMatriculaFromSED` (≈L931), `actionGetStudentAjax` (≈L207, tabela server-side).

**JS:**
- **No módulo** (`app/modules/student/resources/`): `js/form/validations.js` (**1327 linhas**), `_initialization.js` (344), `pagination.js` (76), `functions.js` (34), `js/transfer/*` (16) e `health/*` (118). Quatro `?r=` literais.
- **No `js/` raiz** (só `js/enrollment/form/`; `js/form` e `js/transfer` não existem lá, vivem em `resources/js/`): `js/enrollment/form/` (`validations.js` 122, `_initialization.js` 70, `functions.js` 56, dois `?r=`), registrado por `student/_form.php` (≈L29–31) e por `enrollment/_form.php` (≈L5–7).
- **Ponto de atenção:** `student/_form.php` carrega **dois arquivos `validations.js`** (≈L23–24 e ≈L30). Confirmar colisão de globais.

**CSS:** nenhum arquivo. `<style>` em `student/index.php`. Mais `style=`: `student/_form.php` (**48**), `enrollment/_form.php` (9), `student/index.php` (5), `studentimc/_form.php` (4).
**Sem `accessControl`:** nenhum, mas todas as actions de `StudentController` e `EnrollmentController` liberam `'@'` sem papel. **Regras abertas:** `StudentimcController` libera `index`, `view`, `studentIndex`, `delete`, `renderStudentTable` e `studentIMCReport` para `'*'` (≈L32–33).
**Models locais:** `StudentIMC`, `StudentImcClassification` e `StudentDisorderHistory`; o resto vem de `app/models/`.

## Objetivo
Cadastro, matrícula, transferência e exclusão de aluno passam a ter regra em `app/src/Student/`, atômica e testada, com o SEDSP fora do ActiveRecord e o formulário em `frontend/pages/student/`.

## Passos
1. O card de refinamento ([R4-REFINE-01](R4-REFINE-01.md)) quebra este épico em cards executáveis. Os pacotes abaixo são a ordem e os alvos.
2. **SEC (acesso, SQL e CSRF)**
   - [ ] Fechar o `StudentimcController` (hoje `'*'` em 6 actions, com dados de saúde de crianças) e restringir `studentIMCReport` (≈L377) à escola do usuário.
   - [ ] `EnrollmentController::actionGetModalities` (≈L116–118) monta `"stage = $stage"` com `$_POST['Stage']`: usar bind.
   - [ ] `deleteAll("student_id = $modelStudentIdentification->id")` (≈L523 e ≈L676): usar bind.
   - [ ] `postOnly` no `delete` de aluno e de matrícula; confirmar o bind de `getStudentAjax` (≈L207–298); decidir com uma pessoa se o papel `guardian` pode chegar nessas actions; CSRF nos formulários.
3. **GM (fluxos a caracterizar antes de mexer)**
   - [ ] Cadastrar aluno com e sem matrícula (datas por status, ≈L509–514), duplicidade de CPF e de certidão (≈L469–482).
   - [ ] Atualizar, transferir e excluir matrícula; vacinas (`deleteAll` e reinserção); fluxo SEDSP com um dublê do gateway (sem chamar o serviço real).
4. **SVC (contexto `app/src/Student`)**
   - [ ] `RegisterStudentService` e `UpdateStudentService` (actions `actionCreate` e `actionUpdate`) com `TransactionManager`: ficha, documentos, restrições, transtornos, matrícula e vacinas atômicos.
   - [ ] Gateway SEDSP como porta em `Application` e adaptador em `Infrastructure`, chamado **depois** do commit. O model passa a delegar a ele (card de contrato, `app/models/**` tem dono).
   - [ ] `TransferStudentService`, `DeleteStudentService` e o contexto de matrícula (`EnrollmentController`).
5. **API:** não há endpoint para o app neste épico.
6. **JS (`frontend/pages/student/`)**
   - [ ] Formulário de aluno: quebrar o `validations.js` de 1327 linhas, reaproveitando os validators com teste do R3 (CPF, data, CEP, NIS, CNS, Educacenso); resolver os dois `validations.js` da mesma página.
   - [ ] `js/enrollment/form`, `transfer` e `health` para a mesma pasta; trocar os 6 `?r=` literais e o `var sedspEnable` por `jsonScript`.
7. **CSS (`sass/scss/pages/_student.scss`)**
   - [ ] Mover os 48 `style=` do `_form.php`, o `<style>` do `index.php` e trocar as 13 de Bootstrap 2.

## Fora de escopo
- Reescrever a integração SEDSP ou o módulo `sedsp` (R7-SEDSP).
- Editar models de `app/models/**` (arquivos com dono) e o aluno do `enrollmentonline` (R5-ENROLLMENTONLINE).

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/student.php`; o SQL usa bind; os formulários enviam CSRF.
- [ ] **GM:** cadastro, matrícula, transferência e exclusão têm golden master ou teste de caracterização.
- [ ] **SVC:** `actionCreate` e `actionUpdate` são magras e atômicas; a chamada ao SEDSP fica fora da transação; regras com 80% de cobertura.
- [ ] **JS:** formulário em `frontend/pages/student/`; zero `?r=` literais e zero globais novos na catraca.
- [ ] **CSS:** `_student.scss`, zero blocos `<style>` nas views e Bootstrap 2 em queda.

## Como verificar
```bash
php scripts/inventory-actions.php student
composer analyse && composer lint
composer test:unit -- --filter Student && composer test:integration -- --filter Student   # nomes dos scripts: confirmar no R1-TEST-01
npx playwright test tests/e2e/student
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._
