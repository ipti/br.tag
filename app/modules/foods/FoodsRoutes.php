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
 * e re-execute: composer run routes:generate -- foods
 */

class FoodsRoutes
{
    // DefaultController
    public const DEFAULT_CREATE = 'foods/default/create';
    public const DEFAULT_INDEX = 'foods/default/index';

    // FoodinventoryController
    public const FOODINVENTORY_ADMIN = 'foods/foodinventory/admin';
    public const FOODINVENTORY_CREATE = 'foods/foodinventory/create';
    public const FOODINVENTORY_DELETE = 'foods/foodinventory/delete';
    public const FOODINVENTORY_DELETESTOCKSPENT = 'foods/foodinventory/deleteStockSpent';
    public const FOODINVENTORY_GETFOODALIAS = 'foods/foodinventory/getFoodAlias';
    public const FOODINVENTORY_GETFOODINVENTORY = 'foods/foodinventory/getFoodInventory';
    public const FOODINVENTORY_GETISNUTRITIONIST = 'foods/foodinventory/getIsNutritionist';
    public const FOODINVENTORY_GETSTOCKMOVEMENT = 'foods/foodinventory/getStockMovement';
    public const FOODINVENTORY_INDEX = 'foods/foodinventory/index';
    public const FOODINVENTORY_SAVESTOCK = 'foods/foodinventory/saveStock';
    public const FOODINVENTORY_SAVESTOCKRECEIVED = 'foods/foodinventory/saveStockReceived';
    public const FOODINVENTORY_SAVESTOCKSPENT = 'foods/foodinventory/saveStockSpent';
    public const FOODINVENTORY_UPDATE = 'foods/foodinventory/update';
    public const FOODINVENTORY_UPDATEFOODINVENTORYSTATUS = 'foods/foodinventory/updateFoodInventoryStatus';
    public const FOODINVENTORY_VIEW = 'foods/foodinventory/view';

    // FoodmenuController
    public const FOODMENU_ADMIN = 'foods/foodmenu/admin';
    public const FOODMENU_CREATE = 'foods/foodmenu/create';
    public const FOODMENU_DELETE = 'foods/foodmenu/delete';
    public const FOODMENU_GETFOOD = 'foods/foodmenu/getFood';
    public const FOODMENU_GETFOODMEASUREMENT = 'foods/foodmenu/getFoodMeasurement';
    public const FOODMENU_GETMEALSOFWEEK = 'foods/foodmenu/getMealsOfWeek';
    public const FOODMENU_GETMEALTYPE = 'foods/foodmenu/getMealType';
    public const FOODMENU_GETPUBLICTARGET = 'foods/foodmenu/getPublicTarget';
    public const FOODMENU_GETTACOFOODS = 'foods/foodmenu/getTacoFoods';
    public const FOODMENU_INDEX = 'foods/foodmenu/index';
    public const FOODMENU_UPDATE = 'foods/foodmenu/update';
    public const FOODMENU_VIEWLUNCH = 'foods/foodmenu/viewLunch';

    // FoodnoticeController
    public const FOODNOTICE_ACTIVATENOTICE = 'foods/foodnotice/activateNotice';
    public const FOODNOTICE_ADMIN = 'foods/foodnotice/admin';
    public const FOODNOTICE_CREATE = 'foods/foodnotice/create';
    public const FOODNOTICE_DELETE = 'foods/foodnotice/delete';
    public const FOODNOTICE_GETNOTICE = 'foods/foodnotice/getNotice';
    public const FOODNOTICE_GETNOTICEPDFURL = 'foods/foodnotice/getNoticePdfUrl';
    public const FOODNOTICE_GETSHOPPINGLIST = 'foods/foodnotice/getShoppingList';
    public const FOODNOTICE_GETTACOFOODS = 'foods/foodnotice/getTacoFoods';
    public const FOODNOTICE_INDEX = 'foods/foodnotice/index';
    public const FOODNOTICE_TOGGLENOTICESTATUS = 'foods/foodnotice/toggleNoticeStatus';
    public const FOODNOTICE_UPDATE = 'foods/foodnotice/update';
    public const FOODNOTICE_VIEW = 'foods/foodnotice/view';

    // FoodrequestController
    public const FOODREQUEST_ADMIN = 'foods/foodrequest/admin';
    public const FOODREQUEST_CREATE = 'foods/foodrequest/create';
    public const FOODREQUEST_DELETE = 'foods/foodrequest/delete';
    public const FOODREQUEST_GETFARMERREGISTER = 'foods/foodrequest/getFarmerRegister';
    public const FOODREQUEST_GETFOODALIAS = 'foods/foodrequest/getFoodAlias';
    public const FOODREQUEST_GETFOODNOTICE = 'foods/foodrequest/getFoodNotice';
    public const FOODREQUEST_GETFOODNOTICEITEMS = 'foods/foodrequest/getFoodNoticeItems';
    public const FOODREQUEST_GETFOODREQUEST = 'foods/foodrequest/getFoodRequest';
    public const FOODREQUEST_INDEX = 'foods/foodrequest/index';
    public const FOODREQUEST_UPDATE = 'foods/foodrequest/update';
    public const FOODREQUEST_UPDATEACCEPTEDFOODS = 'foods/foodrequest/updateAcceptedFoods';
    public const FOODREQUEST_UPDATERECEIVEDFOODS = 'foods/foodrequest/updateReceivedFoods';
    public const FOODREQUEST_VIEW = 'foods/foodrequest/view';

    // ReportsController
    public const REPORTS_FOODMENUREPORT = 'foods/reports/foodMenuReport';
    public const REPORTS_SHOPPINGLISTREPORT = 'foods/reports/shoppingListReport';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
