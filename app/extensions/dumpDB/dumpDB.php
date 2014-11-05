<?php

/**
 * Creates DB dump.
 * 
 * @version 0.2
 * @author Rodrigo Zadra Armond <rodzadra@gmail.com>
 * 
 * 
 * Usage:
 * <pre>
 *      Yii::import('ext.dumpDB.dumpDB');
 *           $dumper = new dumpDB();
 *           echo $dumper->getDump();
 * </pre>
 * 
 * 
 * Saving the dump to a file:
 * <pre>
 *      Yii::import('ext.dumpDB.dumpDB');
 *          $dumper = new dumpDB();
 *          $bk_file = 'FILE_NAME-'.date('YmdHis').'.sql';
 *          $fh = fopen($bk_file, 'w') or die("can't open file");
 *          fwrite($fh, $dumper->getDump(FALSE));
 *          fclose($fh);
 * </pre>
 * 
 * Dumping external DB:
 * <pre>
 *      Yii::import('ext.dumpDB.dumpDB');
 *          $dumper = new dumpDB('mysql:host=HOTS_NAME_OR_IP;dbname=DATABASE_NAME','USERNAME','PASSWORD');
 *          $dumper = new dumpDB();
 *          $dumper->setRemoveViewDefinerSecurity(TRUE);
 *          $the_dumper = $dumper->getDump();
 *          echo $meu_dumper;
 * </pre>
 * 
 * 
 */
class dumpDB
{

        private $constraints;
        private $db=NULL;
        private $db_connected=NULL;
        
        //max size of the query in megabytes - this value will be multiplied by 1024.
        private $max_query_size = 50; 
        
        //this can be useful when a view is defined by/for a different user or 
        //that doesn't exists on the DB which this SQL will be imported
        private $removeViewDefiner = FALSE;

        /**
         * construct class
         * 
         * @param string $dsn      - 'mysql:host=[REMOTE_HOST];dbname=[DATABASE]'
         * @param string $username - username
         * @param string $password - password
         */
        public function __construct($dsn=NULL, $username=NULL, $password=NULL){
            if(is_null($dsn) || is_null($username) || is_null($password)){
                $this->db = Yii::app()->db;
            } else {
                $this->db =new CDbConnection($dsn,$username,$password);
                $this->db->active = TRUE;
            }
            
            $this->max_query_size = $this->max_query_size *1024;

            $this->db_connected = $this->db->createCommand('SELECT DATABASE() AS db')->queryAll()[0]['db'];
            
        }
        
        
        /**
         * set/unset the parameter that remove the DEFINER option (USER) from 
         * generated "create view" params
         * 
         * @param bool $b - default FALSE
         * @return bool - the value of $this->removeViewdefiner
         */
        public function setRemoveViewDefinerSecurity($b=FALSE){
                $this->removeViewDefiner = ($b === true)?$b:FALSE;
                return $this->removeViewDefiner;
        }

        
        /**
         * set the max kilobyte the generated query can have
         * 
         * @param integet $size - default 50(Mb)
         * @return void
         */
        public function setMaxQuerySize($size=50){
            if(!isset($size))
                return;
            $this->max_query_size = (int)$size *1024;
            return;
        }
        
        /**
         * return the max size (Kb) the generated query can have
         * 
         * 
         * @return int
         */
        public function getMaxQuerySize(){
            return (int)$this->max_query_size."Mb";
        }

        /**
         * Dump all tables
         * @param boolean $download - if the generated data is to be sent to browser 
         * @return file|strings 
         */
	public function getDump($download = TRUE)
	{
		ob_start();
		foreach($this->getTables() as $val){
			$this->dumpTable($val['name']);
                }
		$result = $this->setHeader();
                $result.= ob_get_contents();
                $result.= $this->getViews();
                $result.= $this->getConstraints();
                $result.= $this->setFooter();
                ob_end_clean();
                if($download){
                    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                    header("Cache-Control: no-cache");
                    header("Pragma: no-cache");
                    header("Content-type:application/sql");
                    header("Content-Disposition:attachment;filename=downloaded.sql");
                } 
                return $result;
	}

