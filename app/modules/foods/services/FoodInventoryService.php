<?php

class FoodInventoryService
{
    public function getFoodsInventoryList()
    {
        $sql = "SELECT fi.id, s.name AS school_name, f.description AS food_description, fi.amount, 
                fi.measurementUnit, fi.expiration_date, fi.status, fi.previous_amount 
                FROM food_inventory fi
                JOIN school s ON fi.school_fk = s.id
                JOIN food f ON fi.food_fk = f.id
                WHERE fi.status = 'Active'";

        return Yii::app()->db->createCommand($sql)->queryAll();
    }
}
