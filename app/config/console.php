<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'TAG',

    // preloading 'log' component
    'preload'=>array('log'),
    'import' => array(
        'application.models.*',
        'application.controllers.*',
        'application.components.*',
        'application.modules.wizard.models.*',
        'application.modules.calendar.models.*',
        'application.modules.quiz.models.*',
    ),
    // application components
    'components'=>array(

        'db2' => array(
            'connectionString' => 'mysql:host=51.81.125.135:31160;dbname=com.escola10',
            'emulatePrepare' => true,
            'username' => 'admin',
            'password' => '123456',
            'charset' => 'utf8',
            'class'   => 'CDbConnection'
        ),
        'db' => array(
            'connectionString' => 'mysql:host=51.81.125.135:31160;dbname=io.escola.se.boquim',
            'emulatePrepare' => true,
            'username' => 'admin',
            'password' => '123456',
            'charset' => 'utf8',
            'class'   => 'CDbConnection'
        ),
        'authManager' => array(
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
            'itemTable' => 'auth_item',
            'assignmentTable' => 'auth_assignment',
            'itemChildTable' => 'auth_item_child',
        )
    ),
);
