<?php

/**
 * This is the model class for table "farmer_foods".
 *
 * The folowings are the available columns in table 'farmer_foods':
 * @property int $id
 * @property int $food_fk
 * @property int $farmer_fk
 * @property string $measurementUnit
 * @property float $amount
 * @property int $foodNotice_fk
 *
 * The followings are the available model relations:
 * @property FarmerRegister $farmerFk
 * @property Food $foodFk
 * @property FoodNotice $foodNoticeFk
 */
class FarmerFoods extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'farmer_foods';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['amount', 'required'],
            ['food_fk, farmer_fk, foodNotice_fk', 'numerical', 'integerOnly' => true],
            ['amount', 'numerical'],
            ['measurementUnit', 'length', 'max' => 7],
            // The following rule is used by search().
            ['id, food_fk, farmer_fk, measurementUnit, amount, foodNotice_fk', 'safe', 'on' => 'search'],
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
            'farmerFk' => [self::BELONGS_TO, 'FarmerRegister', 'farmer_fk'],
            'foodFk' => [self::BELONGS_TO, 'Food', 'food_fk'],
            'foodNoticeFk' => [self::BELONGS_TO, 'FoodNotice', 'foodNotice_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'food_fk' => 'Food Fk',
            'farmer_fk' => 'Farmer Fk',
            'measurementUnit' => 'Unidade de medida',
            'amount' => 'Quantidade',
            'notice' => 'Edital',
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
        $criteria->compare('food_fk', $this->food_fk);
        $criteria->compare('farmer_fk', $this->farmer_fk);
        $criteria->compare('measurementUnit', $this->measurementUnit, true);
        $criteria->compare('amount', $this->amount);
        $criteria->compare('foodNotice_fk', $this->foodNotice_fk);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name
     * @return FarmerFoods the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
