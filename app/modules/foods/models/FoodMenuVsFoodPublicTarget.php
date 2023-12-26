<?php

/**
 * This is the model class for table "food_menu_vs_food_public_target".
 *
 * The followings are the available columns in table 'food_menu_vs_food_public_target':
 * @property integer $id
 * @property integer $food_menu_fk
 * @property integer $food_public_target_fk
 *
 * The followings are the available model relations:
 * @property FoodMenu $foodMenuFk
 * @property FoodPublicTarget $foodPublicTargetFk
 */
class FoodMenuVsFoodPublicTarget extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'food_menu_vs_food_public_target';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('food_menu_fk, food_public_target_fk', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, food_menu_fk, food_public_target_fk', 'safe', 'on'=>'search'),
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
			'foodMenuFk' => array(self::BELONGS_TO, 'FoodMenu', 'food_menu_fk'),
			'foodPublicTargetFk' => array(self::BELONGS_TO, 'FoodPublicTarget', 'food_public_target_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'food_menu_fk' => 'Food Menu Fk',
			'food_public_target_fk' => 'Food Public Target Fk',
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
		$criteria->compare('food_menu_fk',$this->food_menu_fk);
		$criteria->compare('food_public_target_fk',$this->food_public_target_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FoodMenuVsFoodPublicTarget the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
