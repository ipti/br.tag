# Yii extension that allows to create backups of mysql database (structure and data) 

Usage:

      Yii::import('ext.dumpDB.dumpDB');
          $dumper = new dumpDB();
          echo $dumper->getDump();

Saving the dump to a file:

      Yii::import('ext.dumpDB.dumpDB');
          $dumper = new dumpDB();
          $bk_file = 'FILE_NAME-'.date('YmdHis').'.sql';
          $fh = fopen($bk_file, 'w') or die("can't open file");
          fwrite($fh, $dumper->getDump(FALSE));
          fclose($fh);

Dumping external DB:

      Yii::import('ext.dumpDB.dumpDB');
         $dumper = new dumpDB('mysql:host=HOTS_NAME_OR_IP;dbname=DATABASE_NAME','USERNAME','PASSWORD');
         $dumper = new dumpDB();
         $dumper->setRemoveViewDefinerSecurity(TRUE);
         echo $dumper->getDump();

