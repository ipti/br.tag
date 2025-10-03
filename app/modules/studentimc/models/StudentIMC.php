<?php

/**
 * This is the model class for table "student_IMC".
 *
 * The followings are the available columns in table 'student_IMC':
 * @property integer $id
 * @property double $height
 * @property double $weight
 * @property double $IMC
 * @property string $observations
 * @property integer $student_fk
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property StudentIdentification $studentFk
 */
class StudentIMC extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'student_IMC';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('height, weight, IMC, student_fk', 'required'),
			array('student_fk', 'numerical', 'integerOnly'=>true),
			array('height, weight, IMC', 'numerical'),
			array('observations', 'length', 'max'=>500),
			array('created_at, updated_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, height, weight, IMC, observations, student_fk, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'studentFk' => array(self::BELONGS_TO, 'StudentIdentification', 'student_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'height' => 'Height',
			'weight' => 'Weight',
			'IMC' => 'Imc',
			'observations' => 'Observations',
			'student_fk' => 'Student Fk',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
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
		$criteria->compare('height',$this->height);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('IMC',$this->IMC);
		$criteria->compare('observations',$this->observations,true);
		$criteria->compare('student_fk',$this->student_fk);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StudentIMC the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
