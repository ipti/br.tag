<?php

class ResultsManagementModule extends CWebModule
{
    public $baseScriptUrl;
    public $baseUrl;

    public function init()
    {
        $this->baseUrl = Yii::app()->createUrl('resultsmanagement');
        $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.resultsmanagement.resources'));
        $this->layout = 'webroot.themes.default.views.layouts.resultsmanagement';
        $this->setImport([
            'resultsmanagement.models.*',
            'resultsmanagement.components.*',
        ]);
    }

    public function beforeControllerAction($controller, $action)
    {
        return parent::beforeControllerAction($controller, $action);
    }
}
