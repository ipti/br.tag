<?php

/**
 * This is the model class for table "student_enrollment_history".
 *
 * The followings are the available columns in table 'student_enrollment_history':
 * @property int $id
 * @property int $student_enrollment_fk
 * @property int $status
 * @property string $enrollment_date
 * @property string $transfer_date
 * @property string $class_transfer_date
 * @property string $school_readmission_date
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property StudentEnrollment $studentEnrollmentFk
 */
class StudentEnrollmentHistory extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'student_enrollment_history';
    }

    public function behaviors()
    {
        // Define os comportamentos padrão
        $behaviors = [
            'CTimestampBehavior' => [
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'timestampExpression' => new CDbExpression('CONVERT_TZ(NOW(), "+00:00", "-03:00")'),
            ],
        ];

        return $behaviors;
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['student_enrollment_fk, status', 'required'],
            ['student_enrollment_fk, status', 'numerical', 'integerOnly' => true],
            ['enrollment_date, transfer_date, class_transfer_date, school_readmission_date, created_at', 'safe'],
            // The following rule is used by search().
            ['id, student_enrollment_fk, status, enrollment_date, transfer_date, class_transfer_date, school_readmission_date, created_at', 'safe', 'on' => 'search'],
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
            'studentEnrollmentFk' => [self::BELONGS_TO, 'StudentEnrollment', 'student_enrollment_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_enrollment_fk' => 'Student Enrollment Fk',
            'status' => 'Status',
            'enrollment_date' => 'Enrollment Date',
            'transfer_date' => 'Transfer Date',
            'class_transfer_date' => 'Class Transfer Date',
            'school_readmission_date' => 'School Readmission Date',
            'created_at' => 'Created At',
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
        $criteria->compare('student_enrollment_fk', $this->student_enrollment_fk);
        $criteria->compare('status', $this->status);
        $criteria->compare('enrollment_date', $this->enrollment_date, true);
        $criteria->compare('transfer_date', $this->transfer_date, true);
        $criteria->compare('class_transfer_date', $this->class_transfer_date, true);
        $criteria->compare('school_readmission_date', $this->school_readmission_date, true);
        $criteria->compare('created_at', $this->created_at, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name
     * @return StudentEnrollmentHistory the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
