<?php

/*
 * R0-SQL-06: entradas maliciosas, vazias e atípicas em actionUpdateUfDependencies.
 * Nenhuma delas pode ampliar o resultado nem gerar erro de SQL.
 */
require __DIR__ . '/R0Sql06Bootstrap.php';

$uf1 = r0sql06_call(r0sql06_uf('1'));
$todasAsUfs = ['101', '102', '281', '201']; // códigos de órgão de todas as UFs do cenário

// Cada payload tem de dar exatamente o resultado da UF 1: a parte maliciosa é descartada pelo cast.
foreach (['1 OR 1=1', '1) OR (1=1', '1 UNION SELECT 1', "1'; DROP TABLE edcenso_city; --", '1 AND SLEEP(5)'] as $payload) {
    $start = microtime(true);
    $out = r0sql06_call(r0sql06_uf($payload));
    r0sql06_assert($out === $uf1, 'Payload alterou o resultado: ' . $payload);
    r0sql06_assert(microtime(true) - $start < 3, 'Payload executou SQL (SLEEP): ' . $payload);
    r0sql06_assert(strpos($out, 'value="281"') === false, 'Vazou órgão de outra UF: ' . $payload);
}

// A tabela continua existindo e com os 4 registros.
r0sql06_assert((int) Yii::app()->db->createCommand('SELECT COUNT(*) FROM edcenso_city')->queryScalar() === 4, 'Tabela alterada');

// Vazio, ausente e não numérico: JSON válido, sem órgãos e sem erro (antes do card, vazio dava 500).
$semOrgaos = [
    'City' => '<option value="">Selecione a cidade</option>',
    'Regional' => '<option value="">Selecione o órgão</option>',
];
foreach ([r0sql06_uf(''), ['SchoolIdentification' => []], r0sql06_uf('abc')] as $post) {
    $decoded = json_decode(r0sql06_call($post), true, 512, JSON_THROW_ON_ERROR);
    r0sql06_assert($decoded === $semOrgaos, 'Vazio ou inválido deve devolver só as opções padrão: ' . json_encode($post));
}

// Array no campo: não pode gerar exceção. O cast de array não vazio dá 1 (registrado no Handoff).
r0sql06_assert(r0sql06_call(r0sql06_uf(['1'])) === $uf1, 'Array deve se comportar como cast inteiro');

// A UF 28 devolve só o órgão de Sergipe.
$uf28 = json_decode(r0sql06_call(r0sql06_uf('28')), true, 512, JSON_THROW_ON_ERROR);
r0sql06_assert(strpos($uf28['Regional'], 'Órgão Sergipe') !== false && strpos($uf28['Regional'], 'Órgão A') === false, 'UF 28 devolveu órgãos errados');

echo "R0-SQL-06: segurança aprovada (payloads, vazio, array e UF 28)\n";
