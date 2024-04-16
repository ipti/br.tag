<?php

class CourseplanModule extends CWebModule
{

	public $defaultController = "courseplan";

	public $baseScriptUrl;
	public $baseUrl;


	public function init()
	{

		$this->baseUrl = Yii::app()->createUrl("courseplan");
		$this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.professional.resources'));

		$this->setImport(array(
			'courseplan.models.*',
			'courseplan.components.*',
		));
	}




	public function beforeControllerAction($controller, $action){
		
		$controller->layout='webroot.themes.default.views.layouts.fullmenu';
		
		if(parent::beforeControllerAction($controller, $action))
		{

			return true;
		}
		else
			return false;
	}
}
