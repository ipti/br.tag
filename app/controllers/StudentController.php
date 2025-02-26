<?php
Yii::import('application.modules.sedsp.models.Student.*');
Yii::import('application.modules.sedsp.datasources.sed.Student.*');
Yii::import('application.modules.sedsp.mappers.*');
Yii::import('application.modules.sedsp.usecases.Enrollment.*');
Yii::import('application.modules.sedsp.models.Enrollment.*');
Yii::import('application.modules.sedsp.usecases.*');
Yii::import('application.modules.sedsp.usecases.Student.*');
Yii::import('application.modules.sedsp.interfaces.*');
Yii::import('application.modules.sedsp.datasources.sed.Enrollment.*');


class StudentController extends Controller implements AuthenticateSEDTokenInterface
{
    //@done s1 - validação de todos os campos - Colocar uma ? para explicar as regras de cada campo(em todas as telas)
    //@done s1 - Recuperar endereço pelo CEP
    //@done s1 - validar CPF
    //@done s1 - Campo Cartórios - Colocar em ordem alfabética
    //@done s1 - Campo TIpo de Certidão Civil (Add as opções)
    //@done s1 - atualizar dependencia de select2
    //@done s1 - corrigir o deletar
    const CREATE = 'create';
    const UPDATE = 'update';
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'fullmenu';
    private $STUDENT_IDENTIFICATION = 'StudentIdentification';
    private $STUDENT_DOCUMENTS_AND_ADDRESS = 'StudentDocumentsAndAddress';
    private $STUDENT_ENROLLMENT = 'StudentEnrollment';
    private $STUDENT_RESTRICTIONS = 'StudentRestrictions';
    private $STUDENT_DISORDER = 'StudentDisorder';

