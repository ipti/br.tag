---
id: R8-FRONT-03
title: "Aplicar a CSP com script-src 'self', primeiro em Report-Only e depois em enforce"
release: R8
module: plataforma
type: frontend
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R8-FRONT-02]
size: M
risk: alto
human_review: recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F4, meta final: CSP com script-src 'self') e seção 4 (primeiro registrar, depois bloquear)"
# O nginx versionado e os layouts são arquivos com dono: neste card quem os edita é o coordenador (README, "Arquivos com dono").
write_scope:
  - docker/nginx/**
  - "themes/default/views/layouts/**"
  - app/modules/*/views/**
  - app/modules/*/layouts/**
  - frontend/**
  - tests/**
read_only:
  - metrics/baseline/**
  - js/**
forbidden:
  - composer.json
  - app/models/**
acceptance:
  - "A catraca (scripts/frontend-metrics) marca zero <script> inline executável e zero atributos on*= em todos os módulos"
  - "Período de observação em Report-Only sem violação legítima nos relatórios"
  - "Suíte Playwright com a CSP em enforce, sem pageerror nem violação no console"
  - "curl -sI da página de login e de uma página interna devolve Content-Security-Policy com script-src 'self'"
---

# R8-FRONT-03: Aplicar a CSP com script-src 'self', primeiro em Report-Only e depois em enforce

## Contexto
A meta final da F4 é uma CSP com `script-src 'self'`, começando em Report-Only. Hoje não há CSP: `git grep` por `Content-Security-Policy` em PHP, `.conf`, `.yml` e `Dockerfile` não encontra nada (2026-11-27). O nginx versionado, onde o cabeçalho deve ficar, vem do B0 (`docker/nginx/default.conf`; **confirmar:** a pasta `docker/nginx/` ainda não existe e o `Dockerfile` ≈L13 edita o `default.conf` da imagem base com `sed`).

**Pré-condição:** a CSP só pode ser aplicada com zero `<script>` inline e zero `on*=` na catraca. Contagens de 2026-11-27, por grep (**confirmar:** os números atuais): cerca de 112 `<script>` sem `src` na mesma linha em views e módulos (parte são blocos `application/json` do `TFrontend::jsonScript`, que a CSP não executa), cerca de 76 atributos `on*=` e 129 blocos `<style>`, além de 2.055 `style=`.

**O que quebra sob `script-src 'self'` (levantado no código):**
- **Google Analytics:** `themes/default/views/layouts/fullmenu.php` (≈L120–129) e `login.php` (≈L17–23) têm o `gtag` com `<script async src="https://www.googletagmanager.com/gtag/js?id=...">` e um bloco inline de configuração. É decisão do produto: manter o GA4 exige liberar `https://www.googletagmanager.com` em `script-src` e os domínios de coleta em `connect-src`, ou remover o GA.
- **CDNs que sobraram:** `resultsmanagement.php` (≈L27–80, se a ilha ainda existir; ver R7-RESULTSMANAGEMENT), `app/modules/sagres/views/default/index.php` (≈L147) e `gestaopresente/views/default/index.php` (≈L91, jszip do cdnjs), `resultsmanagement/views/default/index.php` (≈L13–14, Google Maps e um `markerclusterer` de `googlecode.com` via `http://`).
- **Estilos:** os 2.055 `style=` e 129 `<style>` exigem `style-src 'unsafe-inline'` até a F5 zerar a catraca. Este card **não** pede `style-src` estrito.
- **Sentry no navegador:** **confirmar:** onde é carregado (o `common.js` checa `window.Raven`) e se precisa de `connect-src`.
- **`eval`:** **confirmar** por grep em `js/` e `frontend/` o uso de `eval(`, `new Function(` e `setTimeout("...")`, que a CSP bloqueia.

## Objetivo
Toda página do TAG serve a CSP com `script-src 'self'` (mais as exceções explícitas aprovadas), observada antes em Report-Only, com reversão rápida.

## Passos
1. Rode a catraca e liste o que falta para zero `<script>` inline e zero `on*=`. Se não for zero, **pare** e abra cards `R8-FRONT-03-<nn>` de limpeza por módulo (as páginas migram para `frontend/pages/` com `registerEntry` e `data-action`).
2. Decida com o produto o destino do GA4 e dos CDNs restantes (remover, hospedar localmente ou liberar por domínio) e registre em ADR.
3. Mova o bloco de configuração do `gtag` para um arquivo estático, se o GA4 ficar, e tire os scripts de CDN das views.
4. Defina a política: `default-src 'self'; script-src 'self' <exceções do ADR>; style-src 'self' 'unsafe-inline'; img-src 'self' data:; connect-src 'self' <exceções>; frame-ancestors 'self'; report-to` (**confirmar:** o endpoint de relatório, por exemplo o endpoint de CSP do Sentry ou uma rota própria).
5. Sirva a política como `Content-Security-Policy-Report-Only` no nginx versionado (edição do coordenador), com um interruptor por variável de ambiente que permita trocar para enforce e desligar sem novo deploy de código.
6. Observe por pelo menos 2 semanas (como no modo log da B2). Cada violação vira correção no código ou exceção no ADR; nenhuma "violação legítima" pode restar.
7. Troque para `Content-Security-Policy` (enforce) e rode a suíte Playwright com falha em `console.error` e em violação de CSP.
8. Documente a política e como investigar violações em `docs/`.

## Fora de escopo
- `style-src` estrito e remoção dos `style=` (F5).
- Trocar o Google Analytics por outra ferramenta.
- Subresource Integrity e demais cabeçalhos de segurança.

## Critérios de aceite
- [ ] A catraca marca zero `<script>` inline executável e zero `on*=`.
- [ ] O ADR registra o destino do GA4 e de cada CDN.
- [ ] O Report-Only rodou pelo período combinado sem violação legítima aberta.
- [ ] O enforce está ativo, com interruptor por ambiente e plano de reversão testado.
- [ ] A suíte Playwright passa com a CSP em enforce.

## Como verificar
```bash
scripts/frontend-metrics        # confirmar o comando; esperado: inline_script=0, inline_handler=0
curl -sI "http://localhost/?r=site/login" | grep -i "content-security-policy"
curl -sI "http://localhost/?r=site/index" -b "PHPSESSID=<sessão>" | grep -i "content-security-policy"
npm run build && npx playwright test      # sem pageerror, console.error nem violação de CSP
```
O Report-Only é lido no relatório de violações do endpoint escolhido. Os testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._
