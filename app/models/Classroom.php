<?php

/**
 * This is the model class for table "classroom".
 *
 * The followings are the available columns in table 'classroom':
 * @property string $register_type
 * @property string $school_inep_fk
 * @property string $inep_id
 * @property integer $id
 * @property string $name
 * @property string $initial_hour
 * @property string $initial_minute
 * @property string $final_hour
 * @property string $final_minute
 * @property integer $week_days_sunday
 * @property integer $week_days_monday
 * @property integer $week_days_tuesday
 * @property integer $week_days_wednesday
 * @property integer $week_days_thursday
 * @property integer $week_days_friday
 * @property integer $week_days_saturday
 * @property integer $assistance_type
 * @property integer $mais_educacao_participator
 * @property integer $complementary_activity_type_1
 * @property integer $complementary_activity_type_2
 * @property integer $complementary_activity_type_3
 * @property integer $complementary_activity_type_4
 * @property integer $complementary_activity_type_5
 * @property integer $complementary_activity_type_6
 * @property integer $aee_braille_system_education
 * @property integer $aee_optical_and_non_optical_resources
 * @property integer $aee_mental_processes_development_strategies
 * @property integer $aee_mobility_and_orientation_techniques
 * @property integer $aee_libras
 * @property integer $aee_caa_use_education
 * @property integer $aee_curriculum_enrichment_strategy
 * @property integer $aee_soroban_use_education
 * @property integer $aee_usability_and_functionality_of_computer_accessible_education
 * @property integer $aee_teaching_of_Portuguese_language_written_modality
 * @property integer $aee_strategy_for_school_environment_autonomy
 * @property integer $modality
 * @property string $edcenso_stage_vs_modality_fk
 * @property integer $edcenso_professional_education_course_fk
 * @property integer $discipline_chemistry
 * @property integer $discipline_physics
 * @property integer $discipline_mathematics
 * @property integer $discipline_biology
 * @property integer $discipline_science
 * @property integer $discipline_language_portuguese_literature
 * @property integer $discipline_foreign_language_english
 * @property integer $discipline_foreign_language_spanish
 * @property integer $discipline_foreign_language_franch
 * @property integer $discipline_foreign_language_other
 * @property integer $discipline_arts
 * @property integer $discipline_physical_education
 * @property integer $discipline_history
 * @property integer $discipline_geography
 * @property integer $discipline_philosophy
 * @property integer $discipline_social_study
 * @property integer $discipline_sociology
 * @property integer $discipline_informatics
 * @property integer $discipline_professional_disciplines
 * @property integer $discipline_special_education_and_inclusive_practices
 * @property integer $discipline_sociocultural_diversity
 * @property integer $discipline_libras
 * @property integer $discipline_pedagogical
 * @property integer $discipline_religious
 * @property integer $discipline_native_language
 * @property integer $discipline_others
 * @property integer $instructor_situation
 *
 * The followings are the available model relations:
 * @property SchoolIdentification $schoolInepFk
 * @property EdcensoProfessionalEducationCourse $edcensoProfessionalEducationCourseFk
 */
