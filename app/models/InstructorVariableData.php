<?php

/**
 * This is the model class for table "instructor_variable_data".
 *
 * The followings are the available columns in table 'instructor_variable_data':
 * @property string $register_type
 * @property string $school_inep_id_fk
 * @property string $inep_id
 * @property integer $id
 * @property integer $scholarity
 * @property integer $high_education_situation_1
 * @property integer $high_education_formation_1
 * @property string $high_education_course_code_1_fk
 * @property string $high_education_initial_year_1
 * @property string $high_education_final_year_1
 * @property integer $high_education_institution_code_1_fk
 * @property integer $high_education_situation_2
 * @property integer $high_education_formation_2
 * @property string $high_education_course_code_2_fk
 * @property string $high_education_initial_year_2
 * @property string $high_education_final_year_2
 * @property integer $high_education_institution_code_2_fk
 * @property integer $high_education_situation_3
 * @property integer $high_education_formation_3
 * @property string $high_education_course_code_3_fk
 * @property string $high_education_initial_year_3
 * @property string $high_education_final_year_3
 * @property integer $high_education_institution_code_3_fk
 * @property integer $post_graduation_specialization
 * @property integer $post_graduation_master
 * @property integer $post_graduation_doctorate
 * @property integer $post_graduation_none
 * @property integer $other_courses_nursery
 * @property integer $other_courses_pre_school
 * @property integer $other_courses_basic_education_initial_years
 * @property integer $other_courses_basic_education_final_years
 * @property integer $other_courses_high_school
 * @property integer $other_courses_education_of_youth_and_adults
 * @property integer $other_courses_special_education
 * @property integer $other_courses_native_education
 * @property integer $other_courses_field_education
 * @property integer $other_courses_environment_education
 * @property integer $other_courses_human_rights_education
 * @property integer $other_courses_bilingual_education_for_the_deaf
 * @property integer $other_courses_education_and_tic
 * @property integer $other_courses_sexual_education
 * @property integer $other_courses_child_and_teenage_rights
 * @property integer $other_courses_ethnic_education
 * @property integer $other_courses_other
 * @property integer $other_courses_none
 * @property integer $hash
 * 
 * 
 *
 * The followings are the available model relations:
 * @property EdcensoCourseOfHigherEducation $highEducationCourseCode1Fk
 * @property EdcensoIES $highEducationInstitutionCode1Fk
 * @property EdcensoCourseOfHigherEducation $highEducationCourseCode2Fk
 * @property EdcensoIES $highEducationInstitutionCode2Fk
 * @property EdcensoCourseOfHigherEducation $highEducationCourseCode3Fk
 * @property EdcensoIES $highEducationInstitutionCode3Fk
 */
class InstructorVariableData extends AltActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return InstructorVariableData the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'instructor_variable_data';
	}
