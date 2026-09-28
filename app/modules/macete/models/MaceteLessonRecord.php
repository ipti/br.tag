<?php

/**
 * ActiveRecord for table macete_lesson_record.
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
            ['lesson_plan_fk, school_inep_fk, classroom_fk, edcenso_stage_vs_modality_fk, users_fk, lesson_date, executed_content', 'required'],
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
