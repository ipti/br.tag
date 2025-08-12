<?php

/**
 * This is the model class for table "grade_results".
 *
 * The followings are the available columns in table 'grade_results':
 * @property int $id
 * @property float $grade_1
 * @property float $grade_2
 * @property float $grade_3
 * @property float $grade_4
 * @property float $grade_5
 * @property float $grade_6
 * @property float $grade_7
 * @property float $grade_8
 * @property float $rec_partial_1
 * @property float $rec_partial_2
 * @property float $rec_partial_3
 * @property float $rec_partial_4
 * @property float $rec_partial_5
 * @property float $rec_partial_6
 * @property float $rec_partial_7
 * @property float $rec_partial_8
 * @property float $sem_rec_partial_1
 * @property float $sem_rec_partial_2
 * @property float $sem_rec_partial_3
 * @property float $sem_rec_partial_4
 * @property float $rec_final
 * @property float $final_media
 * @property string $grade_concept_1
 * @property string $grade_concept_2
 * @property string $grade_concept_3
 * @property string $grade_concept_4
 * @property string $grade_concept_5
 * @property string $grade_concept_6
 * @property string $grade_concept_7
 * @property string $grade_concept_8
 * @property string $situation
 * @property int $enrollment_fk
 * @property int $discipline_fk
 * @property int $grade_faults_1
 * @property int $grade_faults_2
 * @property int $grade_faults_3
 * @property int $grade_faults_4
 * @property int $grade_faults_5
 * @property int $grade_faults_6
 * @property int $grade_faults_7
 * @property int $grade_faults_8
 * @property int $given_classes_1
 * @property int $given_classes_2
 * @property int $given_classes_3
 * @property int $given_classes_4
 * @property int $given_classes_5
 * @property int $given_classes_6
 * @property int $given_classes_7
 * @property int $given_classes_8
 * @property int $final_concept
 * @property string $created_at
 * @property string $updated_at
 * @property float $sem_avarage_1
 * @property float $sem_avarage_2
 *
 * The followings are the available model relations:
 * @property StudentEnrollment $enrollmentFk
 * @property EdcensoDiscipline $disciplineFk
 */
