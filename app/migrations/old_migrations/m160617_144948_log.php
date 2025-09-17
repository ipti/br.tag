<?php

class m160617_144948_log extends CDbMigration
{
	public function up()
	{
		$this->createTable('log',[
			'id' => 'pk',
			'reference' => 'varchar(50) NOT NULL',
			'reference_ids' => 'varchar(20) NOT NULL',
			'crud' => 'char(1) NOT NULL',
			'date' => 'datetime NOT NULL DEFAULT CURRENT_TIMESTAMP()',
			'additional_info' => 'text',
			'school_fk' => 'varchar(8) NOT NULL',
			'user_fk' => 'integer NOT NULL',
		],  'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

		$this->addForeignKey('log_school_fkey','log','school_fk','school_identification','inep_id','CASCADE','CASCADE');
		$this->addForeignKey('log_user_fkey','log','user_fk','users','id','CASCADE','CASCADE');
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