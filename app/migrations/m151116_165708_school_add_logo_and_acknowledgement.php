<?php

class m151116_165708_school_add_logo_and_acknowledgement extends CDbMigration
{
	public function up()
	{
		$this->addColumn('school_identification', 'logo_file_name', 'VARCHAR(100) NULL AFTER `regulation`');
		$this->addColumn('school_identification', 'logo_file_type', 'VARCHAR(50) NULL AFTER `logo_file_name`');
		$this->addColumn('school_identification', 'logo_file_content', 'MEDIUMBLOB NULL AFTER `logo_file_type`');
		$this->addColumn('school_identification', 'act_of_acknowledgement', 'TEXT NULL AFTER `logo_file_content`');
	}

	public function down()
	{
		$this->dropColumn('school_identification', 'logo_file_name');
		$this->dropColumn('school_identification', 'logo_file_type');
		$this->dropColumn('school_identification', 'logo_file_content');
		$this->dropColumn('school_identification', 'act_of_acknowledgement');
	}
}