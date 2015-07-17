CREATE DATABASE  IF NOT EXISTS `br.org.ipti.tag` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `br.org.ipti.tag`;
-- MySQL dump 10.13  Distrib 5.6.25, for Linux (x86_64)
--
-- Host: 192.168.25.209    Database: br.org.ipti.tag
-- ------------------------------------------------------
-- Server version	5.5.40-1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Temporary view structure for view `ata_performance`
--

DROP TABLE IF EXISTS `ata_performance`;
/*!50001 DROP VIEW IF EXISTS `ata_performance`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `ata_performance` AS SELECT 
 1 AS `school`,
 1 AS `city`,
 1 AS `day`,
 1 AS `month`,
 1 AS `year`,
 1 AS `ensino`,
 1 AS `name`,
 1 AS `turn`,
 1 AS `serie`,
 1 AS `school_year`,
 1 AS `classroom_id`,
 1 AS `disciplines`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_assignment` (
  `itemname` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL,
  `bizrule` mediumtext COLLATE utf8_unicode_ci,
  `data` mediumtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`itemname`,`userid`),
  KEY `fk_AuthAssignment_1` (`userid`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_AuthAssignment_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci,
  `bizrule` mediumtext COLLATE utf8_unicode_ci,
  `data` mediumtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `class`
--

DROP TABLE IF EXISTS `class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discipline_fk` int(11) DEFAULT NULL,
  `classroom_fk` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `classtype` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `given_class` tinyint(4) NOT NULL DEFAULT '0',
  `schedule` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `class_discipline_fkey` (`discipline_fk`),
  KEY `class_classroom_fkey` (`classroom_fk`),
  CONSTRAINT `class_classroom_fkey` FOREIGN KEY (`classroom_fk`) REFERENCES `classroom` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `class_discipline_fkey` FOREIGN KEY (`discipline_fk`) REFERENCES `edcenso_discipline` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `class_board`
--

DROP TABLE IF EXISTS `class_board`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_board` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discipline_fk` int(11) NOT NULL,
  `classroom_fk` int(11) NOT NULL,
  `instructor_fk` int(11) DEFAULT NULL,
  `week_day_monday` varchar(50) COLLATE utf8_unicode_ci DEFAULT '0',
  `week_day_tuesday` varchar(50) COLLATE utf8_unicode_ci DEFAULT '0',
  `week_day_wednesday` varchar(50) COLLATE utf8_unicode_ci DEFAULT '0',
  `week_day_thursday` varchar(50) COLLATE utf8_unicode_ci DEFAULT '0',
  `week_day_friday` varchar(50) COLLATE utf8_unicode_ci DEFAULT '0',
  `week_day_saturday` varchar(50) COLLATE utf8_unicode_ci DEFAULT '0',
  `week_day_sunday` varchar(50) COLLATE utf8_unicode_ci DEFAULT '0',
  `estimated_classes` int(11) DEFAULT '0',
  `given_classes` int(11) DEFAULT '0',
  `replaced_classes` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `discipline_fkey` (`discipline_fk`),
  KEY `classroom_fkey` (`classroom_fk`),
  KEY `instructor_fkey` (`instructor_fk`),
  CONSTRAINT `classroom_fkey` FOREIGN KEY (`classroom_fk`) REFERENCES `classroom` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `discipline_fkey` FOREIGN KEY (`discipline_fk`) REFERENCES `edcenso_discipline` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `instructor_fkey` FOREIGN KEY (`instructor_fk`) REFERENCES `instructor_identification` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `class_class_objective`
--

DROP TABLE IF EXISTS `class_class_objective`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_class_objective` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_fk` int(11) NOT NULL,
  `objective_fk` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `class` (`class_fk`),
  KEY `objective` (`objective_fk`),
  CONSTRAINT `fk_class_class_objective_1` FOREIGN KEY (`class_fk`) REFERENCES `class` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_class_class_objective_2` FOREIGN KEY (`objective_fk`) REFERENCES `class_objective` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `class_faults`
--

DROP TABLE IF EXISTS `class_faults`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_faults` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_fk` int(11) NOT NULL,
  `student_fk` int(11) NOT NULL,
  `schedule` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_class_faults_1` (`class_fk`),
  KEY `fk_class_faults_2` (`student_fk`),
  CONSTRAINT `fk_class_faults_1` FOREIGN KEY (`class_fk`) REFERENCES `class` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_class_faults_2` FOREIGN KEY (`student_fk`) REFERENCES `student_enrollment` (`student_fk`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `class_objective`
--

DROP TABLE IF EXISTS `class_objective`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_objective` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `classroom`
--

DROP TABLE IF EXISTS `classroom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classroom` (
  `register_type` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '20',
  `school_inep_fk` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `inep_id` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `initial_hour` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `initial_minute` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `final_hour` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `final_minute` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `week_days_sunday` tinyint(1) NOT NULL,
  `week_days_monday` tinyint(1) NOT NULL,
  `week_days_tuesday` tinyint(1) NOT NULL,
  `week_days_wednesday` tinyint(1) NOT NULL,
  `week_days_thursday` tinyint(1) NOT NULL,
  `week_days_friday` tinyint(1) NOT NULL,
  `week_days_saturday` tinyint(1) NOT NULL,
  `assistance_type` smallint(6) NOT NULL,
  `mais_educacao_participator` tinyint(1) DEFAULT NULL,
  `complementary_activity_type_1` int(11) DEFAULT NULL,
  `complementary_activity_type_2` int(11) DEFAULT NULL,
  `complementary_activity_type_3` int(11) DEFAULT NULL,
  `complementary_activity_type_4` int(11) DEFAULT NULL,
  `complementary_activity_type_5` int(11) DEFAULT NULL,
  `complementary_activity_type_6` int(11) DEFAULT NULL,
  `aee_braille_system_education` tinyint(1) DEFAULT NULL,
  `aee_optical_and_non_optical_resources` tinyint(1) DEFAULT NULL,
  `aee_mental_processes_development_strategies` tinyint(1) DEFAULT NULL,
  `aee_mobility_and_orientation_techniques` tinyint(1) DEFAULT NULL,
  `aee_libras` tinyint(1) DEFAULT NULL,
  `aee_caa_use_education` tinyint(1) DEFAULT NULL,
  `aee_curriculum_enrichment_strategy` tinyint(1) DEFAULT NULL,
  `aee_soroban_use_education` tinyint(1) DEFAULT NULL,
  `aee_usability_and_functionality_of_computer_accessible_education` tinyint(1) DEFAULT NULL,
  `aee_teaching_of_Portuguese_language_written_modality` tinyint(1) DEFAULT NULL,
  `aee_strategy_for_school_environment_autonomy` tinyint(1) DEFAULT NULL,
  `modality` smallint(6) DEFAULT NULL,
  `edcenso_stage_vs_modality_fk` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `edcenso_professional_education_course_fk` int(11) DEFAULT NULL,
  `discipline_chemistry` smallint(6) DEFAULT NULL,
  `discipline_physics` smallint(6) DEFAULT NULL,
  `discipline_mathematics` smallint(6) DEFAULT NULL,
  `discipline_biology` smallint(6) DEFAULT NULL,
  `discipline_science` smallint(6) DEFAULT NULL,
  `discipline_language_portuguese_literature` smallint(6) DEFAULT NULL,
  `discipline_foreign_language_english` smallint(6) DEFAULT NULL,
  `discipline_foreign_language_spanish` smallint(6) DEFAULT NULL,
  `discipline_foreign_language_franch` smallint(6) DEFAULT NULL,
  `discipline_foreign_language_other` smallint(6) DEFAULT NULL,
  `discipline_arts` smallint(6) DEFAULT NULL,
  `discipline_physical_education` smallint(6) DEFAULT NULL,
  `discipline_history` smallint(6) DEFAULT NULL,
  `discipline_geography` smallint(6) DEFAULT NULL,
  `discipline_philosophy` smallint(6) DEFAULT NULL,
  `discipline_social_study` smallint(6) DEFAULT NULL,
  `discipline_sociology` smallint(6) DEFAULT NULL,
  `discipline_informatics` smallint(6) DEFAULT NULL,
  `discipline_professional_disciplines` smallint(6) DEFAULT NULL,
  `discipline_special_education_and_inclusive_practices` smallint(6) DEFAULT NULL,
  `discipline_sociocultural_diversity` smallint(6) DEFAULT NULL,
  `discipline_libras` smallint(6) DEFAULT NULL,
  `discipline_pedagogical` smallint(6) DEFAULT NULL,
  `discipline_religious` smallint(6) DEFAULT NULL,
  `discipline_native_language` smallint(6) DEFAULT NULL,
  `discipline_others` smallint(6) DEFAULT NULL,
  `instructor_situation` tinyint(1) DEFAULT NULL,
  `school_year` int(11) NOT NULL,
  `turn` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`school_inep_fk`),
  KEY `school_inep_fk` (`school_inep_fk`),
  KEY `edcenso_professional_education_course_fk` (`edcenso_professional_education_course_fk`),
  CONSTRAINT `classroom_ibfk_1` FOREIGN KEY (`school_inep_fk`) REFERENCES `school_identification` (`inep_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `classroom_ibfk_4` FOREIGN KEY (`edcenso_professional_education_course_fk`) REFERENCES `edcenso_professional_education_course` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary view structure for view `classroom_enrollment`
--

DROP TABLE IF EXISTS `classroom_enrollment`;
/*!50001 DROP VIEW IF EXISTS `classroom_enrollment`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `classroom_enrollment` AS SELECT 
 1 AS `enrollment`,
 1 AS `name`,
 1 AS `sex`,
 1 AS `birthday`,
 1 AS `nation`,
 1 AS `city`,
 1 AS `address`,
 1 AS `cc`,
 1 AS `cc_new`,
 1 AS `cc_number`,
 1 AS `cc_book`,
 1 AS `cc_sheet`,
 1 AS `parents`,
 1 AS `deficiency`,
 1 AS `classroom_id`,
 1 AS `year`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `classroom_instructors`
--

DROP TABLE IF EXISTS `classroom_instructors`;
/*!50001 DROP VIEW IF EXISTS `classroom_instructors`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `classroom_instructors` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `school_inep_fk`,
 1 AS `time`,
 1 AS `assistance_type`,
 1 AS `modality`,
 1 AS `stage`,
 1 AS `week_days`,
 1 AS `instructor_id`,
 1 AS `birthday_date`,
 1 AS `instructor_name`,
 1 AS `scholarity`,
 1 AS `disciplines`,
 1 AS `school_year`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `classroom_qtd_students`
--

DROP TABLE IF EXISTS `classroom_qtd_students`;
/*!50001 DROP VIEW IF EXISTS `classroom_qtd_students`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `classroom_qtd_students` AS SELECT 
 1 AS `school_inep_fk`,
 1 AS `id`,
 1 AS `name`,
 1 AS `time`,
 1 AS `assistance_type`,
 1 AS `modality`,
 1 AS `stage`,
 1 AS `students`,
 1 AS `school_year`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `edcenso_aee_activity`
--

DROP TABLE IF EXISTS `edcenso_aee_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edcenso_aee_activity` (
  `id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edcenso_city`
--

DROP TABLE IF EXISTS `edcenso_city`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edcenso_city` (
  `id` int(11) NOT NULL,
  `edcenso_uf_fk` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `cep_initial` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cep_final` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ddd1` smallint(6) DEFAULT NULL,
  `ddd2` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `edcenso_uf_fk` (`edcenso_uf_fk`),
  CONSTRAINT `edcenso_city_ibfk_1` FOREIGN KEY (`edcenso_uf_fk`) REFERENCES `edcenso_uf` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edcenso_complementary_activity_type`
--

DROP TABLE IF EXISTS `edcenso_complementary_activity_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edcenso_complementary_activity_type` (
  `id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edcenso_course_of_higher_education`
--

DROP TABLE IF EXISTS `edcenso_course_of_higher_education`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edcenso_course_of_higher_education` (
  `cod` int(11) NOT NULL,
  `area` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `id` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `degree` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edcenso_discipline`
--

DROP TABLE IF EXISTS `edcenso_discipline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edcenso_discipline` (
  `id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edcenso_district`
--

DROP TABLE IF EXISTS `edcenso_district`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edcenso_district` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `edcenso_city_fk` int(11) NOT NULL,
  `code` smallint(6) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `edcenso_city_fk` (`edcenso_city_fk`),
  CONSTRAINT `edcenso_district_ibfk_1` FOREIGN KEY (`edcenso_city_fk`) REFERENCES `edcenso_city` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10330 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edcenso_ies`
--

DROP TABLE IF EXISTS `edcenso_ies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edcenso_ies` (
  `id` int(11) NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `edcenso_uf_fk` int(11) DEFAULT NULL,
  `edcenso_city_fk` int(11) DEFAULT NULL,
  `administrative_dependency_code` smallint(6) DEFAULT NULL,
  `administrative_dependency_name` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `institution_type` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `working_status` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `edcenso_uf_fk` (`edcenso_uf_fk`),
  KEY `edcenso_city_fk` (`edcenso_city_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edcenso_nation`
--

DROP TABLE IF EXISTS `edcenso_nation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edcenso_nation` (
  `id` int(11) NOT NULL,
  `acronym` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edcenso_native_languages`
--

DROP TABLE IF EXISTS `edcenso_native_languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edcenso_native_languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edcenso_notary_office`
--

DROP TABLE IF EXISTS `edcenso_notary_office`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edcenso_notary_office` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `uf` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `serventia` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14214 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edcenso_organ_id_emitter`
--

DROP TABLE IF EXISTS `edcenso_organ_id_emitter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edcenso_organ_id_emitter` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edcenso_professional_education_course`
--

DROP TABLE IF EXISTS `edcenso_professional_education_course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edcenso_professional_education_course` (
  `id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edcenso_regional_education_organ`
--

DROP TABLE IF EXISTS `edcenso_regional_education_organ`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edcenso_regional_education_organ` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `edcenso_city_fk` int(11) NOT NULL,
  `code` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`,`edcenso_city_fk`),
  KEY `edcenso_city_fk` (`edcenso_city_fk`),
  CONSTRAINT `edcenso_regional_education_organ_ibfk_1` FOREIGN KEY (`edcenso_city_fk`) REFERENCES `edcenso_city` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=710 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edcenso_school_stages`
--

DROP TABLE IF EXISTS `edcenso_school_stages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edcenso_school_stages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edcenso_stage_vs_modality`
--

DROP TABLE IF EXISTS `edcenso_stage_vs_modality`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edcenso_stage_vs_modality` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `stage` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edcenso_uf`
--

DROP TABLE IF EXISTS `edcenso_uf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edcenso_uf` (
  `id` int(11) NOT NULL,
  `acronym` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grade`
--

DROP TABLE IF EXISTS `grade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grade1` float unsigned DEFAULT NULL,
  `grade2` float unsigned DEFAULT NULL,
  `grade3` float unsigned DEFAULT NULL,
  `grade4` float unsigned DEFAULT NULL,
  `recovery_grade1` float unsigned DEFAULT NULL,
  `recovery_grade2` float unsigned DEFAULT NULL,
  `recovery_grade3` float unsigned DEFAULT NULL,
  `recovery_grade4` float unsigned DEFAULT NULL,
  `recovery_final_grade` float unsigned DEFAULT NULL,
  `discipline_fk` int(11) NOT NULL,
  `enrollment_fk` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `discipline` (`discipline_fk`),
  KEY `fk_grade_2_idx` (`enrollment_fk`),
  CONSTRAINT `fk_grade_1` FOREIGN KEY (`discipline_fk`) REFERENCES `edcenso_discipline` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_grade_2` FOREIGN KEY (`enrollment_fk`) REFERENCES `student_enrollment` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary view structure for view `imob_classroom`
--

DROP TABLE IF EXISTS `imob_classroom`;
/*!50001 DROP VIEW IF EXISTS `imob_classroom`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `imob_classroom` AS SELECT 
 1 AS `c_total`,
 1 AS `year`,
 1 AS `school_inep_fk`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `imob_student_enrollment`
--

DROP TABLE IF EXISTS `imob_student_enrollment`;
/*!50001 DROP VIEW IF EXISTS `imob_student_enrollment`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `imob_student_enrollment` AS SELECT 
 1 AS `se_total`,
 1 AS `se_half1`,
 1 AS `se_half2`,
 1 AS `year`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `instructor_documents_and_address`
--

DROP TABLE IF EXISTS `instructor_documents_and_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `instructor_documents_and_address` (
  `register_type` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '40',
  `school_inep_id_fk` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `inep_id` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cpf` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `area_of_residence` smallint(6) DEFAULT NULL,
  `cep` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_number` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `complement` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `neighborhood` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `edcenso_uf_fk` int(11) DEFAULT NULL,
  `edcenso_city_fk` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `school_inep_id_fk` (`school_inep_id_fk`),
  KEY `edcenso_uf_fk` (`edcenso_uf_fk`),
  KEY `edcenso_city_fk` (`edcenso_city_fk`),
  CONSTRAINT `instructor_documents_and_address_ibfk_1` FOREIGN KEY (`school_inep_id_fk`) REFERENCES `school_identification` (`inep_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `instructor_documents_and_address_ibfk_2` FOREIGN KEY (`edcenso_uf_fk`) REFERENCES `edcenso_uf` (`id`),
  CONSTRAINT `instructor_documents_and_address_ibfk_3` FOREIGN KEY (`edcenso_city_fk`) REFERENCES `edcenso_city` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=206 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `instructor_identification`
--

DROP TABLE IF EXISTS `instructor_identification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `instructor_identification` (
  `register_type` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '30',
  `school_inep_id_fk` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `inep_id` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nis` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthday_date` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `sex` smallint(6) NOT NULL,
  `color_race` smallint(6) NOT NULL,
  `mother_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nationality` smallint(6) NOT NULL,
  `edcenso_nation_fk` int(11) NOT NULL,
  `edcenso_uf_fk` int(11) DEFAULT NULL,
  `edcenso_city_fk` int(11) DEFAULT NULL,
  `deficiency` tinyint(1) NOT NULL,
  `deficiency_type_blindness` tinyint(1) DEFAULT NULL,
  `deficiency_type_low_vision` tinyint(1) DEFAULT NULL,
  `deficiency_type_deafness` tinyint(1) DEFAULT NULL,
  `deficiency_type_disability_hearing` tinyint(1) DEFAULT NULL,
  `deficiency_type_deafblindness` tinyint(1) DEFAULT NULL,
  `deficiency_type_phisical_disability` tinyint(1) DEFAULT NULL,
  `deficiency_type_intelectual_disability` tinyint(1) DEFAULT NULL,
  `deficiency_type_multiple_disabilities` tinyint(1) DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `edcenso_nation_fk` (`edcenso_nation_fk`),
  KEY `edcenso_uf_fk` (`edcenso_uf_fk`),
  KEY `edcenso_city_fk` (`edcenso_city_fk`),
  CONSTRAINT `instructor_identification_ibfk_1` FOREIGN KEY (`edcenso_nation_fk`) REFERENCES `edcenso_nation` (`id`),
  CONSTRAINT `instructor_identification_ibfk_2` FOREIGN KEY (`edcenso_uf_fk`) REFERENCES `edcenso_uf` (`id`),
  CONSTRAINT `instructor_identification_ibfk_3` FOREIGN KEY (`edcenso_city_fk`) REFERENCES `edcenso_city` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=206 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `instructor_teaching_data`
--

DROP TABLE IF EXISTS `instructor_teaching_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `instructor_teaching_data` (
  `register_type` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '51',
  `school_inep_id_fk` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `instructor_inep_id` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `instructor_fk` int(11) NOT NULL,
  `classroom_inep_id` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `classroom_id_fk` int(11) NOT NULL,
  `role` smallint(6) NOT NULL,
  `contract_type` smallint(6) DEFAULT NULL,
  `discipline_1_fk` int(11) DEFAULT NULL,
  `discipline_2_fk` int(11) DEFAULT NULL,
  `discipline_3_fk` int(11) DEFAULT NULL,
  `discipline_4_fk` int(11) DEFAULT NULL,
  `discipline_5_fk` int(11) DEFAULT NULL,
  `discipline_6_fk` int(11) DEFAULT NULL,
  `discipline_7_fk` int(11) DEFAULT NULL,
  `discipline_8_fk` int(11) DEFAULT NULL,
  `discipline_9_fk` int(11) DEFAULT NULL,
  `discipline_10_fk` int(11) DEFAULT NULL,
  `discipline_11_fk` int(11) DEFAULT NULL,
  `discipline_12_fk` int(11) DEFAULT NULL,
  `discipline_13_fk` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `school_inep_id_fk` (`school_inep_id_fk`),
  KEY `discipline_1_fk` (`discipline_1_fk`),
  KEY `discipline_2_fk` (`discipline_2_fk`),
  KEY `discipline_3_fk` (`discipline_3_fk`),
  KEY `discipline_4_fk` (`discipline_4_fk`),
  KEY `discipline_5_fk` (`discipline_5_fk`),
  KEY `discipline_6_fk` (`discipline_6_fk`),
  KEY `discipline_7_fk` (`discipline_7_fk`),
  KEY `discipline_8_fk` (`discipline_8_fk`),
  KEY `discipline_9_fk` (`discipline_9_fk`),
  KEY `discipline_10_fk` (`discipline_10_fk`),
  KEY `discipline_11_fk` (`discipline_11_fk`),
  KEY `discipline_12_fk` (`discipline_12_fk`),
  KEY `discipline_13_fk` (`discipline_13_fk`),
  KEY `fk_instructor_teaching_data_1` (`instructor_fk`),
  KEY `fk_instructor_teaching_data_2` (`classroom_id_fk`),
  KEY `fk_instructor_teaching_data_3` (`discipline_1_fk`),
  CONSTRAINT `fk_instructor_teaching_data_1` FOREIGN KEY (`instructor_fk`) REFERENCES `instructor_identification` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_instructor_teaching_data_2` FOREIGN KEY (`classroom_id_fk`) REFERENCES `classroom` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_instructor_teaching_data_3` FOREIGN KEY (`discipline_1_fk`) REFERENCES `edcenso_discipline` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `instructor_teaching_data_ibfk_1` FOREIGN KEY (`school_inep_id_fk`) REFERENCES `school_identification` (`inep_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `instructor_teaching_data_ibfk_10` FOREIGN KEY (`discipline_10_fk`) REFERENCES `edcenso_discipline` (`id`),
  CONSTRAINT `instructor_teaching_data_ibfk_11` FOREIGN KEY (`discipline_11_fk`) REFERENCES `edcenso_discipline` (`id`),
  CONSTRAINT `instructor_teaching_data_ibfk_12` FOREIGN KEY (`discipline_12_fk`) REFERENCES `edcenso_discipline` (`id`),
  CONSTRAINT `instructor_teaching_data_ibfk_13` FOREIGN KEY (`discipline_13_fk`) REFERENCES `edcenso_discipline` (`id`),
  CONSTRAINT `instructor_teaching_data_ibfk_2` FOREIGN KEY (`discipline_2_fk`) REFERENCES `edcenso_discipline` (`id`),
  CONSTRAINT `instructor_teaching_data_ibfk_3` FOREIGN KEY (`discipline_3_fk`) REFERENCES `edcenso_discipline` (`id`),
  CONSTRAINT `instructor_teaching_data_ibfk_4` FOREIGN KEY (`discipline_4_fk`) REFERENCES `edcenso_discipline` (`id`),
  CONSTRAINT `instructor_teaching_data_ibfk_5` FOREIGN KEY (`discipline_5_fk`) REFERENCES `edcenso_discipline` (`id`),
  CONSTRAINT `instructor_teaching_data_ibfk_6` FOREIGN KEY (`discipline_6_fk`) REFERENCES `edcenso_discipline` (`id`),
  CONSTRAINT `instructor_teaching_data_ibfk_7` FOREIGN KEY (`discipline_7_fk`) REFERENCES `edcenso_discipline` (`id`),
  CONSTRAINT `instructor_teaching_data_ibfk_8` FOREIGN KEY (`discipline_8_fk`) REFERENCES `edcenso_discipline` (`id`),
  CONSTRAINT `instructor_teaching_data_ibfk_9` FOREIGN KEY (`discipline_9_fk`) REFERENCES `edcenso_discipline` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=639 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `instructor_variable_data`
--

DROP TABLE IF EXISTS `instructor_variable_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `instructor_variable_data` (
  `register_type` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '50',
  `school_inep_id_fk` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `inep_id` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scholarity` smallint(6) NOT NULL,
  `high_education_situation_1` smallint(6) DEFAULT NULL,
  `high_education_formation_1` tinyint(1) DEFAULT NULL,
  `high_education_course_code_1_fk` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `high_education_initial_year_1` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `high_education_final_year_1` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `high_education_institution_type_1` smallint(6) DEFAULT NULL,
  `high_education_institution_code_1_fk` int(11) DEFAULT NULL,
  `high_education_situation_2` smallint(6) DEFAULT NULL,
  `high_education_formation_2` tinyint(1) DEFAULT NULL,
  `high_education_course_code_2_fk` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `high_education_initial_year_2` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `high_education_final_year_2` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `high_education_institution_type_2` smallint(6) DEFAULT NULL,
  `high_education_institution_code_2_fk` int(11) DEFAULT NULL,
  `high_education_situation_3` smallint(6) DEFAULT NULL,
  `high_education_formation_3` tinyint(1) DEFAULT NULL,
  `high_education_course_code_3_fk` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `high_education_initial_year_3` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `high_education_final_year_3` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `high_education_institution_type_3` smallint(6) DEFAULT NULL,
  `high_education_institution_code_3_fk` int(11) DEFAULT NULL,
  `post_graduation_specialization` tinyint(1) DEFAULT NULL,
  `post_graduation_master` tinyint(1) DEFAULT NULL,
  `post_graduation_doctorate` tinyint(1) DEFAULT NULL,
  `post_graduation_none` tinyint(1) DEFAULT NULL,
  `other_courses_nursery` tinyint(1) NOT NULL,
  `other_courses_pre_school` tinyint(1) NOT NULL,
  `other_courses_basic_education_initial_years` tinyint(1) NOT NULL,
  `other_courses_basic_education_final_years` tinyint(1) NOT NULL,
  `other_courses_high_school` tinyint(1) NOT NULL,
  `other_courses_education_of_youth_and_adults` tinyint(1) NOT NULL,
  `other_courses_special_education` tinyint(1) NOT NULL,
  `other_courses_native_education` tinyint(1) NOT NULL,
  `other_courses_field_education` tinyint(1) NOT NULL,
  `other_courses_environment_education` tinyint(1) NOT NULL,
  `other_courses_human_rights_education` tinyint(1) NOT NULL,
  `other_courses_sexual_education` tinyint(1) NOT NULL,
  `other_courses_child_and_teenage_rights` tinyint(1) NOT NULL,
  `other_courses_ethnic_education` tinyint(1) NOT NULL,
  `other_courses_other` tinyint(1) NOT NULL,
  `other_courses_none` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `high_education_course_code_1_fk` (`high_education_course_code_1_fk`),
  KEY `high_education_institution_code_1_fk` (`high_education_institution_code_1_fk`),
  KEY `high_education_course_code_2_fk` (`high_education_course_code_2_fk`),
  KEY `high_education_institution_code_2_fk` (`high_education_institution_code_2_fk`),
  KEY `high_education_course_code_3_fk` (`high_education_course_code_3_fk`),
  KEY `high_education_institution_code_3_fk` (`high_education_institution_code_3_fk`),
  CONSTRAINT `instructor_higher_education_ibfk_1` FOREIGN KEY (`high_education_course_code_1_fk`) REFERENCES `edcenso_course_of_higher_education` (`id`),
  CONSTRAINT `instructor_higher_education_ibfk_2` FOREIGN KEY (`high_education_institution_code_1_fk`) REFERENCES `edcenso_ies` (`id`),
  CONSTRAINT `instructor_higher_education_ibfk_3` FOREIGN KEY (`high_education_course_code_2_fk`) REFERENCES `edcenso_course_of_higher_education` (`id`),
  CONSTRAINT `instructor_higher_education_ibfk_4` FOREIGN KEY (`high_education_institution_code_2_fk`) REFERENCES `edcenso_ies` (`id`),
  CONSTRAINT `instructor_higher_education_ibfk_5` FOREIGN KEY (`high_education_course_code_3_fk`) REFERENCES `edcenso_course_of_higher_education` (`id`),
  CONSTRAINT `instructor_higher_education_ibfk_6` FOREIGN KEY (`high_education_institution_code_3_fk`) REFERENCES `edcenso_ies` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=206 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `school_configuration`
--

DROP TABLE IF EXISTS `school_configuration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `school_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_inep_id_fk` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `morning_initial` time DEFAULT NULL,
  `morning_final` time DEFAULT NULL,
  `afternoom_initial` time DEFAULT NULL,
  `afternoom_final` time DEFAULT NULL,
  `night_initial` time DEFAULT NULL,
  `night_final` time DEFAULT NULL,
  `allday_initial` time DEFAULT NULL,
  `allday_final` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_school_configuration_1_idx` (`school_inep_id_fk`),
  CONSTRAINT `fk_school_configuration_1` FOREIGN KEY (`school_inep_id_fk`) REFERENCES `school_identification` (`inep_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `school_identification`
--

DROP TABLE IF EXISTS `school_identification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `school_identification` (
  `register_type` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '00',
  `inep_id` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `situation` smallint(6) NOT NULL DEFAULT '1',
  `initial_date` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `final_date` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitude` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cep` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `address_number` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_complement` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_neighborhood` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `edcenso_uf_fk` int(11) NOT NULL,
  `edcenso_city_fk` int(11) NOT NULL,
  `edcenso_district_fk` int(11) NOT NULL,
  `ddd` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_number` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `public_phone_number` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `other_phone_number` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax_number` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `edcenso_regional_education_organ_fk` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `administrative_dependence` smallint(6) NOT NULL,
  `location` smallint(6) NOT NULL,
  `private_school_category` smallint(6) DEFAULT NULL,
  `public_contract` smallint(6) DEFAULT NULL,
  `private_school_business_or_individual` tinyint(1) DEFAULT NULL,
  `private_school_syndicate_or_association` tinyint(1) DEFAULT NULL,
  `private_school_ong_or_oscip` tinyint(1) DEFAULT NULL,
  `private_school_non_profit_institutions` tinyint(1) DEFAULT NULL,
  `private_school_s_system` tinyint(1) DEFAULT NULL,
  `private_school_maintainer_cnpj` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL,
  `private_school_cnpj` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL,
  `regulation` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`inep_id`),
  UNIQUE KEY `inep_id` (`inep_id`),
  KEY `edcenso_uf_fk` (`edcenso_uf_fk`),
  KEY `edcenso_city_fk` (`edcenso_city_fk`),
  KEY `edcenso_district_fk` (`edcenso_district_fk`),
  CONSTRAINT `school_identification_ibfk_1` FOREIGN KEY (`edcenso_uf_fk`) REFERENCES `edcenso_uf` (`id`),
  CONSTRAINT `school_identification_ibfk_2` FOREIGN KEY (`edcenso_city_fk`) REFERENCES `edcenso_city` (`id`),
  CONSTRAINT `school_identification_ibfk_3` FOREIGN KEY (`edcenso_district_fk`) REFERENCES `edcenso_district` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `school_structure`
--

DROP TABLE IF EXISTS `school_structure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `school_structure` (
  `register_type` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '10',
  `school_inep_id_fk` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `manager_cpf` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `manager_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `manager_role` smallint(6) NOT NULL,
  `manager_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `operation_location_building` tinyint(1) DEFAULT NULL,
  `operation_location_temple` tinyint(1) DEFAULT NULL,
  `operation_location_businness_room` tinyint(1) DEFAULT NULL,
  `operation_location_instructor_house` tinyint(1) DEFAULT NULL,
  `operation_location_other_school_room` tinyint(1) DEFAULT NULL,
  `operation_location_barracks` tinyint(1) DEFAULT NULL,
  `operation_location_socioeducative_unity` tinyint(1) DEFAULT NULL,
  `operation_location_prison_unity` tinyint(1) DEFAULT NULL,
  `operation_location_other` tinyint(1) DEFAULT NULL,
  `building_occupation_situation` smallint(6) DEFAULT NULL,
  `shared_building_with_school` tinyint(1) DEFAULT NULL,
  `shared_school_inep_id_1` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shared_school_inep_id_2` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shared_school_inep_id_3` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shared_school_inep_id_4` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shared_school_inep_id_5` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shared_school_inep_id_6` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `consumed_water_type` smallint(6) DEFAULT NULL,
  `water_supply_public` tinyint(1) DEFAULT NULL,
  `water_supply_artesian_well` tinyint(1) DEFAULT NULL,
  `water_supply_well` tinyint(1) DEFAULT NULL,
  `water_supply_river` tinyint(1) DEFAULT NULL,
  `water_supply_inexistent` tinyint(1) DEFAULT NULL,
  `energy_supply_public` tinyint(1) DEFAULT NULL,
  `energy_supply_generator` tinyint(1) DEFAULT NULL,
  `energy_supply_other` tinyint(1) DEFAULT NULL,
  `energy_supply_inexistent` tinyint(1) DEFAULT NULL,
  `sewage_public` tinyint(1) DEFAULT NULL,
  `sewage_fossa` tinyint(1) DEFAULT NULL,
  `sewage_inexistent` tinyint(1) DEFAULT NULL,
  `garbage_destination_collect` tinyint(1) DEFAULT NULL,
  `garbage_destination_burn` tinyint(1) DEFAULT NULL,
  `garbage_destination_throw_away` tinyint(1) DEFAULT NULL,
  `garbage_destination_recycle` tinyint(1) DEFAULT NULL,
  `garbage_destination_bury` tinyint(1) DEFAULT NULL,
  `garbage_destination_other` tinyint(1) DEFAULT NULL,
  `dependencies_principal_room` tinyint(1) DEFAULT NULL,
  `dependencies_instructors_room` tinyint(1) DEFAULT NULL,
  `dependencies_secretary_room` tinyint(1) DEFAULT NULL,
  `dependencies_info_lab` tinyint(1) DEFAULT NULL,
  `dependencies_science_lab` tinyint(1) DEFAULT NULL,
  `dependencies_aee_room` tinyint(1) DEFAULT NULL,
  `dependencies_indoor_sports_court` tinyint(1) DEFAULT NULL,
  `dependencies_outdoor_sports_court` tinyint(1) DEFAULT NULL,
  `dependencies_kitchen` tinyint(1) DEFAULT NULL,
  `dependencies_library` tinyint(1) DEFAULT NULL,
  `dependencies_reading_room` tinyint(1) DEFAULT NULL,
  `dependencies_playground` tinyint(1) DEFAULT NULL,
  `dependencies_nursery` tinyint(1) DEFAULT NULL,
  `dependencies_outside_bathroom` tinyint(1) DEFAULT NULL,
  `dependencies_inside_bathroom` tinyint(1) DEFAULT NULL,
  `dependencies_child_bathroom` tinyint(1) DEFAULT NULL,
  `dependencies_prysical_disability_bathroom` tinyint(1) DEFAULT NULL,
  `dependencies_physical_disability_support` tinyint(1) DEFAULT NULL,
  `dependencies_bathroom_with_shower` tinyint(1) DEFAULT NULL,
  `dependencies_refectory` tinyint(1) DEFAULT NULL,
  `dependencies_storeroom` tinyint(1) DEFAULT NULL,
  `dependencies_warehouse` tinyint(1) DEFAULT NULL,
  `dependencies_auditorium` tinyint(1) DEFAULT NULL,
  `dependencies_covered_patio` tinyint(1) DEFAULT NULL,
  `dependencies_uncovered_patio` tinyint(1) DEFAULT NULL,
  `dependencies_student_accomodation` tinyint(1) DEFAULT NULL,
  `dependencies_instructor_accomodation` tinyint(1) DEFAULT NULL,
  `dependencies_green_area` tinyint(1) DEFAULT NULL,
  `dependencies_laundry` tinyint(1) DEFAULT NULL,
  `dependencies_none` tinyint(1) DEFAULT NULL,
  `classroom_count` smallint(6) DEFAULT NULL,
  `used_classroom_count` smallint(6) DEFAULT NULL,
  `equipments_tv` smallint(6) DEFAULT NULL,
  `equipments_vcr` smallint(6) DEFAULT NULL,
  `equipments_dvd` smallint(6) DEFAULT NULL,
  `equipments_satellite_dish` smallint(6) DEFAULT NULL,
  `equipments_copier` smallint(6) DEFAULT NULL,
  `equipments_overhead_projector` smallint(6) DEFAULT NULL,
  `equipments_printer` smallint(6) DEFAULT NULL,
  `equipments_stereo_system` smallint(6) DEFAULT NULL,
  `equipments_data_show` smallint(6) DEFAULT NULL,
  `equipments_fax` smallint(6) DEFAULT NULL,
  `equipments_camera` smallint(6) DEFAULT NULL,
  `equipments_computer` smallint(6) DEFAULT NULL,
  `administrative_computers_count` smallint(6) DEFAULT NULL,
  `student_computers_count` smallint(6) DEFAULT NULL,
  `internet_access` tinyint(1) DEFAULT NULL,
  `bandwidth` tinyint(1) DEFAULT NULL,
  `employees_count` smallint(6) DEFAULT NULL,
  `feeding` tinyint(1) DEFAULT NULL,
  `aee` smallint(6) DEFAULT NULL,
  `complementary_activities` smallint(6) DEFAULT NULL,
  `modalities_regular` tinyint(1) DEFAULT NULL,
  `modalities_especial` tinyint(1) DEFAULT NULL,
  `modalities_eja` tinyint(1) DEFAULT NULL,
  `stage_regular_education_creche` tinyint(1) DEFAULT NULL,
  `stage_regular_education_preschool` tinyint(1) DEFAULT NULL,
  `stage_regular_education_fundamental_eigth_years` tinyint(1) DEFAULT NULL,
  `stage_regular_education_fundamental_nine_years` tinyint(1) DEFAULT NULL,
  `stage_regular_education_high_school` tinyint(1) DEFAULT NULL,
  `stage_regular_education_high_school_integrated` tinyint(1) DEFAULT NULL,
  `stage_regular_education_high_school_normal_mastership` tinyint(1) DEFAULT NULL,
  `stage_regular_education_high_school_preofessional_education` tinyint(1) DEFAULT NULL,
  `stage_special_education_creche` tinyint(1) DEFAULT NULL,
  `stage_special_education_preschool` tinyint(1) DEFAULT NULL,
  `stage_special_education_fundamental_eigth_years` tinyint(1) DEFAULT NULL,
  `stage_special_education_fundamental_nine_years` tinyint(1) DEFAULT NULL,
  `stage_special_education_high_school` tinyint(1) DEFAULT NULL,
  `stage_special_education_high_school_integrated` tinyint(1) DEFAULT NULL,
  `stage_special_education_high_school_normal_mastership` tinyint(1) DEFAULT NULL,
  `stage_special_education_high_school_professional_education` tinyint(1) DEFAULT NULL,
  `stage_special_education_eja_fundamental_education` tinyint(1) DEFAULT NULL,
  `stage_special_education_eja_high_school_education` tinyint(1) DEFAULT NULL,
  `stage_education_eja_fundamental_education` tinyint(1) DEFAULT NULL,
  `stage_education_eja_fundamental_education_projovem` tinyint(1) DEFAULT NULL,
  `stage_education_eja_high_school_education` tinyint(1) DEFAULT NULL,
  `basic_education_cycle_organized` tinyint(1) DEFAULT NULL,
  `different_location` smallint(6) DEFAULT NULL,
  `sociocultural_didactic_material_none` tinyint(1) DEFAULT NULL,
  `sociocultural_didactic_material_quilombola` tinyint(1) DEFAULT NULL,
  `sociocultural_didactic_material_native` tinyint(1) DEFAULT NULL,
  `native_education` tinyint(1) DEFAULT NULL,
  `native_education_language_native` tinyint(1) DEFAULT NULL,
  `native_education_language_portuguese` tinyint(1) DEFAULT NULL,
  `edcenso_native_languages_fk` int(11) DEFAULT NULL,
  `brazil_literate` tinyint(1) DEFAULT '0',
  `open_weekend` tinyint(1) DEFAULT '0',
  `pedagogical_formation_by_alternance` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`school_inep_id_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student_documents_and_address`
--

DROP TABLE IF EXISTS `student_documents_and_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_documents_and_address` (
  `register_type` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '70',
  `school_inep_id_fk` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `student_fk` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rg_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rg_number_complement` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rg_number_edcenso_organ_id_emitter_fk` int(11) DEFAULT NULL,
  `rg_number_edcenso_uf_fk` int(11) DEFAULT NULL,
  `rg_number_expediction_date` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `civil_certification` smallint(6) DEFAULT NULL,
  `civil_certification_type` smallint(6) DEFAULT NULL,
  `civil_certification_term_number` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `civil_certification_sheet` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `civil_certification_book` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `civil_certification_date` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notary_office_uf_fk` int(11) DEFAULT NULL,
  `notary_office_city_fk` int(11) DEFAULT NULL,
  `edcenso_notary_office_fk` int(11) DEFAULT NULL,
  `civil_register_enrollment_number` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cpf` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `foreign_document_or_passport` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nis` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cns` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `document_failure_lack` smallint(6) DEFAULT NULL,
  `residence_zone` smallint(6) NOT NULL,
  `cep` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `number` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `complement` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `neighborhood` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `edcenso_uf_fk` int(11) DEFAULT NULL,
  `edcenso_city_fk` int(11) DEFAULT NULL,
  `received_cc` tinyint(1) DEFAULT NULL,
  `received_address` tinyint(1) DEFAULT NULL,
  `received_photo` tinyint(1) DEFAULT NULL,
  `received_nis` tinyint(1) DEFAULT NULL,
  `received_history` tinyint(1) DEFAULT NULL,
  `received_responsable_rg` tinyint(1) DEFAULT NULL,
  `received_responsable_cpf` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `school_inep_id_fk` (`school_inep_id_fk`),
  KEY `student_fk` (`student_fk`),
  KEY `rg_number_edcenso_organ_id_emitter_fk` (`rg_number_edcenso_organ_id_emitter_fk`),
  KEY `rg_number_edcenso_uf_fk` (`rg_number_edcenso_uf_fk`),
  KEY `edcenso_uf_fk` (`edcenso_uf_fk`),
  KEY `edcenso_city_fk` (`edcenso_city_fk`),
  KEY `notary_office_uf_fk` (`edcenso_uf_fk`),
  KEY `notary_office_city_fk` (`edcenso_city_fk`),
  KEY `edcenso_notary_office_fk` (`edcenso_notary_office_fk`),
  KEY `student_documents_and_address_ibfk_7` (`notary_office_uf_fk`),
  KEY `student_documents_and_address_ibfk_8` (`notary_office_city_fk`),
  CONSTRAINT `student_documents_and_address_ibfk_1` FOREIGN KEY (`school_inep_id_fk`) REFERENCES `school_identification` (`inep_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_documents_and_address_ibfk_3` FOREIGN KEY (`rg_number_edcenso_organ_id_emitter_fk`) REFERENCES `edcenso_organ_id_emitter` (`id`),
  CONSTRAINT `student_documents_and_address_ibfk_4` FOREIGN KEY (`rg_number_edcenso_uf_fk`) REFERENCES `edcenso_uf` (`id`),
  CONSTRAINT `student_documents_and_address_ibfk_5` FOREIGN KEY (`edcenso_uf_fk`) REFERENCES `edcenso_uf` (`id`),
  CONSTRAINT `student_documents_and_address_ibfk_6` FOREIGN KEY (`edcenso_city_fk`) REFERENCES `edcenso_city` (`id`),
  CONSTRAINT `student_documents_and_address_ibfk_7` FOREIGN KEY (`notary_office_uf_fk`) REFERENCES `edcenso_uf` (`id`),
  CONSTRAINT `student_documents_and_address_ibfk_8` FOREIGN KEY (`notary_office_city_fk`) REFERENCES `edcenso_city` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4049 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student_enrollment`
--

DROP TABLE IF EXISTS `student_enrollment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_enrollment` (
  `register_type` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '80',
  `school_inep_id_fk` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `student_inep_id` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `student_fk` int(11) NOT NULL,
  `classroom_inep_id` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `classroom_fk` int(11) NOT NULL,
  `enrollment_id` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unified_class` smallint(6) DEFAULT NULL,
  `edcenso_stage_vs_modality_fk` int(11) DEFAULT NULL,
  `another_scholarization_place` smallint(6) DEFAULT '3',
  `public_transport` tinyint(1) DEFAULT '0',
  `transport_responsable_government` smallint(6) DEFAULT NULL,
  `vehicle_type_van` tinyint(1) DEFAULT NULL,
  `vehicle_type_microbus` tinyint(1) DEFAULT NULL,
  `vehicle_type_bus` tinyint(1) DEFAULT NULL,
  `vehicle_type_bike` tinyint(1) DEFAULT NULL,
  `vehicle_type_animal_vehicle` tinyint(1) DEFAULT NULL,
  `vehicle_type_other_vehicle` tinyint(1) DEFAULT NULL,
  `vehicle_type_waterway_boat_5` tinyint(1) DEFAULT NULL,
  `vehicle_type_waterway_boat_5_15` tinyint(1) DEFAULT NULL,
  `vehicle_type_waterway_boat_15_35` tinyint(1) DEFAULT NULL,
  `vehicle_type_waterway_boat_35` tinyint(1) DEFAULT NULL,
  `vehicle_type_metro_or_train` tinyint(1) DEFAULT NULL,
  `student_entry_form` smallint(6) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`student_fk`,`classroom_fk`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `school_inep_id_fk` (`school_inep_id_fk`),
  KEY `student_fk` (`student_fk`),
  KEY `edcenso_stage_vs_modality_fk` (`edcenso_stage_vs_modality_fk`),
  KEY `fk_student_enrollment_1` (`student_fk`),
  KEY `fk_student_enrollment_2` (`classroom_fk`),
  CONSTRAINT `fk_student_enrollment_1` FOREIGN KEY (`student_fk`) REFERENCES `student_identification` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_student_enrollment_2` FOREIGN KEY (`classroom_fk`) REFERENCES `classroom` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `student_enrollment_ibfk_1` FOREIGN KEY (`school_inep_id_fk`) REFERENCES `school_identification` (`inep_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_enrollment_ibfk_4` FOREIGN KEY (`edcenso_stage_vs_modality_fk`) REFERENCES `edcenso_stage_vs_modality` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4637 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student_identification`
--

DROP TABLE IF EXISTS `student_identification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_identification` (
  `register_type` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '60',
  `school_inep_id_fk` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `inep_id` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nis` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthday` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `sex` smallint(6) NOT NULL,
  `color_race` smallint(6) NOT NULL,
  `filiation` smallint(6) NOT NULL,
  `mother_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `father_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nationality` smallint(6) NOT NULL,
  `edcenso_nation_fk` int(11) NOT NULL,
  `edcenso_uf_fk` int(11) DEFAULT NULL,
  `edcenso_city_fk` int(11) DEFAULT NULL,
  `deficiency` tinyint(1) NOT NULL,
  `deficiency_type_blindness` tinyint(1) DEFAULT NULL,
  `deficiency_type_low_vision` tinyint(1) DEFAULT NULL,
  `deficiency_type_deafness` tinyint(1) DEFAULT NULL,
  `deficiency_type_disability_hearing` tinyint(1) DEFAULT NULL,
  `deficiency_type_deafblindness` tinyint(1) DEFAULT NULL,
  `deficiency_type_phisical_disability` tinyint(1) DEFAULT NULL,
  `deficiency_type_intelectual_disability` tinyint(1) DEFAULT NULL,
  `deficiency_type_multiple_disabilities` tinyint(1) DEFAULT NULL,
  `deficiency_type_autism` tinyint(1) DEFAULT NULL,
  `deficiency_type_aspenger_syndrome` tinyint(1) DEFAULT NULL,
  `deficiency_type_rett_syndrome` tinyint(1) DEFAULT NULL,
  `deficiency_type_childhood_disintegrative_disorder` tinyint(1) DEFAULT NULL,
  `deficiency_type_gifted` tinyint(1) DEFAULT NULL,
  `resource_aid_lector` tinyint(1) DEFAULT NULL,
  `resource_aid_transcription` tinyint(1) DEFAULT NULL,
  `resource_interpreter_guide` tinyint(1) DEFAULT NULL,
  `resource_interpreter_libras` tinyint(1) DEFAULT NULL,
  `resource_lip_reading` tinyint(1) DEFAULT NULL,
  `resource_zoomed_test_16` tinyint(1) DEFAULT NULL,
  `resource_zoomed_test_20` tinyint(1) DEFAULT NULL,
  `resource_zoomed_test_24` tinyint(1) DEFAULT NULL,
  `resource_braille_test` tinyint(1) DEFAULT NULL,
  `resource_none` tinyint(1) DEFAULT NULL,
  `send_year` int(11) NOT NULL,
  `last_change` timestamp NULL DEFAULT NULL,
  `responsable` smallint(6) DEFAULT NULL,
  `responsable_telephone` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `responsable_name` varchar(90) COLLATE utf8_unicode_ci DEFAULT NULL,
  `responsable_rg` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `responsable_cpf` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `responsable_scholarity` smallint(6) DEFAULT NULL,
  `responsable_job` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bf_participator` tinyint(1) DEFAULT NULL,
  `food_restrictions` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `edcenso_nation_fk` (`edcenso_nation_fk`),
  KEY `edcenso_uf_fk` (`edcenso_uf_fk`),
  KEY `edcenso_city_fk` (`edcenso_city_fk`),
  KEY `school_inep_id_fk` (`school_inep_id_fk`),
  CONSTRAINT `student_identification_ibfk_1` FOREIGN KEY (`edcenso_nation_fk`) REFERENCES `edcenso_nation` (`id`),
  CONSTRAINT `student_identification_ibfk_2` FOREIGN KEY (`edcenso_uf_fk`) REFERENCES `edcenso_uf` (`id`),
  CONSTRAINT `student_identification_ibfk_3` FOREIGN KEY (`edcenso_city_fk`) REFERENCES `edcenso_city` (`id`),
  CONSTRAINT `student_identification_ibfk_6` FOREIGN KEY (`school_inep_id_fk`) REFERENCES `school_identification` (`inep_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4049 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary view structure for view `studentsdeclaration`
--

DROP TABLE IF EXISTS `studentsdeclaration`;
/*!50001 DROP VIEW IF EXISTS `studentsdeclaration`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `studentsdeclaration` AS SELECT 
 1 AS `name`,
 1 AS `birthday`,
 1 AS `birth_city`,
 1 AS `birth_uf`,
 1 AS `cc`,
 1 AS `cc_new`,
 1 AS `cc_number`,
 1 AS `cc_book`,
 1 AS `cc_sheet`,
 1 AS `cc_city`,
 1 AS `cc_uf`,
 1 AS `mother`,
 1 AS `father`,
 1 AS `year`,
 1 AS `classroom`,
 1 AS `turn`,
 1 AS `stage`,
 1 AS `classroom_id`,
 1 AS `student_id`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `studentsfile`
--

DROP TABLE IF EXISTS `studentsfile`;
/*!50001 DROP VIEW IF EXISTS `studentsfile`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `studentsfile` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `birth_city`,
 1 AS `birthday`,
 1 AS `birth_uf`,
 1 AS `nation`,
 1 AS `address`,
 1 AS `adddress_city`,
 1 AS `address_uf`,
 1 AS `cep`,
 1 AS `rg`,
 1 AS `cc`,
 1 AS `cc_new`,
 1 AS `cc_number`,
 1 AS `cc_book`,
 1 AS `cc_sheet`,
 1 AS `cc_city`,
 1 AS `cc_uf`,
 1 AS `mother`,
 1 AS `father`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `studentsfile_boquim`
--

DROP TABLE IF EXISTS `studentsfile_boquim`;
/*!50001 DROP VIEW IF EXISTS `studentsfile_boquim`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `studentsfile_boquim` AS SELECT 
 1 AS `id`,
 1 AS `inep_id`,
 1 AS `nis`,
 1 AS `name`,
 1 AS `birth_city`,
 1 AS `gender`,
 1 AS `color`,
 1 AS `birthday`,
 1 AS `birth_uf`,
 1 AS `nation`,
 1 AS `address`,
 1 AS `adddress_city`,
 1 AS `address_uf`,
 1 AS `number`,
 1 AS `cep`,
 1 AS `rg`,
 1 AS `cpf`,
 1 AS `cc`,
 1 AS `cc_type`,
 1 AS `cc_name`,
 1 AS `cc_new`,
 1 AS `cc_number`,
 1 AS `cc_book`,
 1 AS `cc_sheet`,
 1 AS `cc_city`,
 1 AS `cc_uf`,
 1 AS `mother`,
 1 AS `father`,
 1 AS `responsable`,
 1 AS `responsable_name`,
 1 AS `responsable_rg`,
 1 AS `responsable_cpf`,
 1 AS `responsable_scholarity`,
 1 AS `responsable_job`,
 1 AS `bf_participator`,
 1 AS `food_restrictions`,
 1 AS `responsable_telephone`,
 1 AS `deficiency`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `active` smallint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_school`
--

DROP TABLE IF EXISTS `users_school`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_school` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_fk` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `user_fk` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_school_1` (`school_fk`),
  KEY `fk_users_school_2` (`user_fk`),
  CONSTRAINT `fk_users_school_1` FOREIGN KEY (`school_fk`) REFERENCES `school_identification` (`inep_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_school_2` FOREIGN KEY (`user_fk`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Final view structure for view `ata_performance`
--

/*!50001 DROP VIEW IF EXISTS `ata_performance`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `ata_performance` AS select `s`.`name` AS `school`,concat(`ec`.`name`,' - ',`eu`.`acronym`) AS `city`,date_format(now(),'%d') AS `day`,date_format(now(),'%m') AS `month`,date_format(now(),'%Y') AS `year`,substring_index(`svm`.`name`,' - ',1) AS `ensino`,`c`.`name` AS `name`,(case `c`.`turn` when 'M' then 'Matutino' when 'T' then 'Vespertino' else 'Noturno' end) AS `turn`,substring_index(`svm`.`name`,' - ',-(1)) AS `serie`,`c`.`school_year` AS `school_year`,`c`.`id` AS `classroom_id`,concat_ws('|',if((`c`.`discipline_biology` = 1),'Biologia',NULL),if((`c`.`discipline_science` = 1),'Ciência',NULL),if((`c`.`discipline_physical_education` = 1),'Educação Física',NULL),if((`c`.`discipline_religious` = 1),'Ensino Religioso',NULL),if((`c`.`discipline_philosophy` = 1),'Filosofia',NULL),if((`c`.`discipline_physics` = 1),'Física',NULL),if((`c`.`discipline_geography` = 1),'Geografia',NULL),if((`c`.`discipline_history` = 1),'História',NULL),if((`c`.`discipline_native_language` = 1),'Lingua Nativa',NULL),if((`c`.`discipline_mathematics` = 1),'Matemática',NULL),if((`c`.`discipline_pedagogical` = 1),'Pedagogia',NULL),if((`c`.`discipline_language_portuguese_literature` = 1),'Português',NULL),if((`c`.`discipline_chemistry` = 1),'Química',NULL),if((`c`.`discipline_arts` = 1),'Ártes',NULL),if((`c`.`discipline_professional_disciplines` = 1),'Disciplina Proficionalizante',NULL),if((`c`.`discipline_foreign_language_spanish` = 1),'Espanhol',NULL),if((`c`.`discipline_social_study` = 1),'Estudo Social',NULL),if((`c`.`discipline_foreign_language_franch` = 1),'Francês',NULL),if((`c`.`discipline_foreign_language_english` = 1),'Inglês',NULL),if((`c`.`discipline_informatics` = 1),'Informática',NULL),if((`c`.`discipline_libras` = 1),'Libras',NULL),if((`c`.`discipline_foreign_language_other` = 1),'Outro Idioma',NULL),if((`c`.`discipline_sociocultural_diversity` = 1),'Sociedade e Cultura',NULL),if((`c`.`discipline_others` = 1),'Outras',NULL)) AS `disciplines` from ((((`classroom` `c` join `school_identification` `s` on((`c`.`school_inep_fk` = `s`.`inep_id`))) left join `edcenso_city` `ec` on((`s`.`edcenso_city_fk` = `ec`.`id`))) left join `edcenso_uf` `eu` on((`s`.`edcenso_uf_fk` = `eu`.`id`))) left join `edcenso_stage_vs_modality` `svm` on((`c`.`edcenso_stage_vs_modality_fk` = `svm`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `classroom_enrollment`
--

/*!50001 DROP VIEW IF EXISTS `classroom_enrollment`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `classroom_enrollment` AS select `s`.`id` AS `enrollment`,`s`.`name` AS `name`,if((`s`.`sex` = 1),'M','F') AS `sex`,concat(`s`.`birthday`,'<br>',timestampdiff(YEAR,str_to_date(`s`.`birthday`,'%d/%m/%Y'),curdate()),'a ',(timestampdiff(MONTH,str_to_date(`s`.`birthday`,'%d/%m/%Y'),curdate()) % 12),'m') AS `birthday`,`en`.`acronym` AS `nation`,`ec`.`name` AS `city`,`sd`.`address` AS `address`,`sd`.`civil_certification` AS `cc`,`sd`.`civil_register_enrollment_number` AS `cc_new`,`sd`.`civil_certification_term_number` AS `cc_number`,`sd`.`civil_certification_book` AS `cc_book`,`sd`.`civil_certification_sheet` AS `cc_sheet`,concat(`s`.`mother_name`,'<br>',`s`.`father_name`) AS `parents`,`s`.`deficiency` AS `deficiency`,`c`.`id` AS `classroom_id`,`c`.`school_year` AS `year` from (((((`student_identification` `s` join `student_documents_and_address` `sd` on((`s`.`id` = `sd`.`id`))) left join `edcenso_nation` `en` on((`s`.`edcenso_nation_fk` = `en`.`id`))) left join `edcenso_city` `ec` on((`s`.`edcenso_city_fk` = `ec`.`id`))) join `student_enrollment` `se` on((`s`.`id` = `se`.`student_fk`))) join `classroom` `c` on((`se`.`classroom_fk` = `c`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `classroom_instructors`
--

/*!50001 DROP VIEW IF EXISTS `classroom_instructors`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `classroom_instructors` AS select `c`.`id` AS `id`,`c`.`name` AS `name`,`c`.`school_inep_fk` AS `school_inep_fk`,concat_ws(' - ',concat_ws(':',`c`.`initial_hour`,`c`.`initial_minute`),concat_ws(':',`c`.`final_hour`,`c`.`final_minute`)) AS `time`,(case `c`.`assistance_type` when 0 then 'NÃO SE APLICA' when 1 then 'CLASSE HOSPITALAR' when 2 then 'UNIDADE DE ATENDIMENTO SOCIOEDUCATIVO' when 3 then 'UNIDADE PRISIONAL ATIVIDADE COMPLEMENTAR' else 'ATENDIMENTO EDUCACIONALESPECIALIZADO (AEE)' end) AS `assistance_type`,(case `c`.`modality` when 1 then 'REGULAR' when 2 then 'ESPECIAL' else 'EJA' end) AS `modality`,`esm`.`name` AS `stage`,concat_ws(' - ',if((`c`.`week_days_sunday` = 1),'DOMINGO',NULL),if((`c`.`week_days_monday` = 1),'SEGUNDA',NULL),if((`c`.`week_days_tuesday` = 1),'TERÇA',NULL),if((`c`.`week_days_wednesday` = 1),'QUARTA',NULL),if((`c`.`week_days_thursday` = 1),'QUINTA',NULL),if((`c`.`week_days_friday` = 1),'SEXTA',NULL),if((`c`.`week_days_saturday` = 1),'SÁBADO',NULL)) AS `week_days`,`inf`.`id` AS `instructor_id`,`inf`.`birthday_date` AS `birthday_date`,`inf`.`name` AS `instructor_name`,(case `ivd`.`scholarity` when 1 then 'Fundamental Incompleto' when 2 then 'Fundamental Completo' when 3 then 'Ensino Médio Normal/Magistério' when 4 then 'Ensino Médio Normal/Magistério Indígena' when 5 then 'Ensino Médio' else 'Superior' end) AS `scholarity`,concat_ws('<br>',`d1`.`name`,`d2`.`name`,`d3`.`name`,`d4`.`name`,`d5`.`name`,`d6`.`name`,`d7`.`name`,`d8`.`name`,`d9`.`name`,`d10`.`name`,`d11`.`name`,`d12`.`name`,`d13`.`name`) AS `disciplines`,`c`.`school_year` AS `school_year` from (((((((((((((((((`classroom` `c` join `instructor_teaching_data` `itd` on((`itd`.`classroom_id_fk` = `c`.`id`))) join `instructor_identification` `inf` on((`inf`.`id` = `itd`.`instructor_fk`))) join `instructor_variable_data` `ivd` on((`inf`.`id` = `ivd`.`id`))) join `edcenso_stage_vs_modality` `esm` on((`c`.`edcenso_stage_vs_modality_fk` = `esm`.`id`))) left join `edcenso_discipline` `d1` on((`d1`.`id` = `itd`.`discipline_1_fk`))) left join `edcenso_discipline` `d2` on((`d2`.`id` = `itd`.`discipline_2_fk`))) left join `edcenso_discipline` `d3` on((`d3`.`id` = `itd`.`discipline_3_fk`))) left join `edcenso_discipline` `d4` on((`d4`.`id` = `itd`.`discipline_4_fk`))) left join `edcenso_discipline` `d5` on((`d5`.`id` = `itd`.`discipline_5_fk`))) left join `edcenso_discipline` `d6` on((`d6`.`id` = `itd`.`discipline_6_fk`))) left join `edcenso_discipline` `d7` on((`d7`.`id` = `itd`.`discipline_7_fk`))) left join `edcenso_discipline` `d8` on((`d8`.`id` = `itd`.`discipline_8_fk`))) left join `edcenso_discipline` `d9` on((`d9`.`id` = `itd`.`discipline_9_fk`))) left join `edcenso_discipline` `d10` on((`d10`.`id` = `itd`.`discipline_10_fk`))) left join `edcenso_discipline` `d11` on((`d11`.`id` = `itd`.`discipline_11_fk`))) left join `edcenso_discipline` `d12` on((`d12`.`id` = `itd`.`discipline_12_fk`))) left join `edcenso_discipline` `d13` on((`d13`.`id` = `itd`.`discipline_13_fk`))) order by `c`.`id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `classroom_qtd_students`
--

/*!50001 DROP VIEW IF EXISTS `classroom_qtd_students`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `classroom_qtd_students` AS select `c`.`school_inep_fk` AS `school_inep_fk`,`c`.`id` AS `id`,`c`.`name` AS `name`,concat_ws(' - ',concat_ws(':',`c`.`initial_hour`,`c`.`initial_minute`),concat_ws(':',`c`.`final_hour`,`c`.`final_minute`)) AS `time`,(case `c`.`assistance_type` when 0 then 'NÃO SE APLICA' when 1 then 'CLASSE HOSPITALAR' when 2 then 'UNIDADE DE ATENDIMENTO SOCIOEDUCATIVO' when 3 then 'UNIDADE PRISIONAL ATIVIDADE COMPLEMENTAR' else 'ATENDIMENTO EDUCACIONALESPECIALIZADO (AEE)' end) AS `assistance_type`,(case `c`.`modality` when 1 then 'REGULAR' when 2 then 'ESPECIAL' else 'EJA' end) AS `modality`,`esm`.`name` AS `stage`,count(`c`.`id`) AS `students`,`c`.`school_year` AS `school_year` from ((`classroom` `c` join `student_enrollment` `se` on((`c`.`id` = `se`.`classroom_fk`))) join `edcenso_stage_vs_modality` `esm` on((`c`.`edcenso_stage_vs_modality_fk` = `esm`.`id`))) group by `c`.`id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `imob_classroom`
--

/*!50001 DROP VIEW IF EXISTS `imob_classroom`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `imob_classroom` AS select count(distinct `c`.`id`) AS `c_total`,`c`.`school_year` AS `year`,`c`.`school_inep_fk` AS `school_inep_fk` from `classroom` `c` group by `c`.`school_year`,`c`.`school_inep_fk` order by `c`.`school_year` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `imob_student_enrollment`
--

/*!50001 DROP VIEW IF EXISTS `imob_student_enrollment`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `imob_student_enrollment` AS select count(distinct `se`.`id`) AS `se_total`,sum((case when ((month(`se`.`create_date`) >= 1) and (month(`se`.`create_date`) < 6)) then 1 else 0 end)) AS `se_half1`,sum((case when ((month(`se`.`create_date`) >= 6) and (month(`se`.`create_date`) < 12)) then 1 else 0 end)) AS `se_half2`,`c`.`school_year` AS `year` from (`student_enrollment` `se` join `classroom` `c` on((`se`.`classroom_fk` = `c`.`id`))) group by `c`.`school_year` order by `c`.`school_year` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `studentsdeclaration`
--

/*!50001 DROP VIEW IF EXISTS `studentsdeclaration`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `studentsdeclaration` AS select `s`.`name` AS `name`,`s`.`birthday` AS `birthday`,`ec`.`name` AS `birth_city`,`eu`.`acronym` AS `birth_uf`,`sd`.`civil_certification` AS `cc`,`sd`.`civil_register_enrollment_number` AS `cc_new`,`sd`.`civil_certification_term_number` AS `cc_number`,`sd`.`civil_certification_book` AS `cc_book`,`sd`.`civil_certification_sheet` AS `cc_sheet`,`ecn`.`name` AS `cc_city`,`eun`.`acronym` AS `cc_uf`,`s`.`mother_name` AS `mother`,`s`.`father_name` AS `father`,`c`.`school_year` AS `year`,`c`.`name` AS `classroom`,`c`.`turn` AS `turn`,`edsm`.`name` AS `stage`,`c`.`id` AS `classroom_id`,`s`.`id` AS `student_id` from (((((((((`student_identification` `s` left join `edcenso_uf` `eu` on((`s`.`edcenso_uf_fk` = `eu`.`id`))) left join `edcenso_city` `ec` on((`s`.`edcenso_city_fk` = `ec`.`id`))) left join `edcenso_nation` `en` on((`s`.`edcenso_nation_fk` = `en`.`id`))) join `student_documents_and_address` `sd` on((`s`.`id` = `sd`.`id`))) left join `edcenso_uf` `eun` on((`sd`.`notary_office_uf_fk` = `eun`.`id`))) left join `edcenso_city` `ecn` on((`sd`.`notary_office_city_fk` = `ecn`.`id`))) join `student_enrollment` `se` on((`s`.`id` = `se`.`student_fk`))) join `classroom` `c` on(((`se`.`classroom_fk` = `c`.`id`) and (`c`.`assistance_type` <> 4)))) left join `edcenso_stage_vs_modality` `edsm` on((((`se`.`edcenso_stage_vs_modality_fk` is not null) and (`edsm`.`id` = `se`.`edcenso_stage_vs_modality_fk`)) or (`edsm`.`id` = `c`.`edcenso_stage_vs_modality_fk`)))) order by `s`.`name` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `studentsfile`
--

/*!50001 DROP VIEW IF EXISTS `studentsfile`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `studentsfile` AS select `s`.`id` AS `id`,`s`.`name` AS `name`,`ec`.`name` AS `birth_city`,`s`.`birthday` AS `birthday`,`eu`.`acronym` AS `birth_uf`,`en`.`name` AS `nation`,`sd`.`address` AS `address`,`eca`.`name` AS `adddress_city`,`eua`.`acronym` AS `address_uf`,`sd`.`cep` AS `cep`,`sd`.`rg_number` AS `rg`,`sd`.`civil_certification` AS `cc`,`sd`.`civil_register_enrollment_number` AS `cc_new`,`sd`.`civil_certification_term_number` AS `cc_number`,`sd`.`civil_certification_book` AS `cc_book`,`sd`.`civil_certification_sheet` AS `cc_sheet`,`ecn`.`name` AS `cc_city`,`eun`.`acronym` AS `cc_uf`,`s`.`mother_name` AS `mother`,`s`.`father_name` AS `father` from ((((((((`student_identification` `s` left join `edcenso_uf` `eu` on((`s`.`edcenso_uf_fk` = `eu`.`id`))) left join `edcenso_city` `ec` on((`s`.`edcenso_city_fk` = `ec`.`id`))) left join `edcenso_nation` `en` on((`s`.`edcenso_nation_fk` = `en`.`id`))) join `student_documents_and_address` `sd` on((`s`.`id` = `sd`.`id`))) left join `edcenso_uf` `eua` on((`sd`.`edcenso_uf_fk` = `eua`.`id`))) left join `edcenso_city` `eca` on((`sd`.`edcenso_city_fk` = `eca`.`id`))) left join `edcenso_uf` `eun` on((`sd`.`notary_office_uf_fk` = `eun`.`id`))) left join `edcenso_city` `ecn` on((`sd`.`notary_office_city_fk` = `ecn`.`id`))) order by `s`.`name` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `studentsfile_boquim`
--

/*!50001 DROP VIEW IF EXISTS `studentsfile_boquim`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `studentsfile_boquim` AS select `s`.`id` AS `id`,`s`.`inep_id` AS `inep_id`,`sd`.`nis` AS `nis`,`s`.`name` AS `name`,`ec`.`name` AS `birth_city`,if((`s`.`sex` = 1),'Masculino','Feminino') AS `gender`,(case `s`.`color_race` when '1' then 'Branca' when '2' then 'Preta' when '3' then 'Parda' when '4' then 'Amarela' when '5' then 'Indígena' else 'Não Declarada' end) AS `color`,`s`.`birthday` AS `birthday`,`eu`.`acronym` AS `birth_uf`,`en`.`name` AS `nation`,`sd`.`address` AS `address`,`eca`.`name` AS `adddress_city`,`eua`.`acronym` AS `address_uf`,`sd`.`number` AS `number`,`sd`.`cep` AS `cep`,`sd`.`rg_number` AS `rg`,`sd`.`cpf` AS `cpf`,`sd`.`civil_certification` AS `cc`,if((`sd`.`civil_certification_type` = 2),'Casamento','Nascimento') AS `cc_type`,`eno`.`name` AS `cc_name`,`sd`.`civil_register_enrollment_number` AS `cc_new`,`sd`.`civil_certification_term_number` AS `cc_number`,`sd`.`civil_certification_book` AS `cc_book`,`sd`.`civil_certification_sheet` AS `cc_sheet`,`ecn`.`name` AS `cc_city`,`eun`.`acronym` AS `cc_uf`,`s`.`mother_name` AS `mother`,`s`.`father_name` AS `father`,`s`.`responsable` AS `responsable`,(case `s`.`responsable` when '0' then `s`.`father_name` when '1' then `s`.`mother_name` when '2' then `s`.`responsable_name` end) AS `responsable_name`,`s`.`responsable_rg` AS `responsable_rg`,`s`.`responsable_cpf` AS `responsable_cpf`,(case `s`.`responsable_scholarity` when '0' then 'Não sabe Ler e Escrever' when '1' then 'Sabe Ler e Escrever' when '2' then 'Ensino Fundamental Incompleto' when '3' then 'Ensino Fundamental Completo' when '4' then 'Ensino Médio Incompleto' when '5' then 'Ensino Médio Completo' when '6' then 'Ensino Superior Incompleto' when '7' then 'Ensino Superior Completo' end) AS `responsable_scholarity`,`s`.`responsable_job` AS `responsable_job`,if((`s`.`bf_participator` = 0),'Não Participa','Participa') AS `bf_participator`,`s`.`food_restrictions` AS `food_restrictions`,`s`.`responsable_telephone` AS `responsable_telephone`,if((`s`.`deficiency` = 0),'Não Possui',concat_ws(': ','Possui',concat_ws(',',if((`s`.`deficiency_type_blindness` = 1),'Cegueira',NULL),if((`s`.`deficiency_type_low_vision` = 1),'Baixa visão',NULL),if((`s`.`deficiency_type_deafness` = 1),'Surdez',NULL),if((`s`.`deficiency_type_disability_hearing` = 1),'Deficiência Auditiva',NULL),if((`s`.`deficiency_type_deafblindness` = 1),'Surdocegueira',NULL),if((`s`.`deficiency_type_phisical_disability` = 1),'Deficiência Física',NULL),if((`s`.`deficiency_type_intelectual_disability` = 1),'Deficiência Intelectual',NULL),if((`s`.`deficiency_type_multiple_disabilities` = 1),'Deficiência Múltipla',NULL),if((`s`.`deficiency_type_autism` = 1),'Autismo Infantil',NULL),if((`s`.`deficiency_type_aspenger_syndrome` = 1),'Síndrome de Asperger',NULL),if((`s`.`deficiency_type_rett_syndrome` = 1),'Síndrome de Rett',NULL),if((`s`.`deficiency_type_childhood_disintegrative_disorder` = 1),'Transtorno Desintegrativo da Infância',NULL),if((`s`.`deficiency_type_gifted` = 1),'Altas habilidades / Superdotação',NULL)))) AS `deficiency` from (((((((((`student_identification` `s` join `student_documents_and_address` `sd` on((`s`.`id` = `sd`.`id`))) left join `edcenso_uf` `eu` on((`s`.`edcenso_uf_fk` = `eu`.`id`))) left join `edcenso_city` `ec` on((`s`.`edcenso_city_fk` = `ec`.`id`))) left join `edcenso_nation` `en` on((`s`.`edcenso_nation_fk` = `en`.`id`))) left join `edcenso_uf` `eua` on((`sd`.`edcenso_uf_fk` = `eua`.`id`))) left join `edcenso_city` `eca` on((`sd`.`edcenso_city_fk` = `eca`.`id`))) left join `edcenso_uf` `eun` on((`sd`.`notary_office_uf_fk` = `eun`.`id`))) left join `edcenso_city` `ecn` on((`sd`.`notary_office_city_fk` = `ecn`.`id`))) left join `edcenso_notary_office` `eno` on((`sd`.`edcenso_notary_office_fk` = `eno`.`cod`))) order by `s`.`name` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-07-16 10:27:10
