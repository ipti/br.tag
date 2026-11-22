<?php

/**
 * Aluno presente em uma presença do dia para a merenda.
 *
 * @property int $id
 * @property int $school_meal_attendance_fk
 * @property int $student_fk
 * @property string $created_at
 */
class SchoolMealAttendanceStudent extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'school_meal_attendance_student';
    }

    public function rules()
    {
        return [
            ['school_meal_attendance_fk, student_fk', 'required'],
            ['school_meal_attendance_fk, student_fk', 'numerical', 'integerOnly' => true],
        ];
    }
}
