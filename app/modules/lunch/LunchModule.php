<?php

class LunchModule extends CWebModule
{
    public $baseScriptUrl;
    public $baseUrl;

    public function init()
    {
        $this->baseUrl = Yii::app()->createUrl("lunch");
        $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.lunch.resources'));

        Yii::app()->setComponents([
            'errorHandler' => [
                'errorAction' => 'lunch/default/error',
            ],
        ]);

        $this->setImport(array(
            'lunch.models.*',
            'lunch.components.*',
        ));
    }

    public function beforeControllerAction($controller, $action)
    {
        $controller->layout = 'webroot.themes.default.views.layouts.fullmenu';

        if (parent::beforeControllerAction($controller, $action)) {
            return true;
        } else {
            return false;
        }
    }
}
