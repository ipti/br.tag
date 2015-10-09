<?php

class DefaultController extends CController{
	public $headerDescription = "";
	public function actionIndex(){
		$this->render('index');
	}
}