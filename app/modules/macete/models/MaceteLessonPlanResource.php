<?php

/**
 * ActiveRecord for table macete_lesson_plan_resource.
 */
class MaceteLessonPlanResource extends TagModel
{
    public const TYPE_MACETE_BOX = 'MACETE_BOX';
    public const TYPE_ADDITIONAL = 'ADDITIONAL';

    public function tableName()
    {
        return 'macete_lesson_plan_resource';
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
            ['lesson_plan_fk, resource_type, name', 'required'],
            ['lesson_plan_fk', 'numerical', 'integerOnly' => true],
            ['resource_type', 'length', 'max' => 30],
            ['name', 'length', 'max' => 150],
            ['amount', 'length', 'max' => 20],
            ['description, created_at, updated_at', 'safe'],
            ['id, lesson_plan_fk, resource_type, name, amount, description, created_at, updated_at', 'safe', 'on' => 'search'],
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
            'resource_type' => 'Tipo',
            'name' => 'Nome',
            'amount' => 'Quantidade',
            'description' => 'Descrição',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }

    public static function typeLabels(): array
    {
        return [
            self::TYPE_MACETE_BOX => 'Caixa MACETE',
            self::TYPE_ADDITIONAL => 'Materiais adicionais',
        ];
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
