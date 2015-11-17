<?php

/**
 * This is the model class for table "lunch_meal_portion".
 *
 * The followings are the available columns in table 'lunch_meal_portion':
 * @property integer $id
 * @property integer $meal_fk
 * @property integer $portion_fk
 * @property double $amount
 *
 * The followings are the available model relations:
 * @property Portion $portionFk
 * @property Meal $mealFk
 */
class MealPortion extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MealPortion the static model class
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
		return 'lunch_meal_portion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('meal_fk, portion_fk, amount', 'required'),
			array('meal_fk, portion_fk', 'numerical', 'integerOnly'=>true),
			array('amount', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, meal_fk, portion_fk, amount', 'safe', 'on'=>'search'),
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
			'portion' => array(self::BELONGS_TO, 'Portion', 'portion_fk'),
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
			'meal_fk' => Yii::t('default', 'Meal Fk'),
			'portion_fk' => Yii::t('default', 'Portion Fk'),
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
		$criteria->compare('meal_fk',$this->meal_fk);
		$criteria->compare('portion_fk',$this->portion_fk);
		$criteria->compare('amount',$this->amount);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}