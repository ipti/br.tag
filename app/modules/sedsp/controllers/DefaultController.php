<?php

Yii::import('application.modules.sedsp.usecases.*');

class DefaultController extends Controller
{
	public function actionIndex($id)
	{

        $school_id = Yii::app()->user->school;
        $ucidentifymulti = new IdentifyMultipleStudentRACode();
        $students = $ucidentifymulti->exec($school_id);
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
        $ucidentify = new IdentifyStudentRACode();
        $resultRA = $ucidentify->exec($id);
        if(method_exists($resultRA,'getoutSucesso')){
            if(!isset($resultRA->outErro)){
                $ucadd = new AddRACodeToTAG();
                $student = $ucadd->exec($id,$resultRA->outAluno->outNumRA);
                echo $student->gov_id;
            }else {
                echo $resultRA->outErro;
                //echo 'criando..';
                //$ucnewstudent = new AddStudentToSED();
                //$RA = $ucnewstudent->exec($id);
            }
        }else if($resultRA->getHasResponse()){
                if($resultRA->getCode()==400){
                    echo $resultRA->getoutErro();
                }else if($resultRA->getCode()==500){
                    echo 'erro 500';
                }
        }else{
            echo 'tentar novamente';
            //tentar novamente
        }
    }
	
}