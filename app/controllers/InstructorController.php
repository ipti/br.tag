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
                    'index', 'view', 'create', 'update', 'updateEmails', 'frequency', 'saveEmails', 'getCity', 'getCityByCep',
                    'getInstitutions', 'getCourses', 'delete', 'getFrequency', 'getFrequencyDisciplines', 'getFrequencyClassroom',
                    'saveFrequency'
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
//        $mII = new InstructorIdentification();
//        $mDA = new InstructorDocumentsAndAddress();
//        $mVD = new InstructorVariableData();
//
//        if(isset($_POST['InstructorIdentification'],$_POST['InstructorDocumentsAndAddress'],$_POST['InstructorVariableData'])){
//            $mII->attributes = $_POST['InstructorIdentification'];
//            $mDA->attributes = $_POST['InstructorDocumentsAndAddress'];
//            $mVD->attributes = $_POST['InstructorVariableData'];
//            
//            $mII->school_inep_id_fk = Yii::app()->user->school;
//            $mDA->school_inep_id_fk = Yii::app()->user->school;
//            $mVD->school_inep_id_fk = Yii::app()->user->school;
//            
//            if($mII->validate() && $mDA->validate() && $mVD->validate()){
//                if($mII->save()){
//                    $mDA->id = $mII->id;
//                    $mVD->id = $mII->id;
//                    var_dump($mDA->validate() && $mVD->validate());
//                    if($mDA->validate() && $mVD->validate()){
//                        exit;
//                        if($mDA->save() && $mVD->save()){
//                            Yii::app()->user->setFlash('success', Yii::t('default', 'Professor adicionado com sucesso!'));
//                            $this->redirect(array('index'));
//                        }
//                    }
//                }
//            }
//        }
//        $error[] = '';
//        
//        $this->render('create', array(
//            'modelInstructorIdentification' => $mII,
//            'modelInstructorDocumentsAndAddress' => $mDA,
//            'modelInstructorVariableData' => $mVD,
//            'error' => $error,
//        ));

        $modelInstructorIdentification = new InstructorIdentification();
        $modelInstructorDocumentsAndAddress = new InstructorDocumentsAndAddress();
        $modelInstructorVariableData = new InstructorVariableData();
        $saveInstructor = FALSE;
        $saveDocumentsAndAddress = FALSE;
        $saveVariableData = FALSE;

        $error[] = '';
        if (isset($_POST['InstructorIdentification'], $_POST['InstructorDocumentsAndAddress'], $_POST['InstructorVariableData'])) {
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
            if (isset($modelInstructorDocumentsAndAddress->cep) && !empty($modelInstructorDocumentsAndAddress->cep)) {
                //Então o endereço, uf e cidade são obrigatórios
                if (isset($modelInstructorDocumentsAndAddress->address) && !empty($modelInstructorDocumentsAndAddress->address) && isset($modelInstructorDocumentsAndAddress->neighborhood) && !empty($modelInstructorDocumentsAndAddress->neighborhood) && isset($modelInstructorDocumentsAndAddress->edcenso_uf_fk) && !empty($modelInstructorDocumentsAndAddress->edcenso_uf_fk) && isset($modelInstructorDocumentsAndAddress->edcenso_city_fk) && !empty($modelInstructorDocumentsAndAddress->edcenso_city_fk)) {

                    $saveDocumentsAndAddress = TRUE;
                } else {
                    $error['documentsAndAddress'] = 'CEP preenchido então, o Endereço, Bairro, UF e Cidade são Obrigatórios !';
                }
            } else {
                $saveDocumentsAndAddress = TRUE;
            }
            //======================================
            //=== MODEL VariableData
            if (isset($modelInstructorVariableData->scholarity) && $modelInstructorVariableData->scholarity == 6) {

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

            if ($saveInstructor && $saveDocumentsAndAddress && $saveVariableData) {

                // Setar todos os school_inep_id
                $modelInstructorIdentification->school_inep_id_fk = Yii::app()->user->school;
                $modelInstructorDocumentsAndAddress->school_inep_id_fk = $modelInstructorIdentification->school_inep_id_fk;
                $modelInstructorVariableData->school_inep_id_fk = $modelInstructorIdentification->school_inep_id_fk;

                if ($modelInstructorIdentification->validate() && $modelInstructorDocumentsAndAddress->validate() && $modelInstructorVariableData->validate()) {

                    $user = new Users();
                    $user->name = $modelInstructorIdentification->name;
                    $user->username = $modelInstructorDocumentsAndAddress->cpf;
                    $user->password = md5(str_replace("/", "", $modelInstructorIdentification->birthday_date));
                    if ($user->save()) {
                        $userSchool = new UsersSchool();
                        $userSchool->user_fk = $user->id;
                        $userSchool->school_fk = Yii::app()->user->school;
                        if ($userSchool->save()) {
                            $auth = Yii::app()->authManager;
                            $auth->assign('instructor', $user->id);
                            $modelInstructorIdentification->users_fk = $user->id;
                        }
                    }

                    if ($modelInstructorIdentification->save()) {
                        $modelInstructorDocumentsAndAddress->id = $modelInstructorIdentification->id;
                        $modelInstructorVariableData->id = $modelInstructorIdentification->id;

                        $modelInstructorVariableData->high_education_course_code_1_fk = empty($modelInstructorVariableData->high_education_course_code_1_fk) ? NULL : $modelInstructorVariableData->high_education_course_code_1_fk;
                        $modelInstructorVariableData->high_education_course_code_2_fk = empty($modelInstructorVariableData->high_education_course_code_2_fk) ? NULL : $modelInstructorVariableData->high_education_course_code_2_fk;
                        $modelInstructorVariableData->high_education_course_code_3_fk = empty($modelInstructorVariableData->high_education_course_code_3_fk) ? NULL : $modelInstructorVariableData->high_education_course_code_3_fk;

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
        if (isset($_POST['InstructorIdentification'], $_POST['InstructorDocumentsAndAddress'], $_POST['InstructorVariableData'])) {
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
            if (isset($modelInstructorDocumentsAndAddress->cep) && !empty($modelInstructorDocumentsAndAddress->cep)) {
                //Então o endereço, uf e cidade são obrigatórios
                if (isset($modelInstructorDocumentsAndAddress->address) && !empty($modelInstructorDocumentsAndAddress->address) && isset($modelInstructorDocumentsAndAddress->neighborhood) && !empty($modelInstructorDocumentsAndAddress->neighborhood) && isset($modelInstructorDocumentsAndAddress->edcenso_uf_fk) && !empty($modelInstructorDocumentsAndAddress->edcenso_uf_fk) && isset($modelInstructorDocumentsAndAddress->edcenso_city_fk) && !empty($modelInstructorDocumentsAndAddress->edcenso_city_fk)) {

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

                $modelInstructorVariableData->high_education_institution_code_1_fk = empty($modelInstructorVariableData->high_education_institution_code_1_fk) ? NULL : $modelInstructorVariableData->high_education_institution_code_1_fk;
                $modelInstructorVariableData->high_education_institution_code_2_fk = empty($modelInstructorVariableData->high_education_institution_code_2_fk) ? NULL : $modelInstructorVariableData->high_education_institution_code_2_fk;
                $modelInstructorVariableData->high_education_institution_code_3_fk = empty($modelInstructorVariableData->high_education_institution_code_3_fk) ? NULL : $modelInstructorVariableData->high_education_institution_code_3_fk;

                if ($modelInstructorIdentification->validate() && $modelInstructorDocumentsAndAddress->validate() && $modelInstructorVariableData->validate() && $modelInstructorIdentification->save()) {
                    $modelInstructorDocumentsAndAddress->id = $modelInstructorIdentification->id;
                    $modelInstructorVariableData->id = $modelInstructorIdentification->id;

                    $modelInstructorVariableData->high_education_course_code_1_fk = empty($modelInstructorVariableData->high_education_course_code_1_fk) ? NULL : $modelInstructorVariableData->high_education_course_code_1_fk;
                    $modelInstructorVariableData->high_education_course_code_2_fk = empty($modelInstructorVariableData->high_education_course_code_2_fk) ? NULL : $modelInstructorVariableData->high_education_course_code_2_fk;
                    $modelInstructorVariableData->high_education_course_code_3_fk = empty($modelInstructorVariableData->high_education_course_code_3_fk) ? NULL : $modelInstructorVariableData->high_education_course_code_3_fk;
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

        if ($modelInstructorDocumentsAndAddress->delete() && $modelInstructorVariableData->delete()) {
            foreach ($modelInstructorTeachingData as $td) {
                $delete = $delete && $td->delete();
            }
            if ($delete && $modelInstructorIdentification->delete()) {
                Yii::app()->user->setFlash('success', Yii::t('default', 'Professor excluído com sucesso!'));
                $this->redirect(['index']);
            }
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }


//            if( $this->loadModel($id, $this->SCHOOL_STRUCTURE)->delete()
//                && $this->loadModel($id, $this->SCHOOL_IDENTIFICATION)->delete()){
//                    Yii::app()->user->setFlash('success', Yii::t('default', 'Escola excluída com sucesso:'));
//                    $this->redirect(array('index'));
//            }else{
//                throw new CHttpException(404,'The requested page does not exist.');
//            }
//        
//        if (Yii::app()->request->isPostRequest) {
//            // we only allow deletion via POST request
//            $this->loadModel($id)->delete();
//
//            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
//            if (!isset($_GET['ajax']))
//                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
//        }
//        else
//            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $query = InstructorIdentification::model()->findAll();
        $filter = new InstructorIdentification('search');
        $filter->unsetAttributes();  // clear any default values
        if (isset($_GET['InstructorIdentification'])) {
            $_GET['InstructorIdentification']['name'] = $this->removeWhiteSpace($_GET['InstructorIdentification']['name']);
            $filter->attributes = $_GET['InstructorIdentification'];
        }
        $school = Yii::app()->user->school;
        $dataProvider = new CActiveDataProvider('InstructorIdentification', [
            'criteria' => [
                'order' => 'name ASC',
            ], 'pagination' => [
                'pageSize' => count($query),
            ]
        ]);
        $this->render('index', [
            'dataProvider' => $dataProvider, 'filter' => $filter
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

        $data = EdcensoCity::model()->findAll('edcenso_uf_fk=:uf_id', [':uf_id' => (int)$edcenso_uf_fk]);
        $data = CHtml::listData($data, 'id', 'name');

        echo CHtml::tag('option', ['value' => ""], 'Selecione uma Cidade', TRUE);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', ['value' => $value], CHtml::encode($name), TRUE);
        }
    }

    public function actionGetCityByCep()
    {
        $cep = $_POST['cep'];
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
        $results = Yii::app()->db->createCommand("SELECT COUNT(*) as total FROM edcenso_ies where name like :q")->bindValue(":q", "%" . $_POST['q'] . "%")->queryAll();
        $total = (int)$results[0]["total"];

        $data = EdcensoIES::model()->findAll("name like '%" . $_POST['q'] . "%' ORDER BY name LIMIT " . (($_POST['page'] - 1) * 10) . ",10");
        $data = CHtml::listData($data, 'id', 'name');

        $return = [];
        $return['total'] = $total;
        $return['ies'] = [];
        foreach ($data as $value => $name) {
            array_push($return['ies'], ['id' => CHtml::encode($value), 'name' => CHtml::encode($name)]);
        }

        echo json_encode($return);
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
        $instructor_inepid_id = isset($instructor->inep_id) && !empty($instructor->inep_id) ? $instructor->inep_id : $instructor->id;
        $return = NULL;
        if ($model == $this->InstructorIdentification) {
            $return = InstructorIdentification::model()->findByPk($id);
        } else if ($model == $this->InstructorDocumentsAndAddress) {
            if (isset($instructor->inep_id) && !empty($instructor->inep_id)) {
                $return = InstructorDocumentsAndAddress::model()->findByAttributes(['inep_id' => $instructor_inepid_id, "school_inep_id_fk" => Yii::app()->user->school]);
                if ($return == null) {
                    $return = InstructorDocumentsAndAddress::model()->findByAttributes(['inep_id' => $instructor_inepid_id]);
                }
            } else {
                $return = InstructorDocumentsAndAddress::model()->findByPk($instructor_inepid_id);
            }
        } else if ($model == $this->InstructorVariableData) {
            if (isset($instructor->inep_id) && !empty($instructor->inep_id)) {
                $return = InstructorVariableData::model()->findByAttributes(['inep_id' => $instructor_inepid_id, "school_inep_id_fk" => Yii::app()->user->school]);
                if ($return == null) {
                    $return = InstructorVariableData::model()->findByAttributes(['inep_id' => $instructor_inepid_id]);
                }
            } else {
                $return = InstructorVariableData::model()->findByPk($instructor_inepid_id);
            }
        } else if ($model == $this->InstructorTeachingData) {
            if (isset($instructor->inep_id) && !empty($instructor->inep_id)) { // VEr possível correção !!!!
                $return = InstructorTeachingData::model()->findAllByAttributes(['instructor_inep_id' => $instructor_inepid_id]);
            } else {
                $return = InstructorTeachingData::model()->find->findAllByAttributes(['instructor_fk' => $instructor_inepid_id]);
            }
        }

        if ($return === NULL && $model == $this->InstructorIdentification) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        if ($return === NULL && $model == $this->InstructorDocumentsAndAddress) {
            $return = InstructorDocumentsAndAddress::model()->findByPk($id);
        }

        if ($return === NULL && $model == $this->InstructorVariableData) {
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
            echo json_encode(["valid" => false, "error" => "No quadro de horário da turma, não existe dia letivo no mês selecionado para esta disciplina."]);
        }
    }

    public function actionGetFrequencyClassroom () 
    {
        $sql = "SELECT c.id, c.name FROM classroom c 
                JOIN instructor_teaching_data itd ON(c.id = itd.classroom_id_fk)
                WHERE itd.instructor_fk =". $_POST["instructor"] .";";

        $classrooms = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($classrooms as $classroom) {
            echo "<option value=".$classroom['id'].">".$classroom['name']."</option>";
        }
    }

    public function actionGetFrequencyDisciplines()
    {
        $sql = "SELECT DISTINCT ed.id, ed.name FROM instructor_disciplines id
                JOIN edcenso_discipline ed ON(id.discipline_fk = ed.id)
                JOIN classroom c ON(c.id = ".$_POST["classroom"].")
                WHERE id.instructor_fk = ".$_POST["instructor"].";";
        $disciplines = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($disciplines as $discipline) {
            echo "<option value=".$discipline['id'].">".$discipline['name']."</option>";
        }
    }

    public function actionsaveFrequency()
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

}
