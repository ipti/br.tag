<?php

class DefaultController extends Controller {
	public $layout = "layout";

	public function actionIndex(){
		$this->render('index');
	}


	public function actionError(){
		$this->layout = "error";
		if ($error = Yii::app()->errorHandler->error) {
			if (Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', ['error'=>$error]);
		}
	}
}