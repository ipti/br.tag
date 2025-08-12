<?php

/**
 * This is the model class for table "food_inventory_received".
 *
 * The followings are the available columns in table 'food_inventory_received':
 * @property int $id
 * @property float $amount
 * @property string $foodSource
 * @property string $date
 * @property int $food_inventory_fk
 */
class FoodInventoryReceived extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'food_inventory_received';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['amount, food_inventory_fk', 'required'],
            ['food_inventory_fk', 'numerical', 'integerOnly' => true],
            ['amount', 'numerical'],
            ['foodSource', 'length', 'max' => 20],
            ['date', 'safe'],
            // The following rule is used by search().
            ['id, amount, foodSource, date, food_inventory_fk', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'foodInventoryFk' => [self::BELONGS_TO, 'FoodInventory', 'food_inventory_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'amount' => 'Amount',
            'foodSource' => 'Food Source',
            'date' => 'Date',
            'food_inventory_fk' => 'Food Inventory Fk',
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions
     */
    public function search()
    {
        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('amount', $this->amount);
        $criteria->compare('foodSource', $this->foodSource, true);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('food_inventory_fk', $this->food_inventory_fk);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name
     * @return FoodInventoryReceived the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
