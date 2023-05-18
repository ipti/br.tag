<?php

/**
 * This is the model class for table "food_inventory_spent".
 *
 * The followings are the available columns in table 'food_inventory_spent':
 * @property integer $id
 * @property double $amount
 * @property string $date
 * @property string $motivation
 * @property integer $food_inventory_fk
 *
 * The followings are the available model relations:
 * @property FoodInventory $foodInventoryFk
 */
class FoodInventorySpent extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'food_inventory_spent';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('amount, food_inventory_fk', 'required'),
			array('food_inventory_fk', 'numerical', 'integerOnly'=>true),
			array('amount', 'numerical'),
			array('motivation', 'length', 'max'=>100),
			array('date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, amount, date, motivation, food_inventory_fk', 'safe', 'on'=>'search'),
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
			'foodInventoryFk' => array(self::BELONGS_TO, 'FoodInventory', 'food_inventory_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'amount' => 'Amount',
			'date' => 'Date',
			'motivation' => 'Motivation',
			'food_inventory_fk' => 'Food Inventory Fk',
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
		$criteria->compare('amount',$this->amount);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('motivation',$this->motivation,true);
		$criteria->compare('food_inventory_fk',$this->food_inventory_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FoodInventorySpent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
