<?php

class SiteModule extends CWebModule
{

    public $defaultController = "site";
    public $baseScriptUrl;
	public $baseUrl;
	public function init()
	{
		$this->baseUrl = Yii::app()->createUrl("site");
		$this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.site.resources'));

		// import the module-level models and components
		$this->setImport(array(
			'site.models.*',
			'site.components.*',
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
