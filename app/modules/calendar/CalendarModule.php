<?php

class CalendarModule extends CWebModule {

	public $baseScriptUrl;
	public $baseUrl;

	public function init() {
		$this->baseUrl = Yii::app()->createUrl("calendar");
		$this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.calendar.resources'));
		//$this->layoutPath = yii::getPathOfAlias("calendar.views.layouts");

		$this->setImport(array(
			'calendar.models.*', 'calendar.components.*',
		));
	}

	public function beforeControllerAction($controller, $action) {
		$controller->layout = 'webroot.themes.default.views.layouts.fullmenu';

		if (parent::beforeControllerAction($controller, $action)) {
			return true;
		} else {
			return false;
		}
	}
}
