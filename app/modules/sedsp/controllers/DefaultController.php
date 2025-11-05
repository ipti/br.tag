<?php

Yii::import('application.modules.sedsp.usecases.*');
Yii::import('application.modules.sedsp.interfaces.*');

class DefaultController extends Controller implements AuthenticateSEDTokenInterface
{

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
        $schoolId = Yii::app()->user->school;

        $criteira = new CDbCriteria();
        $criteira->select = 'DISTINCT t.*';
        $criteira->join = 'LEFT JOIN student_enrollment se ON se.student_inep_id = t.inep_id
				   LEFT JOIN classroom class ON se.classroom_fk = class.id';
        $criteira->addCondition('t.school_inep_id_fk = :school_id');
        $criteira->addCondition('t.gov_id is null');
        $criteira->addCondition('class.school_year = :year');
        $criteira->params = [':school_id' => $schoolId, ':year' => Yii::app()->user->year];

        $dataProvider = new CActiveDataProvider(
            'StudentIdentification',
            [
                'criteria' => $criteira,
                'countCriteria' => $criteira,
                'pagination' => ['PageSize' => 100],
            ]
        );
        $this->render('manageRA', ['dataProvider' => $dataProvider]);
    }

    public function actionAddStudentWithRA()
    {
        $ra = $_POST['ra'];

        $this->authenticateSEDToken();

        try {
            $createStudent = new CreateStudent();
            $response = $createStudent->exec($ra);
            if (!$response) {
                $response = $createStudent->exec($ra, true);
            }

            $modelStudentIdentification = $response['StudentIdentification'];
            $modelStudentDocumentsAndAddress = $response['StudentDocumentsAndAddress'];
            date_default_timezone_set('America/Recife');
            $modelStudentIdentification->last_change = date('Y-m-d G:i:s');
            $modelStudentDocumentsAndAddress->school_inep_id_fk = $modelStudentIdentification->school_inep_id_fk;
            $modelStudentDocumentsAndAddress->student_fk = $modelStudentIdentification->inep_id;
            // Validação CPF->Nome
            if ($modelStudentDocumentsAndAddress->cpf != null) {
                $studentTestCpf = StudentDocumentsAndAddress::model()->find('cpf=:cpf', [':cpf' => $modelStudentDocumentsAndAddress->cpf]);
                if (isset($studentTestCpf)) {
                    Yii::app()->user->setFlash('error', Yii::t('default', 'O Aluno já está cadastrado'));
                    $this->redirect(['index']);
                }
            }
            if ($modelStudentIdentification->name != null) {
                $studentTestName = StudentIdentification::model()->find('name=:name', [':name' => $modelStudentIdentification->name]);
                if (isset($studentTestName)) {
                    Yii::app()->user->setFlash('error', Yii::t('default', 'O Aluno já está cadastrado'));
                    $this->redirect(['index']);
                }
            }

            if ($modelStudentIdentification->validate() && $modelStudentIdentification->save()) {
                $modelStudentDocumentsAndAddress->id = $modelStudentIdentification->id;
                $modelStudentDocumentsAndAddress->save();
                if ($modelStudentDocumentsAndAddress->validate() && $modelStudentDocumentsAndAddress->save()) {
                    $msg = 'O Cadastro de ' . $modelStudentIdentification->name . ' foi criado com sucesso!';
                    Yii::app()->user->setFlash('success', Yii::t('default', $msg));
                    $this->redirect(['index']);
                }
            }
        } catch (\Throwable $th) {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Ocorreu um erro ao cadastrar o aluno. Certifique-se de digitar um RA válido'));
            $this->redirect(['index']);
        }
    }

    public function actionAddClassroom()
    {
        try {
            $this->authenticateSEDToken();

            $classroomNum = $_POST['classroomNum'];
            $importStudents = isset($_POST['importStudents']);

            if ($classroomNum) {
                $this->registerClassroom($classroomNum);
            }

            if ($importStudents) {
                $params = new InFormacaoClasse($classroomNum);
                $formacaoClass = new GetFormacaoClasseFromSEDUseCase();
                $formacaoClass->exec($params);
            }

            $this->redirect(['index']);
        } catch (\Exception $e) {
            Yii::app()->user->setFlash('error', Yii::t('default', $e->getMessage()));
            $this->redirect(['index']);
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
        $existingClassroom = Classroom::model()->find('inep_id=:inep_id or gov_id=:inep_id', [':inep_id' => $classroomNum]);

        if ($existingClassroom) {
            $msg = 'O Cadastro da Turma ' . $existingClassroom->name . " já existe! <a href='" . Yii::app()->createUrl('classroom/update&id=' . $existingClassroom->id) . "' style='color:white;'>Clique aqui para visualizar.</a>";
            Yii::app()->user->setFlash('error', Yii::t('default', $msg));
            return;
        }

        $modelClassroom = $createClassroom->exec(Yii::app()->user->year, $classroomNum);
        if ($modelClassroom) {
            $msg = 'O Cadastro da Turma ' . $modelClassroom->name . " foi criado com sucesso! <a href='" . Yii::app()->createUrl('classroom/update&id=' . $modelClassroom->id) . "' style='color:white;'>Clique aqui para visualizar.</a>";
            Yii::app()->user->setFlash('success', Yii::t('default', $msg));
        }
    }

    public function actionAddSchool()
    {
        $schoolName = $_POST['schoolName'];
        $schoolMun = $_POST['schoolMun'];

        $this->authenticateSEDToken();

        try {
            $createSchool = new CreateSchool();
            $response = $createSchool->exec($schoolName, $schoolMun);
            $modelSchool = $response['SchoolIdentification'];
            $modelSchoolStructure = new SchoolStructure();
            $modelSchoolStructure->school_inep_id_fk = $modelSchool->inep_id;
            // Bloqueio de duplicação por inep id
            if ($modelSchool->inep_id != null) {
                $schoolTestName = SchoolIdentification::model()->find(
                    'inep_id=:inep_id',
                    [':inep_id' => $modelSchool->inep_id]
                );
                if (isset($schoolTestName)) {
                    $msg = 'O Cadastro da Escola ' . $modelSchool->name . " já existe!
					<a href='" . Yii::app()->createUrl('school/update&id=' . $modelSchool->inep_id) . "' style='color:white;'>
					Clique aqui para visualizar.</a>";
                    Yii::app()->user->setFlash('error', Yii::t('default', $msg));
                    $this->redirect(['index']);
                }
            }
            if (
                $modelSchool->validate() && $modelSchoolStructure->validate()
                && $modelSchool->save() && $modelSchoolStructure->save()
            ) {
                $msg = 'O Cadastro da Escola ' . $modelSchool->name . " foi criado com sucesso!
				<a href='" . Yii::app()->createUrl('school/update&id=' . $modelSchool->inep_id) . "' style='color:white;'>
				Clique aqui para visualizar.</a>";
                Yii::app()->user->setFlash('success', Yii::t('default', $msg));
                $this->redirect(['index']);
            }
        } catch (\Exception $e) {
            Yii::app()->user->setFlash('error', Yii::t('default', $e->getMessage()));
            $this->redirect(['index']);
        }
    }

    public function actionLogin()
    {
        $usecase = new LoginUseCase();
        $token = $usecase->exec('SME701', 'zyd780mhz1s5');
        $this->render('index', ['token' => $token]);
    }

    public function actionSyncSchoolClassrooms()
    {
        $schoolId = Yii::app()->user->school;
        $year = Yii::app()->user->year;
        $usecase = new IdentifyAllClassroomRABySchool();
        $ra = $usecase->exec($schoolId, $year);
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

        $generateRaUseCase = new GenerateRaUseCase();
        $generateRaUseCase->exec($studentId);
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
                Yii::app()->user->setFlash('success', 'Escola e classes importadas com sucesso.');
            } elseif ($statusSave === 2) {
                Yii::app()->user->setFlash(
                    'success',
                    'A escola foi importada com sucesso, mas não foram encontradas classes associadas a ela.'
                );
            } else {
                Yii::app()->user->setFlash('error', 'Não foi possível importar a escola');
            }
        } catch (Exception $e) {
            Yii::app()->user->setFlash(
                'error',
                $e->getMessage()
            );
        } finally {
            $this->redirect(['index']);
        }
    }

    public function actionImportStudentRA()
    {
        $this->authenticateSEDToken();

        try {
            $inAluno = new InAluno($_POST['numRA'], null, 'SP');

            $exibirFicha = new GetExibirFichaAlunoFromSEDUseCase();
            $statusSave = $exibirFicha->exec($inAluno);

            if ($statusSave) {
                Yii::app()->user->setFlash('success', 'O Aluno importado com sucesso.');
            } else {
                Yii::app()->user->setFlash('error', 'O Aluno já está cadastrado no TAG');
            }
        } catch (Exception $e) {
            Yii::app()->user->setFlash('error', 'A escola do aluno não está cadastrada no TAG');
        } finally {
            $this->redirect(['index']);
        }
    }

    public function actionUpdateStudentFromSedsp()
    {
        $this->authenticateSEDToken();

        try {
            $inAluno = new InAluno($_GET['gov_id'], null, 'SP');

            $exibirFicha = new UpdateFichaAlunoInTAGUseCase();
            $statusSave = $exibirFicha->exec($inAluno);
            $urlId =  Yii::app()->request->getQuery('id');
            $id = filter_var($urlId,FILTER_VALIDATE_INT);
            if($id !== false){
                if ($statusSave === -1) {
                    Yii::app()->user->setFlash('error', 'O estudante foi sincronizado com sucesso, no entanto, a matrícula não foi sincronizada.');
                }

                if ($statusSave === 401) {
                    Yii::app()->user->setFlash('error', 'Não foi possível fazer a sincronização da SED para o TAG.');
                }

                if ($statusSave === 23000) {
                    Yii::app()->user->setFlash('error', 'Turma não localizada! Por favor, importe ou adicione uma turma.');
                }

                if ($statusSave) {
                    Yii::app()->user->setFlash('success', 'Aluno sincronizado com sucesso.');
                } else {
                    Yii::app()->user->setFlash('error', 'erro ' . $statusSave);
                }
                $this->redirect(['/student/update', 'id' => $id]);
            }
        } catch (Exception $e) {
            Yii::app()->user->setFlash('error', 'A escola do aluno não está cadastrada no TAG');
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

        Yii::app()->user->setFlash('success', 'Alunos importados com sucesso.');
        $this->redirect(['index']);
    }

    public function actionImportClassroomFromSedsp()
    {
        $this->authenticateSEDToken();

        try {
            $inConsultaTurmaClasse = new InConsultaTurmaClasse(Yii::app()->user->year, $_GET['gov_id']);

            $classroomUseCase = new GetConsultaTurmaClasseSEDUseCase();
            $statusSave = $classroomUseCase->exec($inConsultaTurmaClasse);

            $id = Yii::app()->request->getQuery('id');
            if(filter_var($id,FILTER_VALIDATE_INT) !== false){
                if ($statusSave) {
                Yii::app()->user->setFlash('success', 'Turma importada com sucesso!');
                } else {
                    Yii::app()->user->setFlash('error', 'Não foi possível importar a turma.');
                }
                $this->redirect(['/classroom/update', 'id' => $id]);
            }

        } catch (Exception $e) {
            Yii::app()->user->setFlash('error', 'Um erro ocorreu. Tente novamente.');
        }
    }
}
