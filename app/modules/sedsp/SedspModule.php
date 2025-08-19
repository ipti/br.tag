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
			'sedsp.models.Student.*',
			'sedsp.models.School.*',
			'sedsp.models.Performance.*',
			'sedsp.models.Enrollment.*',
			'sedsp.models.Classroom.*',
			'sedsp.models.ClassStudentsRelation.*',
			'sedsp.models.StudentClassroom.*',
			'sedsp.models.BasicData.*',
			'sedsp.components.*',
			'sedsp.datasources.*',
			'sedsp.datasources.sed.Classroom.*',
			'sedsp.datasources.sed.ClassStudentsRelation.*',
			'sedsp.datasources.sed.Enrollment.*',
			'sedsp.datasources.sed.Performance.*',
			'sedsp.datasources.sed.School.*',
			'sedsp.datasources.sed.Student.*',
			'sedsp.datasources.sed.StudentClassroom.*',
			'sedsp.datasources.sed.BasicData.*',
			'sedsp.datasources.tag.*',
			'sedsp.usecases.*',
			'sedsp.usecases.ClassStudentsRelation.*',
			'sedsp.usecases.Classroom.*',
			'sedsp.usecases.Student.*',
			'sedsp.usecases.School.*',
			'sedsp.usecases.tests.*',
			'sedsp.usecases.Enrollment.*',
			'sedsp.mappers.*',
			'sedsp.*'
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
