<?php
// change the following paths if necessary
//$yii=dirname(__FILE__).'/framework/yii.php';
//$yii=dirname(__FILE__).'/../framework/yii.php';
$yii=dirname(__FILE__).'/app/vendor/autoload.php';

$instance=dirname(__FILE__).'/instance.php';
$configtag=dirname(__FILE__).'/config.php';
$config=dirname(__FILE__).'/app/config/main.php';

require_once($yii);
require_once($configtag);
require_once($instance);
Yii::createWebApplication($config)->run();


