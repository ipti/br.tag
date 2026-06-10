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
 * e re-execute: composer run routes:generate -- curricularmatrix
 */

class CurricularmatrixRoutes
{
    // CurricularmatrixController
    public const CURRICULARMATRIX_ADDMATRIX = 'curricularmatrix/curricularmatrix/addMatrix';
    public const CURRICULARMATRIX_DELETE = 'curricularmatrix/curricularmatrix/delete';
    public const CURRICULARMATRIX_INDEX = 'curricularmatrix/curricularmatrix/index';
    public const CURRICULARMATRIX_MATRIXREUSE = 'curricularmatrix/curricularmatrix/matrixReuse';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
