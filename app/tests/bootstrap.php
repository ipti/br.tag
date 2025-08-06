<?php

// change the following paths if necessary
$yiit = __DIR__.'\..\vendor\yiisoft\yii\framework\yiit.php';
$config = __DIR__.'/../config/test.php';

require_once $yiit;
require_once __DIR__.'/WebTestCase.php';

Yii::createWebApplication($config);
