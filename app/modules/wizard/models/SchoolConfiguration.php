<?php

/**
 * This is the model class for table "school_configuration".
 *
 * The followings are the available columns in table 'school_configuration':
 * @property integer $id
 * @property string $school_inep_id_fk
 * @property string $morning_initial
 * @property string $morning_final
 * @property string $afternoom_initial
 * @property string $afternoom_final
 * @property string $night_initial
 * @property string $night_final
 * @property string $allday_initial
 * @property string $allday_final
 *
 * The followings are the available model relations:
 * @property SchoolIdentification $schoolInepIdFk
 */
class SchoolConfiguration extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'school_configuration';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('school_inep_id_fk', 'required'),
			array('school_inep_id_fk', 'length', 'max'=>8),
			array('morning_initial, morning_final, afternoom_initial, afternoom_final, night_initial, night_final, allday_initial, allday_final', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, school_inep_id_fk, morning_initial, morning_final, afternoom_initial, afternoom_final, night_initial, night_final, allday_initial, allday_final', 'safe', 'on'=>'search'),
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
			'schoolInepIdFk' => array(self::BELONGS_TO, 'SchoolIdentification', 'school_inep_id_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'school_inep_id_fk' => 'School Inep Id Fk',
			'morning_initial' => 'Morning Initial',
			'morning_final' => 'Morning Final',
			'afternoom_initial' => 'Afternoom Initial',
			'afternoom_final' => 'Afternoom Final',
			'night_initial' => 'Night Initial',
			'night_final' => 'Night Final',
			'allday_initial' => 'Allday Initial',
			'allday_final' => 'Allday Final',
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
		$criteria->compare('school_inep_id_fk',$this->school_inep_id_fk,true);
		$criteria->compare('morning_initial',$this->morning_initial,true);
		$criteria->compare('morning_final',$this->morning_final,true);
		$criteria->compare('afternoom_initial',$this->afternoom_initial,true);
		$criteria->compare('afternoom_final',$this->afternoom_final,true);
		$criteria->compare('night_initial',$this->night_initial,true);
		$criteria->compare('night_final',$this->night_final,true);
		$criteria->compare('allday_initial',$this->allday_initial,true);
		$criteria->compare('allday_final',$this->allday_final,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SchoolConfiguration the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
