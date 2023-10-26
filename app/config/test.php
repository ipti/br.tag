<?php

// require(dirname(__FILE__).'/main.php');
defined("DBNAME") or define("DBNAME","demo.tag.ong.br");
$HOST = "localhost:3306";
$USER = 'root';
$PWD = 'root';

define ("DBCONFIG", serialize (array(
    'connectionString' => "mysql:host=$HOST;dbname=demo.tag.ong.br",
    'emulatePrepare' => true,
    'username' => $USER,
    'password' => $SECRET,
    'charset' => 'utf8',
)));


return CMap::mergeArray(
    require(dirname(__FILE__).'/main.php'),
    array(
        'components'=>array(
            'fixture'=>array(
                'class'=>'system.test.CDbFixtureManager',
            ),
            'db' => array(
                'class'=>'CDbConnection',
                'connectionString'=>'mysql:host=localhost;dbname=demo.tag.ong.br',
                'username'=>'root',
                'password'=>'root',
                'emulatePrepare'=>true,  // needed by some MySQL installations
            ),
            /* uncomment the following to provide test database connection
            // */
        ),
    )
);
