---
id: R5-API-02
title: "API v1: criar a consulta e o lançamento de notas para o app, sobre os services de notas do R2"
release: R5
module: grades
type: api
skill: tag-add-api-endpoint
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R2-GRADES-07, R4-API-02]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B3, passos do piloto; B5, depois: notas e frequência) e seção 8.5"
write_scope:
  - app/src/Grades/**
  - app/modules/api/controllers/v1/ClassroomGradesController.php
  - app/modules/api/openapi/v1.yaml
  - tests/Unit/Grades/**
  - tests/Integration/Grades/**
  - tests/Api/Grades/**
read_only:
  - app/modules/grades/controllers/DefaultController.php
  - app/modules/grades/usecases/**
  - app/modules/classdiary/services/DiaryEntryLock.php
  - app/components/ClassroomHelper.php
forbidden:
  - composer.json
  - app/config/main.php
  - app/config/api.php
  - app/models/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "Testes de contrato de disciplinas, notas (GET) e notas (PUT) verdes contra o v1.yaml"
  - "O golden master de notas do R2 continua verde, e o PUT grava as mesmas linhas de grade_results que a tela web"
  - "Lançamento em turma bloqueada responde 403 e não grava"
---

# R5-API-02: API v1: criar a consulta e o lançamento de notas para o app, sobre os services de notas do R2

## Contexto
Depois da frequência (R5-API-01), o app precisa consultar e lançar notas. A seção 6 (B5) manda construir sobre os services das ondas da B3. Os services de notas vêm dos cards R2-GRADES-05 a 07 (pela ordem do plano na B3: `RecalculateFinalMedia`, `SaveGradesRelease` e fechamento de turma; **confirmar nos cards** os nomes e o que cada um cobre). Se a gravação de nota ainda depender do `DefaultController`, não reimplemente: volte ao R2.

O comportamento web de referência está em `app/modules/grades/controllers/DefaultController.php`:
- **Disciplinas** (`actionGetDisciplines` ≈L121–154): professor recebe as disciplinas que ele leciona na turma (`teaching_matrixes`, `requires_exam = 1`); os demais, as da matriz curricular da etapa e do ano. A resposta é HTML (`<option>`), com rótulos de `ClassroomHelper::classroomDisciplineLabelArray()`. A API devolve JSON.
- **Unidades** (`actionGetUnities` ≈L156–188): `GradeUnity` pelas regras de nota da turma (`classroom_vs_grade_rules`).
- **Consulta** (`actionGetGrades` ≈L661–679): exige `classroom`, `discipline` e `unity` (400 sem eles) e delega ao `GetStudentGradesByDisciplineUsecase` (`app/modules/grades/usecases/`, o maior usecase do módulo).
- **Lançamento** (`actionSaveGrades` ≈L570–588 e `saveGrades` ≈L590–659): checa `DiaryEntryLock::assertUnlocked(..., GRADE)` e `assertStudentsBelongToClassroom`; grava as `Grade` (nota ou conceito) e as recuperações parciais dentro de **uma transação**, recalcula `grade_results` por `saveGradeResults` e registra o lote no log. `UndefinedRuleTypeException` e outros erros viram 500.

**As actions web não conferem se a turma é do usuário**; a tela só lista as permitidas. Na API essa conferência é obrigatória. O `GetModalities` do mesmo controller (≈L55–65) concatena `$_POST['Stage']` no SQL; não faz parte da API e deve estar coberto pelo R0-SQL-03 (confirmar).

## Objetivo
Três endpoints de notas por turma, `GET` de disciplinas, `GET` de notas e `PUT` de notas, sobre os services do `Grades`, com autorização por turma e bloqueio de lançamento respeitado.

## Passos
1. **Spec primeiro.** Descreva no `v1.yaml`:
   - `GET /api/v1/classrooms/{id}/disciplines`;
   - `GET /api/v1/classrooms/{id}/grades?discipline=&unity=`;
   - `PUT /api/v1/classrooms/{id}/grades` (corpo: disciplina, alunos com `enrollmentId`, `grades[]`, `partialRecoveriesGrades[]`, indicador de conceito).
   - Erros `application/problem+json`: 401, 403, 404, 422.
2. **Autorização por turma:** reutilize o `ClassroomAccessPolicy` do R5-API-01 (professor só nas turmas dele; demais, só na escola do token). Em `disciplines`, o professor só vê as disciplinas que leciona na turma.
3. **Services e DTOs:** crie `Input` e `Result` para consulta e lançamento no contexto `Grades`, chamando os services do R2. O lançamento mantém a transação e a ordem: valida bloqueio, valida matrículas da turma, grava, recalcula, registra.
4. **Validação de entrada:** `Input::fromArray()` com erros por campo (422): nota numérica na faixa da regra, `enrollmentId` inteiro, `id` de nota pertencente à matrícula (hoje `assertStudentsBelongToClassroom`).
5. **Controller v1 magro,** com o `ExceptionMapper`: bloqueio e acesso viram 403, regra de cálculo não definida vira 422 (hoje 500), o resto 500.
6. **Testes:**
   - integração: nota e conceito; recuperação parcial; recálculo (compare com o golden master do R2); turma bloqueada (403, nada gravado); nota de outra matrícula (400/422); turma de outra escola (403);
   - contrato dos três endpoints em `tests/Api/Grades/`;
   - paridade: o mesmo payload via tela web e via API produz as mesmas linhas de `grade` e `grade_results`.

## Fora de escopo
- Boletim (`reportCard`), lançamento por unidade (`gradesRelease`), fechamento de turma e médias finais em lote.
- Estrutura de notas (`StructureController`) e conceitos (`ConceptController`).
- Mudar regra de nota, média ou aprovação (o agente não decide; divergência do golden master vai para uma pessoa).
- Alterar as actions web além de chamar o service.

## Critérios de aceite
- [ ] Professor só consulta e lança notas de turmas e disciplinas dele; gestor só na escola do token.
- [ ] O PUT em turma com lançamento de nota bloqueado responde 403 e não grava.
- [ ] O PUT é atômico: falha no recálculo desfaz as notas da requisição.
- [ ] O resultado em `grade_results` é idêntico ao da tela web para o mesmo payload (golden master verde).
- [ ] Regra de cálculo ausente responde 422 com a mensagem, não 500.
- [ ] O `v1.yaml` passa no lint e os contratos validam as respostas.

## Como verificar
```bash
composer lint && composer analyse
composer test:integration -- --filter GradesGoldenMaster   # confirmar os nomes no R2 e no R1-TEST-01
composer test:api -- --filter Grades

curl -i "http://localhost/api/v1/classrooms/<id>/grades?discipline=<d>&unity=<u>" -H "Authorization: Bearer <token de professor de teste>"
# esperado: 200 com alunos, notas e regra; turma de outra escola: 403
```
Notas exigem revisão humana (seção 8.5). Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._
