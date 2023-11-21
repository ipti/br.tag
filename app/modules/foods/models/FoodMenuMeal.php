<?php

/**
 * This is the model class for table "food_menu_meal".
 *
 * The followings are the available columns in table 'food_menu_meal':
 * @property integer $id
 * @property string $description
 * @property string $observation
 * @property integer $sequence
 * @property integer $food_meal_type_fk
 * @property integer $friday
 * @property integer $monday
 * @property integer $saturday
 * @property integer $sunday
 * @property integer $thursday
 * @property integer $tuesday
 * @property integer $wednesday
 * @property integer $food_menuId
 *
 * The followings are the available model relations:
 * @property FoodMenu $foodMenu
 * @property FoodMealType $foodMealTypeFk
 * @property FoodMenuMealComponent[] $foodMenuMealComponents
 */
class FoodMenuMeal extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'food_menu_meal';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('food_meal_type_fk', 'required'),
			array('sequence, food_meal_type_fk, friday, monday, saturday, sunday, thursday, tuesday, wednesday, food_menuId', 'numerical', 'integerOnly'=>true),
			array('description, observation', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, description, observation, sequence, food_meal_type_fk, friday, monday, saturday, sunday, thursday, tuesday, wednesday, food_menuId', 'safe', 'on'=>'search'),
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
			'foodMenu' => array(self::BELONGS_TO, 'FoodMenu', 'food_menuId'),
			'foodMealTypeFk' => array(self::BELONGS_TO, 'FoodMealType', 'food_meal_type_fk'),
			'foodMenuMealComponents' => array(self::HAS_MANY, 'FoodMenuMealComponent', 'food_menu_mealId'),
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
			'sequence' => 'Sequence',
			'food_meal_type_fk' => 'Food Meal Type Fk',
			'friday' => 'Friday',
			'monday' => 'Monday',
			'saturday' => 'Saturday',
			'sunday' => 'Sunday',
			'thursday' => 'Thursday',
			'tuesday' => 'Tuesday',
			'wednesday' => 'Wednesday',
			'food_menuId' => 'Food Menu',
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
		$criteria->compare('sequence',$this->sequence);
		$criteria->compare('food_meal_type_fk',$this->food_meal_type_fk);
		$criteria->compare('friday',$this->friday);
		$criteria->compare('monday',$this->monday);
		$criteria->compare('saturday',$this->saturday);
		$criteria->compare('sunday',$this->sunday);
		$criteria->compare('thursday',$this->thursday);
		$criteria->compare('tuesday',$this->tuesday);
		$criteria->compare('wednesday',$this->wednesday);
		$criteria->compare('food_menuId',$this->food_menuId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FoodMenuMeal the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
