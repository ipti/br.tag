<?php
$_FORMS = ['123'];
$domain = array_shift((explode(".",$_SERVER['HTTP_HOST'])));
switch ($domain) {
    case 'propria':
        $instance = 'PROPRIÁ';
        $db = 'io.escola.se.propria';
        break;
    case 'santaluzia':
        $instance = 'SANTA LUZIA DO ITANHY';
        $db = 'io.escola.se.santaluzia';
        break;
    case 'ilhadasflores':
        $instance = 'ILHA DAS FLORES';
        $db = 'io.escola.se.ilhadasflores';
        break;
    case 'saofrancisco':
        $instance = 'SÃO FRANCISCO';
        $db = 'io.escola.se.saofrancisco';
        break;
    case 'santanadosaofrancisco':
        $instance = 'SANTANA DO SÃO FRANCISCO';
        $db = 'io.escola.se.santanadosaofrancisco';
        break;
    case 'pacatuba':
        $instance = 'PACATUBA';
        $db = 'io.escola.se.pacatuba';
        break;
    case 'japoata':
        $instance = 'JAPOATÃ';
        $db = 'io.escola.se.japoata';
        break;
    case 'neopolis':
        $instance = 'NEOPOLIS';
        $db = 'io.escola.se.neopolis';
        break;
    case 'brejogrande':
        $instance = 'BREJO GRANDE';
        $db = 'io.escola.se.brejogrande';
        break;
    default:
        $instance = 'SERGIPE';
        $db = 'io.escola.se.santaluzia';
        break;
}
define("DBNAME",$db);
define ("DBCONFIG", serialize (array(
    'connectionString' => "mysql:host=localhost;dbname=$db",
    'emulatePrepare' => true,
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
)));
define('INSTANCE',$instance);
