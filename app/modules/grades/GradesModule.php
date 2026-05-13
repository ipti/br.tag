<?php

class GradesModule extends CWebModule
{
    public $baseScriptUrl;
    public $baseUrl;

    public function init()
    {
        $this->baseUrl = Yii::app()->createUrl('grades');
        $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(
            Yii::getPathOfAlias('application.modules.grades.resources')
        );
        $this->layout = 'webroot.themes.default.views.layouts.fullmenu';
        $this->setImport([
            'grades.usecases.*',
            'grades.exceptions.*',
        ]);
    }

    public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action)) {
            $controller->layout = $this->layout;
            return true;
        }
        return false;
    }
}
