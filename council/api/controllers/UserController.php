<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\User;
use MongoDB\BSON\ObjectId;
use yii\data\ActiveDataProvider;
use Yii;

class UserController extends Controller
{
    public $enableCsrfValidation = false;

    
    public static function allowedDomains() {
        return [
            '*'
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return array_merge(parent::behaviors(), [
    
            // For cross-domain AJAX request
            'corsFilter'  => [
                'class' => \yii\filters\Cors::className(),
                'cors'  => [
                    // restrict access to domains:
                    'Origin'                           => static::allowedDomains(),
                    'Access-Control-Request-Method'    => ['POST','OPTIONS','GET','PUT','HEAD','DELETE','PATCH'],
                    'Access-Control-Request-Headers'    => ['*'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age'           => 3600,                 // Cache (seconds)
                ],
            ],
    
        ]);
    }
    
    public function actionLogin()
    {
        $data = Yii::$app->request->post();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (isset($data['username']) && isset($data['password'])) {
            $user = User::findByUsername($data['username']);

            if($user !== null && $user->validatePassword($data['password'])){
                $token = $user->generateAccessToken();
                return [
                    'status' => '1',
                    'data' => ['_id' => (string) $user->_id, 'access_token' => $token],
                    'message' => 'Login efetuado com sucesso'
                ];
            }

        }

        return [
            'status' => '0',
            'error' => ['login' => 'Credencial invalída'],
            'message' => 'Credencial invalída'
        ];
    }

    public function actionLogout()
    {
        $data = Yii::$app->request->post();

        if (isset($data['id'])) {
            $user = User::findIdentity($data['id']);
            if($user !== null){
                $user->destroyAccessToken();
                return [
                    'status' => '1',
                    'data' => [],
                    'message' => 'Logout efetuado com sucesso'
                ];
            }

        }

        return [
            'status' => '0',
            'error' => [],
            'message' => 'Erro ao efetuar logout'
        ];
    }
}



?>
