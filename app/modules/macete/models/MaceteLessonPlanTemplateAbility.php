<?php

/**
 * ActiveRecord for table macete_lesson_plan_template_ability.
 */
class MaceteLessonPlanTemplateAbility extends TagModel
{
    public function tableName()
    {
        return 'macete_lesson_plan_template_ability';
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
            ['lesson_plan_template_fk, ability_fk', 'required'],
            ['lesson_plan_template_fk, ability_fk', 'numerical', 'integerOnly' => true],
            ['created_at, updated_at', 'safe'],
            ['id, lesson_plan_template_fk, ability_fk, created_at, updated_at', 'safe', 'on' => 'search'],
        ];
    }

    public function relations()
    {
        return [
            'lessonPlanTemplateFk' => [self::BELONGS_TO, 'MaceteLessonPlanTemplate', 'lesson_plan_template_fk'],
            'abilityFk' => [self::BELONGS_TO, 'CourseClassAbilities', 'ability_fk'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lesson_plan_template_fk' => 'Modelo de plano MACETE',
            'ability_fk' => 'Habilidade BNCC',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
