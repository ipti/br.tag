<?php

Yii::import('application.modules.sedsp.usecases.*');
Yii::import('application.modules.sedsp.interfaces.*');


class DefaultController extends Controller implements AuthenticateSEDTokenInterface
{
    private $ERROR_CONNECTION = 'Conexão com SEDSP falhou. Tente novamente mais tarde.';
    const REDIRECT_PATH = '/student/update';

	public function authenticateSedToken()
    {
        $loginUseCase = new LoginUseCase();
        $loginUseCase->checkSEDToken();
    }

    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionManageRA()
    {
        $school_id = Yii::app()->user->school;

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

        $this->authenticateSEDToken();

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
        $this->authenticateSEDToken();

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
			$this->authenticateSEDToken();

            $classroomNum = $_POST["classroomNum"];
            $importStudents = isset($_POST["importStudents"]);

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

		$this->authenticateSEDToken();

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
		$ra = $usecase->exec($school_id, $year);
		$this->render('index', ['RA' => $ra]);
	}

	public function actionCreate($id)
	{
		$usecase = new AddStudentToSED();
		$ra = $usecase->exec($id);
		$this->render('index', ['RA' => $ra]);
	}

    public function actionGenerateRA($studentId)
    {
        $this->authenticateSEDToken();

        $generateRaUseCase = new GenerateRaUseCase;
        $generationStatus = $generateRaUseCase->exec($studentId);

    }

    public function actionCreateRA($id)
    {
        $this->authenticateSEDToken();

        $createRA = new CreateRA();
        $msg = $createRA->exec($id);

        if (!$msg) {
            $msg = $createRA->exec($id, true);
        }
        echo $msg;
    }

    public function actionImportFullSchool()
    {
        $this->authenticateSEDToken();

        try {
            $inEscola = new InEscola($_POST['nameSchool'], null, null, null);
            $escola = new GetEscolasFromSEDUseCase();
            $statusSave = $escola->exec($inEscola);

            if ($statusSave === true) {
                Yii::app()->user->setFlash('success', "Escola e classes importadas com sucesso.");
            } elseif ($statusSave === 2) {
                Yii::app()->user->setFlash(
                    'success', "A escola foi importada com sucesso, mas não foram encontradas classes associadas a ela."
                );
            } else {
                Yii::app()->user->setFlash('error', "Não foi possível importar a escola");
            }
        } catch (Exception $e) {
            Yii::app()->user->setFlash(
                'error', $e->getMessage()
            );
        } finally {
            $this->redirect(array('index'));
        }
    }

    public function actionImportStudentRA()
	{
		$this->authenticateSEDToken();

		try {
			$inAluno = new InAluno($_POST["numRA"], null, "SP");

			$exibirFicha = new GetExibirFichaAlunoFromSEDUseCase();
			$statusSave = $exibirFicha->exec($inAluno);

			if ($statusSave) {
				Yii::app()->user->setFlash('success', "O Aluno importado com sucesso.");
			} else {
				Yii::app()->user->setFlash('error', "O Aluno já está cadastrado no TAG");
			}
		} catch (Exception $e) {
			Yii::app()->user->setFlash('error', "A escola do aluno não está cadastrada no TAG");
		} finally {
			$this->redirect(array('index'));
		}
	}

    public function actionUpdateStudentFromSedsp()
	{
		$this->authenticateSEDToken();

		try {
			$inAluno = new InAluno($_GET["gov_id"], null, "SP");

			$exibirFicha = new UpdateFichaAlunoInTAGUseCase();
			$statusSave = $exibirFicha->exec($inAluno);
            $id = (int) $_GET["id"];

            if($statusSave === -1) {
                Yii::app()->user->setFlash('error', "O estudante foi sincronizado com sucesso, no entanto, a matrícula não foi sincronizada.");
				$this->redirect([self::REDIRECT_PATH, 'id' => $id]);
            }

            if($statusSave === 401) {
                Yii::app()->user->setFlash('error', "Não foi possível fazer a sincronização da SED para o TAG.");
				$this->redirect([self::REDIRECT_PATH, 'id' => $id]);
            }

            if($statusSave === 23000){
                Yii::app()->user->setFlash('error', 'Turma não localizada! Por favor, importe ou adicione uma turma.');
				$this->redirect([self::REDIRECT_PATH, 'id' => $id]);
            }

			if ($statusSave) {
				Yii::app()->user->setFlash('success', "Aluno sincronizado com sucesso.");
				$this->redirect([self::REDIRECT_PATH, 'id' => $id]);
			} else {
				Yii::app()->user->setFlash('error', 'erro ' . $statusSave);
				$this->redirect([self::REDIRECT_PATH, 'id' => $id]);
			}
		} catch (Exception $e) {
			Yii::app()->user->setFlash('error', "A escola do aluno não está cadastrada no TAG");
		}
	}

    public function actionImportFullStudentsByClasses()
    {
        $this->authenticateSEDToken();

        $selectedClasses = Yii::app()->request->getPost('selectedClasses', '');
        $numClasses = explode(',', $selectedClasses);

        $relacaoClasse = new GetRelacaoClassesFromSEDUseCase();
        foreach ($numClasses as $numClasse) {
            $relacaoClasse->getStudentsFromClass($numClasse);
        }

        Yii::app()->user->setFlash('success', "Alunos importados com sucesso.");
        $this->redirect(array('index'));
    }

    public function actionImportClassroomFromSedsp()
    {
        $this->authenticateSEDToken();

        try {
            $inConsultaTurmaClasse = new InConsultaTurmaClasse(Yii::app()->user->year, $_GET["gov_id"]);

            $classroomUseCase = new GetConsultaTurmaClasseSEDUseCase();
            $statusSave = $classroomUseCase->exec($inConsultaTurmaClasse);

            $id = $_GET["id"];
            if ($statusSave) {
                Yii::app()->user->setFlash('success', "Turma importada com sucesso!");
                $this->redirect(array('/classroom/update', 'id' => $id));
            } else {
                Yii::app()->user->setFlash('error', "Não foi possível importar a turma.");
                $this->redirect(array('/classroom/update', 'id' => $id));
            }
        } catch (Exception $e) {
            Yii::app()->user->setFlash('error', "Um erro ocorreu. Tente novamente.");
        }
    }
}
