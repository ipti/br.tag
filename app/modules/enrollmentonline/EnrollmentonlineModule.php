<?php

class EnrollmentonlineModule extends CWebModule
{

    public $defaultController = 'enrollmentonlinestudentidentificationController';
	public $baseScriptUrl;
	public $baseUrl;

	public function init()
	{
		$this->baseUrl = Yii::app()->createUrl("enrollmentonline");
		$this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.enrollmentonline.resources'));
		$this->setImport(array(
			'enrollmentonline.models.*',
			'enrollmentonline.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		$controller->layout = 'webroot.themes.default.views.layouts.fullmenu';
		return parent::beforeControllerAction($controller, $action);
	}
}
