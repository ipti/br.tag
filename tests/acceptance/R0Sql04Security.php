<?php

/* Exercises the real action against the same in-memory fixture as the golden master. */
require __DIR__ . '/R0Sql04Characterization.php';

$invalidCases = [
    'OR injection' => ['Stage' => '1 OR 1=1'],
    'parentheses injection' => ['Stage' => '1) OR (1=1'],
    'text' => ['Stage' => 'abc'],
    'array' => ['Stage' => ['1']],
    'empty' => ['Stage' => ''],
    'missing' => [],
];
foreach ($invalidCases as $label => $post) {
    $_POST = $post;
    EdcensoStageVsModality::$lastQuery = null;
    ob_start();
    try {
        (new EnrollmentController())->actionGetModalities();
        throw new RuntimeException($label . ': não rejeitou a entrada');
    } catch (CHttpException $e) {
        if ($e->statusCode !== 400) throw new RuntimeException($label . ': status diferente de 400');
    } finally {
        $output = ob_get_clean();
    }
    if ($output !== '' || EdcensoStageVsModality::$lastQuery !== null) {
        throw new RuntimeException($label . ': produziu saída ou consultou o modelo');
    }
}

captureStage('1');
$query = EdcensoStageVsModality::$lastQuery;
if (!$query instanceof CDbCriteria || !preg_match('/^stage\s*=\s*(:[A-Za-z0-9_]+)$/', $query->condition, $matches)
    || ($query->params[$matches[1]] ?? null) !== 1) {
    throw new RuntimeException('Etapa válida não foi ligada ao parâmetro inteiro');
}
captureStage('0');
$query = EdcensoStageVsModality::$lastQuery;
if (!$query instanceof CDbCriteria || $query->condition !== '' || $query->params !== []) {
    throw new RuntimeException('Etapa zero deve consultar todas sem filtro');
}
echo "R0-SQL-04: validação e parametrização aprovadas\n";
