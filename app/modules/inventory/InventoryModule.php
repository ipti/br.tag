<?php

class InventoryModule extends CWebModule
{
    public $defaultController = 'movement';

    public function init()
    {
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
            $controller->layout = 'webroot.themes.default.views.layouts.fullmenu';
            return true;
        } else {
            return false;
        }
    }
}
