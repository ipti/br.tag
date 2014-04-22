<?php

class ReportsController extends Controller {

    public $layout = 'fullmenu';

    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'BFReport', 'getclasses'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionBFReport() {
        $infos = array();
        
        //@todo s3 - Verificar se a frequencia dos últimos 3 meses foi adicionada(existe pelo menso 1 class cadastrado no mês)
        //@todo s3 - Pegar todos os alunos matriculados nas turmas atuais.
        //@todo s3 - Calcular frequência para cada aluno: (Total de horários - faltas do aluno) / (Total de horários - Dias não ministrados)
        //@todo s3 - Gerar json para ser enviado pelo $infos
        
        $this->render('BFReport', array(
            'infos' => $infos,
        ));
    }

    public function actionIndex() {
        $this->render('index');
    }

}
