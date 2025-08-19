<?php

	/**
	 * This is the model class for table "unavailability".
	 *
	 * The followings are the available columns in table 'unavailability':
	 * @property integer $id
	 * @property integer $instructor_school_fk
	 * @property integer $week_day
	 * @property string $schedule
	 * @property string $turn
	 *
	 * The followings are the available model relations:
	 * @property InstructorIdentification $instructorSchoolFk
	 */
	class Unavailability extends TagModel {
		/**
		 * @return string the associated database table name
		 */
		public function tableName() {
			return 'unavailability';
		}


		/**
		 * @return array validation rules for model attributes.
		 */
		public function rules() {
			// NOTE: you should only define rules for those attributes that
			// will receive user inputs.
			return [
				['instructor_school_fk, week_day, schedule, turn', 'required'],
				['instructor_school_fk, week_day, schedule, turn', 'numerical', 'integerOnly' => TRUE],
				// The following rule is used by search().
				// @todo Please remove those attributes that should not be searched.
				['id, instructor_school_fk, week_day, schedule, turn', 'safe', 'on' => 'search'],
			];
		}

		/**
		 * @return array relational rules.
		 */
		public function relations() {
			// NOTE: you may need to adjust the relation name and the related
			// class name for the relations automatically generated below.
			return [
				'instructorSchoolFk' => [self::BELONGS_TO, 'InstructorIdentification', 'instructor_school_fk'],
			];
		}

		/**
		 * @return array customized attribute labels (name=>label)
		 */
		public function attributeLabels() {
			return [
				'id' => yii::t('timesheetModule.labels','ID'),
				'instructor_school_fk' => yii::t('timesheetModule.labels','Instructor'),
				'week_day' => yii::t('timesheetModule.labels','Week Day'),
				'schedule' => yii::t('timesheetModule.labels','Schedule'),
				'turn' => yii::t('timesheetModule.labels','Turn'),
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
			$criteria->compare('instructor_school_fk', $this->instructor_school_fk);
			$criteria->compare('week_day', $this->week_day);
			$criteria->compare('schedule', $this->schedule, TRUE);
			$criteria->compare('turn', $this->turn, TRUE);

			return new CActiveDataProvider($this, [
				'criteria' => $criteria,
			]);
		}

		/**
		 * Returns the static model of the specified AR class.
		 * Please note that you should have this exact method in all your CActiveRecord descendants!
		 * @param string $className active record class name.
		 * @return Unavailability the static model class
		 */
		public static function model($className = __CLASS__) {
			return parent::model($className);
		}
	}
