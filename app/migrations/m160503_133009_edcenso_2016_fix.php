<?php

class m160503_133009_edcenso_2016_fix extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('school_identification', 'regulation_temp', 'SMALLINT(6) NULL AFTER `private_school_cnpj`');
		$this->execute('UPDATE school_identification SET regulation_temp = regulation WHERE inep_id = inep_id;');
		$this->dropColumn('school_identification', 'regulation');
		$this->renameColumn('school_identification', 'regulation_temp','regulation');
		//Alternative
		//$this->execute('ALTER TABLE school_identification CHANGE COLUMN 'regulation' 'regulation' SMALLINT(6) NULL AFTER `private_school_cnpj`;');
		//return false;
	}

	public function safeDown()
	{
		echo "m160503_133009_edcenso_2016_fix does not support migration down.\n";
		return false;
	}
}
