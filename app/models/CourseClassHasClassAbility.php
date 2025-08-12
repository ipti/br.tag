<?php

/**
 * This is the model class for table "course_class_has_class_ability".
 *
 * The followings are the available columns in table 'course_class_has_class_ability':
 * @property int $id
 * @property int $course_class_fk
 * @property int $course_class_ability_fk
 *
 * The followings are the available model relations:
 * @property CourseClass $courseClassFk
 * @property CourseClassAbilities $courseClassAbilityFk
 */
class CourseClassHasClassAbility extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'course_class_has_class_ability';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['course_class_fk, course_class_ability_fk', 'required'],
            ['course_class_fk, course_class_ability_fk', 'numerical', 'integerOnly' => true],
            // The following rule is used by search().
            ['id, course_class_fk, course_class_ability_fk', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'courseClassFk' => [self::BELONGS_TO, 'CourseClass', 'course_class_fk'],
            'courseClassAbilityFk' => [self::BELONGS_TO, 'CourseClassAbilities', 'course_class_ability_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'course_class_fk' => 'Course Class Fk',
            'course_class_ability_fk' => 'Course Class Ability Fk',
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions
     */
    public function search()
    {
        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('course_class_fk', $this->course_class_fk);
        $criteria->compare('course_class_ability_fk', $this->course_class_ability_fk);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name
     * @return CourseClassHasClassAbility the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
