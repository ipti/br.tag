<?php

//@done S2 - Modularizar o código do import
//@done S2 - Criar o controller de Import
//@done S2 - Mover o código do import de SchoolController.php para AdminController.php
//@done S2 - Mover o código do configACL de SchoolController.php para AdminController.php
//@done S2 - Criar método de limparBanco
//@done S2 - Criar tela de index do AdminController.php
//@done S2 - Criar usuários padrões.
//@done S2 - Mensagens de retorno ao executar os scripts.


class AdminController extends Controller
{

    public $layout = 'fullmenu';

    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('CreateUser', 'index'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('import', 'export',
                    'clearDB', 'acl',
                    'backup', 'data',
                    'exportStudentIdentify', 'syncExport', 'syncImport', 'exportToMaster', 'clearMaster', 'importFromMaster'),
                'users' => array('@'),
            ),
        );
    }

    /**
     * Show the Index Page.
     */
    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionSyncExport()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        // Fazer Download no Final
        //Arquivo Json para adcionar no ZIP
        $json = [];
        $json['student'] = [];
        $json['classroom'] = [];

        $school = yii::app()->user->school;
        $year = yii::app()->user->year;
        $filterStudent = "school_inep_id_fk = " . $school;
        $filterClassroom = "school_inep_fk = " . $school . " and school_year = " . $year;

        $enrollments = StudentEnrollment::model()->findAll($filterStudent);

        foreach ($enrollments as $enrollment) {
            $student = $enrollment->studentFk;
            $classroom = $enrollment->classroomFk;
            if ($classroom->school_year == $year) {
                $student->school_inep_id_fk = $enrollment->school_inep_id_fk;
                $student->save();
            }
        }

        $students = StudentIdentification::model()->findAll($filterStudent);
        $classrooms = Classroom::model()->findAll($filterClassroom);

        $updateFKID = "UPDATE `class` as c
            SET `fkid` = CONCAT((select school_inep_fk from classroom as cr where cr.id = c.classroom_fk),';',c.id)
            WHERE true;
            UPDATE `class_board` as c
            SET `fkid` = CONCAT((select school_inep_fk from classroom as cr where cr.id = c.classroom_fk),';',c.id)
            WHERE true;
            UPDATE `class_faults` as c
            SET `fkid` = CONCAT(
                    (select cr.school_inep_fk
                            from class as cs
                    join classroom as cr on (cs.classroom_fk = cr.id)
                            where cs.id = c.class_fk),';',c.id)
            WHERE true;
            UPDATE `class_has_content` as c
            SET `fkid` = CONCAT(
                    (select cr.school_inep_fk
                            from class as cs
                    join classroom as cr on (cs.classroom_fk = cr.id)
                            where cs.id = c.class_fk),';',c.id)
            WHERE true;
            UPDATE `classroom` as c
            SET `fkid` = CONCAT(c.school_inep_fk,';',c.id)
            WHERE true;
            UPDATE `classroom_has_course_plan` as c
            SET `fkid` = CONCAT((select school_inep_fk from classroom as cr where cr.id = c.classroom_fk),';',c.id)
            WHERE true;
            UPDATE `course_class` as c
            SET `fkid` = CONCAT((select school_inep_fk from course_plan as cp where cp.id = c.course_plan_fk),';',c.id)
            WHERE true;
            UPDATE `course_class_has_class_resource` as c
            SET `fkid` = CONCAT(
                    (select cp.school_inep_fk
                            from course_class as cs
                    join course_plan as cp on (cs.course_plan_fk = cp.id)
                            where cs.id = c.course_class_fk),';',c.id)
            WHERE true;
            UPDATE `course_plan` as c
            SET `fkid` = CONCAT(c.school_inep_fk,';',c.id)
            WHERE true;
            UPDATE `grade` as g
            SET `fkid` = CONCAT(
                    (select e.school_inep_id_fk
                            from student_enrollment as e
                            where e.id = g.enrollment_fk),';',g.id)
            WHERE true;
            UPDATE `instructor_documents_and_address` as c
            SET `fkid` = CONCAT(c.school_inep_id_fk,';',c.id)
            WHERE true;
            UPDATE `instructor_identification` as c
            SET `fkid` = CONCAT(c.school_inep_id_fk,';',c.id)
            WHERE true;
            UPDATE `instructor_teaching_data` as c
            SET `fkid` = CONCAT(c.school_inep_id_fk,';',c.id)
            WHERE true;
            UPDATE `instructor_variable_data` as c
            SET `fkid` = CONCAT(c.school_inep_id_fk,';',c.id)
            WHERE true;
            UPDATE `student_documents_and_address` as c
            SET `fkid` = CONCAT(c.school_inep_id_fk,';',c.id)
            WHERE true;
            UPDATE `student_enrollment` as c
            SET `fkid` = CONCAT(c.school_inep_id_fk,';',c.id)
            WHERE true;
            UPDATE `student_identification` as c
            SET `fkid` = CONCAT(c.school_inep_id_fk,';',c.id)
            WHERE true;";
        yii::app()->db->schema->commandBuilder->createSqlCommand($updateFKID)->query();

        $studentArray = [];
        foreach ($students as $student) {
            $sfkid = $student->fkid;
            $studentArray[$sfkid] = [];
            $studentArray[$sfkid]['attributes'] = [];
            $studentArray[$sfkid]['documents'] = [];
            $studentArray[$sfkid]['documents']['attributes'] = [];
            $documents = $student->documentsFk;
            $studentArray[$sfkid]['documents']['attributes'] = $documents->attributes;
            $studentArray[$sfkid]['attributes'] = $student->attributes;
        }
        $json['student'] = $studentArray;


        $classroomArray = [];
        foreach ($classrooms as $classroom) {
            $cfkid = $classroom->fkid;
            $classroomArray[$cfkid] = $classroomArray[$cfkid]['attributes'] = [];
            $classroomArray[$cfkid]['attributes'] = $classroom->attributes;

            $classroomArray[$cfkid]['classes'] = [];
            $classes = $classroom->classes;
            $classesArray = [];
            foreach ($classes as $class) {
                $csfkid = $class->fkid;
                $classesArray[$csfkid] = [];
                $classesArray[$csfkid]['attributes'] = [];
                $classesArray[$csfkid]['attributes'] = $class->attributes;

                $classesArray[$csfkid]['faults'] = [];
                $faults = $class->classFaults;
                $faultArray = [];
                foreach ($faults as $fault) {
                    $ffkid = $fault->fkid;
                    $faultArray[$ffkid] = [];
                    $faultArray[$ffkid]['attributes'] = [];
                    $faultArray[$ffkid]['attributes'] = $fault->attributes;
                }
                $classesArray[$csfkid]['faults'] = $faultArray;
            }
            $classroomArray[$cfkid]['classes'] = $classesArray;


            $classroomArray[$cfkid]['classboards'] = [];
            $classBoards = $classroom->classBoards;
            $classBoardArray = [];
            foreach ($classBoards as $classboard) {
                $cbfkid = $classboard->fkid;
                $classBoardArray[$cbfkid] = [];
                $classBoardArray[$cbfkid]['attributes'] = [];
                $classBoardArray[$cbfkid]['attributes'] = $classboard->attributes;
            }
            $classroomArray[$cfkid]['classboards'] = $classBoardArray;


            $classroomArray[$cfkid]['enrollments'] = [];
            $enrollments = $classroom->studentEnrollments;
            $enrollmentsArray = [];
            foreach ($enrollments as $enrollment) {
                $efkid = $enrollment->fkid;
                $enrollmentsArray[$efkid] = [];
                $enrollmentsArray[$efkid]['attributes'] = [];
                $enrollmentsArray[$efkid]['attributes'] = $enrollment->attributes;
            }
            $classroomArray[$cfkid]['enrollments'] = $enrollmentsArray;
        }
        $json['classroom'] = $classroomArray;

        $json_encode = json_encode($json);
        $date = date('d_m_Y H_i_s');
        $zipName = 'ArquivoSincronizacaoTAG_' . $school . '_' . $date . '.zip';
        $tempArchiveZip = new ZipArchive;
        $tempArchiveZip->open($zipName, ZipArchive::CREATE);
        $tempArchiveZip->addFromString($school . "_" . $date . ".json", $json_encode);
        $tempArchiveZip->close();


        if (file_exists($zipName)) {
            header('Content-type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zipName . '"');
            readfile($zipName);
            unlink($zipName);
        }
    }

    /**
     *
     * @param CActiveRecord $model
     * @param array $attributes
     * @return CDbCommand
     */
    private function createMultipleInsertOnDuplicateKeyUpdate($model, $attributes)
    {
        if (count($attributes) > 0) {
            $builder = Yii::app()->db->schema->commandBuilder;
            $command = $builder->createMultipleInsertCommand($model->tableName(), $attributes);
            $sql = $command->getText();

            $values = [];
            $valuesUpdate = " ON DUPLICATE KEY UPDATE ";
            $i = 0;
            foreach ($model->attributes as $name => $value) {
                if ($i != 0) {
                    $valuesUpdate .= ",";
                }
                $valuesUpdate .= " `" . $name . "`=VALUES(`" . $name . "`)";
                $i = 1;
            }
            $sql .= $valuesUpdate . ";";
            $i = 0;

            foreach ($attributes as $value) {
                foreach ($value as $name => $val) {
                    $values[$name . "_" . $i] = $val;
                }
                $i++;
            }
            return Yii::app()->db->createCommand($sql)->bindValues($values);
        } else {
            return Yii::app()->db->createCommand("select 1+1;");
        }
    }

    /**
     * php.ini
     * @warning max_post_size = 200M
     * @warning upload_max_filesize = 200M
     *
     * nginx
     * @warning client_max_body_size 200M;
     * @warning fastcgi_read_timeout 300;
     */
    public function actionSyncImport()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ignore_user_abort();
        $time1 = time();

        $path = Yii::app()->basePath;
        $myfile = $_FILES['file'];
        $uploadfile = $path . '/import/' . basename($myfile['name']);
        move_uploaded_file($myfile["tmp_name"], $uploadfile);
        $fileDir = $uploadfile;

        $mode = 'r';

        $fileImport = fopen($fileDir, $mode);
        if ($fileImport == false) {
            die('O arquivo não existe.');
        }

        $jsonSyncTag = "";
        while (!feof($fileImport)) {
            $linha = fgets($fileImport, filesize($uploadfile));
            $jsonSyncTag .= $linha;
        }
        fclose($fileImport);

        $json = json_decode($jsonSyncTag, true);
        $students = isset($json['student']) ? $json['student'] : [];
        //student[fkid][attributes]
        //student[fkid][documents][attributes]
        $classrooms = isset($json['classroom']) ? $json['classroom'] : [];
        //classroom[fkid][attributes]
        //classroom[fkid][classes][fkid][attributes]
        //classroom[fkid][classes][fkid][faults][fkid][attributes]
        //classroom[fkid][classboards][fkid][attributes]
        //classroom[fkid][enrollments][fkid][attributes]
        $exit = "";


        $studentValues = [];
        $studentModel = StudentIdentification::model();
        foreach ($students as $student) {
            $myStudent = $studentModel->findByAttributes(['fkid' => $student['attributes']['fkid']]);
            if ($myStudent === null) {
                $student['attributes']['id'] = null;
            } else {
                $student['attributes']['id'] = $myStudent->id;
            }
            array_push($studentValues, $student['attributes']);
//            echo $student['attributes']['id']. '-' .$student['attributes']['fkid'].'<br>';
        }
        $this->createMultipleInsertOnDuplicateKeyUpdate($studentModel, $studentValues)->query();

        $documentValues = [];
        $documentModel = StudentDocumentsAndAddress::model();
        foreach ($students as $student) {
            $document = $student['documents'];
            $myDocument = $documentModel->findByAttributes(['fkid' => $document['attributes']['fkid']]);
            if ($myDocument === null) {
                $student['documents']['attributes']['id'] = null;
            } else {
                $document['attributes']['id'] = $myDocument->id;
            }
            array_push($documentValues, $document['attributes']);
        }
        $this->createMultipleInsertOnDuplicateKeyUpdate($documentModel, $documentValues)->query();


        //classroom[fkid][attributes]
        $classroomValues = [];
        $classroomModel = Classroom::model();
        foreach ($classrooms as $classroom) {
            $myClassroom = $classroomModel->findByAttributes(['fkid' => $classroom['attributes']['fkid']]);
            if ($myClassroom === null) {
                $classroom['attributes']['id'] = null;
            } else {
                $classroom['attributes']['id'] = $myClassroom->id;
            }
            array_push($classroomValues, $classroom['attributes']);
        }
        $this->createMultipleInsertOnDuplicateKeyUpdate($classroomModel, $classroomValues)->query();


        //classroom[fkid][classes][fkid][attributes]
        $classesValues = [];
        $classesModel = Classes::model();
        foreach ($classrooms as $classroom) {
            foreach ($classroom['classes'] as $class) {
                $myClass = $classesModel->findByAttributes(['fkid' => $class['attributes']['fkid']]);
                if ($myClass === null) {
                    $myClassroom = $classroomModel->findByAttributes(['fkid' => $classroom['attributes']['fkid']]);
                    $class['attributes']['id'] = null;
                    $class['attributes']['classroom_fk'] = $myClassroom->id;
                } else {
                    $class['attributes']['id'] = $myClass->id;
                    $class['attributes']['classroom_fk'] = $myClass->classroom_fk;
                }
                array_push($classesValues, $class['attributes']);
            }
        }
        $this->createMultipleInsertOnDuplicateKeyUpdate($classesModel, $classesValues)->query();


        //classroom[fkid][enrollments][fkid][attributes]
        $enrollmentsValues = [];
        $enrollmentsModel = StudentEnrollment::model();
        foreach ($classrooms as $classroom) {
            foreach ($classroom['enrollments'] as $enrollment) {
                $myEnrollment = $enrollmentsModel->findByAttributes(['fkid' => $enrollment['attributes']['fkid']]);
                if ($myEnrollment === null) {
                    $myClassroom = $classroomModel->findByAttributes(['fkid' => $classroom['attributes']['fkid']]);
                    $studentFkid = null;
                    foreach ($students as $student) {
                        $id = explode(';', $student['attributes']['fkid'])[1];
                        if ($enrollment['attributes']['student_fk'] == $id) {
                            $studentFkid = $student['attributes']['fkid'];
                            break;
                        }
                    }
                    $myStudent = $studentModel->findByAttributes(['fkid' => $studentFkid]);
                    $enrollment['attributes']['id'] = null;
                    $enrollment['attributes']['classroom_fk'] = $myClassroom->id;
                    $enrollment['attributes']['student_fk'] = $myStudent->id;
                } else {
                    $enrollment['attributes']['id'] = $myEnrollment->id;
                    $enrollment['attributes']['classroom_fk'] = $myEnrollment->classroom_fk;
                    $enrollment['attributes']['student_fk'] = $myEnrollment->student_fk;
                }
                array_push($enrollmentsValues, $enrollment['attributes']);
            }
        }
        $this->createMultipleInsertOnDuplicateKeyUpdate($enrollmentsModel, $enrollmentsValues)->query();


        //classroom[fkid][classes][fkid][faults][fkid][attributes]
        $faultsValues = [];
        $faultsModel = ClassFaults::model();
        foreach ($classrooms as $classroom) {
            foreach ($classroom['classes'] as $class) {
                foreach ($class['faults'] as $fault) {
                    $myFault = $faultsModel->findByAttributes(['fkid' => $fault['attributes']['fkid']]);
                    if ($myFault === null) {
                        $myClass = $classesModel->findByAttributes(['fkid' => $class['attributes']['fkid']]);
                        $enrollmentFkid = null;
                        foreach ($classroom['enrollments'] as $enrollment) {
                            $id = $enrollment['attributes']['student_fk'];
                            if ($fault['attributes']['student_fk'] == $id) {
                                $enrollmentFkid = $enrollment['attributes']['fkid'];
                                break;
                            }
                        }
                        $myEnrollment = $enrollmentsModel->findByAttributes(['fkid' => $enrollmentFkid]);
                        $fault['attributes']['id'] = null;
                        $fault['attributes']['class_fk'] = $myClass->id;
                        $fault['attributes']['student_fk'] = $myEnrollment->student_fk;
                    } else {
                        $fault['attributes']['id'] = $myFault->id;
                        $fault['attributes']['class_fk'] = $myFault->class_fk;
                        $fault['attributes']['student_fk'] = $myFault->student_fk;
                    }
                    array_push($faultsValues, $fault['attributes']);
                }
            }
        }
        $this->createMultipleInsertOnDuplicateKeyUpdate($faultsModel, $faultsValues)->query();


        //classroom[fkid][classboards][fkid][attributes]
        /**
         * Precisa enviar junto o Instrutor
         * $classboardsValues = [];
         * $classboardsModel = ClassBoard::model();
         * foreach ($classrooms as $classroom) {
         * foreach ($classroom['classboards'] as $classboard) {
         * $myClassboard = $classboardsModel->findByAttributes(['fkid' => $classboard['attributes']['fkid']]);
         * if ($myClassboard === null) {
         * $myClassroom = $classroomModel->findByAttributes(['fkid' => $classroom['attributes']['fkid']]);
         * $classboard['attributes']['id'] = null;
         * $classboard['attributes']['classroom_fk'] = $myClassroom->id;
         * } else {
         * $classboard['attributes']['id'] = $myClassboard->id;
         * $classboard['attributes']['classroom_fk'] = $myClassboard->classroom_fk;
         * }
         * array_push($classboardsValues, $classboard['attributes']);
         * }
         * }
         * $this->createMultipleInsertOnDuplicateKeyUpdate($classboardsModel, $classboardsValues)->query();
         * */
        $time2 = time();
        echo $time2 - $time1;
        echo "<hr>";
        echo $exit;
    }

    /**
     * Update de database.
     *
     * @return {Redirect} Return the index page with a FlashMessage
     *
     */
    public function actionUpdateDB()
    {
        set_time_limit(0);
        ignore_user_abort();

        $updateDir = Yii::app()->basePath . '/../updates/';

        $dirFiles = scandir($updateDir);

        Yii::import('ext.FileManager.fileManager');
        $fm = new fileManager();

        $file = $fm->open($updateDir . '_version');
        $version = fgets($file);
        $fm->closeAll();

        $count = 0;

        foreach ($dirFiles as $fileName) {

            if ($fileName != '.' && $fileName != '..' && $fileName != 'readme' && $fileName != '_version' && substr("abcdef", -1) != '~') {

                if ($version != "" && $version < $fileName) {
                    $file = $fm->open($updateDir . $fileName);
                    $sql = "";
                    while (true) {
                        $fileLine = fgets($file);
                        $sql .= $fileLine;
                        if ($fileLine == null)
                            break;
                    }

                    $result = Yii::app()->db->createCommand($sql)->query();

                    if ($result) {
                        $file = $fm->write($updateDir . '_version', $fileName);
                        Yii::app()->user->setFlash('success', Yii::t('default', 'Atualização Concluída!'));
                        $count++;
                    } else {
                        Yii::app()->user->setFlash('error', Yii::t('default', 'Erro ao atualizar!'));
                    }
                }
            }
        }
        if ($count == 0) {
            Yii::app()->user->setFlash('notice', Yii::t('default', 'Não há atualizações!'));
        }
        $fm->closeAll();
        $this->render('index');
    }

    /**
     * Generate the BackupFile.
     * @param boolean $return - Defaults True
     * @return redirecrToIndex|boolean Return to the index page with a FlashMenssage or return $boolean
     */
    public static function actionBackup($return = TRUE)
    {
        /* Yii::import('ext.dumpDB.dumpDB');
          $dumper = new dumpDB();
          $dump = $dumper->getDump(false);

          $fileDir = Yii::app()->basePath . '/backup/' . date('Y-m-d') . '.sql';

          Yii::import('ext.FileManager.fileManager');
          $fm = new fileManager();
          $result = $fm->write($fileDir, $dump);

          if ($return) {
          if ($result) {
          Yii::app()->user->setFlash('success', Yii::t('default', 'Backup efetuado com Sucesso!'));
          } else {
          Yii::app()->user->setFlash('error', Yii::t('default', 'Backup falhou!'));
          }
          Yii::app()->controller->redirect('?r=admin/index');
          }
          return $result; */
        return 0;
    }

    /**
     * Generate some Data and a DataFile.
     * @param boolean $file - Defaults True
     * @return redirecrToData - Return to the Data page.
     */
    public function actionData($file = TRUE)
    {
        $data = [];
        //Turma
        $where = "school_year = " . date('Y');
        $classrooms = Classroom::model()->count($where);
        $data['classroom'] = $classrooms;

        //Identificação do Professor
        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->join = 'LEFT JOIN instructor_teaching_data ita ON ita.instructor_fk = t.id ';
        $criteria->join .= 'LEFT JOIN classroom c ON c.id = ita.classroom_id_fk';
        $criteria->condition = 'c.school_year = :value';
        $criteria->params = array(":value" => date('Y'));
        $criteria->group = 't.id';
        $instructors = InstructorIdentification::model()->count($criteria);
        $data['instructors'] = $instructors;

        //Identificação do Aluno
        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->join = 'LEFT JOIN student_enrollment se ON se.student_fk = t.id ';
        $criteria->join .= 'LEFT JOIN classroom c ON c.id = se.classroom_fk';
        $criteria->condition = 'c.school_year = :value';
        $criteria->params = array(":value" => date('Y'));
        $criteria->group = 't.id';
        $students = StudentIdentification::model()->count($criteria);
        $data['students'] = $students;

        //Matricula do Aluno
        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->join .= 'LEFT JOIN classroom c ON c.id = t.classroom_fk';
        $criteria->condition = 'c.school_year = :value';
        $criteria->params = array(":value" => date('Y'));
        $enrollments = StudentEnrollment::model()->count($criteria);
        $data['enrollments'] = $enrollments;

        if ($file) {
            $fileDir = Yii::app()->basePath . '/backup/Data/' . date('Y-m-d') . '.dat';
            $dataText = json_encode($data);

            Yii::import('ext.FileManager.fileManager');
            $fm = new fileManager();
            $result = $fm->write($fileDir, $dataText);
            if ($result) {
                Yii::app()->user->setFlash('success', Yii::t('default', 'Dados salvos com Sucesso!'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('default', 'Não foi possível salvar os dados!'));
            }
        }

        $this->render('data', array('data' => $data));
    }

    public function actionExportStudentIdentify()
    {

        $fileDir = Yii::app()->basePath . '/export/alunos-' . date('Y_') . Yii::app()->user->school . '.TXT';

        Yii::import('ext.FileManager.fileManager');
        $fm = new fileManager();

        $export = "";
        /* |id|nome|nascimento|mae|pai|UF|municipio| */

        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->condition = 't.inep_id is null '
            . 'AND t.send_year <= :year';
        $criteria->params = array(":year" => date('Y'));
        $criteria->group = 't.id';
        $students = StudentIdentification::model()->findAll($criteria);
        foreach ($students as $key => $student) {
            $a = $student;
            $export .= "|" . $a->id
                . "|" . $a->name
                . "|" . $a->birthday
                . "|" . $a->filiation_1
                . "|" . $a->filiation_2
                . "|" . $a->edcenso_uf_fk
                . "|" . $a->edcenso_city_fk
                . "|\n";
        }

        $result = $fm->write($fileDir, $export);
        if ($result) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Exportação dos Alunos Concluida com Sucesso.'));
        } else {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Houve algum erro na Exportação dos Alunos.'));
        }

        $this->render('index');
    }

    /**
     * Generate the ExportFile.
     * @return redirecrToIndex - Return to the index page with a FlashMenssage
     */
    public function actionExport()
    {
        $export = "";

        //Escolas
        $schools = SchoolIdentification::model()->findAll();
        foreach ($schools as $key => $school) {
            $attributes = $school->attributes;
            // Remove
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            // Remove
            array_pop($attributes);
            $export .= implode('|', $attributes);
            $export .= "\n";
        }

        //Estrutura Escolar
        $schoolsStructure = SchoolStructure::model()->findAll();
        foreach ($schoolsStructure as $key => $schoolStructure) {
            $export .= implode('|', $schoolStructure->attributes);
            $export .= "\n";
        }

        //Turma
        $where = "school_year = " . date('Y');
        $classrooms = Classroom::model()->findAll($where);
        foreach ($classrooms as $key => $classroom) {
            $attributes = $classroom->attributes;

            // Remove fkid
            array_pop($attributes);
            //Remove create_time
            array_pop($attributes);
            //Remove Turno
            array_pop($attributes);
            //Remove Ano
            array_pop($attributes);

            $export .= implode('|', $attributes);
            $export .= "\n";
        }

        //Identificação do Professor
        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->join = 'LEFT JOIN instructor_teaching_data ita ON ita.instructor_fk = t.id ';
        $criteria->join .= 'LEFT JOIN classroom c ON c.id = ita.classroom_id_fk';
        $criteria->condition = 'c.school_year = :value';
        $criteria->params = array(":value" => date('Y'));
        $criteria->group = 't.id';
        $instructors = InstructorIdentification::model()->findAll($criteria);
        foreach ($instructors as $key => $instructor) {

            //Dados do Professor
            $instructorId = $instructor->attributes;
            // Remove fkid
            array_pop($instructorId);
            $export .= implode('|', $instructorId);
            $export .= "\n";

            //Documentos do Professor
            $instructorDocs = InstructorDocumentsAndAddress::model()->findByPk($instructor->id);
            $instructorDocsAtt = $instructorDocs->attributes;
            // Remove fkid
            array_pop($instructorDocsAtt);
            $export .= implode('|', $instructorDocsAtt);
            $export .= "\n";

            //Variáveis de Encino do Professor
            $instructorVariables = InstructorVariableData::model()->findByPk($instructor->id);
            $export .= implode('|', $instructorVariables->attributes);
            $export .= "\n";
            //$export .= "50|\n";

            //Dados de Docência do Professor
            $criteria->select = 't.*';
            $criteria->condition = 't.instructor_fk = :value';
            $criteria->params = array(":value" => $instructor->id);
            $instructorTeachingDatas = InstructorTeachingData::model()->findAll($criteria);
            foreach ($instructorTeachingDatas as $itd) {
                $attributes = $itd->attributes;
                //Remove fkid
                array_pop($attributes);
                //Remove Id
                array_pop($attributes);
                $export .= implode('|', $attributes);
                $export .= "\n";
            }
        }

        //Identificação do Aluno
        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->join = 'LEFT JOIN student_enrollment se ON se.student_fk = t.id ';
        $criteria->join .= 'LEFT JOIN classroom c ON c.id = se.classroom_fk';
        $criteria->condition = 'c.school_year = :value '
            . 'AND t.send_year <= :year';
        $criteria->params = array(":value" => date('Y'), ":year" => date('Y'));
        $criteria->group = 't.id';
        $students = StudentIdentification::model()->findAll($criteria);
        foreach ($students as $key => $student) {
            $attributes = $student->attributes;
            // Remove fkid
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            // Remove
            array_pop($attributes);
            $export .= implode('|', $attributes);
            $export .= "\n";

            //Documentos do Aluno
            $studentDocs = StudentDocumentsAndAddress::model()->findByPk($student->id);
            $attributes = $studentDocs->attributes;
            // Remove
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            // Remove
            array_pop($attributes);;
            $export .= implode('|', $attributes);
            $export .= "\n";

            //Matricula do Aluno
            $criteria->select = 't.*';
            $criteria->condition = 't.student_fk = :value';
            $criteria->params = array(":value" => $student->id);
            $enrollments = StudentEnrollment::model()->findAll($criteria);
            foreach ($enrollments as $enrollment) {
                $attributes = $enrollment->attributes;
                //Remove create_time
                array_pop($attributes);
                array_pop($attributes);
                array_pop($attributes);
                array_pop($attributes);
                array_pop($attributes);
                array_pop($attributes);
                //Remove Id
                array_pop($attributes);
                $export .= implode('|', $attributes);
                $export .= "\n";
            }
        }

        $fileDir = Yii::app()->basePath . '/export/' . date('Y_') . Yii::app()->user->school . '.TXT';

        Yii::import('ext.FileManager.fileManager');
        $fm = new fileManager();
        $result = $fm->write($fileDir, $export);

        if ($result) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Exportação Concluida com Sucesso.<br><a href="/admin/downloadexportfile" class="btn btn-mini" target="_blank"><i class="icon-download-alt"></i>Clique aqui para fazer o Download do arquivo de exportação!!!</a>'));
        } else {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Houve algum erro na Exportação.'));
        }

        $this->render('index');
    }

    public function actionDownloadExportFile()
    {
        $fileDir = Yii::app()->basePath . '/export/' . date('Y_') . Yii::app()->user->school . '.TXT';
        if (file_exists($fileDir)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($fileDir) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileDir));
            readfile($fileDir);
        } else {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Arquivo de exportação não encontrado!!! Tente exportar novamente.'));
            $this->render('index');
        }

    }

    public function actionCreateUser()
    {
        $model = new Users;

        if (isset($_POST['Users'], $_POST['Confirm'])) {
            $model->attributes = $_POST['Users'];
            if ($model->validate()) {
                $password = md5($_POST['Users']['password']);
                $confirm = md5($_POST['Confirm']);
                if ($password == $confirm) {
                    $model->password = $password;
                    // form inputs are valid, do something here
                    if ($model->save()) {
                        $save = true;
                        foreach ($_POST['schools'] as $school) {
                            $userSchool = new UsersSchool;
                            $userSchool->user_fk = $model->id;
                            $userSchool->school_fk = $school;
                            $save = $save && $userSchool->validate() && $userSchool->save();
                        }
                        if ($save) {
                            $auth = Yii::app()->authManager;
                            $auth->assign($_POST['Role'], $model->id);
                            Yii::app()->user->setFlash('success', Yii::t('default', 'Usuário cadastrado com sucesso!'));
                            $this->redirect(array('index'));
                        }
                    }
                } else {
                    $model->addError('password', Yii::t('default', 'Confirm Password') . ': ' . Yii::t('help', 'Confirm'));
                }
            }
        }
        $this->render('createUser', array('model' => $model));
    }

    public function addTestUsers()
    {
        set_time_limit(0);
        ignore_user_abort();
        $admin_login = 'admin';
        $admin_password = md5('p@s4ipti');

        $command = "INSERT INTO `users`VALUES
                        (1, 'Administrador', '$admin_login', '$admin_password', 1);";
        Yii::app()->db->createCommand($command)->query();

        $auth = Yii::app()->authManager;
        $auth->assign('admin', 1);

//        //Criar usuário de teste, remover depois.
//        /*         * ************************************************************************************************ */
//        /**/$command = "INSERT INTO `users`VALUES"
//                /**/ . "(2, 'Paulo Roberto', 'paulones', 'e10adc3949ba59abbe56e057f20f883e', 1);"
//                /**/ . "INSERT INTO `users_school` (`id`, `school_fk`, `user_fk`) VALUES (1, '28025911', 2);"
//                /**/ . "INSERT INTO `users_school` (`id`, `school_fk`, `user_fk`) VALUES (2, '28025970', 2);"
//                /**/ . "INSERT INTO `users_school` (`id`, `school_fk`, `user_fk`) VALUES (3, '28025989', 2);"
//                /**/ . "INSERT INTO `users_school` (`id`, `school_fk`, `user_fk`) VALUES (4, '28026012', 2);";
//        /**/Yii::app()->db->createCommand($command)->query();
//        /*         * ************************************************************************************************ */
    }

    public function actionClearDB()
    {
        $command = "
            delete from auth_assignment;

            delete from users_school;
            delete from users;

            delete from class_board;
            delete from class_faults;
            delete from class;

            delete from student_enrollment;
            delete from student_identification;
            delete from student_documents_and_address;

            delete from instructor_teaching_data;
            delete from instructor_identification;
            delete from instructor_documents_and_address;
            delete from instructor_variable_data;

            delete from classroom;

            delete from school_identification;
            delete from school_structure;";

        set_time_limit(0);
        ignore_user_abort();
        Yii::app()->db->createCommand($command)->query();

        $this->addTestUsers();

        Yii::app()->user->setFlash('success', Yii::t('default', 'Banco limpado com sucesso. <br/>Faça o login novamente para atualizar os dados.'));
        $this->redirect(array('index'));
    }

    public function actionImport()
    {
        set_time_limit(0);
        ignore_user_abort();
        $path = Yii::app()->basePath;
        //Se não passar parametro, o valor será predefinido
        if (empty($_FILES['file']['name'])) {
            $fileDir = $path . '/import/IMPORTAR.TXT';
        } else {
            $myfile = $_FILES['file'];
            $uploadfile = $path . '/import/' . basename($myfile['name']);
            move_uploaded_file($myfile['tmp_name'], $uploadfile);
            $fileDir = $uploadfile;
        }


        $mode = 'r';

        //Abre o arquivo
        $file = fopen($fileDir, $mode);
        if ($file == false) {
            die('O arquivo não existe.');
        }

        $registerLines = [];

        //Inicializa o contador de linhas
        $lineCount = [];
        $lineCount['00'] = 0;
        $lineCount['10'] = 0;
        $lineCount['20'] = 0;
        $lineCount['30'] = 0;
        $lineCount['40'] = 0;
        $lineCount['50'] = 0;
        $lineCount['51'] = 0;
        $lineCount['60'] = 0;
        $lineCount['70'] = 0;
        $lineCount['80'] = 0;

        //Pega campos do arquivo
        while (true) {
            //Próxima linha do arquivo
            $fileLine = fgets($file);
            if ($fileLine == null)
                break;

            //Tipo do registro são os 2 primeiros caracteres
            $regType = $fileLine[0] . $fileLine[1];
            //Querba a linha nos caracteres |
            $lineFields_Aux = explode("|", $fileLine);
            $lineFields = [];

            //Troca os campos vazios por 'null'
            foreach ($lineFields_Aux as $key => $field) {
                $value = empty($field) ? 'null' : $field;
                $lineFields[$key] = $value;
            }

            //passa os campos do arquivo para a matriz [tipo][linha][coluna]
            $registerLines[$regType][$lineCount[$regType]++] = $lineFields;
        }
        //Pega os valores para preencher o insert.
        $values = $this->getInsertValues($registerLines);
        $instructorInepIds = $values['instructor'];
        $insertValue = $values['insert'];

        //Pega o código SQL com os valores passados
        $str_fields = $this->getInsertSQL($insertValue);

        set_time_limit(0);
        ignore_user_abort();
        Yii::app()->db->createCommand(utf8_encode($str_fields['00']))->query();
        Yii::app()->db->createCommand(utf8_encode($str_fields['10']))->query();
        Yii::app()->db->createCommand(utf8_encode($str_fields['20']))->query();
        Yii::app()->db->createCommand(utf8_encode($str_fields['30']))->query();
        Yii::app()->db->createCommand(utf8_encode($str_fields['40']))->query();
//        Solução paliativa para evitar explosão
//        Yii::app()->db->createCommand(utf8_encode($str_fields['50']))->query();
        Yii::app()->db->createCommand(utf8_encode($str_fields['51']))->query();
        Yii::app()->db->createCommand(utf8_encode($str_fields['60']))->query();
        Yii::app()->db->createCommand(utf8_encode($str_fields['80']))->query();
        Yii::app()->db->createCommand(utf8_encode($str_fields['70']))->query();
        fclose($file);
        set_time_limit(30);


        Yii::app()->user->setFlash('success', Yii::t('default', 'Arquivo do Educacenso importado com sucesso. <br/>Faça o login novamente para atualizar os dados.'));
        $this->redirect(array('index'));
    }

    public function actionACL()
    {
        $auth = Yii::app()->authManager;

        $auth->createOperation('createSchool', 'create a School');
        $auth->createOperation('updateSchool', 'update a School');
        $auth->createOperation('deleteSchool', 'delete a School');

        $auth->createOperation('createClassroom', 'create a classrrom');
        $auth->createOperation('updateClassroom', 'update a Classroom');
        $auth->createOperation('deleteClassroom', 'delete a Classroom');

        $auth->createOperation('createStudent', 'create a Student');
        $auth->createOperation('updateStudent', 'update a Student');
        $auth->createOperation('deleteStudent', 'delete a Student');

        $auth->createOperation('createInstructor', 'create a Instructor');
        $auth->createOperation('updateInstructor', 'update a Instructor');
        $auth->createOperation('deleteInstructor', 'delete a Instructor');

        $auth->createOperation('createEnrollment', 'create a Enrollment');
        $auth->createOperation('updateEnrollment', 'update a Enrollment');
        $auth->createOperation('deleteEnrollment', 'delete a Enrollment');

        $auth->createOperation('updateClasses', 'update a Classes');

        $auth->createOperation('generateBFReport', 'generate BFReport');


        $role = $auth->createRole('manager');
        $role->addChild('createClassroom');
        $role->addChild('updateClassroom');
        $role->addChild('deleteClassroom');

        $role->addChild('createStudent');
        $role->addChild('updateStudent');
        $role->addChild('deleteStudent');

        $role->addChild('createInstructor');
        $role->addChild('updateInstructor');
        $role->addChild('deleteInstructor');

        $role->addChild('createEnrollment');
        $role->addChild('updateEnrollment');
        $role->addChild('deleteEnrollment');

        $role->addChild('updateClasses');

        $role->addChild('generateBFReport');

        $role->addChild('updateSchool');


        $role = $auth->createRole('admin');
        $role->addChild('manager');
        $role->addChild('createSchool');
        $role->addChild('deleteSchool');


        Yii::app()->user->setFlash('success', Yii::t('default', 'ACL configurada com sucesso.'));
        $this->redirect(array('index'));
    }

    //Retorna uma Array com 2 arrays. InsertValue e InstructorInepID
    /**
     * Transforma as linhas em valores a serem inseridos no SQL
     *
     * @param type $registerLines
     * @return array ['insert'] = values do SQL; ['instructor'] = INEPID dos instrutores
     */
    private function getInsertValues($registerLines)
    {
        foreach ($registerLines as $regType => $lines):
            $insertValue[$regType] = "";

            $totalLines = count($lines) - 1;

            $isSchoolIdentification = ($regType == "00");
            $isSchoolStructure = ($regType == "10");

            $isRegInstructorIdentification = ($regType == "30");
            $isRegInstructorDocumentsAndAddress = ($regType == "40");
            $isRegInstructorVariableData = ($regType == "50");
            $isRegInstructorTeachingData = ($regType == "51");

            $isStudentIdentification = ($regType == "60");
            $isStudentDocumentsAndAddress = ($regType == "70");
            $isStudentEnrollment = ($regType == "80");
            if ($isRegInstructorIdentification) {
                $instructorInepIds[] = '';
            }
            for ($line = 0; $line <= $totalLines; $line++) {
                $totalColumns = count($lines[$line]) - 2;
                for ($column = 0; $column <= $totalColumns; $column++) {
                    $value = $lines[$line][$column];
                    $withoutcomma = false;

                    if ($column == 0) {
                        $insertValue[$regType] .= "(";
                    } else if ($regType != 00 && $regType != 10 && $regType != 51 && $regType != 80 && $column == 3) {
                        $value = "null";
                    } else if ($regType == 20 && $column == 4) {
                        $value = str_replace("º", "", $value);
                    } else {
                        if ($regType == '51' && $column == 3) {
                            $withoutcomma = true;
                            $value = "(SELECT id FROM instructor_identification WHERE BINARY inep_id = BINARY " . $lines[$line][2] . " LIMIT 0,1)";
                        } else if ($regType == '51' && $column == 5) {
                            $withoutcomma = true;
                            $value = "(SELECT id FROM classroom WHERE BINARY inep_id = BINARY " . $lines[$line][4] . " LIMIT 0,1)";
                        } else if ($regType == '80' && $column == 3) {
                            $withoutcomma = true;
                            $value = "(SELECT id FROM student_identification WHERE BINARY inep_id = BINARY " . $lines[$line][2] . " LIMIT 0,1)";
                        } else if ($regType == '80' && $column == 5) {
                            $withoutcomma = true;
                            $value = "(SELECT id FROM classroom WHERE BINARY inep_id = BINARY " . $lines[$line][4] . " LIMIT 0,1)";
                        }
                    }

                    if ($isSchoolIdentification && $column == 19) {
                        $value = $value[7] . $value[8];
                    }

                    if ($isRegInstructorIdentification) {
                        if ($column == 2) {
                            $instructorInepIds[$line] = $value;
                        }
                        if ($column == 10) {
                            $withoutcomma = true;
                            $value = "'0','" . $value . "',null";
                        }
                    }

                    if ($isRegInstructorTeachingData && $totalColumns == 19) {
                        $lines[$line][sizeof($lines[$line]) - 1] = 'null';
                        $totalColumns++;
                    }

                    if (($isSchoolStructure && $totalColumns == 105)
                        || ($isRegInstructorIdentification && $totalColumns == 22)
                        || ($isRegInstructorDocumentsAndAddress && $totalColumns == 11)
                        || ($isStudentIdentification && $totalColumns == 37)
                        || ($isStudentEnrollment && $totalColumns == 22)
                        || ($isStudentDocumentsAndAddress && $totalColumns == 27)
                    ) {
                        $lines[$line][sizeof($lines[$line])] = 'null';
                        $totalColumns++;
                    }


                    $value = ($value == 'null' || $withoutcomma) ? $value : "'$value'";
                    if ($column + 1 > $totalColumns) {
                        if ($regType == 20) {
                            $value .= ',' . date("Y");
                        }
                        $insertValue[$regType] .= $value;
                        $insertValue[$regType] .= ($line == $totalLines) ? ")" : "),\n";

                    } else {
                        $insertValue[$regType] .= $value . ", ";
                    }
                }
            }
        endforeach;
        $return = array('insert' => $insertValue, 'instructor' => $instructorInepIds);
        return $return;
    }

    private
    function getInsertSQL($insertValue)
    {
        $str_fields = [];
        foreach ($insertValue as $regType => $lines):
            switch ($regType) {
                case '00': {
                    $str_fields[$regType] = "INSERT INTO `school_identification`(`register_type`,`inep_id`,`manager_cpf`,`manager_name`,`manager_role`,`manager_email`,`situation`,`initial_date`,`final_date`,`name`,`latitude`,`longitude`,`cep`,`address`,`address_number`,`address_complement`,`address_neighborhood`,`edcenso_uf_fk`,`edcenso_city_fk`,`edcenso_district_fk`,`ddd`,`phone_number`,`public_phone_number`,`other_phone_number`,`fax_number`,`email`,`edcenso_regional_education_organ_fk`,`administrative_dependence`,`location`,`private_school_category`,`public_contract`,`private_school_business_or_individual`,`private_school_syndicate_or_association`,`private_school_ong_or_oscip`,`private_school_non_profit_institutions`,`private_school_s_system`,`private_school_maintainer_cnpj`,`private_school_cnpj`,`offer_or_linked_unity`,`inep_head_school`,`ies_code`) VALUES " . $lines . " ON DUPLICATE KEY UPDATE register_type = register_type;";
                    break;
                }
                case '10': {
                    $str_fields[$regType] = "INSERT INTO `school_structure`(`register_type`,`school_inep_id_fk`,`operation_location_building`,`operation_location_temple`,`operation_location_businness_room`,`operation_location_instructor_house`,`operation_location_other_school_room`,`operation_location_barracks`,`operation_location_socioeducative_unity`,`operation_location_prison_unity`,`operation_location_other`,`building_occupation_situation`,`shared_building_with_school`,`shared_school_inep_id_1`,`shared_school_inep_id_2`,`shared_school_inep_id_3`,`shared_school_inep_id_4`,`shared_school_inep_id_5`,`shared_school_inep_id_6`,`consumed_water_type`,`water_supply_public`,`water_supply_artesian_well`,`water_supply_well`,`water_supply_river`,`water_supply_inexistent`,`energy_supply_public`,`energy_supply_generator`,`energy_supply_other`,`energy_supply_inexistent`,`sewage_public`,`sewage_fossa`,`sewage_inexistent`,`garbage_destination_collect`,`garbage_destination_burn`,`garbage_destination_throw_away`,`garbage_destination_recycle`,`garbage_destination_bury`,`garbage_destination_other`,`dependencies_principal_room`,`dependencies_instructors_room`,`dependencies_secretary_room`,`dependencies_info_lab`,`dependencies_science_lab`,`dependencies_aee_room`,`dependencies_indoor_sports_court`,`dependencies_outdoor_sports_court`,`dependencies_kitchen`,`dependencies_library`,`dependencies_reading_room`,`dependencies_playground`,`dependencies_nursery`,`dependencies_outside_bathroom`,`dependencies_inside_bathroom`,`dependencies_child_bathroom`,`dependencies_prysical_disability_bathroom`,`dependencies_physical_disability_support`,`dependencies_bathroom_with_shower`,`dependencies_refectory`,`dependencies_storeroom`,`dependencies_warehouse`,`dependencies_auditorium`,`dependencies_covered_patio`,`dependencies_uncovered_patio`,`dependencies_student_accomodation`,`dependencies_instructor_accomodation`,`dependencies_green_area`,`dependencies_laundry`,`dependencies_none`,`classroom_count`,`used_classroom_count`,`equipments_tv`,`equipments_vcr`,`equipments_dvd`,`equipments_satellite_dish`,`equipments_copier`,`equipments_overhead_projector`,`equipments_printer`,`equipments_stereo_system`,`equipments_data_show`,`equipments_fax`,`equipments_camera`,`equipments_computer`,`equipments_multifunctional_printer`,`administrative_computers_count`,`student_computers_count`,`internet_access`,`bandwidth`,`employees_count`,`feeding`,`aee`,`complementary_activities`,`modalities_regular`,`modalities_especial`,`modalities_eja`,`modalities_professional`,`basic_education_cycle_organized`,`different_location`,`sociocultural_didactic_material_none`,`sociocultural_didactic_material_quilombola`,`sociocultural_didactic_material_native`,`native_education`,`native_education_language_native`,`native_education_language_portuguese`,`edcenso_native_languages_fk`,`brazil_literate`,`open_weekend`,`pedagogical_formation_by_alternance`) VALUES " . $lines . " ON DUPLICATE KEY UPDATE register_type = register_type;";
                    break;
                }
                case '20': {
                    $str_fields[$regType] = "INSERT INTO `classroom`(`register_type`,`school_inep_fk`,`inep_id`,`id`,`name`,`pedagogical_mediation_type`,`initial_hour`,`initial_minute`,`final_hour`,`final_minute`,`week_days_sunday`,`week_days_monday`,`week_days_tuesday`,`week_days_wednesday`,`week_days_thursday`,`week_days_friday`,`week_days_saturday`,`assistance_type`,`mais_educacao_participator`,`complementary_activity_type_1`,`complementary_activity_type_2`,`complementary_activity_type_3`,`complementary_activity_type_4`,`complementary_activity_type_5`,`complementary_activity_type_6`,`aee_braille_system_education`,`aee_optical_and_non_optical_resources`,`aee_mental_processes_development_strategies`,`aee_mobility_and_orientation_techniques`,`aee_libras`,`aee_caa_use_education`,`aee_curriculum_enrichment_strategy`,`aee_soroban_use_education`,`aee_usability_and_functionality_of_computer_accessible_education`,`aee_teaching_of_Portuguese_language_written_modality`,`aee_strategy_for_school_environment_autonomy`,`modality`,`edcenso_stage_vs_modality_fk`,`edcenso_professional_education_course_fk`,`discipline_chemistry`,`discipline_physics`,`discipline_mathematics`,`discipline_biology`,`discipline_science`,`discipline_language_portuguese_literature`,`discipline_foreign_language_english`,`discipline_foreign_language_spanish`,`discipline_foreign_language_franch`,`discipline_foreign_language_other`,`discipline_arts`,`discipline_physical_education`,`discipline_history`,`discipline_geography`,`discipline_philosophy`,`discipline_social_study`,`discipline_sociology`,`discipline_informatics`,`discipline_professional_disciplines`,`discipline_special_education_and_inclusive_practices`,`discipline_sociocultural_diversity`,`discipline_libras`,`discipline_pedagogical`,`discipline_religious`,`discipline_native_language`,`discipline_others`,`school_year`) VALUES " . $lines . " ON DUPLICATE KEY UPDATE register_type = register_type;";
                    break;
                }
                case '30': {
                    $str_fields[$regType] = "INSERT INTO `instructor_identification`(`register_type`,`school_inep_id_fk`,`inep_id`,`id`,`name`,`email`,`nis`,`birthday_date`,`sex`,`color_race`,`filiation`,`filiation_1`,`filiation_2`,`nationality`,`edcenso_nation_fk`,`edcenso_uf_fk`,`edcenso_city_fk`,`deficiency`,`deficiency_type_blindness`,`deficiency_type_low_vision`,`deficiency_type_deafness`,`deficiency_type_disability_hearing`,`deficiency_type_deafblindness`,`deficiency_type_phisical_disability`,`deficiency_type_intelectual_disability`,`deficiency_type_multiple_disabilities`) VALUES " . $lines . " ON DUPLICATE KEY UPDATE register_type = register_type;";
                    break;
                }
                case '40': {
                    $str_fields[$regType] = "INSERT INTO `instructor_documents_and_address`(`register_type`,`school_inep_id_fk`,`inep_id`,`id`,`cpf`,`area_of_residence`,`cep`,`address`,`address_number`,`complement`,`neighborhood`,`edcenso_uf_fk`,`edcenso_city_fk`) VALUES " . $lines . " ON DUPLICATE KEY UPDATE register_type = register_type;";
                    break;
                }
                case '50': {
                    $str_fields[$regType] = "INSERT INTO `instructor_variable_data`(`register_type`,`school_inep_id_fk`,`inep_id`,`id`,`scholarity`,`high_education_situation_1`,`high_education_formation_1`,`high_education_course_code_1_fk`,`high_education_initial_year_1`,`high_education_final_year_1`,`high_education_institution_code_1_fk`,`high_education_situation_2`,`high_education_formation_2`,`high_education_course_code_2_fk`,`high_education_initial_year_2`,`high_education_final_year_2`,`high_education_institution_code_2_fk`,`high_education_situation_3`,`high_education_formation_3`,`high_education_course_code_3_fk`,`high_education_initial_year_3`,`high_education_final_year_3`,`high_education_institution_code_3_fk`,`post_graduation_specialization`,`post_graduation_master`,`post_graduation_doctorate`,`post_graduation_none`,`other_courses_nursery`,`other_courses_pre_school`,`other_courses_basic_education_initial_years`,`other_courses_basic_education_final_years`,`other_courses_high_school`,`other_courses_education_of_youth_and_adults`,`other_courses_special_education`,`other_courses_native_education`,`other_courses_field_education`,`other_courses_environment_education`,`other_courses_human_rights_education`,`other_courses_sexual_education`,`other_courses_child_and_teenage_rights`,`other_courses_ethnic_education`,`other_courses_other`,`other_courses_none`) VALUES " . $lines . " ON DUPLICATE KEY UPDATE register_type = register_type;";
                    break;
                }

                case '51': {
                    $str_fields[$regType] = "INSERT INTO `instructor_teaching_data`(`register_type`,`school_inep_id_fk`,`instructor_inep_id`,`instructor_fk`,`classroom_inep_id`,`classroom_id_fk`,`role`,`contract_type`,`discipline_1_fk`,`discipline_2_fk`,`discipline_3_fk`,`discipline_4_fk`,`discipline_5_fk`,`discipline_6_fk`,`discipline_7_fk`,`discipline_8_fk`,`discipline_9_fk`,`discipline_10_fk`,`discipline_11_fk`,`discipline_12_fk`,`discipline_13_fk`) VALUES " . $lines . " ON DUPLICATE KEY UPDATE register_type = register_type;";
                    break;
                }
                case '60': {
                    $str_fields[$regType] = "INSERT INTO `student_identification`(`register_type`,`school_inep_id_fk`,`inep_id`,`id`,`name`,`birthday`,`sex`,`color_race`,`filiation`,`filiation_1`,`filiation_2`,`nationality`,`edcenso_nation_fk`,`edcenso_uf_fk`,`edcenso_city_fk`,`deficiency`,`deficiency_type_blindness`,`deficiency_type_low_vision`,`deficiency_type_deafness`,`deficiency_type_disability_hearing`,`deficiency_type_deafblindness`,`deficiency_type_phisical_disability`,`deficiency_type_intelectual_disability`,`deficiency_type_multiple_disabilities`,`deficiency_type_autism`,`deficiency_type_aspenger_syndrome`,`deficiency_type_rett_syndrome`,`deficiency_type_childhood_disintegrative_disorder`,`deficiency_type_gifted`,`resource_aid_lector`,`resource_aid_transcription`,`resource_interpreter_guide`,`resource_interpreter_libras`,`resource_lip_reading`,`resource_zoomed_test_16`,`resource_zoomed_test_20`,`resource_zoomed_test_24`,`resource_braille_test`,`resource_none`) VALUES " . $lines . " ON DUPLICATE KEY UPDATE register_type = register_type;";
                    break;
                }
                case '70': {
                    $str_fields[$regType] = "INSERT INTO `student_documents_and_address`(`register_type`,`school_inep_id_fk`,`student_fk`,`id`,`rg_number`,`rg_number_edcenso_organ_id_emitter_fk`,`rg_number_edcenso_uf_fk`,`rg_number_expediction_date`,`civil_certification`,`civil_certification_type`,`civil_certification_term_number`,`civil_certification_sheet`,`civil_certification_book`,`civil_certification_date`,`notary_office_uf_fk`,`notary_office_city_fk`,`edcenso_notary_office_fk`,`civil_register_enrollment_number`,`cpf`,`foreign_document_or_passport`,`nis`,`residence_zone`,`cep`,`address`,`number`,`complement`,`neighborhood`,`edcenso_uf_fk`,`edcenso_city_fk`) VALUES " . $lines . " ON DUPLICATE KEY UPDATE register_type = register_type;";
                    break;
                }
                case '80': {
                    $str_fields[$regType] = "INSERT INTO student_enrollment (`register_type`,`school_inep_id_fk`,`student_inep_id`,`student_fk`,`classroom_inep_id`,`classroom_fk`,`enrollment_id`,`unified_class`,`edcenso_stage_vs_modality_fk`,`another_scholarization_place`,`public_transport`,`transport_responsable_government`,`vehicle_type_van`,`vehicle_type_microbus`,`vehicle_type_bus`,`vehicle_type_bike`,`vehicle_type_animal_vehicle`,`vehicle_type_other_vehicle`,`vehicle_type_waterway_boat_5`,`vehicle_type_waterway_boat_5_15`,`vehicle_type_waterway_boat_15_35`,`vehicle_type_waterway_boat_35`,`vehicle_type_metro_or_train`,`student_entry_form`) VALUES " . $lines . " ON DUPLICATE KEY UPDATE register_type = register_type;";
                    break;
                }
            }
        endforeach;
        return $str_fields;
    }

    public function actionExportToMaster()
    {
        $importToFile = false;
        try {
            Yii::app()->db2;
        } catch (Exception $e) {
            $importToFile = true;
        }

        ini_set('memory_limit', '256M');

        $sql = "";

        $tables = array("school_identification", "school_structure", "classroom",
            "instructor_identification", "instructor_documents_and_address", "instructor_variable_data",
            "instructor_teaching_data", "student_identification", "student_documents_and_address", "student_enrollment");

        for ($i = 0; $i < count($tables); $i++) {
            $array = array();
            $objects = "";
            switch ($i) {
                case "0":
                    $objects = SchoolIdentification::model()->findAll();
                    break;
                case "1":
                    $objects = SchoolStructure::model()->findAll();
                    break;
                case "2":
                    $objects = Classroom::model()->findAll();
                    break;
                case "3":
                    $objects = InstructorIdentification::model()->findAll();
                    break;
                case "4":
                    $objects = InstructorDocumentsAndAddress::model()->findAll();
                    break;
                case "5":
                    $objects = InstructorVariableData::model()->findAll();
                    break;
                case "6":
                    $objects = InstructorTeachingData::model()->findAll();
                    break;
                case "7":
                    $objects = StudentIdentification::model()->findAll();
                    break;
                case "8":
                    $objects = StudentDocumentsAndAddress::model()->findAll();
                    break;
                case "9":
                    $objects = StudentEnrollment::model()->findAll();
                    break;
            }
            foreach ($objects as $object) {
                if ($i == 0) { //remover atributo blob do school_identification
                    $object->logo_file_content = "";
                }
                array_push($array, $object->attributes);
            }
            $sql .= "INSERT INTO $tables[$i]";
            $sql .= " (`" . implode("`, `", array_keys($array[0])) . "`) VALUES";
            foreach ($array as $value) {
                $sql .= " ('" . implode("', '", $value) . "'),";
            }
            $sql = substr($sql, 0, -1) . ";";
        }

        if ($importToFile) {
            ini_set('memory_limit', '128M');
            $fileName = "./app/export/exportSQL " . date("Y-m-d") . ".sql";
            $file = fopen($fileName, "w");
            fwrite($file, $sql);
            fclose($file);
            header("Content-Disposition: attachment; filename=\"" . basename($fileName) . "\"");
            header("Content-Type: application/force-download");
            header("Content-Length: " . filesize($fileName));
            header("Connection: close");

            $file = fopen($fileName, "r");
            fpassthru($file);
            fclose($file);
            unlink($fileName);
            return;
        } else {
            yii::app()->db2->schema->commandBuilder->createSqlCommand($sql)->query();
            ini_set('memory_limit', '128M');
            $this->redirect(array('index'));
        }
    }

    public function actionCleanMaster()
    {
        $schoolSql = "select s1.* from school_identification as s1 join school_identification as s2 on (s1.name = s2.name and s1.cep = s2.cep and s1.inep_id != s2.inep_id)";
        $schools = Yii::app()->db->schema->commandBuilder->createSqlCommand($schoolSql)->query();

    }

    public function importFromMaster()
    {

    }
}

?>
