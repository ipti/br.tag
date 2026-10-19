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
 * e re-execute: composer run routes:generate -- sagres
 */

class SagresRoutes
{
    // DefaultController
    public const DEFAULT_CREATEORUPDATE = 'sagres/default/createOrUpdate';
    public const DEFAULT_DOWNLOAD = 'sagres/default/download';
    public const DEFAULT_EXPORT = 'sagres/default/export';
    public const DEFAULT_INCONSISTENCYSAGRES = 'sagres/default/inconsistencySagres';
    public const DEFAULT_INDEX = 'sagres/default/index';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
