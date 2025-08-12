<?php

/**
 * This is the model class for table "food_ingredient".
 *
 * The followings are the available columns in table 'food_ingredient':
 * @property int $id
 * @property string $observation
 * @property int $amount
 * @property int $replaceable
 * @property int $food_menu_meal_componentId
 * @property int $food_measurement_fk
 * @property int $food_id_fk
 *
 * The followings are the available model relations:
 * @property FoodMenuMealComponent $foodMenuMealComponent
 * @property FoodMeasurement $foodMeasurementFk
 * @property Food $foodIdFk
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
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['amount', 'required'],
            ['replaceable, food_menu_meal_componentId, food_measurement_fk, food_id_fk', 'numerical', 'integerOnly' => true],
            ['observation', 'length', 'max' => 191],
            // The following rule is used by search().
            ['id, observation, amount, replaceable, food_menu_meal_componentId, food_measurement_fk, food_id_fk', 'safe', 'on' => 'search'],
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
            'foodMenuMealComponent' => [self::BELONGS_TO, 'FoodMenuMealComponent', 'food_menu_meal_componentId'],
            'foodMeasurementFk' => [self::BELONGS_TO, 'FoodMeasurement', 'food_measurement_fk'],
            'foodIdFk' => [self::BELONGS_TO, 'Food', 'food_id_fk'],
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
        $criteria->compare('observation', $this->observation, true);
        $criteria->compare('amount', $this->amount);
        $criteria->compare('replaceable', $this->replaceable);
        $criteria->compare('food_menu_meal_componentId', $this->food_menu_meal_componentId);
        $criteria->compare('food_measurement_fk', $this->food_measurement_fk);
        $criteria->compare('food_id_fk', $this->food_id_fk);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name
     * @return FoodIngredient the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
