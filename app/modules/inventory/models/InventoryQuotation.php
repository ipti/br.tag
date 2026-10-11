<?php

/**
 * This is the model class for table "inventory_quotation".
 *
 * The followings are the available columns in table 'inventory_quotation':
 * @property integer $id
 * @property string $title
 * @property string $date
 * @property integer $status
 * @property string $observation
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 */
class InventoryQuotation extends CActiveRecord
{
    public const STATUS_OPEN = 0;
    public const STATUS_CLOSED = 1;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'inventory_quotation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['title, date', 'required'],
            ['title', 'length', 'max' => 255],
            ['status, user_id', 'numerical', 'integerOnly' => true],
            ['observation', 'safe'],
            ['id, title, date, status, observation, user_id, created_at, updated_at', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'items' => [self::HAS_MANY, 'InventoryQuotationItem', 'quotation_id'],
            'userFk' => [self::BELONGS_TO, 'Users', 'user_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Título da Cotação',
            'date' => 'Data da Cotação',
            'status' => 'Situação',
            'observation' => 'Observações',
        ];
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_OPEN => 'Aberta',
            self::STATUS_CLOSED => 'Encerrada',
        ];
    }

    public function getStatusText()
    {
        $list = self::getStatusList();
        return isset($list[$this->status]) ? $list[$this->status] : 'Desconhecido';
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return InventoryQuotation the static model class
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('status', $this->status);
        $criteria->order = 'date DESC, id DESC';

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => $pagination ? ['pageSize' => 20] : false,
        ]);
    }
}
