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
    private $identification = 'InstructorIdentification';
    private $documentsAndAddress = 'InstructorDocumentsAndAddress';
    private $variableData = 'InstructorVariableData';
    private $teachingData = 'InstructorTeachingData';

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
                    'saveFrequency', 'saveJustification'
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
            'modelInstructorIdentification' => $this->loadModel($id, $this->identification),
        ]);
    }

    private function loadModelsInstructor()
    {
        $modelIdentification = new InstructorIdentification();
        $modelDocsAndAddress = new InstructorDocumentsAndAddress();
        $modelVariableData = new InstructorVariableData();

        return [
            'modelIdentification' => $modelIdentification,
            'modelDocumentsAndAddress' => $modelDocsAndAddress,
            'modelVariableData' => $modelVariableData
        ];
    }

    private function setModelIdentificationEdcenso($modelIdentification)
    {
        if (!isset($modelIdentification->edcenso_nation_fk)) {
            $modelIdentification->edcenso_nation_fk = 76;
        }
        if (!isset($modelIdentification->edcenso_uf_fk)) {
            $modelIdentification->edcenso_uf_fk = 0;
        }
        if (!isset($modelIdentification->edcenso_city_fk)) {
            $modelIdentification->edcenso_city_fk = 0;
        }
    }

    private function hasDocsAndAddress($modelDocsAndAddress)
    {
        return
            isset($modelDocsAndAddress->address) &&
            !empty($modelDocsAndAddress->address) &&
            isset($modelDocsAndAddress->neighborhood) &&
            !empty($modelDocsAndAddress->neighborhood) &&
            isset($modelDocsAndAddress->edcenso_uf_fk) &&
            !empty($modelDocsAndAddress->edcenso_uf_fk) &&
            isset($modelDocsAndAddress->edcenso_city_fk) &&
            !empty($modelDocsAndAddress->edcenso_city_fk);
    }

    private function saveDocsAndAddress($modelDocsAndAddress)
    {
        if ($this->hasDocsAndAddress($modelDocsAndAddress)) {
            $saveDocsAndAddress = true;
            return;
        }

        $error['documentsAndAddress'] =
            'CEP preenchido então, o Endereço, Bairro, UF e Cidade são Obrigatórios !';
    }

    private function hasVariableData($modelVariableData)
    {
        return
            isset(
                $modelVariableData->high_education_situation_1,
                $modelVariableData->high_education_course_code_1_fk,
                $modelVariableData->high_education_institution_code_1_fk
            ) ||
            isset(
                $modelVariableData->high_education_situation_2,
                $modelVariableData->high_education_course_code_2_fk,
                $modelVariableData->high_education_institution_code_2_fk
            ) ||
            isset(
                $modelVariableData->high_education_situation_3,
                $modelVariableData->high_education_course_code_3_fk,
                $modelVariableData->high_education_institution_code_3_fk
            );
    }

    private function setSchoolInepId($modelIdentification, $modelDocsAndAddress, $modelVariableData)
    {
        $modelIdentification->school_inep_id_fk = Yii::app()->user->school;
        $modelDocsAndAddress->school_inep_id_fk = $modelIdentification->school_inep_id_fk;
        $modelVariableData->school_inep_id_fk = $modelIdentification->school_inep_id_fk;
    }

    private function sucessSaveInstructor($modelDocsAndAddress, $modelVariableData)
    {
        if ($modelDocsAndAddress->save() && $modelVariableData->save()) {
            Yii::app()->user->setFlash(
                'success',
                Yii::t('default', 'Professor adicionado com sucesso!')
            );
            $this->redirect(['index']);
        }
    }

    private function saveInstructorModel($modelIdentification, $modelDocsAndAddress, $modelVariableData)
    {
        $this->setSchoolInepId($modelIdentification, $modelDocsAndAddress, $modelVariableData);


        if (
            $modelIdentification->validate() &&
            $modelDocsAndAddress->validate() &&
            $modelVariableData->validate()
        ) {
            $user = new Users();
            $user->name = $modelIdentification->name;
            $user->username = $modelDocsAndAddress->cpf;

            $passwordHasher = new PasswordHasher;
            $birthdayDate = str_replace("/", "", $modelIdentification->birthday_date);
            $user->password = $passwordHasher->bcriptHash($birthdayDate);

            if ($user->save()) {
                $userSchool = new UsersSchool();
                $userSchool->user_fk = $user->id;
                $userSchool->school_fk = Yii::app()->user->school;
                if ($userSchool->save()) {
                    $auth = Yii::app()->authManager;
                    $auth->assign('instructor', $user->id);
                    $modelIdentification->users_fk = $user->id;
                }
            }

            if ($modelIdentification->save()) {
                $modelDocsAndAddress->id = $modelIdentification->id;
                $modelVariableData->id = $modelIdentification->id;

                $modelVariableData->high_education_course_code_1_fk =
                    empty($modelVariableData->high_education_course_code_1_fk) ? null :
                    $modelVariableData->high_education_course_code_1_fk;

                $modelVariableData->high_education_course_code_2_fk =
                    empty($modelVariableData->high_education_course_code_2_fk) ? null :
                    $modelVariableData->high_education_course_code_2_fk;

                $modelVariableData->high_education_course_code_3_fk =
                    empty($modelVariableData->high_education_course_code_3_fk) ? null :
                    $modelVariableData->high_education_course_code_3_fk;

                $this->sucessSaveInstructor($modelDocsAndAddress, $modelVariableData);
            }
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $models = $this->loadModelsInstructor();
        $modelIdentification = $models['modelIdentification'];
        $modelDocsAndAddress = $models['modelDocumentsAndAddress'];
        $modelVariableData = $models['modelVariableData'];

        $error = [];

        $iIdentification = Yii::app()->request->getPost('InstructorIdentification', null);
        $iDocumentsAndAddress = Yii::app()->request->getPost('InstructorDocumentsAndAddress', null);
        $iVariableData = Yii::app()->request->getPost('InstructorVariableData', null);

        if (isset($iIdentification, $iDocumentsAndAddress, $iVariableData)) {
            $modelIdentification->attributes = $iIdentification;
            $modelDocsAndAddress->attributes = $iDocumentsAndAddress;
            $modelVariableData->attributes = $iVariableData;

            $this->setModelIdentificationEdcenso($modelIdentification);

            $saveInstructor = true;

            if (isset($modelDocsAndAddress->cep) && !empty($modelDocsAndAddress->cep)) {
                $this->saveDocsAndAddress($modelDocsAndAddress);
            }

            if (isset($modelVariableData->scholarity) && $modelVariableData->scholarity == 6) {
                if (!$this->hasVariableData($modelVariableData)) {
                    $error['variableData'] =
                        "Pelo menos uma situação do curso superior, código do curso superior,
                        tipo de instituição e instituição do curso superior deverão ser obrigatoriamente preenchidos";
                    $saveInstructor = false;
                }
            }

            if ($saveInstructor) {
                $this->saveInstructorModel($modelIdentification, $modelDocsAndAddress, $modelVariableData);
            }
        }

        $this->render(
            'create',
            [
                'modelInstructorIdentification' => $modelIdentification,
                'modelInstructorDocumentsAndAddress' => $modelDocsAndAddress,
                'modelInstructorVariableData' => $modelVariableData,
                'error' => $error,
            ]
        );
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $modelIdentification = $this->loadModel($id, $this->identification);
        $modelDocsAndAddress = $this->loadModel($id, $this->documentsAndAddress);
        $modelDocsAndAddress = isset($modelDocsAndAddress)
            ? $modelDocsAndAddress
            : new InstructorDocumentsAndAddress;
        $modelVariableData = $this->loadModel($id, $this->variableData);
        if ($modelVariableData == null) {
            $modelVariableData = new InstructorVariableData();
        }

        $saveInstructor = false;
        $saveDocsAndAddress = false;
        $saveVariableData = false;

        $error[] = '';
        if (
            isset(
                $_POST['InstructorIdentification'],
                $_POST['InstructorDocumentsAndAddress'],
                $_POST['InstructorVariableData']
            )
        ) {
            $modelIdentification->attributes = $_POST['InstructorIdentification'];
            $modelDocsAndAddress->attributes = $_POST['InstructorDocumentsAndAddress'];
            $modelVariableData->attributes = $_POST['InstructorVariableData'];

            $this->setModelIdentificationEdcenso($modelIdentification);

            $saveInstructor = true;

            $modelDocsAndAddress->cpf =
                str_replace([".", "-"], "", $modelDocsAndAddress->cpf);
            if (isset($modelDocsAndAddress->cep) && !empty($modelDocsAndAddress->cep)) {
                if (
                    isset($modelDocsAndAddress->address) &&
                    !empty($modelDocsAndAddress->address) &&
                    isset($modelDocsAndAddress->neighborhood) &&
                    !empty($modelDocsAndAddress->neighborhood) &&
                    isset($modelDocsAndAddress->edcenso_uf_fk) &&
                    !empty($modelDocsAndAddress->edcenso_uf_fk) &&
                    isset($modelDocsAndAddress->edcenso_city_fk) &&
                    !empty($modelDocsAndAddress->edcenso_city_fk)
                ) {
                    $saveDocsAndAddress = true;
                } else {
                    $error['documentsAndAddress'] =
                        'CEP preenchido então, o Endereço, Bairro, UF e Cidade são Obrigatórios !';
                }
            } else {
                $saveDocsAndAddress = true;
            }

            if (isset($modelVariableData->scholarity)) {
                if ($modelVariableData->scholarity == 6) {
                    if (
                        isset(
                            $modelVariableData->high_education_situation_1,
                            $modelVariableData->high_education_course_code_1_fk,
                            $modelVariableData->high_education_institution_code_1_fk
                        )
                        || isset(
                            $modelVariableData->high_education_situation_2,
                            $modelVariableData->high_education_course_code_2_fk,
                            $modelVariableData->high_education_institution_code_2_fk
                        )
                        || isset(
                            $modelVariableData->high_education_situation_3,
                            $modelVariableData->high_education_course_code_3_fk,
                            $modelVariableData->high_education_institution_code_3_fk
                        )
                    ) {
                        $saveVariableData = true;
                    } else {
                        $error['variableData'] = "Pelo menos uma situação do curso superior, código
do curso superior, tipo de instituição e instituição
do curso superior deverão ser obrigatoriamente
preenchidos";
                    }
                } else {
                    $saveVariableData = true;
                }
            }

            if ($saveInstructor && $saveDocsAndAddress && $saveVariableData) {
                // Setar todos os school_inep_id
                $modelDocsAndAddress->school_inep_id_fk = $modelIdentification->school_inep_id_fk;
                $modelVariableData->school_inep_id_fk = $modelIdentification->school_inep_id_fk;

                $modelVariableData->high_education_institution_code_1_fk =
                    empty($modelVariableData->high_education_institution_code_1_fk) ? null :
                    $modelVariableData->high_education_institution_code_1_fk;

                $modelVariableData->high_education_institution_code_2_fk =
                    empty($modelVariableData->high_education_institution_code_2_fk) ? null :
                    $modelVariableData->high_education_institution_code_2_fk;

                $modelVariableData->high_education_institution_code_3_fk =
                    empty($modelVariableData->high_education_institution_code_3_fk) ? null :
                    $modelVariableData->high_education_institution_code_3_fk;

                if (
                    $modelIdentification->validate() &&
                    $modelDocsAndAddress->validate() &&
                    $modelVariableData->validate() &&
                    $modelIdentification->save()
                ) {
                    $modelDocsAndAddress->id = $modelIdentification->id;
                    $modelVariableData->id = $modelIdentification->id;

                    $modelVariableData->high_education_course_code_1_fk =
                        empty($modelVariableData->high_education_course_code_1_fk) ? null :
                        $modelVariableData->high_education_course_code_1_fk;

                    $modelVariableData->high_education_course_code_2_fk =
                        empty($modelVariableData->high_education_course_code_2_fk) ? null :
                        $modelVariableData->high_education_course_code_2_fk;

                    $modelVariableData->high_education_course_code_3_fk =
                        empty($modelVariableData->high_education_course_code_3_fk) ? null :
                        $modelVariableData->high_education_course_code_3_fk;

                    if ($modelDocsAndAddress->save() && $modelVariableData->save()) {
                        Yii::app()->user->setFlash('success', Yii::t('default', 'Professor alterado com sucesso!'));
                        $this->redirect(['index']);
                    }
                }
            }
        }

        $this->render('update', [
            'modelInstructorIdentification' => $modelIdentification,
            'modelInstructorDocumentsAndAddress' => $modelDocsAndAddress,
            'modelInstructorVariableData' => $modelVariableData,
            'error' => $error,
        ]);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $modelIdentification = $this->loadModel($id, $this->identification);
        $modelDocsAndAddress = $this->loadModel($id, $this->documentsAndAddress);
        $modelVariableData = $this->loadModel($id, $this->variableData);
        $modelTeachingData = $this->loadModel($id, $this->teachingData);

        $delete = true;

        if (isset($modelDocsAndAddress)) {
            $modelDocsAndAddress->delete();
        }
        if (isset($modelVariableData)) {
            $modelVariableData->delete();
            foreach ($modelTeachingData as $td) {
                $delete = $delete && $td->delete();
            }
        }

        if (!($delete && $modelIdentification->delete())) {
            throw new CHttpException(404, 'The requested page does not exist.');

            return;
        }

        Yii::app()->user->setFlash(
            'success',
            Yii::t(
                'default',
                'Professor excluído com sucesso!'
            )
        );
        $this->redirect(['index']);
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

        $edcensoUfFk = $_POST['edcenso_uf_fk'];
        $currentCity = $_POST['current_city'];

        $data = EdcensoCity::model()->findAll('edcenso_uf_fk=:uf_id', [':uf_id' => (int)$edcensoUfFk]);
        $data = CHtml::listData($data, 'id', 'name');

        $options = array();
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
        $results = Yii::app()->db
            ->createCommand("  SELECT COUNT(*) AS total
                                FROM edcenso_ies
                                WHERE NAME like :q")
            ->bindValue(":q", "%" . $institutionName . "%")->queryAll();

        $total = (int)$results[0]["total"];

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
        $institutions = EdcensoIES::model()->findAllByAttributes(array('edcenso_uf_fk' => $edcensoUfFk));

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
        $modelIdentification = new InstructorIdentification('search');
        $modelDocsAndAddress = new InstructorDocumentsAndAddress('search');
        $modelVariableData = new InstructorVariableData('search');
        $modelTeachingData = new InstructorTeachingData('search');

        $modelIdentification->unsetAttributes();  // clear any default values
        $modelDocsAndAddress->unsetAttributes();  // clear any default values
        $modelVariableData->unsetAttributes();  // clear any default values
        $modelTeachingData->unsetAttributes();  // clear any default values
        if (
            isset(
                $_GET[$this->identification],
                $_GET[$this->documentsAndAddress],
                $_GET[$this->variableData],
                $_GET[$this->teachingData]
            )
        ) {
            $modelIdentification->attributes = $_GET['InstructorIdentification'];
            $modelDocsAndAddress->attributes = $_GET['InstructorDocumentsAndAddress'];
            $modelVariableData->attributes = $_GET['InstructorVariableData'];
            $modelTeachingData->attributes = $_GET['InstructorTeachingData'];
        }


        $this->render('admin', [
            'modelInstructorIdentification' => $modelIdentification,
            'modelInstructorDocumentsAndAddress' => $modelDocsAndAddress,
            'modelInstructorVariableData' => $modelVariableData,
            'modelInstructorTeachingData' => $modelTeachingData
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
        $instructor_inepid_id =
            isset($instructor->inep_id)
            && !empty($instructor->inep_id)
            ? $instructor->inep_id : $instructor->id;
        $return = null;
        if ($model == $this->identification) {
            $return = InstructorIdentification::model()->findByPk($id);
        } else if ($model == $this->documentsAndAddress) {
            if (isset($instructor->inep_id) && !empty($instructor->inep_id)) {
                $return = InstructorDocumentsAndAddress::model()->findByAttributes(
                    [
                        'inep_id' => $instructor_inepid_id,
                        "school_inep_id_fk" => Yii::app()->user->school
                    ]
                );
                if ($return == null) {
                    $return = InstructorDocumentsAndAddress::model()->findByAttributes(
                        [
                            'inep_id' => $instructor_inepid_id
                        ]
                    );
                }
            } else {
                $return = InstructorDocumentsAndAddress::model()->findByPk($instructor_inepid_id);
            }
        } else if ($model == $this->variableData) {
            if (isset($instructor->inep_id) && !empty($instructor->inep_id)) {
                $return = InstructorVariableData::model()->findByAttributes(
                    [
                        'inep_id' => $instructor_inepid_id,
                        "school_inep_id_fk" => Yii::app()->user->school
                    ]
                );
                if ($return == null) {
                    $return = InstructorVariableData::model()->findByAttributes(['inep_id' => $instructor_inepid_id]);
                }
            } else {
                $return = InstructorVariableData::model()->findByPk($instructor_inepid_id);
            }
        } else if ($model == $this->teachingData) {
            if (isset($instructor->inep_id) && !empty($instructor->inep_id)) { // VEr possível correção !!!!
                $return = InstructorTeachingData::model()->findAllByAttributes(
                    [
                        'instructor_inep_id' => $instructor_inepid_id
                    ]
                );
            } else {
                $return = InstructorTeachingData::model()->findAllByAttributes(
                    [
                        'instructor_fk' => $instructor_inepid_id
                    ]
                );
            }
        }

        if ($return === null && $model == $this->identification) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        if ($return === null && $model == $this->documentsAndAddress) {
            $return = InstructorDocumentsAndAddress::model()->findByPk($id);
        }

        if ($return === null && $model == $this->variableData) {
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
                if ($email != "") {
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

            return;
        }
        $this->render("updateEmails", ["instructors" => $instructors]);
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
                . " join instructor_teaching_data
                on instructor_teaching_data.classroom_id_fk = c.id "
                . " join instructor_identification
                on instructor_teaching_data.instructor_fk = instructor_identification.id ";
            $criteria->condition = "c.school_year = :school_year
                                    and c.school_inep_fk = :school_inep_fk
                                    and instructor_identification.users_fk = :users_fk";
            $criteria->order = "name";
            $criteria->params = array(
                ':school_year' => Yii::app()->user->year,
                ':school_inep_fk' => Yii::app()->user->school,
                ':users_fk' => Yii::app()->user->loginInfos->id
            );

            $classrooms = Classroom::model()->findAll($criteria);
        } else {
            $classrooms = Classroom::model()->findAll(
                'school_year = :school_year
                and school_inep_fk = :school_inep_fk
                order by name',
                [
                    'school_year' => Yii::app()->user->year,
                    'school_inep_fk' => Yii::app()->user->school
                ]
            );
        }

        $this->render(
            'frequency',
            [
                'instructors' => $instructors,
                'classrooms' => $classrooms
            ]
        );
    }

    public function actionGetFrequency()
    {
        $schedules = Schedule::model()->findAll(
            "classroom_fk = :classroom_fk and month = :month
            and unavailable = 0
            group by day
            order by day, schedule",
        [
            "classroom_fk" => $_POST["classroom"],
            "month" => $_POST["month"]
        ]);

        $criteria = new CDbCriteria();
        $criteria->with = array('instructorFk');
        $criteria->together = true;
        $criteria->order = 'name';
        $enrollments = InstructorTeachingData::model()->findAllByAttributes(
            array(
                'classroom_id_fk' => $_POST["classroom"],
                'instructor_fk' => $_POST["instructor"]
            ),
            $criteria
        );
        if ($schedules != null) {
            if ($enrollments != null) {
                $instructors = [];
                $dayName = ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"];
                foreach ($enrollments as $enrollment) {
                    $array["instructorId"] = $enrollment->instructor_fk;
                    $array["instructorName"] = $enrollment->instructorFk->name;
                    $array["schedules"] = [];
                    foreach ($schedules as $schedule) {
                        $instructorFault = InstructorFaults::model()->find(
                            "schedule_fk = :schedule_fk
                            and instructor_fk = :instructor_fk",
                            [
                                "schedule_fk" => $schedule->id,
                                "instructor_fk" => $enrollment->instructor_fk
                            ]
                        );
                        $available =
                            date("Y-m-d") >= Yii::app()->user->year
                            . "-"
                            . str_pad($schedule->month, 2, "0", STR_PAD_LEFT)
                            . "-"
                            . str_pad($schedule->day, 2, "0", STR_PAD_LEFT);
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
                echo json_encode(
                    [
                        "valid" => false,
                        "error" => "Cadastre professores nesta turma para trazer o quadro de frequência."
                    ]
                );
            }
        } else {
            echo json_encode(
                [
                    "valid" => false,
                    "error" => "No quadro de horário da turma, não existe dia letivo no mês selecionado
                                para este componente curricular/eixo."
                ]
            );
        }
    }

    public function actionGetFrequencyClassroom()
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

                return;
            }
            InstructorFaults::model()->deleteAll(
                "schedule_fk = :schedule_fk
                and instructor_fk = :instructor_fk",
                [
                    "schedule_fk" => $_POST["schedule"],
                    "instructor_fk" => $_POST["instructorId"]
                ]
            );
        }
    }

    public function actionSaveJustification()
    {
        $schedule = Schedule::model()->find(
            "classroom_fk = :classroom_fk
            and day = :day
            and month = :month and id = :schedule",
            [
                "classroom_fk" => $_POST["classroomId"],
                "day" => $_POST["day"],
                "month" => $_POST["month"],
                "schedule" => $_POST["schedule"]
            ]
        );
        $instructorFault = InstructorFaults::model()->find(
            "schedule_fk = :schedule_fk
            and instructor_fk = :instructor_fk",
            [
                "schedule_fk" => $schedule->id,
                "instructor_fk" => $_POST["instructorId"]
            ]
        );
        $instructorFault->justification = $_POST["justification"] == "" ? null : $_POST["justification"];
        $instructorFault->save();
    }
}
