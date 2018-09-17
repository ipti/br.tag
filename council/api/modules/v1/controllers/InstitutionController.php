<?php

namespace app\modules\v1\controllers;

use app\components\AuthController;
use app\modules\v1\models\Institution;
use MongoDB\BSON\ObjectId;
use yii\data\ActiveDataProvider;
use Yii;

class InstitutionController extends AuthController
{

    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => Institution::find(),
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);
    }
    
    public function actionView($id)
    {
        return Institution::findOne(new ObjectId($id));
    }
    
    public function actionCreate()
    {
        $institution = new Institution;
        $data['Institution'] = Yii::$app->request->post();

        if ($institution->load($data) && $institution->save()) {
            return [
                'status' => '1',
                'data' => ['_id' => (string) $institution->_id],
                'message' => 'Instituição cadastrada com sucesso'
            ];
        }

        return [
            'status' => '0',
            'error' => $institution->getErrors(),
            'message' => 'Erro ao cadastrar instituição'
        ];
    }
    
    public function actionUpdate($id)
    {
        $id = Yii::$app->request->post()['id'];
        $institution = Institution::findOne(new ObjectId($id));
        $data = ['Institution' => Yii::$app->request->post()];

        if ($institution->load($data) && $institution->save()) {
            return [
                'status' => '1',
                'data' => ['_id' => (string) $institution->_id],
                'message' => 'Instituição atualizada com sucesso'
            ];
        }

        return [
            'status' => '0',
            'error' => $institution->getErrors(),
            'message' => 'Erro ao atualizar instituição'
        ];
    }
    
    public function actionDelete($id)
    {
        //$id = Yii::$app->request->post()['id'];
        $institution = Institution::findOne(new ObjectId($id));
        $data = ['Institution' => Yii::$app->request->post()];

        if ($institution !== null && $institution->delete()) {
            return [
                'status' => '1',
                'data' => ['_id' => $id],
                'message' => 'Instituição excluída com sucesso'
            ];
        }

        return [
            'status' => '0',
            'error' => $institution->getErrors(),
            'message' => 'Erro ao excluir instituição'
        ];
    }
}



?>
