<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$_SERVER['HTTP_HOST'] = '192.168.15.28';
require '/app/vendor/autoload.php';
require '/app/vendor/yiisoft/yii/framework/yii.php';
require '/app/config.php';
require '/app/instance.php';
$config = unserialize(DBCONFIG);
echo 'DBNAME: ' . DBNAME . PHP_EOL;
echo 'HOST: ' . $config['connectionString'] . PHP_EOL;
try {
    $pdo = new PDO($config['connectionString'], $config['username'], $config['password']);
    echo 'Conexão OK!' . PHP_EOL;
} catch (Exception $e) {
    echo 'Erro: ' . $e->getMessage() . PHP_EOL;
}
