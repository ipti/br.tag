<?php

class AeeRecordModule extends CWebModule
{
    public $baseScriptUrl;
    public $baseUrl;
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
        $this->baseUrl = Yii::app()->createUrl("aeerecord");


        $this->baseScriptUrl = Yii::app()->getAssetManager()
            ->publish(Yii::getPathOfAlias('application.modules.aeerecord.resources'));
		// import the module-level models and components
		$this->setImport(array(
			'aeerecord.models.*',
			'aeerecord.components.*',
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
