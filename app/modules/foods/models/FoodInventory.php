<?php

/**
 * This is the model class for table "food_inventory".
 *
 * The followings are the available columns in table 'food_inventory':
 * @property integer $id
 * @property string $school_fk
 * @property integer $food_fk
 * @property double $amount
 * @property string $measurementUnit
 * @property string $expiration_date
 */
class FoodInventory extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'food_inventory';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('school_fk, food_fk, amount, expiration_date', 'required'),
            array('food_fk', 'numerical', 'integerOnly'=>true),
            array('amount', 'numerical'),
            array('school_fk', 'length', 'max'=>8),
            array('measurementUnit', 'length', 'max'=>7),
            array('expiration_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, school_fk, food_fk, amount, measurementUnit, expiration_date', 'safe', 'on'=>'search'),
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
            'foodRelation' => array(self::BELONGS_TO, 'Food', 'food_fk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'school_fk' => 'School Fk',
            'food_fk' => 'Food Fk',
            'amount' => 'Quantidade',
            'measurementUnit' => 'Measurement Unit',
            'expiration_date' => 'Validade',
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
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('school_fk',$this->school_fk,true);
        $criteria->compare('food_fk',$this->food_fk);
        $criteria->compare('amount',$this->amount);
        $criteria->compare('measurementUnit',$this->measurementUnit,true);
        $criteria->compare('expiration_date',$this->expiration_date,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FoodInventory the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
