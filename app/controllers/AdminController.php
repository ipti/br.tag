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

        if (isset($_POST['Users'])) {
            $model->attributes = $_POST['Users'];
            if ($model->validate()) {
                $password = md5($_POST['Users']['password']);
                
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
                        $modality->weight = $m["weight"];
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
                            $modality->weight = $m["weight"];
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
            'pagination' => false
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

        Yii::app()->user->setFlash('success', Yii::t('default', 'Limpeza do banco de dados concluída com sucesso! <br/>Faça o login novamente para atualizar os dados.'));
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

        $databaseName = Yii::app()->db->createCommand("SELECT DATABASE()")->queryScalar();   
        $pathFileJSON = "./app/export/InfoTagJSON/$databaseName.json";

        $fileImport = fopen($pathFileJSON, 'r');
        if ($fileImport == false) {
            die('O arquivo não existe na pasta import.');
        }
        fclose($fileImport);

        $json = unserialize(file_get_contents($pathFileJSON));   

        $this->loadMaster($json);
    }

    public function loadMaster($datajson)
    {
        try{      
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '-1');
            set_time_limit(0);

            $databaseName = Yii::app()->db->createCommand("SELECT DATABASE()")->queryScalar();          
            $host = getenv("HOST_DB_TAG");
            Yii::app()->db->connectionString = "mysql:host=$host;dbname=$databaseName";

            $pdo = new PDO("mysql:host=$host;dbname=$databaseName", "root", "root");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("SET GLOBAL max_connections = 100");
            $pdo->exec("SET GLOBAL innodb_lock_wait_timeout = 200");

            $transaction = Yii::app()->db->beginTransaction();

            $this->loadEdcensoNations($datajson['edcensonations']);
            #$this->loadSchools($datajson['schools']);
            /*$this->loadSchoolsStructures($datajson['schools_structure']);
            
            $this->loadClassrooms($datajson['classrooms']);

            $this->loadStudentIdentification($datajson['students']);
            $this->loadStudentDocumentsAndAddress($datajson['documentsaddress']);
            $this->loadStudentEnrollment($datajson['enrollments']);
            
            $this->loadInstructorsTeachingData($datajson['instructorsteachingdata']); */

            #$this->saveUsersDB($datajson['users']);
            #$this->saveInstructorIdentificationDB($datajson['instructors']);
            #$this->saveInstructorDocumentsAndAddressDB($datajson['instructorDocumentsAndAddress']); 
                   
            $transaction->commit();
            Yii::app()->user->setFlash('success', Yii::t('default', 'Importação realizada com sucesso!'));
            $this->redirect(array('index'));

        } catch (Exception $e){
            $transaction->rollback();
            
            Yii::app()->user->setFlash('error', Yii::t('default', 'Ocorreu um erro ao importar os dados: '. $e->getMessage()));
            $this->redirect(array('index'));
        }
    }

    function loadEdcensoNations($jsonEdcensoNations)
    {
        foreach ($jsonEdcensoNations as $edcensoNation) {     
            $edcenso = EdcensoNation::model()->findByAttributes(['name' => $edcensoNation['name'], 'acronym' => $edcensoNation['acronym']]);
            $hash = hexdec(hash('crc32', $edcenso['name'].$edcenso['acronym']));

            if($hash !== $edcensoNation['hash']){
                $edcensoNationModel = new EdcensoNation();
                $edcensoNationModel->setDbCriteria(true);
                $edcensoNationModel->refreshMetaData();
                
                $edcensoNationModel->attributes = $edcensoNation;
                $edcensoNationModel->save();
            }
        }
    }


    function loadSchools($jsonSchools) {
        foreach ($jsonSchools as $school) {
            $schoolIdentificationModel = new SchoolIdentification();
            $schoolIdentificationModel->setDb2Connection(true);
            $schoolIdentificationModel->refreshMetaData();

            $existingInepId = $schoolIdentificationModel->findByAttributes(
                array(
                    'inep_id' => $school['inep_id']
                )
            );

            if (!isset($existingInepId)) {
                $schoolIdentificationModel = new SchoolIdentification();
                $schoolIdentificationModel->setDb2Connection(true);
                $schoolIdentificationModel->refreshMetaData();
            }

            $schoolIdentificationModel->attributes = $school;
            $schoolIdentificationModel->save();
        }
    }

    function loadSchoolsStructures($jsonSchoolsStructures) {
        foreach ($jsonSchoolsStructures as $structure) {
            $schoolStructure = new SchoolStructure();
            $schoolStructure->setDb2Connection(true);
            $schoolStructure->refreshMetaData();

            $schoolStructure = $schoolStructure->findByAttributes(
                array(
                    'school_inep_id_fk' => $structure['school_inep_id_fk']
                )
            );

            if (!isset($schoolStructure)) {
                $schoolStructure = new SchoolStructure();
                $schoolStructure->setDb2Connection(true);
                $schoolStructure->refreshMetaData();
            }

            $schoolStructure->attributes = $structure;
            $schoolStructure->save();
        }
    }

    function loadClassrooms($jsonClassrooms) {
        foreach ($jsonClassrooms as  $class) {
            $classroom = new Classroom();
            $classroom->setScenario('search');
            $classroom->setDb2Connection(true);
            $classroom->refreshMetaData();
            
            $classroom = $classroom->findByAttributes(
                array(
                    'hash' => $class['hash']
                )
            );

            if (!isset($classroom)) {
                $classroom = new Classroom();
                $classroom->setScenario('search');
                $classroom->setDb2Connection(true);
                $classroom->refreshMetaData();
            }

            $classroom->attributes = $class;
            $classroom->hash = $class['hash'];
            $classroom->save();
        }
    }

    function loadStudentIdentification($jsonStudents)  {
        foreach ($jsonStudents as $student) {
            $studentIdentification = new StudentIdentification();
            $studentIdentification->setScenario('search');
            $studentIdentification->setDb2Connection(true);
            $studentIdentification->refreshMetaData();
            
            $studentIdentification = $studentIdentification->findByAttributes(
                array(
                    'hash' => $student['hash']
                )
            );

            if (!isset($studentIdentification)) {
                $studentIdentification = new StudentIdentification();
                $studentIdentification->setScenario('search');
                $studentIdentification->setDb2Connection(true);
                $studentIdentification->refreshMetaData();
            }

            $studentIdentification->attributes = $student;
            $studentIdentification->hash = $student['hash'];
            $studentIdentification->save();
        }
    }

    function loadStudentDocumentsAndAddress($jsonDocumentsAddress) {
        foreach ($jsonDocumentsAddress as $documentsaddress) {
            $studentDocumentsAndAddress = new StudentDocumentsAndAddress();
            $studentDocumentsAndAddress->setScenario('search');
            $studentDocumentsAndAddress->setDb2Connection(true);
            $studentDocumentsAndAddress->refreshMetaData();
            
            $studentDocumentsAndAddress = $studentDocumentsAndAddress->findByAttributes(
                array(
                    'hash' => $documentsaddress['hash']
                )
            );

            if (!isset($studentDocumentsAndAddress)) {
                $studentDocumentsAndAddress = new StudentDocumentsAndAddress();
                $studentDocumentsAndAddress->setScenario('search');
                $studentDocumentsAndAddress->setDb2Connection(true);
                $studentDocumentsAndAddress->refreshMetaData();
            }
            
            $studentDocumentsAndAddress->attributes = $documentsaddress;
            $studentDocumentsAndAddress->hash = $documentsaddress['hash'];
            $studentDocumentsAndAddress->save();
        }
    }

    function loadStudentEnrollment($jsonEnrollments)
    {
        foreach ($jsonEnrollments as $enrollment) {
            $studentEnrollment = new StudentEnrollment();
            $studentEnrollment->setScenario('search');
            $studentEnrollment->setDb2Connection(true);
            $studentEnrollment->refreshMetaData();

            $studentEnrollment = $studentEnrollment->findByAttributes(
                array(
                    'hash' => $enrollment['hash']
                )
            );

            if (!isset($studentEnrollment)) {
                $studentEnrollment = new StudentEnrollment();
                $studentEnrollment->setScenario('search');
                $studentEnrollment->setDb2Connection(true);
                $studentEnrollment->refreshMetaData();
            }

            $studentEnrollment->attributes = $enrollment;
            $studentEnrollment->hash = $enrollment['hash'];
            $studentEnrollment->hash_classroom = $enrollment['hash_classroom'];
            $studentEnrollment->hash_student = $enrollment['hash_student'];
            $studentEnrollment->save();
        }
    }

    function loadInstructorsTeachingData($jsonInstructorsTeachingDatas)
    {
        foreach ($jsonInstructorsTeachingDatas as $instructorsTeachingData) {
            $instructorTeachingData = new InstructorTeachingData();
            $instructorTeachingData->setScenario('search');
            $instructorTeachingData->setDb2Connection(true);
            $instructorTeachingData->refreshMetaData();

            $instructorTeachingData = $instructorTeachingData->findByAttributes(
                array(
                    'hash' => $instructorsTeachingData['hash']
                )
            );

            if (!isset($instructorTeachingData)) {
                $instructorTeachingData = new InstructorTeachingData();
                $instructorTeachingData->setScenario('search');
                $instructorTeachingData->setDb2Connection(true);
                $instructorTeachingData->refreshMetaData();
            }

            $instructorTeachingData->attributes = $instructorsTeachingData;
            $instructorTeachingData->hash = $instructorsTeachingData['hash'];
            $instructorTeachingData->save();
        }
    }

    function saveUsersDB($users) {
        foreach ($users as $user) {
            $usersModel = new Users();
            $usersModel->setDb2Connection(true);
            $usersModel->refreshMetaData();

            $var = $usersModel->findByAttributes(['hash' => $user['hash']]);

            if(!isset($var)){
                $usersModel = new Users();
                $usersModel->setDb2Connection(true);
                $usersModel->refreshMetaData();
            }
    
            $usersModel->attributes = $user;
            $usersModel->hash = $user['hash'];
            $usersModel->save();
        }
    }

    function saveInstructorIdentificationDB($instructorIdentifications) {
        foreach ($instructorIdentifications as $instructorIdentification) {
            $instructorIdentificationModel = new InstructorIdentification();
            $instructorIdentificationModel->setDb2Connection(true);
            $instructorIdentificationModel->refreshMetaData();

            $existingModel = $instructorIdentificationModel->findByAttributes(['id' => $instructorIdentification['id']]);

            if (hexdec(hash('crc32', $existingModel['name'].$existingModel['birthday_date'])) !== $instructorIdentification['hash']) {
                $instructorIdentificationModel = new InstructorIdentification();
                $instructorIdentificationModel->setDb2Connection(true);
                $instructorIdentificationModel->refreshMetaData();

                $instructorIdentificationModel->attributes = $instructorIdentification;
                $instructorIdentificationModel->save();
            }
        }
    }
    

    function saveInstructorDocumentsAndAddressDB($instructorDocumentsAndAddresses)
    {
        foreach ($instructorDocumentsAndAddresses as $instructorDocumentsAndAddress) {
            $instructorDocumentsAndAddressModel = new InstructorDocumentsAndAddress();
            $instructorDocumentsAndAddressModel->setDb2Connection(true);
            $instructorDocumentsAndAddressModel->refreshMetaData();

            $instructorDocumentsAndAddressModel->attributes = $instructorDocumentsAndAddress;
            $instructorDocumentsAndAddressModel->save();
        }
    }
    

    public function actionExportMaster()
    {
        $file = null;
        $databaseName = Yii::app()->db->createCommand("SELECT DATABASE()")->queryScalar();
        $pathFileJSON = "./app/export/InfoTagJSON/$databaseName.json";

        try {
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '-1');
            set_time_limit(0);
            ignore_user_abort();
                         
            $host = getenv("HOST_DB_TAG");
            Yii::app()->db->setActive(false);
            Yii::app()->db->connectionString = "mysql:host=$host;dbname=$databaseName";
            Yii::app()->db->setActive(true);

            // Prepara os dados para exportação
            $exportData = array();
            $exportData = $this->prepareExport();
            $datajson = serialize($exportData);

            // Grava os dados no arquivo JSON
            $file = fopen($pathFileJSON, "w");      
            fwrite($file, $datajson);
            fclose($file);

            // Envia o arquivo JSON como download
            header("Content-Disposition: attachment; filename=\"" . basename($pathFileJSON) . "\"");
            header("Content-Type: application/force-download");
            header("Content-Length: " . filesize($pathFileJSON));
            header("Connection: close"); 
            $file = fopen($pathFileJSON, "r");
            fpassthru($file);
            fclose($file); 

            Yii::app()->user->setFlash('success', Yii::t('default', 'Exportação realizada com sucesso!'));
            $this->redirect(array('index'));
            
        } catch (Exception $e) {
            echo "Ocorreu um erro durante o processamento: " . $e->getMessage();

            if (file_exists($pathFileJSON)) {
                unlink($pathFileJSON);
            }
        } finally {
            fclose($file);
            ini_set('memory_limit', ini_get('memory_limit'));
        }
    }

    public function prepareExport()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        ignore_user_abort();
  
        $year = Yii::app()->user->year; 
        $loads = array();

