<?php

Yii::import('application.modules.sedsp.usecases.*');

class DefaultController extends Controller
{
	public function actionIndex($id)
	{
		$identify_student_ra_usecase = new IdentifyStudentRACode();
		$dados_aluno = $identify_student_ra_usecase->exec($id);
		$this->render('index', ['RA' => $dados_aluno]);
	}

	public function actionLogin()
	{
		$usecase = new LoginUseCase();
		$token = $usecase->exec("SME701", "zyd780mhz1s5");
		$this->render('index', ['token' => $token]);
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