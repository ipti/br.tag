<?php

class InstructorTeachingDataController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'fullmenu';

     private $InstructorTeachingData = 'InstructorTeachingData';
    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'create', 'update','getClassroom'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionGetClassroom() {
        if (isset($_POST['InstructorTeachingData'])) {
            $model = new InstructorTeachingData();
            $model->attributes = $_POST['InstructorTeachingData'];
        }
        $data = Classroom::model()->findAllByAttributes(array('school_inep_fk' => (int) $model->school_inep_id_fk));
        $data = CHtml::listData($data, 'id', 'name');

        echo CHtml::tag('option', array('value' => 'NULL'), '(Select a ClassRoom)', true);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
     public function actionCreate() {
        $modelInstructorTeachingData = new InstructorTeachingData();
        $saveTeachingData = false;
        
        $error = '';
        if (isset($_POST['InstructorTeachingData'])) {
            $modelInstructorTeachingData->attributes = $_POST['InstructorTeachingData'];
             //Setar a foreing key
             //=== MODEL TeachingData
            $disciplines = $modelInstructorTeachingData->discipline_1_fk;
            $countDisciplines = count($disciplines);
            //Máximo 13           
            $modelInstructorTeachingData->discipline_1_fk = isset($disciplines[0]) ? $disciplines[0] : NULL;
            $modelInstructorTeachingData->discipline_2_fk = isset($disciplines[1]) ? $disciplines[1] : NULL;
            $modelInstructorTeachingData->discipline_3_fk = isset($disciplines[2]) ? $disciplines[2] : NULL;
            $modelInstructorTeachingData->discipline_4_fk = isset($disciplines[3]) ? $disciplines[3] : NULL;
            $modelInstructorTeachingData->discipline_5_fk = isset($disciplines[4]) ? $disciplines[4] : NULL;
            $modelInstructorTeachingData->discipline_6_fk = isset($disciplines[5]) ? $disciplines[5] : NULL;
            $modelInstructorTeachingData->discipline_7_fk = isset($disciplines[6]) ? $disciplines[6] : NULL;
            $modelInstructorTeachingData->discipline_8_fk = isset($disciplines[7]) ? $disciplines[7] : NULL;
            $modelInstructorTeachingData->discipline_9_fk = isset($disciplines[8]) ? $disciplines[8] : NULL;
            $modelInstructorTeachingData->discipline_10_fk = isset($disciplines[9]) ? $disciplines[9] : NULL;
            $modelInstructorTeachingData->discipline_11_fk = isset($disciplines[10]) ? $disciplines[10] : NULL;
            $modelInstructorTeachingData->discipline_12_fk = isset($disciplines[11]) ? $disciplines[11] : NULL;
            $modelInstructorTeachingData->discipline_13_fk = isset($disciplines[12]) ? $disciplines[12] : NULL;

            $saveTeachingData = true;
            //============================
            if ($saveTeachingData) {

                // Setar todos os school_inep_id
                
                if ($modelInstructorTeachingData->validate()) {
                     
// CORRIGIR !!!!!!!!!!!!!
                    //Get classInepID
                    $classRoom = Classroom::model()->findByPk($modelInstructorTeachingData->classroom_id_fk);
                    $modelInstructorTeachingData->classroom_inep_id = $classRoom->inep_id;
                   
                    
                    if ($modelInstructorTeachingData->save()) {
                        Yii::app()->user->setFlash('success', Yii::t('default', 'Dados de docência do Professor adicionados com sucesso!'));
                        $this->redirect(array('index'));
                    }
                }
            }
        }

        $instructor_id = isset($_GET['instructor_id']) ? $_GET['instructor_id']: NULL;
        $this->render('create', array(
            'model' => $modelInstructorTeachingData,
            'error' => $error,
            'instructor_id'=> $instructor_id,
        ));
    }
    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        
         //=======================================

        $modelInstructorTeachingData = $this->loadModel($id, $this->InstructorTeachingData);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($modelStudentIdentification);   
        $saveTeachingData = false;

        //==================================
        
        $error[] = '';
        if (isset($_POST['InstructorTeachingData'])) {
            $modelInstructorTeachingData->attributes = $_POST['InstructorTeachingData'];

            //=== MODEL TeachingData
            $disciplines = $modelInstructorTeachingData->discipline_1_fk;
            $countDisciplines = count($disciplines);
            //Máximo 13           
            $modelInstructorTeachingData->discipline_1_fk = isset($disciplines[0]) ? $disciplines[0] : NULL;
            $modelInstructorTeachingData->discipline_2_fk = isset($disciplines[1]) ? $disciplines[1] : NULL;
            $modelInstructorTeachingData->discipline_3_fk = isset($disciplines[2]) ? $disciplines[2] : NULL;
            $modelInstructorTeachingData->discipline_4_fk = isset($disciplines[3]) ? $disciplines[3] : NULL;
            $modelInstructorTeachingData->discipline_5_fk = isset($disciplines[4]) ? $disciplines[4] : NULL;
            $modelInstructorTeachingData->discipline_6_fk = isset($disciplines[5]) ? $disciplines[5] : NULL;
            $modelInstructorTeachingData->discipline_7_fk = isset($disciplines[6]) ? $disciplines[6] : NULL;
            $modelInstructorTeachingData->discipline_8_fk = isset($disciplines[7]) ? $disciplines[7] : NULL;
            $modelInstructorTeachingData->discipline_9_fk = isset($disciplines[8]) ? $disciplines[8] : NULL;
            $modelInstructorTeachingData->discipline_10_fk = isset($disciplines[9]) ? $disciplines[9] : NULL;
            $modelInstructorTeachingData->discipline_11_fk = isset($disciplines[10]) ? $disciplines[10] : NULL;
            $modelInstructorTeachingData->discipline_12_fk = isset($disciplines[11]) ? $disciplines[11] : NULL;
            $modelInstructorTeachingData->discipline_13_fk = isset($disciplines[12]) ? $disciplines[12] : NULL;

            $saveTeachingData = true;
            //============================
            if ($saveTeachingData) {

                if ($modelInstructorTeachingData->validate()) {
                   
// CORRIGIR !!!!!!!!!!!!!
                    //Get classInepID
                    $classRoom = Classroom::model()->findByPk($modelInstructorTeachingData->classroom_id_fk);
                    $modelInstructorTeachingData->classroom_inep_id = $classRoom->inep_id;

                    if ($modelInstructorTeachingData->save()) {
                        Yii::app()->user->setFlash('success', Yii::t('default', 'Dados de docência do Professor alterados com sucesso!'));
                        $this->redirect(array('view', 'id' => $modelInstructorTeachingData->id));
                    }
                }
            }
        }
        //====================================
        $this->render('update', array(
            'model' => $modelInstructorTeachingData,
            'error' => $error,
        ));
        
        
        //=====================================================

    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                Yii::app()->user->setFlash('success', Yii::t('default', 'Dados de docência do Professor excluídos com sucesso!'));
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('InstructorTeachingData',
                        array('pagination' => array(
                                'pageSize' => 12,
                        )));
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new InstructorTeachingData('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['InstructorTeachingData']))
            $model->attributes = $_GET['InstructorTeachingData'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = InstructorTeachingData::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'instructor-teaching-data-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
