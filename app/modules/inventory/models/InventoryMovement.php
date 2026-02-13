<?php

/**
 * This is the model class for table "inventory_movement".
 *
 * The followings are the available columns in table 'inventory_movement':
 * @property integer $id
 * @property integer $item_id
 * @property string $school_inep_fk
 * @property integer $user_id
 * @property integer $type
 * @property double $quantity
 * @property string $destination
 * @property string $date
 * @property string $created_at
 */
class InventoryMovement extends CActiveRecord
{
    const TYPE_ENTRY = 1;
    const TYPE_EXIT = 2;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'inventory_movement';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['item_id, user_id, type, quantity, date', 'required'],
            ['item_id, user_id, type', 'numerical', 'integerOnly' => true],
            ['quantity', 'numerical'],
            ['school_inep_fk', 'length', 'max' => 8],
            ['destination', 'length', 'max' => 255],
            ['id, item_id, school_inep_fk, user_id, type, quantity, destination, date, created_at', 'safe', 'on' => 'search'],
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
            'user' => [self::BELONGS_TO, 'Users', 'user_id'],
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
            'user_id' => 'Usuário',
            'type' => 'Tipo (1-Entrada, 2-Saída)',
            'quantity' => 'Quantidade',
            'destination' => 'Origem/Destino',
            'date' => 'Data',
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
     * @return InventoryMovement the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
