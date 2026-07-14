<?php

/**
 * ActiveRecord for table macete_lesson_plan_section.
 */
class MaceteLessonPlanSection extends TagModel
{
    public const TYPE_YEAR_CONTEXT = 'YEAR_CONTEXT';
    public const TYPE_METHODOLOGY_INVOLVE = 'METHODOLOGY_INVOLVE';
    public const TYPE_METHODOLOGY_INVESTIGATE = 'METHODOLOGY_INVESTIGATE';
    public const TYPE_METHODOLOGY_ACT = 'METHODOLOGY_ACT';
    public const TYPE_LEARNING_OBJECTIVE = 'LEARNING_OBJECTIVE';
    public const TYPE_ADAPTATION_NEURODIVERGENT = 'ADAPTATION_NEURODIVERGENT';
    public const TYPE_ADAPTATION_RECOVERY = 'ADAPTATION_RECOVERY';
    public const TYPE_ADAPTATION_MULTIGRADE = 'ADAPTATION_MULTIGRADE';
    public const TYPE_ADAPTATION_MISSING_MATERIAL = 'ADAPTATION_MISSING_MATERIAL';

    public function tableName()
    {
        return 'macete_lesson_plan_section';
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
            ['lesson_plan_fk, section_type', 'required'],
            ['lesson_plan_fk, position', 'numerical', 'integerOnly' => true],
            ['section_type, target_group', 'length', 'max' => 50],
            ['title', 'length', 'max' => 150],
            ['content, created_at, updated_at', 'safe'],
            ['id, lesson_plan_fk, section_type, title, target_group, content, position, created_at, updated_at', 'safe', 'on' => 'search'],
        ];
    }

    public function relations()
    {
        return [
            'lessonPlanFk' => [self::BELONGS_TO, 'MaceteLessonPlan', 'lesson_plan_fk'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lesson_plan_fk' => 'Plano MACETE',
            'section_type' => 'Tipo de seção',
            'title' => 'Título',
            'target_group' => 'Etapa',
            'content' => 'Conteúdo',
            'position' => 'Ordem',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }

    public static function sectionLabels(): array
    {
        return [
            self::TYPE_YEAR_CONTEXT => 'Texto por etapa',
            self::TYPE_METHODOLOGY_INVOLVE => 'Envolver',
            self::TYPE_METHODOLOGY_INVESTIGATE => 'Investigar',
            self::TYPE_METHODOLOGY_ACT => 'Agir',
            self::TYPE_LEARNING_OBJECTIVE => 'Objetivos de aprendizagem',
            self::TYPE_ADAPTATION_NEURODIVERGENT => 'Adaptação para crianças neurodivergentes',
            self::TYPE_ADAPTATION_RECOVERY => 'Recomposição de aprendizagem',
            self::TYPE_ADAPTATION_MULTIGRADE => 'Turma multisseriada',
            self::TYPE_ADAPTATION_MISSING_MATERIAL => 'Caso falte material',
        ];
    }

    public static function targetGroupLabels(): array
    {
        return [
            'general' => 'Geral',
        ];
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
