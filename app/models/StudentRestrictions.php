<?php

/**
 * This is the model class for table "student_restrictions".
 *
 * The followings are the available columns in table 'student_restrictions':
 * @property integer $student_fk
 * @property integer $celiac
 * @property integer $diabetes
 * @property integer $hypertension
 * @property integer $iron_deficiency_anemia
 * @property integer $sickle_cell_anemia
 * @property integer $lactose_intolerance
 * @property integer $malnutrition
 * @property integer $obesity
 * @property string $others
 *
 * The followings are the available model relations:
 * @property StudentIdentification $studentFk
 */
class StudentRestrictions extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'student_restrictions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('student_fk, celiac, diabetes, hypertension, iron_deficiency_anemia, sickle_cell_anemia, lactose_intolerance, malnutrition, obesity', 'required'),
			array('student_fk, celiac, diabetes, hypertension, iron_deficiency_anemia, sickle_cell_anemia, lactose_intolerance, malnutrition, obesity', 'numerical', 'integerOnly'=>true),
			array('others', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('student_fk, celiac, diabetes, hypertension, iron_deficiency_anemia, sickle_cell_anemia, lactose_intolerance, malnutrition, obesity, others', 'safe', 'on'=>'search'),
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
			'student_fk' => 'Student Fk',
			'celiac' => 'Doença celíaca',
			'diabetes' => 'Diabetes',
			'hypertension' => 'Hipertensão',
			'iron_deficiency_anemia' => 'Anemia ferropriva',
			'sickle_cell_anemia' => 'Anemia falciforme',
			'lactose_intolerance' => 'Intolerância à lactose',
			'malnutrition' => 'Desnutrição',
			'obesity' => 'Obesidade',
			'others' => 'Outros'
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

		$criteria->compare('student_fk',$this->student_fk);
		$criteria->compare('celiac',$this->celiac);
		$criteria->compare('diabetes',$this->diabetes);
		$criteria->compare('hypertension',$this->hypertension);
		$criteria->compare('iron_deficiency_anemia',$this->iron_deficiency_anemia);
		$criteria->compare('sickle_cell_anemia',$this->sickle_cell_anemia);
		$criteria->compare('lactose_intolerance',$this->lactose_intolerance);
		$criteria->compare('malnutrition',$this->malnutrition);
		$criteria->compare('obesity',$this->obesity);
		$criteria->compare('others',$this->others,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StudentRestrictions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
