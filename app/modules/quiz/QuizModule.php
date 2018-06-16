<?php

class QuizModule extends CWebModule
{
	public $baseScriptUrl;
	public $baseUrl;
	
	public function init() {
		$this->baseUrl = Yii::app()->createUrl("quiz");
		$this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.quiz.resources'));
		//$this->layoutPath = yii::getPathOfAlias("calendar.views.layouts");

		$this->setImport(array(
			'quiz.models.*', 'quiz.components.*',
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