/*
        public function behaviors() {
            return [
                'afterSave'=>[
                    'class'=>'application.behaviors.CAfterSaveBehavior',
                    'schoolInepId' => Yii::app()->user->school,
                ],
            ];
        }
        */
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('school_inep_id_fk, scholarity, other_courses_nursery, other_courses_pre_school, other_courses_basic_education_initial_years, other_courses_basic_education_final_years, other_courses_high_school, other_courses_education_of_youth_and_adults, other_courses_special_education, other_courses_native_education, other_courses_field_education, other_courses_environment_education, other_courses_human_rights_education, other_courses_bilingual_education_for_the_deaf, other_courses_education_and_tic, other_courses_sexual_education, other_courses_child_and_teenage_rights, other_courses_ethnic_education, other_courses_other, other_courses_none', 'required'),
			array('scholarity, high_education_situation_1, high_education_formation_1, high_education_institution_code_1_fk, high_education_situation_2, high_education_formation_2, high_education_institution_code_2_fk, high_education_situation_3, high_education_formation_3, high_education_institution_code_3_fk, post_graduation_specialization, post_graduation_master, post_graduation_doctorate, post_graduation_none, other_courses_nursery, other_courses_pre_school, other_courses_basic_education_initial_years, other_courses_basic_education_final_years, other_courses_high_school, other_courses_education_of_youth_and_adults, other_courses_special_education, other_courses_native_education, other_courses_field_education, other_courses_environment_education, other_courses_human_rights_education, other_courses_sexual_education, other_courses_child_and_teenage_rights, other_courses_ethnic_education, other_courses_other, other_courses_none', 'numerical', 'integerOnly'=>true),
			array('register_type', 'length', 'max'=>2),
			array('school_inep_id_fk', 'length', 'max'=>8),
			array('inep_id', 'length', 'max'=>12),
			array('high_education_course_code_1_fk, high_education_course_code_2_fk, high_education_course_code_3_fk', 'length', 'max'=>6),
			array('high_education_initial_year_1, high_education_final_year_1, high_education_initial_year_2, high_education_final_year_2, high_education_initial_year_3, high_education_final_year_3', 'length', 'max'=>4),
			array('hash', 'length', 'max'=>40),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('register_type, school_inep_id_fk, inep_id, id, scholarity, high_education_situation_1, high_education_formation_1, high_education_course_code_1_fk, high_education_initial_year_1, high_education_final_year_1, high_education_institution_code_1_fk, high_education_situation_2, high_education_formation_2, high_education_course_code_2_fk, high_education_initial_year_2, high_education_final_year_2, high_education_institution_code_2_fk, high_education_situation_3, high_education_formation_3, high_education_course_code_3_fk, high_education_initial_year_3, high_education_final_year_3, high_education_institution_code_3_fk, post_graduation_specialization, post_graduation_master, post_graduation_doctorate, post_graduation_none, other_courses_nursery, other_courses_pre_school, other_courses_basic_education_initial_years, other_courses_basic_education_final_years, other_courses_high_school, other_courses_education_of_youth_and_adults, other_courses_special_education, other_courses_native_education, other_courses_field_education, other_courses_environment_education, other_courses_human_rights_education, other_courses_bilingual_education_for_the_deaf, other_courses_education_and_tic, other_courses_sexual_education, other_courses_child_and_teenage_rights, other_courses_ethnic_education, other_courses_other, other_courses_none, hash', 'safe', 'on'=>'search'),
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
			'highEducationCourseCode1Fk' => array(self::BELONGS_TO, 'EdcensoCourseOfHigherEducation', 'high_education_course_code_1_fk'),
			'highEducationInstitutionCode1Fk' => array(self::BELONGS_TO, 'EdcensoIES', 'high_education_institution_code_1_fk'),
			'highEducationCourseCode2Fk' => array(self::BELONGS_TO, 'EdcensoCourseOfHigherEducation', 'high_education_course_code_2_fk'),
			'highEducationInstitutionCode2Fk' => array(self::BELONGS_TO, 'EdcensoIES', 'high_education_institution_code_2_fk'),
			'highEducationCourseCode3Fk' => array(self::BELONGS_TO, 'EdcensoCourseOfHigherEducation', 'high_education_course_code_3_fk'),
			'highEducationInstitutionCode3Fk' => array(self::BELONGS_TO, 'EdcensoIES', 'high_education_institution_code_3_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'register_type' => Yii::t('default', 'Register Type'),
			'school_inep_id_fk' => Yii::t('default', 'School Inep Id Fk'),
			'inep_id' => Yii::t('default', 'Inep'),
			'id' => Yii::t('default', 'ID'),
			'scholarity' => Yii::t('default', 'Scholarity'),
			'high_education_situation_1' => Yii::t('default', 'High Education Situation 1'),
			'high_education_formation_1' => Yii::t('default', 'High Education Formation 1'),
			'high_education_course_code_1_fk' => Yii::t('default', 'High Education Course Code 1 Fk'),
			'high_education_initial_year_1' => Yii::t('default', 'High Education Initial Year 1'),
			'high_education_final_year_1' => Yii::t('default', 'High Education Final Year 1'),
			'high_education_institution_code_1_fk' => Yii::t('default', 'High Education Institution Code 1 Fk'),
			'high_education_situation_2' => Yii::t('default', 'High Education Situation 2'),
			'high_education_formation_2' => Yii::t('default', 'High Education Formation 2'),
			'high_education_course_code_2_fk' => Yii::t('default', 'High Education Course Code 2 Fk'),
			'high_education_initial_year_2' => Yii::t('default', 'High Education Initial Year 2'),
			'high_education_final_year_2' => Yii::t('default', 'High Education Final Year 2'),
			'high_education_institution_code_2_fk' => Yii::t('default', 'High Education Institution Code 2 Fk'),
			'high_education_situation_3' => Yii::t('default', 'High Education Situation 3'),
			'high_education_formation_3' => Yii::t('default', 'High Education Formation 3'),
			'high_education_course_code_3_fk' => Yii::t('default', 'High Education Course Code 3 Fk'),
			'high_education_initial_year_3' => Yii::t('default', 'High Education Initial Year 3'),
			'high_education_final_year_3' => Yii::t('default', 'High Education Final Year 3'),
			'high_education_institution_code_3_fk' => Yii::t('default', 'High Education Institution Code 3 Fk'),
			'post_graduation_specialization' => Yii::t('default', 'Post Graduation Specialization'),
			'post_graduation_master' => Yii::t('default', 'Post Graduation Master'),
			'post_graduation_doctorate' => Yii::t('default', 'Post Graduation Doctorate'),
			'post_graduation_none' => Yii::t('default', 'Post Graduation None'),
			'other_courses_nursery' => Yii::t('default', 'Other Courses Nursery'),
			'other_courses_pre_school' => Yii::t('default', 'Other Courses Pre School'),
			'other_courses_basic_education_initial_years' => Yii::t('default', 'Other Courses Basic Education Initial Years'),
			'other_courses_basic_education_final_years' => Yii::t('default', 'Other Courses Basic Education Final Years'),
			'other_courses_high_school' => Yii::t('default', 'Other Courses High School'),
			'other_courses_education_of_youth_and_adults' => Yii::t('default', 'Other Courses Education Of Youth And Adults'),
			'other_courses_special_education' => Yii::t('default', 'Other Courses Special Education'),
			'other_courses_native_education' => Yii::t('default', 'Other Courses Native Education'),
			'other_courses_field_education' => Yii::t('default', 'Other Courses Field Education'),
			'other_courses_environment_education' => Yii::t('default', 'Other Courses Environment Education'),
			'other_courses_human_rights_education' => Yii::t('default', 'Other Courses Human Rights Education'),
			'other_courses_bilingual_education_for_the_deaf' => Yii::t('default', 'Other Courses Bilingual Education For The Deaf'),
			'other_courses_education_and_tic' => Yii::t('default', 'Other Courses Education And Tic'),
			'other_courses_sexual_education' => Yii::t('default', 'Other Courses Sexual Education'),
			'other_courses_child_and_teenage_rights' => Yii::t('default', 'Other Courses Child And Teenage Rights'),
			'other_courses_ethnic_education' => Yii::t('default', 'Other Courses Ethnic Education'),
			'other_courses_other' => Yii::t('default', 'Other Courses Other'),
			'other_courses_none' => Yii::t('default', 'Other Courses None'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('register_type',$this->register_type,true);
		$criteria->compare('school_inep_id_fk',$this->school_inep_id_fk,true);
		$criteria->compare('inep_id',$this->inep_id,true);
		$criteria->compare('id',$this->id);
		$criteria->compare('scholarity',$this->scholarity);
		$criteria->compare('high_education_situation_1',$this->high_education_situation_1);
		$criteria->compare('high_education_formation_1',$this->high_education_formation_1);
		$criteria->compare('high_education_course_code_1_fk',$this->high_education_course_code_1_fk,true);
		$criteria->compare('high_education_initial_year_1',$this->high_education_initial_year_1,true);
		$criteria->compare('high_education_final_year_1',$this->high_education_final_year_1,true);
		$criteria->compare('high_education_institution_code_1_fk',$this->high_education_institution_code_1_fk);
		$criteria->compare('high_education_situation_2',$this->high_education_situation_2);
		$criteria->compare('high_education_formation_2',$this->high_education_formation_2);
		$criteria->compare('high_education_course_code_2_fk',$this->high_education_course_code_2_fk,true);
		$criteria->compare('high_education_initial_year_2',$this->high_education_initial_year_2,true);
		$criteria->compare('high_education_final_year_2',$this->high_education_final_year_2,true);
		$criteria->compare('high_education_institution_code_2_fk',$this->high_education_institution_code_2_fk);
		$criteria->compare('high_education_situation_3',$this->high_education_situation_3);
		$criteria->compare('high_education_formation_3',$this->high_education_formation_3);
		$criteria->compare('high_education_course_code_3_fk',$this->high_education_course_code_3_fk,true);
		$criteria->compare('high_education_initial_year_3',$this->high_education_initial_year_3,true);
		$criteria->compare('high_education_final_year_3',$this->high_education_final_year_3,true);
		$criteria->compare('high_education_institution_code_3_fk',$this->high_education_institution_code_3_fk);
		$criteria->compare('post_graduation_specialization',$this->post_graduation_specialization);
		$criteria->compare('post_graduation_master',$this->post_graduation_master);
		$criteria->compare('post_graduation_doctorate',$this->post_graduation_doctorate);
		$criteria->compare('post_graduation_none',$this->post_graduation_none);
		$criteria->compare('other_courses_nursery',$this->other_courses_nursery);
		$criteria->compare('other_courses_pre_school',$this->other_courses_pre_school);
		$criteria->compare('other_courses_basic_education_initial_years',$this->other_courses_basic_education_initial_years);
		$criteria->compare('other_courses_basic_education_final_years',$this->other_courses_basic_education_final_years);
		$criteria->compare('other_courses_high_school',$this->other_courses_high_school);
		$criteria->compare('other_courses_education_of_youth_and_adults',$this->other_courses_education_of_youth_and_adults);
		$criteria->compare('other_courses_special_education',$this->other_courses_special_education);
		$criteria->compare('other_courses_native_education',$this->other_courses_native_education);
		$criteria->compare('other_courses_field_education',$this->other_courses_field_education);
		$criteria->compare('other_courses_environment_education',$this->other_courses_environment_education);
		$criteria->compare('other_courses_human_rights_education',$this->other_courses_human_rights_education);
		$criteria->compare('other_courses_bilingual_education_for_the_deaf',$this->other_courses_bilingual_education_for_the_deaf);
		$criteria->compare('other_courses_education_and_tic',$this->other_courses_education_and_tic);
		$criteria->compare('other_courses_sexual_education',$this->other_courses_sexual_education);
		$criteria->compare('other_courses_child_and_teenage_rights',$this->other_courses_child_and_teenage_rights);
		$criteria->compare('other_courses_ethnic_education',$this->other_courses_ethnic_education);
		$criteria->compare('other_courses_other',$this->other_courses_other);
		$criteria->compare('other_courses_none',$this->other_courses_none);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}