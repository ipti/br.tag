<?php
// change the following paths if necessary
//$yii=dirname(__FILE__).'/framework/yii.php';
//$yii=dirname(__FILE__).'/../framework/yii.php';

$instance=dirname(__FILE__).'/instance.php';
$config_tag=dirname(__FILE__).'/config.php';
$config=dirname(__FILE__).'/app/config/main.php';

require_once(dirname(__FILE__).'/app/vendor/autoload.php');
require_once($config_tag);  
require_once($instance);



Yii::createWebApplication($config)->run();


