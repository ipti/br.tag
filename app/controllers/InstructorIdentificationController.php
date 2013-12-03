<?php

class InstructorIdentificationController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'fullmenu';

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
                'actions' => array('index', 'view', 'create', 'update', 'getCities'),
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

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new InstructorIdentification;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['InstructorIdentification'])) {
            $model->attributes = $_POST['InstructorIdentification'];
            if(!isset($model->edcenso_nation_fk)){
              $model->edcenso_nation_fk = 76;  
            }
            if(!isset($model->edcenso_uf_fk)){
              $model->edcenso_uf_fk = 0;  
            }
             if(!isset($model->edcenso_city_fk)){
              $model->edcenso_city_fk = 0;  
            }
             if(!isset($model->deficiency_type_blindness)){
              $model->deficiency_type_blindness = 0;  
            }
             if(!isset($model->deficiency_type_low_vision)){
              $model->deficiency_type_low_vision = 0;  
            }
             if(!isset($model->deficiency_type_deafness)){
              $model->deficiency_type_deafness = 0;  
            }
             if(!isset($model->deficiency_type_disability_hearing)){
              $model->deficiency_type_disability_hearing = 0;  
            }
             if(!isset($model->deficiency_type_deafblindness)){
              $model->deficiency_type_deafblindness = 0;  
            }
             if(!isset($model->deficiency_type_phisical_disability)){
              $model->deficiency_type_phisical_disability = 0;  
            }
             if(!isset($model->deficiency_type_intelectual_disability)){
              $model->deficiency_type_intelectual_disability = 0;  
            }
             if(!isset($model->deficiency_type_multiple_disabilities)){
              $model->deficiency_type_multiple_disabilities = 0;  
            }
            
            if ($model->save()) {
                Yii::app()->user->setFlash('success', Yii::t('default', 'InstructorIdentification Created Successful:'));
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['InstructorIdentification'])) {
            $model->attributes = $_POST['InstructorIdentification'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
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
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
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

    //Método para Solicitação Ajax para o select UF x Cities

    public function actionGetCities() {
        $instructor = new InstructorIdentification();
        $instructor->attributes = $_POST['InstructorIdentification'];

        $data = EdcensoCity::model()->findAll('edcenso_uf_fk=:uf_id', array(':uf_id' => (int) $instructor->edcenso_uf_fk));
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
        $model = new InstructorIdentification('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['InstructorIdentification']))
            $model->attributes = $_GET['InstructorIdentification'];

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
        $model = InstructorIdentification::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
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
