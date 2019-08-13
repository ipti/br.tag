ALTER TABLE `school_identification` 
ADD COLUMN `id_difflocation` SMALLINT(4) NULL AFTER `location`,
ADD COLUMN `linked_mec` TINYINT(1) NULL AFTER `id_difflocation`,
ADD COLUMN `linked_army` TINYINT(1) NULL AFTER `linked_mec`,
ADD COLUMN `linked_helth` TINYINT(1) NULL AFTER `linked_army`,
ADD COLUMN `linked_other` TINYINT(1) NULL AFTER `linked_helth`,
ADD COLUMN `regulation_organ` SMALLINT(4) NULL AFTER `regulation`;



ALTER TABLE `school_structure` 
ADD COLUMN `energy_supply_generator_alternative` TINYINT(1) NULL AFTER `energy_supply_public`,
ADD COLUMN `sewage_fossa_common` TINYINT(1) NULL AFTER `sewage_fossa`,
ADD COLUMN `garbage_destination_public` TINYINT(1) NULL AFTER `garbage_destination_bury`,
ADD COLUMN `treatment_garbage_parting_garbage` TINYINT(1) NULL AFTER `garbage_destination_other`,
ADD COLUMN `treatment_garbage_resuse` TINYINT(1) NULL AFTER `treatment_garbage_parting_garbage`,
ADD COLUMN `traetment_garbage_inexistent` TINYINT(1) NULL AFTER `treatment_garbage_resuse`,
ADD COLUMN `dependencies_bathroom_workes` TINYINT(1) NULL AFTER `dependencies_child_bathroom`,
ADD COLUMN `dependencies_arts_room` TINYINT(1) NULL AFTER `dependencies_refectory`,
ADD COLUMN `dependencies_music_room` TINYINT(1) NULL AFTER `dependencies_arts_room`,
ADD COLUMN `dependencies_dance_room` TINYINT(1) NULL AFTER `dependencies_music_room`,
ADD COLUMN `dependencies_multiuse_room` TINYINT(1) NULL AFTER `dependencies_dance_room`,
ADD COLUMN `dependencies_yardzao` TINYINT(1) NULL AFTER `dependencies_multiuse_room`,
ADD COLUMN `dependencies_vivarium` TINYINT(1) NULL AFTER `dependencies_yardzao`,
ADD COLUMN `dependencies_pool` TINYINT(1) NULL AFTER `dependencies_uncovered_patio`,
ADD COLUMN `acessability_handrails_guardrails` TINYINT(1) NULL AFTER `dependencies_none`,
ADD COLUMN `acessability_elevator` TINYINT(1) NULL AFTER `acessability_handrails_guardrails`,
ADD COLUMN `acessability_tactile_floor` TINYINT(1) NULL AFTER `acessability_elevator`,
ADD COLUMN `acessability_doors_80cm` TINYINT(1) NULL AFTER `acessability_tactile_floor`,
ADD COLUMN `acessability_ramps` TINYINT(1) NULL AFTER `acessability_doors_80cm`,
ADD COLUMN `acessability_sound_signaling` TINYINT(1) NULL AFTER `acessability_ramps`,
ADD COLUMN `acessability_tactile_singnaling` TINYINT(1) NULL AFTER `acessability_sound_signaling`,
ADD COLUMN `acessability_visual_signaling` TINYINT(1) NULL AFTER `acessability_tactile_singnaling`,
ADD COLUMN `acessabilty_inexistent` TINYINT(1) NULL AFTER `acessability_visual_signaling`,
ADD COLUMN `dependencies_outside_roomspublic` SMALLINT(4) NULL AFTER `acessabilty_inexistent`,
ADD COLUMN `dependencies_indoor_roomspublic` SMALLINT(4) NULL AFTER `dependencies_outside_roomspublic`,
ADD COLUMN `dependencies_climate_roomspublic` SMALLINT(4) NULL AFTER `dependencies_indoor_roomspublic`,
ADD COLUMN `dependencies_acessibility_roomspublic` SMALLINT(4) NULL AFTER `dependencies_climate_roomspublic`,
ADD COLUMN `equipments_qtd_blackboard` SMALLINT(4) NULL AFTER `equipments_tv`,
ADD COLUMN `equipments_qtd_notebookstudent` SMALLINT(4) NULL AFTER `equipments_qtd_blackboard`,
ADD COLUMN `equipments_qtd_tabletstudent` SMALLINT(4) NULL AFTER `equipments_qtd_notebookstudent`,
ADD COLUMN `equipments_scanner` TINYINT(1) NULL AFTER `equipments_multifunctional_printer`,
ADD COLUMN `internet_access_administrative` TINYINT(1) NULL AFTER `internet_access`,
ADD COLUMN `internet_access_educative_process` TINYINT(1) NULL AFTER `internet_access_administrative`,
ADD COLUMN `internet_access_student` TINYINT(1) NULL AFTER `internet_access_educative_process`,
ADD COLUMN `internet_access_community` TINYINT(1) NULL AFTER `internet_access_student`,
ADD COLUMN `internet_access_inexistent` TINYINT(1) NULL AFTER `internet_access_community`,
ADD COLUMN `internet_access_connected_personaldevice` TINYINT(1) NULL AFTER `internet_access_inexistent`,
ADD COLUMN `internet_access_connected_desktop` TINYINT(1) NULL AFTER `internet_access_connected_personaldevice`,
ADD COLUMN `internet_access_broadband` TINYINT(1) NULL AFTER `internet_access_connected_desktop`,
ADD COLUMN `internet_access_local_cable` TINYINT(1) NULL AFTER `internet_access_broadband`,
ADD COLUMN `internet_access_local_wireless` TINYINT(1) NULL AFTER `internet_access_local_cable`,
ADD COLUMN `internet_access_local_inexistet` TINYINT(1) NULL AFTER `internet_access_local_wireless`,
ADD COLUMN `workers_administrative_assistant` SMALLINT(6) NULL AFTER `bandwidth`,
ADD COLUMN `workers_service_assistant` SMALLINT(6) NULL AFTER `workers_administrative_assistant`,
ADD COLUMN `workers_librarian` SMALLINT(6) NULL AFTER `workers_service_assistant`,
ADD COLUMN `workers_firefighter` SMALLINT(6) NULL AFTER `workers_librarian`,
ADD COLUMN `workers_coordinator_shift` SMALLINT(6) NULL AFTER `workers_firefighter`,
ADD COLUMN `workers_speech_therapist` SMALLINT(6) NULL AFTER `workers_coordinator_shift`,
ADD COLUMN `workers_nutritionist` SMALLINT(6) NULL AFTER `workers_speech_therapist`,
ADD COLUMN `workers_psychologist` SMALLINT(6) NULL AFTER `workers_nutritionist`,
ADD COLUMN `workers_cooker` SMALLINT(6) NULL AFTER `workers_psychologist`,
ADD COLUMN `workers_support_professionals` SMALLINT(6) NULL AFTER `workers_cooker`,
ADD COLUMN `workers_school_secretary` SMALLINT(6) NULL AFTER `workers_support_professionals`,
ADD COLUMN `workers_security_guards` SMALLINT(6) NULL AFTER `workers_school_secretary`,
ADD COLUMN `workers_monitors` SMALLINT(6) NULL AFTER `workers_security_guards`,
ADD COLUMN `org_teaching_series_year` TINYINT(1) NULL AFTER `feeding`,
ADD COLUMN `org_teaching_semester_periods` TINYINT(1) NULL AFTER `org_teaching_series_year`,
ADD COLUMN `org_teaching_elementary_cycle` TINYINT(1) NULL AFTER `org_teaching_semester_periods`,
ADD COLUMN `org_teaching_non_serialgroups` TINYINT(1) NULL AFTER `org_teaching_elementary_cycle`,
ADD COLUMN `org_teaching_modules` TINYINT(1) NULL AFTER `org_teaching_non_serialgroups`,
ADD COLUMN `org_teaching_regular_alternation` TINYINT(1) NULL AFTER `org_teaching_modules`,
ADD COLUMN `equipments_multimedia_collection` TINYINT(1) NULL AFTER `org_teaching_regular_alternation`,
ADD COLUMN `equipments_toys_early` TINYINT(1) NULL AFTER `equipments_multimedia_collection`,
ADD COLUMN `equipments_scientific_materials` TINYINT(1) NULL AFTER `equipments_toys_early`,
ADD COLUMN `equipments_equipment_amplification` TINYINT(1) NULL AFTER `equipments_scientific_materials`,
ADD COLUMN `equipments_musical_instruments` TINYINT(1) NULL AFTER `equipments_equipment_amplification`,
ADD COLUMN `equipments_educational_games` TINYINT(1) NULL AFTER `equipments_musical_instruments`,
ADD COLUMN `equipments_material_cultural` TINYINT(1) NULL AFTER `equipments_educational_games`,
ADD COLUMN `equipments_material_sports` TINYINT(1) NULL AFTER `equipments_material_cultural`,
ADD COLUMN `equipments_material_teachingindian` TINYINT(1) NULL AFTER `equipments_material_sports`,
ADD COLUMN `equipments_material_teachingethnic` TINYINT(1) NULL AFTER `equipments_material_teachingindian`,
ADD COLUMN `equipments_material_teachingrural` TINYINT(1) NULL AFTER `equipments_material_teachingethnic`,
ADD COLUMN `edcenso_native_languages_fk2` INT(11) NULL AFTER `edcenso_native_languages_fk`,
ADD COLUMN `edcenso_native_languages_fk3` INT(11) NULL AFTER `edcenso_native_languages_fk2`,
ADD COLUMN `select_adimission` TINYINT(1) NULL AFTER `edcenso_native_languages_fk3`,
ADD COLUMN `booking_enrollment_self_declaredskin` TINYINT(1) NULL AFTER `select_adimission`,
ADD COLUMN `booking_enrollment_income` TINYINT(1) NULL AFTER `booking_enrollment_self_declaredskin`,
ADD COLUMN `booking_enrollment_public_school` TINYINT(1) NULL AFTER `booking_enrollment_income`,
ADD COLUMN `booking_enrollment_disabled_person` TINYINT(1) NULL AFTER `booking_enrollment_public_school`,
ADD COLUMN `booking_enrollment_others` TINYINT(1) NULL AFTER `booking_enrollment_disabled_person`,
ADD COLUMN `booking_enrollment_inexistent` TINYINT(1) NULL AFTER `booking_enrollment_others`,
ADD COLUMN `website` TINYINT(1) NULL AFTER `booking_enrollment_inexistent`,
ADD COLUMN `community_integration` TINYINT(1) NULL AFTER `website`,
ADD COLUMN `space_schoolenviroment` TINYINT(1) NULL AFTER `community_integration`,
ADD COLUMN `board_organ_association_parent` TINYINT(1) NULL AFTER `space_schoolenviroment`,
ADD COLUMN `board_organ_association_parentinstructors` TINYINT(1) NULL AFTER `board_organ_association_parent`,
ADD COLUMN `board_organ_board_school` TINYINT(1) NULL AFTER `board_organ_association_parentinstructors`,
ADD COLUMN `board_organ_student_guild` TINYINT(1) NULL AFTER `board_organ_board_school`,
ADD COLUMN `board_organ_others` TINYINT(1) NULL AFTER `board_organ_student_guild`,
ADD COLUMN `board_organ_inexistent` TINYINT(1) NULL AFTER `board_organ_others`,
ADD COLUMN `ppp_updated` TINYINT(1) NULL AFTER `board_organ_inexistent`,
ADD COLUMN `building_otherschool` TINYINT(1) NULL AFTER `pedagogical_formation_by_alternance`,
ADD COLUMN `supply_food` TINYINT(1) NULL AFTER `building_otherschool`;


