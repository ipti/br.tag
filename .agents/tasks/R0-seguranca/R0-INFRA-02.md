---
id: R0-INFRA-02
title: "Versionar o nginx, negar acesso a arquivos sensíveis, remover index-test.php e tirar exports da raiz web"
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
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achado 8) e seção 6 (B0, item 0.3)"
write_scope:
  - docker/nginx/default.conf
  - Dockerfile
  - index-test.php
  - app/modules/systemadmin/controllers/DefaultController.php
read_only:
  - app/controllers/AdministratorController.php
  - app/controllers/CensoController.php
  - app/modules/sagres/**
  - docker-compose.yml
forbidden:
  - composer.json
  - app/config/main.php
  - app/models/**
  - themes/default/views/layouts/**
acceptance:
  - "docker build -t tag-nginx-test .   (a imagem constrói e o nginx sobe: docker run --rm tag-nginx-test nginx -t)"
  - "git ls-files index-test.php   (sem resultados)"
  - "Matriz de curl da seção Como verificar: arquivos sensíveis devolvem 403 ou 404; index.php, assets e login continuam funcionando"
  - "composer lint (se o PHP do systemadmin for alterado)"
---

# R0-INFRA-02: Versionar o nginx, negar acesso a arquivos sensíveis, remover index-test.php e tirar exports da raiz web

## Contexto
**Card do coordenador** (infraestrutura). Hoje o nginx não está no repositório. O `Dockerfile` (≈L13–15) parte da imagem `ipti/yii2:8.3-fpm` e usa `sed` no `/etc/nginx/conf.d/default.conf` da imagem para trocar o root de `/app/web` para `/app`, a **raiz do repositório**, e para acrescentar `fastcgi_read_timeout 2400` e `proxy_read_timeout 2400`. O conteúdo original do `default.conf` não está no repo; **confirmar** extraindo da imagem (passo 2).

**Provavelmente expostos** (achado 8; confirmar cada um com `curl`, passo 1):
- logs: `app/runtime/<INSTANCE>/<Y-m-d>/application.log` (`app/config/main.php` ≈L3–15);
- exports com dados de alunos: `app/export/InfoTagJSON/<banco>.json` (`app/modules/systemadmin/controllers/DefaultController.php` ≈L177 e ≈L836), `app/export/InfoTagCSV/*.csv|zip` (≈L212, ≈L400, ≈L701) e `app/export/<ano>_<escola>.TXT` (`AdministratorController` ≈L612, ≈L794; `CensoController` ≈L2743–2802);
- `app/export/SagresEdu/*.zip` (`SagresConsultModel.php` ≈L2858–2872), `app/export/export<data>.sql` (`AdministratorController` ≈L1259 e ≈L1494);
- `resultados.csv`: `actionExportCountUsers` (systemadmin ≈L108–172) grava `fopen('resultados.csv', 'w')` (≈L161–162) no diretório de trabalho do PHP, que é a raiz do repositório, e ainda imprime o link `<a href='resultados.csv'>` (≈L171);
- uploads: `AdministratorController::actionImport` salva o arquivo enviado em `app/import/<nome original>` (≈L877). Se o nginx executa qualquer `.php`, um upload `.php` vira execução remota de código;
- arquivos da raiz: `index-test.php` (usa `app/config/test.php` e liga `YII_DEBUG`; **sem referências no repositório**, `git grep index-test` não acha nada), `composer.lock`, `composer.json`, `package*.json`, `Dockerfile`, `phpstan-baseline.neon`, `config.yml`, `CHANGELOG.md`, `config.php`/`instance.php` (PHP executável), `docs/`, `.agents/`, `.claude/`, `.github/`;
- um backup versionado: `app/commands/io.escola.adefib.json.bak.json` (confirmar o conteúdo; se tiver dados de escola, abra card para removê-lo do repositório).

**Estáticos que precisam continuar servidos:** `assets/` (publicados pelo `assetManager`), `css/`, `js/`, `themes/`, `sass/css/`, `favicon.ico`, `robots.txt`. Não achei no repositório páginas que apontem para `/app/modules/.../resources` ou outros arquivos de `app/` (`git grep` por `app/(modules|extensions|...)/...(js|css|png|...)` só devolve arquivos `.import` sem uso). **Confirmar** antes de negar `app/`, porque algumas views legadas registram caminhos diretos (`.agents/rules/legacy-routing-and-assets.md`).

O `urlManager` usa `showScriptName => false` (`main.php` ≈L145–148); as rotas continuam sendo `/?r=...`. Pelo `urlManager`, o nginx precisa encaminhar para o `index.php` o que não for arquivo (confirmar no `default.conf` extraído); preserve essa regra.

## Objetivo
Só `index.php` executa PHP, o nginx está versionado, nenhum arquivo sensível é servido, e os exports não dependem mais de pasta pública.

## Passos
1. **Medir a exposição atual.** Em homologação (e produção, com cuidado), rode a matriz de `curl` da seção Como verificar e guarde o resultado no Handoff.
2. **Extrair a base:** `docker run --rm ipti/yii2:8.3-fpm cat /etc/nginx/conf.d/default.conf > docker/nginx/default.conf`. Edite o arquivo versionado já com `root /app;` e com os dois timeouts de 2400 s do `Dockerfile` (≈L15), em vez de aplicar `sed`.
3. **Regras do nginx** (ajuste à sintaxe real do arquivo extraído):
   - só `index.php` executa PHP (e, no futuro, `api.php`); qualquer outro `.php` responde 404;
   - `deny all` para `app/`, `vendor/`, `tests/`, `docker/`, `scripts/`, `db/`, `releases/`, `docs/`, para dotfiles (`/\.`) e para `*.lock`, `*.neon`, `*.yml`, `*.md`, `*.sql` e `*.log`;
   - `deny` também para `composer.json`, `package.json`, `package-lock.json`, `Dockerfile`, `Makefile`, `config.php` e `instance.php` (por nome);
   - mantenha a regra de reescrita para `index.php` e o `client_max_body_size` já existente (confirmar o valor no arquivo extraído; os uploads de import e zips dependem dele);
   - **ordem importa:** os `deny` vêm antes do bloco que encaminha para PHP.
4. **Dockerfile:** troque o `RUN sed -i ... default.conf` por `COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf`. Mantenha o `sed` do `memory_limit` do PHP (é outro arquivo).
5. **Apagar `index-test.php`** (`git rm`).
6. **Exports fora da raiz web**, para defesa em profundidade (o `deny app/` já fecha o acesso direto):
   - `actionExportCountUsers`: pare de gravar `resultados.csv` e de imprimir o link; envie o CSV direto na resposta (cabeçalhos de download e `fputcsv` em `php://output`);
   - nas actions do systemadmin que gravam em `./app/export/...` e fazem `readfile` na mesma requisição (≈L201–207, ≈L400–423 e ≈L836; confirmar cada uma), grave em `sys_get_temp_dir()` e apague o arquivo depois do envio;
   - **este passo toca o mesmo arquivo do R0-SYSADMIN-01.** Se aquele card ainda não foi mergeado, entregue os passos 1 a 5 e registre o 6 como pendência no Handoff;
   - as gravações de `AdministratorController`, `CensoController` e `sagres` ficam para um card de acompanhamento (registre-o no Handoff).
7. Conferir o build: `docker build` e `nginx -t` na imagem; suba a stack e rode a matriz de novo.

## Fora de escopo
- Cabeçalhos de segurança (CSP, HSTS) e cache de `/build/` (R1 e R8).
- Roteamento de `/api/` (B4).
- Mover as gravações de `AdministratorController`, `CensoController` e `sagres` (card de acompanhamento).
- Remover do repositório o backup JSON de `app/commands` (card próprio, depois de ver o conteúdo).

## Critérios de aceite
- [ ] `docker/nginx/default.conf` existe, o `Dockerfile` o copia, e `nginx -t` passa.
- [ ] Os arquivos sensíveis da matriz devolvem 403 ou 404, inclusive um `.php` colocado em `app/import/`.
- [ ] `/`, `/?r=site/login`, `assets/`, `css/`, `js/` e `favicon.ico` continuam funcionando; o login e uma página interna carregam sem 404 de estático.
- [ ] `index-test.php` não existe mais.
- [ ] `resultados.csv` não é mais gravado na raiz; o export de usuários baixa por action autenticada.
- [ ] O Handoff traz a matriz antes e depois, e as pendências (exports de Censo, Administrator e Sagres).

## Como verificar
```bash
H=https://<homologação>
for p in app/runtime/ app/export/InfoTagJSON/x.json app/export/readme app/import/readme resultados.csv \
         index-test.php composer.lock composer.json Dockerfile phpstan-baseline.neon config.yml config.php \
         CHANGELOG.md docs/arquitetura/plano-modernizacao.md .git/HEAD .claude/settings.json; do
  printf "%-55s %s\n" "$p" "$(curl -s -o /dev/null -w '%{http_code}' "$H/$p")"
done   # esperado depois: tudo 403 ou 404 (antes, os que existem no disco devolvem 200)

curl -s -o /dev/null -w "%{http_code}\n" "$H/"                      # 200 ou 302 para o login
curl -s -o /dev/null -w "%{http_code}\n" "$H/favicon.ico"           # 200
docker build -t tag-nginx-test . && docker run --rm tag-nginx-test nginx -t
git ls-files index-test.php
```
Faça também um upload de teste em `app/import/` com extensão `.php` (só em ambiente descartável) e confirme que `curl` nele devolve 403 ou 404. Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação; o roteiro mínimo é login, home e abrir 3 páginas internas, olhando o console por 404 de estático.

## Handoff
_Preenchido por quem executar._
