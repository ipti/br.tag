<?php

/**
 * Habilidade registrada numa aula ministrada (class_contents).
 *
 * É uma cópia das habilidades da aula do plano no momento do registro: continua
 * existindo mesmo que a aula do plano seja alterada ou apagada.
 *
 * @property integer $id
 * @property integer $class_contents_fk
 * @property integer $course_class_ability_fk
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ClassContents $classContentsFk
 * @property CourseClassAbilities $courseClassAbilityFk
 */
class ClassContentsAbility extends TagModel
{
    public function tableName()
    {
        return 'class_contents_ability';
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
            ['class_contents_fk, course_class_ability_fk', 'required'],
            ['class_contents_fk, course_class_ability_fk', 'numerical', 'integerOnly' => true],
            ['created_at, updated_at', 'safe'],
        ];
    }

    public function relations()
    {
        return [
            'classContentsFk' => [self::BELONGS_TO, ClassContents::class, 'class_contents_fk'],
            'courseClassAbilityFk' => [self::BELONGS_TO, CourseClassAbilities::class, 'course_class_ability_fk'],
        ];
    }

    /**
     * @return ClassContentsAbility
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
