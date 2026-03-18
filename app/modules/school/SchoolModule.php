<?php

class SchoolModule extends CWebModule
{
    public $defaultController = 'school';
    public $baseScriptUrl;

    public function init()
    {
        $this->baseScriptUrl = Yii::app()->getAssetManager()
            ->publish(Yii::getPathOfAlias('application.modules.school.resources'));

        $this->setImport([
            'school.models.*',
        ]);
    }

    public function beforeControllerAction($controller, $action)
    {
        $controller->layout = 'webroot.themes.default.views.layouts.fullmenu';

        return parent::beforeControllerAction($controller, $action);
    }
}
