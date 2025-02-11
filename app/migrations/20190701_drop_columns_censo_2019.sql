ALTER TABLE `classroom` 
DROP COLUMN `schooling`,
DROP COLUMN `diff_location`,
DROP COLUMN `course`;

ALTER TABLE `instructor_documents_and_address` 
DROP COLUMN `diff_location`;

ALTER TABLE `instructor_identification`
DROP COLUMN `deficiency_type_autism`,
DROP COLUMN `deficiency_type_gifted`;

ALTER TABLE `school_identification` 
DROP COLUMN `id_difflocation`,
DROP COLUMN `linked_mec`,
DROP COLUMN `linked_army`,
DROP COLUMN `linked_helth`,
DROP COLUMN `linked_other`,
DROP COLUMN `regulation_organ`;

ALTER TABLE `school_structure` 
DROP COLUMN `building_otherschool`,
DROP COLUMN `energy_supply_generator_alternative`,
DROP COLUMN `sewage_fossa_common`,
DROP COLUMN `garbage_destination_public`,
DROP COLUMN `supply_food`,
DROP COLUMN `treatment_garbage_parting_garbage`,
DROP COLUMN `treatment_garbage_resuse`,
DROP COLUMN `traetment_garbage_inexistent`,
DROP COLUMN `dependencies_bathroom_workes`,
DROP COLUMN `dependencies_pool`,
DROP COLUMN `dependencies_arts_room`,
DROP COLUMN `dependencies_music_room`,
DROP COLUMN `dependencies_dance_room`,
DROP COLUMN `dependencies_multiuse_room`,
DROP COLUMN `dependencies_yardzao`,
DROP COLUMN `dependencies_vivarium`,
DROP COLUMN `dependencies_outside_roomspublic`,
DROP COLUMN `dependencies_indoor_roomspublic`,
DROP COLUMN `dependencies_climate_roomspublic`,
DROP COLUMN `dependencies_acessibility_roomspublic`,
DROP COLUMN `acessability_handrails_guardrails`,
DROP COLUMN `acessability_elevator`,
DROP COLUMN `acessability_tactile_floor`,
DROP COLUMN `acessability_doors_80cm`,
DROP COLUMN `acessability_ramps`,
DROP COLUMN `acessability_sound_signaling`,
DROP COLUMN `acessability_tactile_singnaling`,
DROP COLUMN `acessability_visual_signaling`,
DROP COLUMN `acessabilty_inexistent`,
DROP COLUMN `equipments_scanner`,
DROP COLUMN `equipments_qtd_blackboard`,
DROP COLUMN `equipments_qtd_notebookstudent`,
DROP COLUMN `equipments_qtd_tabletstudent`,
DROP COLUMN `equipments_multimedia_collection`,
DROP COLUMN `equipments_toys_early`,
DROP COLUMN `equipments_scientific_materials`,
DROP COLUMN `equipments_equipment_amplification`,
DROP COLUMN `equipments_musical_instruments`,
DROP COLUMN `equipments_educational_games`,
DROP COLUMN `equipments_material_cultural`,
DROP COLUMN `equipments_material_sports`,
DROP COLUMN `equipments_material_teachingindian`,
DROP COLUMN `equipments_material_teachingethnic`,
DROP COLUMN `equipments_material_teachingrural`,
DROP COLUMN `internet_access_administrative`,
DROP COLUMN `internet_access_educative_process`,
DROP COLUMN `internet_access_student`,
DROP COLUMN `internet_access_community`,
DROP COLUMN `internet_access_inexistent`,
DROP COLUMN `internet_access_connected_personaldevice`,
DROP COLUMN `internet_access_connected_desktop`,
DROP COLUMN `internet_access_broadband`,
DROP COLUMN `internet_access_local_cable`,
DROP COLUMN `internet_access_local_wireless`,
DROP COLUMN `internet_access_local_inexistet`,
DROP COLUMN `workers_administrative_assistant`,
DROP COLUMN `workers_service_assistant`,
DROP COLUMN `workers_librarian`,
DROP COLUMN `workers_firefighter`,
DROP COLUMN `workers_coordinator_shift`,
DROP COLUMN `workers_speech_therapist`,
DROP COLUMN `workers_nutritionist`,
DROP COLUMN `workers_psychologist`,
DROP COLUMN `workers_cooker`,
DROP COLUMN `workers_support_professionals`,
DROP COLUMN `workers_school_secretary`,
DROP COLUMN `workers_security_guards`,
DROP COLUMN `workers_monitors`,
DROP COLUMN `org_teaching_series_year`,
DROP COLUMN `org_teaching_semester_periods`,
DROP COLUMN `org_teaching_elementary_cycle`,
DROP COLUMN `org_teaching_non_serialgroups`,
DROP COLUMN `org_teaching_modules`,
DROP COLUMN `org_teaching_regular_alternation`,
DROP COLUMN `edcenso_native_languages_fk2`,
DROP COLUMN `edcenso_native_languages_fk3`,
DROP COLUMN `select_adimission`,
DROP COLUMN `booking_enrollment_self_declaredskin`,
DROP COLUMN `booking_enrollment_income`,
DROP COLUMN `booking_enrollment_public_school`,
DROP COLUMN `booking_enrollment_disabled_person`,
DROP COLUMN `booking_enrollment_others`,
DROP COLUMN `booking_enrollment_inexistent`,
DROP COLUMN `website`,
DROP COLUMN `community_integration`,
DROP COLUMN `space_schoolenviroment`,
DROP COLUMN `ppp_updated`,
DROP COLUMN `board_organ_association_parent`,
DROP COLUMN `board_organ_association_parentinstructors`,
DROP COLUMN `board_organ_board_school`,
DROP COLUMN `board_organ_student_guild`,
DROP COLUMN `board_organ_others`,
DROP COLUMN `board_organ_inexistent`;

ALTER TABLE `student_documents_and_address` 
DROP COLUMN `diff_location`;

ALTER TABLE `student_enrollment` 
DROP COLUMN `aee_cognitive_functions`,
DROP COLUMN `aee_autonomous_life`,
DROP COLUMN `aee_curriculum_enrichment`,
DROP COLUMN `aee_accessible_teaching`,
DROP COLUMN `aee_libras`,
DROP COLUMN `aee_portuguese`,
DROP COLUMN `aee_soroban`,
DROP COLUMN `aee_braille`,
DROP COLUMN `aee_mobility_techniques`,
DROP COLUMN `aee_caa`,
DROP COLUMN `aee_optical_nonoptical`;

ALTER TABLE `student_identification` 
DROP COLUMN `id_email`,
DROP COLUMN `resource_zoomed_test_18`,
DROP COLUMN `resource_cd_audio`,
DROP COLUMN `resource_proof_language`,
DROP COLUMN `resource_video_libras`,
DROP COLUMN `scholarity`,
DROP COLUMN `no_documents_desc`;