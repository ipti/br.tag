<?php

namespace app\modules\v1\controllers;

use app\components\AuthController;
use app\modules\v1\models\Complaint;
use MongoDB\BSON\ObjectId;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use Yii;

class ComplaintController extends AuthController
{

    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => Complaint::find(),
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);
    }
    
    public function actionView($id)
    {
        return Complaint::findOne(new ObjectId($id));
    }
    
    public function actionCreate()
    {
        $complaint = new Complaint(['scenario' => Complaint::SCENARIO_CREATE]);
        $data['Complaint'] = Yii::$app->request->post();
        $files = $_FILES;

        if(isset($data['Complaint']['forwards'])){
            if(isset($files['files']) && count($files['files'])){
                $data['Complaint']['forwards'][0]['files'] = $files['files'];
            }

            if ($complaint->create($data)) {
                return [
                    'status' => '1',
                    'data' => ['_id' => (string) $complaint->_id],
                    'message' => 'Denúncia cadastrada com sucesso'
                ];
            }
        }

        return [
            'status' => '0',
            'error' => $complaint->getErrors(),
            'message' => 'Erro ao cadastrar denúncia'
        ];
    }

    public function actionFormalize($id){
        $complaint = Complaint::findOne(new ObjectId($id));
        $complaint->scenario = Complaint::SCENARIO_FORMALIZE;
        $data = ['Complaint' => Yii::$app->request->post()];
        $files = $_FILES;

        if(isset($data['Complaint']['forwards'])){
            if(isset($files['files']) && count($files['files'])){
                $data['Complaint']['forwards'][0]['files'] = $files['files'];
            }

            if ($complaint->formalize($data)) {
                return [
                    'status' => '1',
                    'data' => ['_id' => (string) $complaint->_id],
                    'message' => 'Denúncia cadastrada com sucesso'
                ];
            }
        }

        return [
            'status' => '0',
            'error' => $complaint->getErrors(),
            'message' => 'Erro ao cadastrar denúncia'
        ];

    }

    public function actionForward($id){
        $complaint = Complaint::findOne(new ObjectId($id));
        $complaint->scenario = Complaint::SCENARIO_FORWARD;
        $data = ['Complaint' => Yii::$app->request->post()];
        $files = $_FILES;

        if(isset($data['Complaint']['forwards'])){
            if(isset($files['files']) && count($files['files'])){
                $data['Complaint']['forwards'][0]['files'] = $files['files'];
            }

            if ($complaint->forward($data)) {
                return [
                    'status' => '1',
                    'data' => ['_id' => (string) $complaint->_id],
                    'message' => 'Emcaminhamento realizado com sucesso'
                ];
            }
        }

        return [
            'status' => '0',
            'error' => $complaint->getErrors(),
            'message' => 'Erro ao realizar encaminhamento'
        ];

    }

    public function actionResponse($id){
        $complaint = Complaint::findOne(new ObjectId($id));
        $complaint->scenario = Complaint::SCENARIO_RESPONSE;
        $data = ['Complaint' => Yii::$app->request->post()];
        $files = $_FILES;

        if(isset($data['Complaint']['forwards'])){
            if(isset($files['files']) && count($files['files'])){
                $data['Complaint']['forwards'][0]['files'] = $files['files'];
            }

            if ($complaint->response($data)) {
                return [
                    'status' => '1',
                    'data' => ['_id' => (string) $complaint->_id],
                    'message' => 'Resposta cadatrada com sucesso'
                ];
            }
        }


        return [
            'status' => '0',
            'error' => $complaint->getErrors(),
            'message' => 'Erro ao cadastrar resposta'
        ];

    }
    
    public function actionUpdate($id)
    {
        $complaint = Complaint::findOne(new ObjectId($id));
        $complaint->scenario = Complaint::SCENARIO_UPDATE;
        $data = ['Complaint' => Yii::$app->request->post()];

        if ($complaint->load($data) && $complaint->save()) {
            return [
                'status' => '1',
                'data' => ['_id' => (string) $complaint->_id],
                'message' => 'Denúncia atualizada com sucesso'
            ];
        }

        return [
            'status' => '0',
            'error' => $complaint->getErrors(),
            'message' => 'Erro ao atualizar denúncia'
        ];
    }
    
    public function actionDelete($id)
    {
        $complaint = Complaint::findOne(new ObjectId($id));
        $data = ['Complaint' => Yii::$app->request->post()];

        if ($complaint !== null && $complaint->delete()) {
            return [
                'status' => '1',
                'data' => ['_id' => $id],
                'message' => 'Denúncia excluída com sucesso'
            ];
        }

        return [
            'status' => '0',
            'error' => $complaint->getErrors(),
            'message' => 'Erro ao excluir denúncia'
        ];
    }

    public function actionFinalize($id)
    {
        $complaint = Complaint::findOne(new ObjectId($id));
        $data = ['Complaint' => Yii::$app->request->post()];

        if ($complaint->load($data) && $complaint->save()) {
            return [
                'status' => '1',
                'data' => ['_id' => $id],
                'message' => 'Denúncia concluída com sucesso'
            ];
        }

        return [
            'status' => '0',
            'error' => $complaint->getErrors(),
            'message' => 'Erro ao concluir denúncia'
        ];
    }
}



?>
