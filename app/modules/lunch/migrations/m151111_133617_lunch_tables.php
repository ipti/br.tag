<?php

/**
 * Class m151111_133617_lunch_tables
 *
 * $yiic migrate up --migrationPath=application.modules.lunch.migrations
 *
 */
class m151111_133617_lunch_tables extends CDbMigration {

    public function up() {
        $this->createTable('lunch_received',[
            'id' => 'pk',
            'date' => 'datetime NULL DEFAULT CURRENT_TIMESTAMP()',
            'inventory_fk' => 'int NOT NULL'
        ],  'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $this->createTable('lunch_spent',[
            'id' => 'pk',
            'date' => 'datetime NULL DEFAULT CURRENT_TIMESTAMP()',
            'motivation' => 'varchar(100) NOT NULL',
            'inventory_fk' => 'int NOT NULL'
        ],  'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $this->createTable('lunch_inventory',[
            'id' => 'pk',
            'school_fk' => 'varchar(8) COLLATE utf8_unicode_ci NOT NULL',
            'item_fk' => 'int NOT NULL',
            'amount' => 'float NOT NULL'
        ],  'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $this->createTable('lunch_item',[
            'id' => 'pk',
            'name' => 'varchar(45) NOT NULL',
            'description' => 'varchar(100)',
            'unity_fk' => 'int NOT NULL',
            'measure' => 'float NOT NULL'
        ],  'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $this->createTable('lunch_unity',[
            'id' => 'pk',
            'name' => 'varchar(45) NOT NULL',
            'acronym' => 'varchar(10) NOT NULL'
        ],  'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $this->createTable('lunch_portion',[
            'id' => 'pk',
            'item_fk' => 'int NOT NULL',
            'amount' => 'int NOT NULL',
            'unity_fk' => 'int NOT NULL',
            'measure' => 'float NOT NULL'
        ],  'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $this->createTable('lunch_meal_portion',[
            'id' => 'pk',
            'meal_fk' => 'int NOT NULL',
            'portion_fk' => 'int NOT NULL',
            'amount' => 'float NOT NULL'
        ],  'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $this->createTable('lunch_meal',[
            'id' => 'pk',
            'restrictions' => 'varchar(100) NOT NULL'
        ],  'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $this->createTable('lunch_menu_meal',[
            'id' => 'pk',
            'menu_fk' => 'int NOT NULL',
            'meal_fk' => 'int NOT NULL',
            'amount' => 'float NOT NULL'
        ],  'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $this->createTable('lunch_menu',[
            'id' => 'pk',
            'name' => 'varchar(45) NOT NULL',
            'date' => 'datetime NULL DEFAULT CURRENT_TIMESTAMP()',
            'school_fk' => 'varchar(8) COLLATE utf8_unicode_ci NOT NULL',
        ],  'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci');

        //lunch_received -> lunch_inventory
        $this->addForeignKey('received_inventories','lunch_received','inventory_fk','lunch_inventory','id','CASCADE','CASCADE');

        //lunch_spent -> lunch_inventory
        $this->addForeignKey('spent_inventories','lunch_spent','inventory_fk','lunch_inventory','id','CASCADE','CASCADE');

        //lunch_inventory -> school_identification
        $this->addForeignKey('school_inventories','lunch_inventory','school_fk','school_identification','inep_id','CASCADE','CASCADE');

        //lunch_meal_portion -> lunch_meal
        $this->addForeignKey('portions_meals','lunch_meal_portion','meal_fk','lunch_meal','id','CASCADE','CASCADE');
        //lunch_meal_portion -> lunch_portion
        $this->addForeignKey('meals_portions','lunch_meal_portion','portion_fk','lunch_portion','id','CASCADE','CASCADE');

        //lunch_portion -> lunch_item
        $this->addForeignKey('portion_item','lunch_portion','item_fk','lunch_item','id','CASCADE','CASCADE');
        //lunch_portion -> lunch_unity
        $this->addForeignKey('portion_unity','lunch_portion','unity_fk','lunch_unity','id','CASCADE','CASCADE');

        //lunch_item -> lunch_unity
        $this->addForeignKey('item_unity','lunch_item','unity_fk','lunch_unity','id','CASCADE','CASCADE');

        //lunch_menu_meal -> lunch_menu
        $this->addForeignKey('meals_menus','lunch_menu_meal','menu_fk','lunch_menu','id','CASCADE','CASCADE');
        //lunch_menu_meal -> lunch_meal
        $this->addForeignKey('menus_meals','lunch_menu_meal','meal_fk','lunch_meal','id','CASCADE','CASCADE');

        //lunch_menu -> school_identification
        $this->addForeignKey('school_menus','lunch_menu','school_fk','school_identification','inep_id','CASCADE','CASCADE');


    }

    public function down() {
        $this->dropTable('lunch_received');
        $this->dropTable('lunch_spent');
        $this->dropTable('lunch_inventory');
        $this->dropTable('lunch_meal_portion');
        $this->dropTable('lunch_portion');
        $this->dropTable('lunch_item');
        $this->dropTable('lunch_unity');
        $this->dropTable('lunch_menu_meal');
        $this->dropTable('lunch_meal');
        $this->dropTable('lunch_menu');
    }

    /*
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp() {
    }

    public function safeDown() {
    }
    */
}