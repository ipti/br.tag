<?php
date_default_timezone_set('America/Maceio');
define("YII_ENBLE_ERROR_HANDLER",false);
define("YII_ENBLE_EXCEPTION_HANDLER",false);
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors','0');
ini_set('always_populate_raw_post_data','-1');

// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/app/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',false);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();

error_reporting(0);

