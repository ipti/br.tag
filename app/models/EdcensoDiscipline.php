<?php

/**
 * This is the model class for table "edcenso_discipline".
 *
 * The followings are the available columns in table 'edcenso_discipline':
 * @property integer $id
 * @property string $name
 * @property integer $edcenso_base_discipline_fk
 *
 * The followings are the available model relations:
 * @property ClassBoard[] $classBoards
 * @property CourseClassAbilities[] $courseClassAbilities
 * @property CoursePlan[] $coursePlans
 * @property CurricularMatrix[] $curricularMatrixes
 * @property EdcensoBaseDisciplines $edcensoBaseDisciplineFk
 * @property FrequencyAndMeanByDiscipline[] $frequencyAndMeanByDisciplines
 * @property GradeResults[] $gradeResults
 * @property InstructorDisciplines[] $instructorDisciplines
 * @property InstructorTeachingData[] $instructorTeachingDatas
 * @property Schedule[] $schedules
 * @property WorkByDiscipline[] $workByDisciplines
 */
class EdcensoDiscipline extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'edcenso_discipline';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, edcenso_base_discipline_fk', 'required'),
			array('edcenso_base_discipline_fk', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, edcenso_base_discipline_fk', 'safe', 'on'=>'search'),
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
			'classBoards' => array(self::HAS_MANY, 'ClassBoard', 'discipline_fk'),
			'courseClassAbilities' => array(self::HAS_MANY, 'CourseClassAbilities', 'edcenso_discipline_fk'),
			'coursePlans' => array(self::HAS_MANY, 'CoursePlan', 'discipline_fk'),
			'curricularMatrixes' => array(self::HAS_MANY, 'CurricularMatrix', 'discipline_fk'),
			'edcensoBaseDisciplineFk' => array(self::BELONGS_TO, 'EdcensoBaseDisciplines', 'edcenso_base_discipline_fk'),
			'frequencyAndMeanByDisciplines' => array(self::HAS_MANY, 'FrequencyAndMeanByDiscipline', 'discipline_fk'),
			'gradeResults' => array(self::HAS_MANY, 'GradeResults', 'discipline_fk'),
			'instructorDisciplines' => array(self::HAS_MANY, 'InstructorDisciplines', 'discipline_fk'),
			'instructorTeachingDatas' => array(self::HAS_MANY, 'InstructorTeachingData', 'discipline_1_fk'),
			'schedules' => array(self::HAS_MANY, 'Schedule', 'discipline_fk'),
			'workByDisciplines' => array(self::HAS_MANY, 'WorkByDiscipline', 'discipline_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => Yii::t('default', 'Discipline'),
			'edcenso_base_discipline_fk' => 'Componente no EducaCenso',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('edcenso_base_discipline_fk',$this->edcenso_base_discipline_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EdcensoDiscipline the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
