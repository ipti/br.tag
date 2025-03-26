<?php

/**
 * This is the model class for table "food_request_vs_farmer_register".
 *
 * The followings are the available columns in table 'food_request_vs_farmer_register':
 * @property integer $id
 * @property integer $farmer_fk
 * @property integer $food_request_fk
 *
 * The followings are the available model relations:
 * @property FarmerRegister $farmerFk
 * @property FoodRequest $foodRequestFk
 */
class FoodRequestVsFarmerRegister extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'food_request_vs_farmer_register';
	}


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('farmer_fk, food_request_fk', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			array('id, farmer_fk, food_request_fk', 'safe', 'on'=>'search'),
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
			'farmerFk' => array(self::BELONGS_TO, 'FarmerRegister', 'farmer_fk'),
			'foodRequestFk' => array(self::BELONGS_TO, 'FoodRequest', 'food_request_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'farmer_fk' => 'Agricultor',
			'food_request_fk' => 'Food Request Fk',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('farmer_fk',$this->farmer_fk);
		$criteria->compare('food_request_fk',$this->food_request_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FoodRequestVsFarmerRegister the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
