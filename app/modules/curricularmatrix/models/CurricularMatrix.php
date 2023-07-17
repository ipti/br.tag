<?php

	/**
	 * This is the model class for table "curricular_matrix".
	 *
	 * The followings are the available columns in table 'curricular_matrix':
	 * @property integer $id
	 * @property integer $stage_fk
	 * @property integer $discipline_fk
	 * @property integer $workload
	 * @property integer $credits
     * @property integer $school_year
	 *
	 * The followings are the available model relations:
	 * @property EdcensoDiscipline $disciplineFk
	 * @property EdcensoStageVsModality $stageFk
     * @property TeachingMatrixes[] $teachingMatrixes
	 */
	class CurricularMatrix extends AltActiveRecord {
		/**
		 * @return string the associated database table name
		 */
		public function tableName() {
			return 'curricular_matrix';
		}

		/**
		 * @return array validation rules for model attributes.
		 */
		public function rules() {
			// NOTE: you should only define rules for those attributes that
			// will receive user inputs.
			return [
				['stage_fk, discipline_fk, workload, credits, school_year', 'required'],
				['stage_fk, discipline_fk, workload, credits, school_year', 'numerical', 'integerOnly' => TRUE],
				// @todo Please remove those attributes that should not be searched.
				['id, stage_fk, discipline_fk, workload, credits, school_year', 'safe', 'on' => 'search'],
			];
		}

		/**
		 * @return array relational rules.
		 */
		public function relations() {
			// NOTE: you may need to adjust the relation name and the related
			// class name for the relations automatically generated below.
			return [
				'disciplineFk' => [self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_fk'],
				'stageFk' => [self::BELONGS_TO, 'EdcensoStageVsModality', 'stage_fk'],
                'teachingMatrixes' => array(self::HAS_MANY, 'TeachingMatrixes', 'curricular_matrix_fk'),
			];
		}

		/**
		 * @return array customized attribute labels (name=>label)
		 */
		public function attributeLabels() {
			return [
				'id' => yii::t('curricularmatrixModule.labels', 'ID'),
				'stage_fk' => yii::t('curricularmatrixModule.labels', 'Stage Fk'),
				'discipline_fk' => yii::t('curricularmatrixModule.labels', 'Discipline Fk'),
				'workload' => yii::t('curricularmatrixModule.labels', 'Workload'),
				'credits' => yii::t('curricularmatrixModule.labels', 'Credits'),
                'school_year' => 'School Year',
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
			$criteria->with = array('stageFk', 'disciplineFk');
			$criteria->together = true;
//			$criteria->compare('id', $this->id);
//			$criteria->compare('stage_fk', $this->stage_fk);
//			$criteria->compare('discipline_fk', $this->discipline_fk);
			$criteria->compare('workload', $this->workload, true);
			$criteria->compare('credits', $this->credits, true);

			$criteria->addCondition('stageFk.name like "%' . $this->stage_fk . '%"');
			$criteria->addCondition('disciplineFk.name like "%' . $this->discipline_fk . '%"');
			$criteria->addCondition('school_year = ' . Yii::app()->user->year);

			return new CActiveDataProvider($this, [
				'criteria' => $criteria,
				'pagination' => false
			]);
		}

		/**
		 * Returns the static model of the specified AR class.
		 * Please note that you should have this exact method in all your CActiveRecord descendants!
		 * @param string $className active record class name.
		 * @return CurricularMatrix the static model class
		 */
		public static function model($className = __CLASS__) {
			return parent::model($className);
		}

	}
