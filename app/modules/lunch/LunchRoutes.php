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
 * e re-execute: composer run routes:generate -- lunch
 */

class LunchRoutes
{
    // DefaultController
    public const DEFAULT_ERROR = 'lunch/default/error';
    public const DEFAULT_INDEX = 'lunch/default/index';

    // LunchController
    public const LUNCH_ADDMEAL = 'lunch/lunch/addMeal';
    public const LUNCH_ADDPORTION = 'lunch/lunch/addPortion';
    public const LUNCH_CHANGEMEAL = 'lunch/lunch/changeMeal';
    public const LUNCH_CREATE = 'lunch/lunch/create';
    public const LUNCH_GETFOODALIAS = 'lunch/lunch/getFoodAlias';
    public const LUNCH_GETFOODMEASUREMENT = 'lunch/lunch/getFoodMeasurement';
    public const LUNCH_GETUNITYMEASURE = 'lunch/lunch/getUnityMeasure';
    public const LUNCH_INDEX = 'lunch/lunch/index';
    public const LUNCH_LUNCHDELETE = 'lunch/lunch/lunchDelete';
    public const LUNCH_REMOVEPORTION = 'lunch/lunch/removePortion';
    public const LUNCH_UPDATE = 'lunch/lunch/update';

    // StockController
    public const STOCK_ADDITEM = 'lunch/stock/addItem';
    public const STOCK_DELETEITEM = 'lunch/stock/deleteItem';
    public const STOCK_INDEX = 'lunch/stock/index';
    public const STOCK_REMOVEITEM = 'lunch/stock/removeItem';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
