---
id: R5-API-01
title: "API v1: criar a consulta e o lançamento de frequência para o app, sobre os services do diário de classe"
release: R5
module: classdiary
type: api
skill: tag-add-api-endpoint
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R4-CLASSDIARY, R4-API-02]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B5, depois: notas e frequência) e seção 8.5 (sempre com uma pessoa: frequência)"
write_scope:
  - app/src/ClassDiary/**
  - app/modules/api/controllers/v1/ClassroomFrequencyController.php
  - app/modules/api/openapi/v1.yaml
  - tests/Unit/ClassDiary/**
  - tests/Integration/ClassDiary/**
  - tests/Api/Frequency/**
read_only:
  - app/modules/classdiary/**
  - app/modules/instructor/controllers/DefaultController.php
forbidden:
  - composer.json
  - app/config/main.php
  - app/config/api.php
  - app/models/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "Testes de contrato de GET e PUT de frequência verdes contra o v1.yaml"
  - "Os services de frequência são os mesmos usados pelas actions web do classdiary (sem segunda implementação)"
  - "Lançamento em turma bloqueada responde 403 e não grava"
---

# R5-API-01: API v1: criar a consulta e o lançamento de frequência para o app, sobre os services do diário de classe

## Contexto
O app mobile precisa consultar e lançar frequência (seção 6, B5). O fluxo do plano é spec, DTO, service, controller e teste de contrato, **sobre os services que o épico [R4-CLASSDIARY](../R4-onda-1/R4-CLASSDIARY.md) extraiu** para `app/src/ClassDiary/` (os nomes finais vêm dos cards filhos; hoje o épico prevê `SaveFrequencyService` e `GetFrequencyService`). Este card não deve reimplementar regra: se um service ainda lê `$_POST` ou sessão, volte ao épico.

O comportamento de hoje, para servir de referência:
- **Consulta** (`ClassesController::actionGetFrequency`, `app/modules/classdiary/controllers/ClassesController.php` ≈L683–756): por turma, ano, mês e (para etapa maior) disciplina, devolve alunos × aulas do quadro de horário (`Schedule` com `unavailable = 0`), com `fault`, `justification`, `date`, `available` (data passada) e `valid` (regra de matrícula por status, `verifyStatusEnrollment` ≈L884–917). Para professor, o filtro de substituto (`getInstructorFilter` ≈L758–766).
- **Lançamento** (`actionSaveFrequency` ≈L835–851 e `saveFrequency` ≈L853–882): com `studentId`, marca ou remove a falta de um aluno; sem `studentId`, marca ou remove a turma inteira. Etapa menor lança em todas as aulas do dia; etapa maior, só na aula informada.
- **Bloqueio:** `DiaryEntryLock::assertUnlocked($classroomId, DiaryEntryLock::FREQUENCY)` (`app/modules/classdiary/services/DiaryEntryLock.php` ≈L34–39) lança 403 quando a escola, a turma e o ano estão bloqueados.
- **Defeito conhecido:** `actionGetFrequency` lê `$classFault->justification` (≈L742) mesmo quando `$classFault` é `null` (aviso no PHP 8.3). O service novo precisa tratar o caso.
- **Cópia no `instructor`:** `instructor/DefaultController` tem `actionGetFrequency` (≈L632) e `actionSaveFrequency` (≈L740) próprios. Não mexer; registrar no Handoff.

**As actions web não conferem se a turma é do usuário**: a tela só lista as turmas permitidas. Na API essa conferência é obrigatória.

## Objetivo
Dois endpoints, `GET` e `PUT` de frequência de uma turma, sobre os services do `ClassDiary`, com autorização por turma e bloqueio de lançamento respeitado.

## Passos
1. **Spec primeiro.** Descreva no `v1.yaml`: `GET /api/v1/classrooms/{id}/frequency?month=&year=&discipline=` e `PUT /api/v1/classrooms/{id}/frequency` (corpo com `date`, `schedule`, `student_id` opcional e `fault`). Erros `application/problem+json`: 401, 403 (sem acesso ou bloqueado), 404, 422.
2. **Autorização por turma.** Um `ClassroomAccessPolicy` (em `app/src/ClassDiary/Domain` ou no contexto `Classroom` do R4-API-02):
   - professor: a turma precisa estar entre as dele no ano do token (mesma consulta do `GET /me/classrooms`);
   - demais perfis: a turma precisa ser da escola do token.
3. **Escopo:** exija o escopo de pessoa para frequência (o nome vem do R3-API-04; confirmar). Papéis vêm do `authManager` na hora.
4. **Controller v1 magro:** `Input::fromArray()`, service, resposta. `AccessDenied` e o bloqueio viram 403 pelo `ExceptionMapper`; nada de `Yii::app()->end()`.
5. **Idempotência:** lançar a mesma falta duas vezes não duplica registro em `class_faults`, e remover uma falta inexistente não falha.
6. **Testes:**
   - integração: etapa menor e maior; aluno e turma inteira; aluno transferido ou falecido (`valid = false`); turma bloqueada (403, nada gravado); turma de outra escola e professor de outra turma (403);
   - contrato dos dois endpoints em `tests/Api/Frequency/`;
   - o mesmo service passa nos testes das actions web do `classdiary` (golden master do R4).

## Fora de escopo
- Justificativas de falta e conteúdo ministrado.
- Frequência por aluno para responsáveis (`guardian`).
- Alterar as actions web do `classdiary` ou do `instructor` além de chamar o service.
- Mudar regra de frequência ou de bloqueio.

## Critérios de aceite
- [ ] Professor só consulta e lança em turmas dele; gestor só na escola do token.
- [ ] O PUT em turma com frequência bloqueada responde 403 e não grava nada.
- [ ] O lançamento é idempotente.
- [ ] A regra de matrícula (`valid`) é a mesma da tela web, comprovada por teste.
- [ ] O service é o mesmo das actions web; nenhum SQL nem ActiveRecord no controller v1.
- [ ] O `v1.yaml` passa no lint e os contratos validam as respostas.

## Como verificar
```bash
composer lint && composer analyse
composer test:integration -- --filter ClassDiary      # confirmar o nome do script no R1-TEST-01
composer test:api -- --filter Frequency

curl -i -X PUT "http://localhost/api/v1/classrooms/<id>/frequency" -H "Authorization: Bearer <token de professor de teste>" \
  -H "Content-Type: application/json" -d '{"date":"05/03/2026","schedule":1,"student_id":123,"fault":true}'
# esperado: 204; com a turma bloqueada na tela de bloqueio de lançamentos (`classdiary/entryLock`): 403 e nenhuma linha nova em class_faults
```
Frequência exige revisão humana (seção 8.5). Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._
