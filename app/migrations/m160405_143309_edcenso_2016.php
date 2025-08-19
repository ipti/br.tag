<?php

	class m160405_143309_edcenso_2016 extends CDbMigration {
		public function safeUp() {
				echo("Registro 00\n");
				$this->addColumn("school_identification","manager_cpf", "VARCHAR(11) AFTER inep_id");
				$this->addColumn("school_identification","manager_name", "VARCHAR(100) AFTER manager_cpf");
				$this->addColumn("school_identification","manager_role", "INT AFTER manager_name");
				$this->addColumn("school_identification","manager_email", "VARCHAR(50) AFTER manager_role");

				$this->execute("
				update school_identification, school_structure
				set school_identification.manager_cpf = school_structure.manager_cpf,
					school_identification.manager_name = school_structure.manager_name,
					school_identification.manager_role = school_structure.manager_role,
					school_identification.manager_email = school_structure.manager_email
				where school_identification.inep_id = school_structure.school_inep_id_fk;");

				$this->addColumn("school_identification","offer_or_linked_unity", "INT NOT NULL DEFAULT -1 AFTER private_school_cnpj");
				$this->addColumn("school_identification","inep_head_school", "VARCHAR(8) AFTER offer_or_linked_unity");
				$this->addColumn("school_identification","ies_code", "VARCHAR(14) AFTER inep_head_school");

				echo("Registro 10\n");
				$this->dropColumn("school_structure","manager_cpf");
				$this->dropColumn("school_structure","manager_name");
				$this->dropColumn("school_structure","manager_role");
				$this->dropColumn("school_structure","manager_email");

				$this->addColumn("school_structure","equipments_multifunctional_printer", "SMALLINT(6) AFTER equipments_computer");
				$this->addColumn("school_structure","modalities_professional", "TINYINT(1) AFTER modalities_eja");

				$this->dropColumn("school_structure","stage_regular_education_creche");
				$this->dropColumn("school_structure","stage_regular_education_preschool");
				$this->dropColumn("school_structure","stage_regular_education_fundamental_eigth_years");
				$this->dropColumn("school_structure","stage_regular_education_fundamental_nine_years");
				$this->dropColumn("school_structure","stage_regular_education_high_school");
				$this->dropColumn("school_structure","stage_regular_education_high_school_integrated");
				$this->dropColumn("school_structure","stage_regular_education_high_school_normal_mastership");
				$this->dropColumn("school_structure","stage_regular_education_high_school_preofessional_education");
				$this->dropColumn("school_structure","stage_special_education_creche");
				$this->dropColumn("school_structure","stage_special_education_preschool");
				$this->dropColumn("school_structure","stage_special_education_fundamental_eigth_years");
				$this->dropColumn("school_structure","stage_special_education_fundamental_nine_years");
				$this->dropColumn("school_structure","stage_special_education_high_school");
				$this->dropColumn("school_structure","stage_special_education_high_school_integrated");
				$this->dropColumn("school_structure","stage_special_education_high_school_normal_mastership");
				$this->dropColumn("school_structure","stage_special_education_high_school_professional_education");
				$this->dropColumn("school_structure","stage_special_education_eja_fundamental_education");
				$this->dropColumn("school_structure","stage_special_education_eja_high_school_education");
				$this->dropColumn("school_structure","stage_education_eja_fundamental_education");
				$this->dropColumn("school_structure","stage_education_eja_fundamental_education_projovem");
				$this->dropColumn("school_structure","stage_education_eja_high_school_education");

				echo("Registro 20\n");
				$this->addColumn("classroom","pedagogical_mediation_type", "INT(11) AFTER name");
				$this->dropColumn("classroom","instructor_situation");

				echo("Registro 30\n");
				$this->addColumn("instructor_identification", "filiation", "TINYINT(1) DEFAULT 1 AFTER color_race");
				$this->renameColumn("instructor_identification", "mother_name", "filiation_1");
				$this->addColumn("instructor_identification", "filiation_2", "VARCHAR(100) AFTER filiation_1");

				echo("Registro 40\n");

				echo("Registro 50\n");
				$this->dropColumn("instructor_variable_data","high_education_institution_type_1");
				$this->dropColumn("instructor_variable_data","high_education_institution_type_2");
				$this->dropColumn("instructor_variable_data","high_education_institution_type_3");

				echo("Registro 51\n");

				echo("Registro 60\n");
				$this->dropColumn("student_identification","nis");
				$this->renameColumn("student_identification", "mother_name", "filiation_1");
				$this->renameColumn("student_identification", "father_name", "filiation_2");

				echo("Registro 70\n");
				$this->dropColumn("student_documents_and_address","rg_number_complement");
				$this->dropColumn("student_documents_and_address","document_failure_lack");

				echo("Registro 80\n");
				return true;
		}

		public function safeDown() {
			echo("Registro 00\n");
			echo("Registro 10\n");
			$this->addColumn("school_structure","manager_cpf", "VARCHAR(11) AFTER school_inep_id_fk");
			$this->addColumn("school_structure","manager_name", "VARCHAR(100) AFTER manager_cpf");
			$this->addColumn("school_structure","manager_role", "INT AFTER manager_name");
			$this->addColumn("school_structure","manager_email", "VARCHAR(50) AFTER manager_role");


			$this->execute("
				update school_identification, school_structure
				set school_structure.manager_cpf = school_identification.manager_cpf,
					school_structure.manager_name = school_identification.manager_name,
					school_structure.manager_role = school_identification.manager_role,
					school_structure.manager_email = school_identification.manager_email
				where school_identification.inep_id = school_structure.school_inep_id_fk;");

			$this->dropColumn("school_identification","manager_cpf");
			$this->dropColumn("school_identification","manager_name");
			$this->dropColumn("school_identification","manager_role");
			$this->dropColumn("school_identification","manager_email");
			$this->dropColumn("school_identification","offer_or_linked_unity");
			$this->dropColumn("school_identification","inep_head_school");
			$this->dropColumn("school_identification","ies_code");


			$this->dropColumn("school_structure","equipments_multifunctional_printer");
			$this->dropColumn("school_structure","modalities_professional");

			$this->addColumn("school_structure","stage_regular_education_creche", "TINYINT(1) AFTER modalities_eja");
			$this->addColumn("school_structure","stage_regular_education_preschool", "TINYINT(1) AFTER stage_regular_education_creche");
			$this->addColumn("school_structure","stage_regular_education_fundamental_eigth_years", "TINYINT(1) AFTER stage_regular_education_preschool");
			$this->addColumn("school_structure","stage_regular_education_fundamental_nine_years", "TINYINT(1) AFTER stage_regular_education_fundamental_eigth_years");
			$this->addColumn("school_structure","stage_regular_education_high_school", "TINYINT(1) AFTER stage_regular_education_fundamental_nine_years");
			$this->addColumn("school_structure","stage_regular_education_high_school_integrated", "TINYINT(1) AFTER stage_regular_education_high_school");
			$this->addColumn("school_structure","stage_regular_education_high_school_normal_mastership", "TINYINT(1) AFTER stage_regular_education_high_school_integrated");
			$this->addColumn("school_structure","stage_regular_education_high_school_preofessional_education", "TINYINT(1) AFTER stage_regular_education_high_school_normal_mastership");
			$this->addColumn("school_structure","stage_special_education_creche", "TINYINT(1) AFTER stage_regular_education_high_school_preofessional_education");
			$this->addColumn("school_structure","stage_special_education_preschool", "TINYINT(1) AFTER stage_special_education_creche");
			$this->addColumn("school_structure","stage_special_education_fundamental_eigth_years", "TINYINT(1) AFTER stage_special_education_preschool");
			$this->addColumn("school_structure","stage_special_education_fundamental_nine_years", "TINYINT(1) AFTER stage_special_education_fundamental_eigth_years");
			$this->addColumn("school_structure","stage_special_education_high_school", "TINYINT(1) AFTER stage_special_education_fundamental_nine_years");
			$this->addColumn("school_structure","stage_special_education_high_school_integrated", "TINYINT(1) AFTER stage_special_education_high_school");
			$this->addColumn("school_structure","stage_special_education_high_school_normal_mastership", "TINYINT(1) AFTER stage_special_education_high_school_integrated");
			$this->addColumn("school_structure","stage_special_education_high_school_professional_education", "TINYINT(1) AFTER stage_special_education_high_school_normal_mastership");
			$this->addColumn("school_structure","stage_special_education_eja_fundamental_education", "TINYINT(1) AFTER stage_special_education_high_school_professional_education");
			$this->addColumn("school_structure","stage_special_education_eja_high_school_education", "TINYINT(1) AFTER stage_special_education_eja_fundamental_education");
			$this->addColumn("school_structure","stage_education_eja_fundamental_education", "TINYINT(1) AFTER stage_special_education_eja_high_school_education");
			$this->addColumn("school_structure","stage_education_eja_fundamental_education_projovem", "TINYINT(1) AFTER stage_education_eja_fundamental_education");
			$this->addColumn("school_structure","stage_education_eja_high_school_education", "TINYINT(1) AFTER stage_education_eja_fundamental_education_projovem");


			echo("Registro 20\n");
			$this->dropColumn("classroom","pedagogical_mediation_type");
			$this->addColumn("classroom","instructor_situation", "TINYINT(1) AFTER discipline_others");

			echo("Registro 30\n");
			$this->dropColumn("instructor_identification", "filiation");
			$this->renameColumn("instructor_identification", "filiation_1", "mother_name");
			$this->dropColumn("instructor_identification", "filiation_2");

			echo("Registro 40\n");

			echo("Registro 50\n");
			$this->addColumn("instructor_variable_data", "high_education_institution_type_1", "TINYINT(1) DEFAULT 1 AFTER high_education_final_year_1");
			$this->addColumn("instructor_variable_data", "high_education_institution_type_2", "TINYINT(1) DEFAULT 1 AFTER high_education_final_year_2");
			$this->addColumn("instructor_variable_data", "high_education_institution_type_3", "TINYINT(1) DEFAULT 1 AFTER high_education_final_year_3");

			echo("Registro 51\n");

			echo("Registro 60\n");
			$this->addColumn("student_identification","nis","VARCHAR(11) AFTER name");
			$this->renameColumn("student_identification", "filiation_1", "mother_name");
			$this->renameColumn("student_identification", "filiation_2", "father_name");

			echo("Registro 70\n");
			$this->addColumn("student_documents_and_address","rg_number_complement","VARCHAR(4) AFTER rg_number");
			$this->addColumn("student_documents_and_address","document_failure_lack","SMALLINT(6) AFTER nis");

			echo("Registro 80\n");
			return true;
		}

	}