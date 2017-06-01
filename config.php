<?php
defined('YII_DEBUG') or define('YII_DEBUG',FALSE);
define("YII_ENBLE_ERROR_HANDLER",false);
define("YII_ENBLE_EXCEPTION_HANDLER",false);
define("TAG_VERSION",'2.10.3');
$_FORMS = ['123'];

if(YII_DEBUG){
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
    error_reporting(E_ALL);
}else {
    ini_set('display_errors','0');
    error_reporting(0);
}

$domain = array_shift((explode(".",$_SERVER['HTTP_HOST'])));
switch ($domain) {
    case 'geminiano':
        $db = 'io.escola.geminiano';
        break;
    case 'joaldo':
        $db = 'io.escola.joaldo';
        break;
    case 'josegoes':
        $db = 'io.escola.josegoes';
        break;
    case 'josejacomildes':
        $db = 'io.escola.josejacomildes';
        break;
    case 'lourival':
        $db = 'br.org.ipti.boquim';
        break;
    case 'mariadagloria':
        $db = 'io.escola.mariadagloria';
        break;
    case 'vanda':
        $db = 'io.escola.vanda';
        break;
    default:
        $db = 'br.org.ipti.boquim.tag';
        break;
}

define('DATABASE_INSTANCE',$db);
date_default_timezone_set('America/Maceio');
ini_set('always_populate_raw_post_data','-1');
setlocale(LC_ALL, 'portuguese', 'pt_BR.UTF8', 'pt_br.UTF8', 'ptb_BRA.UTF8',"ptb", 'ptb.UTF8');