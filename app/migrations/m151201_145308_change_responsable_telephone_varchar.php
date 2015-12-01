<?php

class m151201_145308_change_responsable_telephone_varchar extends CDbMigration
{
	public function up()
	{
		$this->alterColumn('student_identification','responsable_telephone', 'VARCHAR(11) NULL');
	}

	public function down()
	{
		echo "m151201_145308_change_responsable_telephone_varchar does not support migration down.\n";
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