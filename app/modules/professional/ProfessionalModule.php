<?php

class ProfessionalModule extends CWebModule
{
	public $baseScriptUrl;
	public $baseUrl;

	public function init() {
		$this->baseUrl = Yii::app()->createUrl("professional");
		$this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.professional.resources'));
		//$this->layoutPath = yii::getPathOfAlias("professional.views.layouts");

		$this->setImport(array(
			'professional.models.*', 'professional.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		$controller->layout = 'webroot.themes.default.views.layouts.fullmenu';
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
