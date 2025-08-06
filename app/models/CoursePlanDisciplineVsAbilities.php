<?php

/**
 * This is the model class for table "course_plan_discipline_vs_abilities".
 *
 * The followings are the available columns in table 'course_plan_discipline_vs_abilities':
 *
 * @property int    $id
 * @property int    $course_plan_fk
 * @property int    $discipline_fk
 * @property int    $course_class_fk
 * @property int    $ability_fk
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property CourseClassAbilities $abilityFk
 * @property CourseClass          $courseClassFk
 * @property CoursePlan           $coursePlanFk
 * @property EdcensoDiscipline    $disciplineFk
 */
class CoursePlanDisciplineVsAbilities extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'course_plan_discipline_vs_abilities';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['course_plan_fk, discipline_fk, course_class_fk, ability_fk', 'required'],
            ['course_plan_fk, discipline_fk, course_class_fk, ability_fk', 'numerical', 'integerOnly'=>true],
            ['created_at, updated_at', 'safe'],
            // The following rule is used by search().
            ['id, course_plan_fk, discipline_fk, course_class_fk, ability_fk, created_at, updated_at', 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'abilityFk'     => [self::BELONGS_TO, 'CourseClassAbilities', 'ability_fk'],
            'courseClassFk' => [self::BELONGS_TO, 'CourseClass', 'course_class_fk'],
            'coursePlanFk'  => [self::BELONGS_TO, 'CoursePlan', 'course_plan_fk'],
            'disciplineFk'  => [self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'              => 'ID',
            'course_plan_fk'  => 'Course Plan Fk',
            'discipline_fk'   => 'Discipline Fk',
            'course_class_fk' => 'Course Class Fk',
            'ability_fk'      => 'Ability Fk',
            'created_at'      => 'Created At',
            'updated_at'      => 'Updated At',
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
     *                             based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('course_plan_fk', $this->course_plan_fk);
        $criteria->compare('discipline_fk', $this->discipline_fk);
        $criteria->compare('course_class_fk', $this->course_class_fk);
        $criteria->compare('ability_fk', $this->ability_fk);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at, true);

        return new CActiveDataProvider($this, [
            'criteria'=> $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className active record class name.
     *
     * @return CoursePlanDisciplineVsAbilities the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
