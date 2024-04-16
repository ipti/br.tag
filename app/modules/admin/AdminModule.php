<?php

class AdminModule extends CWebModule
{

    public $defaultController = "admin";
    public $baseScriptUrl;
	public $baseUrl;

	public function init()
	{
		$this->baseUrl = Yii::app()->createUrl("admin");
		$this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.admin.resources'));

		$this->setImport(array(
			'admin.models.*',
			'admin.components.*',
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
