<?php
$_FORMS[0] = array('name'=>'Ficha de Matrícula','action'=>'StudentFileForm');
$_FORMS[1] = array('name'=>'Declaração','action'=>'EnrollmentDeclarationReport');
$_FORMS[2] = array('name'=>'Ficha de Notas','action'=>'EnrollmentGradesReport');
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
    case 'joaobosco':
        $instance = 'JOÃO BOSCO';
        $db = 'io.escola.joaobosco';
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
    case 'cedro':
        $instance = 'CEDRO';
        $db = 'io.escola.se.cedro';
        break;
    case 'canhoba':
        $instance = 'CANHOBA';
        $db = 'io.escola.se.canhoba';
        break;
    case 'telha':
        $instance = 'TELHA';
        $db = 'io.escola.se.telha';
        break;
    case 'amparo':
        $instance = 'AMPARO DO SÃO FRANCISCO';
        $db = 'io.escola.se.amparo';
        break;
    case 'malhada':
        $instance = 'MALHADA DOS BOIS';
        $db = 'io.escola.se.malhada';
        break;
    case 'geminiano':
        $instance = 'GEMINIANO';
        $db = 'io.escola.geminiano';
        break;
    case 'joaldo':
        $instance = 'JOALDO';
        $db = 'io.escola.joaldo';
        break;
    case 'josegoes':
        $instance = 'JOSE GOES';
        $db = 'io.escola.josegoes';
        break;
    case 'josejacomildes':
        $instance = 'JOSE JACOMILDES';
        $db = 'io.escola.josejacomildes';
        break;
    case 'lourival':
        $instance = 'LOURIVAL';
        $db = 'io.escola.lourival';
        break;
    case 'mariadagloria':
        $instance = 'MARIA DA GLORIA';
        $db = 'io.escola.mariadagloria';
        break;
    case 'vanda':
        $instance = 'VANDA';
        $db = 'io.escola.vanda';
        break;
    default:
        $instance = 'SERGIPE';
        $db = 'io.escola.se.boquim';
        break;
}
define("FORMS",serialize($_FORMS));
define("DBNAME",$db);
define ("DBCONFIG", serialize (array(
    'connectionString' => "mysql:host=localhost;dbname=$db",
    'emulatePrepare' => true,
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
)));
define('INSTANCE',$instance);
