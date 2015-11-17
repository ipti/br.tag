<?php

/**
 * This is the model class for table "lunch_menu_meal".
 *
 * The followings are the available columns in table 'lunch_menu_meal':
 * @property integer $id
 * @property integer $menu_fk
 * @property integer $meal_fk
 * @property double $amount
 *
 * The followings are the available model relations:
 * @property Menu $menuFk
 * @property Meal $mealFk
 */
class MenuMeal extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MenuMeal the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lunch_menu_meal';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('menu_fk, meal_fk, amount', 'required'),
			array('menu_fk, meal_fk', 'numerical', 'integerOnly'=>true),
			array('amount', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, menu_fk, meal_fk, amount', 'safe', 'on'=>'search'),
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
			'menu' => array(self::BELONGS_TO, 'Menu', 'menu_fk'),
			'meal' => array(self::BELONGS_TO, 'Meal', 'meal_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('default', 'ID'),
			'menu_fk' => Yii::t('default', 'Menu Fk'),
			'meal_fk' => Yii::t('default', 'Meal Fk'),
			'amount' => Yii::t('default', 'Amount'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('menu_fk',$this->menu_fk);
		$criteria->compare('meal_fk',$this->meal_fk);
		$criteria->compare('amount',$this->amount);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}