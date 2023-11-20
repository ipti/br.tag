<?php

class EnrollmentController extends Controller
{
    //@done s1 - Validar Ano Letivo
    //@done s1 - Verificar erro - Ao matricular um aluno que acabou de ser cadastrado não está salvando eno bancoo e aparece a mensagem de 'Aluno ja matriculado'
    //@done s1 - Filtrar aluno e turma por escola

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
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array(
                    'index',
                    'view',
                    'create',
                    'update',
                    "updatedependencies",
                    'delete',
                    'getmodalities',
                    'grades',
                    'getGrades',
                    'saveGrades',
                    'CheckEnrollmentDelete',
                    'getDisciplines',
                    'calculateFinalMedia',
                    'reportCard',
                    'getReportCardGrades',
                    'saveGradesReportCard'
                ),
                'users' => array('@'),
            ),
            array(
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin'),
                'users' => array('admin'),
            ),
            array(
                'deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        )
        );
    }

    public function actionCheckEnrollmentDelete($enrollmentId)
    {
        $frequency = ClassFaults::model()->findAllByAttributes(["student_fk" => $enrollmentId]);
        $grades = Grade::model()->findAllByAttributes(["enrollment_fk" => $enrollmentId]);
        $gradeResults = GradeResults::model()->findAllByAttributes(["enrollment_fk" => $enrollmentId]);

        if ($frequency || $grades || $gradeResults) {
            echo json_encode(["block" => true, "message" => "Essa matrícula não pode ser excluída porque existe frequência ou notas associadas a ela!"]);
        } else {
            echo json_encode(["block" => false, "message" => "Tem certeza que deseja excluir a matrícula? Essa ação não pode ser desfeita!"]);
        }
    }

    public function actionUpdateDependencies()
    {
        //$enrollment = new StudentEnrollment;
        //$enrollment->attributes = $_POST["StudentEnrollment"];
        //$students = StudentIdentification::model()->findAll('school_inep_id_fk=:id order by name ASC', array(':id' => $enrollment->school_inep_id_fk));
        //$students = CHtml::listData($students, 'id', 'name');

        $classrooms = Classroom::model()->findAllByAttributes(array("school_year" => Yii::app()->user->year, "school_inep_fk" => Yii::app()->user->school));
        //$classrooms = CHtml::listData($classrooms, 'id', 'name');

        /* $result['Students'] = CHtml::tag('option', array('value' => null), 'Selecione um Aluno', true);
          foreach ($students as $value => $name) {
          $result['Students'] .= CHtml::tag('option', array('value' => $value, ($enrollment->student_fk == $value ? "selected" : "deselected") => ($enrollment->student_fk == $value ? "selected" : "deselected")), CHtml::encode($name), true);
          } */
        $class = new Classroom();
        $result['Classrooms'] = CHtml::tag('option', array('value' => 0), 'Selecione uma Turma', true);
        foreach ($classrooms as $class) {
            if (strpos($class->edcensoStageVsModalityFk->name, 'Multi') !== false) {
                $multi = 1;
            } else {
                $multi = 0;
            }
            $result['Classrooms'] .= CHtml::tag('option', array('value' => $class->id, 'id' => $multi), CHtml::encode($class->name), true);
        }

        echo json_encode($result);
    }

    public function actionGetModalities()
    {
        $stage = $_POST['Stage'];
        $where = ($stage == "0") ? "" : "stage = $stage";
        $data = EdcensoStageVsModality::model()->findAll($where);
        $data = CHtml::listData($data, 'id', 'name');

        foreach ($data as $value => $name) {
            echo htmlspecialchars(CHtml::tag('option', array('value' => $value), CHtml::encode($name), true));
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new StudentEnrollment;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $classrooms = Classroom::model()->findAll(
            "school_year = :year AND school_inep_fk = :school order by name",
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
                        Log::model()->saveAction("enrollment", $model->id, "C", $model->studentFk->name . "|" . $model->classroomFk->name);
                        Yii::app()->user->setFlash('success', Yii::t('default', 'Aluno matriculado com sucesso!'));
                        $this->redirect(array('index'));
                    }
                } catch (Exception $exc) {
                    $model->addError('student_fk', Yii::t('default', 'Student Fk') . ' ' . Yii::t('default', 'already enrolled in this classroom.'));
                    $model->addError('classroom_fk', Yii::t('default', 'Classroom') . ' ' . Yii::t('default', 'already have in this student enrolled.'));
                    //echo $exc->getTraceAsString();

                }
            } else {
                unset($model->s);
            }
        }

        $this->render('create', array(
            'model' => $model,
            'classrooms' => $classrooms,
        )
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

        $modelStudentIdentification = StudentIdentification::model()->find('inep_id="' . $model->student_inep_id . '"');
        if ($model->student_fk == NULL && $model->classroom_fk == NULL) {
            $model->student_fk = $modelStudentIdentification->id;
            $model->classroom_fk = Classroom::model()->find('inep_id="' . $model->classroom_inep_id . '"')->id;
        }

        $isAdmin = Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id);

        $classrooms = [];

        if ($isAdmin) {
            $classrooms = Classroom::model()->findAll(
                "school_year = :year order by name",
                [
                    ':year' => Yii::app()->user->year,
                ]
            );
        } else {
            $classrooms = Classroom::model()->findAll(
                "school_year = :year AND school_inep_fk = :school order by name",
                [
                    ':year' => Yii::app()->user->year,
                    ':school' => $model->school_inep_id_fk,
                ]
            );
        }

        if (isset($_POST['StudentEnrollment'])) {
            if ($model->validate()) {
                $model->attributes = $_POST['StudentEnrollment'];
                $model->school_inep_id_fk = Classroom::model()->findByPk([$_POST['StudentEnrollment']["classroom_fk"]])->school_inep_fk;
                if ($model->save()) {
                    Log::model()->saveAction("enrollment", $model->id, "U", $model->studentFk->name . "|" . $model->classroomFk->name);
                    Yii::app()->user->setFlash('success', Yii::t('default', 'Matrícula alterada com sucesso!'));
                    // $this->redirect(array('student/'));
                }
            }
        }

        $this->render('update', array(
            'model' => $model,
            'modelStudentIdentification' => $modelStudentIdentification,
            'classrooms' => $classrooms
        )
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
        FrequencyAndMeanByDiscipline::model()->deleteAll("enrollment_fk = :enrollment_fk", ["enrollment_fk" => $id]);
        FrequencyByExam::model()->deleteAll("enrollment_fk = :enrollment_fk", ["enrollment_fk" => $id]);
        if ($model->delete()) {
            Log::model()->saveAction("enrollment", $model->id, "D", $model->studentFk->name . "|" . $model->classroomFk->name);
            Yii::app()->user->setFlash('success', Yii::t('default', "A Matrícula de " . $model->studentFk->name . " foi excluída com sucesso!"));
            $this->redirect(array('student/index'));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        //		if(Yii::app()->request->isPostRequest)
//		{
//
//			// we only allow deletion via POST request
//			$this->loadModel($id)->delete();
//
//			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
//			if(!isset($_GET['ajax']))
//				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
//		}
//		else
//			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $query = StudentEnrollment::model()->findAll();
        $model = new StudentEnrollment('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['StudentEnrollment'])) {
            $model->attributes = $_GET['StudentEnrollment'];
        }

        $school = Yii::app()->user->school;

        $criteria = new CDbCriteria;
        $criteria->compare('school_inep_id_fk', "'$school'");
        $dataProvider = new CActiveDataProvider('StudentEnrollment', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => count($query),
            ),
        )
        );

        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'model' => $model
        )
        );
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = StudentEnrollment::model()->with("studentFk")->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
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
