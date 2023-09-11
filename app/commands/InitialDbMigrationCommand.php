<?php

class InitialDbMigrationCommand extends CConsoleCommand
{
    public function run($args) {
        $schema = '"'.$args[0].'"';
        echo $schema;
        $tables = Yii::app()->db->schema->getTables();

        $addForeignKeys = '';
        $dropForeignKeys = '';

        $result = "public function up()\n{\n";
        foreach ($tables as $table) {
            $compositePrimaryKeyCols = array();

            $engine = Yii::app()->db->createCommand()
            ->select('ENGINE')
            ->from('information_schema.TABLES')
            ->where('TABLE_SCHEMA = :schema AND TABLE_NAME = :table', array(':schema' => $schema, ':table' => $table->name))
            ->queryScalar();

            $charset = Yii::app()->db->createCommand()
            ->select('ccsa.character_set_name')
            ->from('information_schema.TABLES t, information_schema.COLLATION_CHARACTER_SET_APPLICABILITY ccsa')
            ->where('ccsa.collation_name = t.table_collation AND t.TABLE_SCHEMA = :schema AND t.TABLE_NAME = :table', array(':schema' => $schema, ':table' => $table->name))
            ->queryScalar();

            // Create table
            $result .= '    $this->createTable(\'' . $table->name . '\', array(' . "\n";
            foreach ($table->columns as $col) {
                $result .= '        \'' . $col->name . '\' => \'' . $this->getColType($col) . '\',' . "\n";

                if ($col->isPrimaryKey && !$col->autoIncrement) {
                    // Add column to composite primary key array
                    $compositePrimaryKeyCols[] = $col->name;
                }
            }
            $result .= '    ), \'ENGINE=' . $engine . ' DEFAULT CHARSET=' . $charset . '\');' . "\n\n";

            // Add foreign key(s) and create indexes
            foreach ($table->foreignKeys as $col => $fk) {
                // Foreign key naming convention: fk_table_foreignTable_col (max 64 characters)
                $fkName = substr('fk_' . $table->name . '_' . $fk[0] . '_' . $col, 0 , 64);
                $addForeignKeys .= '    $this->addForeignKey(' . "'$fkName', '$table->name', '$col', '$fk[0]', '$fk[1]', 'NO ACTION', 'NO ACTION');\n\n";
                $dropForeignKeys .= '    $this->dropForeignKey(' . "'$fkName', '$table->name');\n\n";

                // Index naming convention: idx_col
                $result .= '    $this->createIndex(\'idx_' . $col . "', '$table->name', '$col', FALSE);\n\n";
            }

            // Add composite primary key for join tables
            if ($compositePrimaryKeyCols) {
                $result .= '    $this->addPrimaryKey(\'pk_' . $table->name . "', '$table->name', '" . implode(',', $compositePrimaryKeyCols) . "');\n\n";

            }

        }
        $result .= $addForeignKeys; // This needs to come after all of the tables have been created.
        $result .= "}\n\n\n";

        $result .= "public function down()\n{\n";
        $result .= $dropForeignKeys; // This needs to come before the tables are dropped.
        foreach ($tables as $table) {
            $result .= '    $this->dropTable(\'' . $table->name . '\');' . "\n";
        }
        $result .= "}\n";


        echo $result;
    }

    public function getColType($col) {
        if ($col->isPrimaryKey && $col->autoIncrement) {
            return "pk";
        }
        $result = $col->dbType;
        if (!$col->allowNull) {
            $result .= ' NOT NULL';
        }
        if ($col->defaultValue != null) {
            $result .= " DEFAULT '{$col->defaultValue}'";
        } elseif ($col->allowNull) {
            $result .= ' DEFAULT NULL';
        }
        return addslashes($result);
    }
}