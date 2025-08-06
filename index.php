<?php

// change the following paths if necessary
// $yii_app=dirname(__FILE__).'/app/vendor/autoload.php';
$autoload = __DIR__.'/vendor/autoload.php';
$yii = __DIR__.'/vendor/yiisoft/yii/framework/yii.php';

$instance = __DIR__.'/instance.php';
$configtag = __DIR__.'/config.php';
$config = __DIR__.'/app/config/main.php';
$sagres = __DIR__.'/app/modules/sagres/models/SagresConsultModel.php';

$escola_t = __DIR__.'/app/modules/sagres/soap/src/sagresEdu/EscolaTType.php';
$turma_t = __DIR__.'/app/modules/sagres/soap/src/sagresEdu/TurmaTType.php';
$serie_t = __DIR__.'/app/modules/sagres/soap/src/sagresEdu/SerieTType.php';
$aluno_t = __DIR__.'/app/modules/sagres/soap/src/sagresEdu/AlunoTType.php';
$atendimento_t = __DIR__.'/app/modules/sagres/soap/src/sagresEdu/AtendimentoTType.php';
$educacao_t = __DIR__.'/app/modules/sagres/soap/src/sagresEdu/EducacaoTType.php';
$horario_t = __DIR__.'/app/modules/sagres/soap/src/sagresEdu/HorarioTType.php';
$matricula_t = __DIR__.'/app/modules/sagres/soap/src/sagresEdu/MatriculaTType.php';
$profissional_t = __DIR__.'/app/modules/sagres/soap/src/sagresEdu/ProfissionalTType.php';
$cardapio_t = __DIR__.'/app/modules/sagres/soap/src/sagresEdu/CardapioTType.php';
$diretor_t = __DIR__.'/app/modules/sagres/soap/src/sagresEdu/DiretorTType.php';
$cabecalho_t = __DIR__.'/app/modules/sagres/soap/src/sagresEdu/CabecalhoTType.php';

require_once $yii;
require_once $autoload;
require_once $configtag;
require_once $instance;

require_once $sagres;
require_once $aluno_t;
require_once $atendimento_t;
require_once $cabecalho_t;
require_once $cardapio_t;
require_once $diretor_t;
require_once $educacao_t;
require_once $escola_t;
require_once $horario_t;
require_once $matricula_t;
require_once $profissional_t;
require_once $serie_t;
require_once $turma_t;

require_once __DIR__.'/app/components/FeaturesComponent.php';

Yii::createWebApplication($config)->run();
