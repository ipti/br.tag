<?php
// change the following paths if necessary
//$yii_app=dirname(__FILE__).'/app/vendor/autoload.php';
$yii=dirname(__FILE__).'/vendor/autoload.php';

$instance=dirname(__FILE__).'/instance.php';
$configtag=dirname(__FILE__).'/config.php';
$config=dirname(__FILE__).'/app/config/main.php';
$sagres=dirname(__FILE__).'/app/modules/sagres/models/SagresConsultModel.php';

$escola_t=dirname(__FILE__).'/app/modules/sagres/soap/src/sagresEdu/EscolaTType.php';
$turma_t=dirname(__FILE__).'/app/modules/sagres/soap/src/sagresEdu/TurmaTType.php';
$serie_t=dirname(__FILE__).'/app/modules/sagres/soap/src/sagresEdu/SerieTType.php';
$aluno_t=dirname(__FILE__).'/app/modules/sagres/soap/src/sagresEdu/AlunoTType.php';
$atendimento_t=dirname(__FILE__).'/app/modules/sagres/soap/src/sagresEdu/AtendimentoTType.php';
$educacao_t=dirname(__FILE__).'/app/modules/sagres/soap/src/sagresEdu/EducacaoTType.php';
$horario_t=dirname(__FILE__).'/app/modules/sagres/soap/src/sagresEdu/HorarioTType.php';
$matricula_t=dirname(__FILE__).'/app/modules/sagres/soap/src/sagresEdu/MatriculaTType.php';
$profissional_t=dirname(__FILE__).'/app/modules/sagres/soap/src/sagresEdu/ProfissionalTType.php';
$cardapio_t=dirname(__FILE__).'/app/modules/sagres/soap/src/sagresEdu/CardapioTType.php';
$diretor_t=dirname(__FILE__).'/app/modules/sagres/soap/src/sagresEdu/DiretorTType.php';
$cabecalho_t=dirname(__FILE__).'/app/modules/sagres/soap/src/sagresEdu/CabecalhoTType.php';


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

require_once $yii;
require_once $configtag;
require_once $instance;

require_once dirname(__FILE__).'/app/components/FeaturesComponent.php';


Yii::createWebApplication($config)->run();


