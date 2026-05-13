<?php

class FarmerModule extends CWebModule
{
    public $defaultController = 'default';
    public $baseScriptUrl;
    public $baseUrl;

    public function init()
    {
        $this->baseUrl = Yii::app()->createUrl('farmer');
        $this->baseScriptUrl = Yii::app()->getAssetManager()
            ->publish(Yii::getPathOfAlias('application.modules.farmer.resources'));

        $this->setImport([
            'farmer.components.*',
            'application.modules.foods.models.*',
            'application.modules.foods.services.*',
            'application.modules.foods.usecases.*',
        ]);
    }

    public function beforeControllerAction($controller, $action)
    {
        $controller->layout = 'webroot.themes.default.views.layouts.fullmenu';
        return parent::beforeControllerAction($controller, $action);
    }
}
