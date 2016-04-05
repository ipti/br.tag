<?php

	class m160405_143309_edcenso_2016 extends CDbMigration {
		public function up() {

			echo("Registro 00");
			$this->addColumn("school_identification","manager_cpf", "VARCHAR(11) AFTER inep_id");
			$this->addColumn("school_identification","manager_name", "VARCHAR(100) AFTER manager_cpf");
			$this->addColumn("school_identification","manager_role", "INT AFTER manager_name");
			$this->addColumn("school_identification","manager_email", "VARCHAR(50) AFTER manager_role");
			$schools = SchoolIdentification::model()->findAll();
			/* @var $school SchoolIdentification */
			foreach($schools as $school){
				$structure = $school->structure;
				$school->manager_cpf = $structure->manager_cpf;
				$school->manager_name = $structure->manager_name;
				$school->manager_role = $structure->manager_role;
				$school->manager_email = $structure->manager_email;
				$school->save();
			}
			$this->addColumn("school_identification","offer_or_linked_unity", "INT NOT NULL DEFAULT -1");
			$this->addColumn("school_identification","inep_head_school", "VARCHAR(8)");
			$this->addColumn("school_identification","ies_code", "VARCHAR(14)");

			echo("Registro 10");
			$this->dropColumn("school_structure","manager_cpf");
			$this->dropColumn("school_structure","manager_name");
			$this->dropColumn("school_structure","manager_role");
			$this->dropColumn("school_structure","manager_email");



		}

		public function down() {
			echo "m160405_143309_edcenso_2016 does not support migration down.\n";

			echo("Registro 00");
			$this->addColumn("school_structure","manager_cpf", "VARCHAR(11) AFTER school_inep_id_fk");
			$this->addColumn("school_structure","manager_name", "VARCHAR(100) AFTER manager_cpf");
			$this->addColumn("school_structure","manager_role", "INT AFTER manager_name");
			$this->addColumn("school_structure","manager_email", "VARCHAR(50) AFTER manager_role");

			$schools = SchoolIdentification::model()->findAll();
			/* @var $school SchoolIdentification */
			foreach($schools as $school){
				$structure = $school->structure;
				$structure->manager_cpf = $school->manager_cpf;
				$structure->manager_name = $school->manager_name;
				$structure->manager_role = $school->manager_role;
				$structure->manager_email = $school->manager_email;
				$structure->save();
			}

			$this->dropColumn("school_identification","manager_cpf");
			$this->dropColumn("school_identification","manager_name");
			$this->dropColumn("school_identification","manager_role");
			$this->dropColumn("school_identification","manager_email");
			$this->dropColumn("school_identification","offer_or_linked_unity");
			$this->dropColumn("school_identification","inep_head_school");
			$this->dropColumn("school_identification","ies_code");



			return FALSE;
		}

	}