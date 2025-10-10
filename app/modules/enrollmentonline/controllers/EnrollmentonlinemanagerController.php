<?php

Yii::import('application.modules.enrollmentonline.repository.*');
class EnrollmentOnlineManagerController extends Controller
{

    public $layout = '//layouts/column2';


    public function accessRules()
    {
        return [
            [
                'allow',  // allow all users to perform 'index', 'view', and 'create' actions
                'actions' => ['index', 'view', 'create', 'getCities', 'getSchools'],
                'users' => ['*'],
            ],
            [
                'allow', // allow authenticated user to perform 'update' actions
                'actions' => ['update', 'StudentStatus'],
                'users' => ['@'],
            ],
            [
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => ['admin', 'delete'],
                'users' => ['admin'],
            ],
        ];
    }

    public function init()
    {
        if (!Yii::app()->user->isGuest) {
            $authTimeout = Yii::app()->user->getState('authTimeout', SESSION_MAX_LIFETIME);
            Yii::app()->user->authTimeout = $authTimeout;

            Yii::app()->sentry->setUserContext([
                'id' => Yii::app()->user->loginInfos->id,
                'username' => Yii::app()->user->loginInfos->username,
                'role' => Yii::app()->authManager->getRoles(Yii::app()->user->loginInfos->id)
            ]);
        }
    }

    public function actionIndex () {
        $dataProvider = new CActiveDataProvider('EnrollmentOnlineStudentIdentification',['criteria' => [
            'select'=> 't.id, t.name, t.cpf, t.responsable_name,t.responsable_cpf',
            'join'=> 'JOIN enrollment_online_enrollment_solicitation eoes on t.id = eoes.enrollment_online_student_identification_fk ;'
        ]]);
        $columns = [
            ['name'=>'id','header'=>'ID','value'=>'$data->id'],
            [
                'name' => 'name',
                'type' => 'raw',
                'value' => '$data->name',
                'header'=> "Nome"
            ],
            [
                'name' => 'cpf',
                'header' => 'Cpf',
                'value' => '$data->cpf',
            ],
            [
                'name' => 'responsable_name',
                'header'=> 'Responsável',
                'value' =>'$data->responsable_name',
            ],
            [
                'name'=>'responsable_cpf',
                'header'=>'Cpf do responsável',
                'value'=>'$data->responsable_cpf'
            ],
            [
                'name'=>'',
            ]
    ];


        $this->render('index', [
            'dataProvider' => $dataProvider,
            "columns" => $columns
        ]);
    }
}
