<?php

class AbilitiesModule extends CWebModule
{
    public $defaultController = 'Courseclassabilities';
    public $baseScriptUrl;
    public $baseUrl;
	public function init()
	{

        $this->baseUrl = Yii::app()->createUrl("abilities");


        $this->baseScriptUrl = Yii::app()->getAssetManager()
            ->publish(Yii::getPathOfAlias('application.modules.abilities.resources'));

		$this->setImport(array(
			'abilities.models.*',
			'abilities.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
        $controller->layout = 'webroot.themes.default.views.layouts.fullmenu';

		return parent::beforeControllerAction($controller, $action);
    }

}
