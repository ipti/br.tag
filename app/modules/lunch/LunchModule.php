<?php

class LunchModule extends CWebModule {

	public $baseScriptUrl;
	public $baseUrl;

	public function init() {
        $this->baseUrl = Yii::app()->createUrl("lunch");
        $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.lunch.resources'));
        $this->layoutPath = yii::getPathOfAlias("lunch.views.layouts");
        $this->layout = "layout";

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

	public function beforeControllerAction($controller, $action){
		if(parent::beforeControllerAction($controller, $action)){
			return true;
		}
		else
			return false;
	}
}