    public function authenticateSedToken()
    {
        $loginUseCase = new LoginUseCase();
        $loginUseCase->checkSEDToken();
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array(
                    'index',
                    'view',
                    'comparestudentname',
                    'getstudentajax',
                    'syncToSedsp',
                    'gettransferclassrooms',
                    'comparestudentcpf',
                    'comparestudentcivilregisterenrollmentnumber',
                    'comparestudentcertificate',
                    'create',
                    'update',
                    'transfer',
                    'getcities',
                    'getnotaryoffice',
                    'getnations',
                    'delete'),
                'users' => array('@'),
            ),
            array(
                'deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'modelStudentIdentification' => $this->loadModel($id, $this->STUDENT_IDENTIFICATION),
            'modelStudentDocumentsAndAddress' => $this->loadModel($id, $this->STUDENT_DOCUMENTS_AND_ADDRESS),
        ));
    }

    public function actionGetCities()
    {
        $register_type = isset($_GET["rt"]) ? $_GET["rt"] : 0;
        $uf = null;
        if ($register_type == 0) {
            $student = new StudentIdentification();
            $student->attributes = $_POST[$this->STUDENT_IDENTIFICATION];
            $uf = (int)$student->edcenso_uf_fk;
        } elseif ($register_type == 1) {
            $student = new StudentDocumentsAndAddress();
            $student->attributes = $_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS];
            $uf = (int)$student->notary_office_uf_fk;
        } elseif ($register_type == 2) {
            $student = new StudentDocumentsAndAddress();
            $student->attributes = $_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS];
            $uf = (int)$student->edcenso_uf_fk;
        }

        $data = EdcensoCity::model()->findAll('edcenso_uf_fk=:uf_id', array(':uf_id' => $uf));
        $data = CHtml::listData($data, 'id', 'name');

        echo CHtml::tag('option', array('value' => null), 'Selecione uma cidade', true);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    public function actionGetStudentAjax()
    {
        $requestData = $_POST;

        $columns[0] = 't.id';
        $columns[1] = 'name';
        $columns[2] = 'filiation_1';
        $columns[3] = 'birthday';
        $columns[4] = 'cpf';
        $columns[5] = 'inep_id';
        $columns[6] = 'actions';
        if (Yii::app()->features->isEnable("FEAT_SEDSP")) {
            $columns[6] = 'sedsp_sync';
        }

        $criteria = new CDbCriteria();


        // Filtrar a pesquisa
        if (!empty($requestData['search']['value'])) {
            $criteria->condition = "t.id LIKE '%" . $requestData['search']['value'] . "%' OR " .
                "name LIKE '%" . $requestData['search']['value'] . "%' OR " .
                "filiation_1 LIKE '%" . $requestData['search']['value'] . "%' OR " .
                "birthday LIKE '%" . $requestData['search']['value'] . "%' OR " .
                "documentsFk.cpf LIKE '%" . $requestData['search']['value'] . "%' OR " .
                "inep_id LIKE '%" . $requestData['search']['value'] . "%'";
        }

        // Obter o número total de registros
        $totalData = StudentIdentification::model()->with("documentsFk")->count();
        $totalFiltered = StudentIdentification::model()->with("documentsFk")->count($criteria);

        // Paginação
        $start = $requestData['start'];
        $length = $requestData['length'];

        $criteria->offset = $start;
        $criteria->limit = $length;

        // Ordem
        $sortColumn = $columns[$requestData['order'][0]['column']];
        $sortDirection = $requestData['order'][0]['dir'];
        $criteria->order = $sortColumn . " " . $sortDirection;


        $students = StudentIdentification::model()->with("documentsFk")->findAll($criteria);


        // Formatar os dados de saída
        $data = array();
        foreach ($students as $student) {
            $nestedData = array();
            $nestedData[] = $student->id;
            $nestedData[] = "<a href='/?r=student/update&id=" . $student->id . "' cursor: pointer;>" . $student->name . "</a>";
            $nestedData[] = $student->filiation_1;
            $nestedData[] = $student->birthday;
            $nestedData[] = $student->documentsFk->cpf;
            $nestedData[] = $student->inep_id;
            $nestedData[] = "<a style='cursor: pointer;' title='Editar' id='student-edit'  href='/?r=student/update&id=" . $student->id . "'>
                            <img src='" . Yii::app()->theme->baseUrl . '/img/editar.svg' . "' alt='Editar'></img>
                            </a>&nbsp;"
                . "<a style='cursor: pointer;' title='Excluir'
                            id='student-delete' href='/?r=student/delete&id=" . $student->id . "'>
                            <img src='" . Yii::app()->theme->baseUrl . '/img/deletar.svg' . "' alt='Excluir'></img>
                            </a>";
            if (Yii::app()->features->isEnable("FEAT_SEDSP")) {
                $sync = "<a style='cursor: pointer;display: inline-block' title='Sincronizar' id='student-sync' class='" . ($student->sedsp_sync ? "sync" : "unsync") . "' href='/?r=student/syncToSedsp&id=" . $student->id . "'>";
                $sync .= $student->sedsp_sync
                    ? '<img src="' . Yii::app()->theme->baseUrl . '/img/SyncTrue.png" style="width: 25px;text-align: center">'
                    : '<img src="' . Yii::app()->theme->baseUrl . '/img/notSync.png" style="width: 25px;text-align: center">';
                $sync .= "</a>&nbsp;";
                $nestedData[] = $sync;
            }
            $data[] = $nestedData;
        }

        // Saída JSON
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function actionSyncToSedsp($id)
    {
        $modelStudentIdentification = new StudentIdentification();

        $this->authenticateSedToken();
        $syncResult = (object) $modelStudentIdentification->syncStudentWithSED($id, new StudentEnrollment(), self::UPDATE);

        if ($syncResult->identification->outErro !== null) {
            $flash = "error";
            $msg = 'Não foi possível sincronizar o aluno ' . $modelStudentIdentification->name . '. Motivo: ' . $syncResult->identification->outErro;
        } else {
            $flash = "success";
            $msg = "Sincronização realizada com sucesso!";
        }

        Yii::app()->user->setFlash($flash, $msg);
        $this->redirect(array('index'));
    }

    public function actionGetNotaryOffice()
    {
        $student = new StudentDocumentsAndAddress();
        $student->attributes = $_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS];

        $data = EdcensoNotaryOffice::model()->findAllByAttributes(
            array('city' => (int)$student->notary_office_city_fk), array('order' => 'name')
        );
        $data = CHtml::listData($data, 'cod', 'name');

        echo CHtml::tag('option', array('value' => null), 'Selecione um cartório', true);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
        echo CHtml::tag('option', array('value' => '7177'), CHtml::encode('OUTROS'), true);
    }

    public function actionGetNations()
    {
        $student = new StudentIdentification();
        $student->attributes = $_POST[$this->STUDENT_IDENTIFICATION];

        $where = "";
        if ($student->nationality == 3) {
            $where = "";
            echo CHtml::tag('option', array('value' => null), 'Selecione uma nação', true);
        } else {
            $where = "id = 76";
        }
        $data = EdcensoNation::model()->findAll($where);
        $data = CHtml::listData($data, 'id', 'name');

        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    public function actionCompareStudentName()
    {
        $data = StudentIdentification::model()->findAll();
        $result = [];
        foreach ($data as $student) {
            $result[$student->name] = $student->id;
        }
        echo json_encode($result);
    }

    public function actionCompareStudentCertificate($civil_certification_term_number)
    {
        $data = StudentDocumentsAndAddress::model()->find('civil_certification_term_number=:civil_certification_term_number', array(':civil_certification_term_number' => $civil_certification_term_number));
        $result = [];
        $result[$data->student_fk] = $data->id;

        echo json_encode($result);
    }

    public function actionCompareStudentCivilRegisterEnrollmentNumber($civil_register_enrollment_number)
    {
        $data = StudentDocumentsAndAddress::model()->find('civil_register_enrollment_number=:civil_register_enrollment_number', array(':civil_register_enrollment_number' => $civil_register_enrollment_number));
        $result = [];
        $result[$data->student_fk] = $data->id;

        echo json_encode($result);
    }

    public function actionCompareStudentCpf($student_cpf)
    {
        $data = StudentDocumentsAndAddress::model()->find('cpf=:cpf', array(':cpf' => $student_cpf));
        $result = [];
        $result[$data->id] = $data->id;

        echo json_encode($result);
    }


    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $modelStudentIdentification = new StudentIdentification;
        //@todo Checar o paramentro antes
        $modelStudentIdentification->deficiency = 0;
        $modelStudentDocumentsAndAddress = new StudentDocumentsAndAddress;
        $modelEnrollment = new StudentEnrollment;
        $modelStudentRestrictions = new StudentRestrictions;
        $modelStudentDisorder = new StudentDisorder;

        $vaccines = Vaccine::model()->findAll(array('order' => 'name'));
        $studentVaccinesSaves = StudentVaccine::model()->findAll(['select' => 'vaccine_id', 'condition' => 'student_id=:student_id', 'params' => [':student_id' => $modelStudentIdentification->id]]);
        if ($studentVaccinesSaves) {
            $studentVaccinesSaves = array_map(function ($item) {
                return $item->vaccine_id;
            }, $studentVaccinesSaves);
        }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model
        if (
            isset($_POST[$this->STUDENT_IDENTIFICATION]) && isset($_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS])
            && isset($_POST[$this->STUDENT_RESTRICTIONS]) && isset($_POST[$this->STUDENT_DISORDER])
        ) {
            $modelStudentIdentification->attributes = $_POST[$this->STUDENT_IDENTIFICATION];
            $modelStudentDocumentsAndAddress->attributes = $_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS];
            $modelStudentRestrictions->attributes = $_POST[$this->STUDENT_RESTRICTIONS];
            $modelStudentDisorder->attributes = $_POST[$this->STUDENT_DISORDER];

            // Validação CPF->Certidão->Nome
            if ($modelStudentDocumentsAndAddress->cpf != null) {
                $student_test_cpf = StudentDocumentsAndAddress::model()->find('cpf=:cpf', array(':cpf' => $modelStudentDocumentsAndAddress->cpf));
                if (isset($student_test_cpf)) {
                    Yii::app()->user->setFlash('error', Yii::t('default', "O CPF do responsável informado já está cadastrado"));
                    $this->redirect(array('index'));
                }
            }
            if ($modelStudentDocumentsAndAddress->civil_certification_term_number != null) {
                $student_test_certificate = StudentDocumentsAndAddress::model()->find('civil_certification_term_number=:civil_certification_term_number', array(':civil_certification_term_number' => $modelStudentDocumentsAndAddress->civil_certification_term_number));
                if (isset($student_test_certificate)) {
                    Yii::app()->user->setFlash('error', Yii::t('default', "O Nº do Termo da Certidão informado já está cadastrado"));
                    $this->redirect(array('index'));
                }
            }

            //Atributos comuns entre as tabelas
            $modelStudentDocumentsAndAddress->school_inep_id_fk = $modelStudentIdentification->school_inep_id_fk;
            $modelStudentDocumentsAndAddress->student_fk = $modelStudentIdentification->inep_id;
            date_default_timezone_set("America/Recife");
            $modelStudentIdentification->last_change = date('Y-m-d G:i:s');

            if(Yii::app()->features->isEnable("FEAT_SEDSP")){
                $modelStudentIdentification->scenario = "formSubmit";
            }


            if ($modelStudentIdentification->validate() && $modelStudentDocumentsAndAddress->validate()) {

                if ($modelStudentIdentification->save()) {
                    $modelStudentDocumentsAndAddress->id = $modelStudentIdentification->id;
                    $modelStudentRestrictions->student_fk = $modelStudentIdentification->id;
                    $modelStudentDisorder->student_fk = $modelStudentIdentification->id;

                    if ($modelStudentDocumentsAndAddress->validate()) {
                        if ($modelStudentDocumentsAndAddress->save() && $modelStudentRestrictions->save() && $modelStudentDisorder->save()) {
                            $saved = true;
                            if (
                                isset($_POST[$this->STUDENT_ENROLLMENT], $_POST[$this->STUDENT_ENROLLMENT]["classroom_fk"])
                                && !empty($_POST[$this->STUDENT_ENROLLMENT]["classroom_fk"])
                            ) {
                                $modelEnrollment = new StudentEnrollment;
                                $modelEnrollment->attributes = $_POST[$this->STUDENT_ENROLLMENT];
                                $modelEnrollment->school_inep_id_fk = $modelStudentIdentification->school_inep_id_fk;
                                $modelEnrollment->student_fk = $modelStudentIdentification->id;
                                $modelEnrollment->create_date = date('Y-m-d');
                                if ($modelEnrollment->status == 1) {
                                    $modelEnrollment->enrollment_date = date('Y-m-d');
                                }
                                $modelEnrollment->daily_order = $modelEnrollment->getDailyOrder();
                                $saved = false;
                                if ($modelEnrollment->validate()) {
                                    $saved = $modelEnrollment->save();
                                }
                            }

                            if (isset($_POST['Vaccine']['vaccine_id'])) {
                                if (count($_POST['Vaccine']['vaccine_id']) > 0) {
                                    StudentVaccine::model()->deleteAll("student_id = $modelStudentIdentification->id");

                                    foreach ($_POST['Vaccine']['vaccine_id'] as $vaccine_id) {
                                        $studentVaccine = new StudentVaccine();
                                        $studentVaccine->student_id = $modelStudentIdentification->id;
                                        $studentVaccine->vaccine_id = $vaccine_id;
                                        $studentVaccine->save();
                                    }
                                }
                            }

                            if ($saved) {
                                $flash = "success";
                                $msg = 'O Cadastro de ' . $modelStudentIdentification->name . ' foi criado com sucesso!';

                                if (Yii::app()->features->isEnable("FEAT_SEDSP")) {
                                    $this->authenticateSedToken();
                                    $syncResult = $modelStudentIdentification->syncStudentWithSED($modelStudentIdentification->id, $modelEnrollment, self::CREATE);

                                    if ($syncResult->identification->outErro !== null || $syncResult->enrollment->outErro !== null || $syncResult === false) {
                                        $flash = "error";
                                        $msg = '<span style="color: white;background: #23b923; padding:10px;border-radius: 4px;">Cadastro do aluno ' . $modelStudentIdentification->name .
                                            '  criado com sucesso no TAG, mas não foi possível sincronizá-lo com a SEDSP. Motivo: </span>';
                                        if ($syncResult->identification->outErro) {
                                            $msg .= "<br>Ficha do Aluno: " . $syncResult->identification->outErro;
                                        }
                                        if ($syncResult->enrollment->outErro) {
                                            $msg .= "<br>Matrícula: " . $syncResult->enrollment->outErro;
                                        }
                                    }
                                }

                                Log::model()->saveAction(
                                    "student", $modelStudentIdentification->id, "C", $modelStudentIdentification->name
                                );
                                Yii::app()->user->setFlash($flash, Yii::t('default', $msg));

                                $this->redirect(array('index', 'sid' => $modelStudentIdentification->id));
                            }
                        }
                    }
                }
            }
        }

        $this->render('create', array(
            'modelStudentIdentification' => $modelStudentIdentification,
            'modelStudentDocumentsAndAddress' => $modelStudentDocumentsAndAddress,
            'modelStudentRestrictions' => $modelStudentRestrictions,
            'modelStudentDisorder' => $modelStudentDisorder,
            'modelEnrollment' => $modelEnrollment,
            'vaccines' => $vaccines,
            'studentVaccinesSaves' => $studentVaccinesSaves
        ));
    }


    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $modelStudentIdentification = $this->loadModel($id, $this->STUDENT_IDENTIFICATION);
        $modelStudentDocumentsAndAddress = $this->loadModel($id, $this->STUDENT_DOCUMENTS_AND_ADDRESS);
        $modelStudentRestrictions = $this->loadModel($id, $this->STUDENT_RESTRICTIONS);
        $modelStudentDisorder = $this->loadModel($id, $this->STUDENT_DISORDER);

        $oldCpf = $modelStudentDocumentsAndAddress->cpf;

        $vaccines = Vaccine::model()->findAll(array('order' => 'name'));
        $studentVaccinesSaves = StudentVaccine::model()->findAll(['select' => 'vaccine_id', 'condition' => 'student_id=:student_id', 'params' => [':student_id' => $id]]);
        if ($studentVaccinesSaves) {
            $studentVaccinesSaves = array_map(function ($item) {
                return $item->vaccine_id;
            }, $studentVaccinesSaves);
        }
        $modelEnrollment = new StudentEnrollment();

        if (
            isset($_POST[$this->STUDENT_IDENTIFICATION]) && isset($_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS])
            && isset($_POST[$this->STUDENT_RESTRICTIONS]) && isset($_POST[$this->STUDENT_DISORDER])
        ) {
            $modelStudentIdentification->attributes = $_POST[$this->STUDENT_IDENTIFICATION];
            $modelStudentDocumentsAndAddress->attributes = $_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS];
            $modelStudentRestrictions->attributes = $_POST[$this->STUDENT_RESTRICTIONS];
            $modelStudentDisorder->attributes = $_POST[$this->STUDENT_DISORDER];
            //Atributos comuns entre as tabelas
            $modelStudentDocumentsAndAddress->id = $modelStudentIdentification->id;
            $modelStudentDocumentsAndAddress->school_inep_id_fk = $modelStudentIdentification->school_inep_id_fk;
            $modelStudentDocumentsAndAddress->student_fk = $modelStudentIdentification->id;
            date_default_timezone_set("America/Recife");
            $modelStudentIdentification->last_change = date('Y-m-d G:i:s');

            $newCpf = $_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS]['cpf'];

            if($oldCpf !== $newCpf && $newCpf !== "") {
                $existCpf = StudentDocumentsAndAddress::model()->findByAttributes(array('cpf' => $modelStudentDocumentsAndAddress->cpf));

                if($existCpf !== null) {
                    Yii::app()->user->setFlash(
                        'error', Yii::t('default', 'Já existe um registro associado a este CPF de um aluno cadastrado!')
                    );
                    $this->redirect(array('/student/update', 'id' => $modelStudentDocumentsAndAddress->id));
                }
            }

            if ($modelStudentIdentification->validate() && $modelStudentDocumentsAndAddress->validate()) {
                if ($modelStudentIdentification->save()) {
                    $modelStudentRestrictions->student_fk = $modelStudentIdentification->id;
                    $modelStudentDisorder->student_fk = $modelStudentIdentification->id;
                    $modelStudentDocumentsAndAddress->id = $modelStudentIdentification->id;
                    if ($modelStudentDocumentsAndAddress->save() && $modelStudentRestrictions->save() && $modelStudentDisorder->save()) {
                        $saved = true;
                        if (
                            isset($_POST[$this->STUDENT_ENROLLMENT], $_POST[$this->STUDENT_ENROLLMENT]["classroom_fk"])
                            && !empty($_POST[$this->STUDENT_ENROLLMENT]["classroom_fk"])
                        ) {
                            $modelEnrollment = new StudentEnrollment;
                            $modelEnrollment->attributes = $_POST[$this->STUDENT_ENROLLMENT];
                            $modelEnrollment->school_inep_id_fk = $modelStudentIdentification->school_inep_id_fk;
                            $modelEnrollment->student_fk = $modelStudentIdentification->id;
                            $modelEnrollment->student_inep_id = $modelStudentIdentification->inep_id;
                            $modelEnrollment->create_date = date('Y-m-d');
                            if ($modelEnrollment->status == 1) {
                                $modelEnrollment->enrollment_date = date('Y-m-d');
                            }
                            $modelEnrollment->daily_order = $modelEnrollment->getDailyOrder();
                            $saved = false;


                            $hasDuplicate = $modelEnrollment->alreadyExists();
                            if ($modelEnrollment->validate() && !$hasDuplicate) {
                                $saved = $modelEnrollment->save();
                            }

                            if ($hasDuplicate) {
                                Yii::app()->user->setFlash(
                                    'error', Yii::t('default', 'Aluno já está matriculado nessa turma.')
                                );
                            }
                        }

                        if (isset($_POST['Vaccine']['vaccine_id'])) {
                            if (count($_POST['Vaccine']['vaccine_id']) > 0) {
                                if ($studentVaccinesSaves) {
                                    StudentVaccine::model()->deleteAll("student_id = $modelStudentIdentification->id");
                                }

                                foreach ($_POST['Vaccine']['vaccine_id'] as $vaccine_id) {
                                    $studentVaccine = new StudentVaccine();
                                    $studentVaccine->student_id = $modelStudentIdentification->id;
                                    $studentVaccine->vaccine_id = $vaccine_id;
                                    $studentVaccine->save();
                                }
                            }
                        }

                        if ($saved) {
                            $flash = "success";
                            $msg = 'O Cadastro de ' . $modelStudentIdentification->name . ' foi alterado com sucesso!';

                            if (Yii::app()->features->isEnable("FEAT_SEDSP")) {

                                $this->authenticateSedToken();
                                $syncResult = (object) $modelStudentIdentification->syncStudentWithSED($id, $modelEnrollment, self::UPDATE);

                                if ($syncResult->identification->outErro !== null || $syncResult->enrollment->outErro !== null) {
                                    $flash = "error";
                                    $msg = '<span style="color: white;background: #23b923;
                                    padding:10px;border-radius: 4px;">Cadastro do aluno ' . $modelStudentIdentification->name .
                                        '  alterado com sucesso no TAG, mas não foi possível sincronizá-lo com a SEDSP. Motivo: </span>';
                                    if ($syncResult->identification->outErro) {
                                        $msg .= "<br>Ficha do Aluno: " . $syncResult->identification->outErro;
                                    }
                                    if ($syncResult->enrollment->outErro) {
                                        $msg .= "<br>Matrícula: " . $syncResult->enrollment->outErro;
                                    }
                                }
                            }

                            Log::model()->saveAction(
                                "student", $modelStudentIdentification->id,
                                "U", $modelStudentIdentification->name
                            );

                            Yii::app()->user->setFlash($flash, Yii::t('default', $msg));
                            $this->redirect(array('index', 'id' => $modelStudentIdentification->id));
                        } else {
                            $msg = 'Não foi possível realizar as modificações do aluno: ' .
                                $modelStudentIdentification->name;

                            Yii::app()->user->setFlash('error', Yii::t('default', $msg));
                            $this->redirect(array('index', 'id' => $modelStudentIdentification->id));
                        }
                    }
                }
            }
        }

        $this->render('update', array(
            'modelStudentIdentification' => $modelStudentIdentification,
            'modelStudentDocumentsAndAddress' => $modelStudentDocumentsAndAddress,
            'modelStudentRestrictions' => $modelStudentRestrictions,
            'modelStudentDisorder' => $modelStudentDisorder,
            'modelEnrollment' => $modelEnrollment,
            'vaccines' => $vaccines,
            'studentVaccinesSaves' => $studentVaccinesSaves
        ));
    }

    public function actionTransfer($id)
    {
        $modelStudentIdentification = $this->loadModel($id, $this->STUDENT_IDENTIFICATION);
        $modelEnrollment = new StudentEnrollment;
        $modelSchool = SchoolIdentification::model()->findAll();
        if (isset($_POST['StudentEnrollment'])) {
            $currentEnrollment = StudentEnrollment::model()->findByAttributes(array('student_fk' => $modelStudentIdentification->id, 'current_enrollment' => 1));
            $currentEnrollment = $currentEnrollment == null ? StudentEnrollment::model()->findByPk($modelStudentIdentification->lastEnrollment->id) : $currentEnrollment;
            if ($currentEnrollment != null) {
                //salvar o histórico de matrícula antiga
                $currentEnrollmentHistory = new StudentEnrollmentHistory();
                $currentEnrollmentHistory->student_enrollment_fk = $currentEnrollment->id;
                $currentEnrollmentHistory->status = $currentEnrollment->status;
                $currentEnrollmentHistory->enrollment_date = $currentEnrollment->enrollment_date;
                if ($currentEnrollment->status == 2) {
                    $currentEnrollmentHistory->transfer_date = $currentEnrollment->transfer_date;
                }
                if ($currentEnrollment->status == 13) {
                    $currentEnrollmentHistory->class_transfer_date = $currentEnrollment->class_transfer_date;
                    $currentEnrollmentHistory->school_readmission_date = $currentEnrollment->school_readmission_date;
                }
                $currentEnrollmentHistory->save();
                //

                $currentEnrollment->status = 2;
                $currentEnrollment->transfer_date = date_create_from_format(
                    'd/m/Y', $_POST['StudentEnrollment']['transfer_date']
                )->format('Y-m-d');
                $currentEnrollment->current_enrollment = 0;
                if ($currentEnrollment->save()) {
                    Log::model()->saveAction(
                        "enrollment", $currentEnrollment->id,
                        "U", $currentEnrollment->studentFk->name . "|" . $currentEnrollment->classroomFk->name
                    );
                }
            }

            $modelEnrollment = StudentEnrollment::model()->findByAttributes(array('student_fk' => $modelStudentIdentification->id, 'classroom_fk' => $_POST['StudentEnrollment']['classroom_fk']));
            if ($modelEnrollment != null) {
                //salvar o histórico de matrícula nova
                $studentEnrollmentHistory = new StudentEnrollmentHistory();
                $studentEnrollmentHistory->student_enrollment_fk = $modelEnrollment->id;
                $studentEnrollmentHistory->status = $modelEnrollment->status;
                $studentEnrollmentHistory->enrollment_date = $modelEnrollment->enrollment_date;
                if ($modelEnrollment->status == 2) {
                    $studentEnrollmentHistory->transfer_date = $modelEnrollment->transfer_date;
                }
                if ($modelEnrollment->status == 13) {
                    $studentEnrollmentHistory->class_transfer_date = $modelEnrollment->class_transfer_date;
                    $studentEnrollmentHistory->school_readmission_date = $modelEnrollment->school_readmission_date;
                }
                $studentEnrollmentHistory->save();
            } else {
                $modelEnrollment = new StudentEnrollment();
            }
            $modelEnrollment->school_inep_id_fk = $_POST['StudentEnrollment']['school_inep_id_fk'];
            $modelEnrollment->classroom_fk = $_POST['StudentEnrollment']['classroom_fk'];
            $modelEnrollment->student_fk = $modelStudentIdentification->id;
            $modelEnrollment->student_inep_id = $modelStudentIdentification->inep_id;
            $modelEnrollment->status = 1;
            $modelEnrollment->observation = $_POST['StudentEnrollment']['observation'];
            $modelEnrollment->create_date = date('Y-m-d');
            $modelEnrollment->daily_order = $modelEnrollment->getDailyOrder();
            $modelEnrollment->enrollment_date = $currentEnrollment->transfer_date;
            $modelEnrollment->current_enrollment = 1;

            if ($modelEnrollment->validate()) {
                $modelEnrollment->save();
            }
            Yii::app()->user->setFlash('success', Yii::t('default', 'transferred enrollment'));
            $this->redirect(array('student/update&id=' . $modelStudentIdentification->id));
        } else {
            $this->render('transfer', array(
                'modelStudentIdentification' => $modelStudentIdentification,
                'modelEnrollment' => $modelEnrollment,
                'modelSchool' => $modelSchool,
            ));
        }
    }

    public function actionGetTransferClassrooms()
    {
        $school_inep_id = $_POST["inep_id"];
        $school = SchoolIdentification::model()->findByPk($school_inep_id);
        $classrooms = $school->classrooms;
        foreach ($classrooms as $class) {
            if ($class->school_year == Yii::app()->user->year) {
                echo "<option value='" . htmlspecialchars($class->id) . "'>" . htmlspecialchars($class->name) . "</option>";
            }
        }
    }


    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {

        $classes = Yii::app()->db->createCommand()
            ->select('classroom_inep_id')
            ->from('student_enrollment')
            ->where('student_fk = :id', array(':id' => $id))
            ->queryColumn();

        try {
            $enrollment = $this->loadModel($id, $this->STUDENT_ENROLLMENT);
            $delete = true;
            foreach ($enrollment as $e) {
                if (isset($e->id) && $e->id > 0) {
                    $delete = $delete && $e->delete();
                }
            }

            $documentsAndAddress = $this->loadModel($id, $this->STUDENT_DOCUMENTS_AND_ADDRESS);
            if (isset($documentsAndAddress->id) && $documentsAndAddress->id > 0) {
                $documentsAndAddress->delete();
            }

            $studentRestriction = $this->loadModel($id, $this->STUDENT_RESTRICTIONS);
            if (isset($studentRestriction->id) && $studentRestriction->id > 0) {
                $studentRestriction->delete();
            }

            $identification = $this->loadModel($id, $this->STUDENT_IDENTIFICATION);
            $inNumRA = $identification->gov_id;

            if (isset($identification->id) && $identification->id > 0) {
                $identification->delete();
            }

            if ($delete) {
                if (Yii::app()->features->isEnable("FEAT_SEDSP")) {
                    $this->excluirMatriculaFromSED($classes, $inNumRA);
                }

                Yii::app()->user->setFlash('success', Yii::t('default', 'Aluno excluído com sucesso!'));
                $this->redirect(array('index'));
            } else {
                throw new CHttpException(404, 'The requested page does not exist.');
            }
        } catch (\Throwable $th) {
            Yii::app()->user->setFlash(
                'error', Yii::t(
                'default', 'Esse aluno não pode ser excluído,
                    pois existem dados de frequência, notas ou matrículadas vinculadas a ele!'
            )
            );
            $this->redirect('?r=student');
        }
    }

    public function excluirMatriculaFromSED($classes, $inNumRA)
    {
        if (count($classes) != '0') {
            $excluirMatriculaFromSEDUseCase = new ExcluirMatriculaFromSEDUseCase();

            foreach ($classes as $classe) {
                $statusDelete = $excluirMatriculaFromSEDUseCase->exec(
                    new InExcluirMatricula(new InAluno($inNumRA, null, 'SP'), $classe)
                );
                if ($statusDelete->outErro !== null) {
                    $erros[] = $statusDelete->outErro;
                }
            }
        }
    }


    /**
     * Lists all models.
     */
    public function actionIndex($sid = null, $merId = null)
    {
        $filter = new StudentIdentification('search');
        $filter->unsetAttributes();  // clear any default values
        if (isset($_GET['StudentIdentification'])) {
            $_GET['StudentIdentification']['name'] = $this->removeWhiteSpace($_GET['StudentIdentification']['name']);
            $filter->attributes = $_GET['StudentIdentification'];
        }
        $school = Yii::app()->user->school;
        $dataProvider = new CActiveDataProvider(
            $this->STUDENT_IDENTIFICATION,
            array(
                'criteria' => array(
                    'condition' => 'school_inep_id_fk=' . $school,
                ),
                'pagination' => false
            )
        );
        $buttons = "";
        if ($sid != null) {
            $student = $this->loadModel($sid, $this->STUDENT_IDENTIFICATION);
            if (isset($student->studentEnrollments[0]->id)) {
                $enrollmentId = $student->studentEnrollments[0]->id;
                @$stage = $student->studentEnrollments[0]->classroomFk->edcensoStageVsModalityFk->stage;
                $type = 1;
                if ($stage == 1) {
                    $type = 0;
                } elseif ($stage == 6) {
                    $type = 3;
                }

                $buttons = CHtml::tag(
                    'a',
                    array(
                        'href' => yii::app()->createUrl('student/update', array('id' => $sid)),
                        'class' => "btn btn-primary btn-icon glyphicons eye_open",
                        'style' => 'margin-top: 5px; width: 110px'
                    ),
                    '<i></i>Visualizar aluno'
                );
                $buttons .= "<br>";

                $buttons .= CHtml::tag(
                    'a',
                    array(
                        'target' => '_blank', 'href' => yii::app()->createUrl(
                        '/forms/StudentFileForm', array('type' => $type, 'enrollment_id' => $enrollmentId)
                    ),
                        'class' => "btn btn-primary btn-icon glyphicons notes_2",
                        'style' => 'margin-top: 5px; width: 110px'
                    ),
                    '<i></i>Ficha individual'
                );
                $buttons .= "<br>";
            }
        }

        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'filter' => $filter,
            'buttons' => $buttons,
        ));
    }

    private function removeWhiteSpace($text)
    {
        $text = preg_replace('/[\t\n\r\0\x0B]/', '', $text);
        $text = preg_replace('/([\s])\1+/', ' ', $text);
        $text = trim($text);
        return $text;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @param string the MODEL wich will be loaded
     */
    public function loadModel($id, $model)
    {

        $return = null;

        if ($model == $this->STUDENT_IDENTIFICATION) {
            $return = StudentIdentification::model()->findByPk($id);
        } elseif ($model == $this->STUDENT_DOCUMENTS_AND_ADDRESS) {
            $return = StudentDocumentsAndAddress::model()->findByAttributes(array('id' => $id));
            if ($return === null) {
                $return = new StudentDocumentsAndAddress;
            }
        } elseif ($model == $this->STUDENT_ENROLLMENT) {
            $return = StudentEnrollment::model()->findAllByAttributes(array('student_fk' => $id));
            array_push($return, new StudentEnrollment);
        } elseif ($model == $this->STUDENT_RESTRICTIONS) {
            $return = StudentRestrictions::model()->findByAttributes(array('student_fk' => $id));
            if ($return === null) {
                $return = new StudentRestrictions;
            }
        } elseif ($model == $this->STUDENT_DISORDER) {
            $return = StudentDisorder::model()->findByAttributes(array('student_fk' => $id));
            if ($return === null) {
                $return = new StudentDisorder;
            }
        }

        return $return;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'student') {
            echo CActiveForm::validate($model);
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
