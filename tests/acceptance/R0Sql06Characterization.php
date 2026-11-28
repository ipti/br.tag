<?php

/*
 * R0-SQL-06: golden master de actionUpdateUfDependencies para UFs válidas.
 * A saída foi gravada com o código ANTERIOR à correção (--record) e não pode mudar.
 *
 *   php tests/acceptance/R0Sql06Characterization.php           compara com o golden master
 *   php tests/acceptance/R0Sql06Characterization.php --record  regrava (só com aprovação humana)
 */
require __DIR__ . '/R0Sql06Bootstrap.php';

$cases = ['uf1' => '1', 'uf2' => '2', 'uf28' => '28', 'uf99SemCidades' => '99'];
$file = __DIR__ . '/R0Sql06Characterization.json';

$actual = [];
foreach ($cases as $name => $uf) {
    $actual[$name] = r0sql06_call(r0sql06_uf($uf));
}

if (in_array('--record', $argv ?? [], true)) {
    file_put_contents($file, json_encode($actual, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n");
    echo "R0-SQL-06: golden master gravado em " . basename($file) . "\n";
    exit(0);
}

$golden = json_decode((string) file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
foreach ($cases as $name => $uf) {
    r0sql06_assert(($golden[$name] ?? null) === $actual[$name], 'Saída difere do golden master: ' . $name);
}
echo "R0-SQL-06: caracterização aprovada (" . count($cases) . " casos)\n";
