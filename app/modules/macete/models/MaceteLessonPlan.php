<?php

/**
 * ActiveRecord for table macete_lesson_plan.
 *
 * @property integer $id
 * @property string $name
 * @property string $theme
 * @property string $school_inep_fk
 * @property integer $classroom_fk
 * @property integer $edcenso_stage_vs_modality_fk
 * @property integer $edcenso_discipline_fk
 * @property integer $users_fk
 * @property integer $school_year
 * @property string $unit
 * @property string $territory_context
 * @property string $knowledge_object
 * @property string $evaluation
 * @property string $references_text
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class MaceteLessonPlan extends TagModel
{
    public const STATUS_DRAFT = 'DRAFT';
    public const STATUS_PENDING = 'PENDING';
    public const STATUS_APPROVED = 'APPROVED';
    public const STATUS_REGISTERED = 'REGISTERED';

    public function tableName()
    {
        return 'macete_lesson_plan';
    }

    public function behaviors()
    {
        return [
            'CTimestampBehavior' => [
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'updateAttribute' => 'updated_at',
                'setUpdateOnCreate' => true,
                'timestampExpression' => new CDbExpression('CONVERT_TZ(NOW(), "+00:00", "-03:00")'),
            ],
        ];
    }

    public function rules()
    {
        return [
            ['name, theme, school_inep_fk, edcenso_stage_vs_modality_fk, users_fk, school_year, status', 'required'],
            ['classroom_fk, edcenso_stage_vs_modality_fk, edcenso_discipline_fk, users_fk, school_year', 'numerical', 'integerOnly' => true],
            ['name', 'length', 'max' => 150],
            ['theme', 'length', 'max' => 255],
            ['school_inep_fk', 'length', 'max' => 8],
            ['unit', 'length', 'max' => 50],
            ['status', 'length', 'max' => 20],
            ['territory_context, knowledge_object, evaluation, references_text, created_at, updated_at', 'safe'],
            ['id, name, theme, school_inep_fk, classroom_fk, edcenso_stage_vs_modality_fk, edcenso_discipline_fk, users_fk, school_year, unit, territory_context, knowledge_object, evaluation, references_text, status, created_at, updated_at', 'safe', 'on' => 'search'],
        ];
    }

    public function relations()
    {
        return [
            'abilities' => [self::HAS_MANY, 'MaceteLessonPlanAbility', 'lesson_plan_fk'],
            'planStages' => [self::HAS_MANY, 'MaceteLessonPlanStage', 'lesson_plan_fk'],
            'sections' => [self::HAS_MANY, 'MaceteLessonPlanSection', 'lesson_plan_fk', 'order' => 'sections.position ASC, sections.id ASC'],
            'resources' => [self::HAS_MANY, 'MaceteLessonPlanResource', 'lesson_plan_fk'],
            'materials' => [self::HAS_MANY, 'MaceteLessonMaterial', 'lesson_plan_fk'],
            'records' => [self::HAS_MANY, 'MaceteLessonRecord', 'lesson_plan_fk'],
            'usersFk' => [self::BELONGS_TO, 'Users', 'users_fk'],
            'schoolInepFk' => [self::BELONGS_TO, 'SchoolIdentification', 'school_inep_fk'],
            'classroomFk' => [self::BELONGS_TO, 'Classroom', 'classroom_fk'],
            'stageFk' => [self::BELONGS_TO, 'EdcensoStageVsModality', 'edcenso_stage_vs_modality_fk'],
            'disciplineFk' => [self::BELONGS_TO, 'EdcensoDiscipline', 'edcenso_discipline_fk'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nome',
            'theme' => 'Tema da aula',
            'school_inep_fk' => 'Escola',
            'classroom_fk' => 'Turma',
            'edcenso_stage_vs_modality_fk' => 'Etapa',
            'edcenso_discipline_fk' => 'Componente curricular',
            'users_fk' => 'Professor',
            'school_year' => 'Ano escolar',
            'unit' => 'Unidade',
            'territory_context' => 'Contextualização do território',
            'knowledge_object' => 'Objeto do conhecimento',
            'evaluation' => 'Avaliação',
            'references_text' => 'Referências',
            'status' => 'Status',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }

    public function search()
    {
        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('theme', $this->theme, true);
        $criteria->compare('school_inep_fk', $this->school_inep_fk, true);
        $criteria->compare('classroom_fk', $this->classroom_fk);
        $criteria->compare('edcenso_stage_vs_modality_fk', $this->edcenso_stage_vs_modality_fk);
        $criteria->compare('edcenso_discipline_fk', $this->edcenso_discipline_fk);
        $criteria->compare('users_fk', $this->users_fk);
        $criteria->compare('school_year', $this->school_year);
        $criteria->compare('unit', $this->unit, true);
        $criteria->compare('status', $this->status, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    public function getStatusLabel(): string
    {
        $labels = self::statusLabels();

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusBadgeClass(): string
    {
        $classes = [
            self::STATUS_DRAFT => 't-badge-info',
            self::STATUS_PENDING => 't-badge-warning',
            self::STATUS_APPROVED => 't-badge-success',
            self::STATUS_REGISTERED => 't-badge-success',
        ];

        return $classes[$this->status] ?? 't-badge-info';
    }

    public function getAbilityCodes(): string
    {
        $codes = [];
        foreach ($this->abilities as $ability) {
            if ($ability->abilityFk !== null && $ability->abilityFk->code !== null) {
                $codes[] = $ability->abilityFk->code;
            }
        }

        return implode(', ', $codes);
    }

    public function getStageIds(): array
    {
        $ids = [];
        foreach ($this->planStages as $planStage) {
            $ids[] = (int) $planStage->edcenso_stage_vs_modality_fk;
        }

        if (empty($ids) && $this->edcenso_stage_vs_modality_fk !== null) {
            $ids[] = (int) $this->edcenso_stage_vs_modality_fk;
        }

        return array_values(array_unique($ids));
    }

    public function getStageNames(): string
    {
        $names = [];
        foreach ($this->planStages as $planStage) {
            if ($planStage->stageFk !== null) {
                $names[] = $planStage->stageFk->name;
            }
        }

        if (empty($names) && $this->stageFk !== null) {
            $names[] = $this->stageFk->name;
        }

        return implode(', ', array_unique($names));
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_DRAFT => 'Rascunho',
            self::STATUS_PENDING => 'Pendente',
            self::STATUS_APPROVED => 'Aprovado',
            self::STATUS_REGISTERED => 'Registrado',
        ];
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
