<?php
defined('YII_DEBUG') or define('YII_DEBUG',FALSE);
define("TAG_VERSION",'2.10.5');
$_FORMS = ['123'];

if(YII_DEBUG){
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
    error_reporting(E_ALL);
}else {
    ini_set('display_errors','0');
    error_reporting(0);
    define("YII_ENBLE_ERROR_HANDLER",false);
    define("YII_ENBLE_EXCEPTION_HANDLER",false);
}

$domain = array_shift((explode(".",$_SERVER['HTTP_HOST'])));
switch ($domain) {
    case 'propria':
        $instance = 'PROPRIÁ';
        $db = 'io.escola.se.propria';
        break;
    case 'santaluzia':
        $instance = 'SANTA LUZIA DO ITANHY';
        $db = 'io.escola.se.santaluzia';
        break;
    case 'ilhadasflores':
        $instance = 'ILHA DAS FLORES';
        $db = 'io.escola.se.ilhadasflores';
        break;
    case 'saofrancisco':
        $instance = 'SÃO FRANCISCO';
        $db = 'io.escola.se.saofrancisco';
        break;
    case 'santanadosaofrancisco':
        $instance = 'SANTANA DO SÃO FRANCISCO';
        $db = 'io.escola.se.santanadosaofrancisco';
        break;
    case 'pacatuba':
        $instance = 'PACATUBA';
        $db = 'io.escola.se.pacatuba';
        break;
    case 'japoata':
        $instance = 'JAPOATÃ';
        $db = 'io.escola.se.japoata';
        break;
    case 'neopolis':
        $instance = 'NEOPOLIS';
        $db = 'io.escola.se.neopolis';
        break;
    case 'brejogrande':
        $instance = 'BREJO GRANDE';
        $db = 'io.escola.se.brejogrande';
        break;
    default:
        $instance = 'SERGIPE';
        $db = 'io.escola.se.santaluzia';
        break;
}
define('INSTANCE',$instance);
define('DATABASE_INSTANCE',$db);
date_default_timezone_set('America/Maceio');
ini_set('always_populate_raw_post_data','-1');
setlocale(LC_ALL, 'portuguese', 'pt_BR.UTF8', 'pt_br.UTF8', 'ptb_BRA.UTF8',"ptb", 'ptb.UTF8');