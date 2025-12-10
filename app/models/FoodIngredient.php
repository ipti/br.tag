<?php

/**
 * This is the model class for table "food_ingredient".
 *
 * The followings are the available columns in table 'food_ingredient':
 * @property integer $id
 * @property string $observation
 * @property integer $amount
 * @property integer $replaceable
 * @property integer $food_menu_meal_componentId
 * @property integer $food_measurement_fk
 * @property integer $food_id_fk
 * @property string $portion
 * @property integer $measurement_for_unit
 *
 * The followings are the available model relations:
 * @property Food $foodIdFk
 * @property FoodMenuMealComponent $foodMenuMealComponent
 * @property FoodMeasurement $foodMeasurementFk
 * @property FoodIngredientAlternatives[] $foodIngredientAlternatives
 */
class FoodIngredient extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'food_ingredient';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['amount, replaceable', 'required'],
            ['amount, replaceable, food_menu_meal_componentId, food_measurement_fk, food_id_fk, measurement_for_unit', 'numerical', 'integerOnly' => true],
            ['observation', 'length', 'max' => 191],
            ['portion', 'length', 'max' => 20],
            ['id, observation, amount, replaceable, food_menu_meal_componentId, food_measurement_fk, food_id_fk, portion, measurement_for_unit', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'foodIdFk' => [self::BELONGS_TO, 'Food', 'food_id_fk'],
            'foodMenuMealComponent' => [self::BELONGS_TO, 'FoodMenuMealComponent', 'food_menu_meal_componentId'],
            'foodMeasurementFk' => [self::BELONGS_TO, 'FoodMeasurement', 'food_measurement_fk'],
            'foodIngredientAlternatives' => [self::HAS_MANY, 'FoodIngredientAlternatives', 'food_ingredient_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'observation' => 'Observation',
            'amount' => 'Amount',
            'replaceable' => 'Replaceable',
            'food_menu_meal_componentId' => 'Food Menu Meal Component',
            'food_measurement_fk' => 'Food Measurement Fk',
            'food_id_fk' => 'Food Id Fk',
            'portion' => 'Portion',
            'measurement_for_unit' => 'Measurement For Unit',
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
     * based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('observation', $this->observation, true);
        $criteria->compare('amount', $this->amount);
        $criteria->compare('replaceable', $this->replaceable);
        $criteria->compare('food_menu_meal_componentId', $this->food_menu_meal_componentId);
        $criteria->compare('food_measurement_fk', $this->food_measurement_fk);
        $criteria->compare('food_id_fk', $this->food_id_fk);
        $criteria->compare('portion', $this->portion, true);
        $criteria->compare('measurement_for_unit', $this->measurement_for_unit);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FoodIngredient the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
