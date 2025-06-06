<?php

/**
 * This is the model class for table "sedsp_school_unities".
 *
 * The followings are the available columns in table 'sedsp_school_unities':
 * @property integer $id
 * @property integer $code
 * @property string $description
 * @property string $school_inep_id_fk
 *
 * The followings are the available model relations:
 * @property SchoolIdentification $schoolInepIdFk
 */
class SedspSchoolUnities extends TagModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sedsp_school_unities';
	}


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, description, school_inep_id_fk', 'required'),
			array('code', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>100),
			array('school_inep_id_fk', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, description, school_inep_id_fk', 'safe', 'on'=>'search'),
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
			'code' => 'Code',
			'description' => 'Description',
			'school_inep_id_fk' => 'School Inep Id Fk',
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
		$criteria->compare('code',$this->code);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('school_inep_id_fk',$this->school_inep_id_fk,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SedspSchoolUnities the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
