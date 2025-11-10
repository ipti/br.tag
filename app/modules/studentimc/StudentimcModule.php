<?php

class StudentimcModule extends CWebModule
{
    // public $defaultController = 'studentimc';
    public $baseScriptUrl;
    public $baseUrl;
    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        $this->baseUrl = Yii::app()->createUrl("studentimc");
        $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.studentimc.resources'));
        // import the module-level models and components
        $this->setImport(array(
            'studentimc.models.*',
            'studentimc.components.*',
            'studentimc.resources.*',
        ));
    }

    public function beforeControllerAction($controller, $action)
    {
        $controller->layout = 'webroot.themes.default.views.layouts.fullmenu';
        return parent::beforeControllerAction($controller, $action);
    }
}
