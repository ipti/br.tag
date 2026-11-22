<?php

/**
 * Presença do dia para a merenda, enviada pelo reconhecimento facial da
 * escola. Uma linha por escola e dia; fica gravada a entrega de maior número.
 *
 * @property int $id
 * @property string $school_inep_fk
 * @property string $date
 * @property int $delivery
 * @property string|null $generated_at
 * @property int $total
 * @property int $reviewed
 * @property int $complete
 * @property int $without_registration
 * @property int|null $api_client_fk
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SchoolMealAttendanceStudent[] $students
 */
class SchoolMealAttendance extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'school_meal_attendance';
    }

    public function rules()
    {
        return [
            ['school_inep_fk, date, delivery, total', 'required'],
            ['school_inep_fk', 'length', 'max' => 8],
            ['date', 'date', 'format' => 'yyyy-MM-dd'],
            ['delivery', 'numerical', 'integerOnly' => true, 'min' => 1],
            ['total, without_registration', 'numerical', 'integerOnly' => true, 'min' => 0],
            ['reviewed, complete', 'boolean'],
            ['api_client_fk', 'numerical', 'integerOnly' => true],
            ['generated_at', 'safe'],
        ];
    }

    public function relations()
    {
        return [
            'students' => [self::HAS_MANY, 'SchoolMealAttendanceStudent', 'school_meal_attendance_fk'],
        ];
    }
}
