<?php

class ResultsManagementModule extends CWebModule{
	public $baseScriptUrl;
	public $baseUrl;

	public function init()	{
//		Yii::setPathOfAlias('RMResources', Yii::getPathOfAlias('application.modules.resultsmanagementModule.managementSchool.resources'));
		$this->baseUrl = Yii::app()->createUrl("resultsmanagement");
		$this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.resultsmanagement.resources'));
		$this->layout = "webroot.themes.default.views.layouts.resultsmanagement";
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'resultsmanagement.models.*',
			'resultsmanagement.components.*',
		));
	}

    public function beforeControllerAction($controller, $action){

        if(parent::beforeControllerAction($controller, $action)){
            // this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
