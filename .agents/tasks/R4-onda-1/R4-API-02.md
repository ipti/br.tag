---
id: R4-API-02
title: "API v1: criar GET /me e GET /me/classrooms (turmas do professor ou da escola e do ano do token)"
release: R4
module: plataforma
type: api
skill: tag-add-api-endpoint
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R4-API-01]
size: M
risk: medio
human_review: recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B5, GET me e GET me/classrooms)"
write_scope:
  - app/src/Classroom/**
  - app/src/Shared/Auth/**
  - app/modules/api/controllers/v1/MeController.php
  - app/modules/api/openapi/v1.yaml
  - tests/Unit/Classroom/**
  - tests/Integration/Classroom/**
  - tests/Api/Me/**
read_only:
  - app/modules/grades/controllers/DefaultController.php
  - app/modules/classdiary/services/ClassesService.php
  - app/components/utils/TagUtils.php
forbidden:
  - composer.json
  - app/config/main.php
  - app/models/**
  - app/modules/grades/**
  - app/modules/classdiary/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "Testes de contrato de GET /me e GET /me/classrooms verdes contra o v1.yaml"
  - "Testes de integração: professor vê só as próprias turmas; gestor vê as turmas da escola e do ano do token"
---

# R4-API-02: API v1: criar GET /me e GET /me/classrooms (turmas do professor ou da escola e do ano do token)

## Contexto
Depois do login (R4-API-01), o app precisa saber quem é a pessoa e em quais turmas ela trabalha. A seção 6 (B5) prevê `GET me` e `GET me/classrooms`.

**A consulta de turmas já existe no web**, em `app/modules/grades/controllers/DefaultController.php`, `actionGrades()` (≈L67–88), e se repete em `actionReportCard` (≈L190) e `actionGradesRelease` (≈L215):
- **Professor** (`checkAccess('instructor', loginInfos->id)`, ≈L72): `Classroom` com `join instructor_teaching_data` (`classroom_id_fk = c.id`) e `join instructor_identification` (`users_fk = :users_fk`), filtrada por `c.school_year` e `c.school_inep_fk`, ordenada por `name` (≈L73–82).
- **Demais perfis** (≈L84): todas as turmas da escola e do ano da sessão, por `name`.
- **Confirmar:** o `join` com `instructor_teaching_data` não usa `DISTINCT`. Um professor com mais de uma linha de ensino na mesma turma pode vir duplicado. A API precisa devolver turmas únicas.

Há uma segunda consulta, no `classdiary`, em `app/modules/classdiary/services/ClassesService.php`: `getClassrooms()` (≈L5–12, escola e ano), `getClassroomsInstructor()` (≈L14–61, com disciplina e etapa, filtrando `esvm.unified_frequency` e **sem** filtrar por escola) e `getMinorSchoolingClassrooms()` (≈L63–76). Elas servem à frequência (R5-API-01), não a este card. Registre no Handoff a diferença de critério entre as duas famílias (a do `grades` filtra por escola; a do `classdiary` não).

No web o papel vem do `authManager` pelo **id numérico** (`TagUtils::isInstructor()`, `TagUtils.php` ≈L5–8). Na API vale o mesmo: papéis não vão no token, e `Yii::app()->user->checkAccess()` não deve ser usado (seção 2 do plano, achado 6).

## Objetivo
`GET /api/v1/me` devolve a identidade e o contexto do token, e `GET /api/v1/me/classrooms` devolve as turmas visíveis para essa pessoa na escola e no ano do token.

## Passos
1. **Spec primeiro.** Descreva no `v1.yaml` os dois endpoints e os schemas `Me` e `ClassroomSummary`, com erros `application/problem+json` (401, 403).
2. **`GET /me`:** devolva `id`, `name`, `username`, os papéis (consultados no `authManager` na hora), a escola atual (`inep_id` e `name`), o ano e a lista de escolas permitidas, a mesma do `auth/context`. **Nunca** serialize o `Users` inteiro: o hash da senha fica de fora.
3. **Crie o contexto `app/src/Classroom`:**
   - `Application/ListMyClassroomsService`, com `Input` (userId, papéis, escola, ano) e `Result` (lista de DTOs);
   - `Infrastructure/Yii/ClassroomQuery`, que reproduz as duas consultas do `grades` com `DISTINCT` e bind de parâmetros;
   - o service não lê sessão nem `Yii::app()`; recebe o `UserContext`.
4. **`GET /me/classrooms`:**
   - professor: só as turmas em que ele leciona na escola e no ano do token;
   - gestor, coordenador, leitor e administrador: todas as turmas da escola e do ano do token;
   - campos do DTO: `id`, `name`, `school_inep_fk`, `school_year`, `edcenso_stage_vs_modality_fk` e `turn`. Confirme os nomes das colunas no model `Classroom` antes de fixar o schema.
5. **Escopo e papel:** exija o escopo de pessoa no token (o nome vem do R3-API-04; confirmar) e responda 403 se a escola do token não pertence às permitidas.
6. **Testes:**
   - integração em `tests/Integration/Classroom/`, com um professor de duas turmas, outro de uma e um gestor, e uma turma de outra escola e uma de outro ano que não podem aparecer;
   - teste do caso de duplicidade (professor com duas linhas de ensino na mesma turma);
   - contrato de `/me` e `/me/classrooms` em `tests/Api/Me/`.

## Fora de escopo
- Disciplinas e etapas por turma para frequência (R5-API-01) e paginação ou busca de turmas.
- Trocar as consultas do `grades` ou do `classdiary` para o service novo (cards dos épicos).
- Alterar o JWT ou o `auth/context` (R4-API-01).

## Critérios de aceite
- [ ] Professor recebe só as próprias turmas da escola e do ano do token, sem duplicidade.
- [ ] Gestor recebe todas as turmas da escola e do ano do token.
- [ ] `/me` não expõe hash de senha nem campos internos do `Users`.
- [ ] Os papéis vêm do `authManager` pelo id numérico, e o token não os carrega.
- [ ] O service não usa `Yii::app()`, superglobais nem ActiveRecord na camada `Application`, e o PHPStan do `app/src` passa.
- [ ] O `v1.yaml` passa no lint e os contratos validam as respostas.

## Como verificar
```bash
composer lint && composer analyse
composer test:integration -- --filter Classroom      # confirmar o nome do script no R1-TEST-01
composer test:api -- --filter Me

curl -i "http://localhost/api/v1/me/classrooms" -H "Authorization: Bearer <token de professor de teste>"
# esperado: 200 com as turmas do professor; com o token de gestor, as da escola
```
Compare a lista com a tela de notas (`?r=grades/default/grades`) usando o mesmo usuário: os nomes precisam coincidir. Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._
