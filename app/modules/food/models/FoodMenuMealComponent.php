<?php

/**
 * This is the model class for table "food_menu_meal_component".
 *
 * The followings are the available columns in table 'food_menu_meal_component':
 * @property integer $id
 * @property string $description
 * @property string $observation
 * @property integer $food_menu_mealId
 *
 * The followings are the available model relations:
 * @property FoodIngredient[] $foodIngredients
 * @property FoodMenuMeal $foodMenuMeal
 */
class FoodMenuMealComponent extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'food_menu_meal_component';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('description', 'required'),
			array('food_menu_mealId', 'numerical', 'integerOnly'=>true),
			array('description, observation', 'length', 'max'=>191),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, description, observation, food_menu_mealId', 'safe', 'on'=>'search'),
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
			'foodIngredients' => array(self::HAS_MANY, 'FoodIngredient', 'food_menu_meal_componentId'),
			'foodMenuMeal' => array(self::BELONGS_TO, 'FoodMenuMeal', 'food_menu_mealId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'description' => 'Description',
			'observation' => 'Observation',
			'food_menu_mealId' => 'Food Menu Meal',
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('observation',$this->observation,true);
		$criteria->compare('food_menu_mealId',$this->food_menu_mealId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FoodMenuMealComponent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
