<?php

/**
 * This is the model class for table "inventory_supplier".
 *
 * The followings are the available columns in table 'inventory_supplier':
 * @property integer $id
 * @property string $name
 * @property string $document
 * @property string $phone
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 */
class InventorySupplier extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'inventory_supplier';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'length', 'max' => 255],
            ['document, phone', 'length', 'max' => 20],
            ['email', 'length', 'max' => 150],
            ['email', 'email'],
            ['document, phone, email', 'safe'],
            ['id, name, document, phone, email, created_at, updated_at', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'quotationItems' => [self::HAS_MANY, 'InventoryQuotationItem', 'supplier_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nome do Fornecedor',
            'document' => 'CNPJ/CPF',
            'phone' => 'Telefone',
            'email' => 'E-mail',
        ];
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return InventorySupplier the static model class
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
        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('document', $this->document, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('email', $this->email, true);
        $criteria->order = 'name ASC';

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => $pagination ? ['pageSize' => 20] : false,
        ]);
    }
}
