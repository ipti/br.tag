<?php

/**
 * ActiveRecord for table macete_lesson_plan_stage.
 */
class MaceteLessonPlanStage extends TagModel
{
    public function tableName()
    {
        return 'macete_lesson_plan_stage';
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
            ['lesson_plan_fk, edcenso_stage_vs_modality_fk', 'required'],
            ['lesson_plan_fk, edcenso_stage_vs_modality_fk', 'numerical', 'integerOnly' => true],
            ['id, lesson_plan_fk, edcenso_stage_vs_modality_fk, created_at, updated_at', 'safe', 'on' => 'search'],
        ];
    }

    public function relations()
    {
        return [
            'lessonPlanFk' => [self::BELONGS_TO, 'MaceteLessonPlan', 'lesson_plan_fk'],
            'stageFk' => [self::BELONGS_TO, 'EdcensoStageVsModality', 'edcenso_stage_vs_modality_fk'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lesson_plan_fk' => 'Plano MACETE',
            'edcenso_stage_vs_modality_fk' => 'Etapa',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
