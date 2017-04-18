<?php
// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$configtag=dirname(__FILE__).'/config.php';
$config=dirname(__FILE__).'/app/config/main.php';

require_once($configtag);
require_once($yii);
Yii::createWebApplication($config)->run();


