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
                    'import', 'export', 'update', 'manageUsers', 'acl', 'backup', 'data', 'exportStudentIdentify', 'syncExport',
                    'syncImport', 'exportToMaster', 'exportStudents', 'exportGrades', 'exportFaults', 'clearMaster', 'importFromMaster',
                    'gradesStructure', 'indexGradesStructure', 'instanceConfig', 'editInstanceConfigs'
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

    public function actionExports()
    {
        $this->render('exports');
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

    public function actionExportStudents()
    {
        $pathFile = "./app/export/InfoTagCSV/students_" . Yii::app()->user->year . ".csv";

        $sql = "select
            if (si.inep_id is null, '', si.inep_id) inep_aluno,
            if (si.name is null, '', si.name) nome_aluno,
            if (si.birthday is null, '', si.birthday) data_nascimento,
            if (sdaa.cpf is null, '', sdaa.cpf) cpf,
            if (si.sex is null, '', si.sex) sexo,
            if (si.color_race is null, '', si.color_race) cor_raca,
            if (concat(sdaa.address, \", \", sdaa.`number`, \", \", sdaa.complement) is null
                or trim(concat(sdaa.address, \", \", sdaa.`number`, \", \", sdaa.complement)) = ', , ',
                '',
                concat(sdaa.address, \", \", sdaa.`number`, \", \", sdaa.complement)) endereco,
            if (sdaa.neighborhood is null, '', sdaa.neighborhood) bairro,
            if (sdaa.cep is null, '', sdaa.cep) localizacao,
            if (se.public_transport is null, '', se.public_transport) usa_transporte,
            if (si.bf_participator  IS NULL OR TRIM(si.bf_participator ) = '', 0, si.bf_participator) as recebe_bolsa_familia,
            if (si2.name is null, '', si2.name) nome_da_escola,
            if (esvm.alias is null, '', esvm.alias) etapa,
            if (c.name is null, '', c.name) turma
        from student_enrollment se
            left join student_identification si
            on (se.student_fk = si.id)
            left join student_documents_and_address sdaa
            on (se.student_fk = sdaa.student_fk)
            left join classroom c
            on (se.classroom_fk = c.id)
            left join school_identification si2
            on (se.school_inep_id_fk = si2.inep_id)
            left join edcenso_stage_vs_modality esvm
            on (se.edcenso_stage_vs_modality_fk = esvm.id)
        where 1=1
        and c.school_year = " . Yii::app()->user->year;

        $result = Yii::app()->db->createCommand($sql)->queryAll();

        foreach($result as &$r) {
            switch($r["cor_raca"]) {
                case "0":
                default:
                    $r["cor_raca"] = "Não declarada";
                    break;
                case "1":
                    $r["cor_raca"] = "Branca";
                    break;
                case "2":
                    $r["cor_raca"] = "Preta";
                    break;
                case "3":
                    $r["cor_raca"] = "Parda";
                    break;
                case "4":
                    $r["cor_raca"] = "Amarela";
                    break;
                case "5":
                    $r["cor_raca"] = "Indígena";
                    break;
            }
        }

        $this->exportToCSV($result, $pathFile);
    }

    public function actionExportGrades()
    {
        $pathFile = "./app/export/InfoTagCSV/grades_" . Yii::app()->user->year . ".csv";

        $sql = "select
            if (si.inep_id is null, '', si.inep_id) inep_aluno,
            if (si.name is null, '', si.name) nome_aluno,
            if (c.name is null, '', c.name) turma,
            if (ed.name  is null, '', ed.name) disciplina,
            if (gr.grade_1 is null, '', gr.grade_1) nota_01,
            if (gr.grade_2  is null, '', gr.grade_2) nota_02,
            if (gr.rec_partial_1 is null, '', gr.rec_partial_1) recuperacao_semestral_I,
            if (gr.grade_3  is null, '', gr.grade_3) nota_03,
            if (gr.grade_4  is null, '', gr.grade_4) nota_04,
            if (gr.rec_partial_2  is null, '', gr.rec_partial_2) recuperacao_semestral_II,
            if (gr.rec_final  is null, '', gr.rec_final) recuperacao_final
        from student_enrollment se
            join student_identification si
            on (se.student_fk = si.id)
            join classroom c
            on (se.classroom_fk = c.id)
            left join edcenso_stage_vs_modality esvm
            on (c.edcenso_stage_vs_modality_fk = esvm.id)
            left join curricular_matrix cm
            on (cm.stage_fk = esvm.id)
            left join edcenso_discipline ed
            on (cm.discipline_fk = ed.id)
            left join grade_results gr
            on (gr.enrollment_fk = se.id and gr.discipline_fk = ed.id)
        where 1=1
        and c.school_year = " . Yii::app()->user->year;

        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $this->exportToCSV($result, $pathFile);
    }

    public function actionExportFaults()
    {
        $pathFile = "./app/export/InfoTagCSV/faults_" . Yii::app()->user->year . ".csv";

        $result = [];

        $classrooms = Classroom::model()->findAllByAttributes(["school_year" => Yii::app()->user->year]);
        foreach ($classrooms as $classroom) {
            if ($classroom->calendar_fk != null) {

                $dates = Yii::app()->db->createCommand("select start_date, end_date from calendar join classroom on calendar.id = classroom.calendar_fk where classroom.id = " . $classroom->id)->queryRow();
                $start    = (new DateTime($dates["start_date"]))->modify('first day of this month');
                $end      = (new DateTime($dates["end_date"]))->modify('first day of next month');
                $interval = DateInterval::createFromDateString('1 month');
                $period   = new DatePeriod($start, $interval, $end);
                $months = [];
                foreach ($period as $dt) {
                    array_push($months, $dt->format("m/Y"));
                }

                foreach($classroom->studentEnrollments as $studentEnrollment) {
                    $usedDaysForMinorEducation = [];
                    foreach($months as $month) {
                        $studentIdentification = $studentEnrollment->studentFk;
                        $row["inep_aluno"] = $studentIdentification->inep_id;
                        $row["nome_aluno"] = $studentIdentification->name;
                        $row["turma"] = $classroom->name;
                        $row["mes"] = $month;
                        $row["total_faltas"] = 0;
                        $classFaults = ClassFaults::model()->findAllBySql("select cf.* from class_faults cf join schedule s on s.id = cf.schedule_fk where s.classroom_fk = :classroom_fk and cf.student_fk = :student_fk", ["classroom_fk" => $classroom->id, "student_fk" => $studentIdentification->id]);
                        foreach($classFaults as $classFault) {
                            $schedule = $classFault->scheduleFk;
                            if ($month == str_pad($schedule->month, 2, "0", STR_PAD_LEFT) . "/" . $schedule->year) {
                                if (TagUtils::isStageMinorEducation($classroom->edcenso_stage_vs_modality_fk)) {
                                    if (!in_array($schedule->day . $schedule->month . $schedule->year, $usedDaysForMinorEducation)) {
                                        $row["total_faltas"]++;
                                        array_push($usedDaysForMinorEducation, $schedule->day . $schedule->month . $schedule->year);
                                    }
                                } else {
                                    $row["total_faltas"]++;
                                }
                            }
                        }

                        array_push($result, $row);
                    }
                }
            }
        }
        $this->exportToCSV($result, $pathFile);
    }

    private function exportToCSV($result, $path)
    {
        try {
            // Create Directories
            $this->createDirectoriesIfNotExist($path);

            // Create a file pointer with PHP.
            $output = fopen($path, 'w');

            if ($output !== false) {
                // Escrever os cabeçalhos no arquivo CSV
                fputcsv($output, array_keys($result[0]), ';');

                // Escrever os dados no arquivo CSV
                foreach ($result as $row) {
                    $row = array_map('strval', $row); // Converter todos os valores para string
                    fputcsv($output, $row, ";");
                }
                // Fechar o arquivo
                fclose($output);
            }

            // Set PHP headers for CSV output.
            header("Content-Disposition: attachment; filename=\"" . basename($path) . "\"");
            header("Content-Type: application/force-download");
            header("Content-Length: " . filesize($path));
            header("Connection: close");
            readfile($path);

            $this->redirect(array('exports'));
        } catch (Exception $e) {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Error na exportação: ' . $e->getMessage()));
            $this->redirect(array('exports'));
        }
        Yii::app()->user->setFlash('error', Yii::t('default', 'Error na importação: ' . $e->getMessage()));
    }

    private function createDirectoriesIfNotExist($filePath)
    {
        // Extrai o diretório do caminho do arquivo
        $directoryPath = dirname($filePath);

        // Verifica se o diretório já existe
        if (!is_dir($directoryPath)) {
            // Tenta criar o diretório recursivamente
            if (!mkdir($directoryPath, 0777, true)) {
                // Caso falhe, lança uma exceção
                throw new Exception("Falha ao criar diretórios: $directoryPath");
            }
        }
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

            $importModel->saveInstructorDataDB($dataDecoded['instructor_identification'],
                $dataDecoded['instructor_documents_and_address'],
                $dataDecoded['instructor_variable_data']);
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
            if(!isset($_POST['schools']) && ($_POST['Role']) != 'admin' && ($_POST['Role']) != 'nutritionist' && ($_POST['Role']) != 'reader')
            {
                Yii::app()->user->setFlash('error', Yii::t('default', 'É necessário atribuir uma escola para o novo usuário criado!'));
                $this->redirect(['index']);
            }
            if (!isset($modelValidate)) {
                $model->attributes = $_POST['Users'];
                if ($model->validate()) {
                    $passwordHasher = new PasswordHasher;
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
        $instructors = InstructorIdentification::model()->findAllByAttributes(['users_fk' => null],
            ['select' => 'id, name']);
        $instructorsResult = array_reduce($instructors, function ($carry, $item) {
            $carry[$item['id']] = $item['name'];
            return $carry;
        }, []);
        $this->render('createUser', ['model' => $model, 'instructors' => $instructorsResult]);
    }
    public function actionIndexGradesStructure() {
        $dataProvider = GradeRules::model()->search();
        $dataProvider->pagination = false;

        $this->render('indexGradesStructure', [
            'dataProvider' => $dataProvider
        ]);
    }
    public function actionGradesStructure()
    {
        $stages = Yii::app()->db->createCommand("
            select
                distinct esvm.id,
                esvm.name
            from edcenso_stage_vs_modality esvm
                join curricular_matrix cm on cm.stage_fk = esvm.id order by esvm.name")
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
        $grade_rules_id = Yii::app()->request->getPost("grade_rules_id");

        $result = [];
        $result["unities"] = [];

        $criteria = new CDbCriteria();
        $criteria->alias = "gu";
        $criteria->condition = "grade_rules_fk = :grade_rules_fk";
        $criteria->addInCondition("gu.type", [GradeUnity::TYPE_UNITY, GradeUnity::TYPE_UNITY_BY_CONCEPT, GradeUnity::TYPE_UNITY_WITH_RECOVERY]);
        $criteria->params = array_merge([":grade_rules_fk" => $grade_rules_id], $criteria->params);
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

        $criteria->condition = "grade_rules_fk = :grade_rules_fk and gu.type = :type";
        $criteria->params = [":grade_rules_fk" => $grade_rules_id, ":type" => GradeUnity::TYPE_FINAL_RECOVERY];

        $finalRecovery = GradeUnity::model()
            ->with("gradeUnityModalities")
            ->find($criteria);
        $result["final_recovery"] = $finalRecovery->attributes;
        $result["final_recovery"]["modalities"] = [];
        foreach ($finalRecovery->gradeUnityModalities as $gradeUnityModality) {
            array_push($result["final_recovery"]["modalities"], $gradeUnityModality->attributes);
        }

        $gradeRules = GradeRules::model()
            ->findByPk(
                $grade_rules_id
            );

            $stageIds = Yii::app()->db->createCommand("
            SELECT DISTINCT esvm.id
            FROM
                edcenso_stage_vs_modality esvm
            JOIN
                curricular_matrix cm ON cm.stage_fk = esvm.id
            JOIN
                grade_rules_vs_edcenso_stage_vs_modality grvesvm ON grvesvm.edcenso_stage_vs_modality_fk = esvm.id
            WHERE
                grvesvm.grade_rules_fk = :grade_rule
            ORDER BY
                esvm.name
        ")
        ->bindParam(':grade_rule', $grade_rules_id)
        ->queryColumn();
        $result["edcenso_stage_vs_modality_fk"] = $stageIds;
        $result["approvalMedia"] = $gradeRules->approvation_media;
        $result["finalRecoverMedia"] = $gradeRules->final_recover_media;
        $result["mediaCalculation"] = $gradeRules->grade_calculation_fk;
        $result["ruleType"] = $gradeRules->rule_type;
        $result["ruleName"] = $gradeRules->name;
        $result["hasFinalRecovery"] = (bool)$gradeRules->has_final_recovery;

        $result["partialRecoveries"] = [];

        $gPartialRecoveries = GradePartialRecovery::model()->findAllByAttributes(array('grade_rules_fk' => $gradeRules->id));
        foreach ($gPartialRecoveries as $partialRecovery) {
            $resultPartialRecovery = array();
            $resultPartialRecovery["id"] = $partialRecovery->id;
            $resultPartialRecovery["name"] = $partialRecovery->name;
            $resultPartialRecovery["order"] = $partialRecovery->order_partial_recovery;
            $resultPartialRecovery["grade_calculation_fk"] = $partialRecovery->grade_calculation_fk;
            $resultPartialRecovery["weights"] = [];
            if ($partialRecovery->gradeCalculationFk->name == "Peso") {
                $gradeRecoveryWeights = GradePartialRecoveryWeights::model()->findAllByAttributes(["partial_recovery_fk" => $partialRecovery->id]);
                foreach ($gradeRecoveryWeights as $weight) {
                    array_push($resultPartialRecovery["weights"],
                        [
                            "id" => $weight["id"],
                            "unity_fk" => $weight["unity_fk"],
                            "weight" => $weight["weight"],
                            "name" => $weight["unity_fk"] !== null ? $weight->unityFk->name : 'recuperação'
                        ]
                    );
                }
            }

            $unities = GradeUnity::model()->findAllByAttributes(array('parcial_recovery_fk' => $partialRecovery->id));
            $resultPartialRecovery["unities"] = $unities;

            array_push($result["partialRecoveries"], $resultPartialRecovery);
        }

        $result["partialRecoveries"] = [];

        $gPartialRecoveries = GradePartialRecovery::model()->findAllByAttributes(array('grade_rules_fk' => $gradeRules->id));
        foreach ($gPartialRecoveries as $partialRecovery) {
            $resultPartialRecovery = array();
            $resultPartialRecovery["id"] = $partialRecovery->id;
            $resultPartialRecovery["name"] = $partialRecovery->name;
            $resultPartialRecovery["order"] = $partialRecovery->order_partial_recovery;
            $resultPartialRecovery["grade_calculation_fk"] = $partialRecovery->grade_calculation_fk;
            $resultPartialRecovery["semester"] = $partialRecovery->semester;
            $resultPartialRecovery["weights"] = [];
            if($partialRecovery->gradeCalculationFk->name == "Peso") {
                $gradeRecoveryWeights = GradePartialRecoveryWeights::model()->findAllByAttributes(["partial_recovery_fk"=>$partialRecovery->id]);
                foreach($gradeRecoveryWeights as $weight){
                    array_push($resultPartialRecovery["weights"],
                    [
                     "id" => $weight["id"],
                     "unity_fk" => $weight["unity_fk"],
                     "weight" => $weight["weight"],
                     "name" => $weight["unity_fk"] !== null ? $weight->unityFk->name : 'recuperação'
                     ]
                    );
                }
            }

            $unities = GradeUnity::model()->findAllByAttributes(array('parcial_recovery_fk' => $partialRecovery->id));
            $resultPartialRecovery["unities"]  = $unities;

            array_push($result["partialRecoveries"], $resultPartialRecovery);
        }

        echo CJSON::encode($result);
    }

    public function actionSaveUnities()
    {
        set_time_limit(0);
        ignore_user_abort();
        $gradeRulesId = Yii::app()->request->getPost("grade_rules_id");
        $gradeRulesName = Yii::app()->request->getPost("grade_rules_name");
        $reply = Yii::app()->request->getPost("reply");
        $stages = Yii::app()->request->getPost("stage");
        $unities = Yii::app()->request->getPost("unities");
        $approvalMedia = Yii::app()->request->getPost("approvalMedia");
        $finalRecoverMedia = Yii::app()->request->getPost("finalRecoverMedia");
        $calculationFinalMedia = Yii::app()->request->getPost("finalMediaCalculation");
        $finalRecovery = Yii::app()->request->getPost("finalRecovery");
        $finalRecoveryWeight = Yii::app()->request->getPost("finalRecoveryWeight");
        $finalMediaWeight = Yii::app()->request->getPost("finalMediaWeight");
        $ruleType = Yii::app()->request->getPost("ruleType");
        $hasFinalRecovery = Yii::app()->request->getPost("hasFinalRecovery") === "true";
        $hasPartialRecovery = Yii::app()->request->getPost("hasPartialRecovery") === "true";
        $partialRecoveries = Yii::app()->request->getPost("partialRecoveries");

        try {
            $usecase = new UpdateGradeStructUsecase(
                $gradeRulesId,
                $gradeRulesName,
                $reply,
                $stages,
                $unities,
                $approvalMedia,
                $finalRecoverMedia,
                $calculationFinalMedia,
                $hasFinalRecovery,
                $ruleType,
                $hasPartialRecovery,
                $partialRecoveries
            );
           $gradeRules = $usecase->exec();

            if ($hasFinalRecovery === true) {

                $recoveryUnity = GradeUnity::model()->find('id = :id', array(':id' => $finalRecovery["id"]));

                if ($recoveryUnity === null) {
                    $recoveryUnity = new GradeUnity();
                }

                $recoveryUnity->name = $finalRecovery["name"];
                $recoveryUnity->type = "RF";
                $recoveryUnity->grade_calculation_fk = $finalRecovery["grade_calculation_fk"];
                $recoveryUnity->grade_rules_fk = $gradeRulesId;
                $recoveryUnity->final_recovery_avarage_formula = $finalRecovery["final_recovery_avarage_formula"];

                $gradeCalculation = GradeCalculation::model()->findByPk($finalRecovery["grade_calculation_fk"]);

                if($gradeCalculation->name === "Peso"){
                    $recoveryUnity->weight_final_media = $finalRecovery["WeightfinalMedia"];
                    $recoveryUnity->weight_final_recovery = $finalRecovery["WeightfinalRecovery"];
                }

                if (!$recoveryUnity->validate()) {
                    $validationMessage = Yii::app()->utils->stringfyValidationErrors($recoveryUnity);
                    throw new CHttpException(400, "Não foi possivel salvar dados da recuperação final: \n" . $validationMessage, 1);
                }

                $recoveryUnity->save();


                    $modalityModel = GradeUnityModality::model()->findByAttributes(["grade_unity_fk"=>$recoveryUnity->id]);
                    if ($modalityModel == null) {
                        $modalityModel = new GradeUnityModality();
                    }
                    $modalityModel->name = "Avaliação/Prova";
                    $modalityModel->type = "R";
                    $modalityModel->weight = null;
                    $modalityModel->grade_unity_fk = $recoveryUnity->id;

                    if (!$modalityModel->validate()) {
                        throw new CantSaveGradeUnityModalityException($modalityModel);
                    }

                    $modalityModel->save();

            } elseif ($hasFinalRecovery === false && $finalRecovery["operation"] === "delete" && $gradeRules->rule_type === "N") {
                $recoveryUnity = GradeUnity::model()->find('id = :id', array(':id' => $finalRecovery["id"]));
                $recoveryUnity?->delete();
                echo json_encode(["valid" => true, "gradeRules" => $gradeRules->id]);
                Yii::app()->end();
            }


            echo json_encode(["valid" => true, "gradeRules" => $gradeRules->id]);
        } catch (\Throwable $th) {
            Yii::log($th->getMessage(), CLogger::LEVEL_ERROR);
            Yii::log($th->getTraceAsString(), CLogger::LEVEL_ERROR);

            throw $th;
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
            $model->name = $users["name"];
            $model->username = $users["username"];
            $model->active = $users["active"];
            if ($model->validate()) {
                if ($users["password"] !== "") {
                    $passwordHasher = new PasswordHasher;
                    $model->password = $passwordHasher->bcriptHash($users['password']);
                }
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
