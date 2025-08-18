<?php

defined('DBNAME') || define('DBNAME', 'demo.tag.ong.br');
$HOST = 'localhost:3306';
$USER = 'root';
$SECRET = 'root';

define('DBCONFIG', serialize([
    'connectionString' => "mysql:host=$HOST;dbname=demo.tag.ong.br",
    'emulatePrepare' => true,
    'username' => $USER,
    'password' => $SECRET,
    'charset' => 'utf8',
]));

return CMap::mergeArray(
    require_once dirname(__FILE__) . '/main.php',
    [
        'components' => [
            'fixture' => [
                'class' => 'system.test.CDbFixtureManager',
            ],
        ],
    ]
);
