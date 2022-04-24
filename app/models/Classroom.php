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
 * @property integer $pedagogical_mediation_type
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
 * @property integer $school_year
 * @property string $turn
 * @property string $create_date
 * @property string $fkid
 * @property integer $calendar_fk
 *
 * The followings are the available model relations:
 * @property ClassBoard[] $classBoards
 * @property Calendar $calendarFk
 * @property SchoolIdentification $schoolInepFk
 * @property EdcensoProfessionalEducationCourse $edcensoProfessionalEducationCourseFk
 * @property ClassroomHasCoursePlan[] $classroomHasCoursePlans
 * @property InstructorTeachingData[] $instructorTeachingDatas
 * @property Schedule[] $schedules
 * @property StudentEnrollment[] $studentEnrollments
 */
class Classroom extends AltActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'classroom';
    }

    public function behaviors()
    {
        if (isset(Yii::app()->user->school)) {
            return [
                'afterSave' => [
                    'class' => 'application.behaviors.CAfterSaveBehavior',
                    'schoolInepId' => Yii::app()->user->school,
                ],
            ];
        } else {
            return [];
        }
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, edcenso_stage_vs_modality_fk, modality, school_inep_fk, initial_hour, initial_minute, final_hour, final_minute, week_days_sunday, week_days_monday, week_days_tuesday, week_days_wednesday, week_days_thursday, week_days_friday, week_days_saturday, assistance_type, school_year, pedagogical_mediation_type', 'required'),
            array('pedagogical_mediation_type, week_days_sunday, week_days_monday, week_days_tuesday, week_days_wednesday, week_days_thursday, week_days_friday, week_days_saturday, assistance_type, mais_educacao_participator, complementary_activity_type_1, complementary_activity_type_2, complementary_activity_type_3, complementary_activity_type_4, complementary_activity_type_5, complementary_activity_type_6, modality, edcenso_professional_education_course_fk, discipline_chemistry, discipline_physics, discipline_mathematics, discipline_biology, discipline_science, discipline_language_portuguese_literature, discipline_foreign_language_english, discipline_foreign_language_spanish, discipline_foreign_language_franch, discipline_foreign_language_other, discipline_arts, discipline_physical_education, discipline_history, discipline_geography, discipline_philosophy, discipline_social_study, discipline_sociology, discipline_informatics, discipline_professional_disciplines, discipline_special_education_and_inclusive_practices, discipline_sociocultural_diversity, discipline_libras, discipline_pedagogical, discipline_religious, discipline_native_language, discipline_others, school_year, calendar_fk, schooling, diff_location, course, complementary_activity, aee', 'numerical', 'integerOnly' => true),
            array('register_type, initial_hour, initial_minute, final_hour, final_minute, edcenso_stage_vs_modality_fk', 'length', 'max' => 2),
            array('school_inep_fk', 'length', 'max' => 8),
            array('inep_id', 'length', 'max' => 10),
            array('name', 'length', 'max' => 80),
            array('turn', 'length', 'max' => 45),
            array('hash', 'length', 'max' => 40),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('register_type, school_inep_fk, inep_id, id, name, pedagogical_mediation_type, initial_hour, initial_minute, final_hour, final_minute, week_days_sunday, week_days_monday, week_days_tuesday, week_days_wednesday, week_days_thursday, week_days_friday, week_days_saturday, assistance_type, mais_educacao_participator, complementary_activity_type_1, complementary_activity_type_2, complementary_activity_type_3, complementary_activity_type_4, complementary_activity_type_5, complementary_activity_type_6, aee_braille_system_education, aee_optical_and_non_optical_resources, aee_mental_processes_development_strategies, aee_mobility_and_orientation_techniques, aee_libras, aee_caa_use_education, aee_curriculum_enrichment_strategy, aee_soroban_use_education, aee_usability_and_functionality_of_computer_accessible_education, aee_teaching_of_Portuguese_language_written_modality, aee_strategy_for_school_environment_autonomy, modality, edcenso_stage_vs_modality_fk, edcenso_professional_education_course_fk, discipline_chemistry, discipline_physics, discipline_mathematics, discipline_biology, discipline_science, discipline_language_portuguese_literature, discipline_foreign_language_english, discipline_foreign_language_spanish, discipline_foreign_language_franch, discipline_foreign_language_other, discipline_arts, discipline_physical_education, discipline_history, discipline_geography, discipline_philosophy, discipline_social_study, discipline_sociology, discipline_informatics, discipline_professional_disciplines, discipline_special_education_and_inclusive_practices, discipline_sociocultural_diversity, discipline_libras, discipline_pedagogical, discipline_religious, discipline_native_language, discipline_others, school_year, turn, create_date, fkid, calendar_fk', 'safe', 'on' => 'search'),
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
            'classBoards' => array(self::HAS_MANY, 'ClassBoard', 'classroom_fk'),
            'schedules' => array(self::HAS_MANY, 'Schedule', 'classroom_fk'),
            'schoolInepFk' => array(self::BELONGS_TO, 'SchoolIdentification', 'school_inep_fk'),
            'edcensoStageVsModalityFk' => array(self::BELONGS_TO, 'EdcensoStageVsModality', 'edcenso_stage_vs_modality_fk'),
            'edcensoProfessionalEducationCourseFk' => array(self::BELONGS_TO, 'EdcensoProfessionalEducationCourse', 'edcenso_professional_education_course_fk'),
            'instructorTeachingDatas' => array(self::HAS_MANY, 'InstructorTeachingData', 'classroom_id_fk'),
            'studentEnrollments' => array(self::HAS_MANY, 'StudentEnrollment', 'classroom_fk', 'order' => 'student_identification.name ASC', 'join' => 'JOIN student_identification ON student_identification.id=studentEnrollments.student_fk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'register_type' => Yii::t('default', 'Register Type'),
            'school_inep_fk' => Yii::t('default', 'School Inep Fk'),
            'inep_id' => Yii::t('default', 'Inep'),
            'id' => Yii::t('default', 'ID'),
            'name' => Yii::t('default', 'Name'),
            'pedagogical_mediation_type' => Yii::t('default', 'Pedagogical Mediation Type'),
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
            'assistance_type' => Yii::t('default', 'Differentiated Operating Place'),
            'mais_educacao_participator' => Yii::t('default', 'Mais Educacao Participator'),
            'complementary_activity_type_1' => Yii::t('default', 'Complementary Activity Type 1'),
            'complementary_activity_type_2' => Yii::t('default', 'Complementary Activity Type 2'),
            'complementary_activity_type_3' => Yii::t('default', 'Complementary Activity Type 3'),
            'complementary_activity_type_4' => Yii::t('default', 'Complementary Activity Type 4'),
            'complementary_activity_type_5' => Yii::t('default', 'Complementary Activity Type 5'),
            'complementary_activity_type_6' => Yii::t('default', 'Complementary Activity Type 6'),
            'aee_braille' => Yii::t('default', 'Aee Braille System Education'),
            'aee_optical_nonoptical' => Yii::t('default', 'Aee Optical And Non Optical Resources'),
            'aee_cognitive_functions' => Yii::t('default', 'Aee Mental Processes Development Strategies'),
            'aee_mobility_techniques' => Yii::t('default', 'Aee Mobility And Orientation Techniques'),
            'aee_libras' => Yii::t('default', 'Aee Libras'),
            'aee_caa' => Yii::t('default', 'Aee Caa Use Education'),
            'aee_curriculum_enrichment' => Yii::t('default', 'Aee Curriculum Enrichment Strategy'),
            'aee_soroban' => Yii::t('default', 'Aee Soroban Use Education'),
            'aee_accessible_teaching' => Yii::t('default', 'Aee Usability And Functionality Of Computer Accessible Education'),
            'aee_portuguese' => Yii::t('default', 'Aee Teaching Of Portuguese Language Written Modality'),
            'aee_autonomous_life' => Yii::t('default', 'Aee Strategy For School Environment Autonomy'),
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
            'school_year' => Yii::t('default', 'School Year'),
            'turn' => Yii::t('default', 'Turn'),
            'create_date' => Yii::t('default', 'Create Time'),
            // Support Labels 
            'disciplines' => Yii::t('default', 'Disciplines'),
            'classroom_days' => Yii::t('default', 'Classsrom Days'),
            'stage' => Yii::t('default', 'Stage'),
            'schooling' => Yii::t('default', 'Schooling'),
            'diff_location' => Yii::t('default', 'Classroom Diff Location'),
            'course' => Yii::t('default', 'Course'),
            'complementary_activity' => Yii::t('default', 'Complementary Activity'),
            'aee' => Yii::t('default', 'AEE'),
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
    public function search($is_default_theme = true)
    {
        //  Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('register_type', $this->register_type, true);
        //$criteria->with = array('edcensoStageVsModalityFk');
        if ($is_default_theme == true) {
            $criteria->compare('school_inep_fk', Yii::app()->user->school);
        }
        $criteria->compare('inep_id', $this->inep_id, true);
        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('school_year', Yii::app()->user->year);
        //$criteria->addCondition('edcensoStageVsModalityFk.name like "%' . $this->edcensoStageVsModalityFk . '%"');

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => array(
                    'school_inep_fk' => CSort::SORT_ASC,
                    'name' => CSort::SORT_ASC,
                ),
            ),
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));
    }

    /**
     * @param int $disciplineId
     * @return Classes[]
     */
    public function getGivenClassesByDiscipline($disciplineId)
    {
        $schedules = [];
        foreach ($this->schedules as $schedule) {
            if ($schedule->discipline_fk == $disciplineId && $schedule->unavailable == 0) {
                array_push($schedules, $schedule);
            }
        }
        return $schedules;
    }

    public function getSchoolDaysByExam($exam)
    {
        /* @var $schoolConfiguration SchoolConfiguration */
        $schoolConfiguration = SchoolConfiguration::model()->findByAttributes(['school_inep_id_fk' => yii::app()->user->school]);
        $schoolDays = 0;
        switch ($exam) {
            case 1:
                $initial = new DateTime("01/01/" . yii::app()->user->year);
                $final = new DateTime($schoolConfiguration->exam1);
                break;
            case 2:
                $initial = new DateTime($schoolConfiguration->exam1);
                $final = new DateTime($schoolConfiguration->exam2);
                break;
            case 3:
                $initial = new DateTime($schoolConfiguration->exam2);
                $final = new DateTime($schoolConfiguration->exam3);
                break;
            case 4:
                $initial = new DateTime($schoolConfiguration->exam3);
                $final = new DateTime($schoolConfiguration->exam4);
                break;
            default:
                return [];
        }

        $dates = [];
        foreach ($this->schedules as $schedule) {
            $date = new DateTime($schedule->day . "-" . $schedule->month . "-" . yii::app()->user->year);
            if ($date > $initial && $date <= $final && $schedule->unavailable == 0) {
                if (!in_array($date->format("Y-m-d"), $dates)) {
                    array_push($dates, $date->format("Y-m-d"));
                    $schoolDays++;
                }
            }
        }
        return $schoolDays;
    }

    public function getWorkingHoursByExam($exam)
    {
        /* @var $schoolConfiguration SchoolConfiguration */
        $schoolConfiguration = SchoolConfiguration::model()->findByAttributes(['school_inep_id_fk' => yii::app()->user->school]);
        $workingHours = 0;
        switch ($exam) {
            case 1:
                $initial = new DateTime("01/01/" . yii::app()->user->year);
                $final = new DateTime($schoolConfiguration->exam1);
                break;
            case 2:
                $initial = new DateTime($schoolConfiguration->exam1);
                $final = new DateTime($schoolConfiguration->exam2);
                break;
            case 3:
                $initial = new DateTime($schoolConfiguration->exam2);
                $final = new DateTime($schoolConfiguration->exam3);
                break;
            case 4:
                $initial = new DateTime($schoolConfiguration->exam3);
                $final = new DateTime($schoolConfiguration->exam4);
                break;
            default:
                return [];
        }

        foreach ($this->schedules as $schedule) {
            $date = new DateTime($schedule->day . "-" . $schedule->month . "-" . yii::app()->user->year);
            if ($date > $initial && $date <= $final && $schedule->unavailable == 0) {
                $workingHours++;
            }
        }
        return $workingHours;
    }

    public function getWorkingDaysByDiscipline($discipline)
    {
        /* @var $schoolConfiguration SchoolConfiguration */
        $model = WorkByDiscipline::model()->find('classroom_fk=:classroom_fk AND discipline_fk=:discipline_fk', array(':classroom_fk' => $this->id, ':discipline_fk' => $discipline));
        return $model->school_days;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Classroom the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

}
