<?php


// require(dirname(__FILE__).'/main.php');
define("DBNAME","demo.tag.ong.br");
$HOST = getenv("HOST_DB_TAG");
$USER = getenv("USER_DB_TAG");
$PWD = getenv("PWD_DB_TAG");

define ("DBCONFIG", serialize (array(
    'connectionString' => "mysql:host=$HOST;dbname=demo.tag.ong.br",
    'emulatePrepare' => true,
    'username' => $USER,
    'password' => $PWD,
    'charset' => 'utf8',
)));


return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
			/* uncomment the following to provide test database connection
			// 'db'=>array(
			// 	'connectionString'=>'DSN for test database',
			// ),
			// */
		),
	)
);
