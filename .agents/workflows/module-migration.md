---
description: Migrate legacy controller/model/views to a Yii module
---

Use this workflow when a feature lives as a root controller in `app/controllers/`, with models in `app/models/` and views in `themes/default/views/`. The goal is to move everything into `app/modules/<module_name>/` following the Yii module pattern.

## Before Starting
- **Pick ONE controller** per migration. Never migrate multiple controllers at once.
- Verify the controller is not a critical shared controller (`SiteController`, `AdminController`). These stay in root.

## Steps

### 1. Map the Legacy Code
Identify all files belonging to the feature:
```
# Controller
app/controllers/<Name>Controller.php

# Models used (check imports/usage inside the controller)
app/models/<RelatedModel>.php

# Views
themes/default/views/<controllerName>/*.php
```

### 2. Create Module Structure
```
app/modules/<module_name>/
├── <ModuleName>Module.php
├── controllers/
│   └── <Name>Controller.php
├── models/
│   └── <Model>.php
├── resources/
│   ├── js/           # JS específico deste módulo
│   └── css/          # CSS específico deste módulo
└── views/
    └── <controllerName>/
        └── *.php
```

- Create `<ModuleName>Module.php` extending `CWebModule`:
```php
class ExampleModule extends CWebModule
{
    public $defaultController = 'example';
    public $layout = 'webroot.themes.default.views.layouts.fullmenu';

    public function init()
    {
        $this->setImport(['example.models.*']);
    }

    public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action)) {
            $controller->layout = $this->layout;
            return true;
        }
        return false;
    }
}
```

### 3. Register the Module
Add the module to `app/config/main.php` in the `modules` array:
```php
'modules' => [
    // ...existing modules...
    '<module_name>',
],
```

### 4. Move the Controller
- Copy `app/controllers/<Name>Controller.php` → `app/modules/<module_name>/controllers/<Name>Controller.php`
- Update the class: it must extend `Controller` (same as before)
- Verify `accessRules()` and `filters()` are preserved

### 5. Move the Models
- If a model is **only used by this feature**: move to `app/modules/<module_name>/models/`
- If a model is **shared with other controllers**: keep it in `app/models/` (do not move)

### 6. Move the Views
- Copy `themes/default/views/<controllerName>/` → `app/modules/<module_name>/views/<controllerName>/`
- Check for `renderPartial()` calls that reference other views — update paths if needed

### 7. Move JS and CSS Assets
- Identify JS/CSS files specific to this feature in `js/` or `css/` directories
- Move them to `app/modules/<module_name>/resources/js/` and `resources/css/`
- Update `<script>` and `<link>` tags in the views to point to the new paths
- Register assets via `Yii::app()->assetManager->publish()` or use `Yii::app()->clientScript->registerScriptFile()`

### 8. Find and Replace ALL URL References (CRITICAL)
This is the most error-prone step. URLs change from `controller/action` to `<module_name>/controller/action`.

Search the **entire project** for old URLs:
```bash
# Search for URL references in PHP files
docker exec tag-app grep -rn "r=<controllerName>" /app/themes /app/app/controllers /app/app/modules /app/app/components /app/app/widgets /app/app/models --include="*.php"

# Search in JS files (includes AJAX calls)
docker exec tag-app grep -rn "<controllerName>/" /app/js /app/app/modules/<module_name>/resources --include="*.js"

# Search for Yii URL arrays
docker exec tag-app grep -rn "'<controllerName>/" /app/app /app/themes --include="*.php"
```

**Update ALL matches** to the new module route:
| Before | After |
|--------|-------|
| `['<controllerName>/action']` | `['/<module_name>/<controllerName>/action']` |
| `?r=<controllerName>/action` | `?r=<module_name>/<controllerName>/action` |
| `$this->redirect(['action'])` | `$this->redirect(['/<module_name>/<controllerName>/action'])` |
| `CHtml::link('text', ['<controllerName>/action'])` | `CHtml::link('text', ['/<module_name>/<controllerName>/action'])` |

Also check:
- Menu/sidebar links in `themes/default/views/layouts/`
- Breadcrumbs
- `accessRules()` redirects in other controllers

### 9. Update AJAX URLs (CRITICAL)
AJAX calls are the **most fácil de esquecer** e causam erros silenciosos. Buscar em:
- **JS files**: `$.ajax`, `$.get`, `$.post`, `fetch(`, `XMLHttpRequest`
- **Inline JS nas views**: `<script>` tags dentro dos `.php`
- **Data attributes**: `data-url=`, `data-href=`

```bash
# Search for AJAX patterns
docker exec tag-app grep -rn -E "(\$.ajax|\$.get|\$.post|fetch\(|XMLHttpRequest|data-url)" /app/app/modules/<module_name> /app/js --include="*.php" --include="*.js"
```

Todas as URLs em AJAX devem apontar para a nova rota do módulo. Exemplo:
```js
// Antes
$.post('?r=<controllerName>/action', data, callback);

// Depois
$.post('?r=<module_name>/<controllerName>/action', data, callback);
```

**Dica**: Prefira gerar URLs no PHP e passar para o JS via `data-url` attributes ou variáveis globais para evitar hardcoding:
```php
<div id="my-widget" data-url="<?= Yii::app()->createUrl('/<module_name>/<controllerName>/action') ?>">
```

### 10. Remove Legacy Files
After verifying everything works:
- Delete `app/controllers/<Name>Controller.php`
- Delete `themes/default/views/<controllerName>/` directory
- Delete models that were moved (only if not shared)
- Delete JS/CSS files that were moved from `js/` or `css/`

### 11. Verify
// turbo
- Run `docker exec tag-app grep -rn "r=<controllerName>/" /app --include="*.php" --include="*.js"` to confirm no orphan references remain.
- Test every action of the migrated controller in the browser.
- **Test every interação AJAX** (formulários dinâmicos, carregamento de dados, submits sem reload).
- Test navigation from other parts of the system that link to this feature.

### 12. Refactor UI (Optional)
If the migrated views use Bootstrap classes, apply the `/ui-refactor` workflow to convert to the TAG Design System.
