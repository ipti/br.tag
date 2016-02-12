<?php

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