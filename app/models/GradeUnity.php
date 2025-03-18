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
 * @property integer $parcial_recovery_fk
 *
 * The followings are the available model relations:
 * @property GradePartialRecovery $parcialRecoveryFk
 * @property GradeCalculation $gradeCalculationFk
 * @property EdcensoStageVsModality $edcensoStageVsModalityFk
 * @property GradeUnityModality[] $gradeUnityModalities
 * @property GradeUnityPeriods[] $gradeUnityPeriods
 * @property int $countGradeUnityModalities
 */
class GradeUnity extends TagModel
{

    public const TYPE_UNITY = "U";
    public const TYPE_UNITY_BY_CONCEPT = "UC";
    public const TYPE_UNITY_WITH_RECOVERY = "UR";
    public const TYPE_SEMIANUAL_RECOVERY = "RS";
    public const TYPE_FINAL_RECOVERY = "RF";

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
            array('name, type, grade_calculation_fk', 'required'),
            array('edcenso_stage_vs_modality_fk, grade_calculation_fk, parcial_recovery_fk', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 50),
            array('type', 'length', 'max' => 2),
            // The following rule is used by search().
            array('id, edcenso_stage_vs_modality_fk, name, type, grade_calculation_fk, parcial_recovery_fk', 'safe', 'on' => 'search'),
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
            'parcialRecoveryFk' => array(self::BELONGS_TO, 'GradePartialRecovery', 'parcial_recovery_fk'),
			'gradeCalculationFk' => array(self::BELONGS_TO, 'GradeCalculation', 'grade_calculation_fk'),
			'edcensoStageVsModalityFk' => array(self::BELONGS_TO, 'EdcensoStageVsModality', 'edcenso_stage_vs_modality_fk'),
			'gradeUnityModalities' => array(self::HAS_MANY, 'GradeUnityModality', 'grade_unity_fk'),
            'gradeUnityPeriods' => array(self::HAS_MANY, 'GradeUnityPeriods', 'grade_unity_fk'),
            'countGradeUnityModalities' => array(self::STAT, 'GradeUnityModality', 'grade_unity_fk', 'condition' => "type = 'C'"),
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
            'name' => 'Unity Name',
            'type' => 'Type',
            'grade_calculation_fk' => 'Grade Calculation Fk',
            'parcial_recovery_fk' => 'Parcial Recovery Fk'
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
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('edcenso_stage_vs_modality_fk', $this->edcenso_stage_vs_modality_fk);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('grade_calculation_fk', $this->grade_calculation_fk);
        $criteria->compare('parcial_recovery_fk',$this->parcial_recovery_fk);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        )
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GradeUnity the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
