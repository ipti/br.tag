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
    private $InstructorIdentification = 'InstructorIdentification';
    private $InstructorDocumentsAndAddress = 'InstructorDocumentsAndAddress';
    private $InstructorVariableData = 'InstructorVariableData';
    private $InstructorTeachingData = 'InstructorTeachingData';

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
                    'saveEmails', 'getCity', 'getCityByCep','getInstitutions', 'getInstitution',
                    'getCourses', 'delete', 'getFrequency', 'getFrequencyDisciplines', 'getFrequencyClassroom',
                    'saveFrequency', 'saveJustification', "getClassrooms"
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
            'modelInstructorIdentification' => $this->loadModel($id, $this->InstructorIdentification),
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
        $saveInstructor = FALSE;
        $saveDocumentsAndAddress = FALSE;
        $saveVariableData = FALSE;

        // i is an abbreviation for instructor
        $iIdentification = Yii::app()->request->getPost('InstructorIdentification', NULL);
        $iDocumentsAndAddress = Yii::app()->request->getPost('InstructorDocumentsAndAddress', NULL);
        $iVariableData = Yii::app()->request->getPost('InstructorVariableData', NULL);

        $error[] = '';
        if (isset($iIdentification, $iDocumentsAndAddress, $iVariableData)) {
            $modelInstructorIdentification->attributes = $iIdentification;
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
            if (isset($modelInstructorDocumentsAndAddress->cep) && !empty($modelInstructorDocumentsAndAddress->cep)) {
                //Então o endereço, uf e cidade são obrigatórios
                if (    isset($modelInstructorDocumentsAndAddress->address) &&
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

                if (isset(  $modelInstructorVariableData->high_education_situation_1,
                            $modelInstructorVariableData->high_education_course_code_1_fk,
                            $modelInstructorVariableData->high_education_institution_code_1_fk) ||
                    isset(  $modelInstructorVariableData->high_education_situation_2,
                            $modelInstructorVariableData->high_education_course_code_2_fk,
                            $modelInstructorVariableData->high_education_institution_code_2_fk) ||
                    isset(  $modelInstructorVariableData->high_education_situation_3,
                            $modelInstructorVariableData->high_education_course_code_3_fk,
                            $modelInstructorVariableData->high_education_institution_code_3_fk)) {
                    $saveVariableData = true;
                } else {
                    $error['variableData'] =    "Pelo menos uma situação do curso superior, código
                                                do curso superior, tipo de instituição e instituição
                                                do curso superior deverão ser obrigatoriamente
                                                preenchidos";
                }
            } else {
                $saveVariableData = true;
            }

            if ($saveInstructor && $saveDocumentsAndAddress && $saveVariableData) {

                // Setar todos os school_inep_id
                $modelInstructorIdentification->school_inep_id_fk = Yii::app()->user->school;
                $modelInstructorDocumentsAndAddress->school_inep_id_fk = $modelInstructorIdentification->school_inep_id_fk;
                $modelInstructorVariableData->school_inep_id_fk = $modelInstructorIdentification->school_inep_id_fk;

                if (    $modelInstructorIdentification->validate() &&
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

    private function createUser($modelInstructorIdentification, $modelInstructorDocumentsAndAddress) {
        $user = new Users();
        $user->name = $modelInstructorIdentification->name;
        $user->username = $modelInstructorDocumentsAndAddress->cpf;
        $user->password = $this->hashBirthdayDate($modelInstructorIdentification->birthday_date);
        return $user;
    }

    private function hashBirthdayDate($birthdayDate) {
        $passwordHasher = new PasswordHasher;
        $birthdayDate = str_replace("/", "", $birthdayDate);
        return $passwordHasher->bcriptHash($birthdayDate);
    }

    private function createUserSchool($user) {
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
        if(!$modelUser)
        {
           $user = $this->createUser($instructorIdentification, $instructorDocumentsAndAddress);
           if($user->save()){
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
        $modelInstructorIdentification = $this->loadModel($id, $this->InstructorIdentification);
        $modelInstructorDocumentsAndAddress = $this->loadModel($id, $this->InstructorDocumentsAndAddress);
        $modelInstructorDocumentsAndAddress = isset($modelInstructorDocumentsAndAddress) ? $modelInstructorDocumentsAndAddress : new InstructorDocumentsAndAddress;
        $modelInstructorVariableData = $this->loadModel($id, $this->InstructorVariableData);
        if ($modelInstructorVariableData == null) {
            $modelInstructorVariableData = new InstructorVariableData();
        }
        // Uncomment the following line if AJAX validation is needed
        //			 $this->performAjaxValidation($modelInstructorIdentification);

        $saveInstructor = FALSE;
        $saveDocumentsAndAddress = FALSE;
        $saveVariableData = FALSE;
        //==================================

        $error[] = '';
        if (isset($_POST['InstructorIdentification'], $_POST['InstructorDocumentsAndAddress'], $_POST['InstructorVariableData']))
        {
            $modelInstructorIdentification->attributes = $_POST['InstructorIdentification'];
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


            $saveInstructor = TRUE;

            //=== MODEL DocumentsAndAddress
            $modelInstructorDocumentsAndAddress->cpf = str_replace([".", "-"], "", $modelInstructorDocumentsAndAddress->cpf);
            if (isset($modelInstructorDocumentsAndAddress->cep) && !empty($modelInstructorDocumentsAndAddress->cep)) {
                //Então o endereço, uf e cidade são obrigatórios
                if (    isset($modelInstructorDocumentsAndAddress->address) &&
                        !empty($modelInstructorDocumentsAndAddress->address) &&
                        isset($modelInstructorDocumentsAndAddress->neighborhood) &&
                        !empty($modelInstructorDocumentsAndAddress->neighborhood) &&
                        isset($modelInstructorDocumentsAndAddress->edcenso_uf_fk) &&
                        !empty($modelInstructorDocumentsAndAddress->edcenso_uf_fk) &&
                        isset($modelInstructorDocumentsAndAddress->edcenso_city_fk) &&
                        !empty($modelInstructorDocumentsAndAddress->edcenso_city_fk)) {
                    $saveDocumentsAndAddress = TRUE;
                } else {
                    $error['documentsAndAddress'] = 'CEP preenchido então, o Endereço, Bairro, UF e Cidade são Obrigatórios !';
                }
            } else {
                $saveDocumentsAndAddress = TRUE;
            }
            //======================================
            //=== MODEL VariableData
            if (isset($modelInstructorVariableData->scholarity)) {
                if ($modelInstructorVariableData->scholarity == 6) {
                    if (isset($modelInstructorVariableData->high_education_situation_1, $modelInstructorVariableData->high_education_course_code_1_fk, $modelInstructorVariableData->high_education_institution_code_1_fk) || isset($modelInstructorVariableData->high_education_situation_2, $modelInstructorVariableData->high_education_course_code_2_fk, $modelInstructorVariableData->high_education_institution_code_2_fk) || isset($modelInstructorVariableData->high_education_situation_3, $modelInstructorVariableData->high_education_course_code_3_fk, $modelInstructorVariableData->high_education_institution_code_3_fk)) {
                        $saveVariableData = TRUE;
                    } else {
                        $error['variableData'] = "Pelo menos uma situação do curso superior, código
do curso superior, tipo de instituição e instituição
do curso superior deverão ser obrigatoriamente
preenchidos";
                    }
                } else {
                    $saveVariableData = TRUE;
                }
            }

            if ($saveInstructor && $saveDocumentsAndAddress && $saveVariableData) {
                // Setar todos os school_inep_id
                $modelInstructorDocumentsAndAddress->school_inep_id_fk = $modelInstructorIdentification->school_inep_id_fk;
                $modelInstructorVariableData->school_inep_id_fk = $modelInstructorIdentification->school_inep_id_fk;

                $modelInstructorVariableData->high_education_institution_code_1_fk =
                    empty($modelInstructorVariableData->high_education_institution_code_1_fk) ? NULL :
                    $modelInstructorVariableData->high_education_institution_code_1_fk;

                $modelInstructorVariableData->high_education_institution_code_2_fk =
                    empty($modelInstructorVariableData->high_education_institution_code_2_fk) ? NULL :
                    $modelInstructorVariableData->high_education_institution_code_2_fk;

                $modelInstructorVariableData->high_education_institution_code_3_fk =
                    empty($modelInstructorVariableData->high_education_institution_code_3_fk) ? NULL :
                    $modelInstructorVariableData->high_education_institution_code_3_fk;

                if (    $modelInstructorIdentification->validate() &&
                        $modelInstructorDocumentsAndAddress->validate() &&
                        $modelInstructorVariableData->validate() &&
                        $modelInstructorIdentification->save()) {
                    $modelInstructorDocumentsAndAddress->id = $modelInstructorIdentification->id;
                    $modelInstructorVariableData->id = $modelInstructorIdentification->id;
                    $modelInstructorIdentification = $this->checkHasUser($modelInstructorIdentification, $modelInstructorDocumentsAndAddress);

                    $modelInstructorVariableData->high_education_course_code_1_fk =
                        empty($modelInstructorVariableData->high_education_course_code_1_fk) ? NULL :
                        $modelInstructorVariableData->high_education_course_code_1_fk;

                    $modelInstructorVariableData->high_education_course_code_2_fk =
                        empty($modelInstructorVariableData->high_education_course_code_2_fk) ? NULL :
                        $modelInstructorVariableData->high_education_course_code_2_fk;

                    $modelInstructorVariableData->high_education_course_code_3_fk =
                        empty($modelInstructorVariableData->high_education_course_code_3_fk) ? NULL :
                        $modelInstructorVariableData->high_education_course_code_3_fk;

                    if ($modelInstructorDocumentsAndAddress->save() && $modelInstructorVariableData->save()) {
                        Yii::app()->user->setFlash('success', Yii::t('default', 'Professor alterado com sucesso!'));
                        $this->redirect(['index']);
                    }
                }
            }
        }

        //====================================
        $this->render('update', [
            'modelInstructorIdentification' => $modelInstructorIdentification,
            'modelInstructorDocumentsAndAddress' => $modelInstructorDocumentsAndAddress,
            'modelInstructorVariableData' => $modelInstructorVariableData, 'error' => $error,
        ]);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {

        $modelInstructorIdentification = $this->loadModel($id, $this->InstructorIdentification);
        $modelInstructorDocumentsAndAddress = $this->loadModel($id, $this->InstructorDocumentsAndAddress);
        $modelInstructorVariableData = $this->loadModel($id, $this->InstructorVariableData);
        $modelInstructorTeachingData = $this->loadModel($id, $this->InstructorTeachingData);

        $delete = TRUE;
        $numClassrooms = count($modelInstructorTeachingData);
        if($numClassrooms > 0){
            // $classroomsInstructorIsAssociated = array_map()
            // $classrooms = $this->actionGetClassrooms($id);
            $classrooms = $modelInstructorIdentification->getClassrooms();
            $htmlForAlert = "<b>Professor(a) " . $modelInstructorIdentification->name . " não pode ser excluído uma vez que está associado à(às) seguinte(s) turma(s):</b><br>";
            foreach($classrooms as $classroom)
            {
                $teachingData =
                    "Turma: <b>" . $classroom['classroom_name'] . "</b>" .
                    " / Disciplina: " . $classroom['discipline_name'] .
                    " / Ano: " . $classroom['syear'] .
                    " / Escola: " . $classroom['school_name'] .
                    "<br>";
                $anchorToUpdateClassroom = "<a href='" . Yii::app()->createUrl('classroom/update', array('id' => $classroom['cid'])) . "'>" . $teachingData ."</a>";
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

    private function removeWhiteSpace($text)
    {
        $text = preg_replace('/[\t\n\r\0\x0B]/', '', $text);
        $text = preg_replace('/([\s])\1+/', ' ', $text);
        $text = trim($text);
        return $text;
    }

    //Método para Solicitação Ajax para o select UF x Cities

    public function actionGetCity()
    {

        $edcenso_uf_fk = $_POST['edcenso_uf_fk'];
        $current_city = $_POST['current_city'];

        $data = EdcensoCity::model()->findAll('edcenso_uf_fk=:uf_id', [':uf_id' => (int)$edcenso_uf_fk]);
        $data = CHtml::listData($data, 'id', 'name');

        $options = array();
        foreach ($data as $value => $name) {
            array_push($options, CHtml::tag(
                'option',
                [
                    'value' => $value,
                    'selected' => $value == $current_city
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
        $data = NULL;

        if (!empty($cep)) {
            $data = EdcensoCity::model()->find('cep_initial <= ' . $cep . ' and cep_final >= ' . $cep);
        }

        $result = ($data == NULL) ? ['UF' => NULL, 'City' => NULL] : [
            'UF' => $data->edcenso_uf_fk, 'City' => $data->id

        ];
        echo json_encode($result);
    }

    //@done s1 - Criar Função que retorna instituições filtrando por tipo
    //@done s1 - Modificar função para que ela fique mais rápida
    public function actionGetInstitutions()
    {
        $institutionName = Yii::app()->request->getPost('q');
        $results = Yii::app()->db
            ->createCommand("  SELECT COUNT(*) AS total
                                FROM edcenso_ies
                                WHERE NAME like :q")
            ->bindValue(":q", "%" . $institutionName . "%")->queryAll();

        $total = (int)$results[0]["total"];

        $data = EdcensoIES::model()->findAll("name like '%" . $institutionName . "%' ORDER BY name LIMIT 0,10");
        $data = CHtml::listData($data, 'id', 'name');

        $return = [];
        // $return['total'] = $total;
        $return = [];
        foreach ($data as $value => $name) {
            array_push($return, ['id' => CHtml::encode($value), 'name' => CHtml::encode($name)]);
        }
        header('Content-Type: application/json; charset="UTF-8"');
        echo json_encode($return, JSON_OBJECT_AS_ARRAY);
    }
    public function actionGetInstitution (){
        $edcensoUfFk = Yii::app()->request->getPost('edcenso_uf_fk');
        $institutions = EdcensoIES::model()->findAllByAttributes(array('edcenso_uf_fk' => $edcensoUfFk));
        // $institutions = CHtml::listData($institutions, 'id', 'name');

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

        echo CHtml::tag('option', ['value' => ''], 'Selecione o Curso', TRUE);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', ['value' => $value], CHtml::encode($name), TRUE);
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
        if (isset($_GET[$this->InstructorIdentification], $_GET[$this->InstructorDocumentsAndAddress], $_GET[$this->InstructorVariableData], $_GET[$this->InstructorTeachingData])) {
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
        if ($model == $this->InstructorIdentification) {
            $return = InstructorIdentification::model()->findByPk($instructorId);
        } else if ($model == $this->InstructorDocumentsAndAddress) {
            $return = InstructorDocumentsAndAddress::model()->findByPk($instructorId);
        } else if ($model == $this->InstructorVariableData) {
            $return = InstructorVariableData::model()->findByPk($instructorId);
        } else if ($model == $this->InstructorTeachingData) {
            $return = InstructorTeachingData::model()->findAllByAttributes(['instructor_fk' => $instructorId]);
        }

        if ($return === null && $model == $this->InstructorIdentification) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        if ($return === null && $model == $this->InstructorDocumentsAndAddress) {
            $return = InstructorDocumentsAndAddress::model()->findByPk($id);
        }

        if ($return === null && $model == $this->InstructorVariableData) {
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
            $success = FALSE;
            foreach ($_POST as $id => $email) {
                if ($email != "") {
                    $success = TRUE;
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
            $this->render("updateEmails", ["instructors" => $instructors]);
        }
    }

    public function actionFrequency()
    {
        $instructors = InstructorIdentification::model()->findAll([
            'order' => 'name',
        ]);

        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $criteria = new CDbCriteria;
            $criteria->alias = "c";
            $criteria->join = ""
                . " join instructor_teaching_data on instructor_teaching_data.classroom_id_fk = c.id "
                . " join instructor_identification on instructor_teaching_data.instructor_fk = instructor_identification.id ";
            $criteria->condition = "c.school_year = :school_year and c.school_inep_fk = :school_inep_fk and instructor_identification.users_fk = :users_fk";
            $criteria->order = "name";
            $criteria->params = array(':school_year' => Yii::app()->user->year, ':school_inep_fk' => Yii::app()->user->school, ':users_fk' => Yii::app()->user->loginInfos->id);

            $classrooms = Classroom::model()->findAll($criteria);
        } else {
            $classrooms = Classroom::model()->findAll('school_year = :school_year and school_inep_fk = :school_inep_fk order by name', ['school_year' => Yii::app()->user->year, 'school_inep_fk' => Yii::app()->user->school]);
        }

        $this->render('frequency', ['instructors' => $instructors, 'classrooms' => $classrooms]);
    }

    public function actionGetFrequency()
    {
        $schedules = Schedule::model()->findAll("classroom_fk = :classroom_fk and month = :month and unavailable = 0 group by day order by day, schedule", ["classroom_fk" => $_POST["classroom"], "month" => $_POST["month"]]);

        $criteria = new CDbCriteria();
        $criteria->with = array('instructorFk');
        $criteria->together = true;
        $criteria->order = 'name';
        $enrollments = InstructorTeachingData::model()->findAllByAttributes(array('classroom_id_fk' => $_POST["classroom"], 'instructor_fk' => $_POST["instructor"]), $criteria);
        if ($schedules != null) {
            if ($enrollments != null) {
                $instructors = [];
                $dayName = ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"];
                foreach ($enrollments as $enrollment) {
                    $array["instructorId"] = $enrollment->instructor_fk;
                    $array["instructorName"] = $enrollment->instructorFk->name;
                    $array["schedules"] = [];
                    foreach ($schedules as $schedule) {
                        $instructorFault = InstructorFaults::model()->find("schedule_fk = :schedule_fk and instructor_fk = :instructor_fk", ["schedule_fk" => $schedule->id, "instructor_fk" => $enrollment->instructor_fk]);
                        $available = date("Y-m-d") >= Yii::app()->user->year . "-" . str_pad($schedule->month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($schedule->day, 2, "0", STR_PAD_LEFT);
                        array_push($array["schedules"], [
                            "available" => $available,
                            "day" => $schedule->day,
                            "week_day" => $dayName[$schedule->week_day],
                            "schedule" => $schedule->schedule,
                            "idSchedule" => $schedule->id,
                            "fault" => $instructorFault != null,
                            "justification" => $instructorFault->justification
                        ]);
                    }
                    array_push($instructors, $array);
                }
                echo json_encode(["valid" => true, "instructors" => $instructors]);
            } else {
                echo json_encode(["valid" => false, "error" => "Cadastre professores nesta turma para trazer o quadro de frequência."]);
            }
        } else {
            echo json_encode(["valid" => false, "error" => "No quadro de horário da turma, não existe dia letivo no mês selecionado para este componente curricular/eixo."]);
        }
    }

    public function actionGetFrequencyClassroom ()
    {
        $instructor = htmlspecialchars($_POST["instructor"]);
        $classrooms = Yii::app()->db->createCommand("SELECT c.id, c.name FROM classroom c
                JOIN instructor_teaching_data itd ON(c.id = itd.classroom_id_fk)
                WHERE itd.instructor_fk = :instructor")
            ->bindParam(":instructor", $instructor)
            ->queryAll();
        echo "<option value>" . Yii::t('default', 'Select Classrom') . "</option>";
        foreach ($classrooms as $classroom) {
            echo "<option value=" . $classroom['id'] . ">" . $classroom['name'] . "</option>";
        }
    }

    public function actionGetFrequencyDisciplines()
    {
        $instructor = htmlspecialchars($_POST["instructor"]);
        $classroom = htmlspecialchars($_POST["classroom"]);
        $disciplines = Yii::app()->db->createCommand(
            "SELECT ed.id, ed.name FROM classroom c
                JOIN instructor_teaching_data itd ON(c.id = itd.classroom_id_fk)
                JOIN teaching_matrixes tm ON(itd.id = tm.teaching_data_fk)
                JOIN curricular_matrix cm ON(tm.curricular_matrix_fk = cm.id)
                JOIN edcenso_discipline ed ON(ed.id = cm.discipline_fk)
                WHERE itd.instructor_fk = :instructor AND c.id = :classroom"
        )
            ->bindParam(":instructor", $instructor)
            ->bindParam(":classroom", $classroom)
            ->queryAll();
        echo "<option value>" . Yii::t('default', 'Select Discipline') . "</option>";
        foreach ($disciplines as $discipline) {
            echo "<option value=" . $discipline['id'] . ">" . $discipline['name'] . "</option>";
        }
    }

    public function actionSaveFrequency()
    {
        if ($_POST["instructorId"] != null) {
            if ($_POST["fault"] == "1") {
                $instructorFault = new InstructorFaults();
                $instructorFault->instructor_fk = $_POST["instructorId"];
                $instructorFault->schedule_fk = $_POST["schedule"];
                $instructorFault->save();
            } else {
                InstructorFaults::model()->deleteAll("schedule_fk = :schedule_fk and instructor_fk = :instructor_fk", ["schedule_fk" => $_POST["schedule"], "instructor_fk" => $_POST["instructorId"]]);
            }
        }
    }

    public function actionSaveJustification()
    {
        $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and day = :day and month = :month and id = :schedule", ["classroom_fk" => $_POST["classroomId"], "day" => $_POST["day"], "month" => $_POST["month"], "schedule" => $_POST["schedule"]]);
        $instructorFault = InstructorFaults::model()->find("schedule_fk = :schedule_fk and instructor_fk = :instructor_fk", ["schedule_fk" => $schedule->id, "instructor_fk" => $_POST["instructorId"]]);
        $instructorFault->justification = $_POST["justification"] == "" ? null : $_POST["justification"];
        $instructorFault->save();
    }

    public function actionGetClassrooms($instructorId = null) {
        if($instructorId == null){
            $instructorId = Yii::app()->request->getPost('instructorId', NULL);
        }
        $sql = "SELECT c.id, esvm.id as stage_fk, ii.name as instructor_name, ed.id as edcenso_discipline_fk, ed.name as discipline_name, esvm.name as stage_name, c.name
        from instructor_teaching_data itd
        join teaching_matrixes tm ON itd.id = tm.teaching_data_fk
        join instructor_identification ii on itd.instructor_fk = ii.id
        join curricular_matrix cm on tm.curricular_matrix_fk = cm.id
        JOIN edcenso_discipline ed on ed.id = cm.discipline_fk
        join classroom c on c.id = itd.classroom_id_fk
        Join edcenso_stage_vs_modality esvm on esvm.id = c.edcenso_stage_vs_modality_fk
        WHERE c.school_year = :user_year and ii.id = :intructorId
        ORDER BY ii.name";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':user_year', Yii::app()->user->year, PDO::PARAM_INT)
        ->bindValue(':intructorId', $instructorId, PDO::PARAM_INT);

        $classrooms = $command->queryAll();

        echo json_encode($classrooms);
    }
}
