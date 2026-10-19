<?php

/**
 * ActiveRecord for table macete_lesson_plan_template_stage.
 *
 * @property integer $lesson_plan_template_fk
 * @property integer $edcenso_stage_vs_modality_fk
 * @property integer $edcenso_discipline_fk
 */
class MaceteLessonPlanTemplateStage extends TagModel
{
    public function tableName()
    {
        return 'macete_lesson_plan_template_stage';
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
            ['lesson_plan_template_fk, edcenso_stage_vs_modality_fk, edcenso_discipline_fk', 'required'],
            ['lesson_plan_template_fk, edcenso_stage_vs_modality_fk, edcenso_discipline_fk', 'numerical', 'integerOnly' => true],
            ['id, lesson_plan_template_fk, edcenso_stage_vs_modality_fk, edcenso_discipline_fk, created_at, updated_at', 'safe', 'on' => 'search'],
        ];
    }

    public function relations()
    {
        return [
            'lessonPlanTemplateFk' => [self::BELONGS_TO, 'MaceteLessonPlanTemplate', 'lesson_plan_template_fk'],
            'stageFk' => [self::BELONGS_TO, 'EdcensoStageVsModality', 'edcenso_stage_vs_modality_fk'],
            'disciplineFk' => [self::BELONGS_TO, 'EdcensoDiscipline', 'edcenso_discipline_fk'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lesson_plan_template_fk' => 'Modelo de plano MACETE',
            'edcenso_stage_vs_modality_fk' => 'Etapa',
            'edcenso_discipline_fk' => 'Componente curricular',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
