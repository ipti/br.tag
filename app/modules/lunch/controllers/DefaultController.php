<?php

class DefaultController extends Controller {

	public function actionIndex(){
		$this->render('index');
	}

	public function actionError()
    {
        $this->layout = "error";
        $error = Yii::app()->errorHandler->error;
        if ($error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];

                return;
            }
            $this->render('error', ['error' => $error]);
        }
    }
}
