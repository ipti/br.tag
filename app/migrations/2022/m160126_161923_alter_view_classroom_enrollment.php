<?php

class m160126_161923_alter_view_classroom_enrollment extends CDbMigration
{
	public function up()
	{
		$this->execute(
			"ALTER VIEW `classroom_enrollment` AS
			select
				`s`.`id` AS `enrollment`,
				`s`.`name` AS `name`,
				if((`s`.`sex` = 1), 'M', 'F') AS `sex`,
				`s`.`birthday` AS `birthday`,
				`se`.`current_stage_situation` AS `situation`,
				`se`.`admission_type` AS `admission_type`,
				`en`.`acronym` AS `nation`,
				`ec`.`name` AS `city`,
				`sd`.`address` AS `address`,
				`sd`.`number` AS `number`,
				`sd`.`complement` AS `complement`,
				`sd`.`neighborhood` AS `neighborhood`,
				`sd`.`civil_certification` AS `cc`,
				`sd`.`civil_register_enrollment_number` AS `cc_new`,
				`sd`.`civil_certification_term_number` AS `cc_number`,
				`sd`.`civil_certification_book` AS `cc_book`,
				`sd`.`civil_certification_sheet` AS `cc_sheet`,
				concat(`s`.`mother_name`,
						'<br>',
						`s`.`father_name`) AS `parents`,
				`s`.`deficiency` AS `deficiency`,
				`c`.`id` AS `classroom_id`,
				`c`.`school_year` AS `year`
			from
				(((((`student_identification` `s`
				join `student_documents_and_address` `sd` ON ((`s`.`id` = `sd`.`id`)))
				left join `edcenso_nation` `en` ON ((`s`.`edcenso_nation_fk` = `en`.`id`)))
				left join `edcenso_city` `ec` ON ((`s`.`edcenso_city_fk` = `ec`.`id`)))
				join `student_enrollment` `se` ON ((`s`.`id` = `se`.`student_fk`)))
				join `classroom` `c` ON ((`se`.`classroom_fk` = `c`.`id`)));"
		);

	}

	public function down()
	{
		echo "m160126_161923_alter_view_classroom_enrollment does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}