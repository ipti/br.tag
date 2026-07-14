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
 * e re-execute: composer run routes:generate -- inventory
 */

class InventoryRoutes
{
    // ItemController
    public const ITEM_CREATE = 'inventory/item/create';
    public const ITEM_DELETE = 'inventory/item/delete';
    public const ITEM_INDEX = 'inventory/item/index';
    public const ITEM_UPDATE = 'inventory/item/update';
    public const ITEM_VIEW = 'inventory/item/view';

    // MovementController
    public const MOVEMENT_CREATEENTRY = 'inventory/movement/createEntry';
    public const MOVEMENT_CREATEEXIT = 'inventory/movement/createExit';
    public const MOVEMENT_HISTORY = 'inventory/movement/history';
    public const MOVEMENT_INDEX = 'inventory/movement/index';
    public const MOVEMENT_TRANSFER = 'inventory/movement/transfer';

    // RequestController
    public const REQUEST_ADMIN = 'inventory/request/admin';
    public const REQUEST_APPROVE = 'inventory/request/approve';
    public const REQUEST_CREATE = 'inventory/request/create';
    public const REQUEST_DELETE = 'inventory/request/delete';
    public const REQUEST_INDEX = 'inventory/request/index';
    public const REQUEST_REJECT = 'inventory/request/reject';
    public const REQUEST_UPDATE = 'inventory/request/update';
    public const REQUEST_VIEW = 'inventory/request/view';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
