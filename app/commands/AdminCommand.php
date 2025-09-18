<?php
class AdminCommand extends CConsoleCommand
{
    public function run($args)
    {
        ini_set('display_errors','1');
        error_reporting(1);

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '288M');
        set_time_limit(0);
        ignore_user_abort();
        Yii::app()->db2;
        $sql = "SELECT DISTINCT `TABLE_SCHEMA` FROM `information_schema`.`TABLES` WHERE TABLE_SCHEMA LIKE 'io.escola.%' OR TABLE_SCHEMA LIKE 'br.ong.tag.%';";
        $loads = array();
        $dbs = Yii::app()->db2->createCommand($sql)->queryAll();
        foreach ($dbs as $db) {
            if($db['TABLE_SCHEMA'] != 'io.escola.demo' &&  $db['TABLE_SCHEMA'] != 'br.ong.tag.cras.indiaroba' &&  $db['TABLE_SCHEMA'] != 'io.escola.adefib'
                && $db['TABLE_SCHEMA'] != 'io.escola.joaobosco' && $db['TABLE_SCHEMA'] != 'io.escola.cloc'){
                $dbname = $db['TABLE_SCHEMA'];
                echo "Conectando a $dbname..\n";
                Yii::app()->db->setActive(false);
                Yii::app()->db->connectionString = "mysql:host=51.81.125.135:31160;dbname=$dbname";
                Yii::app()->db->setActive(true);
                @$fileName = $dbname . ".json";
                $fileImport = fopen($fileName, "r");
                @$fileNameBak = $dbname . ".json.bak";
                $fileImportBak = fopen($fileNameBak, "r");

                if ((is_bool( $fileImport) && !$fileImport) && ((is_bool( $fileImportBak)) && !$fileImportBak)) {
                    echo "Exportando..\n";
                    $loads = $this->prepareExport();

                    $datajson = serialize($loads);
                    $file = fopen($fileName, "w");
                    fwrite($file, $datajson);
                    fclose($file);

                }

                echo 'fim exportação\n';
            }

        }
        foreach ($dbs as $db) {
                    if($db['TABLE_SCHEMA'] != 'io.escola.demo' &&  $db['TABLE_SCHEMA'] != 'io.escola.adefib'
                    && $db['TABLE_SCHEMA'] != 'io.escola.joaobosco' && $db['TABLE_SCHEMA'] != 'io.escola.cloc')
                    {
                    $dbname = $db['TABLE_SCHEMA'];
                    echo "Conectando a $dbname..\n";
                    $fileName = $dbname . ".json";
                    $fileImport = fopen($fileName, "r");
                    if ($fileImport) {
                        echo "Importando..\n";
                        $jsonSyncTag = "";
                        while (!feof($fileImport)) {
                            $linha = fgets($fileImport, filesize($fileName));
                            $jsonSyncTag .= $linha;
                        }
                        fclose($fileImport);
                        $json = unserialize($jsonSyncTag);
                        $this->loadMaster($json);
                        rename($fileName, $fileName.'.bak');
                    }
                        echo 'fim importação\n';
                }

        }

    }

    public function mres($value)
    {
        $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
        $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");

        return str_replace($search, $replace, $value);
    }

    public function loadMaster($loads){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        foreach ($loads['schools'] as $scholl) {
            echo "Importando escola". $scholl['name']."..\n";
            $saveschool = new SchoolIdentification();
            $saveschool->setDb2Connection(true);
            $saveschool->setScenario('search');
            $saveschool->refreshMetaData();
            $saveschool = $saveschool->findByAttributes(array('inep_id'=>$scholl['inep_id']));
            if(!isset($saveschool)){
                $saveschool = new SchoolIdentification();
                $saveschool->setDb2Connection(true);
                $saveschool->refreshMetaData();
            }
            $saveschool->setScenario('search');
            $saveschool->attributes = $scholl;
            $saveschool->save();
            if(!empty($saveschool->errors)){
                var_dump($saveschool->errors);exit;
            }
    }
    foreach ($loads['schools_structure'] as $structure) {
        $saveschool = new SchoolStructure();
        $saveschool->setDb2Connection(true);
        $saveschool->setScenario('search');
        $saveschool->refreshMetaData();
        $saveschool = $saveschool->findByAttributes(array('school_inep_id_fk'=>$structure['school_inep_id_fk']));
        if(!isset($saveschool)){
            $saveschool = new SchoolStructure();
            $saveschool->setScenario('search');
            $saveschool->setDb2Connection(true);
            $saveschool->refreshMetaData();
        }
        $saveschool->attributes = $structure;
        $saveschool->save();
        if(!empty($saveschool->errors)){
            var_dump($saveschool->errors);exit;
        }
    }

    foreach ($loads['classrooms'] as $class) {
        echo "Importando turma". $class['name']."..\n";
        $saveclass = new Classroom();
        $saveclass->setScenario('search');
        $saveclass->setDb2Connection(true);
        $saveclass->refreshMetaData();
        $saveclass = $saveclass->findByAttributes(array('hash'=>$class['hash']));
        if (!isset($saveclass)){
            $saveclass = new Classroom();
            $saveclass->setScenario('search');
            $saveclass->setDb2Connection(true);
            $saveclass->refreshMetaData();
        }
        $saveclass->attributes = $class;
        $saveclass->hash = $class['hash'];
        $saveclass->save();
        if(!empty($saveclass->errors)){
            var_dump($saveclass->errors);exit;
        }
    }

    foreach ($loads['instructors'] as $instructor) {
            echo "Importando Professor". $instructor['name']."..\n";
            $saveinstructor = new InstructorIdentification();
            $saveinstructor->setScenario('search');
            $saveinstructor->setDb2Connection(true);
            $saveinstructor->refreshMetaData();
            $saveinstructor = $saveinstructor->findByAttributes(array('hash'=>$instructor['hash']));
            if (!isset($saveinstructor)){
                $saveinstructor = new InstructorIdentification();
                $saveinstructor->setScenario('search');
                $saveinstructor->setDb2Connection(true);
                $saveinstructor->refreshMetaData();
            }
            $saveinstructor->attributes = $instructor;
            $saveinstructor->hash = $instructor['hash'];
            try {
                $saveinstructor->refreshMetaData();
                $saveinstructor->save();
            } catch (Exception $e){
                echo $e->getMessage();
                var_dump($saveinstructor->errors);
                var_dump($saveinstructor->attributes);exit;
            }

            if(!empty($saveinstructor->errors)){
                var_dump($saveinstructor->errors);exit;
            }
    }
    foreach ($loads['idocuments'] as $documentsaddress) {
        echo "Importando Documento Professor". $documentsaddress['hash']."..\n";
        $saveidocument = new InstructorDocumentsAndAddress();
        $saveidocument->setScenario('search');
        $saveidocument->setDb2Connection(true);
        $saveidocument->refreshMetaData();
        $saveidocument = $saveidocument->findByAttributes(array('hash'=>$documentsaddress['hash']));
        if (!isset($saveidocument)){
            $saveidocument = new InstructorDocumentsAndAddress();
            $saveidocument->setScenario('search');
            $saveidocument->setDb2Connection(true);
            $saveidocument->refreshMetaData();
        }
        $saveidocument->attributes = $documentsaddress;
        $saveidocument->hash = $documentsaddress['hash'];
        $saveidocument->refreshMetaData();
        $saveidocument->save();
        if(!empty($saveidocument->errors)){
            var_dump($saveidocument->errors);exit;
        }
    }
    foreach ($loads['instructorsteachingdata'] as $teachingdata) {
        echo "Importando Teaching Data". $teachingdata['hash']."..\n";
        $saveteaching = new InstructorTeachingData();
        $saveteaching->setScenario('search');
        $saveteaching->setDb2Connection(true);
        $saveteaching->refreshMetaData();
        $saveteaching = $saveteaching->findByAttributes(array('hash'=>$teachingdata['hash']));
        if (!isset($saveteaching)){
            $saveteaching = new InstructorTeachingData();
            $saveteaching->setScenario('search');
            $saveteaching->setDb2Connection(true);
            $saveteaching->refreshMetaData();
        }
        $saveteaching->attributes = $teachingdata;
        $saveteaching->hash = $teachingdata['hash'];
        $saveteaching->hash_classroom = $teachingdata['hash_classroom'];
        $saveteaching->hash_instructor = $teachingdata['hash_instructor'];
        $saveteaching->refreshMetaData();
        $saveteaching->save();
        if(!empty($saveteaching->errors)){
            var_dump($saveteaching->errors);exit;
        }
    }
    foreach ($loads['instructorsvariabledata'] as $variabledata) {
        echo "Importando Variable Data Professor". $variabledata['hash']."..\n";
        $savevariable = new InstructorVariableData();
        $savevariable->setScenario('search');
        $savevariable->setDb2Connection(true);
        $savevariable->refreshMetaData();
        $savevariable = $savevariable->findByAttributes(array('hash'=>$variabledata['hash']));
        if (!isset($savevariable)){
            $savevariable = new InstructorVariableData();
            $savevariable->setScenario('search');
            $savevariable->setDb2Connection(true);
            $savevariable->refreshMetaData();
            echo 'entrou';
        }
        $savevariable->attributes = $variabledata;
        $savevariable->hash = $variabledata['hash'];
        $savevariable->refreshMetaData();
        $savevariable->save();
        if(!empty($savevariable->errors)){
            var_dump($savevariable->errors);
        }
    }
}
    public function prepareExport(){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        ignore_user_abort();
        $year = 2019;
        $loads = array();
        $sql = "SELECT DISTINCT(school_inep_id_fk) FROM student_enrollment a
                JOIN classroom b ON(a.`classroom_fk`=b.id)
                WHERE
                b.`school_year`=$year";
        $schools = Yii::app()->db->createCommand($sql)->queryAll();
        $istudent = new StudentIdentification();
        $iteach = new InstructorIdentification();
        $istudent->setDb2Connection(false);
        $istudent->refreshMetaData();
        $iteach->setDb2Connection(false);
        $iteach->refreshMetaData();
        $studentAll = $istudent->findAll();
        $teachAll = $iteach->findAll();
        try {
            Yii::app()->db2;
            $conn = true;
        }
        catch(Exception $ex) {
            $conn = false;
        }
        if($conn){
            foreach ($teachAll as $teach) {
                $hash_teach = hexdec(crc32($teach->name.$teach->birthday_date));
                if(!isset($loads['instructors'][$hash_teach])){
                    $loads['instructors'][$hash_teach] = $teach->attributes;
                    $loads['instructors'][$hash_teach]['hash'] = $hash_teach;
                }
                if(!isset($loads['idocuments'][$hash_teach])){
                    $idocs = new InstructorDocumentsAndAddress();
                    $idocs->setDb2Connection(false);
                    $idocs->refreshMetaData();
                    $loads['idocuments'][$hash_teach] = $idocs->findByPk($teach->id)->attributes;
                    $loads['idocuments'][$hash_teach]['hash'] = $hash_teach;
                }
            }
        }

        foreach ($schools as $schll) {
            $year = 2019;
            $ischool = new SchoolIdentification();
            $ischool->setDb2Connection(false);
            $ischool->refreshMetaData();
            $school = $ischool->findByPk($schll['school_inep_id_fk']);

            $iclass = new Classroom();
            $iclass->setDb2Connection(false);
            $iclass->refreshMetaData();
            $classrooms = $iclass->findAllByAttributes(["school_inep_fk" => $schll['school_inep_id_fk'], "school_year" => $year]);
            $hash_school = hexdec(crc32($school->inep_id.$school->name));
            $loads['schools'][$hash_school] = $school->attributes;
            $loads['schools'][$hash_school]['hash'] = $hash_school;
            $loads['schools_structure'][$hash_school] = $school->structure->attributes;
            $loads['schools_structure'][$hash_school]['hash'] = $hash_school;
            foreach ($classrooms as $iclass => $classroom) {
                $hash_classroom = hexdec(crc32($school->inep_id.$classroom->id.$classroom->school_year));
                $loads['classrooms'][$hash_classroom] = $classroom->attributes;
                $loads['classrooms'][$hash_classroom]['hash'] = $hash_classroom;

                foreach ($classroom->instructorTeachingDatas  as $teachingData) {
                    $hash_instructor = hexdec(crc32($teachingData->instructorFk->name.$teachingData->instructorFk->birthday_date));
                    $hash_teachingdata = hexdec(crc32($hash_classroom.$hash_instructor));
                    $loads['instructorsteachingdata'][$hash_teachingdata] = $teachingData->attributes;
                    $loads['instructorsteachingdata'][$hash_teachingdata]['hash_instructor'] = $hash_instructor;
                    $loads['instructorsteachingdata'][$hash_teachingdata]['hash_classroom'] = $hash_classroom;
                    $loads['instructorsteachingdata'][$hash_teachingdata]['hash'] = $hash_teachingdata;

                    if(!isset($loads['instructors'][$hash_instructor])){
                        $loads['instructors'][$hash_instructor] = $teachingData->instructorFk->attributes;
                        $loads['instructors'][$hash_instructor]['hash'] = $hash_instructor;
                        $loads['idocuments'][$hash_instructor] = $teachingData->instructorFk->documents->attributes;
                        $loads['idocuments'][$hash_instructor]['hash'] = $hash_instructor;
                    }
                    if(!isset($loads['instructorsvariabledata'][$hash_instructor])) {
                        $loads['instructorsvariabledata'][$hash_instructor] = $teachingData->instructorFk->instructorVariableData->attributes;
                        $loads['instructorsvariabledata'][$hash_instructor]['hash'] = $hash_instructor;
                    }
                }

            }
        }

        return $loads;
    }

}
