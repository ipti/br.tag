<?php

class m160912_142340_grades_boquim extends CDbMigration {

    public function safeUp() {


        $this->createTable('work_by_exam',[
            'id' => 'pk',
            'classroom_fk' => 'int(11) NOT NULL',
            'exam' => 'tinyint(1) NOT NULL',
            'school_days' => 'smallint(6) NULL',
            'workload' => 'smallint(6) NULL',
        ],  'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $this->execute("alter table work_by_exam add key `class_classroom_fkey` (`classroom_fk`);");

        $this->addForeignKey('class_classroom_fk','work_by_exam','classroom_fk','classroom','id','NO ACTION','NO ACTION');

        $this->execute("alter table work_by_exam add unique (classroom_fk, exam);");

        $this->createTable('frequency_by_exam',[
            'id' => 'pk',
            'enrollment_fk' => 'int(11) NOT NULL',
            'exam' => 'tinyint(1) NOT NULL',
            'absences' => 'smallint(6) NULL',
        ],  'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $this->execute("alter table frequency_by_exam add key `fk_grade_index` (`enrollment_fk`);");

        $this->addForeignKey('grade_fk','frequency_by_exam','enrollment_fk','student_enrollment','id','NO ACTION','NO ACTION');

        $this->execute("alter table frequency_by_exam add unique (enrollment_fk, exam);");

        $this->createTable('work_by_discipline',[
            'id' => 'pk',
            'classroom_fk' => 'int(11) NOT NULL',
            'discipline_fk' => 'int(11) NOT NULL',
            'school_days' => 'smallint(6) NULL',
        ],  'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $this->execute("alter table work_by_discipline add key `class_classroom_idx` (`classroom_fk`);");
        $this->execute("alter table work_by_discipline add key `class_discipline_idx` (`discipline_fk`);");

        $this->addForeignKey('class_discipline_fk','work_by_discipline','discipline_fk','edcenso_discipline','id','NO ACTION','NO ACTION');
        $this->addForeignKey('class_classroom_fk2','work_by_discipline','classroom_fk','classroom','id','NO ACTION','NO ACTION');

        $this->execute("alter table work_by_discipline add unique (classroom_fk, discipline_fk);");


        $this->createTable('frequency_and_mean_by_discipline',[
            'id' => 'pk',
            'enrollment_fk' => 'int(11) NOT NULL',
            'discipline_fk' => 'int(11) NOT NULL',
            'annual_average' => 'float unsigned DEFAULT NULL',
            'final_average' => 'float unsigned DEFAULT NULL',
            'absences' => 'smallint(6) NULL',
            'frequency' => 'float unsigned DEFAULT NULL',
        ],  'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $this->execute("alter table frequency_and_mean_by_discipline add key `index_fk_grade` (`enrollment_fk`);");
        $this->execute("alter table frequency_and_mean_by_discipline add key `index_class_discipline` (`discipline_fk`);");

        $this->addForeignKey('fkey_class_discipline','frequency_and_mean_by_discipline','discipline_fk','edcenso_discipline','id','NO ACTION','NO ACTION');
        $this->addForeignKey('fk_grade','frequency_and_mean_by_discipline','enrollment_fk','student_enrollment','id','NO ACTION','NO ACTION');

        $this->execute("alter table frequency_and_mean_by_discipline add unique (enrollment_fk, discipline_fk);");

    }

}


