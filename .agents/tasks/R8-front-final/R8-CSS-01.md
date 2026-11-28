---
id: R8-CSS-01
title: "Remover o CSS de vendor por layout quando a catraca zerar: glyphicons, Bootstrap 2 e seções do template.css"
release: R8
module: plataforma
type: css
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R3-CSS-03]
size: M
risk: medio
human_review: recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F5, saída do Bootstrap 2 e do AdminKIT) e seção 4 (catraca)"
# Os layouts são arquivos com dono: neste card quem os edita é o coordenador (README, "Arquivos com dono").
write_scope:
  - themes/default/css/**
  - "themes/default/views/layouts/**"
  - app/modules/enrollmentonline/layouts/**
  - sass/scss/**
  - metrics/baseline/**
read_only:
  - app/modules/*/views/**
forbidden:
  - composer.json
  - app/config/main.php
  - .github/workflows/**
  - app/models/**
acceptance:
  - "A catraca marca zero uso do que será removido em todas as páginas que usam o layout, antes da remoção"
  - "A suíte visual (toHaveScreenshot) não mostra diferença nas páginas do layout alterado"
  - "O relatório de CSS rejeitado do PurgeCSS confirma 100% sem uso nas seções removidas do template.css, e um grep pelo nome das classes também"
---

# R8-CSS-01: Remover o CSS de vendor por layout quando a catraca zerar: glyphicons, Bootstrap 2 e seções do template.css

## Contexto
A F5 define que o CSS do fornecedor sai de um layout quando o uso dele ali chega a zero (plano, seção 7). Este card é o fim dessa trilha, e **só roda para o que a catraca já zerou**. Ele não migra views: a migração de cada módulo está nos épicos R4 a R7, com a skill `tag-css-cleanup`. Se o que se quer remover ainda tem uso, o card para e lista o que falta por módulo.

**O que cada layout carrega hoje (conferido em 2026-11-27; confirmar a `main` no início):**
| Layout | Arquivos de vendor |
|---|---|
| `themes/default/views/layouts/fullmenu.php` | `bootstrap.min.css` (≈L63), `responsive.min.css` (≈L64), `glyphicons.min.css` (≈L75), `template.css` (≈L79), `template2.css` (≈L80) |
| `themes/default/views/layouts/reports.php` | `bootstrap.min.css` (≈L51), `responsive.min.css` (≈L52), `template.css` (≈L55), `glyphicons.min.css` (≈L58) |
| `themes/default/views/layouts/reportsclean.php` | `bootstrap.min.css` (≈L14), `template.css` (≈L15), `glyphicons.min.css` (≈L18) |
| `app/modules/enrollmentonline/layouts/enrollmentonline.php` | `bootstrap.min.css` (≈L5), `responsive.min.css` (≈L6), `template.css` (≈L7), `template2.css` (≈L8) |
| `themes/default/views/layouts/resultsmanagement.php` | Bootstrap 3.3.5 por CDN e o `style.css` do módulo (AdminKIT v2): é a ilha; segue a decisão do R7-RESULTSMANAGEMENT |

**Tamanhos em `themes/default/css/`:** `template.css` 182,8 KB (9584 linhas; começa com um `@import` da fonte Inter no Google Fonts e `@font-face` da Open Sans), `bootstrap.min.css` 102,9 KB (**Bootstrap v2.3.1**), `glyphicons.min.css` 19,8 KB, `responsive.min.css` 16,4 KB e `template2.css` 10,7 KB.

**Ordem de saída (a do plano):**
1. **Glyphicons primeiro.** O plano conta 99 usos; por grep há 79 arquivos de views e JS com `glyphicon` (**confirmar** a contagem atual). O substituto é o Font Awesome 4.7 mais os `t-icon-*`.
2. **Bootstrap 2** (`bootstrap.min.css` e `responsive.min.css`) só quando `spanN`, `row-fluid`, `control-group`, `widget` e `innerLR` estiverem em zero **em todas as páginas do layout**. A baseline do plano era `spanN` 428, `row-fluid` 259, `control-group` 487 e `widget`/`innerLR` 327.
3. **Seções do `template.css` (AdminKIT)** só se o relatório de CSS rejeitado do PurgeCSS (R3-CSS-03) mostrar a seção 100% sem uso, confirmada por `grep` e pela suíte visual. O que o design system já herdou fica.

## Objetivo
Cada layout deixa de carregar o CSS de vendor cujo uso já é zero, sem nenhuma diferença visual aprovada pendente.

## Passos
1. Rode a catraca (`scripts/frontend-metrics`) e leia `metrics/baseline/*.json`: monte uma tabela por layout com o uso de glyphicons, `spanN`, `row-fluid`, `control-group`, `widget`/`innerLR` e das classes das seções candidatas do `template.css`.
2. Para cada item em zero **em todos os módulos que usam o layout**, prepare a remoção. Se um módulo ainda usa, registre-o no Handoff e **não remova**.
3. Faça um PR por layout e por item (glyphicons, depois Bootstrap 2, depois seções do `template.css`), na ordem acima.
4. Antes de cada remoção, `grep` pelo nome da classe e pelo caminho do arquivo, inclusive em `$.getScript`, em JS que monta HTML e em caminhos concatenados.
5. Remova a tag do CSS do layout (edição do coordenador) e, quando nenhum layout o carregar mais, apague o arquivo de `themes/default/css/` e as fontes ligadas a ele.
6. Rode a suíte visual e o smoke das páginas do layout, inclusive as que usam modais, tabelas e formulários.
7. Atualize as baselines da catraca para travar o novo piso (o total não pode subir).
8. Se a fonte Inter do `@import` ficar no `template.css`, mova o `@font-face` para o design system antes de apagar o arquivo.

## Fora de escopo
- Migrar views de Bootstrap 2 para classes `t-*` (épicos de módulo e skill `tag-css-cleanup`).
- A ilha `resultsmanagement` (R7-RESULTSMANAGEMENT decide o destino).

## Critérios de aceite
- [ ] Cada arquivo removido tinha uso zero na catraca em todas as páginas do layout, com a evidência no Handoff.
- [ ] As seções removidas do `template.css` constam como 100% sem uso no relatório do PurgeCSS e no `grep`.
- [ ] A suíte visual não mostra diferença não aprovada nas páginas dos layouts alterados.
- [ ] A baseline da catraca foi atualizada e o total não subiu.

## Como verificar
```bash
scripts/frontend-metrics                    # confirmar o comando; os contadores do item removido em 0
git grep -n "glyphicon" -- app themes js    # vazio quando os glyphicons saírem
git grep -nE "\b(span[0-9]+|row-fluid|control-group|innerLR)\b" -- app themes js   # vazio quando o Bootstrap 2 sair
npm run build && npx playwright test        # suíte visual e smoke
```
Os testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._
