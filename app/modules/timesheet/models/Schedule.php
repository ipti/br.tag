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
 * @property integer $year
 * @property integer $week
 * @property integer $week_day
 * @property integer $schedule
 * @property string $turn
 * @property integer $unavailable
 * @property string $diary
 * @property string $fkid
 * @property string $hash
 * @property integer $substitute_instructor_fk
 *
 * The followings are the available model relations:
 * @property ClassContents[] $classContents
 * @property ClassDiaries[] $classDiaries
 * @property ClassFaults[] $classFaults
 * @property InstructorFaults[] $instructorFaults
 * @property Classroom $classroomFk
 * @property EdcensoDiscipline $disciplineFk
 * @property SubstituteInstructor $substituteInstructorFk
 * @property InstructorIdentification $instructorFk
 */
class Schedule extends TagModel
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
        return array(
            array('discipline_fk, classroom_fk, day, month, year, week, week_day, unavailable', 'required'),
            array('instructor_fk, discipline_fk, classroom_fk, day, month, year, week, week_day, schedule, unavailable', 'numerical', 'integerOnly'=>true),
            array('fkid', 'length', 'max'=>40),
            array('turn', 'length', 'max'=>45),
            array('hash', 'length', 'max'=>20),
            array('diary', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, instructor_fk, discipline_fk, classroom_fk, day, month, year, week, week_day, schedule, turn, unavailable, diary, fkid, hash', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'classContents' => array(self::HAS_MANY, 'ClassContents', 'schedule_fk'),
            'classDiaries' => array(self::HAS_MANY, 'ClassDiaries', 'schedule_fk'),
            'classFaults' => array(self::HAS_MANY, 'ClassFaults', 'schedule_fk'),
            'instructorFaults' => array(self::HAS_MANY, 'InstructorFaults', 'schedule_fk'),
            'classroomFk' => array(self::BELONGS_TO, 'Classroom', 'classroom_fk'),
            'disciplineFk' => array(self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_fk'),
            'substituteInstructorFk' => array(self::BELONGS_TO, 'SubstituteInstructor', 'substitute_instructor_fk'),
            'instructorFk' => array(self::BELONGS_TO, 'InstructorIdentification', 'instructor_fk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'instructor_fk' => 'Instructor Fk',
            'discipline_fk' => 'Discipline Fk',
            'classroom_fk' => 'Classroom Fk',
            'day' => 'Day',
            'month' => 'Month',
            'year' => 'Year',
            'week' => 'Week',
            'week_day' => 'Week Day',
            'schedule' => 'Schedule',
            'turn' => 'Turn',
            'unavailable' => 'Unavailable',
            'diary' => 'Diary',
            'fkid' => 'Fkid',
            'hash' => 'Hash',
            'substitute_instructor_fk' => 'Substitute Instructor Fk',
        );
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

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('instructor_fk',$this->instructor_fk);
        $criteria->compare('discipline_fk',$this->discipline_fk);
        $criteria->compare('classroom_fk',$this->classroom_fk);
        $criteria->compare('day',$this->day);
        $criteria->compare('month',$this->month);
        $criteria->compare('year',$this->year);
        $criteria->compare('week',$this->week);
        $criteria->compare('week_day',$this->week_day);
        $criteria->compare('schedule',$this->schedule);
        $criteria->compare('turn',$this->turn);
        $criteria->compare('unavailable',$this->unavailable);
        $criteria->compare('diary',$this->diary,true);
        $criteria->compare('fkid',$this->fkid,true);
        $criteria->compare('hash',$this->hash,true);
        $criteria->compare('substitute_instructor_fk',$this->substitute_instructor_fk);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public function getInitialHour(){

    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Schedule the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function weekDays(){
        return [
            0 => yii::t('timesheetModule.instructors', 'Sunday'),
            1 => yii::t('timesheetModule.instructors', 'Monday'),
            2 => yii::t('timesheetModule.instructors', 'Tuesday'),
            3 => yii::t('timesheetModule.instructors', 'Wednesday'),
            4 => yii::t('timesheetModule.instructors', 'Thursday'),
            5 => yii::t('timesheetModule.instructors', 'Friday'),
            6 => yii::t('timesheetModule.instructors', 'Saturday'),
        ];
    }
}
