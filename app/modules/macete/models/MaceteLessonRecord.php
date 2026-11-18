<?php

/**
 * ActiveRecord for table macete_lesson_record.
 *
 * O registro de aula guarda uma cópia do plano MACETE (colunas plan_*; listas de
 * etapas, seções, recursos, materiais e habilidades em JSON). lesson_plan_fk é só
 * o id do plano, sem FK: se o plano for apagado, o registro continua com a cópia.
 * Enquanto o plano existir, a cópia acompanha as alterações dele.
 *
 * @property integer|null $lesson_plan_fk
 * @property string|null $plan_name
 * @property string|null $plan_code
 * @property string|null $plan_theme
 * @property integer|null $plan_users_id
 * @property integer|null $plan_classroom_id
 * @property integer|null $plan_stage_id
 * @property integer|null $plan_discipline_id
 * @property integer|null $plan_origin_template_id
 * @property integer|null $plan_school_year
 * @property string|null $plan_unit
 * @property string|null $plan_territory_context
 * @property string|null $plan_knowledge_object
 * @property string|null $plan_evaluation
 * @property string|null $plan_adaptations
 * @property string|null $plan_references_text
 * @property string|null $plan_stages JSON
 * @property string|null $plan_sections JSON
 * @property string|null $plan_resources JSON
 * @property string|null $plan_materials JSON
 * @property string|null $plan_abilities JSON
 */
