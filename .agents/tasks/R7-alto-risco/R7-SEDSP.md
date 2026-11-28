---
id: R7-SEDSP
title: "sedsp: contrato da integração SED-SP, autenticação segura e sincronização fora do model"
release: R7
module: sedsp
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R7-REFINE-01]
size: G
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achados 3 e 7), seção 8.6 (alto risco) e seção 13 (smoke do sedsp com Guzzle)"
write_scope:
  - app/modules/sedsp/**
  - app/src/SedSp/**
  - tests/**/SedSp/**
  - frontend/pages/sedsp/**
  - sass/scss/pages/_sedsp.scss
  - app/config/public-routes/sedsp.php
  - metrics/baseline/sedsp.json
  - phpstan/baseline/sedsp.neon
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
  - "Testes de contrato dos datasources com respostas gravadas da SED-SP (composer test:integration -- --filter SedSp; confirmar o script em R1)"
  - "php scripts/inventory-actions.php: nenhuma action do sedsp sem proteção ou fora de public-routes/sedsp.php"
---

# R7-SEDSP: sedsp: contrato da integração SED-SP, autenticação segura e sincronização fora do model

## Contexto
**Uma pessoa lidera e o agente apoia.** Responsável: _a definir no R7-REFINE-01_. A integração fala com um sistema externo do governo de São Paulo; não há ambiente de teste previsível, então o contrato precisa de respostas gravadas.
**Para que serve:** integra o TAG à SED-SP (sistema estadual): login na API, importação de escolas, turmas, alunos e matrículas, geração e vínculo do RA (registro do aluno) e envio de alunos e turmas.

**Linha do inventário:** 1 controller (354 LOC, 15 actions, sem `accessControl`), 9 superglobais, 2 `createCommand`, 0 JSON, 2 views, 3 `<script>`, 40 `style=`, 6 Bootstrap 2, JS 1 arquivo/104 linhas, **81 models** (DTOs `In*`/`Out*`) e **32 usecases**.

**Detalhamento (conferido em 2026-11-27)**
- `controllers/DefaultController.php`: `index`, `manageRA`, `addStudentWithRA`, `addClassroom`, `addSchool`, `login` (≈L184), `syncSchoolClassrooms`, `create`, `generateRA`, `createRA`, `importFullSchool` (≈L228), `importStudentRA`, `updateStudentFromSedsp` (≈L279), `importFullStudentsByClasses` e `importClassroomFromSedsp`. **Sem `filters()`**: qualquer visitante chega às actions.
- **Autenticação:** `usecases/LoginUseCase.php` grava o token da SED num cookie `SED_TOKEN` (≈L37–41); `datasources/sed/SedDataSource.php` lê esse cookie em todo request para montar o `Authorization: Bearer` e **não trata cookie ausente** (erro em vez de pedir novo login). O cookie deve ter flags de segurança e vida curta (**confirmar:** definição do `CHttpCookie`).
- **Credencial fixa:** `datasources/sed/UsuarioSEDDataSource.php` (≈L9) envia um `Authorization: Basic ...` literal no código. O R0-INFRA-01 trata a rotação e a saída para variável de ambiente; este épico garante que o código final não carrega segredo. A URL base vem de `SEDSP_URL` e, se faltar, cai na **homologação** (`SedDataSource`, `base_uri`).
- **Datasources Guzzle** em `datasources/sed/` (`BasicData`, `ClassStudentsRelation`, `Classroom`, `Enrollment`, `School`, `Student`) com timeout de 10 s e sem retentativa.
- **Duplicatas de DTO:** `models/Student/InInscricao.php` e `models/Enrollment/InInscricao.php`; `models/Student/InNivelEnsino.php` e `models/Classroom/InNivelEnsino.php` (mesmo nome de classe em pastas diferentes, resolvido só pela ordem de import do módulo).
- **Sincronização dentro do model:** `app/models/StudentIdentification.php` (552 linhas), método `syncStudentWithSED` (≈L341–486), chama datasources, mappers e grava o status de sincronização. Tirar isso do model exige card de contrato, porque `app/models/**` é arquivo com dono.
- **Testes:** um único teste, `usecases/tests/GetEscolasFromSEDUseCaseTest.php` (749 bytes).
- Consumidores fora do módulo: `app/models/{Classroom,StudentEnrollment,StudentIdentification,SedspSchoolUnities}.php`, módulos `classroom` e `student` e o módulo `wizard`.

## Objetivo
A integração SED-SP tem contrato testado com respostas gravadas, autenticação sem segredo no código e a sincronização fora do model.

## Passos
1. O card de refinamento (R7-REFINE-01) quebra este épico em cards `R7-SEDSP-<nn>`.
2. **SEC**
   - [ ] `filters()` com `accessControl` na `DefaultController`; definir os papéis (secretaria e admin).
   - [ ] Tratar `SED_TOKEN` ausente ou expirado (novo login) e emitir o cookie com `httpOnly`, `secure` e validade curta; avaliar a sessão em vez de cookie.
   - [ ] Confirmar que o `Basic` literal saiu do código (R0-INFRA-01) e que a URL de homologação não é o padrão em produção.
3. **GM**
   - [ ] Gravar respostas reais anonimizadas da SED-SP e escrever testes de contrato dos datasources e mappers (import de escola, turma, aluno e RA).
   - [ ] Caracterizar `syncStudentWithSED` (aluno novo, existente, erro 401) antes de mover.
4. **SVC**
   - [ ] `app/src/SedSp/`: cliente HTTP atrás de interface (Guzzle em `Infrastructure`), `SyncStudentWithSedService`, `ImportSchoolFromSedService`, `GenerateRaService`; mappers como classes puras.
   - [ ] Unificar os DTOs duplicados (`InInscricao`, `InNivelEnsino`) em um só.
   - [ ] Card de contrato para retirar `syncStudentWithSED` de `StudentIdentification` mantendo um delegador.
5. **API:** nenhuma prevista.
6. **JS:** o arquivo de 104 linhas e os `<script>` das views para `frontend/pages/sedsp/`.
7. **CSS:** `sass/scss/pages/_sedsp.scss`; mover os 40 `style=` e trocar os 6 usos de Bootstrap 2.

## Fora de escopo
- Mudar as regras de mapeamento de etapa e modalidade entre TAG e SED.

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/sedsp.php`; não há credencial no código; o token tem tratamento de ausência e expiração.
- [ ] **GM:** os datasources têm teste de contrato com respostas gravadas, e a sincronização tem teste de caracterização.
- [ ] **SVC:** as actions são magras; as regras estão em `app/src/SedSp/` com pelo menos 80% de cobertura; um só `InInscricao` e um só `InNivelEnsino`.
- [ ] **API:** não se aplica.
- [ ] **JS e CSS:** páginas em `frontend/pages/sedsp/` e `_sedsp.scss`; catraca sem `?r=` literais.
- [ ] A pessoa responsável aprovou cada PR.

## Como verificar
```bash
composer lint && composer analyse
composer test:integration -- --filter SedSp   # confirmar o script em R1
php scripts/inventory-actions.php
git grep -n "Basic " -- app/modules/sedsp
curl -i "http://localhost/?r=sedsp/default/importFullSchool"   # visitante: 302 para o login
```
Testes de navegador contra a SED-SP real seguem `.agents/rules/e2e-testing.md` e dependem de um ambiente de homologação: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._
