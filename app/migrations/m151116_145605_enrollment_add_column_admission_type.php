<?php

class m151116_145605_enrollment_add_column_admission_type extends CDbMigration
{
	public function up()
	{
		$this->addColumn('student_enrollment', 'admission_type', 'TINYINT(1) NULL AFTER `previous_stage_situation`');
	}

	public function down()
	{
		$this->dropColumn('student_enrollment', 'admission_type');
	}

}