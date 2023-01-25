<?php
/**
 *
 * @property string $inep_id
 *
 * The followings are the available model relations:
 * @property Inventory[] $inventories
 * @property Item[] $items
 * @property Menu[] $menus
 * @property Array itemsAmount
*/
class School extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'school_identification';
    }

    public function relations()
    {
        return [
            'inventories' => [self::HAS_MANY, 'Inventory', 'school_fk', 'with' => 'item', 'order' => 'item.name asc'],
            'items' => [self::MANY_MANY, 'Item', 'lunch_inventory(school_fk, item_fk)', 'order' => 'name asc'],
            'menus' => [self::HAS_MANY, 'Menu', 'school_fk', 'order' => 'date desc'],
        ];
    }

    /**
     *
     * @return Array Array with the itens amounts.
     */
    public function itemsAmount()
    {
        $amount = [];
        foreach ($this->inventories as $inventory) {
            $index = $inventory->item->id;
            $exist = isset($amount[$inventory->item->id]);

            if ($exist) {
                $amount[$index]['amount'] += floatval($inventory->amount);
            } else {
                $amount[$index] = [];
                $amount[$index]['id'] = $inventory->item->id;
                $amount[$index]['name'] = $inventory->item->name;
                $amount[$index]['description'] = $inventory->item->description;
                $amount[$index]['measure'] = $inventory->item->measure;
                $amount[$index]['unity'] = $inventory->item->unity->acronym;
                $amount[$index]['amount'] = floatval($inventory->amount);
            }
        }

        return $amount;
    }

    /**
     * @param String $initialDate Format(Y-m-d) default Null
     * @param String $finalDate Format(Y-m-d) default Null
     *
     * @return CDbCommand
     */
    public function transactions($initialDate = null, $finalDate = null)
    {
        $whereSchool = "school = $this->inep_id";
        $whereInitial = $initialDate != null ? " AND date >= $initialDate" : ' ';
        $whereFinal = $finalDate != null ? " AND date <= $finalDate" : ' ';
        $sql = "select school, inventory, date, motivation, item, li.name, li.measure, lu.acronym, amount
                from (
                    select lr.id, lr.date, null motivation, lr.inventory_fk inventory, li.school_fk school, li.item_fk item, li.amount from lunch_received lr
                        join lunch_inventory li on (lr.inventory_fk = li.id)
                    union(
                    select ls.id, ls.date, ls.motivation, ls.inventory_fk, li.school_fk, li.item_fk, li.amount from lunch_spent ls
                        join lunch_inventory li on (ls.inventory_fk = li.id)
                    )
                ) a
                    join lunch_item li on (a.item = li.id)
                    join lunch_unity lu on (li.unity_fk = lu.id)
              where $whereSchool $whereInitial $whereFinal
              order by date DESC";

        $result = yii::app()->db->createCommand($sql)->queryAll();

        return $result;
    }
}