class Classroom extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Classroom the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'classroom';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, school_inep_fk, school_year, initial_hour, initial_minute, final_hour, final_minute, week_days_sunday, week_days_monday, week_days_tuesday, week_days_wednesday, week_days_thursday, week_days_friday, week_days_saturday, assistance_type', 'required'),
            array('week_days_sunday, school_year, week_days_monday, week_days_tuesday, week_days_wednesday, week_days_thursday, week_days_friday, week_days_saturday, assistance_type, mais_educacao_participator, complementary_activity_type_1, complementary_activity_type_2, complementary_activity_type_3, complementary_activity_type_4, complementary_activity_type_5, complementary_activity_type_6, aee_braille_system_education, aee_optical_and_non_optical_resources, aee_mental_processes_development_strategies, aee_mobility_and_orientation_techniques, aee_libras, aee_caa_use_education, aee_curriculum_enrichment_strategy, aee_soroban_use_education, aee_usability_and_functionality_of_computer_accessible_education, aee_teaching_of_Portuguese_language_written_modality, aee_strategy_for_school_environment_autonomy, edcenso_professional_education_course_fk, discipline_chemistry, discipline_physics, discipline_mathematics, discipline_biology, discipline_science, discipline_language_portuguese_literature, discipline_foreign_language_english, discipline_foreign_language_spanish, discipline_foreign_language_franch, discipline_foreign_language_other, discipline_arts, discipline_physical_education, discipline_history, discipline_geography, discipline_philosophy, discipline_social_study, discipline_sociology, discipline_informatics, discipline_professional_disciplines, discipline_special_education_and_inclusive_practices, discipline_sociocultural_diversity, discipline_libras, discipline_pedagogical, discipline_religious, discipline_native_language, discipline_others, instructor_situation', 'numerical', 'integerOnly' => true),
            array('register_type, initial_hour, initial_minute, final_hour, final_minute, edcenso_stage_vs_modality_fk', 'length', 'max' => 2),
            array('school_inep_fk', 'length', 'max' => 8),
            array('inep_id', 'length', 'max' => 10),
            array('name', 'length', 'max' => 80),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('register_type, school_inep_fk, inep_id, id, name, initial_hour, initial_minute, final_hour, final_minute, week_days_sunday, week_days_monday, week_days_tuesday, week_days_wednesday, week_days_thursday, week_days_friday, week_days_saturday, assistance_type, mais_educacao_participator, complementary_activity_type_1, complementary_activity_type_2, complementary_activity_type_3, complementary_activity_type_4, complementary_activity_type_5, complementary_activity_type_6, aee_braille_system_education, aee_optical_and_non_optical_resources, aee_mental_processes_development_strategies, aee_mobility_and_orientation_techniques, aee_libras, aee_caa_use_education, aee_curriculum_enrichment_strategy, aee_soroban_use_education, aee_usability_and_functionality_of_computer_accessible_education, aee_teaching_of_Portuguese_language_written_modality, aee_strategy_for_school_environment_autonomy, modality, edcenso_stage_vs_modality_fk, edcenso_professional_education_course_fk, discipline_chemistry, discipline_physics, discipline_mathematics, discipline_biology, discipline_science, discipline_language_portuguese_literature, discipline_foreign_language_english, discipline_foreign_language_spanish, discipline_foreign_language_franch, discipline_foreign_language_other, discipline_arts, discipline_physical_education, discipline_history, discipline_geography, discipline_philosophy, discipline_social_study, discipline_sociology, discipline_informatics, discipline_professional_disciplines, discipline_special_education_and_inclusive_practices, discipline_sociocultural_diversity, discipline_libras, discipline_pedagogical, discipline_religious, discipline_native_language, discipline_others, instructor_situation', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'schoolInepFk' => array(self::BELONGS_TO, 'SchoolIdentification', 'school_inep_fk'),
            'edcensoProfessionalEducationCourseFk' => array(self::BELONGS_TO, 'EdcensoProfessionalEducationCourse', 'edcenso_professional_education_course_fk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'register_type' => Yii::t('default', 'Register Type'),
            'school_inep_fk' => Yii::t('default', 'School Inep Fk'),
            'inep_id' => Yii::t('default', 'Inep'),
            'id' => Yii::t('default', 'ID'),
            'school_year' => Yii::t('default', 'School Year'),
            'name' => Yii::t('default', 'Name'),
            'initial_hour' => Yii::t('default', 'Initial Hour'),
            'initial_minute' => Yii::t('default', 'Initial Minute'),
            'final_hour' => Yii::t('default', 'Final Hour'),
            'final_minute' => Yii::t('default', 'Final Minute'),
            'week_days_sunday' => Yii::t('default', 'Week Days Sunday'),
            'week_days_monday' => Yii::t('default', 'Week Days Monday'),
            'week_days_tuesday' => Yii::t('default', 'Week Days Tuesday'),
            'week_days_wednesday' => Yii::t('default', 'Week Days Wednesday'),
            'week_days_thursday' => Yii::t('default', 'Week Days Thursday'),
            'week_days_friday' => Yii::t('default', 'Week Days Friday'),
            'week_days_saturday' => Yii::t('default', 'Week Days Saturday'),
            'assistance_type' => Yii::t('default', 'Assistance Type'),
            'mais_educacao_participator' => Yii::t('default', 'Mais Educacao Participator'),
            'complementary_activity_type_1' => Yii::t('default', 'Complementary Activity Type 1'),
            'complementary_activity_type_2' => Yii::t('default', 'Complementary Activity Type 2'),
            'complementary_activity_type_3' => Yii::t('default', 'Complementary Activity Type 3'),
            'complementary_activity_type_4' => Yii::t('default', 'Complementary Activity Type 4'),
            'complementary_activity_type_5' => Yii::t('default', 'Complementary Activity Type 5'),
            'complementary_activity_type_6' => Yii::t('default', 'Complementary Activity Type 6'),
            'aee_braille_system_education' => Yii::t('default', 'Aee Braille System Education'),
            'aee_optical_and_non_optical_resources' => Yii::t('default', 'Aee Optical And Non Optical Resources'),
            'aee_mental_processes_development_strategies' => Yii::t('default', 'Aee Mental Processes Development Strategies'),
            'aee_mobility_and_orientation_techniques' => Yii::t('default', 'Aee Mobility And Orientation Techniques'),
            'aee_libras' => Yii::t('default', 'Aee Libras'),
            'aee_caa_use_education' => Yii::t('default', 'Aee Caa Use Education'),
            'aee_curriculum_enrichment_strategy' => Yii::t('default', 'Aee Curriculum Enrichment Strategy'),
            'aee_soroban_use_education' => Yii::t('default', 'Aee Soroban Use Education'),
            'aee_usability_and_functionality_of_computer_accessible_education' => Yii::t('default', 'Aee Usability And Functionality Of Computer Accessible Education'),
            'aee_teaching_of_Portuguese_language_written_modality' => Yii::t('default', 'Aee Teaching Of Portuguese Language Written Modality'),
            'aee_strategy_for_school_environment_autonomy' => Yii::t('default', 'Aee Strategy For School Environment Autonomy'),
            'modality' => Yii::t('default', 'Modality'),
            'edcenso_stage_vs_modality_fk' => Yii::t('default', 'Edcenso Stage Vs Modality Fk'),
            'edcenso_professional_education_course_fk' => Yii::t('default', 'Edcenso Professional Education Course Fk'),
            'discipline_chemistry' => Yii::t('default', 'Discipline Chemistry'),
            'discipline_physics' => Yii::t('default', 'Discipline Physics'),
            'discipline_mathematics' => Yii::t('default', 'Discipline Mathematics'),
            'discipline_biology' => Yii::t('default', 'Discipline Biology'),
            'discipline_science' => Yii::t('default', 'Discipline Science'),
            'discipline_language_portuguese_literature' => Yii::t('default', 'Discipline Language Portuguese Literature'),
            'discipline_foreign_language_english' => Yii::t('default', 'Discipline Foreign Language English'),
            'discipline_foreign_language_spanish' => Yii::t('default', 'Discipline Foreign Language Spanish'),
            'discipline_foreign_language_franch' => Yii::t('default', 'Discipline Foreign Language Franch'),
            'discipline_foreign_language_other' => Yii::t('default', 'Discipline Foreign Language Other'),
            'discipline_arts' => Yii::t('default', 'Discipline Arts'),
            'discipline_physical_education' => Yii::t('default', 'Discipline Physical Education'),
            'discipline_history' => Yii::t('default', 'Discipline History'),
            'discipline_geography' => Yii::t('default', 'Discipline Geography'),
            'discipline_philosophy' => Yii::t('default', 'Discipline Philosophy'),
            'discipline_social_study' => Yii::t('default', 'Discipline Social Study'),
            'discipline_sociology' => Yii::t('default', 'Discipline Sociology'),
            'discipline_informatics' => Yii::t('default', 'Discipline Informatics'),
            'discipline_professional_disciplines' => Yii::t('default', 'Discipline Professional Disciplines'),
            'discipline_special_education_and_inclusive_practices' => Yii::t('default', 'Discipline Special Education And Inclusive Practices'),
            'discipline_sociocultural_diversity' => Yii::t('default', 'Discipline Sociocultural Diversity'),
            'discipline_libras' => Yii::t('default', 'Discipline Libras'),
            'discipline_pedagogical' => Yii::t('default', 'Discipline Pedagogical'),
            'discipline_religious' => Yii::t('default', 'Discipline Religious'),
            'discipline_native_language' => Yii::t('default', 'Discipline Native Language'),
            'discipline_others' => Yii::t('default', 'Discipline Others'),
            'instructor_situation' => Yii::t('default', 'Instructor Situation'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('register_type', $this->register_type, true);
        $criteria->compare('school_inep_fk', $this->school_inep_fk, true);
        $criteria->compare('inep_id', $this->inep_id, true);
        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('school_year', $this->school_year, true);
        $criteria->compare('initial_hour', $this->initial_hour, true);
        $criteria->compare('initial_minute', $this->initial_minute, true);
        $criteria->compare('final_hour', $this->final_hour, true);
        $criteria->compare('final_minute', $this->final_minute, true);
        $criteria->compare('week_days_sunday', $this->week_days_sunday);
        $criteria->compare('week_days_monday', $this->week_days_monday);
        $criteria->compare('week_days_tuesday', $this->week_days_tuesday);
        $criteria->compare('week_days_wednesday', $this->week_days_wednesday);
        $criteria->compare('week_days_thursday', $this->week_days_thursday);
        $criteria->compare('week_days_friday', $this->week_days_friday);
        $criteria->compare('week_days_saturday', $this->week_days_saturday);
        $criteria->compare('assistance_type', $this->assistance_type);
        $criteria->compare('mais_educacao_participator', $this->mais_educacao_participator);
        $criteria->compare('complementary_activity_type_1', $this->complementary_activity_type_1);
        $criteria->compare('complementary_activity_type_2', $this->complementary_activity_type_2);
        $criteria->compare('complementary_activity_type_3', $this->complementary_activity_type_3);
        $criteria->compare('complementary_activity_type_4', $this->complementary_activity_type_4);
        $criteria->compare('complementary_activity_type_5', $this->complementary_activity_type_5);
        $criteria->compare('complementary_activity_type_6', $this->complementary_activity_type_6);
        $criteria->compare('aee_braille_system_education', $this->aee_braille_system_education);
        $criteria->compare('aee_optical_and_non_optical_resources', $this->aee_optical_and_non_optical_resources);
        $criteria->compare('aee_mental_processes_development_strategies', $this->aee_mental_processes_development_strategies);
        $criteria->compare('aee_mobility_and_orientation_techniques', $this->aee_mobility_and_orientation_techniques);
        $criteria->compare('aee_libras', $this->aee_libras);
        $criteria->compare('aee_caa_use_education', $this->aee_caa_use_education);
        $criteria->compare('aee_curriculum_enrichment_strategy', $this->aee_curriculum_enrichment_strategy);
        $criteria->compare('aee_soroban_use_education', $this->aee_soroban_use_education);
        $criteria->compare('aee_usability_and_functionality_of_computer_accessible_education', $this->aee_usability_and_functionality_of_computer_accessible_education);
        $criteria->compare('aee_teaching_of_Portuguese_language_written_modality', $this->aee_teaching_of_Portuguese_language_written_modality);
        $criteria->compare('aee_strategy_for_school_environment_autonomy', $this->aee_strategy_for_school_environment_autonomy);
        $criteria->compare('modality', $this->modality);
        $criteria->compare('edcenso_stage_vs_modality_fk', $this->edcenso_stage_vs_modality_fk, true);
        $criteria->compare('edcenso_professional_education_course_fk', $this->edcenso_professional_education_course_fk);
        $criteria->compare('discipline_chemistry', $this->discipline_chemistry);
        $criteria->compare('discipline_physics', $this->discipline_physics);
        $criteria->compare('discipline_mathematics', $this->discipline_mathematics);
        $criteria->compare('discipline_biology', $this->discipline_biology);
        $criteria->compare('discipline_science', $this->discipline_science);
        $criteria->compare('discipline_language_portuguese_literature', $this->discipline_language_portuguese_literature);
        $criteria->compare('discipline_foreign_language_english', $this->discipline_foreign_language_english);
        $criteria->compare('discipline_foreign_language_spanish', $this->discipline_foreign_language_spanish);
        $criteria->compare('discipline_foreign_language_franch', $this->discipline_foreign_language_franch);
        $criteria->compare('discipline_foreign_language_other', $this->discipline_foreign_language_other);
        $criteria->compare('discipline_arts', $this->discipline_arts);
        $criteria->compare('discipline_physical_education', $this->discipline_physical_education);
        $criteria->compare('discipline_history', $this->discipline_history);
        $criteria->compare('discipline_geography', $this->discipline_geography);
        $criteria->compare('discipline_philosophy', $this->discipline_philosophy);
        $criteria->compare('discipline_social_study', $this->discipline_social_study);
        $criteria->compare('discipline_sociology', $this->discipline_sociology);
        $criteria->compare('discipline_informatics', $this->discipline_informatics);
        $criteria->compare('discipline_professional_disciplines', $this->discipline_professional_disciplines);
        $criteria->compare('discipline_special_education_and_inclusive_practices', $this->discipline_special_education_and_inclusive_practices);
        $criteria->compare('discipline_sociocultural_diversity', $this->discipline_sociocultural_diversity);
        $criteria->compare('discipline_libras', $this->discipline_libras);
        $criteria->compare('discipline_pedagogical', $this->discipline_pedagogical);
        $criteria->compare('discipline_religious', $this->discipline_religious);
        $criteria->compare('discipline_native_language', $this->discipline_native_language);
        $criteria->compare('discipline_others', $this->discipline_others);
        $criteria->compare('instructor_situation', $this->instructor_situation);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => array(
                        'defaultOrder' => array(
                            'name' => CSort::SORT_ASC
                        ),
                    ),
                ));
    }

}