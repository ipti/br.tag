<?php

Yii::import("application.domain.admin.usecases.*");

class AdminController extends Controller
{
    public $layout = 'fullmenu';

    public function accessRules()
    {
        return [
            [
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['CreateUser', 'index', 'conflicts'],
                'users' => ['*'],
            ],
            [
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => [
                    'import', 'export', 'update', 'manageUsers', 'clearDB', 'acl', 'backup', 'data', 'exportStudentIdentify', 'syncExport',
                    'syncImport', 'exportToMaster', 'clearMaster', 'importFromMaster', 'gradesStructure', 'instanceConfig', 'editInstanceConfigs'
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

    public function actionExportMaster()
    {
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

    public function actionImportMaster()
    {
        ini_set('memory_limit', '2048M');
        $adapter = new Adapter;
        $databaseName = Yii::app()->db->createCommand("SELECT DATABASE()")->queryScalar();
        $pathFileJson = "./app/export/InfoTagJSON/$databaseName.json";

        if (!file_exists($pathFileJson)) {
            Yii::app()->user->setFlash('error', 'O arquivo não existe na pasta de importação.');
            $this->redirect(array('index'));
        }

        try {
            $dataDecoded = $adapter->import(file_get_contents($pathFileJson));
            $importModel = new ImportModel();
            $transaction = Yii::app()->db->beginTransaction();
            Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();

            $importModel->saveSchoolIdentificationsDB($dataDecoded['school_identification']);
            $importModel->saveSchoolStructureDB($dataDecoded['school_structure']);
            $importModel->saveClassroomsDB($dataDecoded['classrooms']);

            $importModel->saveInstructorDataDB(
                $dataDecoded['instructor_identification'],
                $dataDecoded['instructor_documents_and_address'],
                $dataDecoded['instructor_variable_data']
            );
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
            Yii::app()->user->setFlash('error', Yii::t('default', 'Error na importação: ' . $e->getMessage()));
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
            if (!isset($modelValidate)) {
                $model->attributes = $_POST['Users'];
                if ($model->validate()) {
                    $passwordHasher =  new PasswordHasher;
                    $password = $passwordHasher->bcriptHash($_POST['Users']['password']);

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
                        if (isset($_POST['instructor']) && $_POST['instructor'] != "") {
                            $instructors = InstructorIdentification::model()->find("id = :id", ["id" => $_POST['instructor']]);
                            $instructors->users_fk = $model->id;
                            $instructors->save();
                        }
                        Yii::app()->user->setFlash('success', Yii::t('default', 'Usuário cadastrado com sucesso!'));
                        $this->redirect(['index']);
                    }
                }
            } else {
                Yii::app()->user->setFlash('error', Yii::t('default', 'Já existe um usuário cadastrado com esse nome/usuário!'));
                $this->redirect(['index']);
            }
        }
        $instructors = InstructorIdentification::model()->findAllByAttributes(
            ['users_fk' => null],
            ['select' => 'id, name']
        );
        $instructorsResult = array_reduce($instructors, function ($carry, $item) {
            $carry[$item['id']] = $item['name'];
            return $carry;
        }, []);
        $this->render('createUser', ['model' => $model, 'instructors' => $instructorsResult]);
    }

    public function actionGradesStructure()
    {
        $stages = Yii::app()->db->createCommand("
            select
                distinct esvm.id,
                esvm.name
            from edcenso_stage_vs_modality esvm
                join curricular_matrix cm on cm.stage_fk = esvm.id
            where school_year = :year order by esvm.name")
            ->bindParam(":year", Yii::app()->user->year)
            ->queryAll();

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
        $stage = Yii::app()->request->getPost("stage");

        $modality =  Yii::app()->db->createCommand("
        select
            distinct gu.name,
            gu.id,
            gu.edcenso_stage_vs_modality_fk
        from grade_unity gu
            join curricular_matrix cm on cm.stage_fk = gu.edcenso_stage_vs_modality_fk
        where cm.school_year = :year and gu.edcenso_stage_vs_modality_fk = :stage_modality")
            ->bindParam(":year", Yii::app()->user->year)
            ->bindParam(":stage_modality", $stage)
            ->queryAll();

        $result = [];
        $result["unities"] = [];

        $criteria = new CDbCriteria();
        $criteria->alias = "gu";
        $criteria->condition = "edcenso_stage_vs_modality_fk = :stage";
        $criteria->addInCondition("gu.type", [GradeUnity::TYPE_UNITY, GradeUnity::TYPE_UNITY_BY_CONCEPT, GradeUnity::TYPE_UNITY_WITH_RECOVERY]);
        $criteria->params = array_merge([":stage" => $stage], $criteria->params);
        $criteria->order = "gu.id";

        $gradeUnities = GradeUnity::model()
            ->with("gradeUnityModalities")
            ->findAll($criteria);



        foreach ($gradeUnities as $gradeUnity) {
            $arr = $gradeUnity->attributes;
            $arr["modalities"] = [];
            foreach ($gradeUnity->gradeUnityModalities as $gradeUnityModality) {
                array_push($arr["modalities"], $gradeUnityModality->attributes);
            }
            array_push($result["unities"], $arr);
        }

        $criteria->condition = "edcenso_stage_vs_modality_fk = :stage and gu.type = :type";
        $criteria->params = [":stage" => $stage, ":type" => GradeUnity::TYPE_FINAL_RECOVERY];

        $finalRecovery = GradeUnity::model()
            ->with("gradeUnityModalities")
            ->find($criteria);
        $result["final_recovery"] = $finalRecovery->attributes;
        $result["final_recovery"]["modalities"] = [];
        foreach ($finalRecovery->gradeUnityModalities as $gradeUnityModality) {
            array_push($result["final_recovery"]["modalities"], $gradeUnityModality->attributes);
        }

        $gradeRules = GradeRules::model()
            ->find(
                "edcenso_stage_vs_modality_fk = :stage",
                [":stage" => $stage]
            );

        $result["approvalMedia"] = $gradeRules->approvation_media;
        $result["finalRecoverMedia"] = $gradeRules->final_recover_media;
        $result["semiRecoverMedia"] = $gradeRules->semi_recover_media;
        $result["mediaCalculation"] = $gradeRules->grade_calculation_fk;
        $result["ruleType"] = $gradeRules->rule_type;
        $result["hasFinalRecovery"] = (bool) $gradeRules->has_final_recovery;
        $result["modality"] = $modality;

        echo CJSON::encode($result);
    }

    public function actionSaveUnities()
    {
        set_time_limit(0);
        ignore_user_abort();

        $reply = Yii::app()->request->getPost("reply");
        $stage = Yii::app()->request->getPost("stage");
        $unities = Yii::app()->request->getPost("unities");
        $approvalMedia = Yii::app()->request->getPost("approvalMedia");
        $finalRecoverMedia = Yii::app()->request->getPost("finalRecoverMedia");
        $semiRecoverMedia = Yii::app()->request->getPost("semiRecoverMedia");
        $calculationFinalMedia = Yii::app()->request->getPost("finalMediaCalculation");
        $finalRecovery = Yii::app()->request->getPost("finalRecovery");
        $semiRecovery = Yii::app()->request->getPost("semianualRecovery");
        $ruleType = Yii::app()->request->getPost("ruleType");
        $hasFinalRecovery = Yii::app()->request->getPost("hasFinalRecovery") === "true";
        $hasSemianualRecovery = Yii::app()->request->getPost("hasRecoverySemianual") === "true";

        try {
            $usecase = new UpdateGradeStructUsecase(
                $reply,
                $stage,
                $unities,
                $approvalMedia,
                $finalRecoverMedia,
                $semiRecoverMedia,
                $calculationFinalMedia,
                $hasFinalRecovery,
                $hasSemianualRecovery,
                $ruleType
            );
            $usecase->exec();

            if ($hasFinalRecovery === true) {
                $recoveryUnity = GradeUnity::model()->find($finalRecovery["id"]);

                if ($finalRecovery["operation"] === "delete") {
                    $recoveryUnity->delete();
                    echo json_encode(["valid" => true]);
                    Yii::app()->end();
                }

                if ($recoveryUnity === null) {
                    $recoveryUnity = new GradeUnity();
                }

                $recoveryUnity->name = $finalRecovery["name"];
                $recoveryUnity->type = "RF";
                $recoveryUnity->grade_calculation_fk = $finalRecovery["grade_calculation_fk"];
                $recoveryUnity->edcenso_stage_vs_modality_fk = $stage;

                if (!$recoveryUnity->validate()) {
                    $validationMessage = Yii::app()->utils->stringfyValidationErrors($recoveryUnity);
                    throw new CHttpException(400, "Não foi possível salvar dados da recuperação final: \n" . $validationMessage, 1);
                }

                $recoveryUnity->save();
            }

            if ($hasSemianualRecovery === true) {
                $this->processSemianualRecovery($stage, $semiRecovery, $semiRecoverMedia);
            }

            echo json_encode(["valid" => true]);
        } catch (\Throwable $th) {
            Yii::log($th->getMessage(), CLogger::LEVEL_ERROR);
            Yii::log($th->getTraceAsString(), CLogger::LEVEL_ERROR);

            throw $th;
        }
    }

    protected function processSemianualRecovery($stage, $semiRecovery, $semiRecoverMedia)
    {
        foreach ($semiRecovery as $semianual) {
            $recoveryUnitySemi = new GradeUnity();
            $gradeRules = new GradeRules();

            if ($semianual["operation"] === "update") {
                $recoveryUnitySemi = GradeUnity::model()->find($semianual["id"]);
            }

            if ($semianual["operation"] === "delete") {
                $recoveryUnitySemi = GradeUnity::model()->find($semianual["id"]);
                $recoveryUnitySemi->delete();
                echo json_encode(["valid" => true]);
                Yii::app()->end();
            }

            $recoveryUnitySemi->name = $semianual["name"];
            $recoveryUnitySemi->type = "RS";
            // $recoveryUnitySemi
            $recoveryUnitySemi->grade_calculation_fk = $semianual["grade_calculation_fk"];
            $recoveryUnitySemi->edcenso_stage_vs_modality_fk = $stage;
            $gradeRules->semi_recover_media = $semiRecoverMedia;

            if (!$recoveryUnitySemi->validate()) {
                $validationMessage = Yii::app()->utils->stringfyValidationErrors($recoveryUnitySemi);
                throw new CHttpException(400, "Não foi possível salvar dados da recuperação semestral: \n" . $validationMessage, 1);
            }

            $recoveryUnitySemi->save();
        }
    }

    public function actionActiveDisableUser()
    {
        $criteria = new CDbCriteria();
        $criteria->condition = "username != 'admin'";

        $users = Users::model()->findAll($criteria);

        $this->render('activeDisableUser', ['users' => $users]);
    }

    public function actionPHPConfig()
    {

        echo phpinfo();
    }
    public function actionDisableUser($id)
    {
        $model = Users::model()->findByPk($id);

        $model->active = 0;

        if ($model->save()) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Usuário desativado com sucesso!'));
            $this->redirect(['activeDisableUser']);
        } else {
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
        } else {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Ocorreu um erro. Tente novamente!'));
            $this->redirect(['activeDisableUser']);
        }
    }

    public function actionDeleteUser($id)
    {
        // Query para buscar os registros relacionados ao ID no banco
        $user = Users::model()->findByPk($id);
        $userSchool = UsersSchool::model()->findAllByAttributes(array('user_fk' => $id));
        $authAssign = AuthAssignment::model()->findByAttributes(array('userid' => $id));
        $instructorId = InstructorIdentification::model()->findByAttributes(array('users_fk' => $id));
        $delete = false;

        if ($user !== null) {

            // Atualizando a coluna que referência ao usuário na tabela de identificação de professor
            // A função save abstrai o processo de identificar se está ocorrendo um UPDATE ou INSERT
            if ($instructorId !== null) {
                $instructorId->users_fk = null;
                $instructorId->save();
            }

            // Excluindo o registro na tabela que representa o cargo de um profissional cadastrado
            if ($authAssign !== null) {
                $authAssign->delete('auth_assignment', 'userid =' . $id);
            }

            // Excluindo o registro na tabela que representa o acesso às escolas do usuário
            if ($userSchool !== null) {
                foreach ($userSchool as $register) {
                    $register->delete('users_school', 'user_fk=' . $id);
                }
            }

            // Excluindo o registro na tabela de usuário
            $user->delete('users', 'id=' . $id);
            $delete = true;
        }

        // Redirecionando para a tela de gerenciar usuários
        if ($delete) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Usuário excluído com sucesso!'));
            $this->redirect(array('admin/manageUsers'));
        } else {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Erro! Não foi possível excluir o usuário, tente novamente!'));
        }
    }

    public function actionEditPassword($id)
    {
        $model = Users::model()->findByPk($id);

        if (isset($_POST['Users'], $_POST['Confirm'])) {
            $passwordHasher = new PasswordHasher;
            $password = ($_POST['Users']['password']);
            $confirm = ($_POST['Confirm']);
            if ($password == $confirm) {
                $model->password = $passwordHasher->bcriptHash($password);
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', Yii::t('default', 'Senha alterada com sucesso!'));
                    if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) {
                        $this->redirect(['index']);
                    }
                    $this->redirect(['/']);
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

        $hash = hexdec(hash('crc32', 'Administrador' . $admin_login . $admin_password));
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
        $users = Yii::app()->request->getParam('Users');

        if (isset($users)) {
            $filter->attributes = $users;
        }
        $criteria = new CDbCriteria();
        $criteria->condition = "username != 'admin'";
        $dataProvider = new CActiveDataProvider(
            'Users',
            array(
                'criteria' => $criteria,
                'pagination' => false
            )
        );

        $this->render(
            'manageUsers',
            array(
                'dataProvider' => $dataProvider,
                'filter' => $filter,
            )
        );
    }

    public function actionUpdate($id)
    {
        $model = Users::model()->findByPk($id);
        $actualRole = $model->getRole();
        $userSchools = UsersSchool::model()->findAllByAttributes(array('user_fk' => $id));

        // Atribuindo valores da superglobal _POST à variáveis locais a fim de evitar o uso de globais
        $users = Yii::app()->request->getPost('Users');
        $schools = Yii::app()->request->getPost('schools');
        $instructor = Yii::app()->request->getPost('instructor');
        $role = Yii::app()->request->getPost('Role');

        if (isset($users)) {
            $model->attributes = $users;
            if ($model->validate()) {
                $passwordHasher = new PasswordHasher;
                $model->password = $passwordHasher->bcriptHash($users['password']);
                if ($model->save()) {
                    $save = true;
                    foreach ($userSchools as $userSchool) {
                        $userSchool->delete();
                    }
                    foreach ($schools as $school) {
                        $userSchool = new UsersSchool;
                        $userSchool->user_fk = $model->id;
                        $userSchool->school_fk = $school;
                        $save = $save && $userSchool->validate() && $userSchool->save();
                    }
                    if ($save) {
                        $auth = Yii::app()->authManager;
                        $auth->revoke($actualRole, $model->id);
                        $auth->assign($role, $model->id);
                    }
                    if (isset($instructor) && $instructor != "") {
                        $instructors = InstructorIdentification::model()->find("id = :id", ["id" => $instructor]);

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

        $instructors = InstructorIdentification::model()->findAllByAttributes(['users_fk' => null], ['select' => 'id, name']);
        $instructorsResult = array_reduce($instructors, function ($carry, $item) {
            $carry[$item['id']] = $item['name'];
            return $carry;
        }, []);

        $selectedInstructor = InstructorIdentification::model()->find("users_fk = :user_fk", ["user_fk" => $model->id]);

        if (isset($selectedInstructor)) {
            $instructorsResult[$selectedInstructor->id] = $selectedInstructor->name;
        }

        $this->render(
            'editUser',
            [
                'model' => $model,
                'actual_role' => $actualRole,
                'userSchools' => $result,
                'instructors' => $instructorsResult,
                'selectedInstructor' => $selectedInstructor
            ]
        );
    }

    public function actionImportBNCC()
    {
        $import = new BNCCImport();

        $import->importCSVInfantil();
        $disciplines = [
            'Arte',
            'Ciências',
            'Educação Física',
            'Ensino religioso',
            'Geografia',
            'História',
            'Língua Inglesa',
            'Língua Portuguesa',
            'Matemática'
        ];

        foreach ($disciplines as $discipline) {
            $import->importCSVFundamental($discipline);
        }
    }

    public function actionChangelog()
    {
        $this->render('changelog');
    }

    public function actionInstanceConfig()
    {
        $configs = InstanceConfig::model()->findAll();
        $this->render('instanceConfig', [
            "configs" => $configs
        ]);
    }

    public function actionEditInstanceConfigs()
    {
        $changed = false;
        foreach ($_POST["configs"] as $config) {
            $instanceConfig = InstanceConfig::model()->findByPk($config["id"]);
            if ($instanceConfig->value != $config["value"]) {
                $instanceConfig->value = $config["value"];
                $instanceConfig->save();
                $changed = true;
            }
        }
        echo json_encode(["valid" => $changed, "text" => "Configurações alteradas com sucesso.</br>"]);
    }
    public function actionAuditory()
    {
        $schools = Yii::app()->db->createCommand("select inep_id, `name` from school_identification order by `name`")->queryAll();
        $users = Yii::app()->db->createCommand("select id, `name` from users order by `name`")->queryAll();
        $this->render('auditory', [
            "schools" => $schools,
            "users" => $users,
            'schoolyear' => Yii::app()->user->year
        ]);
    }

    public function actionGetAuditoryLogs()
    {
        $criteria = new CDbCriteria();

        $arr = explode('/', $_POST["initialDate"]);
        $initialDate = $arr[2] . "-" . $arr[1] . "-" . $arr[0] . " 00:00:00";
        $arr = explode('/', $_POST["finalDate"]);
        $finalDate = $arr[2] . "-" . $arr[1] . "-" . $arr[0] . " 23:59:59";
        $criteria->addBetweenCondition('date', $initialDate, $finalDate);

        if ($_POST["school"] !== "") {
            $criteria->addColumnCondition(['school_fk' => $_POST["school"]]);
        }

        if ($_POST["action"] !== "") {
            $criteria->addColumnCondition(['crud' => $_POST["action"]]);
        }

        if ($_POST["user"] !== "") {
            $criteria->addColumnCondition(['user_fk' => $_POST["user"]]);
        }

        $countCriteria = $criteria;

        foreach ($_POST["order"] as $key => $order) {
            switch ($_POST["columns"][$order["column"]]["data"]) {
                case "school":
                    $criteria->join = "join school_identification on inep_id = school_fk";
                    $criteria->order .= "TRIM(school_identification.name)";
                    break;
                case "user":
                    $criteria->join = "join users on users.id = user_fk";
                    $criteria->order .= "TRIM(users.name)";
                    break;
                case "date":
                    $criteria->order .= $_POST["columns"][$order["column"]]["data"];
                    break;
            }
            $criteria->order .= " " . $order["dir"];
            if ($key < count($_POST["order"]) - 1) {
                $criteria->order .= ", ";
            }
        }

        $criteria->limit = $_POST["length"];
        $criteria->offset = $_POST["start"];

        $logs = Log::model()->findAll($criteria);
        $logsCount = Log::model()->count($countCriteria);

        $result["recordsTotal"] = $result["recordsFiltered"] = $logsCount;

        $result["data"] = [];
        foreach ($logs as $log) {
            $array["school"] = $log->schoolFk->name;
            $array["user"] = $log->userFk->name;
            $array["action"] = $log->crud == "U" ? "Editar" : ($log->crud == "C" ? "Criar" : "Remover");
            $date = new \DateTime($log->date);
            $array["date"] = $date->format("d/m/Y H:i:s");
            $array["event"] = $log->loadIconsAndTexts($log)["text"];
            array_push($result["data"], $array);
        }
        echo json_encode($result);
    }
}
