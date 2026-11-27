---
id: R3-SEC-01
title: "AccessPolicy: ligar o modo enforce depois da janela em modo log"
release: R3
module: plataforma
type: seguranca
skill: tag-secure-controller
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R2-SEC-02]
size: P
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B2, negar por padrão), seção 13 (riscos) e seção 14 (verificação B0/B2)"
write_scope:
  - app/config/public-routes/**
  - tests/**
read_only:
  - app/src/Shared/Infrastructure/Yii/**
  - app/components/Controller.php
  - app/modules/*/controllers/**
  - themes/default/views/layouts/menus/**
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer analyse"
  - "composer test:integration -- --filter AccessPolicy"
  - "Smoke com curl da seção Como verificar, rodado com ACCESS_POLICY_MODE=enforce em homologação"
  - "Playwright: login, troca de escola/ano e um fluxo AJAX com sessão expirada passam com enforce ligado"
---

# R3-SEC-01: AccessPolicy: ligar o modo enforce depois da janela em modo log

## Contexto
O plano (seção 6, B2) liga a `AccessPolicy` primeiro em modo `log`, por cerca de 2 semanas, e só depois em `enforce`. O card R2-SEC-02 define a política (`app/src/Shared/Infrastructure/Yii/AccessPolicy.php`, com o carregador `PublicRoutes.php`), o gancho em `Controller::runActionWithFilters()` e a variável `ACCESS_POLICY_MODE=log|enforce` (padrão `log`; valor inválido vale `log`). Hoje `app/components/Controller.php` só tem `init()`, `beforeAction()` e `afterAction()` (≈L33–L98): **confirmar** no Handoff do R2-SEC-02 que foi entregue como descrito.

Fatos que já se conhecem:
- Em modo log, cada acesso de visitante a rota não pública gera `Yii::log(..., 'security.access')` (rota, método, AJAX), que chega ao Sentry com `!YII_DEBUG` e ao log de arquivo em `app/runtime/<INSTANCE>/<data>/`.
- Rotas públicas ficam em `app/config/public-routes/<módulo>.php`, um arquivo por módulo, carregados por glob, cada rota com comentário de justificativa (seção 8.4). Este card só acrescenta linhas ou arquivos ali. O baseline `tests/_data/access-baseline.json` só pode encolher.
- Em `enforce`, visitante em rota não pública é redirecionado para `user->loginUrl` (AJAX recebe 401 JSON); usuário logado passa e a autorização por papel continua nos `filters()` dos controllers.
- Não podem cair: `site/login`, `site/error` (`errorAction` em `app/config/main.php` ≈L226) e as duas rotas que o br-nham chama com Bearer e sem sessão, `foods/foodrequest/updateAcceptedFoods` e `updateReceivedFoods` (`app/modules/foods/controllers/FoodrequestController.php` ≈L168 e ≈L218), até o R3-API-05 desligá-las.
- Outros candidatos a públicos (pré-matrícula online, cadastro de agricultor, login de responsáveis) **só entram se o log mostrar uso legítimo**. Não os assuma.
- `Controller::init()` já devolve 401 em JSON para visitante em requisição AJAX (≈L49–L54), antes de qualquer política.
- A variável `ACCESS_POLICY_MODE` vive no ambiente de deploy (fora do repositório). Se o ambiente de deploy não a define, vale o padrão `log`; ligar o enforce é definir `enforce` lá, e o rollback é voltar a `log`. Qualquer valor padrão no `main.php` ou no `docker-compose.yml` é edição do coordenador.

## Objetivo
Ligar `ACCESS_POLICY_MODE=enforce` em produção sem bloquear fluxo legítimo, com rollback por variável de ambiente.

## Passos
1. Confirme no Handoff do R2-SEC-02 a data em que o modo log foi ligado em produção. Com menos de 14 dias, pare e marque o card como `bloqueado`.
2. Exporte os eventos `security.access` do período (Sentry e `app/runtime/<INSTANCE>/<data>/`), agrupados por rota e por tenant. Registre no Handoff a contagem por rota.
3. Classifique cada rota distinta numa tabela do Handoff (rota, eventos, decisão, evidência):
   - uso legítimo de visitante (login, Bearer, formulário público): entra em `public-routes/<módulo>.php`, com comentário de justificativa;
   - uso legítimo de usuário logado negado por engano: **não** vira rota pública. É defeito da política ou das regras do controller; abra card e não ligue o enforce para esse caso;
   - sondagem real (por exemplo `index-test.php` ou ações de admin sem sessão): continua bloqueada.
4. `tag-qa`: o teste do R2-SEC-02 ("toda action ou é pública ou é protegida") precisa passar com as listas atualizadas. Para cada rota pública acrescentada, escreva um caso de regressão (visitante → 200; rota vizinha protegida → 302 ou 401).
5. Ensaio em homologação (ou tenant demo) com `enforce`: smoke com `curl` e Playwright (login, troca de escola e ano, systemadmin, foods com Bearer, AJAX com sessão expirada).
6. Produção em etapas: um tenant piloto por 48 h e depois todos. A cada etapa observe 403 e 5xx e a categoria `security.access`.
7. Registre no Handoff o plano de rollback: `ACCESS_POLICY_MODE=log` mais recarga do contêiner `tag-app` (confirmar se a variável é lida a cada request ou só no boot), com quem executa e em quanto tempo.

## Fora de escopo
- Adicionar `filters()` aos 6 controllers sem `accessControl` (R3-SEC-03).
- Ligar o CSRF em enforce (R3-SEC-02).
- Corrigir o `Yii::app()->user->checkAccess()` (R2-SEC-01).
- A API (`api.php`): ela tem autenticação própria por JWT e não passa por esta política.

## Critérios de aceite
- [ ] O Handoff registra o início do modo log (≥ 14 dias) e a contagem de eventos por rota.
- [ ] Toda rota com evento tem decisão e evidência registradas.
- [ ] Nenhuma rota pública foi adicionada sem comentário de justificativa.
- [ ] O rollback para `log` foi testado em homologação: o visitante deixa de ser bloqueado e o evento volta a ser só registrado.
- [ ] Produção em `enforce` por 48 h sem alta de 403/5xx nem evento legítimo novo.
- [ ] `composer analyse` e os testes de integração do `AccessPolicy` estão verdes.

## Como verificar
```bash
# visitante em rota protegida: bloqueado (302 para site/login; confirmar o status no R2-SEC-02)
curl -i "http://localhost/?r=student/student/index"
# visitante em rota pública listada: 200
curl -i "http://localhost/?r=site/login"
# AJAX de visitante: 401 em JSON com {redirect}
curl -i -H "X-Requested-With: XMLHttpRequest" "http://localhost/?r=student/student/index"
# foods com Bearer, sem sessão, continua passando pela política (a resposta vem do controller, nunca 302)
curl -i -X POST "http://localhost/?r=foods/foodrequest/updateAcceptedFoods" -H "Authorization: Bearer invalido"
# esperado: 200 com {"error":"token nao autorizado"}
composer analyse && composer test:integration -- --filter AccessPolicy
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._
