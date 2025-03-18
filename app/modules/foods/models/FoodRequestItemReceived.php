<?php

/**
 * This is the model class for table "food_request_item_received".
 *
 * The followings are the available columns in table 'food_request_item_received':
 * @property integer $id
 * @property integer $food_fk
 * @property integer $farmer_fk
 * @property integer $food_request_fk
 * @property double $amount
 * @property string $measurementUnit
 * @property string $date
 *
 * The followings are the available model relations:
 * @property FarmerRegister $farmerFk
 * @property Food $foodFk
 * @property FoodRequest $foodRequestFk
 */
class FoodRequestItemReceived extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'food_request_item_received';
    }
    public function behaviors()
    {
        return [
            'CTimestampBehavior' => [
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'updateAttribute' => 'updated_at',
                'setUpdateOnCreate' => true,
                'timestampExpression' => new CDbExpression('CONVERT_TZ(NOW(), "+00:00", "-03:00")'),
            ]
        ];
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('food_fk, farmer_fk, food_request_fk', 'numerical', 'integerOnly'=>true),
            array('amount', 'numerical'),
            array('measurementUnit', 'length', 'max'=>7),
            array('date', 'safe'),
            array('id, food_fk, farmer_fk, food_request_fk, amount, measurementUnit, date', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'farmerFk' => array(self::BELONGS_TO, 'FarmerRegister', 'farmer_fk'),
            'foodFk' => array(self::BELONGS_TO, 'Food', 'food_fk'),
            'foodRequestFk' => array(self::BELONGS_TO, 'FoodRequest', 'food_request_fk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'food_fk' => 'Food Fk',
            'farmer_fk' => 'Farmer Fk',
            'food_request_fk' => 'Food Request Fk',
            'amount' => 'Amount',
            'measurementUnit' => 'Measurement Unit',
            'date' => 'Date',
        );
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
     * based on the search/filter conditions.
     */
    public function search()
    {

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('food_fk',$this->food_fk);
        $criteria->compare('farmer_fk',$this->farmer_fk);
        $criteria->compare('food_request_fk',$this->food_request_fk);
        $criteria->compare('amount',$this->amount);
        $criteria->compare('measurementUnit',$this->measurementUnit,true);
        $criteria->compare('date',$this->date,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FoodRequestItemReceived the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
