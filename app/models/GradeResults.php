<?php

/**
 * This is the model class for table "grade_results".
 *
 * The followings are the available columns in table 'grade_results':
 * @property integer $id
 * @property double $grade_1
 * @property double $grade_2
 * @property double $grade_3
 * @property double $grade_4
 * @property double $grade_5
 * @property double $grade_6
 * @property double $grade_7
 * @property double $grade_8
 * @property double $rec_bim_1
 * @property double $rec_bim_2
 * @property double $rec_bim_3
 * @property double $rec_bim_4
 * @property double $rec_bim_5
 * @property double $rec_bim_6
 * @property double $rec_bim_7
 * @property double $rec_bim_8
 * @property double $rec_sem_1
 * @property double $rec_sem_2
 * @property double $rec_sem_3
 * @property double $rec_sem_4
 * @property double $rec_final
 * @property double $semianual_media
 * @property double $rec_semianual_2
 * @property double $rec_semianual_1
 * @property double $final_media
 * @property string $grade_concept_1
 * @property string $grade_concept_2
 * @property string $grade_concept_3
 * @property string $grade_concept_4
 * @property string $grade_concept_5
 * @property string $grade_concept_6
 * @property string $grade_concept_7
 * @property string $grade_concept_8
 * @property string $situation
 * @property integer $enrollment_fk
 * @property integer $discipline_fk
 * @property integer $grade_faults_1
 * @property integer $grade_faults_2
 * @property integer $grade_faults_3
 * @property integer $grade_faults_4
 * @property integer $grade_faults_5
 * @property integer $grade_faults_6
 * @property integer $grade_faults_7
 * @property integer $grade_faults_8
 * @property integer $given_classes_1
 * @property integer $given_classes_2
 * @property integer $given_classes_3
 * @property integer $given_classes_4
 * @property integer $given_classes_5
 * @property integer $given_classes_6
 * @property integer $given_classes_7
 * @property integer $given_classes_8
 * @property integer $final_concept
 *
 * The followings are the available model relations:
 * @property StudentEnrollment $enrollmentFk
 * @property EdcensoDiscipline $disciplineFk
 */
