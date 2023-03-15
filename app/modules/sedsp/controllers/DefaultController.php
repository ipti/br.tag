<?php

Yii::import('application.modules.sedsp.usecases.*');

class DefaultController extends Controller
{
	public function actionIndex($id)
	{
		$usecase = new IdentifyStudentRACode();
		$RA = $usecase->exec($id);
		$this->render('index', ['RA' => $RA]);
	}
}