class MaceteLessonRecord extends TagModel
{
    public function tableName()
    {
        return 'macete_lesson_record';
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
            ['school_inep_fk, classroom_fk, edcenso_stage_vs_modality_fk, users_fk, lesson_date, executed_content', 'required'],
            ['lesson_plan_fk, classroom_fk, edcenso_stage_vs_modality_fk, edcenso_discipline_fk, users_fk', 'numerical', 'integerOnly' => true],
            ['school_inep_fk', 'length', 'max' => 8],
            ['methodology_notes, evaluation_notes, adaptation_notes, created_at, updated_at', 'safe'],
            ['id, lesson_plan_fk, school_inep_fk, classroom_fk, edcenso_stage_vs_modality_fk, edcenso_discipline_fk, users_fk, lesson_date, executed_content, methodology_notes, evaluation_notes, adaptation_notes, created_at, updated_at', 'safe', 'on' => 'search'],
        ];
    }

    public function relations()
    {
        return [
            'lessonPlanFk' => [self::BELONGS_TO, 'MaceteLessonPlan', 'lesson_plan_fk'],
            'abilities' => [self::HAS_MANY, 'MaceteLessonRecordAbility', 'lesson_record_fk'],
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
            'lesson_plan_fk' => 'Plano MACETE',
            'school_inep_fk' => 'Escola',
            'classroom_fk' => 'Turma',
            'edcenso_stage_vs_modality_fk' => 'Etapa',
            'edcenso_discipline_fk' => 'Componente curricular',
            'users_fk' => 'Professor',
            'lesson_date' => 'Data da aula',
            'executed_content' => 'Anotações de Aula',
            'methodology_notes' => 'Aplicação da metodologia',
            'evaluation_notes' => 'Evidências/observações',
            'adaptation_notes' => 'Adaptações realizadas',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }

    /**
     * Copia para o registro todos os campos do plano MACETE.
     */
    public function applyPlanSnapshot(MaceteLessonPlan $plan): void
    {
        $this->lesson_plan_fk = $plan->id;
        $this->plan_name = $plan->name;
        $this->plan_code = $plan->code;
        $this->plan_theme = $plan->theme;
        $this->plan_users_id = $plan->users_fk;
        $this->plan_classroom_id = $plan->classroom_fk;
        $this->plan_stage_id = $plan->edcenso_stage_vs_modality_fk;
        $this->plan_discipline_id = $plan->edcenso_discipline_fk;
        $this->plan_origin_template_id = $plan->origin_template_fk;
        $this->plan_school_year = $plan->school_year;
        $this->plan_unit = $plan->unit;
        $this->plan_territory_context = $plan->territory_context;
        $this->plan_knowledge_object = $plan->knowledge_object;
        $this->plan_evaluation = $plan->evaluation;
        $this->plan_adaptations = $plan->adaptations;
        $this->plan_references_text = $plan->references_text;

        $stages = [];
        foreach (MaceteLessonPlanStage::model()->findAllByAttributes(['lesson_plan_fk' => $plan->id]) as $stage) {
            $stages[] = ['stage_id' => (int) $stage->edcenso_stage_vs_modality_fk, 'discipline_id' => (int) $stage->edcenso_discipline_fk];
        }

        $sections = [];
        foreach (MaceteLessonPlanSection::model()->findAllByAttributes(['lesson_plan_fk' => $plan->id], ['order' => 'position ASC, id ASC']) as $section) {
            $sections[] = [
                'section_type' => $section->section_type,
                'title' => $section->title,
                'target_group' => $section->target_group,
                'content' => $section->content,
                'position' => $section->position !== null ? (int) $section->position : null,
            ];
        }

        $resources = [];
        foreach (MaceteLessonPlanResource::model()->findAllByAttributes(['lesson_plan_fk' => $plan->id]) as $resource) {
            $resources[] = [
                'resource_type' => $resource->resource_type,
                'name' => $resource->name,
                'amount' => $resource->amount,
                'description' => $resource->description,
            ];
        }

        $materials = [];
        foreach (MaceteLessonMaterial::model()->findAllByAttributes(['lesson_plan_fk' => $plan->id]) as $material) {
            $materials[] = [
                'title' => $material->title,
                'material_type' => $material->material_type,
                'description' => $material->description,
                'file_path' => $material->file_path,
            ];
        }

        $abilities = [];
        foreach (MaceteLessonPlanAbility::model()->with('abilityFk')->findAllByAttributes(['lesson_plan_fk' => $plan->id]) as $planAbility) {
            if ($planAbility->abilityFk !== null) {
                $abilities[] = [
                    'id' => (int) $planAbility->abilityFk->id,
                    'code' => $planAbility->abilityFk->code,
                    'description' => $planAbility->abilityFk->description,
                ];
            }
        }

        $this->plan_stages = CJSON::encode($stages);
        $this->plan_sections = CJSON::encode($sections);
        $this->plan_resources = CJSON::encode($resources);
        $this->plan_materials = CJSON::encode($materials);
        $this->plan_abilities = CJSON::encode($abilities);
    }

    /**
     * Plano atual, se ainda existir.
     */
    public function getCurrentPlan(): ?MaceteLessonPlan
    {
        return $this->lesson_plan_fk !== null ? $this->lessonPlanFk : null;
    }

    public function getPlanName(): string
    {
        $plan = $this->getCurrentPlan();
        return $plan !== null ? (string) $plan->name : (string) $this->plan_name;
    }

    public function getPlanTheme(): string
    {
        $plan = $this->getCurrentPlan();
        return $plan !== null ? (string) $plan->theme : (string) $this->plan_theme;
    }

    public function getPlanStageName(): string
    {
        $plan = $this->getCurrentPlan();
        if ($plan !== null) {
            return $plan->stageFk !== null ? (string) $plan->stageFk->name : '';
        }

        $stage = $this->plan_stage_id !== null ? EdcensoStageVsModality::model()->findByPk($this->plan_stage_id) : null;
        return $stage !== null ? (string) $stage->name : '';
    }

    public function getPlanDisciplineNames(): string
    {
        $plan = $this->getCurrentPlan();
        if ($plan !== null) {
            return $plan->getDisciplineNames();
        }

        $disciplineIds = array_filter(array_unique(array_column($this->decodePlanList('plan_stages'), 'discipline_id')));
        if (empty($disciplineIds)) {
            return '';
        }

        $names = [];
        foreach (EdcensoDiscipline::model()->findAllByPk($disciplineIds) as $discipline) {
            $names[] = $discipline->name;
        }

        return implode(', ', $names);
    }

    public function getPlanAbilityCodes(): string
    {
        $plan = $this->getCurrentPlan();
        if ($plan !== null) {
            return $plan->getAbilityCodes();
        }

        return implode(', ', array_filter(array_column($this->decodePlanList('plan_abilities'), 'code')));
    }

    /**
     * Habilidades do plano: as do plano atual ou, se ele foi apagado, as da cópia.
     *
     * @return int[]
     */
    public function getPlanAbilityIds(): array
    {
        $plan = $this->getCurrentPlan();
        if ($plan !== null) {
            $ids = [];
            foreach ($plan->abilities as $ability) {
                $ids[] = (int) $ability->ability_fk;
            }
            return $ids;
        }

        return array_map('intval', array_column($this->decodePlanList('plan_abilities'), 'id'));
    }

    private function decodePlanList(string $attribute): array
    {
        $value = $this->$attribute;
        if ($value === null || $value === '') {
            return [];
        }

        $decoded = CJSON::decode($value);
        return is_array($decoded) ? $decoded : [];
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

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
