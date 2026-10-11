<?php

class InventoryModule extends CWebModule
{
    public $defaultController = 'movement';
    public $layout = 'webroot.themes.default.views.layouts.fullmenu';
    public $baseScriptUrl;

    public function init()
    {
        $this->setImport(['application.modules.inventory.InventoryRoutes']);
        $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.inventory.resources'));

        // import the module-level models and components
        $this->setImport(
            [
                'inventory.models.*',
                'inventory.components.*',
            ]
        );
    }

    public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            $controller->layout = $this->layout;
            return true;
        } else {
            return false;
        }
    }
}
