<?php

class SchoolreportModule extends CWebModule
{
    public $baseScriptUrl;
    public $baseUrl;

    public function init()
    {
        $this->baseUrl = Yii::app()->createUrl('schoolreport');
        $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.schoolreport.resources'));
        $this->layoutPath = yii::getPathOfAlias('schoolreport.views.layouts');
        $this->layout = 'schoolreport';

        $this->setImport([

            'application.modules.schoolreport.SchoolreportRoutes',
            'schoolreport.models.*',
            'schoolreport.components.*',
            'schoolreport.repositories.*',
        ]);
    }

    public function beforeControllerAction($controller, $action)
    {
        if ($controller->id === 'default') {
            Yii::app()->setComponents([
                'errorHandler' => [
                    'errorAction' => 'schoolreport/default/error',
                ],
                'user' => [
                    'class' => 'CWebUser',
                    'loginUrl' => Yii::app()->createUrl('schoolreport/default/login'),
                ]
            ]);

            Yii::app()->user->setStateKeyPrefix('_schoolreport');
        }

        return parent::beforeControllerAction($controller, $action);
    }
}
