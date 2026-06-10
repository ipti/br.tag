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
 * e re-execute: composer run routes:generate -- professional
 */

class ProfessionalRoutes
{
    // DefaultController
    public const DEFAULT_CREATE = 'professional/default/create';
    public const DEFAULT_DELETE = 'professional/default/delete';
    public const DEFAULT_DELETEALLOCATION = 'professional/default/deleteAllocation';
    public const DEFAULT_DELETEATTENDANCE = 'professional/default/deleteAttendance';
    public const DEFAULT_INDEX = 'professional/default/index';
    public const DEFAULT_SAVEALLOCATION = 'professional/default/saveAllocation';
    public const DEFAULT_UPDATE = 'professional/default/update';
    public const DEFAULT_VIEWALLOCATION = 'professional/default/viewAllocation';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
