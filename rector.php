<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    // Define os caminhos onde o Rector irá atuar
    $rectorConfig->paths([
        __DIR__.'/app',
        __DIR__.'/tests',
        __DIR__.'/themes',
    ]);

    $rectorConfig->skip([
        __DIR__.'/vendor',      // Diretório de pacotes do Composer
        __DIR__.'/app/vendor',      // Diretório de pacotes do Composer
        __DIR__.'/runtime',     // Diretório de cache ou arquivos temporários
    ]);

    // Converte as anotações para atributos
    // Aplica a regra de conversão de anotações para atributos
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_83,
        SetList::PHP_74,
        SetList::PHP_80,
        SetList::PHP_81,
        SetList::PHP_82,
        SetList::PHP_83,
        SetList::CODE_QUALITY,
        SetList::NAMING,
        SetList::DEAD_CODE,
        SetList::TYPE_DECLARATION,
    ]);
};
