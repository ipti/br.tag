<?php

/**
 * This is the model class for table "class_contents".
 *
 * The followings are the available columns in table 'class_contents':
 * @property integer $id
 * @property integer $schedule_fk
 * @property integer $course_class_fk
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $discipline_fk
 * @property integer $day
 * @property integer $month
 * @property integer $year
 * @property integer $classroom_fk
 * @property string $fkid
 *
 * The followings are the available model relations:
 * @property Schedule $scheduleFk
 * @property CourseClass $courseClassFk
 */
class ClassContents extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'class_contents';
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
            ]
        ];
    }
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('schedule_fk, course_class_fk', 'required'),
            array('schedule_fk, course_class_fk, day, month, year, classroom_fk, discipline_fk', 'numerical', 'integerOnly' => true),
            array('fkid', 'length', 'max' => 40),
            array('created_at, updated_at', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, schedule_fk, course_class_fk, fkid, created_at, updated_at, day, month, year, classroom_fk, discipline_fk', 'safe', 'on' => 'search'),
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
            'courseClassFk' => array(self::BELONGS_TO, CourseClass::class, 'course_class_fk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'schedule_fk' => 'Schedule Fk',
            'course_class_fk' => 'Course Class Fk',
            'fkid' => 'Fkid',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'day' => 'Day',
            'month' => 'Month',
            'year' => 'Year',
            'classroom_fk' => 'Classroom Fk',
            'discipline_fk' => 'Discipline Fk',
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('schedule_fk', $this->schedule_fk);
        $criteria->compare('course_class_fk', $this->course_class_fk);
        $criteria->compare('fkid', $this->fkid, true);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('day', $this->day);
        $criteria->compare('month', $this->month);
        $criteria->compare('year', $this->year);
        $criteria->compare('classroom_fk', $this->classroom_fk);
        $criteria->compare('discipline_fk', $this->discipline_fk);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ClassContents the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getTotalClassesByMonth($classroomId, $month, $year, $disciplineId)
    {
        if (!$disciplineId) {
            return Yii::app()->db->createCommand(
                "select count(*) from schedule sc
                where sc.year = :year and sc.month = :month and sc.classroom_fk = :classroom
                and sc.unavailable = 0"
            )
                ->bindParam(":classroom", $classroomId)
                ->bindParam(":month", $month)
                ->bindParam(":year", $year)
                ->queryScalar();
        }
        return Yii::app()->db->createCommand(
            "select count(*) from schedule sc
            where sc.year = :year and sc.month = :month and sc.classroom_fk = :classroom
            and sc.discipline_fk = :discipline and sc.unavailable = 0"
        )
            ->bindParam(":classroom", $classroomId)
            ->bindParam(":month", $month)
            ->bindParam(":year", $year)
            ->bindParam(":discipline", $disciplineId)
            ->queryScalar();
    }

    public function getTotalClassesMinorStage($classroomId): int
    {
        return Yii::app()->db->createCommand(
            "select COUNT(DISTINCT CONCAT(sc.`month`, '-', sc.`day`)) from schedule sc
                where sc.classroom_fk = :classroom
                and sc.unavailable = 0"
        )
            ->bindParam(":classroom", $classroomId)
            ->queryScalar();

    }
    public function getTotalClassesByClassroomAndDiscipline($classroomId, $disciplineId): int
    {
        return Yii::app()->db->createCommand(
            "select count(*) from class_contents cc
            join schedule sc on sc.id = cc.schedule_fk
            where sc.classroom_fk = :classroom
            and sc.discipline_fk = :discipline and sc.unavailable = 0"
        )
            ->bindParam(":classroom", $classroomId)
            ->bindParam(":discipline", $disciplineId)
            ->queryScalar();

    }

    public function getTotalClassContentsByMonth($classroomId, $month, $year, $disciplineId)
    {
        if (!$disciplineId) {
            return Yii::app()->db->createCommand(
                "select count(*) from class_contents cc
                join schedule sc on sc.id = cc.schedule_fk
                where sc.year = :year and sc.month = :month and sc.classroom_fk = :classroom
                and sc.unavailable = 0"
            )
                ->bindParam(":classroom", $classroomId)
                ->bindParam(":month", $month)
                ->bindParam(":year", $year)
                ->queryScalar();
        }
        return Yii::app()->db->createCommand(
            "select count(*) from class_contents cc
            join schedule sc on sc.id = cc.schedule_fk
            where sc.year = :year and sc.month = :month and sc.classroom_fk = :classroom
            and sc.discipline_fk = :discipline and sc.unavailable = 0"
        )
            ->bindParam(":classroom", $classroomId)
            ->bindParam(":month", $month)
            ->bindParam(":year", $year)
            ->bindParam(":discipline", $disciplineId)
            ->queryScalar();
    }

    public function buildClassContents($schedules, $students)
    {
        $classContents = [];
        foreach ($schedules as $schedule) {
            $scheduleDate = date("Y-m-d", mktime(0, 0, 0, $schedule->month, $schedule->day, $schedule->year));
            $classContents[$schedule->day]["available"] = date("Y-m-d") >= $scheduleDate;
            $classContents[$schedule->day]["diary"] = $schedule->diary !== null ? $schedule->diary : "";
            $classContents[$schedule->day]["students"] = [];

            $studentArray = StudentEnrollment::model()->getStudentClassAnottations($schedule, $students);
            array_push($classContents[$schedule->day]["students"], $studentArray);

            $courseClasses = [];
            foreach ($schedule->classContents as $classContent) {
                if (TagUtils::isInstructor() && $classContent->courseClassFk->coursePlanFk->users_fk != Yii::app()->user->loginInfos->id) {
                    continue;
                }

                if (!isset($classContents[$schedule->day]["contents"])) {
                    $classContents[$schedule->day]["contents"] = [];
                }
                $courseClasses["order"] = $classContent->courseClassFk->order;
                $courseClasses["name"] = $classContent->courseClassFk->coursePlanFk->name;
                $courseClasses["content"] = $classContent->courseClassFk->content;
                $courseClasses["abilities"] = [];

                $hasClassAbilities = CourseClassHasClassAbility::model()->findAll(
                    'course_class_fk = :courseClassId',
                    array(':courseClassId' => $classContent->courseClassFk->id)
                );

                foreach ($hasClassAbilities as $classAbility) {
                    $abilityData = CourseClassAbilities::model()->find(
                        'id = :courseClassAbilityId',
                        array(':courseClassAbilityId' => $classAbility->course_class_ability_fk)
                    );

                    $ability = [];

                    $ability["code"] = $abilityData->code;
                    $ability["description"] = $abilityData->description;

                    array_push($courseClasses["abilities"], $ability);
                }

                array_push($classContents[$schedule->day]["contents"], $courseClasses);
            }
        }

        return $classContents;
    }
}
