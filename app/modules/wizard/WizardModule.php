<?php

class WizardModule extends CWebModule
{
    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport([
            'wizard.models.*',
            'wizard.components.*',
        ]);
    }

    public function beforeControllerAction($controller, $action)
    {
        $controller->layout = 'webroot.themes.default.views.layouts.fullmenu';

        return parent::beforeControllerAction($controller, $action);
    }
}
