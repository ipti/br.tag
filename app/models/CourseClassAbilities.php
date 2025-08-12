<?php

/**
 * This is the model class for table "course_class_abilities".
 *
 * The followings are the available columns in table 'course_class_abilities':
 * @property int $id
 * @property string $description
 * @property string $code
 * @property string $type
 * @property int $edcenso_discipline_fk
 * @property int $edcenso_stage_vs_modality_fk
 * @property int $parent_fk
 *
 * The followings are the available model relations:
 * @property EdcensoDiscipline $edcensoDisciplineFk
 * @property EdcensoStageVsModality $edcensoStageVsModalityFk
 * @property CourseClassAbilities $parentFk
 * @property CourseClassAbilities[] $courseClassAbilities
 * @property CourseClassHasClassAbility[] $courseClassHasClassAbilities
 * @property CoursePlanDisciplineVsAbilities[] $coursePlanDisciplineVsAbilities
 */
class CourseClassAbilities extends TagModel
{
    public $parent_fk;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'course_class_abilities';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['description, edcenso_discipline_fk', 'required'],
            ['edcenso_discipline_fk, edcenso_stage_vs_modality_fk, parent_fk', 'numerical', 'integerOnly' => true],
            ['description', 'length', 'max' => 1500],
            ['code', 'length', 'max' => 20],
            ['type', 'length', 'max' => 50],
            // The following rule is used by search().
            ['id, description, code, type, edcenso_discipline_fk, edcenso_stage_vs_modality_fk, parent_fk', 'safe', 'on' => 'search'],
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
            'edcensoDisciplineFk' => [self::BELONGS_TO, 'EdcensoDiscipline', 'edcenso_discipline_fk'],
            'edcensoStageVsModalityFk' => [self::BELONGS_TO, 'EdcensoStageVsModality', 'edcenso_stage_vs_modality_fk'],
            'parentFk' => [self::BELONGS_TO, 'CourseClassAbilities', 'parent_fk'],
            'courseClassAbilities' => [self::HAS_MANY, 'CourseClassAbilities', 'parent_fk'],
            'courseClassHasClassAbilities' => [self::HAS_MANY, 'CourseClassHasClassAbility', 'course_class_ability_fk'],
            'coursePlanDisciplineVsAbilities' => [self::HAS_MANY, 'CoursePlanDisciplineVsAbilities', 'ability_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Descrição',
            'code' => 'Código',
            'edcenso_discipline_fk' => 'Componente Curricular',
            'edcenso_stage_vs_modality_fk' => 'Etapa de ensino',
            'parent_fk' => 'parent_fk',
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
        $criteria->compare('description', $this->description, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('edcenso_discipline_fk', $this->edcenso_discipline_fk);
        $criteria->compare('edcenso_stage_vs_modality_fk', $this->edcenso_stage_vs_modality_fk);
        $criteria->compare('parent_fk', $this->parent_fk);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name
     * @return CourseClassAbilities the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
