<?php

/*
 * ARQUIVO GERADO AUTOMATICAMENTE
 * ================================
 * Gerado por: scripts/generate-routes.php
 * Comando:    composer run routes:generate
 *
 * NÃO EDITE ESTE ARQUIVO MANUALMENTE.
 * Qualquer alteração será sobrescrita na próxima geração.
 *
 * Para adicionar ou renomear rotas, altere os controllers correspondentes
 * e re-execute: composer run routes:generate -- abilities
 */

class AbilitiesRoutes
{
    // CourseclassabilitiesController
    public const COURSECLASSABILITIES_CREATE = 'abilities/courseclassabilities/create';
    public const COURSECLASSABILITIES_DELETE = 'abilities/courseclassabilities/delete';
    public const COURSECLASSABILITIES_INDEX = 'abilities/courseclassabilities/index';
    public const COURSECLASSABILITIES_UPDATE = 'abilities/courseclassabilities/update';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
