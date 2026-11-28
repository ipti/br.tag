<?php

/*
 * R0-SQL-06: bootstrap compartilhado dos testes de SchoolController::actionUpdateUfDependencies.
 * Executa a action real contra um MySQL descartável (esquema real, dados sintéticos).
 * Nunca aponta para a base de desenvolvimento.
 */
if (getenv('TAG_TEST_DB_HOST') !== 'tag-r0-sql06-mysql' || getenv('TAG_TEST_DB_NAME') !== 'tag_r0_test') {
    throw new RuntimeException('Use somente o banco descartável dedicado ao R0-SQL-06.');
}

require __DIR__ . '/../../vendor/yiisoft/yii/framework/yii.php';
Yii::createConsoleApplication([
    'basePath' => realpath(__DIR__ . '/../../app'),
    'import' => ['application.models.*'],
    'components' => [
        'db' => [
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=tag-r0-sql06-mysql;dbname=tag_r0_test;charset=utf8mb4',
            'username' => 'root',
            'password' => '',
            'emulatePrepare' => false,
        ],
    ],
]);

// O Controller real depende de Sentry e da sessão web; aqui basta o CController.
class Controller extends CController
{
}
require __DIR__ . '/../../app/modules/school/controllers/SchoolController.php';

$db = Yii::app()->db;
$db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();
$db->createCommand('DELETE FROM edcenso_regional_education_organ')->execute();
$db->createCommand('DELETE FROM edcenso_city')->execute();

// UF 1 tem duas cidades, UF 28 uma, UF 2 uma. A UF 99 não existe.
foreach ([[1, 'Cidade A', 1], [2, 'Cidade B', 1], [3, 'Cidade Sergipe', 28], [4, 'Cidade Outra', 2]] as $row) {
    $db->createCommand()->insert('edcenso_city', ['id' => $row[0], 'name' => $row[1], 'edcenso_uf_fk' => $row[2]]);
}
foreach ([
    [1, 1, '101', 'Órgão A & B'],
    [2, 2, '102', 'Órgão <C>'],
    [3, 3, '281', 'Órgão Sergipe'],
    [4, 4, '201', 'Órgão Outra UF'],
] as $row) {
    $db->createCommand()->insert('edcenso_regional_education_organ', [
        'id' => $row[0], 'edcenso_city_fk' => $row[1], 'code' => $row[2], 'name' => $row[3],
    ]);
}

/** Chama a action real com o POST informado e devolve exatamente o que ela imprime. */
function r0sql06_call(array $post): string
{
    $_POST = $post;
    $controller = new SchoolController('school');
    ob_start();
    try {
        $controller->actionUpdateUfDependencies();
        return (string) ob_get_clean();
    } catch (Throwable $e) {
        ob_end_clean();
        throw $e;
    }
}

function r0sql06_uf($value): array
{
    return ['SchoolIdentification' => ['edcenso_uf_fk' => $value]];
}

function r0sql06_assert(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}
