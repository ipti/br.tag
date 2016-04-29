<?php

class m160428_123333_curricularmatrix extends CDbMigration
{
	public function safeUp()
	{
		$this->createTable('curricular_matrix',[
			'id' => 'pk',
			'stage_fk' => 'int NOT NULL',
			'discipline_fk' => 'int NOT NULL',
			'school_fk' => 'varchar(8) NOT NULL',
			'workload' => 'int NOT NULL',
			'credits' => 'int NOT NULL',
		],  'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

		$this->addForeignKey('stage_fkey','curricular_matrix','stage_fk','edcenso_stage_vs_modality','id','CASCADE','CASCADE');
		$this->addForeignKey('discipline_fk','curricular_matrix','discipline_fk','edcenso_discipline','id','CASCADE','CASCADE');
		$this->addForeignKey('school_fkey','curricular_matrix','school_fk','school_identification','inep_id','CASCADE','CASCADE');
	}

	public function safeDown()
	{
		$this->dropTable('curricular_matrix');
	}


}