/*         $query = "SELECT DISTINCT school_inep_id_fk FROM student_enrollment se
                    JOIN classroom c ON(c.id = se.classroom_fk)
                    WHERE c.school_year = :year";

        $schools = Yii::app()->db->createCommand($query)->bindValue(":year", $year)->queryAll(); */

/*         $istudent = new StudentIdentification();
        $istudent->setDb2Connection(false);
        $istudent->refreshMetaData(); */
        /*        
        foreach ($schools as $schll) {
            $ischool = new SchoolIdentification();
            $ischool->setDb2Connection(false);
            $ischool->refreshMetaData();
            $school = $ischool->findByPk($schll['school_inep_id_fk']);

            $iclass = new Classroom();
            $iclass->setDb2Connection(false);
            $iclass->refreshMetaData();
            $classrooms = $iclass->findAllByAttributes(["school_inep_fk" => $schll['school_inep_id_fk']]);
            $hash_school = hexdec(crc32($school->inep_id . $school->name));
            
            $loads['schools'][$hash_school] = $school->attributes;
            $loads['schools'][$hash_school]['hash'] = $hash_school;       
            
            $loads['schools_structure'][$hash_school] = $school->structure->attributes;
            $loads['schools_structure'][$hash_school]['hash'] = $hash_school;
            
            foreach ($classrooms as $iclass => $classroom) {
                $hash_classroom = hexdec(crc32($school->inep_id . $classroom->id . $classroom->school_year));       
                $loads['classrooms'][$hash_classroom] = $classroom->attributes;
                $loads['classrooms'][$hash_classroom]['hash'] = $hash_classroom;
                
                foreach ($classroom->studentEnrollments as $enrollment) {
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
                
                foreach ($classroom->instructorTeachingDatas as $teachingData) {
                    $teachingData->setDb2Connection(false);
                    $teachingData->refreshMetaData();

                    $hash_instructor = hexdec(crc32($teachingData->instructorFk->name.$teachingData->instructorFk->birthday_date));
                    $hash_teachingdata = hexdec(crc32($hash_classroom.$hash_instructor));

                    $loads['instructorsteachingdata'][$teachingData->instructor_fk][$classroom->id] = $teachingData->attributes;
                    $loads['instructorsteachingdata'][$teachingData->instructor_fk][$classroom->id]['hash_instructor'] = $hash_instructor;
                    $loads['instructorsteachingdata'][$teachingData->instructor_fk][$classroom->id]['hash_classroom'] = $hash_classroom;
                    $loads['instructorsteachingdata'][$teachingData->instructor_fk][$classroom->id]['hash'] = $hash_teachingdata;

                    $loads['instructorDocumentsAndAddress'][$teachingData->instructor_fk]['documents'] = $teachingData->instructorFk->documents->attributes;
                    $loads['instructorDocumentsAndAddress'][$teachingData->instructor_fk]['documents']['hash'] = $hash_instructor;
                    
                    if(!isset($loads['instructorsvariabledata'][$teachingData->instructor_fk])) {
                        $loads['instructorsvariabledata'][$teachingData->instructor_fk] = $teachingData->instructorFk->instructorVariableData->attributes;
                        $loads['instructorsvariabledata'][$teachingData->instructor_fk]['hash'] = $hash_instructor;
                    }
                }
            }
        }   */

        $loads = array_merge($loads, $this->getEdcensoNationToJsonFile());

        #$loads = array_merge($loads, $this->getUsersToJsonFile());
        #$loads = array_merge($loads, $this->getInstructorsToJsonFile());

        return $loads;
    }

    function getInstructorsToJsonFile() 
    {
        $query = "SELECT * FROM instructor_identification";
        $instructors = Yii::app()->db->createCommand($query)->queryAll();

        $instructorModel = new InstructorIdentification();
        $instructorModel->setDb2Connection(false);
        $instructorModel->refreshMetaData();

        $instructorsData = [];
        foreach ($instructors as $instructor) {
            $instructor['hash'] = hexdec(hash('crc32', $instructor['name'].$instructor['birthday_date']));
            $instructorsData['instructors'][] = $instructor;

            $query = "select * from instructor_documents_and_address where id = :id";
            $instructorDocumentsAndAddresses = Yii::app()->db->createCommand($query)->bindValue(":id", $instructor['id'])->queryRow();
            $instructorsData['instructorDocumentsAndAddress'][] = $instructorDocumentsAndAddresses;
        }

        return $instructorsData;
    }

    function getSchoolIdentificationToJsonFile() 
    {
        $query = "SELECT * FROM school_identification";
        $schools = Yii::app()->db->createCommand($query)->queryAll();

        $schoolIdentificationModel = new SchoolIdentification();
        $schoolIdentificationModel->setDb2Connection(false);
        $schoolIdentificationModel->refreshMetaData();

        $schoolsData = [];
        foreach ($schools as $school) {
            $school['hash'] = hexdec(hash('crc32', $school['inep_id']));
            $schoolsData['schools'][] = $school;
        }

        return $schoolsData;
    }

    function getEdcensoNationToJsonFile() 
    {
        $query = "SELECT * FROM edcenso_nation";
        $edcensoNations = Yii::app()->db->createCommand($query)->queryAll();

        $edcensoNationModel = new EdcensoNation();
        $edcensoNationModel->setDb2Connection(false);
        $edcensoNationModel->refreshMetaData();

        $edcensoNationData = [];
        foreach ($edcensoNations as $edcensoNation) {
            $edcensoNation['hash'] = hexdec(hash('crc32', $edcensoNation['name'].$edcensoNation['acronym']));
            $edcensoNationData['edcensonations'][] = $edcensoNation;
        }

        return $edcensoNationData;
    }

    function getUsersToJsonFile()
    {
        $query = "SELECT * FROM users";
        $users = Yii::app()->db->createCommand($query)->queryAll();

        $userModel = new Users;
        $userModel->setDb2Connection(false);
        $userModel->refreshMetaData();

        $usersData = [];
        foreach ($users as $user) {
            $user['hash'] = hexdec(hash('crc32', $user['name'].$user['username']));
            $usersData['users'][] = $user;
        }

        return $usersData;
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
            'pagination' => false
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
                    foreach ($userSchools as $user_school) {
                        $user_school->delete();
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
