<?php

/**
 * This is the model class for table "student_aee_record".
 *
 * The followings are the available columns in table 'student_aee_record':
 * @property integer $id
 * @property string $date
 * @property string $learning_needs
 * @property string $characterization
 * @property integer $student_fk
 * @property string $school_fk
 * @property integer $classroom_fk
 * @property integer $instructor_fk
 *
 * The followings are the available model relations:
 * @property Classroom $classroomFk
 * @property InstructorIdentification $instructorFk
 * @property SchoolIdentification $schoolFk
 * @property StudentIdentification $studentFk
 */
class StudentAeeRecord extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'student_aee_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('student_fk, school_fk, classroom_fk, instructor_fk', 'required'),
			array('student_fk, classroom_fk, instructor_fk', 'numerical', 'integerOnly'=>true),
			array('learning_needs', 'length', 'max'=>500),
			array('characterization', 'length', 'max'=>1000),
			array('school_fk', 'length', 'max'=>8),
			array('date', 'safe'),
			// The following rule is used by search().
			array('id, date, learning_needs, characterization, student_fk, school_fk, classroom_fk, instructor_fk', 'safe', 'on'=>'search'),
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
			'classroomFk' => array(self::BELONGS_TO, 'Classroom', 'classroom_fk'),
			'instructorFk' => array(self::BELONGS_TO, 'InstructorIdentification', 'instructor_fk'),
			'schoolFk' => array(self::BELONGS_TO, 'SchoolIdentification', 'school_fk'),
			'studentFk' => array(self::BELONGS_TO, 'StudentIdentification', 'student_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date' => 'Date',
			'learning_needs' => 'Learning Needs',
			'characterization' => 'Characterization',
			'student_fk' => 'Student Fk',
			'school_fk' => 'School Fk',
			'classroom_fk' => 'Classroom Fk',
			'instructor_fk' => 'Instructor Fk',
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

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.date', $this->date, true);
        $criteria->compare('t.learning_needs', $this->learning_needs, true);
        $criteria->compare('t.characterization', $this->characterization, true);
        $criteria->compare('t.student_fk', $this->student_fk);
        $criteria->compare('t.school_fk', $this->school_fk, true);
        $criteria->compare('t.classroom_fk', $this->classroom_fk);
        $criteria->compare('t.instructor_fk', $this->instructor_fk);

        // Join com a tabela student_identification
        $criteria->with = array(
            'studentFk' => array('alias' => 'student'),
            'classroomFk' => array('alias' => 'classroom'),
            'instructorFk' => array('alias' => 'instructor')
        );

        $criteria->compare('student.name', $this->studentName, true);
        $criteria->compare('classroom.name', $this->classroomName, true);

        // Adicionando a condição para instructor.user_fk
        $loggedUser = Yii::app()->user->loginInfos->id;
        $criteria->compare('instructor.users_fk', $loggedUser);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StudentAeeRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
