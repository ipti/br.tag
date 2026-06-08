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
 * e re-execute: composer run routes:generate -- stages
 */

class StagesRoutes
{
    // DefaultController
    public const DEFAULT_ADMIN = 'stages/default/admin';
    public const DEFAULT_CREATE = 'stages/default/create';
    public const DEFAULT_DELETE = 'stages/default/delete';
    public const DEFAULT_INDEX = 'stages/default/index';
    public const DEFAULT_UPDATE = 'stages/default/update';
    public const DEFAULT_VIEW = 'stages/default/view';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
