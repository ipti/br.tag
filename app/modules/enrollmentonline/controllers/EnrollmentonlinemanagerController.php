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
        // $dataProvider = new CActiveDataProvider('EnrollmentOnlineStudentIdentification',[
        //         'criteria' => [
        //             'select' => '
        //                 t.id,
        //                 t.name,
        //                 t.cpf,
        //                 t.responsable_name,
        //                 t.responsable_cpf,
        //                 t.edcenso_stage_vs_modality_fk AS etapa,
        //                 eoes.status AS status_matricula
        //             ',
        //             'join' => '
        //                 JOIN enrollment_online_enrollment_solicitation eoes
        //                     ON t.id = eoes.enrollment_online_student_identification_fk
        //                 JOIN edcenso_stage_vs_modality esvm
        //                     ON t.edcenso_stage_vs_modality_fk = esvm.id
        //             ',
        //             'group' => 't.id',
        //         ]
        //     ]
        // );

        // $columns = [
        //     ['name'=>'id','header'=>'ID','value'=>'$data->id'],
        //     [
        //         'name' => 'name',
        //         'type' => 'raw',
        //         'value' => '$data->name',
        //         'header'=> "Nome"
        //     ],
        //     [
        //         'name' => 'cpf',
        //         'header' => 'Cpf',
        //         'value' => '$data->cpf',
        //     ],
        //     [
        //         'name' => 'responsable_name',
        //         'header'=> 'Responsável',
        //         'value' =>'$data->responsable_name',
        //     ],
        //     [
        //         'name'=>'responsable_cpf',
        //         'header'=>'Cpf do responsável',
        //         'value'=>'$data->responsable_cpf'
        //     ],
        //     [
        //         'name'=>'etapa',
        //         'header'=> 'Etapa escolar',
        //         'value'=> '$data->etapa'
        //     ],
        //     [
        //         'name'=>'status_matricula',
        //         'header'=>'Status da matrícula',
        //         'value'=>'$data->status_matricula']
        // ];



        $mockData = [
            [
                'id' => 1,
                'name' => 'Maria Souza',
                'cpf' => '123.456.789-00',
                'responsable_name' => 'João Souza',
                'responsable_cpf' => '987.654.321-00',
                'etapa' => 'Ensino Fundamental I',
                'status_matricula' => 'Aprovado',
                'prioriedade'=> 'Sim',
            ],
            [
                'id' => 2,
                'name' => 'Carlos Lima',
                'cpf' => '321.654.987-00',
                'responsable_name' => 'Fernanda Lima',
                'responsable_cpf' => '654.987.321-00',
                'etapa' => 'Ensino Fundamental I',
                'status_matricula' => 'Pendente',
                'prioriedade'=> 'Não',
            ],
        ];

        $dataProvider = new CArrayDataProvider($mockData, [
            'keyField' => 'id',
            'pagination' => ['pageSize' => 10],
        ]);

        $columns = [
                ['name' => 'id', 'header' => 'ID'],
                ['name' => 'name', 'header' => 'Nome'],
                ['name' => 'cpf', 'header' => 'CPF'],
                ['name' => 'responsable_name', 'header' => 'Responsável'],
                ['name' => 'responsable_cpf', 'header' => 'CPF do Responsável'],
                ['name' => 'etapa', 'header' => 'Etapa Escolar'],
                ['name' => 'status_matricula', 'header' => 'Status da Matrícula'],
                ['name'=> 'prioriedade', 'header'=> 'Prioriedade'],
                [
                    'header'=> "Ações",
                    "type"=> "raw",
                    'value' => '
                        CHtml::button("Aceitar", [
                            "class" => "btn btn-success btn-sm",
                            "onclick" => "updateEnrollmentStatus($data->id, \'Aprovado\')"
                        ]) . " " .
                        CHtml::button("Negar", [
                            "class" => "btn btn-danger btn-sm",
                            "onclick" => "updateEnrollmentStatus($data->id, \'Rejeitado\')"
                        ])
                ',],
            ];

        $this->render('index', [
            'dataProvider' => $dataProvider,
            'columns' => $columns,
        ]);
    }

    public function actionUpdateEnrollmentStatus ($studentId,$status){
        $query = '
            UPDATE enrollment_online_enrollment_solicitation eoes
            JOIN enrollment_online_student_identification eosi
                ON eosi.id = eoes.enrollment_online_student_identification_fk
            SET eoes.status = :value
            WHERE eosi.id = :id';
        $data = Yii::app()->db->createCommand($query)->execute([
            ':value' => $status,
            ':id' => $studentId
        ]);
        $teste = "teste";
    }
}
