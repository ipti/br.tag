<?php

/**
 * This is the model class for table "inventory_stock".
 *
 * The followings are the available columns in table 'inventory_stock':
 * @property integer $id
 * @property integer $item_id
 * @property string $school_inep_fk
 * @property double $quantity
 * @property string $updated_at
 */
class InventoryStock extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'inventory_stock';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['item_id', 'required'],
            ['item_id', 'numerical', 'integerOnly' => true],
            ['quantity', 'numerical'],
            ['school_inep_fk', 'length', 'max' => 8],
            ['id, item_id, school_inep_fk, quantity, updated_at', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'item' => [self::BELONGS_TO, 'InventoryItem', 'item_id'],
            'school' => [self::BELONGS_TO, 'SchoolIdentification', 'school_inep_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_id' => 'Item',
            'school_inep_fk' => 'Escola',
            'quantity' => 'Quantidade Disponível',
        ];
    }

    protected function beforeValidate()
    {
        if (empty($this->school_inep_fk)) {
            $this->school_inep_fk = null;
        }
        return parent::beforeValidate();
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return InventoryStock the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
