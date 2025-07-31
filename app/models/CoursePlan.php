<?php

/**
 * This is the model class for table "course_plan".
 *
 * The followings are the available columns in table 'course_plan':
 * @property integer $id
 * @property string $name
 * @property string $school_inep_fk
 * @property integer $modality_fk
 * @property integer $discipline_fk
 * @property integer $users_fk
 * @property string $creation_date
 * @property string $fkid
 * @property string $situation
 * @property string $start_date
 * @property string $observation
 *
 * The followings are the available model relations:
 * @property ClassroomHasCoursePlan[] $classroomHasCoursePlans
 * @property CourseClass[] $courseClasses
 * @property CourseClassTypes[] $courseClassTypes
 * @property Users $usersFk
 * @property SchoolIdentification $schoolInepFk
 * @property EdcensoStageVsModality $modalityFk
 * @property EdcensoDiscipline $disciplineFk
 */
class CoursePlan extends TagModel
{
    public const STATUS_PENNDING = 'PENDENTE';

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'course_plan';
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
        return [
            ['name, school_inep_fk, modality_fk, situation, start_date', 'required'],
            ['modality_fk, discipline_fk, users_fk', 'numerical', 'integerOnly' => true],
            ['name', 'length', 'max' => 100],
            ['school_inep_fk', 'length', 'max' => 8],
            ['fkid', 'length', 'max' => 40],
            ['situation', 'length', 'max' => 9],
            ['observation', 'length', 'max' => 500],
            ['creation_date', 'safe'],
            // The following rule is used by search().
            ['id, name, school_inep_fk, modality_fk, discipline_fk, users_fk, creation_date, fkid, situation, start_date, observation', 'safe', 'on' => 'search'],
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
            'classroomHasCoursePlans' => [self::HAS_MANY, 'ClassroomHasCoursePlan', 'course_plan_fk'],
            'courseClasses' => [self::HAS_MANY, 'CourseClass', 'course_plan_fk'],
            'courseClassTypes' => [self::HAS_MANY, 'CourseClassTypes', 'course_class_fk'],
            'usersFk' => [self::BELONGS_TO, 'Users', 'users_fk'],
            'schoolInepFk' => [self::BELONGS_TO, 'SchoolIdentification', 'school_inep_fk'],
            'modalityFk' => [self::BELONGS_TO, 'EdcensoStageVsModality', 'modality_fk'],
            'disciplineFk' => [self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'school_inep_fk' => 'School Inep Fk',
            'modality_fk' => 'Modality Fk',
            'discipline_fk' => 'Discipline Fk',
            'users_fk' => 'Users Fk',
            'creation_date' => 'Creation Date',
            'fkid' => 'Fkid',
            'situation' => 'Situation',
            'start_date' => 'Start Date',
            'observation' => 'Observation',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('school_inep_fk', $this->school_inep_fk, true);
        $criteria->compare('modality_fk', $this->modality_fk);
        $criteria->compare('discipline_fk', $this->discipline_fk);
        $criteria->compare('users_fk', $this->users_fk);
        $criteria->compare('creation_date', $this->creation_date, true);
        $criteria->compare('fkid', $this->fkid, true);
        $criteria->compare('situation', $this->situation, true);
        $criteria->compare('start_date', $this->start_date, true);
        $criteria->compare('observation', $this->observation, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CoursePlan the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
