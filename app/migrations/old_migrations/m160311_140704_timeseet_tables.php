<?php

	/**
	 * Class m160311_140704_timeseet_tables
	 *
	 * $yiic migrate up --migrationPath=application.modules.timesheet.migrations
	 *
	 */
	class m160311_140704_timeseet_tables extends CDbMigration {

		public function up() {
			$this->createTable('instructor_disciplines', [
				'id' => 'pk',
				'stage_vs_modality_fk' => 'int NOT NULL',
				'discipline_fk' => 'int NOT NULL',
				'instructor_fk' => 'int NOT NULL',
			], 'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

			$this->createTable('schedule', [
				'id' => 'pk',
				'instructor_fk' => 'int',
				'discipline_fk' => 'int NOT NULL',
				'classroom_fk' => 'int NOT NULL',
				'week_day' => 'int NOT NULL',
				'initial_hour' => 'time NOT NULL',
				'final_hour' => 'time NOT NULL',
			], 'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

			$this->createTable('unavailability', [
				'id' => 'pk',
				'instructor_fk' => 'int NOT NULL',
				'week_day' => 'int NOT NULL',
				'initial_hour' => 'time NOT NULL',
				'final_hour' => 'time NOT NULL',
			], 'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

			$this->createTable('instructor_school', [
				'id' => 'pk',
				'school_fk' => 'VARCHAR(8) NOT NULL',
				'instructor_fk' => 'int NOT NULL',
			], 'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');


			//instructor -> instructor_discipline
			$this->addForeignKey('instructor_instructor_discipline', 'instructor_disciplines', 'instructor_fk', 'instructor_identification', 'id', 'CASCADE', 'CASCADE');
			//discipline -> instructor_discipline
			$this->addForeignKey('discipline_instructor_discipline', 'instructor_disciplines', 'discipline_fk', 'edcenso_discipline', 'id', 'CASCADE', 'CASCADE');
			//stage -> instructor_discipline
			$this->addForeignKey('stage_instructor_discipline', 'instructor_disciplines', 'stage_vs_modality_fk', 'edcenso_stage_vs_modality', 'id', 'CASCADE', 'CASCADE');

			//instructor -> unavailability
			$this->addForeignKey('instructor_unavailability', 'unavailability', 'instructor_fk', 'instructor_identification', 'id', 'CASCADE', 'CASCADE');

			//instructor -> schedule
			$this->addForeignKey('instructor_schedule', 'schedule', 'instructor_fk', 'instructor_identification', 'id', 'CASCADE', 'CASCADE');
			//discipline -> schedule
			$this->addForeignKey('discipline_schedule', 'schedule', 'discipline_fk', 'edcenso_discipline', 'id', 'CASCADE', 'CASCADE');
			//discipline -> classroom
			$this->addForeignKey('classroom_schedule', 'schedule', 'classroom_fk', 'classroom', 'id', 'CASCADE', 'CASCADE');

			//instructor -> instructor_discipline
			$this->addForeignKey('instructor_instructor_school', 'instructor_school', 'instructor_fk', 'instructor_identification', 'id', 'CASCADE', 'CASCADE');
			//discipline -> instructor_discipline
			$this->addForeignKey('school_instructor_school', 'instructor_school', 'school_fk', 'school_identification', 'inep_id', 'CASCADE', 'CASCADE');

		}

		public function down() {
			$this->dropForeignKey('instructor_instructor_discipline', 'instructor_disciplines');
			$this->dropForeignKey('discipline_instructor_discipline', 'instructor_disciplines');
			$this->dropForeignKey('stage_instructor_discipline', 'instructor_disciplines');

			$this->dropForeignKey('instructor_unavailability', 'unavailability');

			$this->dropForeignKey('instructor_schedule', 'schedule');
			$this->dropForeignKey('discipline_schedule', 'schedule');
			$this->dropForeignKey('classroom_schedule', 'schedule');

			$this->dropForeignKey('instructor_instructor_school', 'instructor_school');
			$this->dropForeignKey('school_instructor_school', 'instructor_school');

			$this->dropTable('instructor_disciplines');
			$this->dropTable('unavailability');
			$this->dropTable('schedule');
			$this->dropTable('instructor_school');
		}

	}