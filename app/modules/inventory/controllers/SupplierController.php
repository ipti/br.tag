<?php

class SupplierController extends Controller
{
    public function filters()
    {
        return [
            'accessControl',
        ];
    }

    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => ['index', 'create', 'update', 'delete'],
                'users' => ['@'],
            ],
            [
                'deny',
                'users' => ['*'],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new InventorySupplier('search');
        $model->unsetAttributes();
        if (isset($_GET['InventorySupplier'])) {
            $model->attributes = $_GET['InventorySupplier'];
        }

        $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $model = new InventorySupplier();

        if (isset($_POST['InventorySupplier'])) {
            $model->attributes = $_POST['InventorySupplier'];
            if ($model->save()) {
                $this->redirect(['index']);
            }
        }

        $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if (isset($_POST['InventorySupplier'])) {
            $model->attributes = $_POST['InventorySupplier'];
            if ($model->save()) {
                $this->redirect(['index']);
            }
        }

        $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            $this->loadModel($id)->delete();
            if (!isset($_GET['ajax'])) {
                $this->redirect(['index']);
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function loadModel($id)
    {
        $model = InventorySupplier::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }
}
