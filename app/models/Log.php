<?php

/**
 * This is the model class for table "log".
 *
 * The followings are the available columns in table 'log':
 * @property integer $id
 * @property string $reference
 * @property string $reference_ids
 * @property string $crud
 * @property string $date
 * @property string $additional_info
 * @property string $school_fk
 * @property integer $user_fk
 *
 * The followings are the available model relations:
 * @property SchoolIdentification $schoolFk
 * @property Users $userFk
 */
class Log extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reference, reference_ids, crud, school_fk, user_fk', 'required'),
			array('user_fk', 'numerical', 'integerOnly'=>true),
			array('reference', 'length', 'max'=>50),
			array('reference_ids', 'length', 'max'=>20),
			array('crud', 'length', 'max'=>1),
			array('additional_info', 'length', 'max'=>100),
			array('school_fk', 'length', 'max'=>8),
			array('date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, reference, reference_ids, crud, date, additional_info, school_fk, user_fk', 'safe', 'on'=>'search'),
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
			'schoolFk' => array(self::BELONGS_TO, 'SchoolIdentification', 'school_fk'),
			'userFk' => array(self::BELONGS_TO, 'Users', 'user_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'reference' => 'Reference',
			'reference_ids' => 'Reference Ids',
			'crud' => 'Crud',
			'date' => 'Date',
			'additional_info' => 'Additional Info',
			'school_fk' => 'School Fk',
			'user_fk' => 'User Fk',
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
		$criteria->compare('reference',$this->reference,true);
		$criteria->compare('reference_ids',$this->reference_ids,true);
		$criteria->compare('crud',$this->crud,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('additional_info',$this->additional_info,true);
		$criteria->compare('school_fk',$this->school_fk,true);
		$criteria->compare('user_fk',$this->user_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Log the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function saveAction($reference, $referenceIds, $crud, $additionalInfo = null) {
		date_default_timezone_set("America/Recife");
		$date = new DateTime();
		$log = new Log();
		$log->reference = $reference;
		$log->reference_ids = $referenceIds;
		$log->crud = $crud;
		$log->date = $date->format("Y-m-d H:i:s");
		$log->user_fk = Yii::app()->user->loginInfos->id;
		$log->school_fk = Yii::app()->user->school;
		$log->additional_info = $additionalInfo;
		$log->save();
	}
}
