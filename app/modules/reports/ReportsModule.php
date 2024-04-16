<?php

class ReportsModule extends CWebModule
{
    public $defaultController = "reports";
    public $baseScriptUrl;
	public $baseUrl;
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
        $this->baseUrl = Yii::app()->createUrl("reports");
        $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.reports.resources'));
		$this->setImport(array(
			'reports.models.*',
			'reports.components.*',
            'reports.repository.*'
		));
	}

	public function beforeControllerAction($controller, $action)
	{
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
