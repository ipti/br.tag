<?php

/**
 * This is the model class for table "grade".
 *
 * The followings are the available columns in table 'grade':
 * @property integer $id
 * @property double $grade
 * @property integer $grade_concept_fk
 * @property integer $enrollment_fk
 * @property integer $discipline_fk
 * @property integer $grade_unity_modality_fk
 * @property integer $grade_partial_recovery_fk
 *
 * The followings are the available model relations:
 * @property GradePartialRecovery $gradePartialRecoveryFk
 * @property GradeUnityModality $gradeUnityModalityFk
 * @property EdcensoDiscipline $disciplineFk
 * @property GradeConcept $gradeConceptFk
 */
class Grade extends TagModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'grade';
	}





	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('enrollment_fk, discipline_fk', 'required'),
			array('grade_concept_fk, enrollment_fk, discipline_fk, grade_unity_modality_fk, grade_partial_recovery_fk', 'numerical', 'integerOnly'=>true),
			array('grade', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, grade, grade_concept_fk, enrollment_fk, discipline_fk, grade_unity_modality_fk, grade_partial_recovery_fk', 'safe', 'on'=>'search'),
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
			'gradePartialRecoveryFk' => array(self::BELONGS_TO, 'GradePartialRecovery', 'grade_partial_recovery_fk'),
			'gradeUnityModalityFk' => array(self::BELONGS_TO, 'GradeUnityModality', 'grade_unity_modality_fk'),
			'disciplineFk' => array(self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_fk'),
			'gradeConceptFk' => array(self::BELONGS_TO, 'GradeConcept', 'grade_concept_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'grade' => 'Grade',
			'grade_concept_fk' => 'Grade Concept Fk',
			'enrollment_fk' => 'Enrollment Fk',
			'discipline_fk' => 'Discipline Fk',
			'grade_unity_modality_fk' => 'Grade Unity Modality Fk',
			'grade_partial_recovery_fk' => 'Grade Partial Recovery Fk',
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
		$criteria->compare('grade',$this->grade);
		$criteria->compare('grade_concept_fk',$this->grade_concept_fk);
		$criteria->compare('enrollment_fk',$this->enrollment_fk);
		$criteria->compare('discipline_fk',$this->discipline_fk);
		$criteria->compare('grade_unity_modality_fk',$this->grade_unity_modality_fk);
		$criteria->compare('grade_partial_recovery_fk',$this->grade_partial_recovery_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Grade the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
