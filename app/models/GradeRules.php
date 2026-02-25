<?php

/**
 * This is the model class for table "grade_rules".
 *
 * The followings are the available columns in table 'grade_rules':
 * @property integer $id
 * @property integer $edcenso_stage_vs_modality_fk
 * @property double $approvation_media
 * @property double $final_recover_media
 * @property integer $grade_calculation_fk
 * @property integer $has_final_recovery
 * @property integer $has_partial_recovery
 * @property string $rule_type
 * @property integer $school_year
 *
 * The followings are the available model relations:
 * @property GradeCalculation $gradeCalculationFk
 * @property EdcensoStageVsModality $edcensoStageVsModalityFk
 */
class GradeRules extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'grade_rules';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['approvation_media', 'required', 'on' => 'numericGrade'],
            ['grade_calculation_fk, has_final_recovery, has_partial_recovery', 'numerical', 'integerOnly' => true],
            ['approvation_media, final_recover_media', 'numerical'],
            ['school_year', 'numerical', 'integerOnly' => true],
            ['rule_type', 'length', 'max' => 1],
            ['id, edcenso_stage_vs_modality_fk, approvation_media, final_recover_media, grade_calculation_fk, has_final_recovery, has_partial_recovery, rule_type, school_year', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'gradeCalculationFk'   => [self::BELONGS_TO, 'GradeCalculation', 'grade_calculation_fk'],
            'edcensoStageVsModalityFk' => [self::BELONGS_TO, 'EdcensoStageVsModality', 'edcenso_stage_vs_modality_fk'],
            'gradeRulesStages'     => [self::HAS_MANY, 'GradeRulesVsEdcensoStageVsModality', 'grade_rules_fk'],
        ];
    }

    /**
     * Returns a comma-separated string with the names of all stages
     * linked to this grade rule via the junction table.
     *
     * @return string e.g. "1º Ano – Ensino Fundamental, 2º Ano – Ensino Fundamental"
     */
    public function getStageNames(): string
    {
        $names = [];
        foreach ($this->gradeRulesStages as $pivot) {
            if ($pivot->edcensoStageVsModalityFk !== null) {
                $names[] = $pivot->edcensoStageVsModalityFk->name;
            }
        }
        return implode(', ', $names);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'edcenso_stage_vs_modality_fk' => 'Edcenso Stage Vs Modality Fk',
            'approvation_media' => 'Approvation Media',
            'final_recover_media' => 'Final Recover Media',
            'grade_calculation_fk' => 'Grade Calculation Fk',
            'has_final_recovery' => 'Has Final Recovery',
            'has_partial_recovery' => 'Has Partial Recovery',
            'rule_type' => 'Rule Type',
            'school_year' => 'Ano de Vigência',
        ];
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
        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('edcenso_stage_vs_modality_fk', $this->edcenso_stage_vs_modality_fk);
        $criteria->compare('approvation_media', $this->approvation_media);
        $criteria->compare('final_recover_media', $this->final_recover_media);
        $criteria->compare('grade_calculation_fk', $this->grade_calculation_fk);
        $criteria->compare('has_final_recovery', $this->has_final_recovery);
        $criteria->compare('has_partial_recovery', $this->has_partial_recovery);
        $criteria->compare('rule_type', $this->rule_type, true);
        $criteria->compare('school_year', $this->school_year);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GradeRules the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
