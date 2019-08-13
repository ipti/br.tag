ALTER TABLE `school_identification` 
ADD COLUMN `private_school_organization_civil_society` TINYINT(1) NULL AFTER `private_school_s_system`,
ADD COLUMN `regulation_organ_federal` TINYINT(1) NULL AFTER `regulation_organ`,
ADD COLUMN `regulation_organ_state` TINYINT(1) NULL AFTER `regulation_organ_federal`,
ADD COLUMN `regulation_organ_municipal` TINYINT(1) NULL AFTER `regulation_organ_state`;


ALTER TABLE `school_structure` 
ADD COLUMN `provide_potable_water` TINYINT(1) NULL AFTER `consumed_water_type`,
ADD COLUMN `dependencies_student_repose_room` TINYINT(1) NULL AFTER `dependencies_refectory`;

ALTER TABLE `classroom` 
ADD COLUMN `complementary_activity` TINYINT(1) NULL AFTER `schooling`,
ADD COLUMN `aee` TINYINT(1) NULL AFTER `complementary_activity`;

ALTER TABLE `instructor_teaching_data` 
ADD COLUMN `discipline_14_fk` INT(11) NULL AFTER `discipline_13_fk`,
ADD COLUMN `discipline_15_fk` INT(11) NULL AFTER `discipline_14_fk`;

ALTER TABLE `student_enrollment` 
ADD COLUMN `multi` INT(11) NULL AFTER `another_scholarization_place`,
CHANGE COLUMN `aee_cognitive_functions` `aee_cognitive_functions` TINYINT(1) NULL AFTER `multi`,
CHANGE COLUMN `aee_autonomous_life` `aee_autonomous_life` TINYINT(1) NULL AFTER `aee_cognitive_functions`,
CHANGE COLUMN `aee_curriculum_enrichment` `aee_curriculum_enrichment` TINYINT(1) NULL AFTER `aee_autonomous_life`,
CHANGE COLUMN `aee_accessible_teaching` `aee_accessible_teaching` TINYINT(1) NULL AFTER `aee_curriculum_enrichment`,
CHANGE COLUMN `aee_libras` `aee_libras` TINYINT(1) NULL AFTER `aee_accessible_teaching`,
CHANGE COLUMN `aee_portuguese` `aee_portuguese` TINYINT(1) NULL AFTER `aee_libras`,
CHANGE COLUMN `aee_soroban` `aee_soroban` TINYINT(1) NULL AFTER `aee_portuguese`,
CHANGE COLUMN `aee_braille` `aee_braille` TINYINT(1) NULL AFTER `aee_soroban`,
CHANGE COLUMN `aee_mobility_techniques` `aee_mobility_techniques` TINYINT(1) NULL AFTER `aee_braille`,
CHANGE COLUMN `aee_caa` `aee_caa` TINYINT(1) NULL AFTER `aee_mobility_techniques`,
CHANGE COLUMN `aee_optical_nonoptical` `aee_optical_nonoptical` TINYINT(1) NULL AFTER `aee_caa`;

ALTER TABLE `school_identification` 
ADD COLUMN `manager_access_criterion` VARCHAR(100) NULL AFTER `manager_email`,
ADD COLUMN `manager_contract_type` SMALLINT(6) NULL AFTER `manager_access_criterion`;

ALTER TABLE `classroom` CHANGE `aee_braille_system_education` `aee_braille` TINYINT(1) NULL,
 CHANGE `aee_optical_and_non_optical_resources` `aee_optical_nonoptical` TINYINT(1) NULL, CHANGE `aee_mental_processes_development_strategies` `aee_cognitive_functions` TINYINT(1) NULL, CHANGE `aee_mobility_and_orientation_techniques` `aee_mobility_techniques` TINYINT(1) NULL, CHANGE `aee_caa_use_education` `aee_caa` TINYINT(1) NULL, CHANGE `aee_curriculum_enrichment_strategy` `aee_curriculum_enrichment` TINYINT(1) NULL, CHANGE `aee_soroban_use_education` `aee_soroban` TINYINT(1) NULL, CHANGE `aee_usability_and_functionality_of_computer_accessible_education` `aee_accessible_teaching` TINYINT(1) NULL, CHANGE `aee_teaching_of_Portuguese_language_written_modality` `aee_portuguese` TINYINT(1) NULL, CHANGE `aee_strategy_for_school_environment_autonomy` `aee_autonomous_life` TINYINT(1) NULL; 









