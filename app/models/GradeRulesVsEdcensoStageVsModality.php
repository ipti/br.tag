<?php

/**
 * This is the model class for table "grade_rules_vs_edcenso_stage_vs_modality".
 *
 * The followings are the available columns in table 'grade_rules_vs_edcenso_stage_vs_modality':
 * @property integer $id
 * @property integer $edcenso_stage_vs_modality_fk
 * @property integer $grade_rules_fk
 *
 * The followings are the available model relations:
 * @property EdcensoStageVsModality $edcensoStageVsModalityFk
 * @property GradeRules $gradeRulesFk
 */
class GradeRulesVsEdcensoStageVsModality extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'grade_rules_vs_edcenso_stage_vs_modality';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('edcenso_stage_vs_modality_fk, grade_rules_fk', 'required'),
			array('edcenso_stage_vs_modality_fk, grade_rules_fk', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, edcenso_stage_vs_modality_fk, grade_rules_fk', 'safe', 'on'=>'search'),
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
			'edcensoStageVsModalityFk' => array(self::BELONGS_TO, 'EdcensoStageVsModality', 'edcenso_stage_vs_modality_fk'),
			'gradeRulesFk' => array(self::BELONGS_TO, 'GradeRules', 'grade_rules_fk'),
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
			'grade_rules_fk' => 'Grade Rules Fk',
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
		$criteria->compare('grade_rules_fk',$this->grade_rules_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GradeRulesVsEdcensoStageVsModality the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
