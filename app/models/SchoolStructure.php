<?php

/**
 * This is the model class for table "school_structure".
 *
 * The followings are the available columns in table 'school_structure':
 * @property string $register_type
 * @property string $school_inep_id_fk
 * @property integer $operation_location_building
 * @property integer $operation_location_temple
 * @property integer $operation_location_businness_room
 * @property integer $operation_location_instructor_house
 * @property integer $operation_location_other_school_room
 * @property integer $operation_location_barracks
 * @property integer $operation_location_socioeducative_unity
 * @property integer $operation_location_prison_unity
 * @property integer $operation_location_other
 * @property integer $building_occupation_situation
 * @property integer $shared_building_with_school
 * @property string $shared_school_inep_id_1
 * @property string $shared_school_inep_id_2
 * @property string $shared_school_inep_id_3
 * @property string $shared_school_inep_id_4
 * @property string $shared_school_inep_id_5
 * @property string $shared_school_inep_id_6
 * @property integer $consumed_water_type
 * @property integer $water_supply_public
 * @property integer $water_supply_artesian_well
 * @property integer $water_supply_well
 * @property integer $water_supply_river
 * @property integer $water_supply_inexistent
 * @property integer $energy_supply_public
 * @property integer $energy_supply_generator
 * @property integer $energy_supply_generator_alternative
 * @property integer $energy_supply_other
 * @property integer $energy_supply_inexistent
 * @property integer $sewage_public
 * @property integer $sewage_fossa
 * @property integer $sewage_inexistent
 * @property integer $garbage_destination_collect
 * @property integer $garbage_destination_burn
 * @property integer $garbage_destination_throw_away
 * @property integer $garbage_destination_recycle
 * @property integer $garbage_destination_bury
 * @property integer $garbage_destination_other
 * @property integer $dependencies_principal_room
 * @property integer $dependencies_instructors_room
 * @property integer $dependencies_secretary_room
 * @property integer $dependencies_info_lab
 * @property integer $dependencies_science_lab
 * @property integer $dependencies_aee_room
 * @property integer $dependencies_indoor_sports_court
 * @property integer $dependencies_outdoor_sports_court
 * @property integer $dependencies_kitchen
 * @property integer $dependencies_library
 * @property integer $dependencies_reading_room
 * @property integer $dependencies_playground
 * @property integer $dependencies_nursery
 * @property integer $dependencies_outside_bathroom
 * @property integer $dependencies_inside_bathroom
 * @property integer $dependencies_child_bathroom
 * @property integer $dependencies_prysical_disability_bathroom
 * @property integer $dependencies_physical_disability_support
 * @property integer $dependencies_bathroom_with_shower
 * @property integer $dependencies_bathroom_workes
 * @property integer $dependencies_refectory
 * @property integer $dependencies_storeroom
 * @property integer $dependencies_warehouse
 * @property integer $dependencies_auditorium
 * @property integer $dependencies_covered_patio
 * @property integer $dependencies_uncovered_patio
 * @property integer $dependencies_student_accomodation
 * @property integer $dependencies_instructor_accomodation
 * @property integer $dependencies_green_area
 * @property integer $dependencies_laundry
 * @property integer $dependencies_professional_specific_lab
 * @property integer $dependencies_vocational_education_workshop
 * @property integer $dependencies_none
 * @property integer $classroom_count
 * @property integer $used_classroom_count
 * @property integer $equipments_tv
 * @property integer $equipments_vcr
 * @property integer $equipments_dvd
 * @property integer $equipments_satellite_dish
 * @property integer $equipments_copier
 * @property integer $equipments_overhead_projector
 * @property integer $equipments_printer
 * @property integer $equipments_stereo_system
 * @property integer $equipments_data_show
 * @property integer $equipments_fax
 * @property integer $equipments_camera
 * @property integer $equipments_computer
 * @property integer $equipments_multifunctional_printer
 * @property integer $equipments_inexistent
 * @property integer $equipments_qtd_desktop
 * @property integer $equipments_material_professional_education
 * @property integer $instruments_inexistent
 * @property integer $administrative_computers_count
 * @property integer $student_computers_count
 * @property integer $internet_access
 * @property integer $bandwidth
 * @property integer $employees_count
 * @property integer $feeding
 * @property integer $aee
 * @property integer $complementary_activities
 * @property integer $modalities_regular
 * @property integer $modalities_especial
 * @property integer $modalities_eja
 * @property integer $modalities_professional
 * @property integer $basic_education_cycle_organized
 * @property integer $different_location
 * @property integer $sociocultural_didactic_material_none
 * @property integer $sociocultural_didactic_material_quilombola
 * @property integer $sociocultural_didactic_material_native
 * @property integer $native_education
 * @property integer $native_education_language_native
 * @property integer $native_education_language_portuguese
 * @property integer $edcenso_native_languages_fk
 * @property integer $brazil_literate
 * @property integer $open_weekend
 * @property integer $pedagogical_formation_by_alternance
 */
