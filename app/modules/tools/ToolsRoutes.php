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
 * e re-execute: composer run routes:generate -- tools
 */

class ToolsRoutes
{
    // DefaultController
    public const DEFAULT_INDEX = 'tools/default/index';
    public const DEFAULT_OPCACHE = 'tools/default/opcache';
    public const DEFAULT_RUN = 'tools/default/run';
    public const DEFAULT_VIEWLOGS = 'tools/default/viewLogs';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
