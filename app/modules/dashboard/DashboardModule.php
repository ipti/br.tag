<?php

class DashboardModule extends CWebModule
{
	public $baseScriptUrl;
    public $baseUrl;
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->baseUrl = Yii::app()->createUrl("dashboard");


        $this->baseScriptUrl = Yii::app()->getAssetManager()
            ->publish(Yii::getPathOfAlias('application.modules.dashboard.resources'));
		$this->setImport(
			array(
			'dashboard.models.*',
			'dashboard.components.*',
			'dashboard.services.*',
			'dashboard.usecases.*',
		)
	);
		
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
