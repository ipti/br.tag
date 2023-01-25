<?php

    /**
     * This is the model class for table "instructor_disciplines".
     *
     * The followings are the available columns in table 'instructor_disciplines':
     * @property integer $id
     * @property integer $stage_vs_modality_fk
     * @property integer $discipline_fk
     * @property integer $instructor_fk
     *
     * The followings are the available model relations:
     * @property EdcensoDiscipline $disciplineFk
     * @property TimesheetInstructor $instructorFk
     * @property EdcensoStageVsModality $stageVsModalityFk
     */
    class InstructorDisciplines extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'instructor_disciplines';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return [
                ['stage_vs_modality_fk, discipline_fk, instructor_fk', 'required'],
                ['stage_vs_modality_fk, discipline_fk, instructor_fk', 'numerical', 'integerOnly' => true],
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                ['id, stage_vs_modality_fk, discipline_fk, instructor_fk', 'safe', 'on' => 'search'],
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
                'disciplineFk' => [self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_fk'],
                'instructorFk' => [self::BELONGS_TO, 'TimesheetInstructor', 'instructor_fk'],
                'stageVsModalityFk' => [self::BELONGS_TO, 'EdcensoStageVsModality', 'stage_vs_modality_fk'],
            ];
        }

        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return [
                'id' => yii::t('timesheetModule.labels', 'ID'),
                'stage_vs_modality_fk' => yii::t('timesheetModule.labels', 'Stage Vs Modality'),
                'discipline_fk' => yii::t('timesheetModule.labels', 'Discipline'),
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
        public function search()
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $criteria = new CDbCriteria();

            $criteria->compare('id', $this->id);
            $criteria->compare('stage_vs_modality_fk', $this->stage_vs_modality_fk);
            $criteria->compare('discipline_fk', $this->discipline_fk);
            $criteria->compare('instructor_fk', $this->instructor_fk);

            return new CActiveDataProvider($this, [
                'criteria' => $criteria,
            ]);
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         * @param string $className active record class name.
         * @return InstructorDisciplines the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