        /**
         * Generate constraints to all tables
         * @return string 
         */
        private function getConstraints()
        {
            $sql = "--\r\n-- Constraints for dumped tables\r\n--".PHP_EOL.PHP_EOL;
            $first = TRUE;
            foreach ($this->constraints as $key => $value) {
                if($first && count($value[0]) > 0){
                    $sql  .= "--\r\n-- Constraints for table $key\r\n--".PHP_EOL.PHP_EOL;
                    $sql .= "ALTER TABLE $key".PHP_EOL;
                }
                if(count($value[0]) > 0){
                    for($i=0; $i<count($value[0]);$i++){
                        if(strpos($value[0][$i], 'CONSTRAINT') === FALSE)
                                $sql .= preg_replace('/(FOREIGN[\s]+KEY)/', "  ADD $1", $value[0][$i]);
                        else
                                $sql .= preg_replace('/(CONSTRAINT)/', "  ADD $1", $value[0][$i]);
                        if($i==count($value[0])-1)
                            $sql .= ";".PHP_EOL;
                        if($i<count($value[0])-1)
                            $sql .=PHP_EOL;
                    }
                }
            }
            
            return $sql;            
        }

                
        /**
         * Set sql file header
         * @return string 
         */
        private function setHeader()
        {
            $header = PHP_EOL."--\n-- ".sprintf("Host info: %s", $this->db->getConnectionStatus())."\n--";
            $header.= PHP_EOL."--\n-- Date: ".date('Y-m-d H:i:s')."\n--".PHP_EOL.PHP_EOL;
            $header.= PHP_EOL."--\n-- Disable foreign key checks, autocommit and start a transaction\n--".PHP_EOL.PHP_EOL;
            $header.="SET FOREIGN_KEY_CHECKS=0;".PHP_EOL;
            $header.="SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";".PHP_EOL;
            $header.="SET AUTOCOMMIT=0;".PHP_EOL;
            $header.="START TRANSACTION;".PHP_EOL.PHP_EOL;
            $header.="/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;".PHP_EOL;
            $header.="/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;".PHP_EOL;
            $header.="/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;".PHP_EOL;
            $header.="/*!40101 SET NAMES utf8 */;".PHP_EOL;
            
            return $header;
        }
        
        
        /**
         * Set sql file footer
         * @return string 
         */
        private function setFooter()
        {
            $footer =PHP_EOL."SET FOREIGN_KEY_CHECKS=1;".PHP_EOL;
            $footer.="COMMIT;".PHP_EOL.PHP_EOL;
            $footer.="/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;".PHP_EOL;
            $footer.="/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;".PHP_EOL;
            $footer.="/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;".PHP_EOL;
            
            return $footer;
        }

                
	/**
	 * Create table dump
	 * @param $tableName
	 * @return mixed
	 */
	private function dumpTable($tableName)
	{
                
		$pdo = $this->db->getPdoInstance();
                
		echo PHP_EOL."--\n-- Table structure for table `$tableName`\n--".PHP_EOL;
		echo PHP_EOL.'DROP TABLE IF EXISTS '.$this->db->quoteTableName($tableName).';'.PHP_EOL.PHP_EOL;

		$q = $this->db->createCommand('SHOW CREATE TABLE '.$this->db->quoteTableName($tableName).';')->queryRow();
                
                if(!isset($q['Create Table'])) return;

                $create_query = $q['Create Table'];
                                
                $pattern = '/CONSTRAINT.*|FOREIGN[\s]+KEY/';
                
                // constraints to $tablename
                preg_match_all($pattern, $create_query, $this->constraints[$this->db->quoteTableName($tableName)]);
                
                $create_query = preg_replace('/CREATE TABLE/', 'CREATE TABLE IF NOT EXISTS', $create_query);
                
                $create_query = explode("\n", $create_query);                
                
                $create_query_size = count($create_query);

                
                for($i=0;$i<$create_query_size-1;$i++){
                    if(preg_match($pattern, $create_query[$i + 1])){
                        echo preg_replace ('/\,$/', '', $create_query[$i]).PHP_EOL;
                        break;
                    }
                    else
                        echo $create_query[$i].PHP_EOL;
                }
                                
                echo trim($create_query[$create_query_size-1]).";".PHP_EOL;

		$rows = $this->db->createCommand('SELECT * FROM '.$this->db->quoteTableName($tableName).';')->queryAll();
                    
		if(empty($rows)){
                        echo PHP_EOL;
                        echo "-- --------------------------------------------------------".PHP_EOL;
			return;
                }
    
		echo PHP_EOL."--\n-- Dumping data for table `$tableName`\n--".PHP_EOL.PHP_EOL;

		$attrs = array_map(array($this->db, 'quoteColumnName'), array_keys($rows[0]));
		
                $insert_head = 'INSERT INTO '.$this->db->quoteTableName($tableName).''." (". implode(', ', $attrs). ') VALUES';
                echo $insert_head.PHP_EOL;
		
                $i=0;
                $query_size = 0;
		$rowsCount = count($rows);
                
		foreach($rows AS $row)
		{
			// Process row
			foreach($row AS $key => $value)
			{
				if($value === null)
					$row[$key] = 'NULL';
				else
					$row[$key] = $pdo->quote($value);
			}

                        $imploded_row = " (". implode(', ', $row). ')';
                        echo $imploded_row;
                        
                        //$query_size +=mb_strlen($imploded_row, 'UTF-8');
                        $query_size +=strlen($imploded_row);
                        
			if($i<$rowsCount-1){
                            if($query_size <=$this->max_query_size){
				echo ',';
                            } else {
				echo ';'.PHP_EOL;
                                echo $insert_head;
                                $query_size = 0;
                            }
                        } else {
                            echo ';';
                        }
                        echo PHP_EOL;
			$i++;
		}
		echo PHP_EOL;
                echo "-- --------------------------------------------------------";
		echo PHP_EOL;
	}


