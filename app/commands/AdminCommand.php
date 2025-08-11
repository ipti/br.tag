<?php

class AdminCommand extends CConsoleCommand
{
    public function run($args)
    {
        ini_set('display_errors', '1');
        error_reporting(1);

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '288M');
        set_time_limit(0);
        ignore_user_abort();
        Yii::app()->db2;
        $sql = "SELECT DISTINCT `TABLE_SCHEMA` FROM `information_schema`.`TABLES` WHERE TABLE_SCHEMA LIKE 'io.escola.%' OR TABLE_SCHEMA LIKE 'br.ong.tag.%';";
        $dbs = Yii::app()->db2->createCommand($sql)->queryAll();

        $excludedSchemasExport = [
            'io.escola.demo',
            'br.ong.tag.cras.indiaroba',
            'io.escola.adefib',
            'io.escola.joaobosco',
            'io.escola.cloc'
        ];
        $excludedSchemasImport = [
            'io.escola.demo',
            'io.escola.adefib',
            'io.escola.joaobosco',
            'io.escola.cloc'
        ];

        foreach ($dbs as $db) {
            $dbname = $db['TABLE_SCHEMA'];

            if (!in_array($dbname, $excludedSchemasExport)) {
                $this->exportDatabase($dbname);
            }
        }
        foreach ($dbs as $db) {
            $dbname = $db['TABLE_SCHEMA'];

            if (!in_array($dbname, $excludedSchemasImport)) {
                $this->importDatabase($dbname);
            }
        }

    }

    private function exportDatabase(string $dbname){
        echo "Conectando a $dbname..\n";
        Yii::app()->db->setActive(false);
        Yii::app()->db->connectionString = "mysql:host=51.81.125.135:31160;dbname=$dbname";
        Yii::app()->db->setActive(true);

        $fileName = $dbname . '.json';
        $fileImport = @fopen($fileName, 'r');
        $fileNameBak = $dbname . '.json.bak';
        $fileImportBak = @fopen($fileNameBak, 'r');

        if (!$fileImport && !$fileImportBak) {
            echo "Exportando..\n";
            $loads = $this->prepareExport();
            $datajson = serialize($loads);
            $file = fopen($fileName, 'w');
            fwrite($file, $datajson);
            fclose($file);
        }
        echo "fim exportação\n";
    }

    private function importDatabase(string $dbname): void
    {
        echo "Conectando a $dbname..\n";
        $fileName = $dbname . '.json';
        $fileImport = @fopen($fileName, 'r');

        if ($fileImport) {
            echo "Importando..\n";
            $jsonSyncTag = '';
            while (!feof($fileImport)) {
                $linha = fgets($fileImport, filesize($fileName));
                $jsonSyncTag .= $linha;
            }
            fclose($fileImport);
            $json = unserialize($jsonSyncTag);
            $this->loadMaster($json);
            rename($fileName, $fileName . '.bak');
        }
        echo "fim importação\n";
    }

    public function mres($value)
    {
        $search = ['\\',  "\x00", "\n",  "\r",  "'",  '"', "\x1a"];
        $replace = ['\\\\', '\\0', '\\n', '\\r', "\'", '\"', '\\Z'];

        return str_replace($search, $replace, $value);
    }

    public function loadMaster($loads)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $this->loadSchools($loads['schools']);
        $this->loadSchoolStructure($loads['schools_structure']);
        $this->loadClassrooms($loads['classrooms']);
        $this->loadInstructors($loads['instructors']);
        $this->loadDocuments($loads['idocuments']);
        $this->loadInstructorsTeachingData($loads['instructorsteachingdata']);
        $this->loadInstructorsVariableData($loads['instructorsvariabledata']);

    }

    private function loadSchools($schools){
        foreach ($schools as $scholl) {
            echo 'Importando escola' . $scholl['name'] . "..\n";
            $saveschool = new SchoolIdentification();
            $saveschool->setDb2Connection(true);
            $saveschool->setScenario('search');
            $saveschool->refreshMetaData();
            $saveschool = $saveschool->findByAttributes(['inep_id' => $scholl['inep_id']]);
            if (!isset($saveschool)) {
                $saveschool = new SchoolIdentification();
                $saveschool->setDb2Connection(true);
                $saveschool->refreshMetaData();
            }
            $saveschool->setScenario('search');
            $saveschool->attributes = $scholl;
            $saveschool->save();
            if (!empty($saveschool->errors)) {
                var_dump($saveschool->errors);
                exit;
            }
        }
    }

    private function loadSchoolStructure($schoolStructures){
        foreach ( $schoolStructures as $structure) {
            $saveschool = new SchoolStructure();
            $saveschool->setDb2Connection(true);
            $saveschool->setScenario('search');
            $saveschool->refreshMetaData();
            $saveschool = $saveschool->findByAttributes(['school_inep_id_fk' => $structure['school_inep_id_fk']]);
            if (!isset($saveschool)) {
                $saveschool = new SchoolStructure();
                $saveschool->setScenario('search');
                $saveschool->setDb2Connection(true);
                $saveschool->refreshMetaData();
            }
            $saveschool->attributes = $structure;
            $saveschool->save();
            if (!empty($saveschool->errors)) {
                var_dump($saveschool->errors);
                exit;
            }
        }
    }

    private function loadClassrooms($classrooms){
                foreach ($classrooms as $class) {
            echo 'Importando turma' . $class['name'] . "..\n";
            $saveclass = new Classroom();
            $saveclass->setScenario('search');
            $saveclass->setDb2Connection(true);
            $saveclass->refreshMetaData();
            $saveclass = $saveclass->findByAttributes(['hash' => $class['hash']]);
            if (!isset($saveclass)) {
                $saveclass = new Classroom();
                $saveclass->setScenario('search');
                $saveclass->setDb2Connection(true);
                $saveclass->refreshMetaData();
            }
            $saveclass->attributes = $class;
            $saveclass->hash = $class['hash'];
            $saveclass->save();
            if (!empty($saveclass->errors)) {
                var_dump($saveclass->errors);
                exit;
            }
        }

    }
    private function loadInstructors($instructors){
        foreach ($instructors as $instructor) {
            echo 'Importando Professor' . $instructor['name'] . "..\n";
            $saveinstructor = new InstructorIdentification();
            $saveinstructor->setScenario('search');
            $saveinstructor->setDb2Connection(true);
            $saveinstructor->refreshMetaData();
            $saveinstructor = $saveinstructor->findByAttributes(['hash' => $instructor['hash']]);
            if (!isset($saveinstructor)) {
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
            } catch (Exception $e) {
                echo $e->getMessage();
                var_dump($saveinstructor->errors);
                var_dump($saveinstructor->attributes);
                exit;
            }

            if (!empty($saveinstructor->errors)) {
                var_dump($saveinstructor->errors);
                exit;
            }
        }
    }

    private function loadDocuments($documents){
          foreach ($documents as $documentsaddress) {
            echo 'Importando Documento Professor' . $documentsaddress['hash'] . "..\n";
            $saveidocument = new InstructorDocumentsAndAddress();
            $saveidocument->setScenario('search');
            $saveidocument->setDb2Connection(true);
            $saveidocument->refreshMetaData();
            $saveidocument = $saveidocument->findByAttributes(['hash' => $documentsaddress['hash']]);
            if (!isset($saveidocument)) {
                $saveidocument = new InstructorDocumentsAndAddress();
                $saveidocument->setScenario('search');
                $saveidocument->setDb2Connection(true);
                $saveidocument->refreshMetaData();
            }
            $saveidocument->attributes = $documentsaddress;
            $saveidocument->hash = $documentsaddress['hash'];
            $saveidocument->refreshMetaData();
            $saveidocument->save();
            if (!empty($saveidocument->errors)) {
                var_dump($saveidocument->errors);
                exit;
            }
        }
    }

    private function loadInstructorsTeachingData($instructorsTeachingData){
        foreach ($instructorsTeachingData as $teachingdata) {
            echo 'Importando Teaching Data' . $teachingdata['hash'] . "..\n";
            $saveteaching = new InstructorTeachingData();
            $saveteaching->setScenario('search');
            $saveteaching->setDb2Connection(true);
            $saveteaching->refreshMetaData();
            $saveteaching = $saveteaching->findByAttributes(['hash' => $teachingdata['hash']]);
            if (!isset($saveteaching)) {
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
            if (!empty($saveteaching->errors)) {
                var_dump($saveteaching->errors);
                exit;
            }
        }
    }

    private function loadInstructorsVariableData($instructorsVariableData){
        foreach ($instructorsVariableData as $variabledata) {
            echo 'Importando Variable Data Professor' . $variabledata['hash'] . "..\n";
            $savevariable = new InstructorVariableData();
            $savevariable->setScenario('search');
            $savevariable->setDb2Connection(true);
            $savevariable->refreshMetaData();
            $savevariable = $savevariable->findByAttributes(['hash' => $variabledata['hash']]);
            if (!isset($savevariable)) {
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
            if (!empty($savevariable->errors)) {
                var_dump($savevariable->errors);
            }
        }
    }

    public function prepareExport()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        ignore_user_abort();

        $year = 2019;
        $loads = [];

        $schools = $this->getSchoolIdsByYear($year);
        $teachAll = $this->getAllInstructors();

        if ($this->hasDb2Connection()) {
            $this->loadInstructorData($teachAll, $loads);
        }

        foreach ($schools as $schoolRow) {
            $this->loadSchoolData($schoolRow['school_inep_id_fk'], $year, $loads);
        }

        return $loads;
    }

    private function getSchoolIdsByYear($year)
    {
        $sql = "SELECT DISTINCT(school_inep_id_fk)
                FROM student_enrollment a
                JOIN classroom b ON a.classroom_fk = b.id
                WHERE b.school_year = $year";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    private function getAllInstructors()
    {
        $i = new InstructorIdentification();
        $i->setDb2Connection(false);
        $i->refreshMetaData();
        return $i->findAll();
    }

    private function hasDb2Connection()
    {
        try {
            Yii::app()->db2;
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    private function loadInstructorData( $instructors,  &$loads)
    {
        foreach ($instructors as $teach) {
            $hash = hexdec(crc32($teach->name . $teach->birthday_date));

            if (!isset($loads['instructors'][$hash])) {
                $loads['instructors'][$hash] = $teach->attributes;
                $loads['instructors'][$hash]['hash'] = $hash;
            }

            if (!isset($loads['idocuments'][$hash])) {
                $doc = new InstructorDocumentsAndAddress();
                $doc->setDb2Connection(false);
                $doc->refreshMetaData();
                $loads['idocuments'][$hash] = $doc->findByPk($teach->id)->attributes;
                $loads['idocuments'][$hash]['hash'] = $hash;
            }
        }
    }

    private function loadSchoolData($schoolInepId, $year,  &$loads)
    {
        $school = $this->getSchoolById($schoolInepId);
        $hashSchool = hexdec(crc32($school->inep_id . $school->name));

        $loads['schools'][$hashSchool] = $school->attributes;
        $loads['schools'][$hashSchool]['hash'] = $hashSchool;

        $loads['schools_structure'][$hashSchool] = $school->structure->attributes;
        $loads['schools_structure'][$hashSchool]['hash'] = $hashSchool;

        $classrooms = $this->getClassroomsBySchoolYear($schoolInepId, $year);

        foreach ($classrooms as $classroom) {
            $this->loadClassroomData($school->inep_id, $classroom,  $loads);
        }
    }

    private function getSchoolById($id)
    {
        $s = new SchoolIdentification();
        $s->setDb2Connection(false);
        $s->refreshMetaData();
        return $s->findByPk($id);
    }

    private function getClassroomsBySchoolYear($schoolId, $year)
    {
        $c = new Classroom();
        $c->setDb2Connection(false);
        $c->refreshMetaData();
        return $c->findAllByAttributes(['school_inep_fk' => $schoolId, 'school_year' => $year]);
    }

    private function loadClassroomData($schoolInepId, $classroom, &$loads)
    {
        $hashClassroom = hexdec(crc32($schoolInepId . $classroom->id . $classroom->school_year));
        $loads['classrooms'][$hashClassroom] = $classroom->attributes;
        $loads['classrooms'][$hashClassroom]['hash'] = $hashClassroom;

        foreach ($classroom->instructorTeachingDatas as $teachingData) {
            $this->loadTeachingData($teachingData, $hashClassroom, $loads);
        }
    }

    private function loadTeachingData($teachingData, $hashClassroom, &$loads)
    {
        $instructor = $teachingData->instructorFk;
        $hashInstructor = hexdec(crc32($instructor->name . $instructor->birthday_date));
        $hashTeaching = hexdec(crc32($hashClassroom . $hashInstructor));

        $loads['instructorsteachingdata'][$hashTeaching] = $teachingData->attributes;
        $loads['instructorsteachingdata'][$hashTeaching]['hash_instructor'] = $hashInstructor;
        $loads['instructorsteachingdata'][$hashTeaching]['hash_classroom'] = $hashClassroom;
        $loads['instructorsteachingdata'][$hashTeaching]['hash'] = $hashTeaching;

        if (!isset($loads['instructors'][$hashInstructor])) {
            $loads['instructors'][$hashInstructor] = $instructor->attributes;
            $loads['instructors'][$hashInstructor]['hash'] = $hashInstructor;
            $loads['idocuments'][$hashInstructor] = $instructor->documents->attributes;
            $loads['idocuments'][$hashInstructor]['hash'] = $hashInstructor;
        }

        if (!isset($loads['instructorsvariabledata'][$hashInstructor])) {
            $loads['instructorsvariabledata'][$hashInstructor] = $instructor->instructorVariableData->attributes;
            $loads['instructorsvariabledata'][$hashInstructor]['hash'] = $hashInstructor;
        }
    }
}
