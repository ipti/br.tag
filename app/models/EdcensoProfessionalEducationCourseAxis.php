<?php

/**
 * This is the model class for table "edcenso_professional_education_course_axis".
 *
 * The followings are the available columns in table 'edcenso_professional_education_course_axis':
 * @property integer $id
 * @property string  $name
 *
 * The followings are the available model relations:
 * @property EdcensoProfessionalEducationCourse[] $courses
 */
class EdcensoProfessionalEducationCourseAxis extends TagModel
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'edcenso_professional_education_course_axis';
    }

    public function rules()
    {
        return [
            ['id, name', 'required'],
            ['id', 'numerical', 'integerOnly' => true],
            ['name', 'length', 'max' => 100],
        ];
    }

    public function relations()
    {
        return [
            'courses' => [self::HAS_MANY, 'EdcensoProfessionalEducationCourse', 'axis_id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'   => Yii::t('default', 'ID'),
            'name' => Yii::t('default', 'Name'),
        ];
    }
}
