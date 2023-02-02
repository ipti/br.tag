<?php

/**
 * This is the model class for table "schedule".
 *
 * The followings are the available columns in table 'schedule':
 * @property integer $id
 * @property integer $instructor_fk
 * @property integer $discipline_fk
 * @property integer $classroom_fk
 * @property integer $day
 * @property integer $month
 * @property integer $week
 * @property integer $week_day
 * @property integer $schedule
 * @property integer $turn
 * @property integer $unavailable
 *
 * The followings are the available model relations:
 * @property ClassFaults[] $classFaults
 * @property ClassHasContent[] $classHasContents
 * @property Classroom $classroomFk
 * @property EdcensoDiscipline $disciplineFk
 * @property InstructorIdentification $instructorFk
 */
class Schedule extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'schedule';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['discipline_fk, classroom_fk, day, month, week, week_day, unavailable', 'required'],
            ['instructor_fk, discipline_fk, classroom_fk, day, month, week, week_day, schedule, turn, unavailable', 'numerical', 'integerOnly' => true],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, instructor_fk, discipline_fk, classroom_fk, day, month, week, week_day, schedule, turn, unavailable', 'safe', 'on' => 'search'],
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
            'classFaults' => [self::HAS_MANY, 'ClassFaults', 'schedule_fk'],
            'classHasContents' => [self::HAS_MANY, 'ClassHasContent', 'schedule_fk'],
            'classroomFk' => [self::BELONGS_TO, 'Classroom', 'classroom_fk'],
            'disciplineFk' => [self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_fk'],
            'instructorFk' => [self::BELONGS_TO, 'InstructorIdentification', 'instructor_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'instructor_fk' => 'Instructor Fk',
            'discipline_fk' => 'Discipline Fk',
            'classroom_fk' => 'Classroom Fk',
            'day' => 'Day',
            'month' => 'Month',
            'week' => 'Week',
            'week_day' => 'Week Day',
            'schedule' => 'Schedule',
            'turn' => 'Turn',
            'unavailable' => 'Unavailable',
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
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('instructor_fk', $this->instructor_fk);
        $criteria->compare('discipline_fk', $this->discipline_fk);
        $criteria->compare('classroom_fk', $this->classroom_fk);
        $criteria->compare('day', $this->day);
        $criteria->compare('month', $this->month);
        $criteria->compare('week', $this->week);
        $criteria->compare('week_day', $this->week_day);
        $criteria->compare('schedule', $this->schedule);
        $criteria->compare('turn', $this->turn);
        $criteria->compare('unavailable', $this->unavailable);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Schedule the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
