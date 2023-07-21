<?php

class SedspModule extends CWebModule
{
	public $baseScriptUrl;
	public $baseUrl;

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		$this->baseUrl = Yii::app()->createUrl("sedsp");
		$this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.sedsp.resources'));

		// import the module-level models and components
		$this->setImport(array(
			'sedsp.models.*',
			'sedsp.models.student.*',
			'sedsp.models.school.*',
			'sedsp.models.performance.*',
			'sedsp.models.enrollment.*',
			'sedsp.models.classroom.*',
			'sedsp.models.studentclassroom.*',
			'sedsp.components.*',
			'sedsp.datasources.*',
			'sedsp.datasources.sed.classroom.*',
			'sedsp.datasources.sed.enrollment.*',
			'sedsp.datasources.sed.performance.*',
			'sedsp.datasources.sed.school.*',
			'sedsp.datasources.sed.student.*',
			'sedsp.datasources.sed.studentclassroom.*',
			'sedsp.usecases.*',
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
