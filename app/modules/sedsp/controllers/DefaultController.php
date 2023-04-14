<?php

Yii::import('application.modules.sedsp.usecases.*');

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionManageRA()
	{
		$school_id = Yii::app()->user->school;
		$ucidentifymulti = new IdentifyMultipleStudentRACode();
		$students = $ucidentifymulti->exec($school_id);

		$criteira = new CDbCriteria;
		$criteira->select = 'DISTINCT t.*';
		$criteira->join = 'LEFT JOIN student_enrollment se ON se.student_inep_id = t.inep_id 
				   LEFT JOIN classroom class ON se.classroom_fk = class.id';
		$criteira->addCondition('t.school_inep_id_fk = :school_id');
		$criteira->addCondition('t.gov_id is null');
		$criteira->addCondition('class.school_year = :year');
		$criteira->params = array(':school_id' => $school_id, ':year' => Yii::app()->user->year);

		$dataProvider = new CActiveDataProvider('StudentIdentification', array(
			'criteria' => $criteira,
			'countCriteria' => $criteira,
			'pagination' => array('PageSize' => 100),
		));
		$this->render('manageRA', ['dataProvider' => $dataProvider]);
	}

	public function actionAddStudentWithRA()
	{
		$RA = $_POST["ra"];
		if (!isset(Yii::app()->request->cookies['SED_TOKEN'])) {
			$uclogin = new LoginUseCase();
			$uclogin->exec("SME701", "zyd780mhz1s5");
		}
		$createStudent = new CreateStudent();
		$response = $createStudent->exec($RA);
		if (!$response) {
			$response = $createStudent->exec($RA, true);
		}
		$modelStudentIdentification = new StudentIdentification;
		$modelStudentDocumentsAndAddress = new StudentDocumentsAndAddress;

		$modelStudentIdentification = $response["StudentIdentification"];
		$modelStudentDocumentsAndAddress = $response["StudentDocumentsAndAddress"];
		date_default_timezone_set("America/Recife");
        $modelStudentIdentification->last_change = date('Y-m-d G:i:s');
		$modelStudentDocumentsAndAddress->school_inep_id_fk = $modelStudentIdentification->school_inep_id_fk;
        $modelStudentDocumentsAndAddress->student_fk = $modelStudentIdentification->inep_id;

		// Validação CPF->Nome
		if($modelStudentDocumentsAndAddress->cpf != null) {
			$student_test_cpf = StudentDocumentsAndAddress::model()->find('cpf=:cpf', array(':cpf' => $modelStudentDocumentsAndAddress->cpf));
			if (isset($student_test_cpf)) {
				Yii::app()->user->setFlash('error', Yii::t('default', "O Aluno já está cadastrado"));
				$this->redirect(array('index'));
			}
		}
		if ($modelStudentIdentification->name != null) {
			$student_test_name = StudentIdentification::model()->find('name=:name', array(':name' => $modelStudentIdentification->name));
			if (isset($student_test_name)) {
				Yii::app()->user->setFlash('error', Yii::t('default', "O Aluno já está cadastrado"));
				$this->redirect(array('index'));
			}
		}

		if($modelStudentIdentification->validate() && $modelStudentIdentification->save()) {
			$modelStudentDocumentsAndAddress->id = $modelStudentIdentification->id;
			$modelStudentDocumentsAndAddress->save();
			if($modelStudentDocumentsAndAddress->validate() && $modelStudentDocumentsAndAddress->save()) {
				$msg = 'O Cadastro de ' . $modelStudentIdentification->name . ' foi criado com sucesso!';
				Yii::app()->user->setFlash('success', Yii::t('default', $msg));
				$this->redirect(array('index'));
			}
		}
		Yii::app()->user->setFlash('error', Yii::t('default', 'Ocorreu um erro ao cadastrar o aluno'));
		$this->redirect(array('index'));
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
		if (!isset(Yii::app()->request->cookies['SED_TOKEN'])) {
			$uclogin = new LoginUseCase();
			$uclogin->exec("SME701", "zyd780mhz1s5");
		}
		$genRA = new GenRA();
		$msg = $genRA->exec($id);
		try {
			if (!$msg) {
				$msg = $genRA->exec($id, true);
			}
			echo $msg;
		} catch (\Throwable $th) {
			header('Content-Type: application/json', true, 400);
            echo CJSON::encode(array(
                'success' => false,
                'message' => 'Bad Request',
				'id' => $id,
            )); // Set the HTTP response code to 400
            Yii::app()->end();
		}
		
		
	}
	public function actionCreateRA($id)
	{
		if (!isset(Yii::app()->request->cookies['SED_TOKEN'])) {
			$uclogin = new LoginUseCase();
			$uclogin->exec("SME701", "zyd780mhz1s5");
		}
		$createRA = new CreateRA();
		$msg = $createRA->exec($id);

		if (!$msg) {
			$msg = $createRA->exec($id, true);
		}
		echo $msg;
	}
}
