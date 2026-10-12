<?php

/**
 * This is the model class for table "inventory_quotation_item".
 *
 * The followings are the available columns in table 'inventory_quotation_item':
 * @property integer $id
 * @property integer $quotation_id
 * @property integer $item_id
 * @property integer $supplier_id
 * @property string $quoted_value
 * @property string $created_at
 */
class InventoryQuotationItem extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'inventory_quotation_item';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['quotation_id, item_id, supplier_id, quoted_value', 'required'],
            ['quotation_id, item_id, supplier_id', 'numerical', 'integerOnly' => true],
            ['quoted_value', 'numerical', 'min' => 0],
            ['id, quotation_id, item_id, supplier_id, quoted_value, created_at', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'quotationFk' => [self::BELONGS_TO, 'InventoryQuotation', 'quotation_id'],
            'itemFk' => [self::BELONGS_TO, 'InventoryItem', 'item_id'],
            'supplierFk' => [self::BELONGS_TO, 'InventorySupplier', 'supplier_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quotation_id' => 'Cotação',
            'item_id' => 'Item',
            'supplier_id' => 'Fornecedor',
            'quoted_value' => 'Valor Cotado',
        ];
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return InventoryQuotationItem the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
