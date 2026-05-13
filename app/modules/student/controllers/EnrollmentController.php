<?php

Yii::import('application.modules.sedsp.models.Student.*');
Yii::import('application.modules.sedsp.datasources.sed.Student.*');
Yii::import('application.modules.sedsp.mappers.*');
Yii::import('application.modules.sedsp.usecases.Enrollment.*');
Yii::import('application.modules.sedsp.models.Enrollment.*');
Yii::import('application.modules.sedsp.usecases.*');
Yii::import('application.modules.sedsp.usecases.Student.*');
Yii::import('application.modules.sedsp.interfaces.*');
Yii::import('application.modules.sedsp.datasources.sed.Enrollment.*');
class EnrollmentController extends Controller implements AuthenticateSEDTokenInterface
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'fullmenu';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl',
        ];
    }

    public function authenticateSedToken()
    {
        $loginUseCase = new LoginUseCase();
        $loginUseCase->checkSEDToken();
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => [
                    'index',
                    'view',
                    'create',
                    'update',
                    'updatedependencies',
                    'delete',
                    'getmodalities',
                    'CheckEnrollmentDelete'
                ],
                'users' => ['@'],
            ],
            [
                'allow',
                'actions' => ['admin'],
                'users' => ['admin'],
            ],
            [
                'deny',
                'users' => ['*'],
            ],
        ];
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render(
            'view',
            [
                'model' => $this->loadModel($id),
            ]
        );
    }

    public function actionCheckEnrollmentDelete($enrollmentId)
    {
        $frequency = ClassFaults::model()->findAllByAttributes(['student_fk' => $enrollmentId]);
        $grades = Grade::model()->findAllByAttributes(['enrollment_fk' => $enrollmentId]);
        $gradeResults = GradeResults::model()->findAllByAttributes(['enrollment_fk' => $enrollmentId]);

        if ($frequency || $grades || $gradeResults) {
            echo json_encode(['block' => true, 'message' => 'Essa matrícula não pode ser excluída porque existe frequência ou notas associadas a ela!']);
        } else {
            echo json_encode(['block' => false, 'message' => 'Tem certeza que deseja excluir a matrícula? Essa ação não pode ser desfeita!']);
        }
    }

    public function actionUpdateDependencies()
    {
        $classrooms = Classroom::model()->findAllByAttributes(['school_year' => Yii::app()->user->year, 'school_inep_fk' => Yii::app()->user->school]);
        $class = new Classroom();
        $result['Classrooms'] = CHtml::tag('option', ['value' => 0], 'Selecione uma Turma', true);
        foreach ($classrooms as $class) {
            if (strpos($class->edcensoStageVsModalityFk->name, 'Multi') !== false) {
                $multi = 1;
            } else {
                $multi = 0;
            }
            $result['Classrooms'] .= CHtml::tag('option', ['value' => $class->id, 'id' => $multi], CHtml::encode($class->name), true);
        }

        echo json_encode($result);
    }

    public function actionGetModalities()
    {
        $stage = $_POST['Stage'];
        $where = ($stage == '0') ? '' : "stage = $stage";
        $data = EdcensoStageVsModality::model()->findAll($where);
        $data = CHtml::listData($data, 'id', 'name');

        foreach ($data as $value => $name) {
            echo htmlspecialchars(CHtml::tag('option', ['value' => $value], CHtml::encode($name), true));
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new StudentEnrollment();

        $classrooms = Classroom::model()->findAll(
            'school_year = :year AND school_inep_fk = :school order by name',
            [
                ':year' => Yii::app()->user->year,
                ':school' => Yii::app()->user->school,
            ]
        );

        if (isset($_POST['StudentEnrollment'])) {
            $model->attributes = $_POST['StudentEnrollment'];
            if ($model->validate()) {
                $model->classroom_inep_id = Classroom::model()->findByPk($model->classroom_fk)->inep_id;
                $model->student_inep_id = StudentIdentification::model()->findByPk($model->student_fk)->inep_id;
                try {
                    if ($model->save()) {
                        Log::model()->saveAction('enrollment', $model->id, 'C', $model->studentFk->name . '|' . $model->classroomFk->name);
                        Yii::app()->user->setFlash('success', Yii::t('default', 'Aluno matriculado com sucesso!'));
                        $this->redirect(['index']);
                    }
                } catch (Exception $exc) {
                    $model->addError('student_fk', Yii::t('default', 'Student Fk') . ' ' . Yii::t('default', 'already enrolled in this classroom.'));
                    $model->addError('classroom_fk', Yii::t('default', 'Classroom') . ' ' . Yii::t('default', 'already have in this student enrolled.'));
                }
            } else {
                unset($model->s);
            }
        }

        $this->render(
            'create',
            [
                'model' => $model,
                'classrooms' => $classrooms,
            ]
        );
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        $rawDate = $model->enrollment_date ?? '';

        $date = (!empty($rawDate) && $rawDate !== '0000-00-00')
            ? DateTime::createFromFormat('Y-m-d', $rawDate) ?: new DateTime()
            : new DateTime();

        $model->enrollment_date = $date->format('d/m/Y');

        $class = Classroom::model()->findByPk($model->classroom_fk);
        $oldClass = $class->gov_id === null ? $class->inep_id : $class->gov_id;

        $modelStudentIdentification = StudentIdentification::model()->find('inep_id="' . $model->student_inep_id . '"');
        if ($model->student_fk == null && $model->classroom_fk == null) {
            $model->student_fk = $modelStudentIdentification->id;
            $model->classroom_fk = Classroom::model()->find('inep_id="' . $model->classroom_inep_id . '"')->id;
        }

        $isAdmin = Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id);

        if ($isAdmin) {
            $classrooms = Classroom::model()->findAll(
                'school_year = :year order by name',
                [
                    ':year' => Yii::app()->user->year,
                ]
            );
        } else {
            $classrooms = Classroom::model()->findAll(
                'school_year = :year AND school_inep_fk = :school order by name',
                [
                    ':year' => Yii::app()->user->year,
                    ':school' => $model->school_inep_id_fk,
                ]
            );
        }

        if (isset($_POST['StudentEnrollment']) && $model->validate()) {
            $model->attributes = $_POST['StudentEnrollment'];
            $model->enrollment_date = DateTime::createFromFormat('d/m/Y', $model->enrollment_date);
            $model->enrollment_date = $model->enrollment_date->format('Y-m-d');
            $model->school_inep_id_fk = Classroom::model()->findByPk([$_POST['StudentEnrollment']['classroom_fk']])->school_inep_fk;
            if ($model->validate()) {
                if ($model->status != $_POST['StudentEnrollment']['status']) {
                    $studentEnrollmentHistory = new StudentEnrollmentHistory();
                    $studentEnrollmentHistory->student_enrollment_fk = $model->id;
                    $studentEnrollmentHistory->status = $model->status;
                    $studentEnrollmentHistory->enrollment_date = $model->enrollment_date;
                    $studentEnrollmentHistory->transfer_date = $model->transfer_date;
                    $studentEnrollmentHistory->class_transfer_date = $model->class_transfer_date;
                    $studentEnrollmentHistory->school_readmission_date = $model->school_readmission_date;
                    $studentEnrollmentHistory->save();
                }

                if ($model->save()) {
                    $model->enrollment_date = DateTime::createFromFormat('Y-m-d', $model->enrollment_date);
                    $model->enrollment_date = $model->enrollment_date->format('d/m/Y');
                    $message = '';
                    if (Yii::app()->features->isEnable(TFeature::FEAT_INTEGRATIONS_SEDSP)) {
                        $this->authenticateSedToken();

                        $inNumRA = StudentIdentification::model()->findByPk($model->student_fk);
                        $inAluno = new InAluno($inNumRA->gov_id, null, 'SP');
                        $class = Classroom::model()->findByPk($model->classroom_fk);
                        $newClass = $class->gov_id === null ? $class->inep_id : $class->gov_id;

                        if ($model->sedsp_sync == 0) {
                            $model->studentFk->processEnrollment($model->studentFk, $model);
                        }

                        if ($model->status === '2' || $model->status === '5') {
                            $classroomMapper = new ClassroomMapper();
                            $ensino = (object)$classroomMapper->convertStageToTipoEnsino($class->edcenso_stage_vs_modality_fk);

                            $inDataMovimento = date('d/m/Y');
                            $inNumAluno = '00';
                            $inNumClasseOrigem = $oldClass;
                            $inNumClasseDestino = $newClass;

                            $inTrocarAlunoEntreClasses = new InTrocarAlunoEntreClasses(
                                $inAluno,
                                new InMatriculaTrocar(Yii::app()->user->year, $inDataMovimento, $inNumAluno, $inNumClasseOrigem, $inNumClasseDestino),
                                new InNivelEnsino($ensino->tipoEnsino, $ensino->serieAno)
                            );

                            $trocarAlunoEntreClassesUseCase = new TrocarAlunoEntreClassesUseCase();
                            $result = $trocarAlunoEntreClassesUseCase->exec($inTrocarAlunoEntreClasses);
                        } elseif ($model->status === '3' || $model->status === '11') {
                            $class = Classroom::model()->findByPk($model->classroom_fk);
                            $inNumClasse = $class->gov_id === null ? $class->inep_id : $class->gov_id;

                            $inExcluirMatricula = new InExcluirMatricula($inAluno, $inNumClasse);

                            $deleteEnrollmentUseCase = new DeleteEnrollmentUseCase();
                            $result = $deleteEnrollmentUseCase->exec($inExcluirMatricula);
                        } elseif ($model->status === '4') {
                            $inTipoBaixa = $_POST['reason'];
                            if ($inTipoBaixa == '1') {
                                $inMotivoBaixa = $_POST['secondReason'];
                            } else {
                                $inMotivoBaixa = null;
                            }

                            $inDataBaixa = date('Y-m-d');
                            $class = Classroom::model()->findByPk($model->classroom_fk);
                            $inNumClasse = $class->gov_id === null ? $class->inep_id : $class->gov_id;

                            $inBaixarMatricula = new InBaixarMatricula($inAluno, $inTipoBaixa, $inMotivoBaixa, $inDataBaixa, $inNumClasse);

                            $terminateEnrollmentUseCase = new TerminateEnrollmentUseCase();
                            $result = $terminateEnrollmentUseCase->exec($inBaixarMatricula);
                        }

                        if ($result->outErro === null) {
                            $flash = 'success';
                            $message .= 'Matrícula alterada com sucesso!';
                        } else {
                            $flash = 'error';
                            $message .= 'Matrícula alterada com sucesso no TAG, mas não foi possível sincronizá-la com o SEDSP. Motivo: ' . $result->outErro;
                        }
                    } else {
                        $flash = 'success';
                        $message .= 'Matrícula alterada com sucesso!';
                    }
                    Log::model()->saveAction('enrollment', $model->id, 'U', $model->studentFk->name . '|' . $model->classroomFk->name);
                    Yii::app()->user->setFlash($flash, $message);
                }
            }
        }

        $this->render(
            'update',
            [
                'model' => $model,
                'modelStudentIdentification' => $modelStudentIdentification,
                'classrooms' => $classrooms
            ]
        );
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $model = $this->loadModel($id);
        if ($model->delete()) {
            Log::model()->saveAction('enrollment', $model->id, 'D', $model->studentFk->name . '|' . $model->classroomFk->name);
            Yii::app()->user->setFlash('success', Yii::t('default', 'A Matrícula de ' . $model->studentFk->name . ' foi excluída com sucesso!'));
            $this->redirect(['/student/student/index']);
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $query = StudentEnrollment::model()->findAll();
        $model = new StudentEnrollment('search');
        $model->unsetAttributes();
        if (isset($_GET['StudentEnrollment'])) {
            $model->attributes = $_GET['StudentEnrollment'];
        }

        $school = Yii::app()->user->school;

        $criteria = new CDbCriteria();
        $criteria->compare('school_inep_id_fk', "'$school'");
        $dataProvider = new CActiveDataProvider(
            'StudentEnrollment',
            [
                'criteria' => $criteria,
                'pagination' => [
                    'pageSize' => count($query),
                ],
            ]
        );

        $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
                'model' => $model
            ]
        );
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = StudentEnrollment::model()->with('studentFk')->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'student-enrollment-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
