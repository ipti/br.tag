<?php

// ----------------------------------------
// Raiz do projeto (/app)
// ----------------------------------------
define('APP_ROOT', dirname(__DIR__));

// ----------------------------------------
// Autoload e Yii
// ----------------------------------------
$autoload = APP_ROOT . '/vendor/autoload.php';
$yii      = APP_ROOT . '/vendor/yiisoft/yii/framework/yii.php';

// ----------------------------------------
// Configurações globais
// ----------------------------------------
$instance   = APP_ROOT . '/instance.php';
$configtag  = APP_ROOT . '/config.php';
$config     = APP_ROOT . '/app/config/main.php';

// ----------------------------------------
// Módulo SAGRES (SOAP)
// ----------------------------------------
$sagres = APP_ROOT . '/app/modules/sagres/models/SagresConsultModel.php';

$soapBase = APP_ROOT . '/app/modules/sagres/soap/src/sagresEdu';

$soapFiles = [
    'AlunoTType.php',
    'AtendimentoTType.php',
    'CabecalhoTType.php',
    'CardapioTType.php',
    'DiretorTType.php',
    'EducacaoTType.php',
    'EscolaTType.php',
    'HorarioTType.php',
    'MatriculaTType.php',
    'ProfissionalTType.php',
    'SerieTType.php',
    'TurmaTType.php',
];

// ----------------------------------------
// Requires
// ----------------------------------------
require_once $yii;
require_once $autoload;
require_once $configtag;
require_once $instance;

require_once $sagres;

foreach ($soapFiles as $file) {
    require_once $soapBase . '/' . $file;
}

require_once APP_ROOT . '/app/components/FeaturesComponent.php';

// ----------------------------------------
// Inicializa Yii
// ----------------------------------------
Yii::createWebApplication($config)->run();
