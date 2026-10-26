<?php

class ConceptController extends Controller
{
    public function filters()
    {
        return [
            'accessControl',
            'postOnly + delete',
        ];
    }

    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => ['index', 'create', 'update'],
                'users' => ['@'],
            ],
            [
                'allow',
                'actions' => ['delete'],
                'users' => ['admin'],
            ],
            [
                'deny',
                'users' => ['*'],
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('GradeConcept');
        $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionCreate()
    {
        $model = new GradeConcept();

        if (Yii::app()->request->isAjaxRequest) {
            $model->name = Yii::app()->request->getPost('name');
            $model->acronym = Yii::app()->request->getPost('acronym');
            $model->value = Yii::app()->request->getPost('value');

            if ($model->save()) {
                Yii::app()->user->setFlash('success', Yii::t('default', 'Conceito cadastrado com sucesso!'));
            }
        }

        if (isset($_POST['GradeConcept'])) {
            $model->attributes = $_POST['GradeConcept'];
            if ($model->save()) {
                $this->redirect(['index']);
            }
        }

        $this->render('_form', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $conceptId = Yii::app()->request->getPost('conceptId');

        if ($conceptId) {
            $model->name = Yii::app()->request->getPost('name');
            $model->acronym = Yii::app()->request->getPost('acronym');
            $model->value = Yii::app()->request->getPost('value');

            if ($model->save()) {
                Yii::app()->user->setFlash('success', Yii::t('default', 'Conceito atualizado com sucesso!'));
            }
        }

        $this->render('_form', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        if (!isset($_GET['ajax'])) {
            $returnUrl = isset($_POST['returnUrl']) ? $_POST['returnUrl'] : null;
            if ($returnUrl && preg_match('#^(\/|index\.php\?r=)#', $returnUrl)) {
                $this->redirect($returnUrl);
            } else {
                $this->redirect(['index']);
            }
        }
    }

    public function loadModel($id)
    {
        $model = GradeConcept::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }
}
