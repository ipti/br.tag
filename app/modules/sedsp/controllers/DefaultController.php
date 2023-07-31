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
		));
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
			if ($modelStudentDocumentsAndAddress->validate() && $modelStudentDocumentsAndAddress->save()
			&& $modelStudentEnrollment->validate() && $modelStudentEnrollment->save()) {
				return $modelStudentIdentification->name;
			}
		}
		return false;
	}

	public function actionAddClassroom()
	{
		try {
			$this->checkSEDToken();

			$importStudents  = isset($_POST["importStudents"]);
			$registerAllClasses = isset($_POST["registerAllClasses"]);

			$inep_id = Yii::app()->user->school;
			$year = date('Y');

			$classes =  new IdentifyAllClassroomRABySchool();
			$classNumbers = $classes->exec($inep_id, $year);
			$aux = count($classNumbers->classrooms);

			if($registerAllClasses){
				foreach ($classNumbers->classrooms as $classroom) {
					$this->registerClassroom($classroom->outNumClasse, $importStudents);
				}
			}else{
				$classroomNum = $_POST["classroomNum"];
				if ($classroomNum) {
					$this->registerClassroom($classroomNum, $importStudents);
				}
			}

			$this->redirect(array('index'));

		} catch (\Exception $e) {
			Yii::app()->user->setFlash('error', Yii::t('default', $e->getMessage()));
			$this->redirect(array('index'));
		}
	}

	private function registerClassroom($classroomNum, $importStudents)
	{
		$createClassroom = new CreateClassroom();
		$response = $createClassroom->exec($classroomNum);
		$modelClassroom = $response["Classroom"];
		$students = $response["Students"];

		if ($modelClassroom->inep_id != null) {
			$existingClassroom = Classroom::model()->find('inep_id=:inep_id', array(':inep_id' => $modelClassroom->inep_id));
			if ($existingClassroom) {
				$msg = "O Cadastro da Turma " . $modelClassroom->name . " já existe! <a href='".Yii::app()->createUrl('classroom/update&id='.$existingClassroom->id)."' style='color:white;'>Clique aqui para visualizar.</a>";
				Yii::app()->user->setFlash('error', Yii::t('default', $msg));
				return;
			}
		}

		if ($modelClassroom->validate() && $modelClassroom->save()) {
			$msg = "O Cadastro da Turma " . $modelClassroom->name . " foi criado com sucesso! <a href='".Yii::app()->createUrl('classroom/update&id='.$modelClassroom->id)."' style='color:white;'>Clique aqui para visualizar.</a>";
			if ($importStudents) {
				foreach ($students as $student) {
					$this->addClassroomStudent($student->outNumRA, $modelClassroom->id, $modelClassroom->inep_id, $modelClassroom->edcenso_stage_vs_modality_fk);
				}
			}

			Yii::app()->user->setFlash('success', Yii::t('default', $msg));
		}
	}

	public function actionAddSchool()
	{
		$school_name = $_POST["schoolName"];
		$school_mun = $_POST["schoolMun"];

		$this->checkSEDToken();

		try {
			$createSchool = new CreateSchool;
			$response = $createSchool->exec($school_name, $school_mun);
			$modelSchool = $response["SchoolIdentification"];
			$modelSchoolStructure = new SchoolStructure;
			$modelSchoolStructure->school_inep_id_fk = $modelSchool->inep_id;
			// Bloqueio de duplicação por inep id
			if ($modelSchool->inep_id != null) {
				$school_test_name = SchoolIdentification::model()->find('inep_id=:inep_id', 
				array(':inep_id' => $modelSchool->inep_id));
				if (isset($school_test_name)) {
					$msg = "O Cadastro da Escola " . $modelSchool->name . " já existe!
					<a href='".Yii::app()->createUrl('school/update&id='.$modelSchool->inep_id)."' style='color:white;'>
					Clique aqui para visualizar.</a>";
					Yii::app()->user->setFlash('error', Yii::t('default', $msg));
					$this->redirect(array('index'));
				}
			}
			if ($modelSchool->validate() && $modelSchoolStructure->validate() 
				&& $modelSchool->save() && $modelSchoolStructure->save()) {
				$msg = "O Cadastro da Escola " . $modelSchool->name . " foi criado com sucesso!
				<a href='".Yii::app()->createUrl('school/update&id='.$modelSchool->inep_id)."' style='color:white;'>
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
		$this->checkSEDToken();

		$createRA = new CreateRA();
		$msg = $createRA->exec($id);

		if (!$msg) {
			$msg = $createRA->exec($id, true);
		}
		echo $msg;
	}

	function actionTest() {

		$opt = 8;
		switch ($opt) {
			case 1:
				$inAluno = new InAluno("000124464761", null, "SP");
				$dataSource = new StudentSEDDataSource();
				$dataSource->exibirFichaAluno($inAluno);
				echo "<pre>";
				var_export($dataSource->exibirFichaAluno($inAluno));
				echo "</pre>";
				break;
			case 2:
				$inClassroom = new InClassroom("262429087");
				$dataSource = new ClassroomSEDDataSource();
				echo "<pre>";
				var_export($dataSource->getClassroom($inClassroom));
				echo "</pre>";
			case 3:
				$inConsult = new InConsultClass("2022", "262429087");
				$dataSource = new ClassroomSEDDataSource();
				echo "<pre>";
				var_export($dataSource->getConsultClass($inConsult));
				echo "</pre>";
			case 4:
				$inConsult = new InAluno("000124464761", "5", "SP");
				$dataSource = new EnrollmentSEDDataSource();
				echo "<pre>";
				var_export($dataSource->getListarMatriculasRA($inConsult));
				echo "</pre>";
			case 5:

				$search = [
					'InDadosPessoais' => [
						'inNomeAluno' => 'IGOR GONÇALVES'
					],
					'InDocumentos' => []
				];

				$inConsult = new InListarAlunos($search);
				$dataSource = new StudentSEDDataSource();
				echo "<pre>";
				var_export($dataSource->getListStudents($inConsult));
				echo "</pre>";

			case 6:
				$search = [
					'inAluno' => [
						'inNumRA' => "000124464761",
						'inDigitoRA' => "5",
						'inSiglaUFRA' => "SP"
					],
					'inDocumentosAluno' => [
						'inCPF' =>  null,
						'inNRRG' => null,
						'inUFRG' => null
					]
				];

				$inConsult = new InResponsavelAluno($search);
				$dataSource = new StudentSEDDataSource();
				echo "<pre>";
				var_export($dataSource->getConsultarResponsavelAluno($inConsult));
				echo "</pre>";
			case 7:
				$search = [ 
					'inNomeAluno' => 'EDUARDA LUCAS BERNARDES', 
					'inNomeMae' => 'BRUNA LUCAS FAGANELLI',
					'inDataNascimento' => '22/01/2019'
				];

				$inConsult = json_encode(new InDadosPessoais($search));
				$dataSource = new StudentSEDDataSource();
				echo "<pre>";
				var_export(json_decode(json_encode($dataSource->getStudentRA(null, json_decode($inConsult)))));
				echo "</pre>";
			case 8:
				$search = [
					'inDadosPessoais' => [
						'inNomeAluno' => 'Nathan Santos',
						'inNomeMae' => 'Maria',
						'inNomePai' => NULL,
						'inNomeSocial' => NULL,
						'inNomeAfetivo' => NULL,
						'inDataNascimento' => '22/04/1999',
						'inCorRaca' => 1, // Cor / Raça: 1 Branca | 2 Preta | 3 Parda | 4 Amarela | 5 Indígena | 6 Não Declarada
						'inSexo' => 1, // 1 – Masculino | 2 – Feminino
						'inBolsaFamilia' => NULL,
						'inQuilombola' => NULL,
						'inPossuiInternet' => 'S',
						'inPossuiNotebookSmartphoneTablet' => 'S',
						'inTipoSanguineo' => NULL,
						'inDoadorOrgaos' => NULL,
						'inNumeroCns' => NULL,
						'inEmail' => NULL,
						'inNacionalidade' => '1',
						'inNomeMunNascto' => 'Porto da Folha', // Opcional. Obrigatório quando inNacionalidade = 1
						'inUfMunNascto' => 'SE', // Opcional. Obrigatório quando inNacionalidade = 1
						'inCodMunNasctoDne' => NULL,
						'inDataEntradaPais' => '22/04/2018', // Opcional. Obrigatório quando inNacionalidade = 2
						'inCodPaisOrigem' => '76',
						'inPaisOrigem' => 'Brasil',
					],
					'inDocumentos' => [
						'inNumRG' => null,
						'inDigitoRG' => null, // Opcional. Obrigatório quando o documento civil for o RG, maior ou igual a 24.000.000 e inUFDoctoCivil = SP
						'inUFRG' => null,
						'inCPF' => null,
						'inNumNIS' => null,
						'inDataEmissaoDoctoCivil' => null,
						'inJustificativaDocumentos' => null,
						'inNumINEP' => null,
						'inNumCertidaoNova' => null,
					],					
					'inCertidaoNova' => [
						'inCertMatr01' => '999999',
						'inCertMatr02' => '99',
						'inCertMatr03' => '99',
						'inCertMatr04' => '9999',
						'inCertMatr05' => '9',
						'inCertMatr06' => '99999',
						'inCertMatr07' => '999',
						'inCertMatr08' => '9999999',
						'inCertMatr09' => '99',
						'inDataEmissaoCertidao' => '22/05/2023',                
					],
					'inCertidaoAntiga' => [
						'inNumCertidao' => '999999',
						'inLivro' => '2343',
						'inFolha' => '4343',
						'inDistritoCertidao' => 'Aracaju',
						'inMunicipioComarca' => 'Aracaju',
						'inUfComarca' => 'SE',
						'inDataEmissaoCertidao' => '22/05/2022',               
					],
					'inEnderecoResidencial' => [
						'inLogradouro' => 'Rua Francisco',
						'inNumero' => '67',
						'inBairro' => 'Centro',
						'inNomeCidade' => 'Itabaiana',
						'inUFCidade' => 'SE',
						'inComplemento' => NULL,
						'inCep' => '49500190',
						'inAreaLogradouro' => '0',
						'inCodLocalizacaoDiferenciada' => NULL,
						'inCodMunicipioDNE' => NULL,
						'inLatitude' => '242423432',
						'inLongitude' => '542354325',                
					],
					'inDeficiencia' => [
						'inCodNecessidade' => NULL,
						'inMobilidadeReduzida' => '0',
						'inTipoMobilidadeReduzida' => NULL,
						'inCuidador' => NULL,
						'inTipoCuidador' => NULL,
						'inProfSaude' => NULL,
						'inTipoProfSaude' => NULL,                
					],
					'inRecursoAvaliacao' => [
						'inNenhum' => NULL,
						'inAuxilioLeitor' => NULL,
						'inAuxilioTranscricao' => NULL,
						'inGuiaInterprete' => NULL,
						'inInterpreteLibras' => NULL,
						'inLeituraLabial' => NULL,
						'inProvaBraile' => NULL,
						'inProvaAmpliada' => NULL,
						'inFonteProva' => NULL,
						'inProvaVideoLibras' => NULL,
						'inCdAudioDefVisual' => NULL,
						'inProvaLinguaPortuguesa' => NULL,                
					],
					'inRastreio' => [
						'inUsuarioRemoto' => NULL,
						'inNomeUsuario' => NULL,
						'inNumCPF' => NULL,
						'inLocalPerfilAcesso' => NULL,
					]
				];	
	
				$inConsult = new InFichaAluno($search);
				$dataSource = new StudentSEDDataSource();
				echo "<pre>";
				var_export($dataSource->addStudent($inConsult));
				echo "</pre>";

			default:
				break;
		}
	}
}