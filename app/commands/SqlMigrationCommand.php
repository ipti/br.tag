<?php

/**
 * Console command to execute SQL files across multiple TAG databases.
 * 
 * Usage:
 *   php yiic sqlmigration run /path/to/file.sql
 *   php yiic sqlmigration run /path/to/file.sql --dry-run
 * 
 * This command will:
 * 1. Discover all databases matching the pattern *.tag.ong.br
 * 2. Execute the specified SQL file against each database
 * 3. Report success/failure for each database
 */
class SqlMigrationCommand extends CConsoleCommand
{
    /**
     * @var string Database host
     */
    private $host;
    
    /**
     * @var string Database username
     */
    private $username;
    
    /**
     * @var string Database password
     */
    private $password;

    /**
     * Initialize database connection parameters from main config
     */
    public function init()
    {
        parent::init();
        
        // Extract connection details from the main database configuration
        $dbConfig = Yii::app()->db;
        
        // Parse DSN to get host
        if (preg_match('/host=([^;]+)/', $dbConfig->connectionString, $matches)) {
            $this->host = $matches[1];
        }
        
        $this->username = $dbConfig->username;
        $this->password = $dbConfig->password;
    }

    /**
     * Execute SQL file across all TAG databases
     * 
     * @param array $args Command arguments [0] = SQL file path
     */
    public function actionRun($args)
    {
        if (empty($args[0])) {
            echo "Error: Please provide the SQL file path.\n";
            echo "Usage: php yiic sqlmigration run /path/to/file.sql [--dry-run]\n";
            return 1;
        }

        $sqlFile = $args[0];
        $dryRun = in_array('--dry-run', $args);
        
        $dbFilter = null;
        foreach ($args as $arg) {
            if (strpos($arg, '--db-filter=') === 0) {
                $dbFilter = substr($arg, 12);
            }
        }

        // Validate SQL file exists
        if (!file_exists($sqlFile)) {
            echo "Error: SQL file not found: $sqlFile\n";
            return 1;
        }

        // Read SQL content
        $sqlContent = file_get_contents($sqlFile);
        if ($sqlContent === false) {
            echo "Error: Could not read SQL file: $sqlFile\n";
            return 1;
        }

        echo "=================================================\n";
        echo "SQL Migration Tool\n";
        echo "=================================================\n";
        echo "SQL File: $sqlFile\n";
        echo "Mode: " . ($dryRun ? "DRY RUN" : "EXECUTION") . "\n";
        if ($dbFilter) echo "Filter: $dbFilter\n";
        echo "=================================================\n\n";

        // Get list of databases matching pattern
        $databases = $this->getTagDatabases();
        
        if ($dbFilter) {
            $databases = array_filter($databases, function($db) use ($dbFilter) {
                return strpos($db, $dbFilter) !== false;
            });
        }
        
        if (empty($databases)) {
            echo "No databases matching pattern found.\n";
            return 1;
        }

        echo "Found " . count($databases) . " database(s) to process\n\n";

        $successCount = 0;
        $failureCount = 0;
        $results = [];

        foreach ($databases as $database) {
            echo "Processing: $database ... ";
            
            if ($dryRun) {
                echo "[DRY RUN - SKIPPED]\n";
                $results[] = ['database' => $database, 'status' => 'skipped'];
                continue;
            }

            try {
                $this->executeSqlOnDatabase($database, $sqlContent);
                echo "[SUCCESS]\n";
                $successCount++;
                $results[] = ['database' => $database, 'status' => 'success'];
            } catch (Exception $e) {
                echo "[FAILED]\n";
                echo "  Error: " . $e->getMessage() . "\n";
                $failureCount++;
                $results[] = ['database' => $database, 'status' => 'failed', 'error' => $e->getMessage()];
            }
        }

        // Summary
        echo "\n=================================================\n";
        echo "Migration Summary\n";
        echo "=================================================\n";
        echo "Total databases: " . count($databases) . "\n";
        
        if (!$dryRun) {
            echo "Successful: $successCount\n";
            echo "Failed: $failureCount\n";
            
            if ($failureCount > 0) {
                echo "\nFailed databases:\n";
                foreach ($results as $result) {
                    if ($result['status'] === 'failed') {
                        echo "  - {$result['database']}: {$result['error']}\n";
                    }
                }
            }
        } else {
            echo "Mode: DRY RUN (no changes made)\n";
        }
        echo "=================================================\n";

        return $failureCount > 0 ? 1 : 0;
    }

    /**
     * Get list of databases matching the pattern *.tag.ong.br
     * 
     * @return array List of database names
     */
    private function getTagDatabases()
    {
        try {
            $connection = new PDO(
                "mysql:host={$this->host}",
                $this->username,
                $this->password
            );
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $connection->query("SHOW DATABASES LIKE '%.tag.ong.br'");
            $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);

            return $databases;
        } catch (PDOException $e) {
            echo "Error connecting to database server: " . $e->getMessage() . "\n";
            return [];
        }
    }

    /**
     * Execute SQL content on a specific database
     * 
     * @param string $database Database name
     * @param string $sqlContent SQL content to execute
     * @throws Exception if execution fails
     */
    private function executeSqlOnDatabase($database, $sqlContent)
    {
        $connection = new PDO(
            "mysql:host={$this->host};dbname={$database}",
            $this->username,
            $this->password
        );
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Split SQL by semicolon, but ignore semicolons inside single or double quotes
        // This regex splits by ; only if it's not followed by an odd number of quotes
        $statements = preg_split('/;(?=(?:[^\'"]*([\'"])[^\'"]*\1)*[^\'"]*$)/', $sqlContent);
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                $connection->exec($statement);
            }
        }
    }

    /**
     * Display help information
     */
    public function getHelp()
    {
        return <<<EOD
USAGE
  yiic sqlmigration run <sql-file> [--dry-run] [--db-filter=<name>]

DESCRIPTION
  This command executes a SQL file across databases matching the pattern '*.tag.ong.br'.
  
PARAMETERS
  * sql-file: required, path to the SQL file to execute
  * --dry-run: optional, list databases without executing SQL
  * --db-filter: optional, process only databases containing this string

EXAMPLES
  * Execute migration on all TAG databases:
    yiic sqlmigration run app/migrations/inventory_complete.sql
    
  * Target only the training database:
    yiic sqlmigration run app/migrations/inventory_complete.sql --db-filter=treinamento

  * Preview which databases would be affected (dry run):
    yiic sqlmigration run app/migrations/inventory_complete.sql --dry-run

EOD;
    }
}
