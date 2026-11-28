<?php

/* Dedicated test server only: real Yii routing/filter/exception handling, synthetic user. */
if (PHP_SAPI !== 'cli-server' || getenv('TAG_TEST_DB_HOST') !== 'tag-r0-sql04-mysql' || getenv('TAG_TEST_DB_NAME') !== 'tag_r0_test') {
    http_response_code(403);
    exit;
}
define('YII_DEBUG', true);
require __DIR__ . '/../../vendor/yiisoft/yii/framework/yii.php';
class Controller extends CController {}
require __DIR__ . '/../../app/modules/sedsp/interfaces/AuthenticateSEDTokenInterface.php';
$controllerFile = getenv('TAG_TEST_BASELINE') === '1'
    ? __DIR__ . '/R0Sql04Baseline.tmp.php'
    : __DIR__ . '/../../app/modules/student/controllers/EnrollmentController.php';
class R0SyntheticIdentity extends CBaseUserIdentity
{
    public function authenticate() { $this->errorCode = self::ERROR_NONE; return true; }
    public function getName() { return 'synthetic-test-user'; }
}
class R0FixtureController extends CController
{
    public function actionLogin()
    {
        $identity = new R0SyntheticIdentity();
        $identity->authenticate();
        Yii::app()->user->login($identity);
        echo 'authenticated';
    }
}
$app = Yii::createWebApplication([
    'basePath' => realpath(__DIR__ . '/../../app'),
    'runtimePath' => '/tmp',
    'import' => ['application.models.*'],
    'controllerMap' => [
        'enrollment' => ['class' => 'EnrollmentController'],
        'fixture' => ['class' => 'R0FixtureController'],
    ],
    'components' => [
        'user' => ['class' => 'CWebUser', 'allowAutoLogin' => false, 'loginUrl' => ['/fixture/login']],
        'db' => [
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=tag-r0-sql04-mysql;dbname=tag_r0_test;charset=utf8mb4',
            'username' => 'root', 'password' => '', 'emulatePrepare' => false,
        ],
    ],
]);
require $controllerFile;
$app->run();