        /**
         * creates the views schema
         * 
         * @return string
         */
        private function getViews()
        {
          
            $result = NULL;
            
            //$this->db_connected  = $this->db->createCommand('SELECT DATABASE() AS db')->queryAll()[0];
            $db_views    = $this->db->createCommand('SHOW FULL TABLES IN '.$this->db_connected.' WHERE TABLE_TYPE LIKE "VIEW";')->queryAll();

            foreach ($db_views as $view) {
                
                $the_view = $view['Tables_in_'.$this->db_connected];
                
                $result .=PHP_EOL."--\n-- Structure for view `".$the_view."`\n--".PHP_EOL;
                
                
                $create_view = $this->db->createCommand('SHOW CREATE VIEW `'.$the_view.'`')->queryAll();
                
                
                
//                 DEFINER=`anpec_3`@`%`

                foreach ($create_view as $create) {
                    $result .=PHP_EOL.'DROP TABLE IF EXISTS '.$this->db->quoteTableName($the_view).';'.PHP_EOL.PHP_EOL;
                    
                    if($this->removeViewDefiner)
                        $result .= preg_replace("/DEFINER=`\w+`@`.` /", '', $create['Create View']);
                    else 
                        $result .= $create['Create View'];
                    $result .= ";".PHP_EOL;
                    
                    $result .= PHP_EOL;
                    $result .= "-- --------------------------------------------------------";
                    $result .= PHP_EOL;

                }


            }
            
            return $result;
        }

        
        /**
	 * Get mysql tables list
	 * @return array
	 */
	private function getTables()
	{
//            $this->db_connected  = $this->db->createCommand('SELECT DATABASE() AS db')->queryAll()[0];
            $tables = $this->db->createCommand('SHOW FULL TABLES IN '.$this->db_connected.' WHERE TABLE_TYPE LIKE "BASE TABLE";')->queryAll();
            $result = array();
            
            
            if(count($tables>0))
                foreach ($tables as $table) {
                    $result[]['name']=$table['Tables_in_'.$this->db_connected];

                }
            
            return $result;
		//return $this->db->getSchema()->getTables();
	}
        
}
