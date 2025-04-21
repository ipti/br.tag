<?php

class OnlineenrollmentModule extends CWebModule
{
    public $defaultController = 'onlineenrollment';
    public $baseScriptUrl;
    public $baseUrl;
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
        $this->baseUrl = Yii::app()->createUrl("onlineenrollment");

        $this->baseScriptUrl = Yii::app()->getAssetManager()
            ->publish(Yii::getPathOfAlias('application.modules.onlineenrollment.resources'));

		// import the module-level models and components
		$this->setImport(array(
			'onlineenrollment.models.*',
			'onlineenrollment.components.*',
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
