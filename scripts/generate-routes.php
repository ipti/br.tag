#!/usr/bin/env php
<?php

/**
 * Gerador de classes de rotas do TAG.
 *
 * Varre os controllers de cada módulo e gera uma classe *Routes.php co-localizada
 * dentro do próprio módulo (app/modules/<module>/<Module>Routes.php).
 * Registra o import automaticamente no setImport() do *Module.php correspondente.
 *
 * Controllers raiz geram AppRoutes.php em app/components/utils/.
 *
 * Uso:
 *   php scripts/generate-routes.php          # gera todos os módulos
 *   php scripts/generate-routes.php student  # gera apenas StudentRoutes
 *   php scripts/generate-routes.php root     # gera apenas AppRoutes (controllers raiz)
 *
 * Via Composer:
 *   composer run routes:generate
 *   composer run routes:generate -- student
 */

$filter     = $argv[1] ?? null;
$modulesDir = __DIR__ . '/../app/modules';
$generated  = 0;

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

function extractActions(string $file): array
{
    $src = file_get_contents($file);
    preg_match_all('/public function action([A-Z][a-zA-Z0-9]*)/', $src, $m);
    return $m[1];
}

function generatedHeader(string $moduleId): string
{
    $rerun = $moduleId === 'root'
        ? 'composer run routes:generate -- root'
        : "composer run routes:generate -- {$moduleId}";

    return implode("\n", [
        '<?php',
        '',
        '/*',
        ' * ARQUIVO GERADO AUTOMATICAMENTE',
        ' * ================================',
        ' * Gerado por: scripts/generate-routes.php',
        ' * Comando:    composer run routes:generate',
        ' *',
        ' * NÃO EDITE ESTE ARQUIVO MANUALMENTE.',
        ' * Qualquer alteração será sobrescrita na próxima geração.',
        ' *',
        ' * Para adicionar ou renomear rotas, altere os controllers correspondentes',
        " * e re-execute: {$rerun}",
        ' */',
    ]);
}

function buildRouteClass(string $className, array $consts, string $moduleId): string
{
    ksort($consts);

    $byController = [];
    foreach ($consts as [$ctrlLabel, $const, $route]) {
        $byController[$ctrlLabel][] = [$const, $route];
    }

    $lines   = [];
    $lines[] = generatedHeader($moduleId);
    $lines[] = '';
    $lines[] = "class {$className}";
    $lines[] = '{';

    $first = true;
    foreach ($byController as $ctrlLabel => $items) {
        if ($first) {
            $first = false;
        } else {
            $lines[] = '';
        }
        $lines[] = "    // {$ctrlLabel}";
        foreach ($items as [$const, $route]) {
            $lines[] = "    public const {$const} = '{$route}';";
        }
    }

    $lines[] = '';
    $lines[] = '    public static function url(string $route, array $params = []): string';
    $lines[] = '    {';
    $lines[] = '        return Yii::app()->createUrl($route, $params);';
    $lines[] = '    }';
    $lines[] = '}';
    $lines[] = '';

    return implode("\n", $lines);
}

/**
 * Adiciona $importAlias ao setImport() existente no *Module.php,
 * se ainda não estiver presente. Caso não exista setImport(), cria um.
 */
function patchModuleImport(string $moduleFile, string $importAlias): void
{
    $src = file_get_contents($moduleFile);

    if (str_contains($src, $importAlias)) {
        echo "    (já importado em " . basename($moduleFile) . ")\n";
        return;
    }

    if (preg_match('/^(\s*)\$this->setImport\(\[/m', $src, $m)) {
        $indent     = $m[1];
        $itemIndent = $indent . '    ';
        $src = preg_replace(
            '/^(\s*)\$this->setImport\(\[/m',
            "{$indent}\$this->setImport([\n{$itemIndent}'{$importAlias}',",
            $src,
            1
        );
    } else {
        // Módulo sem setImport() — adiciona após a abertura do init()
        $src = preg_replace(
            '/(public function init\(\)[^{]*\{)/',
            "$1\n        \$this->setImport(['{$importAlias}']);\n",
            $src,
            1
        );
    }

    file_put_contents($moduleFile, $src);
    echo "    Atualizado: " . basename($moduleFile) . "\n";
}

// ---------------------------------------------------------------------------
// Módulos
// ---------------------------------------------------------------------------

foreach (glob($modulesDir . '/*', GLOB_ONLYDIR) as $moduleDir) {
    $moduleId = basename($moduleDir);

    if ($filter !== null && ($filter === 'root' || $filter !== $moduleId)) {
        continue;
    }

    $ctrlDir = $moduleDir . '/controllers';
    if (!is_dir($ctrlDir)) {
        continue;
    }

    $consts = [];
    foreach (glob($ctrlDir . '/*Controller.php') as $ctrlFile) {
        $ctrlBase     = basename($ctrlFile, '.php');
        $controllerId = lcfirst(str_replace('Controller', '', $ctrlBase));

        foreach (extractActions($ctrlFile) as $name) {
            $actionId = lcfirst($name);
            $route    = "{$moduleId}/{$controllerId}/{$actionId}";
            $const    = strtoupper("{$controllerId}_{$actionId}");

            if (isset($consts[$const])) {
                $existingRoute = $consts[$const][2];
                if (strpos($consts[$const][0], '_') === false) {
                    $oldCtrl   = $consts[$const][0];
                    $oldCtrlId = lcfirst(str_replace('Controller', '', $oldCtrl));
                    $oldConst  = strtoupper("{$oldCtrlId}_{$actionId}");
                    $consts[$oldConst] = [$oldCtrl, $oldConst, $existingRoute];
                    unset($consts[$const]);
                }
                $const = strtoupper("{$controllerId}_{$actionId}");
            }

            $consts[$const] = [$ctrlBase, $const, $route];
        }
    }

    if (!$consts) {
        continue;
    }

    $className  = ucfirst($moduleId) . 'Routes';
    $outputFile = $moduleDir . '/' . $className . '.php';
    $alias      = "application.modules.{$moduleId}.{$className}";

    file_put_contents($outputFile, buildRouteClass($className, $consts, $moduleId));
    echo "  Gerado: {$moduleId}/{$className}.php\n";

    $moduleFile = $moduleDir . '/' . ucfirst($moduleId) . 'Module.php';
    if (file_exists($moduleFile)) {
        patchModuleImport($moduleFile, $alias);
    }

    $generated++;
}

// ---------------------------------------------------------------------------
// Controllers raiz → AppRoutes em app/components/utils/
// ---------------------------------------------------------------------------

if (!$filter || $filter === 'root') {
    $consts = [];
    foreach (glob(__DIR__ . '/../app/controllers/*Controller.php') as $ctrlFile) {
        $ctrlBase     = basename($ctrlFile, '.php');
        $controllerId = lcfirst(str_replace('Controller', '', $ctrlBase));

        foreach (extractActions($ctrlFile) as $name) {
            $actionId = lcfirst($name);
            $route    = "{$controllerId}/{$actionId}";
            $const    = strtoupper("{$controllerId}_{$actionId}");
            $consts[$const] = [$ctrlBase, $const, $route];
        }
    }

    if ($consts) {
        $outputFile = __DIR__ . '/../app/components/utils/AppRoutes.php';
        file_put_contents($outputFile, buildRouteClass('AppRoutes', $consts, 'root'));
        echo "  Gerado: app/components/utils/AppRoutes.php\n";
        $generated++;
    }
}

echo "Concluído. {$generated} classe(s) gerada(s).\n";
