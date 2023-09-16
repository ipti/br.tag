<?php

Yii::import('application.modules.sedsp.usecases.*');

class DefaultController extends Controller
{
	private $ERROR_CONNECTION = 'Conexão com SEDSP falhou. Tente novamente mais tarde.';

	public function actionIndex()
	{
		$this->render('index');
	}

	private function checkSEDToken()
	{
		try {
			if (!isset(Yii::app()->request->cookies['SED_TOKEN'])) {
				$uclogin = new LoginUseCase();
				$uclogin->exec("SME701", "zyd780mhz1s5");
			}
		} catch (\Throwable $th) {
			Yii::app()->user->setFlash('error', Yii::t('default', $this->ERROR_CONNECTION));
			$this->redirect(array('index'));
		}
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
		)
		);
		$this->render('manageRA', ['dataProvider' => $dataProvider]);
	}

	public function actionAddStudentWithRA()
	{
		$RA = $_POST["ra"];

		$this->checkSEDToken();

		try {
			$createStudent = new CreateStudent();
			$response = $createStudent->exec($RA);
			if (!$response) {
				$response = $createStudent->exec($RA, true);
			}

			$modelStudentIdentification = $response["StudentIdentification"];
			$modelStudentDocumentsAndAddress = $response["StudentDocumentsAndAddress"];
			date_default_timezone_set("America/Recife");
			$modelStudentIdentification->last_change = date('Y-m-d G:i:s');
			$modelStudentDocumentsAndAddress->school_inep_id_fk = $modelStudentIdentification->school_inep_id_fk;
			$modelStudentDocumentsAndAddress->student_fk = $modelStudentIdentification->inep_id;

			// Validação CPF->Nome
			if ($modelStudentDocumentsAndAddress->cpf != null) {
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

			if ($modelStudentIdentification->validate() && $modelStudentIdentification->save()) {
				$modelStudentDocumentsAndAddress->id = $modelStudentIdentification->id;
				$modelStudentDocumentsAndAddress->save();
				if ($modelStudentDocumentsAndAddress->validate() && $modelStudentDocumentsAndAddress->save()) {
					$msg = 'O Cadastro de ' . $modelStudentIdentification->name . ' foi criado com sucesso!';
					Yii::app()->user->setFlash('success', Yii::t('default', $msg));
					$this->redirect(array('index'));
				}
			}
		} catch (\Throwable $th) {
			Yii::app()->user->setFlash('error', Yii::t('default', 'Ocorreu um erro ao cadastrar o aluno. Certifique-se de digitar um RA válido'));
			$this->redirect(array('index'));
		}
	}

	private function addClassroomStudent($RA, $classroomId, $classroomInepId, $classroomStage)
	{
		$this->checkSEDToken();

		$createStudent = new CreateStudent();
		$response = $createStudent->exec($RA);
		$modelStudentEnrollment = new StudentEnrollment;

		$modelStudentIdentification = $response["StudentIdentification"];
		$modelStudentDocumentsAndAddress = $response["StudentDocumentsAndAddress"];
		date_default_timezone_set("America/Recife");
		$modelStudentIdentification->last_change = date('Y-m-d G:i:s');
		$modelStudentDocumentsAndAddress->school_inep_id_fk = $modelStudentIdentification->school_inep_id_fk;
		$modelStudentDocumentsAndAddress->student_fk = $modelStudentIdentification->inep_id;

		if ($modelStudentIdentification->validate() && $modelStudentIdentification->save()) {
			$modelStudentDocumentsAndAddress->id = $modelStudentIdentification->id;
			$modelStudentEnrollment->school_inep_id_fk = Yii::app()->user->school;
			$modelStudentEnrollment->student_fk = $modelStudentIdentification->id;
			$modelStudentEnrollment->student_inep_id = $modelStudentIdentification->inep_id;
			$modelStudentEnrollment->classroom_fk = $classroomId;
			$modelStudentEnrollment->classroom_inep_id = $classroomInepId;
			$modelStudentEnrollment->edcenso_stage_vs_modality_fk = $classroomStage;
			if (
				$modelStudentDocumentsAndAddress->validate() && $modelStudentDocumentsAndAddress->save()
				&& $modelStudentEnrollment->validate() && $modelStudentEnrollment->save()
			) {
				return $modelStudentIdentification->name;
			}
		}
		return false;
	}

	public function actionAddClassroom()
	{
		try {
			$this->checkSEDToken();

			$classroomNum = $_POST["classroomNum"];
			$importStudents = isset($_POST["importStudents"]);
			$registerAllClasses = isset($_POST["registerAllClasses"]);
			$inep_id = Yii::app()->user->school;
			$year = date('Y');
			
			if ($classroomNum) {
				$this->registerClassroom($classroomNum);
			}
		
			if ($importStudents) {
				$params = new InFormacaoClasse($classroomNum);
				$formacaoClass = new GetFormacaoClasseFromSEDUseCase();
				$formacaoClass->exec($params);
			}

			$this->redirect(array('index'));

		} catch (\Exception $e) {
			Yii::app()->user->setFlash('error', Yii::t('default', $e->getMessage()));
			$this->redirect(array('index'));
		}
	}

	/**
	 * Summary of registerClassroom
	 * @param string $classroomNum
	 * @return void
	 */
	private function registerClassroom($classroomNum)
	{
		$createClassroom = new CreateClassroomUsecase();
		$existingClassroom = Classroom::model()->find('inep_id=:inep_id or gov_id=:inep_id', array(':inep_id' => $classroomNum));

		if ($existingClassroom) {
			$msg = "O Cadastro da Turma " . $existingClassroom->name . " já existe! <a href='" . Yii::app()->createUrl('classroom/update&id=' . $existingClassroom->id) . "' style='color:white;'>Clique aqui para visualizar.</a>";
			Yii::app()->user->setFlash('error', Yii::t('default', $msg));
			return;
		}
		
		$modelClassroom = $createClassroom->exec(Yii::app()->user->year, $classroomNum);
		if ($modelClassroom) {
			$msg = "O Cadastro da Turma " . $modelClassroom->name . " foi criado com sucesso! <a href='" . Yii::app()->createUrl('classroom/update&id=' . $modelClassroom->id) . "' style='color:white;'>Clique aqui para visualizar.</a>";
			Yii::app()->user->setFlash('success', Yii::t('default', $msg));
		}
	}

	public function actionAddSchool()
	{
		$school_name = $_POST["schoolName"];
		$school_mun = $_POST["schoolMun"];

		$this->checkSEDToken();

		try {
			$createSchool = new CreateSchool();
			$response = $createSchool->exec($school_name, $school_mun);
			$modelSchool = $response["SchoolIdentification"];
			$modelSchoolStructure = new SchoolStructure;
			$modelSchoolStructure->school_inep_id_fk = $modelSchool->inep_id;
			// Bloqueio de duplicação por inep id
			if ($modelSchool->inep_id != null) {
				$school_test_name = SchoolIdentification::model()->find(
					'inep_id=:inep_id',
					array(':inep_id' => $modelSchool->inep_id)
				);
				if (isset($school_test_name)) {
					$msg = "O Cadastro da Escola " . $modelSchool->name . " já existe!
					<a href='" . Yii::app()->createUrl('school/update&id=' . $modelSchool->inep_id) . "' style='color:white;'>
					Clique aqui para visualizar.</a>";
					Yii::app()->user->setFlash('error', Yii::t('default', $msg));
					$this->redirect(array('index'));
				}
			}
			if (
				$modelSchool->validate() && $modelSchoolStructure->validate()
				&& $modelSchool->save() && $modelSchoolStructure->save()
			) {
				$msg = "O Cadastro da Escola " . $modelSchool->name . " foi criado com sucesso!
				<a href='" . Yii::app()->createUrl('school/update&id=' . $modelSchool->inep_id) . "' style='color:white;'>
				Clique aqui para visualizar.</a>";
				Yii::app()->user->setFlash('success', Yii::t('default', $msg));
				$this->redirect(array('index'));
			}
		} catch (\Exception $e) {
			Yii::app()->user->setFlash('error', Yii::t('default', $e->getMessage()));
			$this->redirect(array('index'));
		}
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
		$this->checkSEDToken();

		$genRA = new GenRA();
		$msg = $genRA->exec($id);
		try {
			if (!$msg) {
				$msg = $genRA->exec($id, true);
			}
			echo $msg;
		} catch (\Throwable $th) {
			header('Content-Type: application/json', true, 400);
			echo CJSON::encode(
				array(
					'success' => false,
					'message' => 'Bad Request',
					'id' => $id,
				)
			); // Set the HTTP response code to 400
			Yii::app()->end();
		}
	}
	public function actionCreateRA($id)
	{
		$this->checkSEDToken();

		$createRA = new CreateRA();
		$msg = $createRA->exec($id);

		if (!$msg) {
			$msg = $createRA->exec($id, true);
		}
		echo $msg;
	}

	public function actionImportFullSchool()
	{
		$this->checkSEDToken();
		try {
			$inConsult = new InEscola($_POST["schoolName"], null, null, null);
			$escola = new GetEscolasFromSEDUseCase();
			$statusSave = $escola->exec($inConsult);

			if ($statusSave) {
				Yii::app()->user->setFlash('success', "Escola e classes importadas com sucesso.");
			} else {
				Yii::app()->user->setFlash('error', "Erro ao importar a escola");
			}

			$this->redirect(array('index'));
		} catch (Exception $e) {
			CVarDumper::dump($e->getMessage(), 10, true);
		}
	}

	public function actionImportStudentRA()
	{
		$this->checkSEDToken();

		try {
			$inAluno = new InAluno($_POST["numRA"], null, "SP");

			$exibirFicha = new GetExibirFichaAlunoFromSEDUseCase();
			$statusSave = $exibirFicha->exec($inAluno);

			if ($statusSave) {
				Yii::app()->user->setFlash('success', "O Aluno cadastrado com sucesso.");
			} else {
				Yii::app()->user->setFlash('error', "O Aluno já está cadastrado");
			}

			$this->redirect(array('index'));
		} catch (Exception $e) {
			Yii::app()->user->setFlash('error', "A escola do aluno não está cadastrada no TAG");
			$this->redirect(array('index'));
		}
	}

	public function actionImportFullStudentsByClasses()
	{
		$this->checkSEDToken();
		
		$selectedClasses = Yii::app()->request->getPost('selectedClasses', '');	
		$numClasses = explode(',', $selectedClasses);
	
		$relacaoClasse = new GetRelacaoClassesFromSEDUseCase();
		foreach ($numClasses as $numClasse) {
			$relacaoClasse->getStudentsFromClass($numClasse);
		}
	}

	public function actionImportSchool()
	{
		$this->checkSEDToken();

			$inConsult = new InEscola("JOSE DE ANCHIETA PADRE EM", null, null, null);
			$escola = new GetEscolasFromSEDUseCase();
			$escola->createSchool($inConsult);
	}
}