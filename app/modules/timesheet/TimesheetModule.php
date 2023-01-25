<?php

    class TimesheetModule extends CWebModule
    {
        public $defaultController = 'timesheet';
        public $baseScriptUrl;
        public $baseUrl;

        public function init()
        {
            $this->baseUrl = Yii::app()->createUrl("timesheet");
            $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.timesheet.resources'));
            //$this->layoutPath = yii::getPathOfAlias("timesheet.views.layouts");

            $this->setImport([
                'timesheet.models.*', 'timesheet.components.*',
            ]);
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
