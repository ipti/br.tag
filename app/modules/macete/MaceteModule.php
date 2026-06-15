<?php

class MaceteModule extends CWebModule
{
    public $defaultController = 'lessonPlan';
    public $baseScriptUrl;
    public $baseUrl;

    public function init()
    {
        $this->baseUrl = Yii::app()->createUrl('macete');

        $this->baseScriptUrl = Yii::app()->getAssetManager()
            ->publish(Yii::getPathOfAlias('application.modules.macete.resources'), false, -1, YII_DEBUG);

        $this->setImport([
            'application.modules.macete.MaceteRoutes',
            'macete.models.*',
            'macete.services.*',
            'macete.controllers.*',
        ]);
    }

    public function beforeControllerAction($controller, $action)
    {
        $controller->layout = 'webroot.themes.default.views.layouts.fullmenu';

        return parent::beforeControllerAction($controller, $action);
    }
}
