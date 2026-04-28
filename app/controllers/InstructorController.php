<?php

class InstructorController extends Controller
{
    //@done s1 - Tirar Aba Dados do Instrutor do update de instrutor
    //@done s1 - Adicionar validações em todos os campos que estão faltando
    //@done s1 - Recuperar endereço pelo CEP
    //@done s1 - validar CPF
    //@done s1 - corrigir o delete do instructor

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'fullmenu';
    private $instructorIdentification = 'InstructorIdentification';
    private $instructorDocumentsAndAddress = 'InstructorDocumentsAndAddress';
    private $instructorVariableData = 'InstructorVariableData';
    private $instructorTeachingData = 'InstructorTeachingData';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
        ];
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            [
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => [
                    'index', 'view', 'create', 'update', 'updateEmails', 'frequency',
                    'saveEmails', 'getCity', 'getCityByCep', 'getInstitutions', 'getInstitution',
                    'getCourses', 'delete', 'getFrequency', 'getFrequencyDisciplines', 'getFrequencyClassroom',
                    'saveFrequency', 'saveJustification', 'getClassrooms', 'printHistory', 'printYearHistory'
                ], 'users' => ['@'],
            ], [
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => ['admin'], 'users' => ['admin'],
            ], [
                'deny', // deny all users
                'users' => ['*'],
            ],
        ];
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', [
            'modelInstructorIdentification' => $this->loadModel($id, $this->instructorIdentification),
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $modelInstructorIdentification = new InstructorIdentification();
        $modelInstructorDocumentsAndAddress = new InstructorDocumentsAndAddress();
        $modelInstructorVariableData = new InstructorVariableData();
        $saveInstructor = false;
        $saveDocumentsAndAddress = false;
        $saveVariableData = false;

        // i is an abbreviation for instructor
        $iIdentification = Yii::app()->request->getPost('InstructorIdentification', null);
        $iDocumentsAndAddress = Yii::app()->request->getPost('InstructorDocumentsAndAddress', null);
        $iVariableData = Yii::app()->request->getPost('InstructorVariableData', null);

        $error[] = '';
        if (isset($iIdentification, $iDocumentsAndAddress, $iVariableData)) {
            $modelInstructorIdentification->attributes = $iIdentification;

            $idIndigenousPeople = $_POST['InstructorIdentification']['id_indigenous_people'];
            $modelInstructorIdentification->id_indigenous_people = $idIndigenousPeople === '' ? null : $idIndigenousPeople;

            $modelInstructorDocumentsAndAddress->attributes = $iDocumentsAndAddress;
            $modelInstructorVariableData->attributes = $iVariableData;

            if (!isset($modelInstructorIdentification->edcenso_nation_fk)) {
                $modelInstructorIdentification->edcenso_nation_fk = 76;
            }
            if (!isset($modelInstructorIdentification->edcenso_uf_fk)) {
                $modelInstructorIdentification->edcenso_uf_fk = 0;
            }
            if (!isset($modelInstructorIdentification->edcenso_city_fk)) {
                $modelInstructorIdentification->edcenso_city_fk = 0;
            }

            $saveInstructor = true;

            //=== MODEL DocumentsAndAddress
            $modelInstructorDocumentsAndAddress->cpf = str_replace(['.', '-'], '', $modelInstructorDocumentsAndAddress->cpf);
            if (isset($modelInstructorDocumentsAndAddress->cep) && !empty($modelInstructorDocumentsAndAddress->cep)) {
                //Então o endereço, uf e cidade são obrigatórios
                if (isset($modelInstructorDocumentsAndAddress->address) &&
                        !empty($modelInstructorDocumentsAndAddress->address) &&
                        isset($modelInstructorDocumentsAndAddress->neighborhood) &&
                        !empty($modelInstructorDocumentsAndAddress->neighborhood) &&
                        isset($modelInstructorDocumentsAndAddress->edcenso_uf_fk) &&
                        !empty($modelInstructorDocumentsAndAddress->edcenso_uf_fk) &&
                        isset($modelInstructorDocumentsAndAddress->edcenso_city_fk) &&
                        !empty($modelInstructorDocumentsAndAddress->edcenso_city_fk)) {
                    $saveDocumentsAndAddress = true;
                } else {
                    $error['documentsAndAddress'] = 'CEP preenchido então, o Endereço, Bairro, UF e Cidade são Obrigatórios !';
                }
            } else {
                $saveDocumentsAndAddress = true;
            }
            //======================================
            //=== MODEL VariableData
            if (isset($modelInstructorVariableData->scholarity) && $modelInstructorVariableData->scholarity == 6) {
                if (isset($modelInstructorVariableData->high_education_situation_1,
                    $modelInstructorVariableData->high_education_course_code_1_fk,
                    $modelInstructorVariableData->high_education_institution_code_1_fk) ||
                    isset($modelInstructorVariableData->high_education_situation_2,
                        $modelInstructorVariableData->high_education_course_code_2_fk,
                        $modelInstructorVariableData->high_education_institution_code_2_fk) ||
                    isset($modelInstructorVariableData->high_education_situation_3,
                        $modelInstructorVariableData->high_education_course_code_3_fk,
                        $modelInstructorVariableData->high_education_institution_code_3_fk)) {
                    $saveVariableData = true;
                } else {
                    $error['variableData'] = 'Pelo menos uma situação do curso superior, código
                                                do curso superior, tipo de instituição e instituição
                                                do curso superior deverão ser obrigatoriamente
                                                preenchidos';
                }
            } else {
                $saveVariableData = true;
            }

            if ($saveInstructor && $saveDocumentsAndAddress && $saveVariableData) {
                // Setar todos os school_inep_id
                $modelInstructorIdentification->school_inep_id_fk = Yii::app()->user->school;
                $modelInstructorDocumentsAndAddress->school_inep_id_fk = $modelInstructorIdentification->school_inep_id_fk;
                $modelInstructorVariableData->school_inep_id_fk = $modelInstructorIdentification->school_inep_id_fk;

                if ($modelInstructorIdentification->validate() &&
                        $modelInstructorDocumentsAndAddress->validate() &&
                        $modelInstructorVariableData->validate()) {
                    $user = $this->createUser($modelInstructorIdentification, $modelInstructorDocumentsAndAddress);

                    if ($user->save()) {
                        $modelInstructorIdentification->users_fk = $user->id;
                        $this->createUserSchool($user, $modelInstructorIdentification);
                    }

                    if ($modelInstructorIdentification->save()) {
                        $modelInstructorDocumentsAndAddress->id = $modelInstructorIdentification->id;
                        $modelInstructorVariableData->id = $modelInstructorIdentification->id;

                        $modelInstructorVariableData->high_education_course_code_1_fk =
                            empty($modelInstructorVariableData->high_education_course_code_1_fk) ? null :
                            $modelInstructorVariableData->high_education_course_code_1_fk;

                        $modelInstructorVariableData->high_education_course_code_2_fk =
                            empty($modelInstructorVariableData->high_education_course_code_2_fk) ? null :
                            $modelInstructorVariableData->high_education_course_code_2_fk;

                        $modelInstructorVariableData->high_education_course_code_3_fk =
                            empty($modelInstructorVariableData->high_education_course_code_3_fk) ? null :
                            $modelInstructorVariableData->high_education_course_code_3_fk;

                        if ($modelInstructorDocumentsAndAddress->save() && $modelInstructorVariableData->save()) {
                            Yii::app()->user->setFlash('success', Yii::t('default', 'Professor adicionado com sucesso!'));
                            $this->redirect(['index']);
                        }
                    }
                }
            }
        }

        $this->render('create', [
            'modelInstructorIdentification' => $modelInstructorIdentification,
            'modelInstructorDocumentsAndAddress' => $modelInstructorDocumentsAndAddress,
            'modelInstructorVariableData' => $modelInstructorVariableData, 'error' => $error,
        ]);
    }

    private function createUser($modelInstructorIdentification, $modelInstructorDocumentsAndAddress)
    {
        $user = new Users();
        $user->name = $modelInstructorIdentification->name;
        $user->username = $modelInstructorDocumentsAndAddress->cpf;
        $user->password = $this->hashBirthdayDate($modelInstructorIdentification->birthday_date);
        return $user;
    }

    private function hashBirthdayDate($birthdayDate)
    {
        $passwordHasher = new PasswordHasher();
        $birthdayDate = str_replace('/', '', $birthdayDate);
        return $passwordHasher->bcriptHash($birthdayDate);
    }

    private function createUserSchool($user)
    {
        $userSchool = new UsersSchool();
        $userSchool->user_fk = $user->id;
        $userSchool->school_fk = Yii::app()->user->school;

        if ($userSchool->save()) {
            $auth = Yii::app()->authManager;
            $auth->assign('instructor', $user->id);
        }
    }

    private function checkHasUser($instructorIdentification, $instructorDocumentsAndAddress)
    {
        $modelUser = Users::model()->findByAttributes(['username' => $instructorDocumentsAndAddress->cpf]);
        if (!$modelUser) {
            $user = $this->createUser($instructorIdentification, $instructorDocumentsAndAddress);
            if ($user->save()) {
                $this->createUserSchool($user);
                $instructorIdentification->users_fk = $user->id;
            }
        }
        return $instructorIdentification;
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        //=======================================
        $modelInstructorIdentification = $this->loadModel($id, $this->instructorIdentification);
        $modelInstructorDocumentsAndAddress = $this->loadModel($id, $this->instructorDocumentsAndAddress);
        $modelInstructorDocumentsAndAddress = isset($modelInstructorDocumentsAndAddress) ? $modelInstructorDocumentsAndAddress : new InstructorDocumentsAndAddress();
        $modelInstructorVariableData = $this->loadModel($id, $this->instructorVariableData);
        if ($modelInstructorVariableData == null) {
            $modelInstructorVariableData = new InstructorVariableData();
        }
        $scholId = $modelInstructorIdentification->school_inep_id_fk;
        // Uncomment the following line if AJAX validation is needed
        //			 $this->performAjaxValidation($modelInstructorIdentification);

        $saveInstructor = false;
        $saveDocumentsAndAddress = false;
        $saveVariableData = false;
        //==================================

        $error[] = '';
        if (isset($_POST['InstructorIdentification'], $_POST['InstructorDocumentsAndAddress'], $_POST['InstructorVariableData'])) {
            $modelInstructorIdentification->attributes = $_POST['InstructorIdentification'];

            $idIndigenousPeople = $_POST['InstructorIdentification']['id_indigenous_people'];
            $modelInstructorIdentification->id_indigenous_people = $idIndigenousPeople === '' ? null : $idIndigenousPeople;

            $modelInstructorDocumentsAndAddress->attributes = $_POST['InstructorDocumentsAndAddress'];
            $modelInstructorVariableData->attributes = $_POST['InstructorVariableData'];
            if (!isset($modelInstructorIdentification->edcenso_nation_fk)) {
                $modelInstructorIdentification->edcenso_nation_fk = 76;
            }
            if (!isset($modelInstructorIdentification->edcenso_uf_fk)) {
                $modelInstructorIdentification->edcenso_uf_fk = 0;
            }
            if (!isset($modelInstructorIdentification->edcenso_city_fk)) {
                $modelInstructorIdentification->edcenso_city_fk = 0;
            }

            $saveInstructor = true;

            //=== MODEL DocumentsAndAddress
            $modelInstructorDocumentsAndAddress->cpf = str_replace(['.', '-'], '', $modelInstructorDocumentsAndAddress->cpf);
            if (isset($modelInstructorDocumentsAndAddress->cep) && !empty($modelInstructorDocumentsAndAddress->cep)) {
                //Então o endereço, uf e cidade são obrigatórios
                if (isset($modelInstructorDocumentsAndAddress->address) &&
                        !empty($modelInstructorDocumentsAndAddress->address) &&
                        isset($modelInstructorDocumentsAndAddress->neighborhood) &&
                        !empty($modelInstructorDocumentsAndAddress->neighborhood) &&
                        isset($modelInstructorDocumentsAndAddress->edcenso_uf_fk) &&
                        !empty($modelInstructorDocumentsAndAddress->edcenso_uf_fk) &&
                        isset($modelInstructorDocumentsAndAddress->edcenso_city_fk) &&
                        !empty($modelInstructorDocumentsAndAddress->edcenso_city_fk)) {
                    $saveDocumentsAndAddress = true;
                } else {
                    $error['documentsAndAddress'] = 'CEP preenchido então, o Endereço, Bairro, UF e Cidade são Obrigatórios !';
                }
            } else {
                $saveDocumentsAndAddress = true;
            }
            //======================================
            //=== MODEL VariableData
            if (isset($modelInstructorVariableData->scholarity)) {
                if ($modelInstructorVariableData->scholarity == 6) {
                    if (isset($modelInstructorVariableData->high_education_situation_1, $modelInstructorVariableData->high_education_course_code_1_fk, $modelInstructorVariableData->high_education_institution_code_1_fk) || isset($modelInstructorVariableData->high_education_situation_2, $modelInstructorVariableData->high_education_course_code_2_fk, $modelInstructorVariableData->high_education_institution_code_2_fk) || isset($modelInstructorVariableData->high_education_situation_3, $modelInstructorVariableData->high_education_course_code_3_fk, $modelInstructorVariableData->high_education_institution_code_3_fk)) {
                        $saveVariableData = true;
                    } else {
                        $error['variableData'] = 'Pelo menos uma situação do curso superior, código
do curso superior, tipo de instituição e instituição
do curso superior deverão ser obrigatoriamente
preenchidos';
                    }
                } else {
                    $saveVariableData = true;
                }
            }

            if ($saveInstructor && $saveDocumentsAndAddress && $saveVariableData) {
                // Setar todos os school_inep_id
                $modelInstructorDocumentsAndAddress->school_inep_id_fk = $scholId;
                $modelInstructorVariableData->school_inep_id_fk = $scholId;

                $modelInstructorVariableData->high_education_institution_code_1_fk =
                    empty($modelInstructorVariableData->high_education_institution_code_1_fk) ? null :
                    $modelInstructorVariableData->high_education_institution_code_1_fk;

                $modelInstructorVariableData->high_education_institution_code_2_fk =
                    empty($modelInstructorVariableData->high_education_institution_code_2_fk) ? null :
                    $modelInstructorVariableData->high_education_institution_code_2_fk;

                $modelInstructorVariableData->high_education_institution_code_3_fk =
                    empty($modelInstructorVariableData->high_education_institution_code_3_fk) ? null :
                    $modelInstructorVariableData->high_education_institution_code_3_fk;

                if ($modelInstructorIdentification->validate() &&
                        $modelInstructorDocumentsAndAddress->validate() &&
                        $modelInstructorVariableData->validate() &&
                        $modelInstructorIdentification->save()) {
                    $modelInstructorDocumentsAndAddress->id = $modelInstructorIdentification->id;
                    $modelInstructorVariableData->id = $modelInstructorIdentification->id;
                    $modelInstructorIdentification = $this->checkHasUser($modelInstructorIdentification, $modelInstructorDocumentsAndAddress);

                    $modelInstructorVariableData->high_education_course_code_1_fk =
                        empty($modelInstructorVariableData->high_education_course_code_1_fk) ? null :
                        $modelInstructorVariableData->high_education_course_code_1_fk;

                    $modelInstructorVariableData->high_education_course_code_2_fk =
                        empty($modelInstructorVariableData->high_education_course_code_2_fk) ? null :
                        $modelInstructorVariableData->high_education_course_code_2_fk;

                    $modelInstructorVariableData->high_education_course_code_3_fk =
                        empty($modelInstructorVariableData->high_education_course_code_3_fk) ? null :
                        $modelInstructorVariableData->high_education_course_code_3_fk;

                    if ($modelInstructorDocumentsAndAddress->save() && $modelInstructorVariableData->save()) {
                        Yii::app()->user->setFlash('success', Yii::t('default', 'Professor alterado com sucesso!'));
                        $this->redirect(['index']);
                    }
                }
            }
        }

        //====================================
        $teachingHistory = Yii::app()->db->createCommand('
            SELECT
                itd.id,
                itd.school_inep_id_fk,
                itd.classroom_id_fk,
                itd.role,
                itd.contract_type,
                s.name        AS school_name,
                c.name        AS classroom_name,
                c.school_year AS classroom_year,
                c.period      AS classroom_period
            FROM instructor_teaching_data itd
            LEFT JOIN school_identification s ON s.inep_id = itd.school_inep_id_fk
            LEFT JOIN classroom c             ON c.id = itd.classroom_id_fk
            WHERE itd.instructor_fk = :id
            ORDER BY c.school_year DESC, s.name ASC, c.name ASC
        ')->queryAll(true, [':id' => $id]);

        $this->render('update', [
            'modelInstructorIdentification' => $modelInstructorIdentification,
            'modelInstructorDocumentsAndAddress' => $modelInstructorDocumentsAndAddress,
            'modelInstructorVariableData' => $modelInstructorVariableData,
            'error' => $error,
            'teachingHistory' => $teachingHistory,
        ]);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $modelInstructorIdentification = $this->loadModel($id, $this->instructorIdentification);
        $modelInstructorDocumentsAndAddress = $this->loadModel($id, $this->instructorDocumentsAndAddress);
        $modelInstructorVariableData = $this->loadModel($id, $this->instructorVariableData);
        $modelInstructorTeachingData = $this->loadModel($id, $this->instructorTeachingData);

        $delete = true;
        $numClassrooms = count($modelInstructorTeachingData);
        if ($numClassrooms > 0) {
            // $classroomsInstructorIsAssociated = array_map()
            // $classrooms = $this->actionGetClassrooms($id);
            $classrooms = $modelInstructorIdentification->getClassrooms();
            $htmlForAlert = '<b>Professor(a) ' . $modelInstructorIdentification->name . ' não pode ser excluído uma vez que está associado à(às) seguinte(s) turma(s):</b><br>';
            foreach ($classrooms as $classroom) {
                $teachingData =
                    'Turma: <b>' . $classroom['classroom_name'] . '</b>' .
                    ' / Disciplina: ' . $classroom['discipline_name'] .
                    ' / Ano: ' . $classroom['syear'] .
                    ' / Escola: ' . $classroom['school_name'] .
                    '<br>';
                $anchorToUpdateClassroom = "<a href='" . Yii::app()->createUrl('classroom/update', ['id' => $classroom['cid']]) . "'>" . $teachingData . '</a>';
                $htmlForAlert .= $anchorToUpdateClassroom;
            }

            Yii::app()->user->setFlash('notice', Yii::t('default', $htmlForAlert));
            $this->redirect(['index']);
        }

        if (isset($modelInstructorDocumentsAndAddress)) {
            $modelInstructorDocumentsAndAddress->delete();
        }
        if (isset($modelInstructorVariableData)) {
            $modelInstructorVariableData->delete();
            foreach ($modelInstructorTeachingData as $td) {
                $delete = $delete && $td->delete();
            }
        }
        if ($delete && $modelInstructorIdentification->delete()) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Professor excluído com sucesso!'));
            $this->redirect(['index']);
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = InstructorIdentification::model()->search();

        $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    //Método para Solicitação Ajax para o select UF x Cities

    public function actionGetCity()
    {
        $edcensoUfFk = $_POST['edcenso_uf_fk'];
        $currentCity = $_POST['current_city'];

        $data = EdcensoCity::model()->findAll('edcenso_uf_fk=:uf_id', [':uf_id' => (int)$edcensoUfFk]);
        $data = CHtml::listData($data, 'id', 'name');

        $options = [];
        foreach ($data as $value => $name) {
            array_push($options, CHtml::tag(
                'option',
                [
                    'value' => $value,
                    'selected' => $value == $currentCity
                ],
                CHtml::encode($name),
                true
            ));
        }

        echo json_encode($options);
    }

    public function actionGetCityByCep()
    {
        $cep = Yii::app()->request->getPost('cep');
        $data = null;

        if (!empty($cep)) {
            $data = EdcensoCity::model()->find('cep_initial <= ' . $cep . ' and cep_final >= ' . $cep);
        }

        $result = ($data == null) ? ['UF' => null, 'City' => null] : [
            'UF' => $data->edcenso_uf_fk, 'City' => $data->id

        ];
        echo json_encode($result);
    }

    //@done s1 - Criar Função que retorna instituições filtrando por tipo
    //@done s1 - Modificar função para que ela fique mais rápida
    public function actionGetInstitutions()
    {
        $institutionName = Yii::app()->request->getPost('q');

        $data = EdcensoIES::model()->findAll("name like '%" . $institutionName . "%' ORDER BY name LIMIT 0,10");
        $data = CHtml::listData($data, 'id', 'name');

        $return = [];
        foreach ($data as $value => $name) {
            array_push($return, ['id' => CHtml::encode($value), 'name' => CHtml::encode($name)]);
        }
        header('Content-Type: application/json; charset="UTF-8"');
        echo json_encode($return, JSON_OBJECT_AS_ARRAY);
    }

    public function actionGetInstitution()
    {
        $edcensoUfFk = Yii::app()->request->getPost('edcenso_uf_fk');
        $institutions = EdcensoIES::model()->findAllByAttributes(['edcenso_uf_fk' => $edcensoUfFk]);

        $return = [];
        foreach ($institutions as $institution) {
            array_push($return, ['id' => CHtml::encode($institution->id), 'name' => CHtml::encode($institution->name)]);
        }
        header('Content-Type: application/json; charset="UTF-8"');
        echo json_encode($return, JSON_OBJECT_AS_ARRAY);
    }

    //@done s1 - criar funçao que retorna os cursos baseados na área de atuação
    public function actionGetCourses($tdid = 1)
    {
        $area = $_POST['high_education_course_area' . $tdid];
        $data = EdcensoCourseOfHigherEducation::model()->findAll([
            'order' => 'name', 'condition' => 'cod=:x', 'params' => [':x' => $area]
        ]);
        $data = CHtml::listData($data, 'id', 'name');

        echo CHtml::tag('option', ['value' => ''], 'Selecione o Curso', true);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', ['value' => $value], CHtml::encode($name), true);
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $modelInstructorIdentification = new InstructorIdentification('search');
        $modelInstructorDocumentsAndAddress = new InstructorDocumentsAndAddress('search');
        $modelInstructorVariableData = new InstructorVariableData('search');
        $modelInstructorTeachingData = new InstructorTeachingData('search');

        $modelInstructorIdentification->unsetAttributes();  // clear any default values
        $modelInstructorDocumentsAndAddress->unsetAttributes();  // clear any default values
        $modelInstructorVariableData->unsetAttributes();  // clear any default values
        $modelInstructorTeachingData->unsetAttributes();  // clear any default values
        if (isset($_GET[$this->instructorIdentification], $_GET[$this->instructorDocumentsAndAddress], $_GET[$this->instructorVariableData], $_GET[$this->instructorTeachingData])) {
            $modelInstructorIdentification->attributes = $_GET['InstructorIdentification'];
            $modelInstructorDocumentsAndAddress->attributes = $_GET['InstructorDocumentsAndAddress'];
            $modelInstructorVariableData->attributes = $_GET['InstructorVariableData'];
            $modelInstructorTeachingData->attributes = $_GET['InstructorTeachingData'];
        }

        $this->render('admin', [
            'modelInstructorIdentification' => $modelInstructorIdentification,
            'modelInstructorDocumentsAndAddress' => $modelInstructorDocumentsAndAddress,
            'modelInstructorVariableData' => $modelInstructorVariableData,
            'modelInstructorTeachingData' => $modelInstructorTeachingData
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id, $model)
    {
        $instructor = InstructorIdentification::model()->findByPk($id);
        $instructorId = $instructor->id;
        $return = null;
        if ($model == $this->instructorIdentification) {
            $return = InstructorIdentification::model()->findByPk($instructorId);
        } elseif ($model == $this->instructorDocumentsAndAddress) {
            $return = InstructorDocumentsAndAddress::model()->findByPk($instructorId);
        } elseif ($model == $this->instructorVariableData) {
            $return = InstructorVariableData::model()->findByPk($instructorId);
        } elseif ($model == $this->instructorTeachingData) {
            $return = InstructorTeachingData::model()->findAllByAttributes(['instructor_fk' => $instructorId]);
        }

        if ($return === null && $model == $this->instructorIdentification) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        if ($return === null && $model == $this->instructorDocumentsAndAddress) {
            $return = InstructorDocumentsAndAddress::model()->findByPk($id);
        }

        if ($return === null && $model == $this->instructorVariableData) {
            $return = InstructorVariableData::model()->findByPk($id);
        }

        return $return;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'instructor-identification-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionUpdateEmails()
    {
        $instructors = InstructorIdentification::model()->findAll([
            'order' => 'name', 'condition' => 'email is null'
        ]);
        if (!empty($_POST)) {
            $success = false;
            foreach ($_POST as $id => $email) {
                if ($email != '') {
                    $success = true;
                    $instructor = InstructorIdentification::model()->findByPk($id);
                    $instructor->email = strtoupper($email);
                    $instructor->save();
                }
            }
            if ($success) {
                Yii::app()->user->setFlash('success', Yii::t('default', 'E-mails atualizados com sucesso!'));
            }
            $this->redirect(['index']);
        } else {
            $this->render('updateEmails', ['instructors' => $instructors]);
        }
    }

    public function actionFrequency()
    {
        $instructors = InstructorIdentification::model()->findAll([
            'order' => 'name',
        ]);

        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $criteria = new CDbCriteria();
            $criteria->alias = 'c';
            $criteria->join = ''
                . ' join instructor_teaching_data on instructor_teaching_data.classroom_id_fk = c.id '
                . ' join instructor_identification on instructor_teaching_data.instructor_fk = instructor_identification.id ';
            $criteria->condition = 'c.school_year = :school_year and c.school_inep_fk = :school_inep_fk and instructor_identification.users_fk = :users_fk';
            $criteria->order = 'name';
            $criteria->params = [':school_year' => Yii::app()->user->year, ':school_inep_fk' => Yii::app()->user->school, ':users_fk' => Yii::app()->user->loginInfos->id];

            $classrooms = Classroom::model()->findAll($criteria);
        } else {
            $classrooms = Classroom::model()->findAll('school_year = :school_year and school_inep_fk = :school_inep_fk order by name', ['school_year' => Yii::app()->user->year, 'school_inep_fk' => Yii::app()->user->school]);
        }

        $this->render('frequency', ['instructors' => $instructors, 'classrooms' => $classrooms]);
    }

    public function actionGetFrequency()
    {
        $instructor = (int)$_POST['instructor'];
        $month      = (int)$_POST['month'];
        $year       = (int)Yii::app()->user->year;

        // Dias letivos do professor: passa pelas turmas do professor (instructor_teaching_data)
        // e usa classroom.school_year para filtrar o ano (schedule.year pode ser NULL em dados antigos)
        $days = Yii::app()->db->createCommand(
            'SELECT DISTINCT s.day, s.week_day
             FROM schedule s
             JOIN classroom c ON c.id = s.classroom_fk
             JOIN instructor_teaching_data itd ON itd.classroom_id_fk = c.id
             WHERE itd.instructor_fk = :instructor
               AND s.month      = :month
               AND c.school_year = :year
               AND s.unavailable = 0
             ORDER BY s.day'
        )->queryAll(true, [':instructor' => $instructor, ':month' => $month, ':year' => $year]);

        if (empty($days)) {
            echo json_encode(['valid' => false, 'error' => 'Nenhum dia letivo encontrado para este professor no mês selecionado.']);
            return;
        }

        // Busca todas as faltas do professor no mês em uma só query
        $faultRows = Yii::app()->db->createCommand(
            'SELECT s.day, f.justification
             FROM instructor_faults f
             JOIN schedule s ON s.id = f.schedule_fk
             JOIN classroom c ON c.id = s.classroom_fk
             WHERE f.instructor_fk = :instructor
               AND s.month      = :month
               AND c.school_year = :year'
        )->queryAll(true, [':instructor' => $instructor, ':month' => $month, ':year' => $year]);

        $faultMap = [];
        foreach ($faultRows as $row) {
            $d = (int)$row['day'];
            if (!isset($faultMap[$d])) {
                $faultMap[$d] = ['fault' => true, 'justification' => null];
            }
            if ($row['justification'] !== null && $faultMap[$d]['justification'] === null) {
                $faultMap[$d]['justification'] = $row['justification'];
            }
        }

        $instructorModel = InstructorIdentification::model()->findByPk($instructor);
        $dayNames = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
        $schedules = [];

        foreach ($days as $day) {
            $dayNum    = (int)$day['day'];
            $hasFault  = isset($faultMap[$dayNum]);
            $available = date('Y-m-d') >= $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($dayNum, 2, '0', STR_PAD_LEFT);

            $schedules[] = [
                'available'     => $available,
                'day'           => $dayNum,
                'week_day'      => $dayNames[(int)$day['week_day']],
                'fault'         => $hasFault,
                'justification' => $hasFault ? $faultMap[$dayNum]['justification'] : null,
            ];
        }

        echo json_encode([
            'valid'       => true,
            'instructors' => [[
                'instructorId'   => $instructor,
                'instructorName' => $instructorModel ? $instructorModel->name : '',
                'schedules'      => $schedules,
            ]],
        ]);
    }

    public function actionGetFrequencyClassroom()
    {
        $instructor = htmlspecialchars($_POST['instructor']);
        $classrooms = Yii::app()->db->createCommand('SELECT c.id, c.name FROM classroom c
                JOIN instructor_teaching_data itd ON(c.id = itd.classroom_id_fk)
                WHERE itd.instructor_fk = :instructor AND c.school_year = :school_year')

            ->bindParam(':instructor', $instructor)
            ->bindParam(':school_year', Yii::app()->user->year)
            ->queryAll();
        echo '<option value>' . Yii::t('default', 'Select Classrom') . '</option>';
        foreach ($classrooms as $classroom) {
            echo '<option value=' . $classroom['id'] . '>' . $classroom['name'] . '</option>';
        }
    }

    public function actionGetFrequencyDisciplines()
    {
        $instructor = htmlspecialchars($_POST['instructor']);
        $classroom = htmlspecialchars($_POST['classroom']);
        $disciplines = Yii::app()->db->createCommand(
            'SELECT ed.id, ed.name FROM classroom c
                JOIN instructor_teaching_data itd ON(c.id = itd.classroom_id_fk)
                JOIN teaching_matrixes tm ON(itd.id = tm.teaching_data_fk)
                JOIN curricular_matrix cm ON(tm.curricular_matrix_fk = cm.id)
                JOIN edcenso_discipline ed ON(ed.id = cm.discipline_fk)
                WHERE itd.instructor_fk = :instructor AND c.id = :classroom'
        )
            ->bindParam(':instructor', $instructor)
            ->bindParam(':classroom', $classroom)
            ->queryAll();
        echo '<option value>' . Yii::t('default', 'Select Discipline') . '</option>';
        foreach ($disciplines as $discipline) {
            echo '<option value=' . $discipline['id'] . '>' . $discipline['name'] . '</option>';
        }
    }

    public function actionSaveFrequency()
    {
        if (empty($_POST['instructorId'])) return;

        $instructor = (int)$_POST['instructorId'];
        $day        = (int)$_POST['day'];
        $month      = (int)$_POST['month'];
        $year       = (int)Yii::app()->user->year;

        // Busca todos os schedules do professor naquele dia via instructor_teaching_data
        $scheduleIds = Yii::app()->db->createCommand(
            'SELECT s.id
             FROM schedule s
             JOIN classroom c ON c.id = s.classroom_fk
             JOIN instructor_teaching_data itd ON itd.classroom_id_fk = c.id
             WHERE itd.instructor_fk = :instructor
               AND s.day        = :day
               AND s.month      = :month
               AND c.school_year = :year
               AND s.unavailable = 0'
        )->queryColumn([':instructor' => $instructor, ':day' => $day, ':month' => $month, ':year' => $year]);

        if ($_POST['fault'] == '1') {
            foreach ($scheduleIds as $scheduleId) {
                $exists = InstructorFaults::model()->find(
                    'schedule_fk = :s AND instructor_fk = :i',
                    [':s' => (int)$scheduleId, ':i' => $instructor]
                );
                if (!$exists) {
                    $fault = new InstructorFaults();
                    $fault->instructor_fk = $instructor;
                    $fault->schedule_fk   = (int)$scheduleId;
                    $fault->save();
                }
            }
        } else {
            if (!empty($scheduleIds)) {
                $ids = implode(',', array_map('intval', $scheduleIds));
                Yii::app()->db->createCommand(
                    "DELETE FROM instructor_faults
                     WHERE instructor_fk = :instructor
                       AND schedule_fk IN ({$ids})"
                )->execute([':instructor' => $instructor]);
            }
        }
    }

    public function actionSaveJustification()
    {
        $instructor    = (int)$_POST['instructorId'];
        $day           = (int)$_POST['day'];
        $month         = (int)$_POST['month'];
        $year          = (int)Yii::app()->user->year;
        $justification = $_POST['justification'] === '' ? null : $_POST['justification'];

        Yii::app()->db->createCommand(
            'UPDATE instructor_faults f
             JOIN schedule s ON s.id = f.schedule_fk
             JOIN classroom c ON c.id = s.classroom_fk
             SET f.justification = :justification
             WHERE f.instructor_fk = :instructor
               AND s.day        = :day
               AND s.month      = :month
               AND c.school_year = :year'
        )->execute([
            ':justification' => $justification,
            ':instructor'    => $instructor,
            ':day'           => $day,
            ':month'         => $month,
            ':year'          => $year,
        ]);
    }

    public function actionGetClassrooms($instructorId = null)
    {
        if ($instructorId == null) {
            $instructorId = Yii::app()->request->getPost('instructorId', null);
        }
        $sql = 'SELECT c.id, esvm.id as stage_fk, ii.name as instructor_name, ed.id as edcenso_discipline_fk, ed.name as discipline_name, esvm.name as stage_name, c.name
        from instructor_teaching_data itd
        join teaching_matrixes tm ON itd.id = tm.teaching_data_fk
        join instructor_identification ii on itd.instructor_fk = ii.id
        join curricular_matrix cm on tm.curricular_matrix_fk = cm.id
        JOIN edcenso_discipline ed on ed.id = cm.discipline_fk
        join classroom c on c.id = itd.classroom_id_fk
        Join edcenso_stage_vs_modality esvm on esvm.id = c.edcenso_stage_vs_modality_fk
        WHERE c.school_year = :user_year and ii.id = :intructorId
        ORDER BY ii.name';

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':user_year', Yii::app()->user->year, PDO::PARAM_INT)
        ->bindValue(':intructorId', $instructorId, PDO::PARAM_INT);

        $classrooms = $command->queryAll();

        echo json_encode($classrooms);
    }

    /**
     * Renders a printable ficha of all teaching links for a given instructor.
     * @param int $id Instructor ID
     */
    public function actionPrintHistory($id, $teaching_id = null)
    {
        $instructor = $this->loadModel($id, $this->instructorIdentification);
        $instructorDoc = $this->loadModel($id, $this->instructorDocumentsAndAddress);

        // Filtra vínculo específico se teaching_id for informado
        $teachingFilter = $teaching_id ? ' AND itd.id = :teaching_id' : '';
        $params = [':id' => $id];
        if ($teaching_id) {
            $params[':teaching_id'] = (int)$teaching_id;
        }

        // Carrega cada vínculo com dados da escola, turma e etapa
        $teachingHistory = Yii::app()->db->createCommand("
            SELECT
                itd.id          AS itd_id,
                itd.school_inep_id_fk,
                itd.classroom_id_fk,
                itd.role,
                itd.contract_type,
                s.name          AS school_name,
                c.name          AS classroom_name,
                c.school_year   AS classroom_year,
                c.period        AS classroom_period,
                esm.name        AS stage_name
            FROM instructor_teaching_data itd
            LEFT JOIN school_identification s      ON s.inep_id = itd.school_inep_id_fk
            LEFT JOIN classroom c                  ON c.id = itd.classroom_id_fk
            LEFT JOIN edcenso_stage_vs_modality esm ON esm.id = c.edcenso_stage_vs_modality_fk
            WHERE itd.instructor_fk = :id {$teachingFilter}
            ORDER BY c.school_year DESC, s.name ASC, c.name ASC
        ")->queryAll(true, $params);

        // Para cada vínculo, busca aulas dadas (por disciplina via Diário de Classe) e faltas
        foreach ($teachingHistory as &$link) {
            $classroomId = $link['classroom_id_fk'];
            $itdId = $link['itd_id']; // instructor_teaching_data.id

            // Aulas ministradas por disciplina
            // Filtra pelas disciplinas do vínculo via:
            //   itd → teaching_matrixes → curricular_matrix → discipline_fk
            // (mesmo padrão usado no InstructorController::actionGetClassrooms)

            // Instancia o vínculo para usar os métodos encapsulados
            $itdModel = InstructorTeachingData::model()->findByPk($itdId);
            $classroom = Classroom::model()->findByPk($classroomId);

            // Verifica se a turma é de Fundamental Menor / Infantil (regência por dia)
            $isMinorStage = $classroom ? $classroom->checkIsStageMinorEducation() : false;

            // Obtém as aulas por disciplina (Para MinorStage, conta os dias únicos. Para Regulares, conta aulas totais)
            $classesByDiscipline = $itdModel->getGivenClassesByDiscipline($isMinorStage);

            if ($isMinorStage) {
                // Total ministrado: dias distintos da matriz do professor
                $totalGiven = $itdModel->getTotalGivenMinorStage();

                // Total previsto no horário (dias distintos previstos)
                $totalSchedules = $itdModel->getTotalSchedulesAssigned(true);
            } else {
                // Total geral de aulas ministradas somando a matriz regular
                $totalGiven = array_sum(array_column($classesByDiscipline, 'classes_given'));

                // Total de aulas previstas no horário (slots totais)
                $totalSchedules = $itdModel->getTotalSchedulesAssigned(false);
            }

            $link['is_minor_stage'] = $isMinorStage;

            $link['classes_by_discipline'] = $classesByDiscipline;
            $link['classes_given'] = $totalGiven;
            $link['total_schedules'] = $totalSchedules;

            // Dias letivos previstos para esta turma (contagem por dia distinto)
            $totalDistinctDays = (int)Yii::app()->db->createCommand(
                "SELECT COUNT(DISTINCT CONCAT(s.month, '-', s.day))
                 FROM schedule s
                 WHERE s.classroom_fk = :classroom
                   AND s.unavailable  = 0"
            )->queryScalar([':classroom' => $classroomId]);

            $link['total_distinct_days'] = $totalDistinctDays;

            // Faltas por dia distinto (cálculo por dia, não por horário)
            $faults = Yii::app()->db->createCommand(
                'SELECT s.day, s.month, s.year,
                        MIN(f.justification) AS justification
                 FROM instructor_faults f
                 JOIN schedule s ON s.id = f.schedule_fk
                 WHERE f.instructor_fk = :instructor_id
                   AND s.classroom_fk  = :classroom
                 GROUP BY s.year, s.month, s.day
                 ORDER BY s.year ASC, s.month ASC, s.day ASC'
            )->queryAll(true, [
                ':instructor_id' => $id,
                ':classroom'     => $classroomId,
            ]);

            $link['faults'] = $faults;
            $link['faults_count'] = count($faults);
        }
        unset($link);

        $this->layout = 'reports';
        $this->render('printHistory', [
            'instructor' => $instructor,
            'instructorDoc' => $instructorDoc,
            'teachingHistory' => $teachingHistory,
        ]);
    }

    /**
     * Renders a printable consolidated ficha of all teaching links for a given instructor in a specific year.
     * @param int $id Instructor ID
     * @param int $year Academic Year
     */
    public function actionPrintYearHistory($id, $year)
    {
        $instructor = $this->loadModel($id, $this->instructorIdentification);
        $instructorDoc = $this->loadModel($id, $this->instructorDocumentsAndAddress);

        // Carrega cada vínculo com dados da escola, turma e etapa apenas do ano selecionado
        $teachingHistory = Yii::app()->db->createCommand('
            SELECT
                itd.id          AS itd_id,
                itd.school_inep_id_fk,
                itd.classroom_id_fk,
                itd.role,
                itd.contract_type,
                s.name          AS school_name,
                c.name          AS classroom_name,
                c.school_year   AS classroom_year,
                c.period        AS classroom_period,
                esm.name        AS stage_name
            FROM instructor_teaching_data itd
            LEFT JOIN school_identification s      ON s.inep_id = itd.school_inep_id_fk
            LEFT JOIN classroom c                  ON c.id = itd.classroom_id_fk
            LEFT JOIN edcenso_stage_vs_modality esm ON esm.id = c.edcenso_stage_vs_modality_fk
            WHERE itd.instructor_fk = :id 
              AND c.school_year = :year
            ORDER BY s.name ASC, c.name ASC
        ')->queryAll(true, [
            ':id' => $id,
            ':year' => $year
        ]);

        // Para cada vínculo, busca aulas dadas (por disciplina via Diário de Classe) e faltas
        foreach ($teachingHistory as &$link) {
            $classroomId = $link['classroom_id_fk'];
            $itdId = $link['itd_id'];

            // Instancia o vínculo para usar os métodos encapsulados
            $itdModel = InstructorTeachingData::model()->findByPk($itdId);
            $classroom = Classroom::model()->findByPk($classroomId);

            // Verifica se a turma é de Fundamental Menor / Infantil (regência por dia)
            $isMinorStage = $classroom ? $classroom->checkIsStageMinorEducation() : false;

            // Obtém as aulas por disciplina (Para MinorStage, conta os dias únicos. Para Regulares, conta aulas totais)
            $classesByDiscipline = $itdModel->getGivenClassesByDiscipline($isMinorStage);

            if ($isMinorStage) {
                // Total ministrado: dias distintos da matriz do professor
                $totalGiven = $itdModel->getTotalGivenMinorStage();

                // Total previsto no horário (dias distintos previstos)
                $totalSchedules = $itdModel->getTotalSchedulesAssigned(true);
            } else {
                // Total geral de aulas ministradas somando a matriz regular
                $totalGiven = array_sum(array_column($classesByDiscipline, 'classes_given'));

                // Total de aulas previstas no horário (slots totais)
                $totalSchedules = $itdModel->getTotalSchedulesAssigned(false);
            }

            $link['is_minor_stage'] = $isMinorStage;
            $link['classes_by_discipline'] = $classesByDiscipline;
            $link['classes_given'] = $totalGiven;
            $link['total_schedules'] = $totalSchedules;

            // Dias letivos previstos para esta turma (contagem por dia distinto)
            $totalDistinctDays = (int)Yii::app()->db->createCommand(
                "SELECT COUNT(DISTINCT CONCAT(s.month, '-', s.day))
                 FROM schedule s
                 WHERE s.classroom_fk = :classroom
                   AND s.unavailable  = 0"
            )->queryScalar([':classroom' => $classroomId]);

            $link['total_distinct_days'] = $totalDistinctDays;

            // Faltas por dia distinto (cálculo por dia, não por horário)
            $faults = Yii::app()->db->createCommand(
                'SELECT s.day, s.month, s.year,
                        MIN(f.justification) AS justification
                 FROM instructor_faults f
                 JOIN schedule s ON s.id = f.schedule_fk
                 WHERE f.instructor_fk = :instructor_id
                   AND s.classroom_fk  = :classroom
                 GROUP BY s.year, s.month, s.day
                 ORDER BY s.month ASC, s.day ASC'
            )->queryAll(true, [
                ':instructor_id' => $id,
                ':classroom'     => $classroomId,
            ]);

            $link['faults'] = $faults;
            $link['faults_count'] = count($faults);
        }
        unset($link);

        // Total de dias únicos de falta no ano (sem dupla contagem entre turmas)
        $totalFaultDays = (int)Yii::app()->db->createCommand(
            'SELECT COUNT(DISTINCT CONCAT(c.school_year, \'-\', s.month, \'-\', s.day))
             FROM instructor_faults f
             JOIN schedule s  ON s.id  = f.schedule_fk
             JOIN classroom c ON c.id  = s.classroom_fk
             WHERE f.instructor_fk = :id
               AND c.school_year   = :year'
        )->queryScalar([':id' => $id, ':year' => $year]);

        $this->layout = 'reports';
        $this->render('printYearHistory', [
            'instructor'     => $instructor,
            'instructorDoc'  => $instructorDoc,
            'teachingHistory' => $teachingHistory,
            'year'           => $year,
            'totalFaultDays' => $totalFaultDays,
        ]);
    }
}
