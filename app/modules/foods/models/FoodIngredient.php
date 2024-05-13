<?php
/**
 * @property integer $id
 * @property string $observation
 * @property integer $amount
 * @property integer $replaceable
 * @property integer $food_menu_meal_componentId
 * @property integer $food_measurement_fk
 * @property integer $food_id_fk
 *
 * The followings are the available model relations:
 * @property FoodMenuMealComponent $foodMenuMealComponent
 * @property FoodMeasurement $foodMeasurementFk
 * @property Food $foodIdFk
 * @property FoodIngredientAlternatives[] $foodIngredientAlternatives
 */
class FoodIngredient extends CActiveRecord
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
        return array(
            array('amount', 'required'),
            array('replaceable, food_menu_meal_componentId, food_measurement_fk, food_id_fk', 'numerical', 'integerOnly'=>true),
            array('observation', 'length', 'max'=>191),
            array('id, observation, amount, replaceable, food_menu_meal_componentId, food_measurement_fk, food_id_fk', 'safe', 'on'=>'search'),
        );
    }

    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'foodMenuMealComponent' => array(self::BELONGS_TO, 'FoodMenuMealComponent', 'food_menu_meal_componentId'),
            'foodMeasurementFk' => array(self::BELONGS_TO, 'FoodMeasurement', 'food_measurement_fk'),
            'foodIdFk' => array(self::BELONGS_TO, 'Food', 'food_id_fk'),
            'foodIngredientAlternatives' => array(self::HAS_MANY, 'FoodIngredientAlternatives', 'food_ingredient_fk'),
            'foodInventory' => array(
                self::BELONGS_TO,
                FoodInventory::class,
                ['food_id_fk' => 'food_fk' ],
                'joinType' => "LEFT JOIN",
                "together" => true,
                'condition' => 'foodInventory.school_fk =' . Yii::app()->user->school,
            )
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'observation' => 'Observation',
            'amount' => 'Amount',
            'replaceable' => 'Replaceable',
            'food_menu_meal_componentId' => 'Food Menu Meal Component',
            'food_measurement_fk' => 'Food Measurement Fk',
            'food_id_fk' => 'Food Id Fk'
        );
    }

    /**
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('observation',$this->observation,true);
        $criteria->compare('amount',$this->amount);
        $criteria->compare('replaceable',$this->replaceable);
        $criteria->compare('food_menu_meal_componentId',$this->food_menu_meal_componentId);
        $criteria->compare('food_measurement_fk',$this->food_measurement_fk);
        $criteria->compare('food_id_fk',$this->food_id_fk);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FoodIngredient the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
