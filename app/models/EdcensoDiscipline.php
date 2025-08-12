<?php

/**
 * This is the model class for table "edcenso_discipline".
 *
 * The followings are the available columns in table 'edcenso_discipline':
 * @property int $id
 * @property string $name
 * @property string $abbreviation
 * @property int $edcenso_base_discipline_fk
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
class EdcensoDiscipline extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'edcenso_discipline';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['name, edcenso_base_discipline_fk', 'required'],
            ['edcenso_base_discipline_fk', 'numerical', 'integerOnly' => true],
            ['name', 'length', 'max' => 100],
            ['abbreviation', 'length', 'max' => 15],
            // The following rule is used by search().
            ['id, name, edcenso_base_discipline_fk, abbreviation', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'classBoards' => [self::HAS_MANY, 'ClassBoard', 'discipline_fk'],
            'courseClassAbilities' => [self::HAS_MANY, 'CourseClassAbilities', 'edcenso_discipline_fk'],
            'coursePlans' => [self::HAS_MANY, 'CoursePlan', 'discipline_fk'],
            'curricularMatrixes' => [self::HAS_MANY, 'CurricularMatrix', 'discipline_fk'],
            'edcensoBaseDisciplineFk' => [self::BELONGS_TO, 'EdcensoBaseDisciplines', 'edcenso_base_discipline_fk'],
            'frequencyAndMeanByDisciplines' => [self::HAS_MANY, 'FrequencyAndMeanByDiscipline', 'discipline_fk'],
            'gradeResults' => [self::HAS_MANY, 'GradeResults', 'discipline_fk'],
            'instructorDisciplines' => [self::HAS_MANY, 'InstructorDisciplines', 'discipline_fk'],
            'instructorTeachingDatas' => [self::HAS_MANY, 'InstructorTeachingData', 'discipline_1_fk'],
            'schedules' => [self::HAS_MANY, 'Schedule', 'discipline_fk'],
            'workByDisciplines' => [self::HAS_MANY, 'WorkByDiscipline', 'discipline_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('default', 'Discipline'),
            'edcenso_base_discipline_fk' => 'Componente no EducaCenso',
            'abbreviation' => 'Abreviação do componente curricular/eixo',
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
     * based on the search/filter conditions
     */
    public function search()
    {

        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('edcenso_base_discipline_fk', $this->edcenso_base_discipline_fk);
        $criteria->compare('abbreviation', $this->abbreviation, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name
     * @return EdcensoDiscipline the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
