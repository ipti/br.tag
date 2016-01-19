<?php

class m160119_113351_add_exams_dates extends CDbMigration{
	public function up(){
		for($i=1; $i<=4;$i++) $this->addColumn('school_configuration', 'exam'.$i, 'timestamp NULL');
		for($i=1; $i<=4;$i++) $this->addColumn('school_configuration', 'recovery'.$i, 'timestamp NULL');
		$this->addColumn('school_configuration', 'recovery_final', 'timestamp NULL');
	}

	public function down(){
		for($i=1; $i<=4;$i++) $this->dropColumn('school_configuration', 'exam'.$i);
		for($i=1; $i<=4;$i++) $this->dropColumn('school_configuration', 'recovery'.$i);
		$this->dropColumn('school_configuration', 'recovery_final');
	}

}