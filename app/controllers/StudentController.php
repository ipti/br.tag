<?php

class StudentController extends Controller {
    //@done s1 - validação de todos os campos - Colocar uma ? para explicar as regras de cada campo(em todas as telas)
    //@done s1 - Recuperar endereço pelo CEP
    //@done s1 - validar CPF
    //@done s1 - Campo Cartórios - Colocar em ordem alfabética
    //@done s1 - Campo TIpo de Certidão Civil (Add as opções)
    //@done s1 - atualizar dependencia de select2
    //@done s1 - corrigir o deletar
    
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'fullmenu';
    private $STUDENT_IDENTIFICATION = 'StudentIdentification';
    private $STUDENT_DOCUMENTS_AND_ADDRESS = 'StudentDocumentsAndAddress';
    private $STUDENT_ENROLLMENT = 'StudentEnrollment';

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
                'actions' => array('index', 'view', 'create', 'update', 'getcities', 'getnotaryoffice', 'getnations', 'delete'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin'),
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
            'modelStudentIdentification' => $this->loadModel($id, $this->STUDENT_IDENTIFICATION),
            'modelStudentDocumentsAndAddress' => $this->loadModel($id, $this->STUDENT_DOCUMENTS_AND_ADDRESS),
        ));
    }

    public function actionGetCities() {
        $register_type = isset($_GET["rt"]) ? $_GET["rt"] : 0;
        $uf = null;
        if ($register_type == 0) {
            $student = new StudentIdentification();
            $student->attributes = $_POST[$this->STUDENT_IDENTIFICATION];
            $uf = (int) $student->edcenso_uf_fk;
        } else if ($register_type == 1){
            $student = new StudentDocumentsAndAddress();
            $student->attributes = $_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS];
            $uf = (int) $student->notary_office_uf_fk;
        } else if($register_type == 2){
            $student = new StudentDocumentsAndAddress();
            $student->attributes = $_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS];
            $uf = (int) $student->edcenso_uf_fk;
        }

        $data = EdcensoCity::model()->findAll('edcenso_uf_fk=:uf_id', array(':uf_id' => $uf));
        $data = CHtml::listData($data, 'id', 'name');

        echo CHtml::tag('option', array('value' => null), 'Selecione uma cidade', true);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    public function actionGetNotaryOffice() {
        $student = new StudentDocumentsAndAddress();
        $student->attributes = $_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS];

        $data = EdcensoNotaryOffice::model()->findAllByAttributes(array('city' => (int) $student->notary_office_city_fk), array('order' => 'name'));
        $data = CHtml::listData($data, 'cod', 'name');

        echo CHtml::tag('option', array('value' => null), 'Selecione um cartório', true);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    public function actionGetNations() {
        $student = new StudentIdentification();
        $student->attributes = $_POST[$this->STUDENT_IDENTIFICATION];

        $where = "";
        if($student->nationality == 3){
            $where = "";
            echo CHtml::tag('option', array('value' => null), 'Selecione uma nação', true);
        }else{
            $where = "id = 76";
        }
        $data = EdcensoNation::model()->findAll($where);
        $data = CHtml::listData($data, 'id', 'name');

        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $modelStudentIdentification = new StudentIdentification;
        $modelStudentDocumentsAndAddress = new StudentDocumentsAndAddress;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model
        if (isset($_POST[$this->STUDENT_IDENTIFICATION]) && isset($_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS])) {
            $modelStudentIdentification->attributes = $_POST[$this->STUDENT_IDENTIFICATION];
            $modelStudentDocumentsAndAddress->attributes = $_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS];
            
            //Atributos comuns entre as tabelas
            $modelStudentDocumentsAndAddress->school_inep_id_fk = $modelStudentIdentification->school_inep_id_fk;
            $modelStudentDocumentsAndAddress->student_fk = $modelStudentIdentification->inep_id;
            $modelStudentDocumentsAndAddress->nis = $modelStudentIdentification->nis;
            
            if ($modelStudentIdentification->validate() && $modelStudentDocumentsAndAddress->validate()) {
                if ($modelStudentIdentification->save()) {
                    $modelStudentDocumentsAndAddress->id = $modelStudentIdentification->id;
                    
                    if($modelStudentDocumentsAndAddress->validate()){
                        if ($modelStudentDocumentsAndAddress->save()) {
                            Yii::app()->user->setFlash('success', Yii::t('default', 'Aluno adicionado com sucesso!'));
                            $this->redirect(array('index'));
                        }
                    }
                }
            }
        }

        $this->render('create', array(
            'modelStudentIdentification' => $modelStudentIdentification,
            'modelStudentDocumentsAndAddress' => $modelStudentDocumentsAndAddress
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $modelStudentIdentification = $this->loadModel($id, $this->STUDENT_IDENTIFICATION);
        $modelStudentDocumentsAndAddress = $this->loadModel($id, $this->STUDENT_DOCUMENTS_AND_ADDRESS);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($modelStudentIdentification);

        if (isset($_POST[$this->STUDENT_IDENTIFICATION]) && isset($_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS])) {
            $modelStudentIdentification->attributes = $_POST[$this->STUDENT_IDENTIFICATION];
            $modelStudentDocumentsAndAddress->attributes = $_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS];
            
            //Atributos comuns entre as tabelas
            $modelStudentDocumentsAndAddress->school_inep_id_fk = $modelStudentIdentification->school_inep_id_fk;
            $modelStudentDocumentsAndAddress->student_fk = $modelStudentIdentification->inep_id;
            $modelStudentDocumentsAndAddress->nis = $modelStudentIdentification->nis;
            
            if ($modelStudentIdentification->validate() && $modelStudentDocumentsAndAddress->validate()) {
                if ($modelStudentIdentification->save()) {
                    if ($modelStudentDocumentsAndAddress->save()) {
                        Yii::app()->user->setFlash('success', Yii::t('default', 'Aluno alterado com sucesso!'));
                        $this->redirect(array('index'));
                    }
                }
            }
        }

        $this->render('update', array(
            'modelStudentIdentification' => $modelStudentIdentification,
            'modelStudentDocumentsAndAddress' => $modelStudentDocumentsAndAddress
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $enrollment = $this->loadModel($id, $this->STUDENT_ENROLLMENT);
        $delete = true;
        foreach ($enrollment as $e) {$delete = $delete && $e->delete();}
        
        if( $delete
            && $this->loadModel($id, $this->STUDENT_DOCUMENTS_AND_ADDRESS)->delete()
            && $this->loadModel($id, $this->STUDENT_IDENTIFICATION)->delete()){
                Yii::app()->user->setFlash('success', Yii::t('default', 'Aluno excluído com sucesso!'));
                $this->redirect(array('index'));
        }else{
            throw new CHttpException(404,'The requested page does not exist.');
        }
        
        
        
        
//        if (Yii::app()->request->isPostRequest) {
//            // we only allow deletion via POST request
//            $this->loadModel($id, $this->STUDENT_DOCUMENTS_AND_ADDRESS)->delete();
//            $this->loadModel($id, $this->STUDENT_IDENTIFICATION)->delete();
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
        $filter = new StudentIdentification('search');
        $filter->unsetAttributes();  // clear any default values
        if (isset($_GET['StudentIdentification'])) {
            $filter->attributes = $_GET['StudentIdentification'];
        }
        $school = Yii::app()->user->school;
        $dataProvider = new CActiveDataProvider($this->STUDENT_IDENTIFICATION,
                        array(
                            'criteria'=>array(
                                'condition'=>'school_inep_id_fk='.$school,
                                //'order'=>'name ASC',
                            ),
                            
                            'pagination' => array(
                                'pageSize' => 12,
                        )));
        
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'filter' => $filter
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $modelStudentIdentification = new StudentIdentification('search');
        $modelStudentIdentification->unsetAttributes();  // clear any default values
        $modelStudentDocumentsAndAddress = new StudentDocumentsAndAddress('search');
        $modelStudentDocumentsAndAddress->unsetAttributes();  // clear any default values

        if (isset($_GET[$this->STUDENT_IDENTIFICATION]) && isset($_GET[$this->STUDENT_DOCUMENTS_AND_ADDRESS])) {
            $modelStudentIdentification->attributes = $_GET[$this->STUDENT_IDENTIFICATION];
            $modelStudentDocumentsAndAddress->attributes = $_GET[$this->STUDENT_DOCUMENTS_AND_ADDRESS];
        }
        $this->render('admin', array(
            'modelStudentIdentification' => $modelStudentIdentification,
            'modelStudentDocumentsAndAddress' => $modelStudentDocumentsAndAddress,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id, $model) {
        
        $return = null;

        if ($model == $this->STUDENT_IDENTIFICATION) {
            $return = StudentIdentification::model()->findByPk($id);
        } else if ($model == $this->STUDENT_DOCUMENTS_AND_ADDRESS) {
            $student_inep_id = StudentIdentification::model()->findByPk($id)->inep_id;
            
            $return = ($student_inep_id === null) 
                    ? StudentDocumentsAndAddress::model()->findByAttributes(array('id' => $id)) 
                    : StudentDocumentsAndAddress::model()->findByAttributes(array('student_fk' => $student_inep_id));
        } else if ($model == $this->STUDENT_ENROLLMENT){            
            $return = StudentEnrollment::model()->findAllByAttributes(array('student_fk' => $id));
        }
        if ($return === null){
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $return;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'student') {
            echo CActiveForm::validate($model);
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
