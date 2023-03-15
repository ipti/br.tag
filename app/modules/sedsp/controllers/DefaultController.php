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

	public function actionLogin()
	{
		$usecase = new LoginUseCase();
		$RA = $usecase->exec("SME701", "zyd780mhz1s5");
		$this->render('index', ['RA' => $RA]);
	}

	public function actionSyncSchoolClassrooms()
	{
		$school_id = Yii::app()->user->school;
		$year = Yii::app()->user->year;
		$usecase = new IdentifyAllClassroomRABySchool();
		$RA = $usecase->exec($school_id, $year);
		$this->render('index', ['RA' => $RA]);
	}
	public function actionCreate($id)
	{
		$usecase = new AddStudentToSED();
		$RA = $usecase->exec($id);
		$this->render('index', ['RA' => $RA]);
	}
	
}