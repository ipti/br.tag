<?php

class QuizModule extends CWebModule
{
    public $baseScriptUrl;
    public $baseUrl;

    public function init()
    {
        $this->baseUrl = Yii::app()->createUrl('quiz');
        $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.quiz.resources'));

        $this->setImport([
            'quiz.models.*', 'quiz.components.*',
        ]);
    }

    public function beforeControllerAction($controller, $action)
    {
        $controller->layout = 'webroot.themes.default.views.layouts.fullmenu';
        return parent::beforeControllerAction($controller, $action);
    }
}
