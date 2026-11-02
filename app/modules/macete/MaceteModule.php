<?php

class MaceteModule extends CWebModule
{
    public $defaultController = 'lessonsplan';
    public $baseScriptUrl;
    public $baseUrl;

    public function init()
    {
        $this->baseUrl = Yii::app()->createUrl('macete');

        $this->baseScriptUrl = Yii::app()->getAssetManager()
            ->publish(Yii::getPathOfAlias('application.modules.macete.resources'), false, -1, true);

        $this->setImport([
            'application.modules.macete.MaceteRoutes',
            'macete.models.*',
            'macete.services.*',
            'macete.controllers.*',
        ]);
    }

    public function beforeControllerAction($controller, $action)
    {
        if (!Yii::app()->features->isEnable(TTask::TASK_MACETE)) {
            throw new CHttpException(403, 'O módulo MACETE não está habilitado para este município.');
        }

        $controller->layout = 'webroot.themes.default.views.layouts.fullmenu';

        return parent::beforeControllerAction($controller, $action);
    }
}
