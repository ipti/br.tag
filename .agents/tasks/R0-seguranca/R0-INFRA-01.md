---
id: R0-INFRA-01
title: "Tirar segredos do código (db2, Bearer do foods, credenciais do SED), restringir o gii e rotacionar tudo"
release: R0
module: plataforma
type: seguranca
skill: .agents/playbooks/bugfix.md
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: []
size: P
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achado 7) e seção 6 (B0, item 0.3)"
write_scope:
  - app/config/main.php
  - app/modules/foods/controllers/FoodrequestController.php
  - app/modules/foods/controllers/FoodnoticeController.php
  - app/modules/sedsp/datasources/sed/UsuarioSEDDataSource.php
  - app/modules/sedsp/usecases/LoginUseCase.php
  - app/modules/sedsp/controllers/DefaultController.php
  - app/commands/AdminCommand.php
  - docker-compose.yml
read_only:
  - app/models/AltActiveRecord.php
  - app/controllers/AdministratorController.php
  - vendor/yiisoft/yii/framework/gii/GiiModule.php
forbidden:
  - composer.json
  - app/models/**
  - themes/default/views/layouts/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "git grep -nE \"'password' *=> *'[^']\" -- app/config/main.php   (sem resultados)"
  - "git grep -nE \"[$]2[aby][$][0-9]{2}[$]|Basic [A-Za-z0-9+/=]{12,}|exec[(]'[A-Za-z0-9]+', '\" -- app/modules/foods app/modules/sedsp   (sem resultados; ajuste o padrão se houver falso positivo e registre)"
  - "git grep -n CVarDumper -- app/modules/foods/controllers   (sem resultados)"
  - "Smoke da seção Como verificar: gii some sem as variáveis; endpoints do foods falham fechados sem token; login no SED continua funcionando em homologação"
---

# R0-INFRA-01: Tirar segredos do código (db2, Bearer do foods, credenciais do SED), restringir o gii e rotacionar tudo

## Contexto
**Card do coordenador.** Mexe em `app/config/main.php`, arquivo com dono (`.agents/tasks/README.md`). Toca também os módulos `foods` e `sedsp`. **Nenhum segredo aparece neste card:** os valores estão no código e no histórico do git; quem executar não deve copiá-los para PR, log nem Handoff.

Os quatro pontos:
1. **gii aberto** (`app/config/main.php` ≈L80–87): o módulo `gii` fica sempre registrado, com senha literal e `ipFilters => ['*']`. O gii gera código e escreve em disco. Pelo `GiiModule` (`vendor/yiisoft/yii/framework/gii/GiiModule.php` ≈L74–78), `password = false` **libera sem senha**, então `getenv()` vazio (que devolve `false`) abriria o gii. O padrão do Yii, sem `ipFilters`, já é só localhost.
2. **`db2` com credencial fixa** (`app/config/main.php` ≈L210–217): host interno do cluster, usuário e senha literais. É usado por `app/commands/AdminCommand.php` (≈L14, ≈L17, ≈L265), por `AltActiveRecord::setDb2Connection` (`app/models/AltActiveRecord.php` ≈L16) e por `AdministratorController` (≈L1253, ≈L1322 e as consultas de ≈L1509–1585). No mesmo `AdminCommand` há um host e porta fixos (≈L24). Confirmar se esses fluxos de sincronização com o banco master ainda são usados antes de decidir entre env e remoção.
3. **Bearer fixo do foods:**
   - **recebe:** `FoodrequestController::actionUpdateAcceptedFoods` (≈L168) e `actionUpdateReceivedFoods` (≈L218) lêem `$_SERVER['HTTP_AUTHORIZATION']` (≈L170 e ≈L220, que gera erro de índice quando o header falta) e comparam com um literal (≈L177 e ≈L227). A comparação é `!=`, não constante no tempo;
   - **envia:** `FoodnoticeController::updatePdfFile` (≈L234) e `uploadFile` (≈L262) mandam o mesmo literal no header `Authorization` (≈L243 e ≈L270) para a Cloud Function do br-nham;
   - **vaza em tela:** nos `catch` desses métodos, `CVarDumper::dump($request, 10, true)` (≈L256 e ≈L297) imprime a requisição, **inclusive o header com o token**, na resposta HTTP. `CVarDumper::dump($e, ...)` (≈L258 e ≈L299) imprime a exceção.
4. **Credenciais do SED** (módulo `sedsp`): `UsuarioSEDDataSource::login` manda um header `Basic` literal (≈L9). `LoginUseCase::checkSEDToken` (≈L48) e `sedsp/controllers/DefaultController.php` (≈L187) chamam `exec('<usuário>', '<senha>')` com literais. Confirmar: o header `Basic` provavelmente codifica o mesmo par; confira decodificando localmente, sem registrar o resultado.

`SEDSP_URL` já vem de env (`SedDataSource.php` ≈L15); siga esse padrão. Atenção: em produção hoje todo `CHttpException` vira 500 em branco (achado 9, R0-INFRA-03).

## Objetivo
Nenhum segredo literal no código, gii ligado só em desenvolvimento local, e todas as credenciais expostas rotacionadas.

## Passos
1. **gii:** registre o módulo só quando `YII_DEBUG && getenv('GII_ENABLED') && getenv('GII_PASSWORD')`. Nesse caso use `password => getenv('GII_PASSWORD')` e `ipFilters => ['127.0.0.1', '::1']`. Com qualquer condição falsa, `gii` nem entra em `modules`. Cuidado com o `YII_DEBUG`: o `config.php` hoje trata `YII_DEBUG=false` como verdadeiro (ver R0-INFRA-03).
2. **db2:** defina `DB2_DSN`, `DB2_USER` e `DB2_PASSWORD`. Registre o componente só quando as três existirem; sem elas, quem usar `db2` falha com erro claro (não conecte com valor vazio). Se os fluxos que usam `db2` estiverem mortos, a pessoa que revisa pode preferir remover o componente e registrar a dívida; decida em conjunto.
3. **Bearer do foods:** leia o token esperado de env (`FOODS_API_TOKEN`; para rotacionar cada sentido separado, use dois nomes, por exemplo `FOODS_API_TOKEN_IN` e `FOODS_API_TOKEN_OUT`).
   - nos dois endpoints de entrada, compare com `hash_equals` e **falhe fechado**: se a variável estiver vazia, rejeite sempre (`'Bearer ' === 'Bearer '` aceitaria o header vazio). Trate o header ausente com `$_SERVER['HTTP_AUTHORIZATION'] ?? ''`. Mantenha as mesmas mensagens JSON e o mesmo status HTTP, para não quebrar o cliente do br-nham (mudar para 401 só com a confirmação deles);
   - nos dois envios, monte o header a partir de env;
   - remova os quatro `CVarDumper::dump` e registre a falha com `Yii::log(..., CLogger::LEVEL_ERROR, 'application')`, sem o header.
4. **SED:** defina `SEDSP_USER` e `SEDSP_PASSWORD`. Use-as em `LoginUseCase::checkSEDToken`, em `DefaultController` (≈L187) e para montar o header `Basic` em `UsuarioSEDDataSource` (`base64_encode(user . ':' . pass)`), se a confirmação do item 4 do Contexto indicar o mesmo par. Sem as variáveis, não tente logar.
5. **Rotação** (a coordenação é do coordenador; ninguém executa sozinho). Todos os valores antigos devem ser tratados como vazados, porque estão no histórico do git:

   | Segredo | Quem rotaciona | Observação |
   |---|---|---|
   | Senha do gii | coordenador | só em ambientes de desenvolvimento; confirme que não há gii em homologação/produção |
   | Credencial do `db2` | quem administra o MariaDB do cluster | criar usuário novo com o menor privilégio e revogar o antigo |
   | Bearer do foods | coordenador e quem mantém o br-nham | aceitar token antigo e novo durante a troca; depois desligar o antigo |
   | Usuário e senha do SED | quem administra o acesso do TAG à SEDSP | pedir nova credencial à SED; testar em homologação antes |

   Não reescreva o histórico agora. Registre a data de cada rotação no Handoff.
6. Acrescente ao `docker-compose.yml` as variáveis novas com valores falsos de desenvolvimento (`GII_ENABLED: 0`) e rode as varreduras da seção `acceptance`.

## Fora de escopo
- CSRF, sessão e autenticação da API nova (B2 e B4); reescrever o cliente HTTP do foods ou a integração do SED (R3-FOODS e R7-SEDSP).

## Critérios de aceite
- [ ] `git grep` das varreduras da seção `acceptance` não retorna nada.
- [ ] Sem `GII_ENABLED`, `?r=gii` não resolve (404 em desenvolvimento) e o módulo não está em `modules`.
- [ ] Endpoints do foods rejeitam header ausente, errado e variável de ambiente vazia; aceitam o token correto; nenhuma resposta imprime a requisição nem o token.
- [ ] Login no SED funciona em homologação com as variáveis e falha sem elas.
- [ ] As quatro rotações foram feitas e datadas no Handoff, e os valores antigos deixaram de funcionar.

## Como verificar
```bash
# gii: sem as variáveis, o módulo não existe
curl -i "http://localhost/?r=gii"   # esperado: 404 (em produção, 500 em branco até o R0-INFRA-03)

# foods: falha fechada
curl -i -X POST "http://localhost/?r=foods/foodrequest/updateAcceptedFoods" -d "requestId=1"                               # sem header
curl -i -X POST "http://localhost/?r=foods/foodrequest/updateAcceptedFoods" -H "Authorization: Bearer errado" -d "requestId=1"
# com FOODS_API_TOKEN vazio no ambiente: qualquer header, inclusive "Bearer ", é rejeitado
# com o token correto (do env de teste): segue para o corpo da action

git grep -nE "'password' *=> *'[^']" -- app/config/main.php
git grep -n CVarDumper -- app/modules/foods/controllers
composer lint && composer analyse
```
Para o SED, em homologação: faça o login do TAG com a integração ligada (`TFeature::FEAT_INTEGRATIONS_SEDSP`) e confirme que o `SED_TOKEN` é criado. Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._
Não escreva segredos aqui: registre só o nome da variável, a data e quem rotacionou.
