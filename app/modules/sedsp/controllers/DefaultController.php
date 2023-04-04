<?php

Yii::import('application.modules.sedsp.usecases.*');

class DefaultController extends Controller
{
	public function actionIndex($id)
	{

        $school_id = Yii::app()->user->school;
        $ucidentifymulti = new IdentifyMultipleStudentRACode();
        $students = $ucidentifymulti->exec($school_id);
        //@todo precisa ajustar para carregar os alunos das turmas do ano letivo atual e da escola atual
        $this->render('index', ['students' => $students]);
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

    public function actionGenRA($id)
    {
        if(!isset(Yii::app()->request->cookies['SED_TOKEN'])) {
            $uclogin = new LoginUseCase();
            $uclogin->exec("SME701", "zyd780mhz1s5");
        }
        $genRA = new GenRA();
        $msg = $genRA->exec($id);
        if(!$msg){
            $msg = $genRA->exec($id,true);
        }
        echo $msg;
    }
	public function actionCreateRA($id){
	    $createRA = new CreateRA();
	    $createRA->exec($id);

        //@todo
    }
}