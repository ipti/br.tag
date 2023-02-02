<?php

/**
 * This is the model class for table "school_stages_concept_grades".
 *
 * The followings are the available columns in table 'school_stages_concept_grades':
 * @property integer $id
 * @property string $school_fk
 * @property integer $edcenso_stage_vs_modality_fk
 *
 * The followings are the available model relations:
 * @property EdcensoStageVsModality $edcensoStageVsModalityFk
 * @property SchoolIdentification $schoolFk
 */
class SchoolStagesConceptGrades extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'school_stages_concept_grades';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['school_fk, edcenso_stage_vs_modality_fk', 'required'],
            ['edcenso_stage_vs_modality_fk', 'numerical', 'integerOnly' => true],
            ['school_fk', 'length', 'max' => 8],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, school_fk, edcenso_stage_vs_modality_fk', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'edcensoStageVsModalityFk' => [self::BELONGS_TO, 'EdcensoStageVsModality', 'edcenso_stage_vs_modality_fk'],
            'schoolFk' => [self::BELONGS_TO, 'SchoolIdentification', 'school_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_fk' => 'School Fk',
            'edcenso_stage_vs_modality_fk' => 'Edcenso Stage Vs Modality Fk',
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
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('school_fk', $this->school_fk, true);
        $criteria->compare('edcenso_stage_vs_modality_fk', $this->edcenso_stage_vs_modality_fk);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SchoolStagesConceptGrades the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
