<?php

class ActionGetInventoryFoods extends Controller
{


public function ActionGetInventoryFoods()
{
    // Supondo que GetInventoryFoods já esteja importando e usando FoodInventoryService
    $inventoryFoodsService = new GetInventoryFoods();
    $foodsInventoryList = $inventoryFoodsService->exec();

    echo CJSON::encode($foodsInventoryList);
    Yii::app()->end();
    }
}
