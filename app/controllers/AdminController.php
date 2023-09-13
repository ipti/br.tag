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

    public function actionExportMaster() {
        $databaseName = Yii::app()->db->createCommand("SELECT DATABASE()")->queryScalar();   
        $pathFileJson = "./app/export/InfoTagJSON/$databaseName.json";

        $adapter = new Adapter;
        $exportModel = new ExportModel;
        $loadedData = [];

        $loadedData = array_merge($loadedData, $exportModel->getSchoolIdentification());
        $loadedData = array_merge($loadedData, $exportModel->getSchoolStructure());
        $loadedData = array_merge($loadedData, $exportModel->getClassrooms());

        $loadedData = array_merge($loadedData, $exportModel->getInstructorsIdentification());
        $loadedData = array_merge($loadedData, $exportModel->getInstructorsTeachingData());
        $loadedData = array_merge($loadedData, $exportModel->getTeachingMatrixes());

        $loadedData = array_merge($loadedData, $exportModel->getStudentIdentification());
        $loadedData = array_merge($loadedData, $exportModel->getStudentDocumentsAndAddress());
        $loadedData = array_merge($loadedData, $exportModel->getStudentEnrollment()); 


        $host = getenv("HOST_DB_TAG");
        Yii::app()->db->setActive(false);
        Yii::app()->db->connectionString = "mysql:host=$host;dbname=$databaseName";
        Yii::app()->db->setActive(true);

        $dataEncoded = $adapter->export($loadedData);
        file_put_contents($pathFileJson, $dataEncoded);

        // Envia o arquivo JSON como download
        header("Content-Disposition: attachment; filename=\"" . basename($pathFileJson) . "\"");
        header("Content-Type: application/force-download");
        header("Content-Length: " . filesize($pathFileJson));
        header("Connection: close"); 
        readfile($pathFileJson);
    }

    public function actionImportMaster() {
        $adapter = new Adapter;
        $databaseName = Yii::app()->db->createCommand("SELECT DATABASE()")->queryScalar();   
        $pathFileJson = "./app/export/InfoTagJSON/$databaseName.json";

        if (!file_exists($pathFileJson)) {
            Yii::app()->user->setFlash('error', 'O arquivo não existe na pasta de importação.');
            $this->redirect(array('index'));
        }   

        try{
            $dataDecoded = $adapter->import(file_get_contents($pathFileJson));
            $importModel = new ImportModel(); 
            $transaction = Yii::app()->db->beginTransaction();
            Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();

            $importModel->saveSchoolIdentificationsDB($dataDecoded['school_identification']);
            $importModel->saveSchoolStructureDB($dataDecoded['school_structure']);
            $importModel->saveClassroomsDB($dataDecoded['classrooms']);

            $importModel->saveInstructorDataDB($dataDecoded['instructor_identification'], $dataDecoded['instructor_documents_and_address'], $dataDecoded['instructor_variable_data']);
            $importModel->saveInstructorsTeachingDataDB($dataDecoded['instructor_teaching_data']);
            $importModel->saveTeachingMatrixes($dataDecoded['teaching_matrixes']);

            $importModel->saveStudentIdentificationDB($dataDecoded['student_identification']);
            $importModel->saveStudentDocumentsAndAddressDB($dataDecoded['student_documents_and_address']);
            $importModel->saveStudentEnrollmentDB($dataDecoded['student_enrollment']); 

            Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute(); 
            $transaction->commit(); 

            Yii::app()->user->setFlash('success', Yii::t('default', 'Importação realizada com sucesso!'));
            $this->redirect(array('index'));
        } catch (Exception $e) {
            $transaction->rollback();
            Yii::app()->user->setFlash('error', Yii::t('default', 'Error na importação: '.$e->getMessage()));
            $this->redirect(array('index'));
        }
    }

    public function actionCreateUser()
    {
        $model = new Users();

        $modelValidate = Users::model()->findByAttributes(
            [
                "name" => $_POST["Users"]["name"],
                "username" => $_POST["Users"]["name"]
            ]
        );
        if (isset($_POST['Users'])) {
            if(!isset($modelValidate)) {
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
                            
                        }
                        if(isset($_POST['instructor']) &&  $_POST['instructor'] != ""){
                            $instructors = InstructorIdentification::model()->find("id = :id", ["id" => $_POST['instructor']]); 
                            $instructors->users_fk = $model->id;
                            $instructors->save();
                        }
                        Yii::app()->user->setFlash('success', Yii::t('default', 'Usuário cadastrado com sucesso!'));
                        $this->redirect(['index']);
                    }
                }
            }else {
                Yii::app()->user->setFlash('error', Yii::t('default', 'Já existe um usuário cadastrado com esse nome/usuário!'));
                $this->redirect(['index']);
            }
        }
        $instructors = InstructorIdentification::model()->findAllByAttributes(['users_fk'=> null], ['select' => 'id, name']); 
        $instructorsResult = array_reduce($instructors, function($carry, $item) {
            $carry[$item['id']] = $item['name'];
            return $carry;
        }, []);
        $this->render('createUser', ['model' => $model, 'instructors' => $instructorsResult]);
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
        $gradeRules = GradeRules::model()->find("edcenso_stage_vs_modality_fk = :stage", [":stage" => $_POST["stage"]]);
        $result["approvalMedia"] = $gradeRules->approvation_media;
        $result["finalRecoverMedia"] = $gradeRules->final_recover_media;
        echo json_encode($result);
    }

    public function actionSaveUnities()
    {
        set_time_limit(0);
        ignore_user_abort();
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

            $gradeRules = GradeRules::model()->find("edcenso_stage_vs_modality_fk = :stage", [":stage" => $_POST["stage"]]);
            if ($gradeRules == null) {
                $gradeRules = new GradeRules();
                $gradeRules->edcenso_stage_vs_modality_fk = $_POST["stage"];
            }
            $gradeRules->approvation_media = $_POST["approvalMedia"];
            $gradeRules->final_recover_media = $_POST["finalRecoverMedia"];
            $gradeRules->save();

            $this->refreshResults($_POST["stage"]);
        } else {
            if ($_POST["reply"] == "A") {
                $grades = Yii::app()->db->createCommand("select * from grade")->queryAll();
                $curricularMatrixes = Yii::app()->db->createCommand("select * from curricular_matrix cm where school_year = :year")->bindParam(":year", Yii::app()->user->year)->queryAll();
            } else if ($_POST["reply"] == "S") {
                $stage = EdcensoStageVsModality::model()->find("id = :id", [":id" => $_POST["stage"]])->stage;
                $grades = Yii::app()->db->createCommand("
                    select * from grade g
                    join grade_unity_modality gum on g.grade_unity_modality_fk = gum.id
                    join grade_unity gu on gu.id = gum.grade_unity_fk
                    join edcenso_stage_vs_modality esvm on esvm.id = gu.edcenso_stage_vs_modality_fk
                    where esvm.stage = :stage
                ")->bindParam(":stage", $stage)->queryAll();
                $curricularMatrixes = Yii::app()->db->createCommand("
                    select * from curricular_matrix cm 
                    join edcenso_stage_vs_modality esvm on esvm.id = cm.stage_fk
                    where school_year = :year and esvm.stage = :stage
                  ")->bindParam(":year", Yii::app()->user->year)->bindParam(":stage", $stage)->queryAll();
            }
            foreach ($curricularMatrixes as $curricularMatrix) {
                if ($grades == null) {
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
                    $valid = true;
                }

                $gradeRules = GradeRules::model()->find("edcenso_stage_vs_modality_fk = :stage", [":stage" => $curricularMatrix["stage_fk"]]);
                if ($gradeRules == null) {
                    $gradeRules = new GradeRules();
                    $gradeRules->edcenso_stage_vs_modality_fk = $curricularMatrix["stage_fk"];
                }
                $gradeRules->approvation_media = $_POST["approvalMedia"];
                $gradeRules->final_recover_media = $_POST["finalRecoverMedia"];
                $gradeRules->save();

                $this->refreshResults($curricularMatrix["stage_fk"]);
            }
        }
        echo json_encode(["valid" => $valid]);
    }

    private function refreshResults($stage) {
        $classrooms = Classroom::model()->findAll("edcenso_stage_vs_modality_fk = :stage and school_year = :year", ["stage" => $stage, "year" => Yii::app()->user->year]);
        $curricularMatrixes = CurricularMatrix::model()->findAll("stage_fk = :stage", ["stage" => $stage]);
        foreach($classrooms as $classroom) {
            foreach($curricularMatrixes as $curricularMatrix) {
                EnrollmentController::saveGradeResults($classroom, $curricularMatrix->discipline_fk);
            }
        }
    }
    public function actionActiveDisableUser()
    {
        $criteria = new CDbCriteria();
        $criteria->condition = "username != 'admin'";

        $users = Users::model()->findAll($criteria);

        $this->render('activeDisableUser', ['users' => $users]);
    }

    public function actionDisableUser($id)
    {
        $model = Users::model()->findByPk($id);

        $model->active = 0;

        if ($model->save()) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Usuário desativado com sucesso!'));
            $this->redirect(['activeDisableUser']);
        }else {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Ocorreu um erro. Tente novamente!'));
            $this->redirect(['activeDisableUser']);
        }
    }

    public function actionActiveUser($id)
    {
        $model = Users::model()->findByPk($id);

        $model->active = 1;

        if ($model->save()) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Usuário ativado com sucesso!'));
            $this->redirect(['activeDisableUser']);
        }else {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Ocorreu um erro. Tente novamente!'));
            $this->redirect(['activeDisableUser']);
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
            delete from teaching_matrixes;

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

        $hash = hexdec(hash('crc32', 'Administrador'.$admin_login.$admin_password));
        $command = "INSERT INTO users VALUES (1, 'Administrador', '$admin_login', '$admin_password', 1, $hash);";
        Yii::app()->db->createCommand("ALTER TABLE users AUTO_INCREMENT = 2;")->execute();
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
                    }
                    if(isset($_POST['instructor']) &&  $_POST['instructor'] != ""){
                        $instructors = InstructorIdentification::model()->find("id = :id", ["id" => $_POST['instructor']]); 
                        $instructors->users_fk = $model->id;
                        $instructors->save();
                    }
                    Yii::app()->user->setFlash('success', Yii::t('default', 'Usuário cadastrado com sucesso!'));
                    $this->redirect(['index']);
                }
            }
        }

        $result = [];
        $i = 0;
        foreach ($userSchools as $scholl) {
            $result[$i] = $scholl->school_fk;
            $i++;
        }

        $instructors = InstructorIdentification::model()->findAllByAttributes(['users_fk'=> null], ['select' => 'id, name']); 
        $instructorsResult = array_reduce($instructors, function($carry, $item) {
            $carry[$item['id']] = $item['name'];
            return $carry;
        }, []);

        $this->render('editUser', ['model' => $model, 'actual_role' => $actual_role, 'userSchools' => $result, 'instructors' => $instructorsResult]);
    }

    public function actionChangelog()
    {
        $this->render('changelog');
    }
}
