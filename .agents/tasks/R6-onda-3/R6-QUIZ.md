---
id: R6-QUIZ
title: "quiz (questionários): módulo pronto, com controle de acesso em todas as 23 actions"
release: R6
module: quiz
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R6-REFINE-01]
size: M
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achado 3), seção 6 (B2) e seção 8.6 (onda 3)"
write_scope:
  - app/modules/quiz/**
  - app/src/Quiz/**
  - tests/**/Quiz/**
  - frontend/pages/quiz/**
  - sass/scss/pages/_quiz.scss
  - app/config/public-routes/quiz.php
  - metrics/baseline/quiz.json
  - phpstan/baseline/quiz.neon
read_only:
  - app/models/StudentIdentification.php
  - themes/default/views/layouts/menus/**
forbidden:
  - composer.json
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer lint && composer analyse"
  - "composer test:unit -- --filter Quiz (confirmar o nome do script em R1)"
  - "php scripts/inventory-actions.php: nenhuma action do quiz sem proteção ou fora de public-routes/quiz.php"
  - "Catraca do módulo (metrics/baseline/quiz.json) sem aumento e com zero ?r= literais"
---

# R6-QUIZ: quiz (questionários): módulo pronto, com controle de acesso em todas as 23 actions

## Contexto
**Para que serve:** montar questionários (com grupos de perguntas, perguntas objetivas, de múltipla escolha e subjetivas, e opções) e registrar as respostas por aluno. O menu aparece para admin e superuser (`themes/default/views/layouts/menus/_admin_menu.php` ≈L240 e `_superuser_menu.php` ≈L258) e a rota amigável é `questionario/` (`app/config/main.php` ≈L179–181). Não há documento funcional em `docs/modules/`.

**Linha do inventário:** 1 controller, 499 LOC, 23 actions, 1 sem `accessControl`, 45 superglobais, 3 `createCommand`, 5 JSON, 18 views, 0 `<script>`, 7 `style=`, 24 Bootstrap 2, JS 1 arquivo/386 linhas, 1 CSS, 8 models, 0 services.

**Detalhamento (conferido em 2026-11-27)**
- `controllers/DefaultController.php` (499 LOC): `index`, `quiz`, `createQuiz`, `updateQuiz`, `deleteQuiz`, `group`/`createGroup`/`updateGroup`/`deleteGroup`, `questionGroup` e suas 3 variantes, `question` e suas 3 variantes, `createOption`/`updateOption`/`deleteOption`, `setQuizQuestion`, `unsetQuizQuestion` e `answer`. **Não há `filters()` nem `accessRules()`**: qualquer visitante chama qualquer action.
- `actionAnswer($quizId, $studentId)` (≈L440): grava respostas de **qualquer** aluno informado na URL, sem login e sem checar escola. O SQL usa bind e a gravação tem transação, o que é bom e deve ser preservado num service. É o fluxo mais crítico do módulo.
- `actionDeleteQuiz` (≈L63) chama `findByPk` e usa `$quiz->questions` sem tratar `null` (erro 500 em id inválido); as exclusões não exigem POST.
- `actionCreateQuiz` (≈L27) é um esqueleto: instancia `Quiz` e `QuizQuestion` e não grava nada. **confirmar:** se está em uso (a view de criação) ou é código morto.
- 45 leituras de `$_GET`/`$_POST` (`$_POST['FormQuestion'][$quizId]` sem validação de forma).
- JS: `resources/common/js/quiz.js` (386 linhas). CSS: `resources/common/css/layout.css` (24 linhas). Há 1 bloco `<style>` nas views; views com `style=`: `question/_form.php` (2) e 1 cada em `quiz/quiz.php`, `questiongroup/index.php`, `question/index.php` e `group/index.php`.
- Widgets: `widgets/QuizWidget.php` e `QuestionWidget.php`, com views em `widgets/views/` (`form`, `objective`, `objectiveMultiple`, `subjective`, `subjectiveText`).
- Models: `Answer`, `FormQuestion`, `Question`, `QuestionGroup`, `QuestionGroupQuestion`, `QuestionOption`, `Quiz` e `QuizQuestion`.

## Objetivo
Questionários só para quem tem papel para isso, com as respostas validadas e gravadas por um service testado.

## Passos
1. O card de refinamento (R6-REFINE-01) quebra este épico em cards executáveis `R6-QUIZ-<nn>`.
2. **SEC**
   - [ ] Criar `filters()` com `accessControl` e `accessRules()`. Montar e excluir questionários: admin (decisão de produto sobre outros papéis). Responder: o papel que preenche, hoje desconhecido (**confirmar**).
   - [ ] Em `answer`, validar que o aluno pertence à escola da sessão e que o `$quizId` existe.
   - [ ] Exigir POST e CSRF nas actions `create*`, `update*`, `delete*`, `setQuizQuestion` e `unsetQuizQuestion`; tratar `findByPk` nulo com 404.
3. **GM**
   - [ ] Caracterizar `answer` (respostas objetivas, múltiplas e subjetivas; aluno que já respondeu) e `setQuizQuestion`/`unsetQuizQuestion`.
4. **SVC**
   - [ ] `app/src/Quiz/Application/`: `AnswerQuizService` (com `TransactionManager`), `SaveQuestionService`, `SaveQuizService`; regra "o aluno já respondeu" no `Domain`.
   - [ ] Validar a forma de `FormQuestion` num DTO de entrada.
5. **API:** nenhuma prevista.
6. **JS:** `quiz.js` para `frontend/pages/quiz/`, dividido por tela (montagem e resposta), com `routes/` gerado.
7. **CSS:** `sass/scss/pages/_quiz.scss`, mover os `style=` e o bloco `<style>` e trocar os 24 usos de Bootstrap 2.

## Fora de escopo
- Relatórios de respostas e exportações novas.
- Integrar o questionário a outros módulos (matrícula, saúde).

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/quiz.php`; o SQL usa bind; os formulários enviam CSRF; não há segredo no código.
- [ ] **GM:** o fluxo de resposta e a montagem têm teste de caracterização.
- [ ] **SVC:** as actions críticas são magras; as regras estão em `app/src/Quiz/` com pelo menos 80% de cobertura.
- [ ] **API:** não se aplica.
- [ ] **JS:** páginas em `frontend/pages/quiz/`; catraca com zero `?r=` literais e zero globais novos.
- [ ] **CSS:** estilos em `sass/scss/pages/_quiz.scss`, sem blocos `<style>`, Bootstrap 2 em zero ou em queda.

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter Quiz       # confirmar scripts em R1
php scripts/inventory-actions.php         # quiz sem action desprotegida
scripts/frontend-metrics                  # catraca; confirmar o comando em R1
curl -i "http://localhost/?r=quiz/default/answer&quizId=1&studentId=1"   # visitante: 302 para o login
```
Testes de navegador (montar e responder um questionário) seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._
