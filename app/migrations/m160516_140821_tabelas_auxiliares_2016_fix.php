<?php

class m160516_140821_tabelas_auxiliares_2016_fix extends CDbMigration
{
	public function safeUp()
	{
		$this->execute('ALTER TABLE edcenso_ies MODIFY administrative_dependency_name VARCHARACTER(9);');
		$this->execute('UPDATE edcenso_ies SET administrative_dependency_name = "MUNICIPAL" WHERE administrative_dependency_name = "MUNICIPA";');
	}

	public function safeDown()
	{
			echo "m160516_140821_tabelas_auxiliares_2016_fix does not support migration down.\n";
			return false;
	}
}
