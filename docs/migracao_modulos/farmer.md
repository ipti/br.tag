# Migração: FarmerRegisterController → novo módulo `app/modules/farmer/`

## Objetivo

Mover o CRUD de registros de agricultores do controller legado `FarmerRegisterController` para um novo módulo `farmer`, seguindo o padrão de modularização do TAG.

---

## Origem

**Arquivo:** `app/controllers/FarmerRegisterController.php`
**Tamanho:** ~5 KB (182 linhas)
**Layout:** `fullmenu`

---

## Destino

**Módulo:** `app/modules/farmer/` (a criar)
**Já existente:** Não

---

## Análise das Ações

| Ação | HTTP | Parâmetros | Propósito |
|---|---|---|---|
| `actionIndex()` | GET | — | Lista todos os registros de agricultor com paginação |
| `actionView($id)` | GET | `$id` | Exibe registro individual |
| `actionCreate()` | GET/POST | `$_POST['FarmerRegister']` | Cria novo registro de agricultor |
| `actionUpdate($id)` | GET/POST | `$id` + `$_POST['FarmerRegister']` | Atualiza registro existente |
| `actionDelete($id)` | POST | `$id` | Exclui registro (filter: postOnly) |
| `actionAdmin()` | GET | `$_GET['FarmerRegister']` | Grid admin com filtro de busca |

---

## Controle de Acesso

```
public: index, view
autenticado (@): create, update
admin: admin, delete
```

---

## Modelos Usados

- `FarmerRegister::model()` (único modelo)

Verificar se `FarmerRegister` está em `app/models/` (modelo compartilhado) ou se é específico deste controller. Se for específico, pode ser movido para `app/modules/farmer/models/`.

---

## Helper Methods

```php
protected function loadModel($id)
// Carrega FarmerRegister por PK, lança HTTP 404 se não encontrado

protected function performAjaxValidation($model)
// Valida via CActiveForm::validate, retorna JSON de erros
```

---

## Views a Criar/Mover

| View atual | Novo caminho esperado |
|---|---|
| `app/views/farmerRegister/index.php` | `app/modules/farmer/views/default/index.php` |
| `app/views/farmerRegister/view.php` | `app/modules/farmer/views/default/view.php` |
| `app/views/farmerRegister/create.php` | `app/modules/farmer/views/default/create.php` |
| `app/views/farmerRegister/update.php` | `app/modules/farmer/views/default/update.php` |
| `app/views/farmerRegister/admin.php` | `app/modules/farmer/views/default/admin.php` |

---

## Estrutura do Novo Módulo a Criar

```
app/modules/farmer/
├── FarmerModule.php
├── controllers/
│   └── DefaultController.php
├── models/
│   └── (FarmerRegister.php — se for específico)
├── views/
│   └── default/
│       ├── index.php
│       ├── view.php
│       ├── create.php
│       ├── update.php
│       └── admin.php
└── resources/ (se houver assets)
```

### Template mínimo para `FarmerModule.php`

```php
class FarmerModule extends CWebModule
{
    public function init()
    {
        $this->setImport([
            'farmer.models.*',
            'farmer.components.*',
        ]);
    }

    public function beforeControllerAction($controller, $action)
    {
        $controller->layout = 'webroot.themes.default.views.layouts.fullmenu';
        return parent::beforeControllerAction($controller, $action);
    }
}
```

---

## Registro no `app/config/main.php`

Adicionar ao array `modules`:
```php
'farmer' => [],
```

---

## Referências de Rota a Atualizar

Buscar no repositório:
```
?r=farmerRegister/
createUrl('farmerRegister/
```

Verificar:
- `themes/default/views/layouts/menus/` — se houver link no menu
- Qualquer view que referencie o controller de agricultor

Rotas a renomear:
| Rota atual | Nova rota |
|---|---|
| `?r=farmerRegister/index` | `?r=farmer/default/index` |
| `?r=farmerRegister/view` | `?r=farmer/default/view` |
| `?r=farmerRegister/create` | `?r=farmer/default/create` |
| `?r=farmerRegister/update` | `?r=farmer/default/update` |
| `?r=farmerRegister/delete` | `?r=farmer/default/delete` |

---

## Riscos e Observações

- **Risco baixo** — controller CRUD simples sem dependências externas complexas
- Verificar se `FarmerRegister` model é específico deste domínio antes de decidir se move para módulo ou permanece em `app/models/`
- Se o módulo `farmer` tiver pouquíssimo uso, avaliar se a migração é prioritária
- Verificar se há link no menu principal — se não houver, o impacto de rotas quebradas é menor

---

## Passos de Migração

1. Verificar se `FarmerRegister` model existe em `app/models/` e se é usado por outros controllers
2. Criar estrutura de diretórios `app/modules/farmer/`
3. Criar `FarmerModule.php` seguindo o template acima
4. Criar `DefaultController.php` com todas as 6 ações
5. Mover views de `app/views/farmerRegister/` para `modules/farmer/views/default/`
6. Registrar módulo em `app/config/main.php`
7. Buscar e atualizar referências `?r=farmerRegister/` em PHP, JS e layouts
8. Testar: CRUD completo de registro de agricultor
9. Remover `app/controllers/FarmerRegisterController.php` após validação

---

## Checklist de Encerramento

- [ ] Módulo criado e registrado em `app/config/main.php`
- [ ] `FarmerModule.php` com layout `fullmenu` configurado
- [ ] `DefaultController.php` com todas as ações migradas
- [ ] `FarmerRegister` model acessível no módulo
- [ ] Views movidas para `modules/farmer/views/default/`
- [ ] Rotas `?r=farmerRegister/` atualizadas para `?r=farmer/default/`
- [ ] Active-state do menu verificado
- [ ] CRUD testado: index, view, create, update, delete
- [ ] Controller legado removido
