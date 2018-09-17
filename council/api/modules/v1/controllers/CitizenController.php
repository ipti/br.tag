<?php

namespace app\modules\v1\controllers;

use app\components\AuthController;
use app\modules\v1\models\Complaint;
use MongoDB\BSON\ObjectId;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use Yii;

class CitizenController extends AuthController
{
    
    public function actionView($id)
    {
        return Complaint::findOne(new ObjectId($id));
    }
    
    public function actionCreate()
    {
        $complaint = new Complaint(['scenario' => Complaint::SCENARIO_CITIZEN]);
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

}



?>
