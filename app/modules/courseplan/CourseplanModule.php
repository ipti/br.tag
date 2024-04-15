<?php

class CourseplanModule extends CWebModule
{

	public $defaultController = "courseplan";
	public function init()
	{
		$this->setImport(array(
			'courseplan.models.*',
			'courseplan.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		$controller->layout='webroot.themes.default.views.layouts.fullmenu';
		
		if(parent::beforeControllerAction($controller, $action))
		{

			return true;
		}
		else
			return false;
	}
}
