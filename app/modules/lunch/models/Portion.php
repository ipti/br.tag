<?php

/**
 * This is the model class for table "lunch_portion".
 *
 * The followings are the available columns in table 'lunch_portion':
 * @property int $id
 * @property int $amount
 * @property int $unity_fk
 * @property float $measure
 * @property int $food_fk
 *
 * The followings are the available model relations:
 * @property LunchMealPortion[] $lunchMealPortions
 * @property Food $foodFk
 * @property FoodMeasurement $unityFk
 */
class Portion extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'lunch_portion';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['amount, unity_fk, measure, food_fk', 'required'],
            ['amount, unity_fk, food_fk', 'numerical', 'integerOnly' => true],
            ['measure', 'numerical'],
            // The following rule is used by search().
            ['id, amount, unity_fk, measure, food_fk', 'safe', 'on' => 'search'],
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
            'lunchMealPortions' => [self::HAS_MANY, 'LunchMealPortion', 'portion_fk'],
            'foodFk' => [self::BELONGS_TO, 'Food', 'food_fk'],
            'unityFk' => [self::BELONGS_TO, 'FoodMeasurement', 'unity_fk'],
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
            'unity_fk' => 'Unity Fk',
            'measure' => 'Measure',
            'food_fk' => 'Food Fk',
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
        $criteria->compare('unity_fk', $this->unity_fk);
        $criteria->compare('measure', $this->measure);
        $criteria->compare('food_fk', $this->food_fk);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name
     * @return Portion the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