class SchoolStructure extends AltActiveRecord
{
    public $stages_concept_grades;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SchoolStructure the static model class
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
		return 'school_structure';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('school_inep_id_fk', 'required'),
			array('operation_location_building, operation_location_temple, operation_location_businness_room, operation_location_instructor_house, operation_location_other_school_room, operation_location_barracks, operation_location_socioeducative_unity, operation_location_prison_unity, operation_location_other, building_occupation_situation, shared_building_with_school, consumed_water_type, water_supply_public, water_supply_artesian_well, water_supply_well, water_supply_river, water_supply_inexistent, energy_supply_public, energy_supply_generator, energy_supply_generator_alternative, energy_supply_other, energy_supply_inexistent, sewage_public, sewage_fossa, sewage_inexistent, garbage_destination_collect, garbage_destination_burn, garbage_destination_throw_away, garbage_destination_recycle, garbage_destination_bury, garbage_destination_other, dependencies_principal_room, dependencies_instructors_room, dependencies_secretary_room, dependencies_info_lab, dependencies_science_lab, dependencies_aee_room, dependencies_indoor_sports_court, dependencies_outdoor_sports_court, dependencies_kitchen, dependencies_library, dependencies_reading_room, dependencies_playground, dependencies_nursery, dependencies_outside_bathroom, dependencies_inside_bathroom, dependencies_child_bathroom, dependencies_prysical_disability_bathroom, dependencies_physical_disability_support, dependencies_bathroom_with_shower, dependencies_bathroom_workes, dependencies_refectory, dependencies_storeroom, dependencies_warehouse, dependencies_auditorium, dependencies_covered_patio, dependencies_uncovered_patio, dependencies_student_accomodation, dependencies_instructor_accomodation, dependencies_green_area, dependencies_laundry, dependencies_professional_specific_lab, dependencies_vocational_education_workshop, dependencies_none, classroom_count, used_classroom_count, instruments_inexistent, equipments_material_professional_education, equipments_tv, equipments_vcr, equipments_dvd, equipments_satellite_dish, equipments_copier, equipments_overhead_projector, equipments_printer, equipments_stereo_system, equipments_data_show, equipments_fax, equipments_camera, equipments_computer, equipments_multifunctional_printer, equipments_inexistent, administrative_computers_count, student_computers_count, internet_access, bandwidth, employees_count, feeding, aee, complementary_activities, modalities_regular, modalities_especial, modalities_eja, modalities_professional, basic_education_cycle_organized, different_location, sociocultural_didactic_material_none, sociocultural_didactic_material_quilombola, sociocultural_didactic_material_native, native_education, native_education_language_native, native_education_language_portuguese, edcenso_native_languages_fk, brazil_literate, open_weekend, pedagogical_formation_by_alternance, building_otherschool, sewage_fossa_common, garbage_destination_public, supply_food, treatment_garbage_parting_garbage, treatment_garbage_resuse, traetment_garbage_inexistent, dependencies_prysical_disability_bathroom, dependencies_pool, dependencies_arts_room, dependencies_music_room, dependencies_dance_room, dependencies_multiuse_room, dependencies_yardzao, dependencies_vivarium, dependencies_outside_roomspublic, dependencies_indoor_roomspublic, dependencies_climate_roomspublic,dependencies_acessibility_roomspublic, acessability_handrails_guardrails, acessability_elevator, acessability_tactile_floor, acessability_doors_80cm, acessability_ramps, acessability_sound_signaling, acessability_tactile_singnaling,acessability_visual_signaling, acessabilty_inexistent, equipments_scanner, equipments_qtd_blackboard, equipments_qtd_desktop, equipments_qtd_notebookstudent, equipments_qtd_tabletstudent,equipments_multimedia_collection, equipments_toys_early, equipments_scientific_materials, equipments_equipment_amplification, equipments_musical_instruments, equipments_educational_games, equipments_material_cultural, equipments_material_sports, equipments_material_teachingindian, equipments_material_teachingethnic, equipments_material_teachingrural, internet_access_administrative, internet_access_educative_process, internet_access_student, internet_access_community, internet_access_inexistent, internet_access_connected_personaldevice, internet_access_connected_desktop, internet_access_broadband, internet_access_local_cable, internet_access_local_wireless, internet_access_local_inexistet, workers_administrative_assistant, workers_service_assistant, workers_librarian, workers_firefighter, workers_coordinator_shift, workers_speech_therapist, workers_nutritionist, workers_psychologist, workers_cooker, workers_support_professionals, workers_school_secretary, workers_security_guards, workers_monitors, org_teaching_series_year, org_teaching_semester_periods, org_teaching_elementary_cycle, org_teaching_non_serialgroups, org_teaching_modules, org_teaching_regular_alternation, edcenso_native_languages_fk2, edcenso_native_languages_fk3, select_adimission, booking_enrollment_self_declaredskin, booking_enrollment_income, booking_enrollment_public_school, booking_enrollment_disabled_person, booking_enrollment_others, booking_enrollment_inexistent, website, community_integration, space_schoolenviroment, ppp_updated, board_organ_association_parent, board_organ_association_parentinstructors, board_organ_board_school, board_organ_student_guild, board_organ_others, board_organ_inexistent, provide_potable_water, dependencies_student_repose_room', 'numerical', 'integerOnly'=>true),
			array('register_type', 'length', 'max'=>2),
			array('school_inep_id_fk, shared_school_inep_id_1, shared_school_inep_id_2, shared_school_inep_id_3, shared_school_inep_id_4, shared_school_inep_id_5, shared_school_inep_id_6', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('register_type, school_inep_id_fk, operation_location_building, operation_location_temple, operation_location_businness_room, operation_location_instructor_house, operation_location_other_school_room, operation_location_barracks, operation_location_socioeducative_unity, operation_location_prison_unity, operation_location_other, building_occupation_situation, shared_building_with_school, shared_school_inep_id_1, shared_school_inep_id_2, shared_school_inep_id_3, shared_school_inep_id_4, shared_school_inep_id_5, shared_school_inep_id_6, consumed_water_type, water_supply_public, water_supply_artesian_well, water_supply_well, water_supply_river, water_supply_inexistent, energy_supply_public, energy_supply_generator, energy_supply_other, energy_supply_inexistent, sewage_public, sewage_fossa, sewage_inexistent, garbage_destination_collect, garbage_destination_burn, garbage_destination_throw_away, garbage_destination_recycle, garbage_destination_bury, garbage_destination_other, dependencies_principal_room, dependencies_instructors_room, dependencies_secretary_room, dependencies_info_lab, dependencies_science_lab, dependencies_aee_room, dependencies_indoor_sports_court, dependencies_outdoor_sports_court, dependencies_kitchen, dependencies_library, dependencies_reading_room, dependencies_playground, dependencies_nursery, dependencies_outside_bathroom, dependencies_inside_bathroom, dependencies_child_bathroom, dependencies_prysical_disability_bathroom, dependencies_physical_disability_support, dependencies_bathroom_with_shower, dependencies_refectory, dependencies_storeroom, dependencies_warehouse, dependencies_auditorium, dependencies_covered_patio, dependencies_uncovered_patio, dependencies_student_accomodation, dependencies_instructor_accomodation, dependencies_green_area, dependencies_laundry, dependencies_professional_specific_lab, dependencies_vocational_education_workshop, dependencies_none, classroom_count, used_classroom_count, instruments_inexistent, equipments_material_professional_education, equipments_tv, equipments_vcr, equipments_dvd, equipments_satellite_dish, equipments_copier, equipments_overhead_projector, equipments_printer, equipments_stereo_system, equipments_data_show, equipments_fax, equipments_camera, equipments_computer, equipments_multifunctional_printer, equipments_inexistent, administrative_computers_count, student_computers_count, internet_access, bandwidth, employees_count, feeding, aee, complementary_activities, modalities_regular, modalities_especial, modalities_eja, modalities_professional, basic_education_cycle_organized, different_location, sociocultural_didactic_material_none, sociocultural_didactic_material_quilombola, sociocultural_didactic_material_native, native_education, native_education_language_native, native_education_language_portuguese, edcenso_native_languages_fk, brazil_literate, open_weekend, pedagogical_formation_by_alternance', 'safe', 'on'=>'search'),
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
			'operation_location_building' => Yii::t('default', 'Operation Location Building'),
			'operation_location_temple' => Yii::t('default', 'Operation Location Temple'),
			'operation_location_businness_room' => Yii::t('default', 'Operation Location Businness Room'),
			'operation_location_instructor_house' => Yii::t('default', 'Operation Location Instructor House'),
			'operation_location_other_school_room' => Yii::t('default', 'Operation Location Other School Room'),
			'operation_location_barracks' => Yii::t('default', 'Operation Location Barracks'),
			'operation_location_socioeducative_unity' => Yii::t('default', 'Operation Location Socioeducative Unity'),
			'operation_location_prison_unity' => Yii::t('default', 'Operation Location Prison Unity'),
			'operation_location_other' => Yii::t('default', 'Operation Location Other'),
			'building_occupation_situation' => Yii::t('default', 'Building Occupation Situation'),
			'shared_building_with_school' => Yii::t('default', 'Shared Building With School'),
			'shared_school_inep_id_1' => Yii::t('default', 'Shared School Inep Id 1'),
			'shared_school_inep_id_2' => Yii::t('default', 'Shared School Inep Id 2'),
			'shared_school_inep_id_3' => Yii::t('default', 'Shared School Inep Id 3'),
			'shared_school_inep_id_4' => Yii::t('default', 'Shared School Inep Id 4'),
			'shared_school_inep_id_5' => Yii::t('default', 'Shared School Inep Id 5'),
			'shared_school_inep_id_6' => Yii::t('default', 'Shared School Inep Id 6'),
			'consumed_water_type' => Yii::t('default', 'Consumed Water Type'),
			'water_supply_public' => Yii::t('default', 'Water Supply Public'),
			'water_supply_artesian_well' => Yii::t('default', 'Water Supply Artesian Well'),
			'water_supply_well' => Yii::t('default', 'Water Supply Well'),
			'water_supply_river' => Yii::t('default', 'Water Supply River'),
			'water_supply_inexistent' => Yii::t('default', 'Water Supply Inexistent'),
			'energy_supply_public' => Yii::t('default', 'Energy Supply Public'),
			'energy_supply_generator' => Yii::t('default', 'Energy Supply Generator'),
			'energy_supply_other' => Yii::t('default', 'Energy Supply Other'),
			'energy_supply_inexistent' => Yii::t('default', 'Energy Supply Inexistent'),
			'sewage_public' => Yii::t('default', 'Sewage Public'),
			'sewage_fossa' => Yii::t('default', 'Sewage Fossa'),
			'sewage_inexistent' => Yii::t('default', 'Sewage Inexistent'),
			'garbage_destination_collect' => Yii::t('default', 'Garbage Destination Collect'),
			'garbage_destination_burn' => Yii::t('default', 'Garbage Destination Burn'),
			'garbage_destination_throw_away' => Yii::t('default', 'Garbage Destination Throw Away'),
			'garbage_destination_recycle' => Yii::t('default', 'Garbage Destination Recycle'),
			'garbage_destination_bury' => Yii::t('default', 'Garbage Destination Bury'),
			'garbage_destination_other' => Yii::t('default', 'Garbage Destination Other'),
			'dependencies_principal_room' => Yii::t('default', 'Dependencies Principal Room'),
			'dependencies_instructors_room' => Yii::t('default', 'Dependencies Instructors Room'),
			'dependencies_secretary_room' => Yii::t('default', 'Dependencies Secretary Room'),
			'dependencies_info_lab' => Yii::t('default', 'Dependencies Info Lab'),
			'dependencies_science_lab' => Yii::t('default', 'Dependencies Science Lab'),
			'dependencies_aee_room' => Yii::t('default', 'Dependencies Aee Room'),
			'dependencies_indoor_sports_court' => Yii::t('default', 'Dependencies Indoor Sports Court'),
			'dependencies_outdoor_sports_court' => Yii::t('default', 'Dependencies Outdoor Sports Court'),
			'dependencies_kitchen' => Yii::t('default', 'Dependencies Kitchen'),
			'dependencies_library' => Yii::t('default', 'Dependencies Library'),
			'dependencies_reading_room' => Yii::t('default', 'Dependencies Reading Room'),
			'dependencies_playground' => Yii::t('default', 'Dependencies Playground'),
			'dependencies_nursery' => Yii::t('default', 'Dependencies Nursery'),
			'dependencies_outside_bathroom' => Yii::t('default', 'Dependencies Outside Bathroom'),
			'dependencies_inside_bathroom' => Yii::t('default', 'Dependencies Inside Bathroom'),
			'dependencies_child_bathroom' => Yii::t('default', 'Dependencies Child Bathroom'),
			'dependencies_prysical_disability_bathroom' => Yii::t('default', 'Dependencies Prysical Disability Bathroom'),
			'dependencies_physical_disability_support' => Yii::t('default', 'Dependencies Physical Disability Support'),
			'dependencies_bathroom_with_shower' => Yii::t('default', 'Dependencies Bathroom With Shower'),
			'dependencies_refectory' => Yii::t('default', 'Dependencies Refectory'),
			'dependencies_storeroom' => Yii::t('default', 'Dependencies Storeroom'),
			'dependencies_warehouse' => Yii::t('default', 'Dependencies Warehouse'),
			'dependencies_auditorium' => Yii::t('default', 'Dependencies Auditorium'),
			'dependencies_covered_patio' => Yii::t('default', 'Dependencies Covered Patio'),
			'dependencies_uncovered_patio' => Yii::t('default', 'Dependencies Uncovered Patio'),
			'dependencies_student_accomodation' => Yii::t('default', 'Dependencies Student Accomodation'),
			'dependencies_instructor_accomodation' => Yii::t('default', 'Dependencies Instructor Accomodation'),
			'dependencies_green_area' => Yii::t('default', 'Dependencies Green Area'),
			'dependencies_laundry' => Yii::t('default', 'Dependencies Laundry'),
			'dependencies_professional_specific_lab' => Yii::t('default', 'Dependencies Professional Specific Lab'),
            'dependencies_vocational_education_workshop' => Yii::t('default', 'Dependencies Vocational Education Workshop'),
			'dependencies_none' => Yii::t('default', 'Dependencies None'),
			'classroom_count' => Yii::t('default', 'Classroom Count'),
			'used_classroom_count' => Yii::t('default', 'Used Classroom Count'),
			'equipments_tv' => Yii::t('default', 'Equipments Tv'),
			'equipments_vcr' => Yii::t('default', 'Equipments Vcr'),
			'equipments_dvd' => Yii::t('default', 'Equipments Dvd'),
			'equipments_material_professional_education' => Yii::t('default', 'Equipments Material Professional Education'),
			'instruments_inexistent' => Yii::t('default', 'Instruments Inexistent'),
			'equipments_satellite_dish' => Yii::t('default', 'Equipments Satellite Dish'),
			'equipments_copier' => Yii::t('default', 'Equipments Copier'),
			'equipments_overhead_projector' => Yii::t('default', 'Equipments Overhead Projector'),
			'equipments_printer' => Yii::t('default', 'Equipments Printer'),
			'equipments_stereo_system' => Yii::t('default', 'Equipments Stereo System'),
			'equipments_data_show' => Yii::t('default', 'Equipments Data Show'),
			'equipments_fax' => Yii::t('default', 'Equipments Fax'),
			'equipments_camera' => Yii::t('default', 'Equipments Camera'),
			'equipments_computer' => Yii::t('default', 'Equipments Computer'),
			'equipments_multifunctional_printer' => Yii::t('default', 'Equipments Multifunctional Printer'),
			'equipments_inexistent' => Yii::t('default', 'Equipments Inexistent'),
			'administrative_computers_count' => Yii::t('default', 'Administrative Computers Count'),
			'student_computers_count' => Yii::t('default', 'Student Computers Count'),
			'internet_access' => Yii::t('default', 'Have Internet Access'),
			'bandwidth' => Yii::t('default', 'Bandwidth'),
			'employees_count' => Yii::t('default', 'Employees Count'),
			'feeding' => Yii::t('default', 'Feeding'),
			'aee' => Yii::t('default', 'Aee'),
			'complementary_activities' => Yii::t('default', 'Complementary Activities'),
			'modalities_regular' => Yii::t('default', 'Modalities Regular'),
			'modalities_especial' => Yii::t('default', 'Modalities Especial'),
			'modalities_eja' => Yii::t('default', 'Modalities Eja'),
			'modalities_professional' => Yii::t('default', 'Modalities Professional'),
			'basic_education_cycle_organized' => Yii::t('default', 'Basic Education Cycle Organized'),
			'different_location' => Yii::t('default', 'Different Location'),
			'sociocultural_didactic_material_none' => Yii::t('default', 'Sociocultural Didactic Material None'),
			'sociocultural_didactic_material_quilombola' => Yii::t('default', 'Sociocultural Didactic Material Quilombola'),
			'sociocultural_didactic_material_native' => Yii::t('default', 'Sociocultural Didactic Material Native'),
			'native_education' => Yii::t('default', 'Native Education'),
			'native_education_language_native' => Yii::t('default', 'Native Education Language Native'),
			'native_education_language_portuguese' => Yii::t('default', 'Native Education Language Portuguese'),
			'edcenso_native_languages_fk' => Yii::t('default', 'Edcenso Native Languages Fk'),
			'edcenso_native_languages_fk2' => (Yii::t('default', 'Edcenso Native Languages Fk') . ' 2'),
			'edcenso_native_languages_fk3' => (Yii::t('default', 'Edcenso Native Languages Fk') . ' 3'),
			'brazil_literate' => Yii::t('default', 'Brazil Literate'),
			'open_weekend' => Yii::t('default', 'Open Weekend'),
			'pedagogical_formation_by_alternance' => Yii::t('default', 'Pedagogical Formation By Alternance'),
			'building_otherschool' => Yii::t('default', 'Building Others School'),
			'energy_supply_generator_alternative' => Yii::t('default', 'Generator Alternative'),
			'sewage_fossa_common' => Yii::t('default', 'Fossa Common'),
			'garbage_destination_public' => Yii::t('default', 'Destination Public'),
			'supply_food' => Yii::t('default', 'Food'),
			'treatment_garbage_parting_garbage' => Yii::t('default', 'Garbage Parting'),
			'treatment_garbage_resuse' => Yii::t('default', 'Garbage Resuse'),
			'traetment_garbage_inexistent' => Yii::t('default', 'Garbage Inexistent'),
			'dependencies_bathroom_workes' => Yii::t('default', 'Bathroom Workes'),
			'dependencies_pool' => Yii::t('default', 'Pool'),
			'dependencies_arts_room' => Yii::t('default', 'Arts Room'),
			'dependencies_music_room' => Yii::t('default', 'Music Room'),
			'dependencies_dance_room' => Yii::t('default', 'Dance Room'),
			'dependencies_multiuse_room' => Yii::t('default', 'Multiuse Room'),
			'dependencies_yardzao' => Yii::t('default', 'Yardzao'),
			'dependencies_vivarium' => Yii::t('default', 'Vivarium'),
			'dependencies_outside_roomspublic' => Yii::t('default', 'Outside Rooms Public'),
			'dependencies_indoor_roomspublic' => Yii::t('default', 'Indoor Rooms Public'),
			'dependencies_climate_roomspublic' => Yii::t('default', 'Climate Rooms Public'),
			'dependencies_acessibility_roomspublic' => Yii::t('default', 'Acessibility Rooms Public'),
			'acessability_handrails_guardrails' => Yii::t('default', 'Handrails Guardrails'),
			'acessability_elevator' => Yii::t('default', 'Elevator'),
			'acessability_tactile_floor' => Yii::t('default', 'Tactile Floor'),
			'acessability_doors_80cm' => Yii::t('default', 'Doors 80'),
			'acessability_ramps' => Yii::t('default', 'Ramps'),
			'acessability_sound_signaling' => Yii::t('default', 'Sound Signaling'),
			'acessability_tactile_singnaling' => Yii::t('default', 'Tactile Signaling'),
			'acessability_visual_signaling' => Yii::t('default', 'Visual Signaling'),
			'acessabilty_inexistent' => Yii::t('default', 'Acessability Inexistent'),
			'equipments_scanner' => Yii::t('default', 'Scanner'),
			'equipments_qtd_blackboard' => Yii::t('default', 'Black Board'),
			'equipments_qtd_notebookstudent' => Yii::t('default', 'Notebook Student'),
			'equipments_qtd_desktop' => Yii::t('default', 'Desktop'),
			'equipments_qtd_tabletstudent' => Yii::t('default', 'Tablet Student'),
			'equipments_multimedia_collection' => Yii::t('default', 'Multimedia Collection'),
			'equipments_toys_early' => Yii::t('default', 'Toys Early'),
			'equipments_scientific_materials' => Yii::t('default', 'Scientific Materials'),
			'equipments_equipment_amplification' => Yii::t('default', 'Equipment Amplification'),
			'equipments_musical_instruments' => Yii::t('default', 'Musical Instruments'),
			'equipments_educational_games' => Yii::t('default', 'Educational Games'),
			'equipments_material_cultural' => Yii::t('default', 'Equipments Material Cultural'),
			'equipments_material_sports' => Yii::t('default', 'Equipments Material Sports'),
			'equipments_material_teachingindian' => Yii::t('default', 'Material Teaching Indian'),
			'equipments_material_teachingethnic' => Yii::t('default', 'Material Teaching Ethinic'),
			'equipments_material_teachingrural' => Yii::t('default', 'Material Teaching Rural'),
			'internet_access_administrative' => Yii::t('default', 'Internet Access Administrative'),
			'internet_access_educative_process' => Yii::t('default', 'Internet Access Educative'),
			'internet_access_student' => Yii::t('default', 'Internet Access Student'),
			'internet_access_community' => Yii::t('default', 'Internet Access Community'),
			'internet_access_inexistent' => Yii::t('default', 'Internet Access Inexistent'),
			'internet_access_connected_personaldevice' => Yii::t('default', 'Internet Access Personal Devices'),
			'internet_access_connected_desktop' => Yii::t('default', 'Internet Access Desktop'),
			'internet_access_broadband' => Yii::t('default', 'Internet Access Broadband'),
			'internet_access_local_cable' => Yii::t('default', 'Internet Access Cable'),
			'internet_access_local_wireless' => Yii::t('default', 'Internet Access Wireless'),
			'internet_access_local_inexistet' => Yii::t('default', 'Internet Access Inexistent'),
			'workers_administrative_assistant' => Yii::t('default', 'Administrative Assistant'),
			'workers_service_assistant' => Yii::t('default', 'Service Assistant'),
			'workers_librarian' => Yii::t('default', 'Librarian'),
			'workers_firefighter' => Yii::t('default', 'Firefighter'),
			'workers_coordinator_shift' => Yii::t('default', 'Coordinator Shift'),
			'workers_speech_therapist' => Yii::t('default', 'Speech Terapist'),
			'workers_nutritionist' => Yii::t('default', 'Nutritionist'),
			'workers_psychologist' => Yii::t('default', 'Psychologist'),
			'workers_cooker' => Yii::t('default', 'Cooker'),
			'workers_support_professionals' => Yii::t('default', 'Suport Professional'),
			'workers_school_secretary' => Yii::t('default', 'School Secretary'),
			'workers_security_guards' => Yii::t('default', 'Security Gaurds'),
			'workers_monitors' => Yii::t('default', 'Monitors'),
			'org_teaching_series_year' => Yii::t('default', 'Series Year'),
			'org_teaching_semester_periods' => Yii::t('default', 'Semester Periods'),
			'org_teaching_elementary_cycle' => Yii::t('default', 'Elementary Cycle'),
			'org_teaching_non_serialgroups' => Yii::t('default', 'Non Serial Groups'),
			'org_teaching_modules' => Yii::t('default', 'Teaching Modules'),
			'org_teaching_regular_alternation' => Yii::t('default', 'Regular Alternation'),
			'select_adimission' => Yii::t('default', 'Select Adimission'),
			'booking_enrollment_self_declaredskin' => Yii::t('default', 'Self Declared Skin'),
			'booking_enrollment_income' => Yii::t('default', 'Enrollment Icome'),
			'booking_enrollment_public_school' => Yii::t('default', 'Enrollment Public School'),
			'booking_enrollment_disabled_person' => Yii::t('default', 'Disable Person'),
			'booking_enrollment_others' => Yii::t('default', 'Enrollment Other'),
			'booking_enrollment_inexistent' => Yii::t('default', 'Enrollment Inexistent'),
			'website' => Yii::t('default', 'Website or Blog'),
			'community_integration' => Yii::t('default', 'Community Integration'),
			'space_schoolenviroment' => Yii::t('default', 'Space School Enviroment'),
			'ppp_updated' => Yii::t('default', 'PPP Updated'),
			'board_organ_association_parent' => Yii::t('default', 'Association Parent'),
			'board_organ_association_parentinstructors' => Yii::t('default', 'Association Parent Instructors'),
			'board_organ_board_school' => Yii::t('default', 'Borad School'),
			'board_organ_student_guild' => Yii::t('default', 'Student Guild'),
			'board_organ_others' => Yii::t('default', 'Organ Others'),
			'board_organ_inexistent' => Yii::t('default', 'Organ Inexistent'),
			'provide_potable_water' => Yii::t('default', 'Provide Potable Water'),
			'dependencies_student_repose_room' => Yii::t('default', 'Student Repose Room'),
            'stages_concept_grades' => Yii::t('default', 'Stages Concept Grades')
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
		$criteria->compare('manager_cpf',$this->manager_cpf,true);
		$criteria->compare('manager_name',$this->manager_name,true);
		$criteria->compare('manager_role',$this->manager_role);
		$criteria->compare('manager_email',$this->manager_email,true);
		$criteria->compare('operation_location_building',$this->operation_location_building);
		$criteria->compare('operation_location_temple',$this->operation_location_temple);
		$criteria->compare('operation_location_businness_room',$this->operation_location_businness_room);
		$criteria->compare('operation_location_instructor_house',$this->operation_location_instructor_house);
		$criteria->compare('operation_location_other_school_room',$this->operation_location_other_school_room);
		$criteria->compare('operation_location_barracks',$this->operation_location_barracks);
		$criteria->compare('operation_location_socioeducative_unity',$this->operation_location_socioeducative_unity);
		$criteria->compare('operation_location_prison_unity',$this->operation_location_prison_unity);
		$criteria->compare('operation_location_other',$this->operation_location_other);
		$criteria->compare('building_occupation_situation',$this->building_occupation_situation);
		$criteria->compare('shared_building_with_school',$this->shared_building_with_school);
		$criteria->compare('shared_school_inep_id_1',$this->shared_school_inep_id_1,true);
		$criteria->compare('shared_school_inep_id_2',$this->shared_school_inep_id_2,true);
		$criteria->compare('shared_school_inep_id_3',$this->shared_school_inep_id_3,true);
		$criteria->compare('shared_school_inep_id_4',$this->shared_school_inep_id_4,true);
		$criteria->compare('shared_school_inep_id_5',$this->shared_school_inep_id_5,true);
		$criteria->compare('shared_school_inep_id_6',$this->shared_school_inep_id_6,true);
		$criteria->compare('consumed_water_type',$this->consumed_water_type);
		$criteria->compare('water_supply_public',$this->water_supply_public);
		$criteria->compare('water_supply_artesian_well',$this->water_supply_artesian_well);
		$criteria->compare('water_supply_well',$this->water_supply_well);
		$criteria->compare('water_supply_river',$this->water_supply_river);
		$criteria->compare('water_supply_inexistent',$this->water_supply_inexistent);
		$criteria->compare('energy_supply_public',$this->energy_supply_public);
		$criteria->compare('energy_supply_generator',$this->energy_supply_generator);
		$criteria->compare('energy_supply_other',$this->energy_supply_other);
		$criteria->compare('energy_supply_inexistent',$this->energy_supply_inexistent);
		$criteria->compare('sewage_public',$this->sewage_public);
		$criteria->compare('sewage_fossa',$this->sewage_fossa);
		$criteria->compare('sewage_inexistent',$this->sewage_inexistent);
		$criteria->compare('garbage_destination_collect',$this->garbage_destination_collect);
		$criteria->compare('garbage_destination_burn',$this->garbage_destination_burn);
		$criteria->compare('garbage_destination_throw_away',$this->garbage_destination_throw_away);
		$criteria->compare('garbage_destination_recycle',$this->garbage_destination_recycle);
		$criteria->compare('garbage_destination_bury',$this->garbage_destination_bury);
		$criteria->compare('garbage_destination_other',$this->garbage_destination_other);
		$criteria->compare('dependencies_principal_room',$this->dependencies_principal_room);
		$criteria->compare('dependencies_instructors_room',$this->dependencies_instructors_room);
		$criteria->compare('dependencies_secretary_room',$this->dependencies_secretary_room);
		$criteria->compare('dependencies_info_lab',$this->dependencies_info_lab);
		$criteria->compare('dependencies_science_lab',$this->dependencies_science_lab);
		$criteria->compare('dependencies_aee_room',$this->dependencies_aee_room);
		$criteria->compare('dependencies_indoor_sports_court',$this->dependencies_indoor_sports_court);
		$criteria->compare('dependencies_outdoor_sports_court',$this->dependencies_outdoor_sports_court);
		$criteria->compare('dependencies_kitchen',$this->dependencies_kitchen);
		$criteria->compare('dependencies_library',$this->dependencies_library);
		$criteria->compare('dependencies_reading_room',$this->dependencies_reading_room);
		$criteria->compare('dependencies_playground',$this->dependencies_playground);
		$criteria->compare('dependencies_nursery',$this->dependencies_nursery);
		$criteria->compare('dependencies_outside_bathroom',$this->dependencies_outside_bathroom);
		$criteria->compare('dependencies_inside_bathroom',$this->dependencies_inside_bathroom);
		$criteria->compare('dependencies_child_bathroom',$this->dependencies_child_bathroom);
		$criteria->compare('dependencies_prysical_disability_bathroom',$this->dependencies_prysical_disability_bathroom);
		$criteria->compare('dependencies_physical_disability_support',$this->dependencies_physical_disability_support);
		$criteria->compare('dependencies_bathroom_with_shower',$this->dependencies_bathroom_with_shower);
		$criteria->compare('dependencies_refectory',$this->dependencies_refectory);
		$criteria->compare('dependencies_storeroom',$this->dependencies_storeroom);
		$criteria->compare('dependencies_warehouse',$this->dependencies_warehouse);
		$criteria->compare('dependencies_auditorium',$this->dependencies_auditorium);
		$criteria->compare('dependencies_covered_patio',$this->dependencies_covered_patio);
		$criteria->compare('dependencies_uncovered_patio',$this->dependencies_uncovered_patio);
		$criteria->compare('dependencies_student_accomodation',$this->dependencies_student_accomodation);
		$criteria->compare('dependencies_instructor_accomodation',$this->dependencies_instructor_accomodation);
		$criteria->compare('dependencies_green_area',$this->dependencies_green_area);
		$criteria->compare('dependencies_laundry',$this->dependencies_laundry);
        $criteria->compare('dependencies_professional_specific_lab',$this->dependencies_professional_specific_lab);
        $criteria->compare('dependencies_vocational_education_workshop',$this->dependencies_vocational_education_workshop);
		$criteria->compare('dependencies_none',$this->dependencies_none);
		$criteria->compare('classroom_count',$this->classroom_count);
		$criteria->compare('used_classroom_count',$this->used_classroom_count);
		$criteria->compare('equipments_tv',$this->equipments_tv);
		$criteria->compare('equipments_vcr',$this->equipments_vcr);
		$criteria->compare('equipments_dvd',$this->equipments_dvd);
		$criteria->compare('equipments_material_professional_education',$this->equipments_material_professional_education);
		$criteria->compare('instruments_inexistent', $this->instruments_inexistent);
		$criteria->compare('equipments_satellite_dish',$this->equipments_satellite_dish);
		$criteria->compare('equipments_copier',$this->equipments_copier);
		$criteria->compare('equipments_overhead_projector',$this->equipments_overhead_projector);
		$criteria->compare('equipments_printer',$this->equipments_printer);
		$criteria->compare('equipments_stereo_system',$this->equipments_stereo_system);
		$criteria->compare('equipments_data_show',$this->equipments_data_show);
		$criteria->compare('equipments_fax',$this->equipments_fax);
		$criteria->compare('equipments_camera',$this->equipments_camera);
		$criteria->compare('equipments_computer',$this->equipments_computer);
		$criteria->compare('administrative_computers_count',$this->administrative_computers_count);
		$criteria->compare('student_computers_count',$this->student_computers_count);
		$criteria->compare('internet_access',$this->internet_access);
		$criteria->compare('bandwidth',$this->bandwidth);
		$criteria->compare('employees_count',$this->employees_count);
		$criteria->compare('feeding',$this->feeding);
		$criteria->compare('aee',$this->aee);
		$criteria->compare('complementary_activities',$this->complementary_activities);
		$criteria->compare('modalities_regular',$this->modalities_regular);
		$criteria->compare('modalities_especial',$this->modalities_especial);
		$criteria->compare('modalities_eja',$this->modalities_eja);
		$criteria->compare('stage_regular_education_creche',$this->stage_regular_education_creche);
		$criteria->compare('stage_regular_education_preschool',$this->stage_regular_education_preschool);
		$criteria->compare('stage_regular_education_fundamental_eigth_years',$this->stage_regular_education_fundamental_eigth_years);
		$criteria->compare('stage_regular_education_fundamental_nine_years',$this->stage_regular_education_fundamental_nine_years);
		$criteria->compare('stage_regular_education_high_school',$this->stage_regular_education_high_school);
		$criteria->compare('stage_regular_education_high_school_integrated',$this->stage_regular_education_high_school_integrated);
		$criteria->compare('stage_regular_education_high_school_normal_mastership',$this->stage_regular_education_high_school_normal_mastership);
		$criteria->compare('stage_regular_education_high_school_preofessional_education',$this->stage_regular_education_high_school_preofessional_education);
		$criteria->compare('stage_special_education_creche',$this->stage_special_education_creche);
		$criteria->compare('stage_special_education_preschool',$this->stage_special_education_preschool);
		$criteria->compare('stage_special_education_fundamental_eigth_years',$this->stage_special_education_fundamental_eigth_years);
		$criteria->compare('stage_special_education_fundamental_nine_years',$this->stage_special_education_fundamental_nine_years);
		$criteria->compare('stage_special_education_high_school',$this->stage_special_education_high_school);
		$criteria->compare('stage_special_education_high_school_integrated',$this->stage_special_education_high_school_integrated);
		$criteria->compare('stage_special_education_high_school_normal_mastership',$this->stage_special_education_high_school_normal_mastership);
		$criteria->compare('stage_special_education_high_school_professional_education',$this->stage_special_education_high_school_professional_education);
		$criteria->compare('stage_special_education_eja_fundamental_education',$this->stage_special_education_eja_fundamental_education);
		$criteria->compare('stage_special_education_eja_high_school_education',$this->stage_special_education_eja_high_school_education);
		$criteria->compare('stage_education_eja_fundamental_education',$this->stage_education_eja_fundamental_education);
		$criteria->compare('stage_education_eja_fundamental_education_projovem',$this->stage_education_eja_fundamental_education_projovem);
		$criteria->compare('stage_education_eja_high_school_education',$this->stage_education_eja_high_school_education);
		$criteria->compare('basic_education_cycle_organized',$this->basic_education_cycle_organized);
		$criteria->compare('different_location',$this->different_location);
		$criteria->compare('sociocultural_didactic_material_none',$this->sociocultural_didactic_material_none);
		$criteria->compare('sociocultural_didactic_material_quilombola',$this->sociocultural_didactic_material_quilombola);
		$criteria->compare('sociocultural_didactic_material_native',$this->sociocultural_didactic_material_native);
		$criteria->compare('native_education',$this->native_education);
		$criteria->compare('native_education_language_native',$this->native_education_language_native);
		$criteria->compare('native_education_language_portuguese',$this->native_education_language_portuguese);
		$criteria->compare('edcenso_native_languages_fk',$this->edcenso_native_languages_fk);
		$criteria->compare('brazil_literate',$this->brazil_literate);
		$criteria->compare('open_weekend',$this->open_weekend);
		$criteria->compare('pedagogical_formation_by_alternance',$this->pedagogical_formation_by_alternance);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}