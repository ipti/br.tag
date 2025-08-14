<?php

class DefaultController extends Controller
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
        return [
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        ];
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            ['allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => ['index', 'view'],
                'users' => ['*'],
            ],
            ['allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['create', 'update'],
                'users' => ['@'],
            ],
            ['allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => ['admin', 'delete'],
                'users' => ['admin'],
            ],
            ['deny',  // deny all users
                'users' => ['*'],
            ],
        ];
    }

    /**
     * Displays a particular model.
     * @param int $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', [
            'model' => $this->loadModel($id),
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new GradeConcept();

        if (Yii::app()->request->isAjaxRequest) {
            $name = Yii::app()->request->getPost('name');
            $acronym = Yii::app()->request->getPost('acronym');
            $value = Yii::app()->request->getPost('value');

            $model->name = $name;
            $model->acronym = $acronym;
            $model->value = $value;

            if ($model->save()) {
                Yii::app()->user->setFlash('success', Yii::t('default', 'Conceito cadastrado com sucesso!'));
            }
        }

        if (isset($_POST['GradeConcept'])) {
            $model->attributes = $_POST['GradeConcept'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $conceptId = Yii::app()->request->getPost('conceptId');

        if ($conceptId) {
            $name = Yii::app()->request->getPost('name');
            $acronym = Yii::app()->request->getPost('acronym');
            $value = Yii::app()->request->getPost('value');

            $model->name = $name;
            $model->acronym = $acronym;
            $model->value = $value;

            if ($model->save()) {
                Yii::app()->user->setFlash('success', Yii::t('default', 'Conceito atualizado com sucesso!'));
            }
        }

        $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param int $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['admin']);
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('GradeConcept');
        $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new GradeConcept('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GradeConcept'])) {
            $model->attributes = $_GET['GradeConcept'];
        }

        $this->render('admin', [
            'model' => $model,
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param int $id the ID of the model to be loaded
     * @return GradeConcept the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = GradeConcept::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GradeConcept $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'grade-concept-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
