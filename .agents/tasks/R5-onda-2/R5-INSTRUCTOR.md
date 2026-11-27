---
id: R5-INSTRUCTOR
title: "instructor: levar o cadastro de professor ao critério de módulo pronto"
release: R5
module: instructor
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R5-REFINE-01]
size: G
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 8.6 (onda 2) e seção 7 (F4, item 4: instructor)"
write_scope:
  - app/modules/instructor/**
  - app/src/Instructor/**
  - tests/**/Instructor/**
  - frontend/pages/instructor/**
  - sass/scss/pages/_instructor.scss
  - app/config/public-routes/instructor.php
  - metrics/baseline/instructor.json
  - phpstan/baseline/instructor.neon
  - js/instructor/**
read_only:
  - app/models/**
  - app/modules/classdiary/**
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer analyse (app/src/Instructor no nível máximo, sem baseline)"
  - "scripts/inventory-actions.php instructor mostra zero actions críticas com SQL cru ou superglobal"
  - "Golden master de criar professor com usuário e do histórico de lecionação verde, com cobertura de pelo menos 80% no código novo"
  - "Catraca do módulo instructor: zero ?r= literais, zero globais novos e Bootstrap 2 em queda"
---

# R5-INSTRUCTOR: instructor: levar o cadastro de professor ao critério de módulo pronto

## Contexto
O `instructor` cadastra o **professor** (identificação, documentos e endereço, formação, dados variáveis do Educacenso), cria o **usuário de acesso** dele, emite o histórico de lecionação (impresso por ano) e traz uma tela de frequência do professor. Não há `docs/modules/instructor.md`; a descrição vem do código.

**Inventário do README (2026-11-27):** 1 controller, 1034 LOC, 21 actions, 0 sem `accessControl`, 38 superglobais, 16 `createCommand`, 7 JSON, 9 views, 2 `<script>`, **189 `style=`**, **83 Bootstrap 2**, JS em `js/instructor/` (fora do módulo), 0 CSS, 0 models, 1 usecase.

**Controller:** `DefaultController` (1034 LOC, 21 actions). `actionCreate` (≈L65–232) e `actionUpdate` (≈L233–377); impressão em `actionPrintHistory` (≈L841) e `actionPrintYearHistory` (≈L936); 16 `createCommand` espalhados (≈L347 a ≈L1016).
- **Senha padrão (risco a registrar):** `createUser` (≈L190–195) usa o CPF como `username` e `hashBirthdayDate` (≈L197–202) como senha: a data de nascimento sem as barras, em bcrypt (`PasswordHasher`, cost 10). A senha é previsível por quem conhece CPF e data de nascimento, e não há troca obrigatória no primeiro acesso (confirmar se existe algum campo para isso no `Users`). `createUserSchool` (≈L204–213) vincula a escola do usuário logado e atribui o papel `instructor`.
- **Frequência duplicada do `classdiary`:** `actionFrequency`, `actionGetFrequency` (≈L632), `actionGetFrequencyClassroom`, `actionGetFrequencyDisciplines`, `actionSaveFrequency` (≈L740), `actionSaveJustification` (≈L786) e `actionGetClassrooms` (≈L812) são uma segunda implementação, com SQL próprio (≈L638–828).
- **`accessRules` (≈L28–52):** tudo `'@'` sem perfil, e o `delete` (≈L378) sem `postOnly`; `saveEmails` está na lista mas não existe action.
- **Usecase morto ou quebrado:** `usecases/importInstructorUserUseCase.php` declara `private $x = new InstructorIdentification()` (inválido como valor padrão de propriedade) e chama `hashBirthdayDate`, que só existe no controller. Confirmar se algo o carrega.

**JS** (`js/instructor/`, registrado por `_form.php` ≈L10–13, `frequency.php` ≈L9 e `updateEmails.php` ≈L12): `form/validations.js` (**690**), `frequency.js` (166), `form/pagination.js` (139), `form/functions.js` (124), `form/_initialization.js` (88) e `form/updateEmails.js` (32), 1239 linhas e 8 `?r=` literais. O módulo não tem `resources/`.
**CSS:** nenhum arquivo. `style=`: `printHistory.php` (**71**), `printYearHistory.php` (**65**), `_history.php` (39), `_form.php` (7) e `frequency.php` (6); `<style>` nas duas telas de impressão.
**Sem `accessControl`:** nenhum. **Models locais:** nenhum (usa `InstructorIdentification`, `InstructorDocumentsAndAddress`, `InstructorVariableData` e `Users` de `app/models/`).

## Objetivo
Cadastro, usuário de acesso e histórico de professor passam a ter regra em `app/src/Instructor/`, sem a segunda implementação de frequência, e as telas em `frontend/pages/instructor/` e `_instructor.scss`.

## Passos
1. O card de refinamento ([R5-REFINE-01](R5-REFINE-01.md)) quebra este épico em cards executáveis. Os pacotes abaixo são a ordem e os alvos.
2. **SEC (acesso, SQL e CSRF)**
   - [ ] Definir com uma pessoa quem cadastra e exclui professor (hoje qualquer logado); `postOnly + delete`; limpar `saveEmails` do `accessRules`.
   - [ ] Conferir o bind nos 16 `createCommand`, em especial os de frequência (≈L638–828) e os dois históricos (≈L852 e ≈L941).
   - [ ] **Senha padrão:** registrar o risco e propor com uma pessoa a troca obrigatória no primeiro acesso ou senha aleatória com envio seguro. A decisão não é do agente; este card só implementa o que for aprovado.
   - [ ] CSRF nos formulários de cadastro e de e-mails.
3. **GM (fluxos a caracterizar antes de mexer)**
   - [ ] Criar professor com e sem usuário (CPF existente, usuário já existente), e atualizar.
   - [ ] Saída do `printHistory` e do `printYearHistory` (dados, não só HTML).
   - [ ] Frequência do professor: consulta, lançamento e justificativa, lado a lado com a do `classdiary`.
4. **SVC (contexto `app/src/Instructor`)**
   - [ ] `CreateInstructorService` e `UpdateInstructorService` com `TransactionManager` (hoje o usuário, a escola, o papel e as três fichas são gravados em etapas).
   - [ ] `InstructorUserProvisioner`, com o hash e a política de senha isolados em `Domain`.
   - [ ] `InstructorTeachingHistoryService` para os dois históricos.
   - [ ] **Frequência:** trocar a implementação própria pelos services do `ClassDiary` (R4-CLASSDIARY) e remover as actions duplicadas só depois do golden master.
5. **API:** não há endpoint para o app neste épico (frequência fica no R5-API-01).
6. **JS (`frontend/pages/instructor/`)**
   - [ ] Formulário do professor (`validations.js` de 690 linhas, com os validators do R3), `frequency` e `updateEmails`; trocar os 8 `?r=` pelo `url()` gerado.
   - [ ] Remover `js/instructor/` quando o smoke estiver verde.
7. **CSS (`sass/scss/pages/_instructor.scss`)**
   - [ ] Mover os 189 `style=` (comece pelas duas telas de impressão, que podem ir para `styles/print.scss`) e trocar as 83 ocorrências de Bootstrap 2.

## Fora de escopo
- Mudar regra de cadastro do Educacenso ou o conteúdo do histórico.
- Mudar o fluxo de login (card R4-API-01) ou a política geral de senhas do sistema.
- Alocação de professor em turma (`teaching_data`, do `classroom`) e o quadro de horário (R5-TIMESHEET).
- Editar models de `app/models/**` (arquivos com dono).

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/instructor.php`; o SQL usa bind; os formulários enviam CSRF; a decisão sobre a senha padrão está registrada e implementada.
- [ ] **GM:** criar, atualizar, históricos e frequência têm golden master ou teste de caracterização.
- [ ] **SVC:** `actionCreate` e `actionUpdate` são magras e atômicas; frequência usa os services do `ClassDiary`; regras com 80% de cobertura.
- [ ] **JS:** `js/instructor/` migrado para `frontend/pages/instructor/`; zero `?r=` literais e zero globais novos na catraca.
- [ ] **CSS:** `_instructor.scss`, zero blocos `<style>` nas views e Bootstrap 2 em queda (o maior do sistema, 83, deve cair).

## Como verificar
```bash
php scripts/inventory-actions.php instructor
composer analyse && composer lint
composer test:unit -- --filter Instructor && composer test:integration -- --filter Instructor   # nomes: confirmar no R1-TEST-01
npx playwright test tests/e2e/instructor
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._