class GradeResults extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'grade_results';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('enrollment_fk, discipline_fk', 'required'),
			array('enrollment_fk, discipline_fk, grade_faults_1, grade_faults_2, grade_faults_3, grade_faults_4, grade_faults_5, grade_faults_6, grade_faults_7, grade_faults_8, given_classes_1, given_classes_2, given_classes_3, given_classes_4, given_classes_5, given_classes_6, given_classes_7, given_classes_8, final_concept', 'numerical', 'integerOnly'=>true),
			array('grade_1, grade_2, grade_3, grade_4, grade_5, grade_6, grade_7, grade_8, rec_bim_1, rec_bim_2, rec_bim_3, rec_bim_4, rec_bim_5, rec_bim_6, rec_bim_7, rec_bim_8, rec_sem_1, rec_sem_2, rec_sem_3, rec_sem_4, rec_final, semianual_media, rec_semianual_2, rec_semianual_1, final_media', 'numerical'),
			array('grade_concept_1, grade_concept_2, grade_concept_3, grade_concept_4, grade_concept_5, grade_concept_6, grade_concept_7, grade_concept_8, situation', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, grade_1, grade_2, grade_3, grade_4, grade_5, grade_6, grade_7, grade_8, rec_bim_1, rec_bim_2, rec_bim_3, rec_bim_4, rec_bim_5, rec_bim_6, rec_bim_7, rec_bim_8, rec_sem_1, rec_sem_2, rec_sem_3, rec_sem_4, rec_final, semianual_media, rec_semianual_2, rec_semianual_1, final_media, grade_concept_1, grade_concept_2, grade_concept_3, grade_concept_4, grade_concept_5, grade_concept_6, grade_concept_7, grade_concept_8, situation, enrollment_fk, discipline_fk, grade_faults_1, grade_faults_2, grade_faults_3, grade_faults_4, grade_faults_5, grade_faults_6, grade_faults_7, grade_faults_8, given_classes_1, given_classes_2, given_classes_3, given_classes_4, given_classes_5, given_classes_6, given_classes_7, given_classes_8, final_concept', 'safe', 'on'=>'search'),
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
			'enrollmentFk' => array(self::BELONGS_TO, 'StudentEnrollment', 'enrollment_fk'),
			'disciplineFk' => array(self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'grade_1' => 'Grade 1',
			'grade_2' => 'Grade 2',
			'grade_3' => 'Grade 3',
			'grade_4' => 'Grade 4',
			'grade_5' => 'Grade 5',
			'grade_6' => 'Grade 6',
			'grade_7' => 'Grade 7',
			'grade_8' => 'Grade 8',
			'rec_bim_1' => 'Rec Bim 1',
			'rec_bim_2' => 'Rec Bim 2',
			'rec_bim_3' => 'Rec Bim 3',
			'rec_bim_4' => 'Rec Bim 4',
			'rec_bim_5' => 'Rec Bim 5',
			'rec_bim_6' => 'Rec Bim 6',
			'rec_bim_7' => 'Rec Bim 7',
			'rec_bim_8' => 'Rec Bim 8',
			'rec_sem_1' => 'Rec Sem 1',
			'rec_sem_2' => 'Rec Sem 2',
			'rec_sem_3' => 'Rec Sem 3',
			'rec_sem_4' => 'Rec Sem 4',
			'rec_final' => 'Rec Final',
			'semianual_media' => 'Semianual Media',
			'rec_semianual_2' => 'Rec Semianual 2',
			'rec_semianual_1' => 'Rec Semianual 1',
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
		$criteria->compare('grade_1',$this->grade_1);
		$criteria->compare('grade_2',$this->grade_2);
		$criteria->compare('grade_3',$this->grade_3);
		$criteria->compare('grade_4',$this->grade_4);
		$criteria->compare('grade_5',$this->grade_5);
		$criteria->compare('grade_6',$this->grade_6);
		$criteria->compare('grade_7',$this->grade_7);
		$criteria->compare('grade_8',$this->grade_8);
		$criteria->compare('rec_bim_1',$this->rec_bim_1);
		$criteria->compare('rec_bim_2',$this->rec_bim_2);
		$criteria->compare('rec_bim_3',$this->rec_bim_3);
		$criteria->compare('rec_bim_4',$this->rec_bim_4);
		$criteria->compare('rec_bim_5',$this->rec_bim_5);
		$criteria->compare('rec_bim_6',$this->rec_bim_6);
		$criteria->compare('rec_bim_7',$this->rec_bim_7);
		$criteria->compare('rec_bim_8',$this->rec_bim_8);
		$criteria->compare('rec_sem_1',$this->rec_sem_1);
		$criteria->compare('rec_sem_2',$this->rec_sem_2);
		$criteria->compare('rec_sem_3',$this->rec_sem_3);
		$criteria->compare('rec_sem_4',$this->rec_sem_4);
		$criteria->compare('rec_final',$this->rec_final);
		$criteria->compare('semianual_media',$this->semianual_media);
		$criteria->compare('rec_semianual_2',$this->rec_semianual_2);
		$criteria->compare('rec_semianual_1',$this->rec_semianual_1);
		$criteria->compare('final_media',$this->final_media);
		$criteria->compare('grade_concept_1',$this->grade_concept_1,true);
		$criteria->compare('grade_concept_2',$this->grade_concept_2,true);
		$criteria->compare('grade_concept_3',$this->grade_concept_3,true);
		$criteria->compare('grade_concept_4',$this->grade_concept_4,true);
		$criteria->compare('grade_concept_5',$this->grade_concept_5,true);
		$criteria->compare('grade_concept_6',$this->grade_concept_6,true);
		$criteria->compare('grade_concept_7',$this->grade_concept_7,true);
		$criteria->compare('grade_concept_8',$this->grade_concept_8,true);
		$criteria->compare('situation',$this->situation,true);
		$criteria->compare('enrollment_fk',$this->enrollment_fk);
		$criteria->compare('discipline_fk',$this->discipline_fk);
		$criteria->compare('grade_faults_1',$this->grade_faults_1);
		$criteria->compare('grade_faults_2',$this->grade_faults_2);
		$criteria->compare('grade_faults_3',$this->grade_faults_3);
		$criteria->compare('grade_faults_4',$this->grade_faults_4);
		$criteria->compare('grade_faults_5',$this->grade_faults_5);
		$criteria->compare('grade_faults_6',$this->grade_faults_6);
		$criteria->compare('grade_faults_7',$this->grade_faults_7);
		$criteria->compare('grade_faults_8',$this->grade_faults_8);
		$criteria->compare('given_classes_1',$this->given_classes_1);
		$criteria->compare('given_classes_2',$this->given_classes_2);
		$criteria->compare('given_classes_3',$this->given_classes_3);
		$criteria->compare('given_classes_4',$this->given_classes_4);
		$criteria->compare('given_classes_5',$this->given_classes_5);
		$criteria->compare('given_classes_6',$this->given_classes_6);
		$criteria->compare('given_classes_7',$this->given_classes_7);
		$criteria->compare('given_classes_8',$this->given_classes_8);
		$criteria->compare('final_concept',$this->final_concept);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GradeResults the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
