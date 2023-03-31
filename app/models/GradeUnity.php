<?php

/**
 * This is the model class for table "grade_unity".
 *
 * The followings are the available columns in table 'grade_unity':
 * @property integer $id
 * @property integer $edcenso_stage_vs_modality_fk
 * @property string $name
 * @property string $type
 * @property integer $grade_calculation_fk
 *
 * The followings are the available model relations:
 * @property GradeCalculation $gradeCalculationFk
 * @property EdcensoStageVsModality $edcensoStageVsModalityFk
 * @property GradeUnityModality[] $gradeUnityModalities
 */
class GradeUnity extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'grade_unity';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('edcenso_stage_vs_modality_fk, name, type, grade_calculation_fk', 'required'),
			array('edcenso_stage_vs_modality_fk, grade_calculation_fk', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			array('type', 'length', 'max'=>2),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, edcenso_stage_vs_modality_fk, name, type, grade_calculation_fk', 'safe', 'on'=>'search'),
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
			'gradeCalculationFk' => array(self::BELONGS_TO, 'GradeCalculation', 'grade_calculation_fk'),
			'edcensoStageVsModalityFk' => array(self::BELONGS_TO, 'EdcensoStageVsModality', 'edcenso_stage_vs_modality_fk'),
			'gradeUnityModalities' => array(self::HAS_MANY, 'GradeUnityModality', 'grade_unity_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'edcenso_stage_vs_modality_fk' => 'Edcenso Stage Vs Modality Fk',
			'name' => 'Name',
			'type' => 'Type',
			'grade_calculation_fk' => 'Grade Calculation Fk',
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
		$criteria->compare('edcenso_stage_vs_modality_fk',$this->edcenso_stage_vs_modality_fk);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('grade_calculation_fk',$this->grade_calculation_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GradeUnity the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
