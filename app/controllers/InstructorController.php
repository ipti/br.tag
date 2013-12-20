<?php

class InstructorController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'fullmenu';
    private $InstructorIdentification = 'InstructorIdentification';
    private $InstructorDocumentsAndAddress = 'InstructorDocumentsAndAddress';
    private $InstructorVariableData = 'InstructorVariableData';
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
                'actions' => array('index', 'view', 'create', 'update', 'getCities', 'getClassroom'),
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
            'modelInstructorIdentification' => $this->loadModel($id, $this->InstructorIdentification),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
//         $modelInstructorTeachingData = new InstructorTeachingData();
//        $modelInstructorTeachingData->attributes = $_POST['InstructorTeachingData'];
//        var_dump($modelInstructorTeachingData->discipline_1_fk);exit();
        $modelInstructorIdentification = new InstructorIdentification();
        $modelInstructorDocumentsAndAddress = new InstructorDocumentsAndAddress();
        $modelInstructorVariableData = new InstructorVariableData();
        $modelInstructorTeachingData = new InstructorTeachingData();
        $saveInstructor = false;
        $saveDocumentsAndAddress = false;
        $saveVariableData = false;
        $saveTeachingData = false;

        $error[] = '';
        if (isset($_POST['InstructorIdentification'], $_POST['InstructorDocumentsAndAddress']
                        , $_POST['InstructorVariableData'], $_POST['InstructorTeachingData'])) {
            $modelInstructorIdentification->attributes = $_POST['InstructorIdentification'];
            $modelInstructorDocumentsAndAddress->attributes = $_POST['InstructorDocumentsAndAddress'];
            $modelInstructorVariableData->attributes = $_POST['InstructorVariableData'];
            $modelInstructorTeachingData->attributes = $_POST['InstructorTeachingData'];

            if (!isset($modelInstructorIdentification->edcenso_nation_fk)) {
                $modelInstructorIdentification->edcenso_nation_fk = 76;
            }
            if (!isset($modelInstructorIdentification->edcenso_uf_fk)) {
                $modelInstructorIdentification->edcenso_uf_fk = 0;
            }
            if (!isset($modelInstructorIdentification->edcenso_city_fk)) {
                $modelInstructorIdentification->edcenso_city_fk = 0;
            }


            $saveInstructor = true;

            //=== MODEL DocumentsAndAddress
            if (isset($modelInstructorDocumentsAndAddress->cep) && !empty($modelInstructorDocumentsAndAddress->cep)) {
                //Então o endereço, uf e cidade são obrigatórios
                if (isset($modelInstructorDocumentsAndAddress->address) && !empty($modelInstructorDocumentsAndAddress->address) &&
                        isset($modelInstructorDocumentsAndAddress->neighborhood) && !empty($modelInstructorDocumentsAndAddress->neighborhood) &&
                        isset($modelInstructorDocumentsAndAddress->edcenso_uf_fk) && !empty($modelInstructorDocumentsAndAddress->edcenso_uf_fk) &&
                        isset($modelInstructorDocumentsAndAddress->edcenso_city_fk) && !empty($modelInstructorDocumentsAndAddress->edcenso_city_fk)) {

                    $saveDocumentsAndAddress = true;
                } else {
                    $error['documentsAndAddress'] = 'CEP preenchido então, o Endereço, Bairro, UF e Cidade são Obrigatórios !';
                }
            } else {
                $saveDocumentsAndAddress = true;
            }
            //======================================
            //=== MODEL VariableData            
            if (isset($modelInstructorVariableData->scholarity) &&
                    $modelInstructorVariableData->scholarity == 6) {

                if (isset($modelInstructorVariableData->high_education_situation_1, $modelInstructorVariableData->high_education_course_code_1_fk, $modelInstructorVariableData->high_education_institution_type_1, $modelInstructorVariableData->high_education_institution_code_1_fk)
                        || isset($modelInstructorVariableData->high_education_situation_2, $modelInstructorVariableData->high_education_course_code_2_fk, $modelInstructorVariableData->high_education_institution_type_2, $modelInstructorVariableData->high_education_institution_code_2_fk)
                        || isset($modelInstructorVariableData->high_education_situation_3, $modelInstructorVariableData->high_education_course_code_3_fk, $modelInstructorVariableData->high_education_institution_type_3, $modelInstructorVariableData->high_education_institution_code_3_fk)) {
                    $saveVariableData = true;
                } else {
                    $error['variableData'] = "Pelo menos uma situação do curso superior, código
do curso superior, tipo de instituição e instituição
do curso superior deverão ser obrigatoriamente
preenchidos";
                }
            } else {
                $saveVariableData = true;
            }

            //============================
            //=== MODEL TeachingData
            $disciplines = $modelInstructorTeachingData->discipline_1_fk;
            $countDisciplines = count($disciplines);
            //Máximo 13           
            $modelInstructorTeachingData->discipline_1_fk = isset($disciplines[0]) ? $disciplines[0] : 0;
            $modelInstructorTeachingData->discipline_2_fk = isset($disciplines[1]) ? $disciplines[1] : 0;
            $modelInstructorTeachingData->discipline_3_fk = isset($disciplines[2]) ? $disciplines[2] : 0;
            $modelInstructorTeachingData->discipline_4_fk = isset($disciplines[3]) ? $disciplines[3] : 0;
            $modelInstructorTeachingData->discipline_5_fk = isset($disciplines[4]) ? $disciplines[4] : 0;
            $modelInstructorTeachingData->discipline_6_fk = isset($disciplines[5]) ? $disciplines[5] : 0;
            $modelInstructorTeachingData->discipline_7_fk = isset($disciplines[6]) ? $disciplines[6] : 0;
            $modelInstructorTeachingData->discipline_8_fk = isset($disciplines[7]) ? $disciplines[7] : 0;
            $modelInstructorTeachingData->discipline_9_fk = isset($disciplines[8]) ? $disciplines[8] : 0;
            $modelInstructorTeachingData->discipline_10_fk = isset($disciplines[9]) ? $disciplines[9] : 0;
            $modelInstructorTeachingData->discipline_11_fk = isset($disciplines[10]) ? $disciplines[10] : 0;
            $modelInstructorTeachingData->discipline_12_fk = isset($disciplines[11]) ? $disciplines[11] : 0;
            $modelInstructorTeachingData->discipline_13_fk = isset($disciplines[12]) ? $disciplines[12] : 0;

            $saveTeachingData = true;
            //============================
            if ($saveInstructor && $saveDocumentsAndAddress && $saveTeachingData
                    && $saveVariableData) {

                // Setar todos os school_inep_id
                $modelInstructorDocumentsAndAddress->school_inep_id_fk = $modelInstructorIdentification->school_inep_id_fk;
                $modelInstructorTeachingData->school_inep_id_fk = $modelInstructorIdentification->school_inep_id_fk;
                $modelInstructorVariableData->school_inep_id_fk = $modelInstructorIdentification->school_inep_id_fk;

                if ($modelInstructorIdentification->validate()
                        && $modelInstructorDocumentsAndAddress->validate()
                        && $modelInstructorTeachingData->validate()
                        && $modelInstructorVariableData->validate()
                        && $modelInstructorIdentification->save()) {
                    $modelInstructorDocumentsAndAddress->id = $modelInstructorIdentification->id;
                    $modelInstructorTeachingData->id = $modelInstructorIdentification->id;
                    $modelInstructorVariableData->id = $modelInstructorIdentification->id;
// CORRIGIR !!!!!!!!!!!!!
                    //Get classInepID
                    $classRoom = Classroom::model()->findByPk($modelInstructorTeachingData->classroom_id_fk);
                    $modelInstructorTeachingData->inep_id = $classRoom->inep_id;

                    $modelInstructorDocumentsAndAddress->edcenso_uf_fk = $modelInstructorIdentification->edcenso_uf_fk;
                    $modelInstructorDocumentsAndAddress->edcenso_city_fk = $modelInstructorIdentification->edcenso_city_fk;

                    if ($modelInstructorDocumentsAndAddress->save()
                            && $modelInstructorVariableData->save()
                            && $modelInstructorTeachingData->save()) {
                        Yii::app()->user->setFlash('success', Yii::t('default', 'InstructorIdentification, DocumentsAndAddress, 
                            InstructorVariableData and InstructorTeachingData Created Successful:'));
                        $this->redirect(array('index'));
                    }
                }
            }
        }

        $this->render('create', array(
            'modelInstructorIdentification' => $modelInstructorIdentification,
            'modelInstructorDocumentsAndAddress' => $modelInstructorDocumentsAndAddress,
            'modelInstructorVariableData' => $modelInstructorVariableData,
            'modelInstructorTeachingData' => $modelInstructorTeachingData,
            'error' => $error,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        //=======================================

        $modelInstructorIdentification = $this->loadModel($id, $this->InstructorIdentification);
        $modelInstructorDocumentsAndAddress = $this->loadModel($id, $this->InstructorDocumentsAndAddress);
        $modelInstructorVariableData = $this->loadModel($id, $this->InstructorVariableData);
        $modelInstructorTeachingData = $this->loadModel($id, $this->InstructorTeachingData);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($modelStudentIdentification);   

        $saveInstructor = false;
        $saveDocumentsAndAddress = false;
        $saveVariableData = false;
        $saveTeachingData = false;

        $error[] = '';
        if (isset($_POST['InstructorIdentification'], $_POST['InstructorDocumentsAndAddress']
                        , $_POST['InstructorVariableData'], $_POST['InstructorTeachingData'])) {

            $modelInstructorIdentification->attributes = $_POST['InstructorIdentification'];
            $modelInstructorDocumentsAndAddress->attributes = $_POST['InstructorDocumentsAndAddress'];
            $modelInstructorVariableData->attributes = $_POST['InstructorVariableData'];
            $modelInstructorTeachingData->attributes = $_POST['InstructorTeachingData'];

            if (!isset($modelInstructorIdentification->edcenso_nation_fk)) {
                $modelInstructorIdentification->edcenso_nation_fk = 76;
            }
            if (!isset($modelInstructorIdentification->edcenso_uf_fk)) {
                $modelInstructorIdentification->edcenso_uf_fk = 0;
            }
            if (!isset($modelInstructorIdentification->edcenso_city_fk)) {
                $modelInstructorIdentification->edcenso_city_fk = 0;
            }


            $saveInstructor = true;

            //=== MODEL DocumentsAndAddress
            if (isset($modelInstructorDocumentsAndAddress->cep) && $modelInstructorDocumentsAndAddress->cep != 0) { // VERIFICAR POR que o bairro deve começar com inteiro
                //Então o endereço, uf e cidade são obrigatórios
                // var_dump(isset($modelInstructorDocumentsAndAddress->neighborhood) && $modelInstructorDocumentsAndAddress->neighborhood != 0);exit();
                if (isset($modelInstructorDocumentsAndAddress->address) && !empty($modelInstructorDocumentsAndAddress->address) &&
                        isset($modelInstructorDocumentsAndAddress->neighborhood) && !empty($modelInstructorDocumentsAndAddress->neighborhood) &&
                        isset($modelInstructorDocumentsAndAddress->edcenso_uf_fk) && !empty($modelInstructorDocumentsAndAddress->edcenso_uf_fk) &&
                        isset($modelInstructorDocumentsAndAddress->edcenso_city_fk) && !empty($modelInstructorDocumentsAndAddress->edcenso_city_fk)) {

                    $saveDocumentsAndAddress = true;
                } else {
                    $error['documentsAndAddress'] = 'CEP preenchido então, o Endereço, Bairro, UF e Cidade são Obrigatórios !';
                }
            }
            //======================================
            //=== MODEL VariableData            
            if (isset($modelInstructorVariableData->scholarity) &&
                    $modelInstructorVariableData->scholarity == 6) {



                if (isset($modelInstructorVariableData->high_education_situation_1, $modelInstructorVariableData->high_education_course_code_1_fk, $modelInstructorVariableData->high_education_institution_type_1, $modelInstructorVariableData->high_education_institution_code_1_fk)
                        || isset($modelInstructorVariableData->high_education_situation_2, $modelInstructorVariableData->high_education_course_code_2_fk, $modelInstructorVariableData->high_education_institution_type_2, $modelInstructorVariableData->high_education_institution_code_2_fk)
                        || isset($modelInstructorVariableData->high_education_situation_3, $modelInstructorVariableData->high_education_course_code_3_fk, $modelInstructorVariableData->high_education_institution_type_3, $modelInstructorVariableData->high_education_institution_code_3_fk)) {
                    $saveVariableData = true;
                } else {
                    $error['variableData'] = "Pelo menos uma situação do curso superior, código
do curso superior, tipo de instituição e instituição
do curso superior deverão ser obrigatoriamente
preenchidos";
                }
            }
            if (!isset($modelInstructorVariableData->high_education_institution_code_1_fk)) {
                $modelInstructorVariableData->high_education_institution_code_1_fk = 0;
            }
            if (!isset($modelInstructorVariableData->high_education_institution_code_2_fk)) {
                $modelInstructorVariableData->high_education_institution_code_2_fk = 0;
            }
            if (!isset($modelInstructorVariableData->high_education_institution_code_3_fk)) {
                $modelInstructorVariableData->high_education_institution_code_3_fk = 0;
            }


            //============================
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


            //=======================================

            if ($modelInstructorIdentification->validate() && $modelInstructorDocumentsAndAddress->validate()
                    && $modelInstructorVariableData->validate() && $modelInstructorTeachingData->validate()) {
                if ($modelInstructorIdentification->save()) {
                    if ($modelInstructorDocumentsAndAddress->save()) {
                        if ($modelInstructorVariableData->save()) {
                            if ($modelInstructorTeachingData->save()) {
                                $this->redirect(array('view', 'id' => $modelInstructorIdentification->id));
                            }
                        }
                    }
                }
            }
        }

        $this->render('update', array(
            'modelInstructorIdentification' => $modelInstructorIdentification,
            'modelInstructorDocumentsAndAddress' => $modelInstructorDocumentsAndAddress,
            'modelInstructorVariableData' => $modelInstructorVariableData,
            'modelInstructorTeachingData' => $modelInstructorTeachingData
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        
        $modelInstructorIdentification = $this->loadModel($id, $this->InstructorIdentification);
        $modelInstructorDocumentsAndAddress = $this->loadModel($id, $this->InstructorDocumentsAndAddress);
        $modelInstructorVariableData = $this->loadModel($id, $this->InstructorVariableData);
        $modelInstructorTeachingData = $this->loadModel($id, $this->InstructorTeachingData);

        if ( $modelInstructorDocumentsAndAddress->delete()
                && $modelInstructorVariableData->delete()
                && $modelInstructorTeachingData->delete()
                && $modelInstructorIdentification->delete()) {
            
            Yii::app()->user->setFlash('success', Yii::t('default', 'Instructor excluído com sucesso:'));
            $this->redirect(array('index'));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }


//            if( $this->loadModel($id, $this->SCHOOL_STRUCTURE)->delete()
//                && $this->loadModel($id, $this->SCHOOL_IDENTIFICATION)->delete()){
//                    Yii::app()->user->setFlash('success', Yii::t('default', 'Escola excluída com sucesso:'));
//                    $this->redirect(array('index'));
//            }else{
//                throw new CHttpException(404,'The requested page does not exist.');
//            }
//        
//        if (Yii::app()->request->isPostRequest) {
//            // we only allow deletion via POST request
//            $this->loadModel($id)->delete();
//
//            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
//            if (!isset($_GET['ajax']))
//                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
//        }
//        else
//            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('InstructorIdentification',
                        array('pagination' => array(
                                'pageSize' => 12,
                        )));
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionGetClassroom() {
        if (isset($_POST['InstructorIdentification'])) {
            $model = new InstructorIdentification();
            $model->attributes = $_POST['InstructorIdentification'];
        }
        $data = Classroom::model()->findAllByAttributes(array('school_inep_fk' => (int) $model->school_inep_id_fk));
        $data = CHtml::listData($data, 'id', 'name');

        echo CHtml::tag('option', array('value' => 'NULL'), '(Select a ClassRoom)', true);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    //Método para Solicitação Ajax para o select UF x Cities

    public function actionGetCities() {

        if (isset($_POST['InstructorIdentification'])) {
            $model = new InstructorIdentification();
            $model->attributes = $_POST['InstructorIdentification'];
        } else if (isset($_POST['InstructorDocumentsAndAddress'])) {
            $model = new InstructorDocumentsAndAddress();
            $model->attributes = $_POST['InstructorDocumentsAndAddress'];
        }


        $data = EdcensoCity::model()->findAll('edcenso_uf_fk=:uf_id', array(':uf_id' => (int) $model->edcenso_uf_fk));
        $data = CHtml::listData($data, 'id', 'name');

        echo CHtml::tag('option', array('value' => 'NULL'), '(Select a city)', true);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $modelInstructorIdentification = new InstructorIdentification('search');
        $modelInstructorDocumentsAndAddress = new InstructorDocumentsAndAddress('search');
        $modelInstructorVariableData = new InstructorVariableData('search');
        $modelInstructorTeachingData = new InstructorTeachingData('search');

        $modelInstructorIdentification->unsetAttributes();  // clear any default values
        $modelInstructorDocumentsAndAddress->unsetAttributes();  // clear any default values
        $modelInstructorVariableData->unsetAttributes();  // clear any default values
        $modelInstructorTeachingData->unsetAttributes();  // clear any default values
        if (isset($_GET[$this->InstructorIdentification], $_GET[$this->InstructorDocumentsAndAddress]
                        , $_GET[$this->InstructorVariableData], $_GET[$this->InstructorTeachingData])) {
            $modelInstructorIdentification->attributes = $_GET['InstructorIdentification'];
            $modelInstructorDocumentsAndAddress->attributes = $_GET['InstructorDocumentsAndAddress'];
            $modelInstructorVariableData->attributes = $_GET['InstructorVariableData'];
            $modelInstructorTeachingData->attributes = $_GET['InstructorTeachingData'];
        }



        $this->render('admin', array(
            'modelInstructorIdentification' => $modelInstructorIdentification,
            'modelInstructorDocumentsAndAddress' => $modelInstructorDocumentsAndAddress,
            'modelInstructorVariableData' => $modelInstructorVariableData,
            'modelInstructorTeachingData' => $modelInstructorTeachingData
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id, $model) {

        $return = null;
        if ($model == $this->InstructorIdentification) {
            $return = InstructorIdentification::model()->findByPk($id);
        } else if ($model == $this->InstructorDocumentsAndAddress) {
            // CORRIGIR 2.0
            $instructor_inep_ip = InstructorIdentification::model()->findByPk($id)->inep_id;
            $return = InstructorDocumentsAndAddress::model()->findByAttributes(
                    array('inep_id' => $instructor_inep_ip));
        } else if ($model == $this->InstructorVariableData) {
            $instructor_inep_ip = InstructorIdentification::model()->findByPk($id)->inep_id;
            $return = InstructorVariableData::model()->findByAttributes(
                    array('inep_id' => $instructor_inep_ip));
        } else if ($model == $this->InstructorTeachingData) {
            $instructor_inep_ip = InstructorIdentification::model()->findByPk($id)->inep_id;
            $return = InstructorTeachingData::model()->findByAttributes(
                    array('inep_id' => $instructor_inep_ip));
        }

        if ($return === null && $model == $this->InstructorIdentification) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $return;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'instructor-identification-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
