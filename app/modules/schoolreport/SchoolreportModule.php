<?php

class SchoolreportModule extends CWebModule{
	public $baseScriptUrl;
	public $baseUrl;

	public function init(){
		$this->baseUrl = Yii::app()->createUrl("schoolreport");
		$this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.schoolreport.resources'));
		$this->layoutPath = yii::getPathOfAlias("schoolreport.views.layouts");
		$this->layout = "schoolreport";

		$this->setImport(array(
			'schoolreport.models.*',
			'schoolreport.components.*',
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
