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

        $data = Yii::$app->request->get();
        $query = Complaint::find();
        if(isset($data['filter'])){
            switch($data['filter']){
                case "receive":
                    $query->where(['status' => 1, 'place' =>  $data['institution']]);
                break;
                case "forward":
                    $query->where(['status' => 2, 'place' => ['$ne' => $data['institution']]]);
                    $query->andWhere(['$in', 'was', [$data['institution']]]);
                break;
                case "analysis":
                    $query->where(['status' => 2, 'place' =>  $data['institution']]);
                break;
                case "completed":
                    $query->where(['status' => 9]);
                break;
            }
        }

        $complaints = [];
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);

        $pagination = $provider->getPagination();
        $paginationParam = [];
        if($pagination){
            $paginationParam['currentPage'] = $pagination->page;
            $paginationParam['perPage'] = $pagination->pageSize;
            $paginationParam['totalPages'] = $pagination->pageCount;
            $paginationParam['totalItens'] = $provider->getCount();
        }
        else{
            $paginationParam['currentPage'] = 0;
            $paginationParam['perPage'] = 10;
            $paginationParam['totalPages'] = 0;
            $paginationParam['totalItens'] = $provider->getCount();
        }

        $models = $provider->getModels();

        foreach($models as $model){
            $complaints[] = $model->formatData();
        }
        return array_merge(['complaints' => $complaints], ['pagination' =>$paginationParam]);
    }
    
    public function actionView($id)
    {
        $complaint = Complaint::findOne(new ObjectId($id));

        if(!is_null($complaint)){
            return [
                'status' => '1',
                'data' => $complaint->formatData(),
                'message' => 'Denúncia carregada com sucesso'
            ];
        }

        return [
            'status' => '0',
            'error' => ['complaint' => 'Denúncia não encontrada'],
            'message' => 'Denúncia não encontrada'
        ];
    }
    
    public function actionCreate()
    {
        $complaint = new Complaint(['scenario' => Complaint::SCENARIO_CREATE]);
        $data['Complaint'] = Yii::$app->request->post();

        if(isset($data['Complaint']['forwards'])){
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

        if(isset($data['Complaint']['forwards'])){
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

        if(isset($data['Complaint']['forwards'])){
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

        if(isset($data['Complaint']['forwards'])){
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

        if ($complaint->_update($data)) {
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

        if ($complaint->finalize()) {
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

    public function actionOption(){
        return [];
    }
}



?>
