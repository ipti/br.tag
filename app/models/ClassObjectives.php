<?php

/**
 * This is the model class for table "class_class_objective".
 *
 * The followings are the available columns in table 'class_class_objective':
 * @property integer $id
 * @property integer $class_fk
 * @property integer $objective_fk
 *
 * The followings are the available model relations:
 * @property Class $classFk
 * @property ClassObjective $objectiveFk
 */
class ClassObjectives extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'class_class_objective';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('class_fk, objective_fk', 'required'),
			array('id, class_fk, objective_fk', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, class_fk, objective_fk', 'safe', 'on'=>'search'),
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
			'classFk' => array(self::BELONGS_TO, 'Classes', 'class_fk'),
			'objectiveFk' => array(self::BELONGS_TO, 'Objective', 'objective_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' =>  Yii::t('default', 'ID'),
			'class_fk' =>  Yii::t('default', 'Class Fk'),
			'objective_fk' =>  Yii::t('default', 'Objective Fk'),
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
		$criteria->compare('class_fk',$this->class_fk);
		$criteria->compare('objective_fk',$this->objective_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClassObjectives the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
