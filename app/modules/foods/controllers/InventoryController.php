<?php

class InventoryController extends Controller {
    public function actionGetInventoryFoods() {
        $useCase = new GetInventoryFoods();
        $inventoryData = $useCase->execute();
        
        header('Content-Type: application/json');
        echo CJSON::encode($inventoryData);
        Yii::app()->end();
    }
}
