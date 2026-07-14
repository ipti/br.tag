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
 * e re-execute: composer run routes:generate -- farmer
 */

class FarmerRoutes
{
    // DefaultController
    public const DEFAULT_ACTIVATEFARMERS = 'farmer/default/activateFarmers';
    public const DEFAULT_ADMIN = 'farmer/default/admin';
    public const DEFAULT_CREATE = 'farmer/default/create';
    public const DEFAULT_CREATEFARMERREGISTER = 'farmer/default/createFarmerRegister';
    public const DEFAULT_DELETE = 'farmer/default/delete';
    public const DEFAULT_GETFARMERDELIVERIES = 'farmer/default/getFarmerDeliveries';
    public const DEFAULT_GETFARMERFOODS = 'farmer/default/getFarmerFoods';
    public const DEFAULT_GETFARMERREGISTER = 'farmer/default/getFarmerRegister';
    public const DEFAULT_GETFOODALIAS = 'farmer/default/getFoodAlias';
    public const DEFAULT_GETFOODNOTICE = 'farmer/default/getFoodNotice';
    public const DEFAULT_GETFOODNOTICEITEMS = 'farmer/default/getFoodNoticeItems';
    public const DEFAULT_INDEX = 'farmer/default/index';
    public const DEFAULT_TOGGLEFARMERSTATUS = 'farmer/default/toggleFarmerStatus';
    public const DEFAULT_UPDATE = 'farmer/default/update';
    public const DEFAULT_UPDATEFARMERREGISTER = 'farmer/default/updateFarmerRegister';
    public const DEFAULT_VIEW = 'farmer/default/view';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
