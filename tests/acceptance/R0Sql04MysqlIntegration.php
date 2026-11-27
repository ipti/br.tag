<?php

/* Yii + MySQL integration against a disposable database only. */
if (getenv('TAG_TEST_DB_HOST') !== 'tag-r0-sql04-mysql' || getenv('TAG_TEST_DB_NAME') !== 'tag_r0_test') {
    throw new RuntimeException('Use somente o banco descartável dedicado ao R0-SQL-04.');
}
require __DIR__ . '/../../vendor/yiisoft/yii/framework/yii.php';
Yii::createConsoleApplication([
    'basePath' => realpath(__DIR__ . '/../../app'),
    'import' => ['application.models.*'],
    'components' => [
        'db' => [
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=tag-r0-sql04-mysql;dbname=tag_r0_test;charset=utf8mb4',
            'username' => 'root',
            'password' => '',
            'emulatePrepare' => false,
        ],
    ],
]);
class Controller extends CController {}
require __DIR__ . '/../../app/modules/sedsp/interfaces/AuthenticateSEDTokenInterface.php';
require __DIR__ . '/../../app/modules/student/controllers/EnrollmentController.php';

$db = Yii::app()->db;
// Temporary table is scoped to this connection and disappears on exit.
$db->createCommand('CREATE TEMPORARY TABLE edcenso_stage_vs_modality (id INT PRIMARY KEY, name VARCHAR(120), stage INT, edcenso_associated_stage_id INT) CHARACTER SET utf8mb4')->execute();
foreach ([[101, 'Pré & Creche', 1, 7], [102, 'Etapa <A>', 1, 8], [201, 'Outra etapa', 2, 9]] as $row) {
    $db->createCommand()->insert('edcenso_stage_vs_modality', [
        'id' => $row[0], 'name' => $row[1], 'stage' => $row[2], 'edcenso_associated_stage_id' => $row[3],
    ]);
}
$controller = new EnrollmentController('enrollment');
$fixture = json_decode(file_get_contents(__DIR__ . '/R0Sql04Characterization.json'), true, 512, JSON_THROW_ON_ERROR);
foreach (['0' => 'all', '1' => 'stage1', '2' => 'stage2', '99' => 'emptyResult'] as $stage => $case) {
    $_POST = ['Stage' => (string) $stage];
    ob_start();
    try { $controller->actionGetModalities(); $actual = ob_get_clean(); }
    catch (Throwable $e) { ob_end_clean(); throw $e; }
    if ($actual !== $fixture[$case]) throw new RuntimeException('Saída MySQL difere do golden master: ' . $case);
}
foreach ([['Stage' => '1 OR 1=1'], ['Stage' => '1) OR (1=1'], ['Stage' => 'abc'], ['Stage' => ['1']], []] as $post) {
    $_POST = $post;
    ob_start();
    try {
        $controller->actionGetModalities();
        throw new RuntimeException('Entrada inválida aceita');
    } catch (CHttpException $e) {
        if ($e->statusCode !== 400) throw $e;
    } finally {
        $output = ob_get_clean();
    }
    if ($output !== '') throw new RuntimeException('Entrada inválida produziu saída');
}
echo "R0-SQL-04: integração Yii + MySQL isolado aprovada\n";
