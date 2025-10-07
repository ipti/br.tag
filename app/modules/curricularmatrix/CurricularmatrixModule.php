<?php

    class CurricularmatrixModule extends CWebModule
    {
        public $defaultController = 'curricularmatrix';
        public $baseScriptUrl;
        public $baseUrl;

        public function init()
        {
            $this->baseUrl = Yii::app()->createUrl('curricularmatrix');
            $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.curricularmatrix.resources'));
            //$this->layoutPath = yii::getPathOfAlias("curricularmatrix.views.layouts");

            $this->setImport([
                'curricularmatrix.models.*', 'curricularmatrix.components.*',
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
