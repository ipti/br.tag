<?php

/**
 * This is the model class for table "inventory_request".
 *
 * @property integer $id
 * @property string $school_inep_fk
 * @property integer $item_id
 * @property string $quantity
 * @property integer $user_id
 * @property integer $status
 * @property string $justification
 * @property string $observation
 * @property string $requested_at
 * @property string $updated_at
 *
 * @property InventoryItem $item
 * @property SchoolIdentification $school
 * @property Users $user
 */
class InventoryRequest extends CActiveRecord
{
    public const STATUS_PENDING = 0;
    public const STATUS_APPROVED = 1;
    public const STATUS_REJECTED = 2;
    public const STATUS_DELIVERED = 3;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'inventory_request';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['school_inep_fk, item_id, quantity, user_id', 'required'],
            ['item_id, user_id, status', 'numerical', 'integerOnly' => true],
            ['quantity', 'numerical'],
            ['school_inep_fk', 'length', 'max' => 8],
            ['justification, observation', 'safe'],
            ['id, school_inep_fk, item_id, quantity, user_id, status, requested_at, updated_at', 'safe', 'on' => 'search'],
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
            'school_inep_fk' => 'Escola',
            'item_id' => 'Item',
            'quantity' => 'Quantidade Solicitada',
            'user_id' => 'Solicitante',
            'status' => 'Status',
            'justification' => 'Justificativa',
            'observation' => 'Observação (Secretaria)',
            'requested_at' => 'Data da Solicitação',
        ];
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return InventoryRequest the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Returns status list
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_PENDING => 'Pendente',
            self::STATUS_APPROVED => 'Aprovado',
            self::STATUS_REJECTED => 'Rejeitado',
            self::STATUS_DELIVERED => 'Entregue',
        ];
    }

    /**
     * Returns the text status
     */
    public function getStatusText()
    {
        $list = self::getStatusList();
        return isset($list[$this->status]) ? $list[$this->status] : 'Desconhecido';
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('school_inep_fk', $this->school_inep_fk, true);
        $criteria->compare('item_id', $this->item_id);
        $criteria->compare('quantity', $this->quantity);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('status', $this->status);
        $criteria->compare('requested_at', $this->requested_at, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'requested_at DESC',
            ),
        ));
    }
}
