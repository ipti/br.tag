<?php

class m160509_132203_edcenso_discipline extends CDbMigration
{
	public function up()
	{
			$this->insert('edcenso_discipline',array('id' => 10002,'name' => 'Linguagem oral e escrita'));
			$this->insert('edcenso_discipline',array('id' => 10003,'name' => 'Natureza e sociedade'));
			$this->insert('edcenso_discipline',array('id' => 10004,'name' => 'Movimento'));
			$this->insert('edcenso_discipline',array('id' => 10005,'name' => 'Musica'));
			$this->insert('edcenso_discipline',array('id' => 10006,'name' => 'Artes visuais'));
	}

	public function down()
	{
		$this->delete('edcenso_discipline','id = :id' , [":id" => 10002]);
		$this->delete('edcenso_discipline','id = :id' , [":id" => 10003]);
		$this->delete('edcenso_discipline','id = :id' , [":id" => 10004]);
		$this->delete('edcenso_discipline','id = :id' , [":id" => 10005]);
		$this->delete('edcenso_discipline','id = :id' , [":id" => 10006]);
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