class GradeResults extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'grade_results';
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
            ],
        ];
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['enrollment_fk, discipline_fk', 'required'],
            ['enrollment_fk, discipline_fk, grade_faults_1, grade_faults_2, grade_faults_3, grade_faults_4, grade_faults_5, grade_faults_6, grade_faults_7, grade_faults_8, given_classes_1, given_classes_2, given_classes_3, given_classes_4, given_classes_5, given_classes_6, given_classes_7, given_classes_8, final_concept', 'numerical', 'integerOnly' => true],
            ['grade_1, grade_2, grade_3, grade_4, grade_5, grade_6, grade_7, grade_8, rec_partial_1, rec_partial_2, rec_partial_3, rec_partial_4, rec_partial_5, rec_partial_6, rec_partial_7, rec_partial_8, sem_rec_partial_1, sem_rec_partial_2, sem_rec_partial_3, sem_rec_partial_4, rec_final, final_media, sem_avarage_1, sem_avarage_2', 'numerical'],
            ['grade_concept_1, grade_concept_2, grade_concept_3, grade_concept_4, grade_concept_5, grade_concept_6, grade_concept_7, grade_concept_8, situation', 'length', 'max' => 50],
            ['created_at, updated_at', 'safe'],
            // The following rule is used by search().
            ['id, grade_1, grade_2, grade_3, grade_4, grade_5, grade_6, grade_7, grade_8, rec_partial_1, rec_partial_2, rec_partial_3, rec_partial_4, rec_partial_5, rec_partial_6, rec_partial_7, rec_partial_8, sem_rec_partial_1, sem_rec_partial_2, sem_rec_partial_3, sem_rec_partial_4, rec_final, final_media, grade_concept_1, grade_concept_2, grade_concept_3, grade_concept_4, grade_concept_5, grade_concept_6, grade_concept_7, grade_concept_8, situation, enrollment_fk, discipline_fk, grade_faults_1, grade_faults_2, grade_faults_3, grade_faults_4, grade_faults_5, grade_faults_6, grade_faults_7, grade_faults_8, given_classes_1, given_classes_2, given_classes_3, given_classes_4, given_classes_5, given_classes_6, given_classes_7, given_classes_8, final_concept, created_at, updated_at, sem_avarage_1, sem_avarage_2', 'safe', 'on' => 'search'],
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
            'enrollmentFk' => [self::BELONGS_TO, 'StudentEnrollment', 'enrollment_fk'],
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
            'grade_1' => 'Grade 1',
            'grade_2' => 'Grade 2',
            'grade_3' => 'Grade 3',
            'grade_4' => 'Grade 4',
            'grade_5' => 'Grade 5',
            'grade_6' => 'Grade 6',
            'grade_7' => 'Grade 7',
            'grade_8' => 'Grade 8',
            'rec_partial_1' => 'Rec Partial 1',
            'rec_partial_2' => 'Rec Partial 2',
            'rec_partial_3' => 'Rec Partial 3',
            'rec_partial_4' => 'Rec Partial 4',
            'rec_partial_5' => 'Rec Partial 5',
            'rec_partial_6' => 'Rec Partial 6',
            'rec_partial_7' => 'Rec Partial 7',
            'rec_partial_8' => 'Rec Partial 8',
            'sem_rec_partial_1' => 'Sem Rec Partial 1',
            'sem_rec_partial_2' => 'Sem Rec Partial 2',
            'sem_rec_partial_3' => 'Sem Rec Partial 3',
            'sem_rec_partial_4' => 'Sem Rec Partial 4',
            'rec_final' => 'Rec Final',
            'final_media' => 'Final Media',
            'grade_concept_1' => 'Grade Concept 1',
            'grade_concept_2' => 'Grade Concept 2',
            'grade_concept_3' => 'Grade Concept 3',
            'grade_concept_4' => 'Grade Concept 4',
            'grade_concept_5' => 'Grade Concept 5',
            'grade_concept_6' => 'Grade Concept 6',
            'grade_concept_7' => 'Grade Concept 7',
            'grade_concept_8' => 'Grade Concept 8',
            'situation' => 'Situation',
            'enrollment_fk' => 'Enrollment Fk',
            'discipline_fk' => 'Discipline Fk',
            'grade_faults_1' => 'Grade Faults 1',
            'grade_faults_2' => 'Grade Faults 2',
            'grade_faults_3' => 'Grade Faults 3',
            'grade_faults_4' => 'Grade Faults 4',
            'grade_faults_5' => 'Grade Faults 5',
            'grade_faults_6' => 'Grade Faults 6',
            'grade_faults_7' => 'Grade Faults 7',
            'grade_faults_8' => 'Grade Faults 8',
            'given_classes_1' => 'Given Classes 1',
            'given_classes_2' => 'Given Classes 2',
            'given_classes_3' => 'Given Classes 3',
            'given_classes_4' => 'Given Classes 4',
            'given_classes_5' => 'Given Classes 5',
            'given_classes_6' => 'Given Classes 6',
            'given_classes_7' => 'Given Classes 7',
            'given_classes_8' => 'Given Classes 8',
            'final_concept' => 'Final Concept',
            'sem_avarage_1' => 'Sem Avarage 1',
            'sem_avarage_2' => 'Sem Avarage 2',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
        $criteria->compare('grade_1', $this->grade_1);
        $criteria->compare('grade_2', $this->grade_2);
        $criteria->compare('grade_3', $this->grade_3);
        $criteria->compare('grade_4', $this->grade_4);
        $criteria->compare('grade_5', $this->grade_5);
        $criteria->compare('grade_6', $this->grade_6);
        $criteria->compare('grade_7', $this->grade_7);
        $criteria->compare('grade_8', $this->grade_8);
        $criteria->compare('rec_partial_1', $this->rec_partial_1);
        $criteria->compare('rec_partial_2', $this->rec_partial_2);
        $criteria->compare('rec_partial_3', $this->rec_partial_3);
        $criteria->compare('rec_partial_4', $this->rec_partial_4);
        $criteria->compare('rec_partial_5', $this->rec_partial_5);
        $criteria->compare('rec_partial_6', $this->rec_partial_6);
        $criteria->compare('rec_partial_7', $this->rec_partial_7);
        $criteria->compare('rec_partial_8', $this->rec_partial_8);
        $criteria->compare('sem_rec_partial_1', $this->sem_rec_partial_1);
        $criteria->compare('sem_rec_partial_2', $this->sem_rec_partial_2);
        $criteria->compare('sem_rec_partial_3', $this->sem_rec_partial_3);
        $criteria->compare('sem_rec_partial_4', $this->sem_rec_partial_4);
        $criteria->compare('rec_final', $this->rec_final);
        $criteria->compare('final_media', $this->final_media);
        $criteria->compare('grade_concept_1', $this->grade_concept_1, true);
        $criteria->compare('grade_concept_2', $this->grade_concept_2, true);
        $criteria->compare('grade_concept_3', $this->grade_concept_3, true);
        $criteria->compare('grade_concept_4', $this->grade_concept_4, true);
        $criteria->compare('grade_concept_5', $this->grade_concept_5, true);
        $criteria->compare('grade_concept_6', $this->grade_concept_6, true);
        $criteria->compare('grade_concept_7', $this->grade_concept_7, true);
        $criteria->compare('grade_concept_8', $this->grade_concept_8, true);
        $criteria->compare('situation', $this->situation, true);
        $criteria->compare('enrollment_fk', $this->enrollment_fk);
        $criteria->compare('discipline_fk', $this->discipline_fk);
        $criteria->compare('grade_faults_1', $this->grade_faults_1);
        $criteria->compare('grade_faults_2', $this->grade_faults_2);
        $criteria->compare('grade_faults_3', $this->grade_faults_3);
        $criteria->compare('grade_faults_4', $this->grade_faults_4);
        $criteria->compare('grade_faults_5', $this->grade_faults_5);
        $criteria->compare('grade_faults_6', $this->grade_faults_6);
        $criteria->compare('grade_faults_7', $this->grade_faults_7);
        $criteria->compare('grade_faults_8', $this->grade_faults_8);
        $criteria->compare('given_classes_1', $this->given_classes_1);
        $criteria->compare('given_classes_2', $this->given_classes_2);
        $criteria->compare('given_classes_3', $this->given_classes_3);
        $criteria->compare('given_classes_4', $this->given_classes_4);
        $criteria->compare('given_classes_5', $this->given_classes_5);
        $criteria->compare('given_classes_6', $this->given_classes_6);
        $criteria->compare('given_classes_7', $this->given_classes_7);
        $criteria->compare('given_classes_8', $this->given_classes_8);
        $criteria->compare('final_concept', $this->final_concept);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('sem_avarage_1', $this->sem_avarage_1);
        $criteria->compare('sem_avarage_2', $this->sem_avarage_2);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name
     * @return GradeResults the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
