# Migração: ToolsController → `app/modules/tools/`

## Objetivo

Mover as funcionalidades de visualização de logs e OPcache do controller legado root `ToolsController` para o módulo `tools` já existente.

---

## Origem

**Arquivo:** `app/controllers/ToolsController.php`
**Tamanho:** ~2 KB
**Layout:** `fullmenu`

---

## Destino

**Módulo:** `app/modules/tools/`
**Já existente:** Sim
**Controller atual do módulo:** `app/modules/tools/controllers/DefaultController.php`

---

## Análise das Ações

### `actionIndex()` — GET
- Monta array `$tools` com links para `tools/viewLogs` e `tools/opcache`
- Renderiza view `tools/index`
- Passa para a view: `['tools' => array de ferramentas com nome e url]`

### `actionOpcache()` — GET
- Sobrescreve layout para `reportsclean`
- Inclui extensão `application.extensions.opcache-gui/index.php`
- Lança `CHttpException(404)` se o arquivo não existir
- Encerra com `Yii::app()->end()` (não chama `render()`)

### `actionViewLogs()` — GET
- Lê arquivos de log do path: `/app/app/runtime/{INSTANCE}/{Y-m-d}/application.log*`
- Usa `rsort()` para ordenar por mais recentes primeiro
- Cria `CArrayDataProvider` com paginação (50 itens por página)
- Renderiza view `tools/viewLogs`
- Depende da constante `INSTANCE`

---

## Modelos Envolvidos

Nenhum model customizado. Usa apenas `CArrayDataProvider` (componente nativo Yii).

---

## Views a Mover

| View atual | Novo caminho esperado |
|---|---|
| `app/views/tools/index.php` | `app/modules/tools/views/default/index.php` |
| `app/views/tools/viewLogs.php` | `app/modules/tools/views/default/viewLogs.php` |

> `actionOpcache()` não usa `render()`, portanto não há view a mover para essa ação.

---

## Assets / JS / CSS

Nenhum asset registrado diretamente no controller.

---

## Situação do Módulo `tools` Atual

O módulo já possui um `DefaultController` com funcionalidade **diferente e complementar**:
- `actionIndex()` — lista comandos de console whitelisted (superuser only)
- `actionRun()` — executa comandos de console via `exec()` com segurança

As ações do `ToolsController` legado (logs e opcache) **não existem no módulo** — não há conflito de nomes.

### Estratégia recomendada

**Opção A (preferida):** Adicionar as ações `opcache` e `viewLogs` diretamente ao `DefaultController` do módulo, pois o módulo já expõe ferramentas de sistema.

**Opção B:** Criar um `LogsController.php` separado dentro do módulo para isolar melhor o domínio de logs.

---

## Referências de Rota a Atualizar

Buscar no repositório:
```
?r=tools/
createUrl('tools/
```

Verificar especialmente:
- `themes/default/views/layouts/menus/` — links do menu
- `themes/default/views/layouts/` — breadcrumbs

Rotas a renomear (root → módulo):
| Rota atual | Nova rota |
|---|---|
| `?r=tools/index` | `?r=tools/default/index` |
| `?r=tools/opcache` | `?r=tools/default/opcache` |
| `?r=tools/viewLogs` | `?r=tools/default/viewLogs` |

---

## Riscos e Observações

- **Baixo risco** — controller sem dependências de modelo
- A constante `INSTANCE` deve estar acessível no escopo do módulo (verificar `config.php`)
- O path de logs `/app/app/runtime/{INSTANCE}/` usa path absoluto — validar após mover
- A extensão `opcache-gui` deve continuar acessível pelo caminho `application.extensions.opcache-gui/index.php`

---

## Passos de Migração

1. Verificar se módulo está registrado em `app/config/main.php` (já deve estar)
2. Adicionar ações `actionOpcache()` e `actionViewLogs()` ao `DefaultController` do módulo
3. Atualizar `actionIndex()` do módulo para incluir os novos links (ou manter index próprio)
4. Mover views `tools/index.php` e `tools/viewLogs.php` para `modules/tools/views/default/`
5. Buscar e atualizar todas as referências `?r=tools/` e `createUrl('tools/`
6. Testar no browser: rota carrega, logs aparecem, opcache funciona
7. Remover `app/controllers/ToolsController.php` após validação

---

## Checklist de Encerramento

- [ ] Módulo registrado em `app/config/main.php`
- [ ] Ações migradas para `DefaultController` do módulo
- [ ] Views movidas para `modules/tools/views/default/`
- [ ] Rotas `?r=tools/` atualizadas em PHP, JS e layouts
- [ ] `$_SERVER['REQUEST_URI']` active-state revisado em menus
- [ ] Constante `INSTANCE` acessível no contexto do módulo
- [ ] Path de logs funcional após mudança de contexto
- [ ] Controller legado removido após validação completa
