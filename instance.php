<?php
$_FORMS[0] = array('name' => 'Ficha de Matrícula', 'action' => 'StudentFileForm');
$_FORMS[1] = array('name' => 'Declaração de Matrícula', 'action' => 'EnrollmentDeclarationReport');
//$_FORMS[2] = array('name'=>'Ficha de Notas','action'=>'EnrollmentGradesReport');
//$_FORMS[3] = array('name'=>'Notificação de Matrícula','action'=>'EnrollmentNotification');
$_FORMS[4] = array('name' => 'Declaração de Aluno', 'action' => 'StudentsDeclarationReport');
$_FORMS[5] = array('name' => 'Formulário de Transferência', 'action' => 'TransferForm');
$_FORMS[6] = array('name' => 'Requerimento de Transferência', 'action' => 'TransferRequirement');
$_FORMS[8] = array('name' => 'Declaração de Cursou', 'action' => 'StatementAttended');
$_FORMS[9] = array('name' => 'Termo de Advertência', 'action' => 'WarningTerm');
$_FORMS[10] = array('name' => 'Certificado de Conclusão', 'action' => 'ConclusionCertification');
$_FORMS[11] = array('name' => 'Termo de Suspensão', 'action' => 'SuspensionTerm');

$host_array = explode(".", $_SERVER['HTTP_HOST']);
$domain = array_shift($host_array);

$newdb = $domain . '.tag.ong.br';

if ($domain == "localhost") {
    $newdb = 'boquim.tag.ong.br';
}

$_GLOBALGROUP = 0;

define("GLOGALGROUP", $_GLOBALGROUP);
define("FORMS", serialize($_FORMS));
define("DBNAME", $newdb);
$HOST = getenv("HOST_DB_TAG");
$USER = getenv("USER_DB_TAG");
$PWD = getenv("PWD_DB_TAG");

define ("DBCONFIG", serialize (array(
    'connectionString' => "mysql:host=$HOST;dbname=$newdb",
    'emulatePrepare' => true,
    'enableProfiling' => YII_DEBUG,
    'enableParamLogging' => YII_DEBUG,
    'username' => $USER,
    'password' => $PWD,
    'charset' => 'utf8',
)));

define('INSTANCE', strtoupper($domain));
