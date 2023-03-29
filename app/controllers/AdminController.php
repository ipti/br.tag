<?php

class AdminController extends Controller
{
    public $layout = 'fullmenu';

    public function accessRules()
    {
        return [
            [
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['CreateUser', 'index', 'conflicts'], 'users' => ['*'],
            ], [
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => [
                    'import', 'export', 'update', 'manageUsers', 'clearDB', 'acl', 'backup', 'data', 'exportStudentIdentify', 'syncExport',
                    'syncImport', 'exportToMaster', 'clearMaster', 'importFromMaster', 'gradesStructure'
                ], 'users' => ['@'],
            ],
        ];
    }

    /**
     * Show the Index Page.
     */
    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionCreateUser()
    {
        $model = new Users();

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
                            $userSchool = new UsersSchool();
                            $userSchool->user_fk = $model->id;
                            $userSchool->school_fk = $school;
                            $save = $save && $userSchool->validate() && $userSchool->save();
                        }
                        if ($save) {
                            $auth = Yii::app()->authManager;
                            $auth->assign($_POST['Role'], $model->id);
                            Yii::app()->user->setFlash('success', Yii::t('default', 'Usuário cadastrado com sucesso!'));
                            $this->redirect(['index']);
                        }
                    }
                } else {
                    $model->addError('password', Yii::t('default', 'Confirm Password') . ': ' . Yii::t('help', 'Confirm'));
                }
            }
        }
        $this->render('createUser', ['model' => $model]);
    }

    public function actionGradesStructure()
    {
        $stages = Yii::app()->db->createCommand("select distinct esvm.id, esvm.name from edcenso_stage_vs_modality esvm join curricular_matrix cm on cm.stage_fk = esvm.id where school_year = :year order by esvm.name")->bindParam(":year", Yii::app()->user->year)->queryAll();
        $formulas = GradeCalculation::model()->findAll();
        $gradeUnity = new GradeUnity();
        $this->render('gradesStructure', [
            "gradeUnity" => $gradeUnity,
            "stages" => $stages,
            "formulas" => $formulas
        ]);
    }

    public function actionGetUnities()
    {
        $stage = EdcensoStageVsModality::model()->find("id = :id", [":id" => $_POST["stage"]])->stage;
        switch ($stage) {
            case 1:
                $result["stageName"] = "Educação Infantil";
                break;
            case 2:
                $result["stageName"] = "Ensino Fundamental Menor (Anos Iniciais)";
                break;
            case 3:
            case 7:
                $result["stageName"] = "Ensino Fundamental Maior (Anos Finais)";
                break;
            case 6:
                $result["stageName"] = "Educação de Jovens e Adultos (EJA)";
                break;
            default:
                $result["stageName"] = "a modalidade selecionada";
                break;
        }
        $result["unities"] = [];
        $gradeUnities = GradeUnity::model()->findAll("edcenso_stage_vs_modality_fk = :stage", [":stage" => $_POST["stage"]]);
        foreach ($gradeUnities as $gradeUnity) {
            $arr = $gradeUnity->attributes;
            $arr["modalities"] = [];
            foreach ($gradeUnity->gradeUnityModalities as $gradeUnityModality) {
                array_push($arr["modalities"], $gradeUnityModality->attributes);
            }
            array_push($result["unities"], $arr);
        }
        echo json_encode($result);
    }

    public function actionSaveUnities()
    {
        $valid = false;
        if ($_POST["reply"] == "") {
            $grades = Yii::app()->db->createCommand("
                select * from grade g 
                join grade_unity_modality gum on g.grade_unity_modality_fk = gum.id
                join grade_unity gu on gu.id = gum.grade_unity_fk
                where edcenso_stage_vs_modality_fk = :stage
            ")->bindParam(":stage", $_POST["stage"])->queryAll();
            if ($grades == null) {
                GradeUnity::model()->deleteAll("edcenso_stage_vs_modality_fk = :stage", [":stage" => $_POST["stage"]]);
                foreach ($_POST["unities"] as $u) {
                    $unity = new GradeUnity();
                    $unity->edcenso_stage_vs_modality_fk = $_POST["stage"];
                    $unity->name = $u["name"];
                    $unity->type = $u["type"];
                    $unity->grade_calculation_fk = $u["formula"];
                    $unity->save();
                    foreach ($u["modalities"] as $m) {
                        $modality = new GradeUnityModality();
                        $modality->name = $m["name"];
                        $modality->type = $m["type"];
                        $modality->grade_unity_fk = $unity->id;
                        $modality->save();
                    }
                }
                $valid = true;
            }
        } else {
            if ($_POST["reply"] == "A") {
                $grades = Yii::app()->db->createCommand("select * from grade")->queryAll();
            } else if ($_POST["reply"] == "S") {
                $stage = EdcensoStageVsModality::model()->find("id = :id", [":id" => $_POST["stage"]])->stage;
                $grades = Yii::app()->db->createCommand("
                    select * from grade g
                    join grade_unity_modality gum on g.grade_unity_modality_fk = gum.id
                    join grade_unity gu on gu.id = gum.grade_unity_fk
                    join edcenso_stage_vs_modality esvm on esvm.id = gu.edcenso_stage_vs_modality_fk
                    where esvm.stage = :stage
                ")->bindParam(":stage", $stage)->queryAll();
            }
            if ($grades == null) {
                if ($_POST["reply"] == "A") {
                    $curricularMatrixes = Yii::app()->db->createCommand("select * from curricular_matrix cm where school_year = :year")->bindParam(":year", Yii::app()->user->year)->queryAll();
                } else if ($_POST["reply"] == "S") {
                    $curricularMatrixes = Yii::app()->db->createCommand("
                    select * from curricular_matrix cm 
                    join edcenso_stage_vs_modality esvm on esvm.id = cm.stage_fk
                    where school_year = :year and esvm.stage = :stage
                  ")->bindParam(":year", Yii::app()->user->year)->bindParam(":stage", $stage)->queryAll();
                }
                foreach ($curricularMatrixes as $curricularMatrix) {
                    GradeUnity::model()->deleteAll("edcenso_stage_vs_modality_fk = :stage", [":stage" => $curricularMatrix["stage_fk"]]);
                    foreach ($_POST["unities"] as $u) {
                        $unity = new GradeUnity();
                        $unity->edcenso_stage_vs_modality_fk = $curricularMatrix["stage_fk"];
                        $unity->name = $u["name"];
                        $unity->type = $u["type"];
                        $unity->grade_calculation_fk = $u["formula"];
                        $unity->save();
                        foreach ($u["modalities"] as $m) {
                            $modality = new GradeUnityModality();
                            $modality->name = $m["name"];
                            $modality->type = $m["type"];
                            $modality->grade_unity_fk = $unity->id;
                            $modality->save();
                        }
                    }
                }
                $valid = true;
            }
        }
        echo json_encode(["valid" => $valid]);
    }

    public function actionActiveDisableUser()
    {
        $filter = new Users('search');
        if (isset($_GET['Users'])) {
            $filter->attributes = $_GET['Users'];
        }
        $criteria = new CDbCriteria();
        $criteria->condition = "username != 'admin'";
        $dataProvider = new CActiveDataProvider('Users', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 12,
            )
        ));
        $this->render('activeDisableUser', ['dataProvider' => $dataProvider, 'filter' => $filter]);
    }

    public function actionDisableUser($id)
    {
        $model = Users::model()->findByPk($id);

        $model->active = 0;

        if ($model->save()) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Usuário desativado com sucesso!'));
            $this->redirect(['index']);
        }
    }

    public function actionActiveUser($id)
    {
        $model = Users::model()->findByPk($id);

        $model->active = 1;

        if ($model->save()) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Usuário ativado com sucesso!'));
            $this->redirect(['index']);
        }
    }

    public function actionEditPassword($id)
    {
        $model = Users::model()->findByPk($id);

        if (isset($_POST['Users'], $_POST['Confirm'])) {
            $password = md5($_POST['Users']['password']);
            $confirm = md5($_POST['Confirm']);
            if ($password == $confirm) {
                $model->password = $password;
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', Yii::t('default', 'Senha alterada com sucesso!'));
                    $this->redirect(['index']);
                }
            } else {
                $model->addError('password', Yii::t('default', 'Confirm Password') . ': ' . Yii::t('help', 'Confirm'));
            }
        }
        $this->render('editPassword', ['model' => $model]);
    }


    public function actionClearDB()
    {
        //delete from users_school;
        //delete from users;
        // delete from auth_assignment;

        $command = "
			SET FOREIGN_KEY_CHECKS=0;
			
			delete from auth_assignment;
			delete from users;
			delete from users_school;
			
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

    public function mres($value)
    {
        $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
        $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");

        return str_replace($search, $replace, $value);
    }

    public function actionImportMaster()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ignore_user_abort();
        $time1 = time();
        $path = Yii::app()->basePath;
        $uploadfile = $path . '/import/28031610.json';
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
        $json = unserialize($jsonSyncTag);
        $this->loadMaster($json);
    }

    public function loadMaster($loads)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        //ignore_user_abort();
        foreach ($loads['schools'] as $index => $scholl) {
            $saveschool = new SchoolIdentification();
            $saveschool->setDb2Connection(true);
            $saveschool->refreshMetaData();
            $saveschool = $saveschool->findByAttributes(array('inep_id' => $scholl['inep_id']));
            if (!isset($saveschool)) {
                $saveschool = new SchoolIdentification();
                $saveschool->setDb2Connection(true);
                $saveschool->refreshMetaData();
            }
            $saveschool->attributes = $scholl;
            $saveschool->save();
        }
        foreach ($loads['schools_structure'] as $index => $structure) {
            $saveschool = new SchoolStructure();
            $saveschool->setDb2Connection(true);
            $saveschool->refreshMetaData();
            $saveschool = $saveschool->findByAttributes(array('school_inep_id_fk' => $structure['school_inep_id_fk']));
            if (!isset($saveschool)) {
                $saveschool = new SchoolStructure();
                $saveschool->setDb2Connection(true);
                $saveschool->refreshMetaData();
            }
            $saveschool->attributes = $structure;
            $saveschool->save();
        }
        foreach ($loads['classrooms'] as $index => $class) {
            $saveclass = new Classroom();
            $saveclass->setScenario('search');
            $saveclass->setDb2Connection(true);
            $saveclass->refreshMetaData();
            $saveclass = $saveclass->findByAttributes(array('hash' => $class['hash']));
            if (!isset($saveclass)) {
                $saveclass = new Classroom();
                $saveclass->setScenario('search');
                $saveclass->setDb2Connection(true);
                $saveclass->refreshMetaData();
            }
            $saveclass->attributes = $class;
            $saveclass->hash = $class['hash'];
            $saveclass->save();
        }

        foreach ($loads['students'] as $i => $student) {
            $savestudent = new StudentIdentification();
            $savestudent->setScenario('search');
            $savestudent->setDb2Connection(true);
            $savestudent->refreshMetaData();
            $savestudent = $savestudent->findByAttributes(array('hash' => $student['hash']));
            if (!isset($savestudent)) {
                $savestudent = new StudentIdentification();
                $savestudent->setScenario('search');
                $savestudent->setDb2Connection(true);
                $savestudent->refreshMetaData();
            }
            $savestudent->attributes = $student;
            $savestudent->hash = $student['hash'];
            $savestudent->save();
        }

        foreach ($loads['documentsaddress'] as $i => $documentsaddress) {
            $savedocument = new StudentDocumentsAndAddress();
            $savedocument->setScenario('search');
            $savedocument->setDb2Connection(true);
            $savedocument->refreshMetaData();
            $savedocument = $savedocument->findByAttributes(array('hash' => $documentsaddress['hash']));
            if (!isset($exist)) {
                $savedocument = new StudentDocumentsAndAddress();
                $savedocument->setScenario('search');
                $savedocument->setDb2Connection(true);
                $savedocument->refreshMetaData();
            }
            $savedocument->attributes = $documentsaddress;
            $savedocument->hash = $documentsaddress['hash'];
            $savedocument->save();
        }

        foreach ($loads['enrollments'] as $index => $enrollment) {
            $saveenrollment = new StudentEnrollment();
            $saveenrollment->setScenario('search');
            $saveenrollment->setDb2Connection(true);
            $saveenrollment->refreshMetaData();
            $saveenrollment = $saveenrollment->findByAttributes(array('hash' => $enrollment['hash']));
            if (!isset($exist)) {
                $saveenrollment = new StudentEnrollment();
                $saveenrollment->setScenario('search');
                $saveenrollment->setDb2Connection(true);
                $saveenrollment->refreshMetaData();
            }
            $saveenrollment->attributes = $enrollment;
            $saveenrollment->hash = $enrollment['hash'];
            $saveenrollment->hash_classroom = $enrollment['hash_classroom'];
            $saveenrollment->hash_student = $enrollment['hash_student'];
            $saveenrollment->save();
        }
        //@TODO FAZER A PARTE DE PROFESSORES A PARTIR DAQUI
    }

    public function prepareExport()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        ignore_user_abort();
        $year = Yii::app()->user->year;
        $loads = array();
        $sql = "SELECT DISTINCT(school_inep_id_fk) FROM student_enrollment a
                JOIN classroom b ON(a.`classroom_fk`=b.id)
                WHERE
                b.`school_year`=$year";
        //$sql = "SELECT inep_id as school_inep_id_fk  FROM school_identification where situation='1'";
        $schools = Yii::app()->db->createCommand($sql)->queryAll();
        $istudent = new StudentIdentification();
        $istudent->setDb2Connection(false);
        $istudent->refreshMetaData();
        $studentAll = $istudent->findAll();
        try {
            Yii::app()->db2;
            $conn = true;
        } catch (Exception $ex) {
            $conn = false;
        }
        if ($conn) {
            /*
            foreach ($studentAll as $index => $student) {
                $hash_student = hexdec(crc32($student->name.$student->birthday));
                if(!isset($loads['students'][$hash_student])){
                    $loads['students'][$hash_student] = $student->attributes;
                    $loads['students'][$hash_student]['hash'] = $hash_student;
                }
                if(!isset($loads['documentsaddress'][$hash_student])){
                    $idocs = new StudentDocumentsAndAddress();
                    $idocs->setDb2Connection(false);
                    $idocs->refreshMetaData();
                    $loads['documentsaddress'][$hash_student] = $idocs->findByPk($student->id)->attributes;
                    $loads['documentsaddress'][$hash_student]['hash'] = $hash_student;
                }
            }*/
        }
        foreach ($schools as $index => $schll) {
            $ischool = new SchoolIdentification();
            $ischool->setDb2Connection(false);
            $ischool->refreshMetaData();
            $school = $ischool->findByPk($schll['school_inep_id_fk']);

            $iclass = new Classroom();
            $iclass->setDb2Connection(false);
            $iclass->refreshMetaData();
            $classrooms = $iclass->findAllByAttributes(["school_inep_fk" => $schll['school_inep_id_fk'], "school_year" => Yii::app()->user->year]);
            $hash_school = hexdec(crc32($school->inep_id . $school->name));
            $loads['schools'][$hash_school] = $school->attributes;
            $loads['schools'][$hash_school]['hash'] = $hash_school;
            //@todo adicionado load na tabela de schoolstructure
            $loads['schools_structure'][$hash_school] = $school->structure->attributes;
            $loads['schools_structure'][$hash_school]['hash'] = $hash_school;
            foreach ($classrooms as $iclass => $classroom) {
                $hash_classroom = hexdec(crc32($school->inep_id . $classroom->id . $classroom->school_year));
                $loads['classrooms'][$hash_classroom] = $classroom->attributes;
                $loads['classrooms'][$hash_classroom]['hash'] = $hash_classroom;
                foreach ($classroom->studentEnrollments as $ienrollment => $enrollment) {
                    $enrollment->setDb2Connection(false);
                    $enrollment->refreshMetaData();
                    $hash_student = hexdec(crc32($enrollment->studentFk->name . $enrollment->studentFk->birthday));
                    if (!isset($loads['students'][$hash_student])) {
                        $loads['students'][$hash_student] = $enrollment->studentFk->attributes;
                        $loads['students'][$hash_student]['hash'] = $hash_student;
                    }
                    if (!isset($loads['documentsaddress'][$hash_student])) {
                        $loads['documentsaddress'][$hash_student] = $enrollment->studentFk->documentsFk->attributes;
                        $loads['documentsaddress'][$hash_student]['hash'] = $hash_student;
                    }
                    $hash_enrollment = hexdec(crc32($hash_classroom . $hash_student));
                    $loads['enrollments'][$hash_enrollment] = $enrollment->attributes;
                    $loads['enrollments'][$hash_enrollment]['hash'] = $hash_enrollment;
                    $loads['enrollments'][$hash_enrollment]['hash_classroom'] = $hash_classroom;
                    $loads['enrollments'][$hash_enrollment]['hash_student'] = $hash_student;
                }
                /*
                foreach ($classroom->instructorTeachingDatas as $iteaching => $teachingData) {
                    //CARREGAR AS INFORMAÇÕES DE TEACHING DATA;
                    $hash_instructor = hexdec(crc32($teachingData->instructorFk->name.$teachingData->instructorFk->birthday_date));
                    $hash_teachingdata = hexdec(crc32($hash_classroom.$hash_instructor));
                    $loads['instructorsteachingdata'][$teachingData->instructor_fk][$classroom->id] = $teachingData->attributes;
                    $loads['instructorsteachingdata'][$teachingData->instructor_fk][$classroom->id]['hash_instructor'] = $hash_instructor;
                    $loads['instructorsteachingdata'][$teachingData->instructor_fk][$classroom->id]['hash_classroom'] = $hash_classroom;
                    $loads['instructorsteachingdata'][$teachingData->instructor_fk][$classroom->id]['hash'] = $hash_teachingdata;

                    //CARREGAR AS INFORMAÇÕES DE TEACHING DATA;
                    if(!isset($loads['instructors'][$teachingData->instructor_fk])){
                        $loads['instructors'][$teachingData->instructor_fk]['identification'] = $teachingData->instructorFk->attributes;
                        $loads['instructors'][$teachingData->instructor_fk]['identification']['hash'] = $hash_instructor;
                        $loads['idocuments'][$teachingData->instructor_fk]['documents'] = $teachingData->instructorFk->documents->attributes;
                        $loads['idocuments'][$teachingData->instructor_fk]['documents']['hash'] = $hash_instructor;

                    }
                    if(!isset($loads['instructorsvariabledata'][$teachingData->instructor_fk])) {
                        $loads['instructorsvariabledata'][$teachingData->instructor_fk] = $teachingData->instructorFk->instructorVariableData->attributes;
                        $loads['instructorsvariabledata'][$teachingData->instructor_fk]['hash'] = $hash_instructor;
                    }
                }*/
            }
        }
        //var_dump($loads);exit;
        //apc_store('loads', $bar);
        return $loads;
    }

    public function actionExportMaster()
    {
        try {
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '-1');
            set_time_limit(0);
            ignore_user_abort();
            Yii::app()->db2;
            $sql = "SELECT DISTINCT `TABLE_SCHEMA` FROM `information_schema`.`TABLES` WHERE TABLE_SCHEMA LIKE 'io.escola.%';";
            $dbs = Yii::app()->db2->createCommand($sql)->queryAll();
            $loads = array();
            $priority['TABLE_SCHEMA'] = Yii::app()->db->createCommand("SELECT DATABASE()")->queryScalar();
            array_unshift($dbs, $priority);
            foreach ($dbs as $db) {
                //if($db['TABLE_SCHEMA'] != 'io.escola.demo' && $db['TABLE_SCHEMA'] != 'io.escola.geminiano'){
                if ($db['TABLE_SCHEMA'] == 'io.escola.geminiano') {
                    $dbname = $db['TABLE_SCHEMA'];
                    echo $dbname;
                    Yii::app()->db->setActive(false);
                    Yii::app()->db->connectionString = "mysql:host=ipti.org.br;dbname=$dbname";
                    Yii::app()->db->setActive(true);

                    $loads = $this->prepareExport();
                    $datajson = serialize($loads);
                    ini_set('memory_limit', '288M');
                    $fileName = "./app/export/" . $dbname . ".json";
                    $file = fopen($fileName, "w");
                    fwrite($file, $datajson);
                    fclose($file);
                    header("Content-Disposition: attachment; filename=\"" . basename($fileName) . "\"");
                    header("Content-Type: application/force-download");
                    header("Content-Length: " . filesize($fileName));
                    header("Connection: close");
                    $file = fopen($fileName, "r");
                    fpassthru($file);
                    fclose($file);
                    unlink($fileName);
                    //$this->loadMaster($loads);
                }
            }
            Yii::app()->user->setFlash('success', Yii::t('default', 'Escola exportada com sucesso!'));
            $this->redirect(['index']);
        } catch (Exception $e) {
            //echo
            //var_dump($e);exit;
            $loads = $this->prepareExport();
            $datajson = serialize($loads);
            ini_set('memory_limit', '288M');
            $fileName = "./app/export/" . Yii::app()->user->school . ".json";
            $file = fopen($fileName, "w");
            fwrite($file, $datajson);
            fclose($file);
            header("Content-Disposition: attachment; filename=\"" . basename($fileName) . "\"");
            header("Content-Type: application/force-download");
            header("Content-Length: " . filesize($fileName));
            header("Connection: close");
            $file = fopen($fileName, "r");
            fpassthru($file);
            fclose($file);
            unlink($fileName);
        }
    }

    public function actionManageUsers()
    {
        $filter = new Users('search');
        $filter->unsetAttributes();
        if (isset($_GET['Users'])) {
            $filter->attributes = $_GET['Users'];
        }
        $criteria = new CDbCriteria();
        $criteria->condition = "username != 'admin'";
        $dataProvider = new CActiveDataProvider('Users', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 12,
            )
        ));

        $this->render('manageUsers', array(
            'dataProvider' => $dataProvider,
            'filter' => $filter,
        ));
    }

    public function actionUpdate($id)
    {
        $model = Users::model()->findByPk($id);
        $actual_role = $model->getRole();
        $userSchools = UsersSchool::model()->findAllByAttributes(array('user_fk' => $id));
        if (isset($_POST['Users'])) {
            $model->attributes = $_POST['Users'];
            if ($model->validate()) {
                $password = md5($_POST['Users']['password']);
                $model->password = $password;
                if ($model->save()) {
                    $save = TRUE;
                    foreach ($userSchools as $school) {
                        UsersSchool::model()->deleteAll(array("condition" => "school_fk='$school->school_fk'"));
                    }
                    foreach ($_POST['schools'] as $school) {
                        $userSchool = new UsersSchool;
                        $userSchool->user_fk = $model->id;
                        $userSchool->school_fk = $school;
                        $save = $save && $userSchool->validate() && $userSchool->save();
                    }
                    if ($save) {
                        $auth = Yii::app()->authManager;
                        $auth->revoke($actual_role, $model->id);
                        $auth->assign($_POST['Role'], $model->id);
                        Yii::app()->user->setFlash('success', Yii::t('default', 'Usuário alterado com sucesso!'));
                        $this->redirect(['index']);
                    }
                }
            }
        }

        $result = [];
        $i = 0;
        foreach ($userSchools as $scholl) {
            $result[$i] = $scholl->school_fk;
            $i++;
        }

        $this->render('editUser', ['model' => $model, 'actual_role' => $actual_role, 'userSchools' => $result]);
    }

    public function actionChangelog()
    {
        $this->render('changelog');
    }
}
