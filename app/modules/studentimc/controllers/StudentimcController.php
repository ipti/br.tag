<?php

class StudentIMCController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
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
                'allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'studentIndex', 'delete', 'renderStudentTable', 'studentIMCReport'),
                'users' => array('*'),
            ),
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array(
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin'),
                'users' => array('admin'),
            ),
            array(
                'deny',  // deny all users
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
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($studentId)
    {
        $model = new StudentIMC();
        $modelStudentDisorder = StudentDisorder::model()->findByAttributes(['student_fk' => $studentId]);
        $modelStudentIdentification = StudentIdentification::model()->findByPk($studentId);

        if (!$modelStudentIdentification) {
            throw new CHttpException(404, 'Estudante não encontrado.');
        }

        if (!$modelStudentDisorder) {
            $modelStudentDisorder = new StudentDisorder();
            $modelStudentDisorder->student_fk = $studentId;
        }

        if (isset($_POST['StudentImc'], $_POST['StudentDisorder'], $_POST['StudentIdentification'])) {
            $model->attributes = $_POST['StudentImc'];
            $model->student_fk = $studentId;

            $classification = $this->getclassification($model, $modelStudentIdentification);

            $model->student_imc_classification_fk = $classification;



            $modelStudentDisorder->attributes = $_POST['StudentDisorder'];
            $modelStudentIdentification->attributes = $_POST['StudentIdentification'];

            if ($_POST['StudentIdentification']['deficiency_type_autism'] == 1) {
                $modelStudentIdentification->deficiency = 1;
            }
            $disorderHistory = new StudentDisorderHistory();
            $disorderHistory->student_fk = $studentId;
            $disorderHistory->student_disorder_fk = $modelStudentDisorder->id;
            $disorderHistory->attributes =  $_POST['StudentDisorder'];

            $isValid = $model->validate();
            $isValid = $modelStudentDisorder->validate() && $isValid;
            $isValid = $modelStudentIdentification->validate() && $isValid;
            $isValid = $disorderHistory->validate() && $isValid;

            if ($isValid) {
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    $model->save(false);
                    $modelStudentDisorder->save(false);
                    $modelStudentIdentification->save(false);
                    $disorderHistory->student_imc_fk = $model->id;
                    $disorderHistory->save(false);

                    $transaction->commit();
                    Yii::app()->user->setFlash('success', Yii::t('default', 'Student IMC successfully created.'));
                    $this->redirect(['index', 'studentId' => $studentId]);
                } catch (Exception $e) {
                    $transaction->rollback();
                    Yii::log("Erro ao salvar IMC: " . $e->getMessage(), CLogger::LEVEL_ERROR);
                    Yii::app()->user->setFlash('error', Yii::t('default', 'An error occurred while saving the data.'));
                }
            }
        }

        $this->render('create', [
            'model' => $model,
            'disorder' => $modelStudentDisorder,
            'studentIdentification' => $modelStudentIdentification,
        ]);
    }


    private function getclassification($model, $studentIdentification)
    {
        Yii::import('ext.imc.IMC');
        $imc = new IMC();
        $birthDate = DateTime::createFromFormat('d/m/Y', $studentIdentification->birthday)
            ?: DateTime::createFromFormat('Y-m-d', $studentIdentification->birthday);
        $today = new DateTime();
        $age = $today->diff($birthDate)->y;
        $gender = $studentIdentification->sex == 1 ? 'masculino' : 'feminino';
        if ($age <= 18 && $age >= 5) {

            $classification = $imc->classificarIMCInfantil($model->IMC, $age, $gender);
        } else {

            $classification = $imc->imcSituation($model->IMC);
        }

        return $classification;
    }


    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $studentId = $model->student_fk;

        $model->height = number_format((float) $model->height, 2, '.', '');
        $model->weight = number_format((float) $model->weight, 2, '.', '');
        $model->IMC = number_format((float) $model->IMC, 2, '.', '');

        // Buscar ou criar os modelos relacionados
        $modelStudentDisorderHistory = StudentDisorderHistory::model()->findByAttributes(['student_fk' => $studentId, 'student_imc_fk' => $id]);
        if ($modelStudentDisorderHistory === null) {
            $modelStudentDisorderHistory = new StudentDisorderHistory();
            $modelStudentDisorderHistory->student_fk = $studentId;
            $modelStudentDisorderHistory->student_imc_fk = $id;
        }

        $modelStudentIdentification = StudentIdentification::model()->findByPk($studentId);
        if ($modelStudentIdentification === null) {
            $modelStudentIdentification = new StudentIdentification();
            $modelStudentIdentification->id = $studentId;
        }

        if (isset($_POST['StudentImc'])) {
            $createAt = $model->created_at;
            $model->attributes = $_POST['StudentImc'];
            $model->created_at = $createAt;

            $classification = $this->getclassification($model, $modelStudentIdentification);
            $model->student_imc_classification_fk = $classification;

            if (isset($_POST['StudentDisorderHistory'])) {
                $modelStudentDisorderHistory->attributes = $_POST['StudentDisorderHistory'];
            }

            if (isset($_POST['StudentIdentification'])) {
                $modelStudentIdentification->attributes = $_POST['StudentIdentification'];
                if ($_POST['StudentIdentification']['deficiency_type_autism'] == 1) {
                    $modelStudentIdentification->deficiency = 1;
                }
            }

            if ($model->save() && $modelStudentDisorderHistory->save() && $modelStudentIdentification->save()) {
                $this->redirect(['index', 'studentId' => $studentId]);
            }
        }

        if ($model->created_at) {
            $model->created_at = date('d/m/Y', strtotime($model->created_at));
        }

        $this->render('update', [
            'model' => $model,
            'disorder' => $modelStudentDisorderHistory,
            'studentIdentification' => $modelStudentIdentification,
        ]);
    }


    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $studentId = $this->loadModel($id)->student_fk;
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(array('index', 'studentId' => $studentId));
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex($studentId)
    {
        $student = StudentIdentification::model()->findByPk($studentId);

        $dataProvider = new CActiveDataProvider('StudentIMC', array(
            'criteria' => array(
                'condition' => 'student_fk = :student_fk',
                'params' => array(':student_fk' => $studentId),
            ),
        ));

        $highest = StudentIMC::model()->find(array(
            'condition' => 'student_fk = :student_fk',
            'params' => array(':student_fk' => $studentId),
            'order' => 'imc DESC',
            'limit' => 1,
        ));
        $lowest = StudentIMC::model()->find(array(
            'condition' => 'student_fk = :student_fk',
            'params' => array(':student_fk' => $studentId),
            'order' => 'imc ASC',
            'limit' => 1,
        ));
        $variationRate = null;
        if ($lowest != null && $highest != null && $lowest->IMC != 0) {

            $variationRate = number_format((($highest->IMC - $lowest->IMC) / $lowest->IMC) * 100, 2);
        }

        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'student' => $student,
            'highest' => $highest->IMC,
            'lowest' => $lowest->IMC,
            'variationRate' => $variationRate,
        ));
    }

    public function actionStudentIndex()
    {

        $year = Yii::app()->user->year;
        $school = Yii::app()->user->school;

        $classrooms = Classroom::model()->findAll('school_year = :school_year and school_inep_fk = :school_inep_fk order by name', ['school_year' => $year, 'school_inep_fk' => $school]);

        $dataProvider = new CActiveDataProvider('StudentIdentification', array(
            'criteria' => array(
                'condition' => 'school_inep_id_fk=' . $school,
            ),
            'pagination' => false
        ));
        $this->render('studentIndex', array(
            'dataProvider' => $dataProvider,
            'classrooms' => $classrooms
        ));
    }

    public function actionRenderStudentTable($classroomId)
    {
        $school = Yii::app()->user->school;
        if ($classroomId) {
            $criteria = new CDbCriteria();
            $criteria->alias = 'si';
            $criteria->with = array(
                'lastEnrollment.classroomFk'
            );
            $criteria->together = true;
            $criteria->condition = 'classroomFk.id = :classroomId and si.school_inep_id_fk= :school';
            $criteria->params = array(':classroomId' => $classroomId, ':school' => $school);

            $dataProvider = new CActiveDataProvider('StudentIdentification', array(
                'criteria' => $criteria,
                'pagination' => false
            ));
        } else {
            $dataProvider = new CActiveDataProvider('StudentIdentification', array(
                'criteria' => array(
                    'condition' => 'school_inep_id_fk=' . $school,
                ),
                'pagination' => false
            ));
        }


        $this->renderPartial('_studentTable', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionStudentIMCReport($studentId)
    {
        $studentICM = StudentIMC::model()->findAllByAttributes(["student_fk" => $studentId]);

        $this->render('studentIMCReport', array(
            'studentICM' => $studentICM,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new StudentIMC('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['StudentIMC'])) {
            $model->attributes = $_GET['StudentIMC'];
        }
        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return StudentIMC the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = StudentIMC::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param StudentIMC $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'student-imc-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
