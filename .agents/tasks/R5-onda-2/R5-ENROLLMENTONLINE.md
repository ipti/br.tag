---
id: R5-ENROLLMENTONLINE
title: "enrollmentonline: levar a matrícula online ao critério de módulo pronto, preservando as rotas públicas legítimas"
release: R5
module: enrollmentonline
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R5-REFINE-01]
size: G
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 8.6 (onda 2), seção 7 (F2: jQuery explícito no enrollmentonline)"
write_scope:
  - app/modules/enrollmentonline/**
  - app/src/EnrollmentOnline/**
  - tests/**/EnrollmentOnline/**
  - frontend/pages/enrollmentonline/**
  - sass/scss/pages/_enrollmentonline.scss
  - app/config/public-routes/enrollmentonline.php
  - metrics/baseline/enrollmentonline.json
  - phpstan/baseline/enrollmentonline.neon
read_only:
  - app/models/**
  - app/controllers/SiteController.php
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer analyse (app/src/EnrollmentOnline no nível máximo, sem baseline)"
  - "app/config/public-routes/enrollmentonline.php lista exatamente as rotas públicas legítimas; as demais exigem login"
  - "Smoke da pré-matrícula pública (criar, listar eventos, buscar cidades e escolas) verde"
  - "Catraca do módulo enrollmentonline: zero ?r= literais, zero globais novos e Bootstrap 2 em queda"
---

# R5-ENROLLMENTONLINE: enrollmentonline: levar a matrícula online ao critério de módulo pronto, preservando as rotas públicas legítimas

## Contexto
O `enrollmentonline` recebe a **pré-matrícula feita pelo responsável**, sem login: o responsável escolhe o evento, a etapa e até três escolas, e o sistema cria o aluno em pré-matrícula e o **usuário do responsável** (papel `guardian`, login pelo CPF). Depois a escola vê os pedidos, **confirma** (e escolhe a turma) ou **rejeita**. O responsável acompanha em `StudentList` (o `SiteController` o redireciona para lá depois do login, ≈L109–110). Não há `docs/modules/enrollmentonline.md`; a descrição vem do código.

**Inventário do README (2026-11-27):** 3 controllers, 767 LOC, 19 actions, 1 sem `accessControl`, 16 superglobais, 0 `createCommand`, 2 JSON, 12 views, 8 `style=`, 9 Bootstrap 2, 3 JS (412 LOC), 0 CSS, 4 models, 1 service/repositório.

**Controllers (LOC; actions):** `EnrollmentonlinestudentidentificationController` (405; 11), `EnrollmentonlinepreenrollmenteventonlineController` (346; 7) e `DefaultController` (16; 1, **sem `accessControl`**).
- **Rotas públicas legítimas (sem login):** `index`, `view` e `getPreEnrollmentStages` do evento; `index`, `view`, `create`, `getCities` e `getSchools` do aluno (≈L36–40). As ações de apoio `getCities` e `getSchools` também ficam fora do 401 por AJAX (`beforeAction` ≈L74–81). Essas rotas **precisam continuar públicas**; o objetivo é listá-las em `app/config/public-routes/enrollmentonline.php` e fechar o resto.
- **Sem `deny` final:** `EnrollmentonlinestudentidentificationController::accessRules` tem o `deny` **comentado** (≈L51–54). Ações que não estão em nenhuma regra, como `actionRenderStudentTable` (≈L382), ficam liberadas.
- **Confirmar e rejeitar sem dono:** `actionConfirmEnrollment` (≈L148) e `actionRejectEnrollment` (≈L157) aceitam qualquer `enrollmentId` e, na confirmação, um `classroom_fk` postado, para qualquer usuário logado (inclusive um responsável). Não conferem perfil, escola nem se a turma é da escola.
- **`actionIndex($eventId)` (≈L274)** é público (`'*'`) e lista pré-matrículas (alunos e responsáveis) filtradas pela **escola da sessão**. Para visitante a escola é nula, mas um responsável logado recebe a primeira escola ativa (`UserIdentity` ≈L30–35). **Confirmar** o vazamento.
- **Criação pública do usuário:** `EnrollmentonlinestudentidentificationRepository::savePreEnrollment` (≈L16–60, com transação) lê `$_POST['school_1'..'school_3']` e `$_POST['password']` dentro do repositório. `createUser` (≈L65–91): se já existe `Users` com o CPF do responsável, **vincula o aluno a esse usuário sem autenticar** (≈L67–72), inclusive professor ou gestor, cujo login também é o CPF. O controller mostra o `username` na mensagem de sucesso (≈L129). Sem captcha nem limite de tentativas.
- **`DefaultController::actionIndex`** (16 linhas) lista turmas por `Classroom::model()->search()`; descobrir se é rota usada.

**JS** (`app/modules/enrollmentonline/resources/`): `functions.js` (246, chama `initDatatable()` ≈L220), `validations.js` (98) e `pagination.js` (68), 7 `?r=` literais. Registrados por `_form.php` e `index.php` do aluno.
**Layout próprio** (`layouts/enrollmentonline.php`): carrega `bootstrap.min.css`, `template.css`, `template2.css`, `select2.css`, `jquery-ui-1.9.2`, `common.js`, `util.js` e o Hotjar (≈L31–46) **sem carregar jQuery explicitamente** (o jQuery vem do `clientScript` do Yii, depois dessas tags). A F2 do plano manda carregá-lo.
**CSS:** nenhum arquivo. `style=`: `studentidentification/_form.php` (6), `studentlist.php` e `preenrollmenteventonline/_form.php` (1 cada). **Models locais:** `EnrollmentOnlineStudentIdentification`, `...EnrollmentSolicitation`, `...PreEnrollmentEventOnline` e `...EventVsEdcensoStage`.

## Objetivo
A matrícula online pública continua funcionando, e todo o resto exige login e perfil. Criar pré-matrícula, confirmar e rejeitar passam a ser regra testada em `app/src/EnrollmentOnline/`, com o usuário do responsável criado de forma segura.

## Passos
1. O card de refinamento ([R5-REFINE-01](R5-REFINE-01.md)) quebra este épico em cards executáveis. Os pacotes abaixo são a ordem e os alvos.
2. **SEC (acesso, SQL e CSRF)**
   - [ ] Criar `public-routes/enrollmentonline.php` com **só** as rotas públicas legítimas acima e restaurar o `deny` final do `EnrollmentonlinestudentidentificationController`; dar `accessControl` ao `DefaultController`.
   - [ ] `confirmEnrollment`, `rejectEnrollment` e `renderStudentTable`: exigir perfil de gestão da escola e validar que o pedido e a turma pertencem à escola do usuário.
   - [ ] `actionIndex($eventId)` do aluno: exigir login e perfil, ou confirmar que não vaza dados.
   - [ ] Vínculo por CPF no `createUser`: **decisão de uma pessoa** (confirmação por e-mail ou telefone, ou outra prova de posse); não decidir sozinho.
   - [ ] Limite de tentativas, captcha e CSRF em `create` (o token precisa funcionar sem sessão autenticada).
3. **GM (fluxos a caracterizar antes de mexer)**
   - [ ] Pré-matrícula com responsável novo e com responsável já existente; uma, duas e três escolas.
   - [ ] Confirmar com turma e rejeitar; regra de "já aceito em outra escola" (consultas das ≈L288–291 e ≈L397–399).
   - [ ] `StudentList` do responsável e o redirecionamento do login.
4. **SVC (contexto `app/src/EnrollmentOnline`)**
   - [ ] `SubmitPreEnrollmentService`, que recebe `school_1..3` e a senha por `Input` (sem `$_POST` no repositório) e já usa transação.
   - [ ] `ConfirmEnrollmentService` e `RejectEnrollmentService`, com a regra de escola; a lista de pedidos por escola em repositório.
5. **API:** não há endpoint para o app neste épico.
6. **JS (`frontend/pages/enrollmentonline/`)**
   - [ ] `functions.js`, `validations.js` e `pagination.js`; trocar os 7 `?r=` pelo `url()` gerado (a página pública não tem a ponte; confirmar como o layout próprio carrega o bundle).
   - [ ] Carregar o jQuery explicitamente no layout próprio (`app/modules/enrollmentonline/layouts/`, dentro do `write_scope`).
7. **CSS (`sass/scss/pages/_enrollmentonline.scss`):** mover os 8 `style=`, trocar as 9 de Bootstrap 2 e decidir se o layout deixa de carregar `template.css` e `template2.css` (F5).

## Fora de escopo
- Reescrever o layout público ou tirar o Hotjar (decisão de produto e de privacidade; registrar).
- Mudar regra de vagas, prazos de evento ou critérios de matrícula.
- O cadastro de aluno do `student`, o `SiteController` e `app/models/**` (arquivos com dono).

## Critérios de aceite
- [ ] **SEC:** apenas as rotas listadas em `public-routes/enrollmentonline.php` respondem sem login; confirmar, rejeitar e listar exigem perfil e escola; o SQL usa bind; o formulário público envia CSRF.
- [ ] A pré-matrícula pública (criar, eventos, cidades, escolas) continua funcionando.
- [ ] **GM:** os fluxos de pré-matrícula, confirmação e rejeição têm golden master ou teste de caracterização.
- [ ] **SVC:** nenhuma superglobal no repositório; criação atômica; regras com 80% de cobertura.
- [ ] **JS e CSS:** páginas em `frontend/pages/enrollmentonline/`, `_enrollmentonline.scss`, jQuery carregado explicitamente, zero `?r=` literais e Bootstrap 2 em queda.

## Como verificar
```bash
# visitante: rotas públicas legítimas respondem; as demais redirecionam ao login
curl -i "http://localhost/?r=enrollmentonline/enrollmentonlinestudentidentification/create"          # esperado: 200
curl -i -X POST "http://localhost/?r=enrollmentonline/enrollmentonlinestudentidentification/confirmEnrollment" -d "enrollmentId=1"   # esperado: 302 para site/login

php scripts/inventory-actions.php enrollmentonline
composer analyse && composer lint
composer test:integration -- --filter EnrollmentOnline    # confirmar o nome do script no R1-TEST-01
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._
