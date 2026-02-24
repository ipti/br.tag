<?php

/**
 * This is the model class for table "inventory_item".
 *
 * The followings are the available columns in table 'inventory_item':
 * @property integer $id
 * @property string $name
 * @property string $unit
 * @property string $description
 * @property string $minimum_stock
 * @property string $created_at
 * @property string $updated_at
 */
class InventoryItem extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'inventory_item';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['name, unit', 'required'],
            ['name', 'length', 'max' => 255],
            ['unit', 'length', 'max' => 50],
            ['description', 'safe'],
            ['minimum_stock', 'numerical'],
            ['id, name, unit, description, minimum_stock, created_at, updated_at', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'stocks' => [self::HAS_MANY, 'InventoryStock', 'item_id'],
            'movements' => [self::HAS_MANY, 'InventoryMovement', 'item_id'],
            'requests' => [self::HAS_MANY, 'InventoryRequest', 'item_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nome do Item',
            'unit' => 'Unidade (ex: Kg, Unid)',
            'description' => 'Descrição',
            'minimum_stock' => 'Estoque Mínimo (Alerta)',
        ];
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return InventoryItem the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search($pagination = true)
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('unit', $this->unit, true);
        $criteria->compare('description', $this->description, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => $pagination ? ['pageSize' => 20] : false,
        ]);
    }

}
