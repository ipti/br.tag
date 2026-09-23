<?php

/**
 * ActiveRecord for table macete_lesson_plan_template.
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $theme
 * @property integer $edcenso_stage_vs_modality_fk
 * @property integer $edcenso_discipline_fk
 * @property integer $created_by_users_fk
 * @property string $unit
 * @property string $knowledge_object
 * @property string $evaluation
 * @property string $references_text
 * @property string $created_at
 * @property string $updated_at
 */
class MaceteLessonPlanTemplate extends TagModel
{
    public function tableName()
    {
        return 'macete_lesson_plan_template';
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
            ['name, theme, edcenso_stage_vs_modality_fk', 'required'],
            ['edcenso_stage_vs_modality_fk, edcenso_discipline_fk, created_by_users_fk', 'numerical', 'integerOnly' => true],
            ['name', 'length', 'max' => 150],
            ['code', 'length', 'max' => 50],
            ['theme', 'length', 'max' => 255],
            ['unit', 'length', 'max' => 50],
            ['code, knowledge_object, evaluation, references_text, created_at, updated_at', 'safe'],
            ['id, name, code, theme, edcenso_stage_vs_modality_fk, edcenso_discipline_fk, created_by_users_fk, unit, knowledge_object, evaluation, references_text, created_at, updated_at', 'safe', 'on' => 'search'],
        ];
    }

    public function relations()
    {
        return [
            'abilities' => [self::HAS_MANY, 'MaceteLessonPlanTemplateAbility', 'lesson_plan_template_fk'],
            'planStages' => [self::HAS_MANY, 'MaceteLessonPlanTemplateStage', 'lesson_plan_template_fk'],
            'sections' => [self::HAS_MANY, 'MaceteLessonPlanTemplateSection', 'lesson_plan_template_fk', 'order' => 'sections.position ASC, sections.id ASC'],
            'resources' => [self::HAS_MANY, 'MaceteLessonPlanTemplateResource', 'lesson_plan_template_fk'],
            'materials' => [self::HAS_MANY, 'MaceteLessonMaterialTemplate', 'lesson_plan_template_fk'],
            'plans' => [self::HAS_MANY, 'MaceteLessonPlan', 'origin_template_fk'],
            'createdByUsersFk' => [self::BELONGS_TO, 'Users', 'created_by_users_fk'],
            'stageFk' => [self::BELONGS_TO, 'EdcensoStageVsModality', 'edcenso_stage_vs_modality_fk'],
            'disciplineFk' => [self::BELONGS_TO, 'EdcensoDiscipline', 'edcenso_discipline_fk'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tema da aula',
            'code' => 'Código do modelo',
            'theme' => 'Tema da aula',
            'edcenso_stage_vs_modality_fk' => 'Etapa',
            'edcenso_discipline_fk' => 'Componente curricular',
            'created_by_users_fk' => 'Criado por',
            'unit' => 'Unidade',
            'knowledge_object' => 'Objeto do conhecimento',
            'evaluation' => 'Avaliação',
            'references_text' => 'Referências',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }

    public function search()
    {
        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('theme', $this->theme, true);
        $criteria->compare('edcenso_stage_vs_modality_fk', $this->edcenso_stage_vs_modality_fk);
        $criteria->compare('edcenso_discipline_fk', $this->edcenso_discipline_fk);
        $criteria->compare('created_by_users_fk', $this->created_by_users_fk);
        $criteria->compare('unit', $this->unit, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
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

    public function getDisciplineNames(): string
    {
        $names = [];
        foreach ($this->planStages as $planStage) {
            if ($planStage->disciplineFk !== null) {
                $names[] = $planStage->disciplineFk->name;
            }
        }

        return implode(', ', array_unique($names));
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
