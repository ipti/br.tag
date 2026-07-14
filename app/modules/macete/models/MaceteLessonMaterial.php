<?php

/**
 * ActiveRecord for table macete_lesson_material.
 */
class MaceteLessonMaterial extends TagModel
{
    public const TYPE_ACTIVITY_SHEET = 'ACTIVITY_SHEET';
    public const TYPE_GAME = 'GAME';
    public const TYPE_SUPPORT_MATERIAL = 'SUPPORT_MATERIAL';

    public function tableName()
    {
        return 'macete_lesson_material';
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
            ['lesson_plan_fk, title, material_type', 'required'],
            ['lesson_plan_fk', 'numerical', 'integerOnly' => true],
            ['title', 'length', 'max' => 150],
            ['material_type', 'length', 'max' => 30],
            ['file_path', 'length', 'max' => 255],
            ['description, created_at, updated_at', 'safe'],
            ['id, lesson_plan_fk, title, material_type, description, file_path, created_at, updated_at', 'safe', 'on' => 'search'],
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
            'title' => 'Título',
            'material_type' => 'Tipo',
            'description' => 'Descrição',
            'file_path' => 'Arquivo',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }

    public static function typeLabels(): array
    {
        return [
            self::TYPE_ACTIVITY_SHEET => 'Ficha de atividade',
            self::TYPE_GAME => 'Jogo pedagógico',
            self::TYPE_SUPPORT_MATERIAL => 'Material de apoio',
        ];
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
