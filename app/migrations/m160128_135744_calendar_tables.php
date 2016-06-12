<?php

/**
 * Class m160128_135744_calendar_tables
 *
 * $yiic migrate up --migrationPath=application.modules.calendar.migrations
 *
 */
class m160128_135744_calendar_tables extends CDbMigration{

	public function up() {
		$this->createTable('calendar',[
			'id' => 'pk',
            'school_year' => 'varchar(10) NOT NULL',
			'start_date' => 'date NOT NULL',
            'end_date' => 'date NOT NULL',
			'actual' => 'int NOT NULL',
            'school_fk' => 'varchar(8) COLLATE utf8_unicode_ci NOT NULL',
		],  'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $this->createTable('calendar_event_type',[
            'id' => 'pk',
            'name' => 'varchar(50) NOT NULL COLLATE utf8_unicode_ci NOT NULL',
            'icon' => 'varchar(50) NOT NULL COLLATE utf8_unicode_ci',
            'color' => 'varchar(50) NOT NULL COLLATE utf8_unicode_ci',
            'copyable' => 'tinyint(1) NOT NULL',
        ],  'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

		$this->createTable('calendar_event',[
			'id' => 'pk',
            'name' => 'varchar(50) NOT NULL COLLATE utf8_unicode_ci NOT NULL',
            'start_date' => 'datetime NOT NULL',
            'end_date' => 'datetime NOT NULL',
            'calendar_fk' => 'int NOT NULL',
			'calendar_event_type_fk' => 'int NOT NULL',
            'copyable' => 'tinyint(1) NOT NULL',
		],  'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $this->addColumn('classroom', 'calendar_fk','int');

		//school_identification -> calendar
		$this->addForeignKey('school_calendar','calendar','school_fk','school_identification','inep_id','CASCADE','CASCADE');

        //calendar -> calendar_event
        $this->addForeignKey('calendar_events','calendar_event','calendar_fk','calendar','id','CASCADE','CASCADE');
        //calendar_event_type -> calendar_event
        $this->addForeignKey('event_type','calendar_event','calendar_event_type_fk','calendar_event_type','id','CASCADE','CASCADE');

        //classroom -> calendar
        $this->addForeignKey('classroom_calendar','classroom','calendar_fk','calendar','id','CASCADE','CASCADE');

        $this->insert('calendar_event_type', ['id'=>101, 'name'=>'Holyday', 'icon'=>'fa-square', 'color'=>'red', 'copyable'=>1]);
        $this->insert('calendar_event_type', ['id'=>102, 'name'=>'Vacation', 'icon'=>'fa-square', 'color'=>'green', 'copyable'=>0]);
        $this->insert('calendar_event_type', ['id'=>103, 'name'=>'Commemoration', 'icon'=>'fa-star', 'color'=>'yellow', 'copyable'=>0]);
        $this->insert('calendar_event_type', ['id'=>201, 'name'=>'Admnistrative Meeting', 'icon'=>'fa-group', 'color'=>'green', 'copyable'=>0]);
        $this->insert('calendar_event_type', ['id'=>202, 'name'=>'Parents Meeting', 'icon'=>'fa-group', 'color'=>'yellow', 'copyable'=>0]);
        $this->insert('calendar_event_type', ['id'=>203, 'name'=>'Planning', 'icon'=>'fa-group', 'color'=>'blue', 'copyable'=>0]);
        $this->insert('calendar_event_type', ['id'=>301, 'name'=>'Saturday School', 'icon'=>'fa-square', 'color'=>'black', 'copyable'=>0]);
        $this->insert('calendar_event_type', ['id'=>302, 'name'=>'Replacement', 'icon'=>'fa-square-o', 'color'=>'black', 'copyable'=>0]);
        $this->insert('calendar_event_type', ['id'=>401, 'name'=>'Exam', 'icon'=>'fa-file-text', 'color'=>'pink', 'copyable'=>0]);
        $this->insert('calendar_event_type', ['id'=>402, 'name'=>'Semester Recovery', 'icon'=>'fa-file-text', 'color'=>'yellow', 'copyable'=>0]);
        $this->insert('calendar_event_type', ['id'=>403, 'name'=>'Final Exam', 'icon'=>'fa-file-text', 'color'=>'purple', 'copyable'=>0]);
        $this->insert('calendar_event_type', ['id'=>501, 'name'=>'Continuing Education', 'icon'=>'fa-square', 'color'=>'purple', 'copyable'=>0]);

	}

	public function down() {
        $this->dropForeignKey('school_calendar','calendar');
        $this->dropForeignKey('calendar_events','calendar_event');
        $this->dropForeignKey('event_type','calendar_event');
        $this->dropForeignKey('classroom_calendar','classroom');

		$this->dropTable('calendar_event');
		$this->dropTable('calendar_event_type');
		$this->dropTable('calendar');
        $this->dropColumn('classroom', 'calendar_fk');
	}

}