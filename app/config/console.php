<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'TAG',

	// preloading 'log' component
	'preload'=>array('log'),

	// application components
	'components'=>array(

		'db' => array(
            'connectionString' => 'mysql:host=db.ipti.org.br;dbname=br.org.ipti.boquim.tag',
            'emulatePrepare' => true,
            'username' => 'user.tag',
            'password' => '123456',
            'charset' => 'utf8',
        ),

		
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);