ALTER TABLE `classroom` 
ADD COLUMN `schooling` TINYINT(1) NULL AFTER `week_days_saturday`,
ADD COLUMN `diff_location` SMALLINT(4) NULL AFTER `complementary_activity_type_6`,
ADD COLUMN `discipline_protuguese_secondary_language` SMALLINT(6) NULL AFTER `discipline_sociology`,
ADD COLUMN `discipline_curricular_stage` SMALLINT(6) NULL AFTER `discipline_protuguese_secondary_language`,
ADD COLUMN `course` INT(11) NULL;

ALTER TABLE `instructor_identification` 
ADD COLUMN `deficiency_type_autism` TINYINT(1) NULL AFTER `deficiency_type_multiple_disabilities`,
ADD COLUMN `deficiency_type_gifted` TINYINT(1) NULL AFTER `deficiency_type_autism`;

ALTER TABLE `instructor_documents_and_address` 
ADD COLUMN `diff_location` SMALLINT(4) NULL AFTER `area_of_residence`;

ALTER TABLE `student_documents_and_address` 
ADD COLUMN `diff_location` SMALLINT(4) NULL AFTER `residence_zone`;

ALTER TABLE `student_identification` 
ADD COLUMN `resource_zoomed_test_18` TINYINT(1) NULL AFTER `resource_zoomed_test_16`,
ADD COLUMN `resource_cd_audio` TINYINT(1) NULL AFTER `resource_zoomed_test_24`,
ADD COLUMN `resource_proof_language` TINYINT(1) NULL AFTER `resource_cd_audio`,
ADD COLUMN `resource_video_libras` TINYINT(1) NULL AFTER `resource_proof_language`,
ADD COLUMN `no_document_desc` SMALLINT(4) NULL,
ADD COLUMN `scholarity` SMALLINT(4) NULL AFTER `no_document_desc`,
ADD COLUMN `id_email` VARCHAR(255) NULL AFTER `scholarity`;

ALTER TABLE `student_enrollment` 
ADD COLUMN `aee_cognitive_functions` TINYINT(1) NULL,
ADD COLUMN `aee_autonomous_life` TINYINT(1) NULL,
ADD COLUMN `aee_curriculum_enrichment` TINYINT(1) NULL,
ADD COLUMN `aee_accessible_teaching` TINYINT(1) NULL,
ADD COLUMN `aee_libras` TINYINT(1) NULL,
ADD COLUMN `aee_portuguese` TINYINT(1) NULL,
ADD COLUMN `aee_soroban` TINYINT(1) NULL,
ADD COLUMN `aee_braille` TINYINT(1) NULL,
ADD COLUMN `aee_mobility_techniques` TINYINT(1) NULL,
ADD COLUMN `aee_caa` TINYINT(1) NULL,
ADD COLUMN `aee_optical_nonoptical` TINYINT(1) NULL;





