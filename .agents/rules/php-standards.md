---
trigger: always_on
---

# PHP Standards and Quality

Ensures the code follows the project's quality standards and uses the available automated tools.

## Static Analysis and Linting
- **PHPMD**: Always run `docker exec tag-app ./vendor/bin/phpmd` to check for architectural flaws, unused variables, etc.
- **PHP-CS-Fixer**: Always check code formatting before submitting: `docker exec tag-app ./vendor/bin/php-cs-fixer fix`.
- **PHP Version**: The runtime is PHP 8.3 (see `Dockerfile`). Use modern PHP practices.

## Yii 1.1 Best Practices

### Model (M) — `CActiveRecord`
- Models extend `CActiveRecord` and represent database tables.
- **All business logic** belongs in the Model (or a dedicated Service class), never in the Controller.
- Always define `rules()` for validation, `relations()` for relationships, and `attributeLabels()` for form labels.
- Use `CDbCriteria` for complex queries instead of raw SQL:
  ```php
  $criteria = new CDbCriteria();
  $criteria->compare('status', 1);
  $criteria->addCondition('created_at > :date');
  $criteria->params[':date'] = '2025-01-01';
  $models = MyModel::model()->findAll($criteria);
  ```
- **Security**: Always use parameter binding (`:param`). Never concatenate user input into SQL.
- Use scopes for reusable query filters:
  ```php
  public function scopes() {
      return ['active' => ['condition' => 'status=1']];
  }
  ```

### Controller (C)
- Controllers must be **thin**: only handle request/response flow.
- Follow the action naming convention: `actionCreate`, `actionUpdate`, `actionDelete`, `actionIndex`, `actionView`.
- Always use `accessRules()` for access control:
  ```php
  public function accessRules() {
      return [
          ['allow', 'actions' => ['index', 'view'], 'users' => ['@']],
          ['deny', 'users' => ['*']],
      ];
  }
  ```
- Use `$this->redirect()` after POST operations (PRG pattern).
- Validate `$_POST` data through the model, never manually in the controller.

### View (V)
- Views are `.php` files that only handle **presentation**.
- Never put business logic or database queries in views.
- Use `$this->renderPartial()` for reusable UI fragments.
- Use `CHtml` helpers for generating HTML forms and links:
  ```php
  CHtml::link('Text', ['controller/action', 'id' => $model->id]);
  CHtml::activeTextField($model, 'name');
  ```

### General
- **Dependencies**: Use the provided `composer.json` for managing backend dependencies.
- **URL Routing**: Use Yii's URL manager with `['controller/action', 'param' => value]` arrays, never hardcode URLs.

## New Features — Yii Module Pattern
- **Always** create new features as Yii modules inside `app/modules/<module_name>/`.
- Each module must follow this structure:
  ```
  app/modules/<module_name>/
  ├── <ModuleName>Module.php    # extends CWebModule
  ├── controllers/
  │   └── <Name>Controller.php
  ├── models/
  │   └── <ModelName>.php
  └── views/
      └── <controllerName>/
          └── <viewName>.php
  ```
- The module class must extend `CWebModule`, set the layout, and import its own models:
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
- Do **not** add controllers or views to the root `app/controllers/` or `themes/` for new features.
