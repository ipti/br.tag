<?php

class ReportsModule extends CWebModule
{
    public $defaultController = "reports";
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'reports.models.*',
			'reports.components.*',
            'reports.repository.*'
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
