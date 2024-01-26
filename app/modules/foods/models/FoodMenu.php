<?php

/**
 * This is the model class for table "food_menu".
 *
 * The followings are the available columns in table 'food_menu':
 * @property integer $id
 * @property string $description
 * @property string $week
 * @property string $observation
 * @property string $start_date
 * @property string $final_date
 *
 * The followings are the available model relations:
 * @property FoodMenuMeal[] $foodMenuMeals
 * @property FoodMenuVsFoodPublicTarget[] $foodMenuVsFoodPublicTargets
 */
class FoodMenu extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'food_menu';
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
			array('description, observation', 'length', 'max'=>100),
			array('start_date, final_date, week', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, description, observation, start_date, final_date', 'safe', 'on'=>'search'),
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
			'foodMenuMeals' => array(self::HAS_MANY, 'FoodMenuMeal', 'food_menuId'),
			'foodMenuVsFoodPublicTargets' => array(self::HAS_MANY, 'FoodMenuVsFoodPublicTarget', 'food_menu_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'description'  => 'Nome',
	        'observation'  => 'Observação',
	        'start_date'  => 'Data inicial',
	        'final_date'  =>'Data Final',
	        'week'  => 'Semana',
	        'stage_fk'  => 'Publico',
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
		$criteria->compare('week',$this->week,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('observation',$this->observation,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('final_date',$this->final_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FoodMenu the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
