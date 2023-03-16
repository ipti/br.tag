<?php

/**
 * This is the model class for table "attendance".
 *
 * The followings are the available columns in table 'attendance':
 * @property integer $id_attendance
 * @property string $date
 * @property string $local
 * @property integer $professional_fk
 *
 * The followings are the available model relations:
 * @property Professional $professionalFk
 * @property Professional[] $professionals
 */
class Attendance extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'attendance';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, local, professional_fk', 'required'),
			array('professional_fk', 'numerical', 'integerOnly'=>true),
			array('local', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_attendance, date, local, professional_fk', 'safe', 'on'=>'search'),
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
			'professionalFk' => array(self::BELONGS_TO, 'Professional', 'professional_fk'),
			'professionals' => array(self::HAS_MANY, 'Professional', 'attendance_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_attendance' => 'Id Attendance',
			'date' => 'Data',
			'local' => 'Local',
			'professional_fk' => 'Professional Fk',
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

		$criteria->compare('id_attendance',$this->id_attendance);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('local',$this->local,true);
		$criteria->compare('professional_fk',$this->professional_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Attendance the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
