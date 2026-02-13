<?php

define('DBNAME', 'demo.tag.ong.br');
$HOST = getenv("HOST_DB_TAG");
$USER = getenv("USER_DB_TAG");
$PWD = getenv("PWD_DB_TAG");

define('DBCONFIG', serialize([
    'connectionString' => "mysql:host=$HOST",
    'emulatePrepare' => true,
    'username' => $USER,
    'password' => $PWD,
    'charset' => 'utf8',
]));

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return [
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'TAG',
    // preloading 'log' component
    'preload' => ['log'],
    'import' => [
        'application.models.*',
        'application.controllers.*',
        'application.components.*',
        'application.modules.wizard.models.*',
        'application.modules.calendar.models.*',
        'application.modules.quiz.models.*',
    ],
    // Custom console commands
    'commandMap' => [
        'sqlmigration' => [
            'class' => 'application.commands.SqlMigrationCommand',
        ],
    ],
    // application components
    'components' => [        
        'db' => unserialize(DBCONFIG),
        'authManager' => [
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
            'itemTable' => 'auth_item',
            'assignmentTable' => 'auth_assignment',
            'itemChildTable' => 'auth_item_child',
        ]
    ],
];
