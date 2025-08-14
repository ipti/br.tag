<?php

class CourseClassAbilitiesController extends Controller
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
            [
                'allow',
                'actions' => ['index', 'view'],
                'users' => ['*'],
            ],
            [
                'allow',
                'actions' => ['create', 'update', 'delete'],
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
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new CourseClassAbilities();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['CourseClassAbilities'])) {
            $model->attributes = $_POST['CourseClassAbilities'];
            $model->type = 'HABILIDADE';

            $parent = CourseClassAbilities::model()->find([
                'condition' => 'edcenso_discipline_fk = :disc AND parent_fk IS NULL',
                'params' => [':disc' => $model->edcenso_discipline_fk],
            ]);
            $model->parent_fk = $parent->id;

            if ($model->save()) {
                $this->redirect(['index']);
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

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['CourseClassAbilities'])) {
            $model->attributes = $_POST['CourseClassAbilities'];

            $parent = CourseClassAbilities::model()->find([
                'condition' => 'edcenso_discipline_fk = :disc AND parent_fk IS NULL',
                'params' => [':disc' => $model->edcenso_discipline_fk],
            ]);
            $model->parent_fk = $parent->id;
            if ($model->save()) {
                $this->redirect(['index']);
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
            $this->redirect(['index']);
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $criteria = new CDbCriteria();

        $criteria->addCondition('code IS NOT NULL');

        $criteria->addCondition("created_at >= '2025-01-01'");

        $dataProvider = new CActiveDataProvider('CourseClassAbilities', [
            'criteria' => $criteria,
            'pagination' => false,
        ]);
        $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param int $id the ID of the model to be loaded
     * @return CourseClassAbilities the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = CourseClassAbilities::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CourseClassAbilities $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'course-class-abilities-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
