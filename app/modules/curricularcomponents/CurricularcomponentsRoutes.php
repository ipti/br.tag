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
 * e re-execute: composer run routes:generate -- curricularcomponents
 */

class CurricularcomponentsRoutes
{
    // DefaultController
    public const DEFAULT_ADMIN = 'curricularcomponents/default/admin';
    public const DEFAULT_CREATE = 'curricularcomponents/default/create';
    public const DEFAULT_DELETE = 'curricularcomponents/default/delete';
    public const DEFAULT_INDEX = 'curricularcomponents/default/index';
    public const DEFAULT_UPDATE = 'curricularcomponents/default/update';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
