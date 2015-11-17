<?php
/**
 * The followings are the available model relations:
 * @property Inventory[] $inventories
 * @property Item[] $items
*/
class School extends CActiveRecord{
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'school_identification';
    }

    public function relations() {
        return [
            'inventories' => array(self::HAS_MANY, 'Inventory', 'school_fk', 'with'=>'item', 'order'=>'item.name asc'),
            'items' => array(self::MANY_MANY, 'Item', 'lunch_inventory(school_fk, item_fk)', 'order'=>'name asc'),
        ];
    }

    public function itemsAmount(){
        $amount = [];
        foreach($this->inventories as $inventory){
            $index = $inventory->item->name;
            $exist = isset($amount[$inventory->item->name]);

            if($exist){
                $amount[$index]["amount"] += floatval($inventory->amount);
            }else{
                $amount[$index] = [];
                $amount[$index]['id'] = $inventory->item->id;
                $amount[$index]['name'] = $inventory->item->name;
                $amount[$index]['description'] = $inventory->item->description;
                $amount[$index]['measure'] = $inventory->item->measure;
                $amount[$index]['unity'] = $inventory->item->unity->acronym;
                $amount[$index]["amount"] = floatval($inventory->amount);
            }
        }
        return $amount;
    }
}