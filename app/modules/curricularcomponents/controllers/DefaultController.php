<?php

class DefaultController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    // public $layout='webroot.themes.default.views.layouts.fullmenu';

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
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('index','view'),
                'users'=>array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('create','update'),
                'users'=>array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('admin','delete'),
                'users'=>array('admin'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }


    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new EdcensoDiscipline();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $edcenso_base_disciplines =  EdcensoBaseDisciplines::model()->findAll();

        if(isset($_POST['EdcensoDiscipline'])) {
            $model->attributes=$_POST['EdcensoDiscipline'];
            if($model->save()) {
                $this->redirect(array('index'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('default', 'Não foi possível criar esse componente curricular'));
            }
        }

        $this->render('create', array(
            'model'=>$model,
            'edcenso_base_disciplines' => $edcenso_base_disciplines,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);
        $edcenso_base_disciplines =  EdcensoBaseDisciplines::model()->findAll();
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['EdcensoDiscipline'])) {
            $model->attributes=$_POST['EdcensoDiscipline'];
            if($model->save()) {
                $this->redirect(array('index'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('default', 'Não foi possível atualizar esse componente curricular'));
            }            
        }

        $this->render('update', array(
            'model'=>$model,
            'edcenso_base_disciplines' => $edcenso_base_disciplines,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $curricular_component = $this->loadModel($id);

        if($curricular_component->edcenso_base_discipline_fk < 99) {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Não é possível remover um componente base do EducaCenso'));
            $this->redirect(array('index'));
        }

        if($curricular_component->delete()){
            Yii::app()->user->setFlash('success', Yii::t('default', 'Componente curricular excluído com sucesso!'));
        } else {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Não é possível remover este componente curricular'));
        }

        $this->redirect(array('index'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('EdcensoDiscipline', array('pagination' => false));
        $this->render('index', array(
            'dataProvider'=> $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new EdcensoDiscipline('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['EdcensoDiscipline'])) {
            $model->attributes=$_GET['EdcensoDiscipline'];
        }

        $this->render('admin', array(
            'model'=>$model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return EdcensoDiscipline the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = EdcensoDiscipline::model()->findByPk($id);
        if($model===null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param EdcensoDiscipline $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='edcenso-discipline-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
