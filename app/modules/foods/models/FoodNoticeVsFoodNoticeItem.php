<?php

/**
 * This is the model class for table "food_notice_vs_food_notice_item".
 *
 * The followings are the available columns in table 'food_notice_vs_food_notice_item':
 * @property integer $id
 * @property integer $food_notice_id
 * @property integer $food_notice_item_id
 *
 * The followings are the available model relations:
 * @property FoodNotice $foodNotice
 * @property FoodNoticeItem $foodNoticeItem
 */
class FoodNoticeVsFoodNoticeItem extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'food_notice_vs_food_notice_item';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('food_notice_id, food_notice_item_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			array('id, food_notice_id, food_notice_item_id', 'safe', 'on'=>'search'),
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
			'foodNotice' => array(self::BELONGS_TO, 'FoodNotice', 'food_notice_id'),
			'foodNoticeItem' => array(self::BELONGS_TO, 'FoodNoticeItem', 'food_notice_item_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'food_notice_id' => 'Food Notice',
			'food_notice_item_id' => 'Food Notice Item',
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
		$criteria->compare('food_notice_id',$this->food_notice_id);
		$criteria->compare('food_notice_item_id',$this->food_notice_item_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FoodNoticeVsFoodNoticeItem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
