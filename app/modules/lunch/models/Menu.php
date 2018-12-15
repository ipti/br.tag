<?php

/**
 * This is the model class for table "lunch_menu".
 *
 * The followings are the available columns in table 'lunch_menu':
 * @property integer $id
 * @property string $name
 * @property string $date
 * @property string $school_fk
 *
 * The followings are the available model relations:
 * @property MenuMeal[] $menuMeals
 * @property School $school
 */
class Menu extends CActiveRecord{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Menu the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'lunch_menu';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('school_fk, name', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, date, name, school_fk', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'menuMeals' => array(self::HAS_MANY, 'MenuMeal', 'menu_fk'),
			'school' => array(self::BELONGS_TO, 'School', 'school_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'id' => Yii::t('lunchModule.labels', 'ID'),
			'date' => Yii::t('lunchModule.labels', 'Date Creation'),
			'name' => Yii::t('lunchModule.labels', 'Name'),
			'school' => Yii::t('lunchModule.labels', 'School'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search(){
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}