<?php

	/**
	 * This is the model class for table "instructor_school".
	 *
	 * The followings are the available columns in table 'instructor_school':
	 * @property integer $id
	 * @property string $school_fk
	 * @property integer $instructor_fk
	 *
	 * The followings are the available model relations:
	 * @property InstructorIdentification $instructorFk
	 * @property SchoolIdentification $schoolFk
	 */
	class InstructorSchool extends CActiveRecord {
		/**
		 * @return string the associated database table name
		 */
		public function tableName() {
			return 'instructor_school';
		}

		/**
		 * @return array validation rules for model attributes.
		 */
		public function rules() {
			// NOTE: you should only define rules for those attributes that
			// will receive user inputs.
			return [
				['school_fk, instructor_fk', 'required'], ['instructor_fk', 'numerical', 'integerOnly' => TRUE],
				['school_fk', 'length', 'max' => 8], // The following rule is used by search().
				// @todo Please remove those attributes that should not be searched.
				['id, school_fk, instructor_fk', 'safe', 'on' => 'search'],
			];
		}

		/**
		 * @return array relational rules.
		 */
		public function relations() {
			// NOTE: you may need to adjust the relation name and the related
			// class name for the relations automatically generated below.
			return [
				'instructorFk' => [self::BELONGS_TO, 'TimesheetInstructor', 'instructor_fk'],
				'schoolFk' => [self::BELONGS_TO, 'SchoolIdentification', 'school_fk'],
			];
		}

		/**
		 * @return array customized attribute labels (name=>label)
		 */
		public function attributeLabels() {
			return [
				'id' => 'ID', 'school_fk' => yii::t('timesheetModule.labels', 'School'),
				'instructor_fk' => yii::t('timesheetModule.labels', 'Instructor'),
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
		public function search() {
			// @todo Please modify the following code to remove attributes that should not be searched.

			$criteria = new CDbCriteria;

			$criteria->compare('id', $this->id);
			$criteria->compare('school_fk', $this->school_fk, TRUE);
			$criteria->compare('instructor_fk', $this->instructor_fk);

			return new CActiveDataProvider($this, [
				'criteria' => $criteria,
			]);
		}

		/**
		 * Returns the static model of the specified AR class.
		 * Please note that you should have this exact method in all your CActiveRecord descendants!
		 * @param string $className active record class name.
		 * @return InstructorSchool the static model class
		 */
		public static function model($className = __CLASS__) {
			return parent::model($className);
		}

		public function getName() {
			return $this->instructorFk->name;
		}

		public function getDisciplines() {

			return $this->instructorFk->disciplines;
		}

		public function getStages() {
			return $this->instructorFk->stages;
		}
	}
