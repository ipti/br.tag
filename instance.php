<?php
$_FORMS[0] = array('name'=>'Ficha de Matrícula','action'=>'StudentFileForm');
$_FORMS[1] = array('name'=>'Declaração de Matrícula','action'=>'EnrollmentDeclarationReport');
$_FORMS[2] = array('name'=>'Ficha de Notas','action'=>'EnrollmentGradesReport');
$_FORMS[3] = array('name'=>'Notificação de Matrícula','action'=>'EnrollmentNotification');
$_FORMS[4] = array('name'=>'Declaração de Aluno','action'=>'StudentsDeclarationReport');
$_FORMS[5] = array('name'=>'Formulário de Transferência','action'=>'TransferForm');
$_FORMS[6] = array('name'=>'Requerimento de Transferência','action'=>'TransferRequirement');
$domain = array_shift((explode(".",$_SERVER['HTTP_HOST'])));
$_GLOBALGROUP = 0;
switch ($domain) {
    case 'propria':
        $instance = 'PROPRIÁ';
        $db = 'io.escola.se.propria';
        break;
    case 'afonsomedeiros':
        $instance = 'AFONSO DE MEDEIROS';
        $db = 'io.escola.se.propria.afonsomedeiros';
        break;
    case 'santaluzia':
        $instance = 'SANTA LUZIA DO ITANHY';
        $db = 'io.escola.se.santaluzia';
        break;
    case 'santaluzia2':
        $instance = 'SANTA LUZIA DO ITANHY - 2';
        $db = 'io.escola.se.santaluzia2';
        break;
    case 'edeziosouza':
        $instance = 'EDEZIO SOUZA - SANTA LUZIA';
        $db = 'io.escola.se.santaluzia.edeziosouza';
        break;
    case 'adelsonsilveira':
        $instance = 'ADELSON SILVEIRA - SANTA LUZIA';
        $db = 'io.escola.se.santaluzia.adelsonsilveira';
        break;
     case 'acrisiocruz':
        $instance = 'ACRISIO CRUZ - SANTA LUZIA';
        $db = 'io.escola.se.santaluzia.acrisiocruz';
        break;   
     case 'edmarjose':
        $instance = 'EDMAR JOSÉ - SANTA LUZIA';
        $db = 'io.escola.se.santaluzia.edmarjose';
        break;
     case 'josevicente':
        $instance = 'JOSÉ VICENTE - SANTA LUZIA';
        $db = 'io.escola.se.santaluzia.josevicente';
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
    case 'aquidaba':
        $instance = 'AQUIDABÃ';
        $db = 'io.escola.se.aquidaba';
        break;
    case 'malhada':
        $instance = 'MALHADA DOS BOIS';
        $db = 'io.escola.se.malhada';
        break;
    case 'romeuaguiar':
        $instance = 'ROMEU DE AGUIAR';
        $db = 'io.escola.se.malhada.romeuaguiar';
        break;
    case 'geminiano':
        $_GLOBALGROUP = 1;
        $_FORMS[2] = array('name'=>'Ficha de Notas','action'=>'EnrollmentGradesReportBoquim');
        $_FORMS[7] = array('name'=>'Ficha de Notas - Ciclo','action'=>'EnrollmentGradesReportBoquimCiclo');
        $instance = 'GEMINIANO';
        $db = 'io.escola.geminiano';
        break;
    case 'joaldo':
        $_GLOBALGROUP = 1;
        $_FORMS[2] = array('name'=>'Ficha de Notas','action'=>'EnrollmentGradesReportBoquim');
        $_FORMS[7] = array('name'=>'Ficha de Notas - Ciclo','action'=>'EnrollmentGradesReportBoquimCiclo');
        $instance = 'JOALDO';
        $db = 'io.escola.joaldo';
        break;
    case 'josegoes':
        $_GLOBALGROUP = 1;
        $_FORMS[2] = array('name'=>'Ficha de Notas','action'=>'EnrollmentGradesReportBoquim');
        $_FORMS[7] = array('name'=>'Ficha de Notas - Ciclo','action'=>'EnrollmentGradesReportBoquimCiclo');
        $instance = 'JOSE GOES';
        $db = 'io.escola.josegoes';
        break;
    case 'josejacomildes':
        $_GLOBALGROUP = 1;
        $_FORMS[2] = array('name'=>'Ficha de Notas','action'=>'EnrollmentGradesReportBoquim');
        $_FORMS[7] = array('name'=>'Ficha de Notas - Ciclo','action'=>'EnrollmentGradesReportBoquimCiclo');
        $instance = 'JOSE JACOMILDES';
        $db = 'io.escola.josejacomildes';
        break;
    case 'lourival':
        $_GLOBALGROUP = 1;
        $_FORMS[2] = array('name'=>'Ficha de Notas','action'=>'EnrollmentGradesReportBoquim');
        $_FORMS[7] = array('name'=>'Ficha de Notas - Ciclo','action'=>'EnrollmentGradesReportBoquimCiclo');
        $instance = 'LOURIVAL';
        $db = 'io.escola.lourival';
        break;
    case 'mariadagloria':
        $_GLOBALGROUP = 1;
        $_FORMS[2] = array('name'=>'Ficha de Notas','action'=>'EnrollmentGradesReportBoquim');
        $_FORMS[7] = array('name'=>'Ficha de Notas - Ciclo','action'=>'EnrollmentGradesReportBoquimCiclo');
        $instance = 'MARIA DA GLORIA';
        $db = 'io.escola.mariadagloria';
        break;
    case 'vanda':
        $_GLOBALGROUP = 1;
        $_FORMS[2] = array('name'=>'Ficha de Notas','action'=>'EnrollmentGradesReportBoquim');
        $_FORMS[7] = array('name'=>'Ficha de Notas - Ciclo','action'=>'EnrollmentGradesReportBoquimCiclo');
        $instance = 'VANDA';
        $db = 'io.escola.vanda';
        break;
   case 'adefib':
        $_GLOBALGROUP = 1;
        $_FORMS[2] = array('name'=>'Ficha de Notas','action'=>'EnrollmentGradesReportBoquim');
        $_FORMS[7] = array('name'=>'Ficha de Notas - Ciclo','action'=>'EnrollmentGradesReportBoquimCiclo');
        $instance = 'ADEFIB';
        $db = 'io.escola.adefib';
        break;
       
    case 'boquim':
        $_GLOBALGROUP = 1;
        $_FORMS[2] = array('name'=>'Ficha de Notas','action'=>'EnrollmentGradesReportBoquim');
        $_FORMS[7] = array('name'=>'Ficha de Notas - Ciclo','action'=>'EnrollmentGradesReportBoquimCiclo');
        $instance = 'BOQUIM';
        $db = 'io.escola.se.boquim';
        break;
    case 'demo':
        $_GLOBALGROUP = 1;
        $_FORMS[2] = array('name'=>'Ficha de Notas','action'=>'EnrollmentGradesReportBoquim');
        $_FORMS[7] = array('name'=>'Ficha de Notas - Ciclo','action'=>'EnrollmentGradesReportBoquimCiclo');
        $instance = 'DEMONSTRAÇÃO';
        $db = 'io.escola.se.demo';
        break;
    default:
        $instance = 'SERGIPE';
        $db = 'io.escola.se';
        break;
}
define("GLOGALGROUP",$_GLOBALGROUP);
define("FORMS",serialize($_FORMS));
define("DBNAME",$db);
define ("DBCONFIG", serialize (array(
    'connectionString' => "mysql:host=localhost;dbname=$db",
    'emulatePrepare' => true,
    'username' => 'user.tag',
    'password' => '123456',
    'charset' => 'utf8',
)));
define('INSTANCE',$instance);
