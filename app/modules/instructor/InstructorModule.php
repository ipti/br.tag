<?php

class InstructorModule extends CWebModule
{
    public $baseScriptUrl;
    public $baseUrl;

    public function init()
    {
        $this->baseUrl = Yii::app()->createUrl('instructor');
        $this->setImport([
            'instructor.models.*',
            'instructor.components.*',
            'instructor.usecases.*',
        ]);
    }

    public function beforeControllerAction($controller, $action)
    {
        $controller->layout = 'webroot.themes.default.views.layouts.fullmenu';
        return parent::beforeControllerAction($controller, $action);
    }
}
