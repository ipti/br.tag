<?php

class DefaultController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */


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

    public function accessRules()
    {
        return array(
            array(
                'allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array(
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array(
                'deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionCreate()
    {
        $model = new EdcensoStageVsModality;

        $stageVsModality = Yii::app()->request->getPost('EdcensoStageVsModality');

        if (isset($stageVsModality)) {
            $model->attributes = $stageVsModality;
            if ($model->save()) {
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
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        $stageVsModality = Yii::app()->request->getPost('EdcensoStageVsModality');

        if (isset($stageVsModality)) {
            $model->attributes = $stageVsModality;
            if ($model->save()) {
                $this->redirect(array('update', 'id' => $model->id));
            }
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
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        $ajax = Yii::app()->request->getParam('ajax');
        $returnUrl = Yii::app()->request->getPost('returnUrl');

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($ajax)) {
            $this->redirect(isset($returnUrl) ? $returnUrl : array('admin'));
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('EdcensoStageVsModality', array('pagination' => false));
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new EdcensoStageVsModality('search');
        $model->unsetAttributes();
        $stageVsModality = Yii::app()->request->getParam('EdcensoStageVsModality');

        if (isset($stageVsModality)) {
            $model->attributes = $stageVsModality;
        }
        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return EdcensoStageVsModality the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = EdcensoStageVsModality::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param EdcensoStageVsModality $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        $ajax = Yii::app()->request->getPost('ajax');
        if (isset($ajax) && $ajax === 'edcenso-stage-vs-modality-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
