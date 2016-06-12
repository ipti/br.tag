<?php

	class m160506_143333_unitary_schedule extends CDbMigration {

		// Use safeUp/safeDown to do migration with transaction
		public function safeUp() {
			$this->addColumn("unavailability", "instructor_school_fk","int after id");
			$this->addColumn("unavailability", "schedule","int after week_day");
			$this->addColumn("unavailability", "turn","int after schedule");
			$this->addForeignKey('instructor_school_unavailability', 'unavailability', 'instructor_school_fk', 'instructor_school', 'id', 'CASCADE', 'CASCADE');

			$this->dropForeignKey('instructor_unavailability', 'unavailability');
			$this->dropColumn("unavailability", "instructor_fk");
			$this->dropColumn("unavailability", "initial_hour");
			$this->dropColumn("unavailability", "final_hour");


			$this->addColumn("schedule", "schedule","int after week_day");
			$this->addColumn("schedule", "turn","int after schedule");

			$this->dropColumn("schedule", "initial_hour");
			$this->dropColumn("schedule", "final_hour");

		}

		public function safeDown() {
			$this->addColumn("unavailability", "instructor_fk","int after id");
			$this->addColumn("unavailability", "initial_hour","int after week_day");
			$this->addColumn("unavailability", "final_hour","int after initial_hour");
			$this->addForeignKey('instructor_unavailability', 'unavailability', 'instructor_fk', 'instructor_identification', 'id', 'CASCADE', 'CASCADE');

			$this->dropForeignKey('instructor_school_unavailability', 'unavailability');
			$this->dropColumn("unavailability", "instructor_school_fk");
			$this->dropColumn("unavailability", "schedule");
			$this->dropColumn("unavailability", "turn");


			$this->addColumn("schedule", "initial_hour","int after week_day");
			$this->addColumn("schedule", "final_hour","int after initial_hour");

			$this->dropColumn("schedule", "schedule");
			$this->dropColumn("schedule", "turn");
		}

	}