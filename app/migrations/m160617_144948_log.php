<?php

class m160617_144948_log extends CDbMigration
{
	public function up()
	{
		$this->createTable('log',[
			'id' => 'pk',
			'table' => 'varchar(50) NOT NULL',
			'table_pk' => 'varchar(20) NOT NULL',
			'crud' => 'char(1) NOT NULL',
			'date' => 'datetime NOT NULL DEFAULT CURRENT_TIMESTAMP()',
			'purged_info' => 'varchar(100)',
			'school_fk' => 'varchar(8) NOT NULL',
		],  'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

		$this->addForeignKey('log_school_fkey','log','school_fk','school_identification','inep_id','CASCADE','CASCADE');
	}

	public function down()
	{
		$this->dropTable('log');
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