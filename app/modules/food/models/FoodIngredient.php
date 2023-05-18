<?php

/**
 * This is the model class for table "food_ingredient".
 *
 * The followings are the available columns in table 'food_ingredient':
 * @property integer $id
 * @property integer $food_id_fk
 * @property string $observation
 * @property integer $amount
 * @property string $measurementUnit
 * @property integer $replaceable
 * @property integer $food_menu_meal_componentId
 *
 * The followings are the available model relations:
 * @property Food $foodIdFk
 * @property FoodMenuMealComponent $foodMenuMealComponent
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
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('food_id_fk, amount, measurementUnit, replaceable', 'required'),
			array('food_id_fk, amount, replaceable, food_menu_meal_componentId', 'numerical', 'integerOnly'=>true),
			array('observation', 'length', 'max'=>191),
			array('measurementUnit', 'length', 'max'=>7),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, food_id_fk, observation, amount, measurementUnit, replaceable, food_menu_meal_componentId', 'safe', 'on'=>'search'),
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
			'foodIdFk' => array(self::BELONGS_TO, 'Food', 'food_id_fk'),
			'foodMenuMealComponent' => array(self::BELONGS_TO, 'FoodMenuMealComponent', 'food_menu_meal_componentId'),
			'foodIngredientAlternatives' => array(self::HAS_MANY, 'FoodIngredientAlternatives', 'food_ingredient_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'food_id_fk' => 'Food Id Fk',
			'observation' => 'Observation',
			'amount' => 'Amount',
			'measurementUnit' => 'Measurement Unit',
			'replaceable' => 'Replaceable',
			'food_menu_meal_componentId' => 'Food Menu Meal Component',
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
		$criteria->compare('food_id_fk',$this->food_id_fk);
		$criteria->compare('observation',$this->observation,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('measurementUnit',$this->measurementUnit,true);
		$criteria->compare('replaceable',$this->replaceable);
		$criteria->compare('food_menu_meal_componentId',$this->food_menu_meal_componentId);

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
