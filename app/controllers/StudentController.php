<?php

class StudentController extends Controller
{
    //@done s1 - validação de todos os campos - Colocar uma ? para explicar as regras de cada campo(em todas as telas)
    //@done s1 - Recuperar endereço pelo CEP
    //@done s1 - validar CPF
    //@done s1 - Campo Cartórios - Colocar em ordem alfabética
    //@done s1 - Campo TIpo de Certidão Civil (Add as opções)
    //@done s1 - atualizar dependencia de select2
    //@done s1 - corrigir o deletar

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'fullmenu';
    private $STUDENT_IDENTIFICATION = 'StudentIdentification';
    private $STUDENT_DOCUMENTS_AND_ADDRESS = 'StudentDocumentsAndAddress';
    private $STUDENT_ENROLLMENT = 'StudentEnrollment';

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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'comparestudentname', 'comparestudentcpf', 'comparestudentcertificate', 'create', 'update', 'getcities', 'getnotaryoffice', 'getnations', 'delete'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
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
        } else if ($register_type == 1) {
            $student = new StudentDocumentsAndAddress();
            $student->attributes = $_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS];
            $uf = (int)$student->notary_office_uf_fk;
        } else if ($register_type == 2) {
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

    public function actionGetNotaryOffice()
    {
        $student = new StudentDocumentsAndAddress();
        $student->attributes = $_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS];

        $data = EdcensoNotaryOffice::model()->findAllByAttributes(array('city' => (int)$student->notary_office_city_fk), array('order' => 'name'));
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

    public function actionCompareStudentName() { 
        $data = StudentIdentification::model()->findAll();
        $result = [];
        foreach ($data as $student) {
            $result[$student->name] = $student->id;
        }
        echo json_encode($result);
    }

    public function actionCompareStudentCertificate($student_certificate) {
        $data = StudentDocumentsAndAddress::model()->find('civil_certification_term_number=:civil_certification_term_number', array(':civil_certification_term_number' => $student_certificate));
        $result = [];
        $result[$data->student_fk] = $data->id;

        echo json_encode($result);
    }

    public function actionCompareStudentCpf($student_responsable_cpf) {
        $data = StudentIdentification::model()->find('responsable_cpf=:responsable_cpf', array(':responsable_cpf' => $student_responsable_cpf));
        $result = [];
        $result[$data->name] = $data->id;

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

        $vaccines = Vaccine::model()->findAll(array('order' => 'name'));
        $studentVaccinesSaves = StudentVaccine::model()->findAll(['select' => 'vaccine_id', 'condition' => 'student_id=:student_id', 'params' => [':student_id' => $modelStudentIdentification->id]]);
        if ($studentVaccinesSaves) {
            $studentVaccinesSaves = array_map(function ($item) {
                return $item->vaccine_id;
            }, $studentVaccinesSaves);
        }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model
        if (isset($_POST[$this->STUDENT_IDENTIFICATION]) && isset($_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS])) {
            $modelStudentIdentification->attributes = $_POST[$this->STUDENT_IDENTIFICATION];
            $modelStudentDocumentsAndAddress->attributes = $_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS];

            // Validação CPF->Certidão->Nome
            if($modelStudentIdentification->responsable_cpf != null) {
                $student_test_cpf = StudentIdentification::model()->find('responsable_cpf=:responsable_cpf', array(':responsable_cpf'=>$modelStudentIdentification->responsable_cpf));
                if(isset($student_test_cpf)) {
                    Yii::app()->user->setFlash('error', Yii::t('default', "O CPF do responsável informado já está cadastrado"));
                    $this->redirect(array('index'));
                }
            }
            if($modelStudentDocumentsAndAddress->civil_certification_term_number != null) {
                $student_test_certificate = StudentIdentification::model()->find('civil_certification_term_number=:civil_certification_term_number', array(':civil_certification_term_number'=>$modelStudentDocumentsAndAddress->civil_certification_term_number));
                if(isset($student_test_certificate)) {
                    Yii::app()->user->setFlash('error', Yii::t('default', "O Nº do Termo da Certidão informado já está cadastrado"));
                    $this->redirect(array('index'));
                }
            }
            $student_test_name = StudentIdentification::model()->find('name=:name', array(':name'=>$modelStudentIdentification->name));
            if(isset($student_test_name)) {
                Yii::app()->user->setFlash('error', Yii::t('default', "O nome do aluno informado já está cadastrado"));
                $this->redirect(array('index'));
            }

            //Atributos comuns entre as tabelas
            $modelStudentDocumentsAndAddress->school_inep_id_fk = $modelStudentIdentification->school_inep_id_fk;
            $modelStudentDocumentsAndAddress->student_fk = $modelStudentIdentification->inep_id;
            date_default_timezone_set("America/Recife");
            $modelStudentIdentification->last_change = date('Y-m-d G:i:s');

            if ($modelStudentIdentification->validate() && $modelStudentDocumentsAndAddress->validate()) {
                if ($modelStudentIdentification->save()) {
                    $modelStudentDocumentsAndAddress->id = $modelStudentIdentification->id;

                    if ($modelStudentDocumentsAndAddress->validate()) {
                        if ($modelStudentDocumentsAndAddress->save()) {
                            $saved = true;
                            if (isset($_POST[$this->STUDENT_ENROLLMENT], $_POST[$this->STUDENT_ENROLLMENT]["classroom_fk"])
                                && !empty($_POST[$this->STUDENT_ENROLLMENT]["classroom_fk"])) {
                                $modelEnrollment = new StudentEnrollment;
                                $modelEnrollment->attributes = $_POST[$this->STUDENT_ENROLLMENT];
                                $modelEnrollment->school_inep_id_fk = $modelStudentIdentification->school_inep_id_fk;
                                $modelEnrollment->student_fk = $modelStudentIdentification->id;
                                $saved = false;
                                if ($modelEnrollment->validate()) {
                                    $saved = $modelEnrollment->save();
                                }
                                //$modelEnrollment = $this->loadModel($id, $this->STUDENT_ENROLLMENT);
                            }

                            if(isset($_POST['Vaccine']['vaccine_id'])) {
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
                                Log::model()->saveAction("student", $modelStudentIdentification->id, "C", $modelStudentIdentification->name);
                                $msg = 'O Cadastro de ' . $modelStudentIdentification->name . ' foi criado com sucesso!';

                                Yii::app()->user->setFlash('success', Yii::t('default', $msg));

                                $this->redirect(array('index', 'sid' => $modelStudentIdentification->id));
                            }
                        }
                    }
                }
            }
        }
        //$modelEnrollment = array();
        //array_push($modelEnrollment,  new StudentEnrollment);

        $this->render('create', array(
            'modelStudentIdentification' => $modelStudentIdentification,
            'modelStudentDocumentsAndAddress' => $modelStudentDocumentsAndAddress,
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

        $vaccines = Vaccine::model()->findAll(array('order' => 'name'));
        $studentVaccinesSaves = StudentVaccine::model()->findAll(['select' => 'vaccine_id', 'condition' => 'student_id=:student_id', 'params' => [':student_id' => $id]]);
        if ($studentVaccinesSaves) {
            $studentVaccinesSaves = array_map(function ($item) {
                return $item->vaccine_id;
            }, $studentVaccinesSaves);
        }
        //$modelEnrollment = $this->loadModel($id, $this->STUDENT_ENROLLMENT);
        $modelEnrollment = new StudentEnrollment();
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($modelStudentIdentification);
        //$modelEnrollment = NULL;

        if (isset($_POST[$this->STUDENT_IDENTIFICATION]) && isset($_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS])) {
            $modelStudentIdentification->attributes = $_POST[$this->STUDENT_IDENTIFICATION];
            $modelStudentDocumentsAndAddress->attributes = $_POST[$this->STUDENT_DOCUMENTS_AND_ADDRESS];
            //Atributos comuns entre as tabelas
            $modelStudentDocumentsAndAddress->school_inep_id_fk = $modelStudentIdentification->school_inep_id_fk;
            $modelStudentDocumentsAndAddress->student_fk = $modelStudentIdentification->inep_id;
            date_default_timezone_set("America/Recife");
            $modelStudentIdentification->last_change = date('Y-m-d G:i:s');

            if ($modelStudentIdentification->validate() && $modelStudentDocumentsAndAddress->validate()) {
                if ($modelStudentIdentification->save()) {
                    if ($modelStudentDocumentsAndAddress->save()) {
                        $saved = true;
                        if (isset($_POST[$this->STUDENT_ENROLLMENT], $_POST[$this->STUDENT_ENROLLMENT]["classroom_fk"])
                            && !empty($_POST[$this->STUDENT_ENROLLMENT]["classroom_fk"])) {
                            $modelEnrollment = new StudentEnrollment;
                            $modelEnrollment->attributes = $_POST[$this->STUDENT_ENROLLMENT];
                            $modelEnrollment->school_inep_id_fk = $modelStudentIdentification->school_inep_id_fk;
                            $modelEnrollment->student_fk = $modelStudentIdentification->id;
                            $modelEnrollment->student_inep_id = $modelStudentIdentification->inep_id;
                            $saved = false;
                            if ($modelEnrollment->validate()) {
                                $saved = $modelEnrollment->save();
                            }

                            //$modelEnrollment = $this->loadModel($id, $this->STUDENT_ENROLLMENT);
                        }

                        if(isset($_POST['Vaccine']['vaccine_id'])) {
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
                            Log::model()->saveAction("student", $modelStudentIdentification->id, "U", $modelStudentIdentification->name);
                            $msg = 'O Cadastro de ' . $modelStudentIdentification->name . ' foi alterado com sucesso!';
                            Yii::app()->user->setFlash('success', Yii::t('default', $msg));
                            $this->redirect(array('index', 'sid' => $modelStudentIdentification->id));
                        }
                    }
                }
            }
        }
        //$modelEnrollment = $modelEnrollment[0];
        $this->render('update', array(
            'modelStudentIdentification' => $modelStudentIdentification,
            'modelStudentDocumentsAndAddress' => $modelStudentDocumentsAndAddress,
            'modelEnrollment' => $modelEnrollment,
            'vaccines' => $vaccines,
            'studentVaccinesSaves' => $studentVaccinesSaves
        ));
    }


    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $enrollment = $this->loadModel($id, $this->STUDENT_ENROLLMENT);
        $delete = true;
        foreach ($enrollment as $e) {
            $delete = $delete && $e->delete();
        }

        if ($delete
            && $this->loadModel($id, $this->STUDENT_DOCUMENTS_AND_ADDRESS)->delete()
            && $this->loadModel($id, $this->STUDENT_IDENTIFICATION)->delete()) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Aluno excluído com sucesso!'));
            $this->redirect(array('index'));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }


    /**
     * Lists all models.
     */
    public function actionIndex($sid = null, $mer_id = null)
    {
        $filter = new StudentIdentification('search');
        $filter->unsetAttributes();  // clear any default values
        if (isset($_GET['StudentIdentification'])) {
            $_GET['StudentIdentification']['name'] = $this->removeWhiteSpace($_GET['StudentIdentification']['name']);
            $filter->attributes = $_GET['StudentIdentification'];
        }
        $school = Yii::app()->user->school;
        $dataProvider = new CActiveDataProvider($this->STUDENT_IDENTIFICATION,
            array(
                'criteria' => array(
                    'condition' => 'school_inep_id_fk=' . $school,
                ),
                'pagination' => array(
                    'pageSize' => 12,
                )));
        $buttons = "";
        if ($sid != null) {
            $student = $this->loadModel($sid, $this->STUDENT_IDENTIFICATION);
            if (isset($student->studentEnrollments[0]->id)) {
                $mer_id = $student->studentEnrollments[0]->id;
                @$stage = $student->studentEnrollments[0]->classroomFk->edcensoStageVsModalityFk->stage;
                if ($stage == 1) {
                    $type = 0;
                } else if ($stage == 6) {
                    $type = 3;
                } else {
                    $type = 1;
                }
                $buttons = CHtml::tag('a',
                    array('href' => yii::app()->createUrl('student/update', array('id' => $sid)),
                        'class' => "btn btn-primary btn-icon glyphicons eye_open",
                        'style' => 'margin-top: 5px; width: 110px'), '<i></i>Visualizar aluno');
                $buttons .= "<br>";

                $buttons .= CHtml::tag('a',
                    array('target' => '_blank', 'href' => yii::app()->createUrl('/forms/StudentFileForm', array('type' => $type, 'enrollment_id' => $mer_id)),
                        'class' => "btn btn-primary btn-icon glyphicons notes_2",
                        'style' => 'margin-top: 5px; width: 110px'), '<i></i>Ficha individual');
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
        } else if ($model == $this->STUDENT_DOCUMENTS_AND_ADDRESS) {
            $student_inep_id = StudentIdentification::model()->findByPk($id)->inep_id;
            $return = StudentDocumentsAndAddress::model()->findByAttributes(array('id' => $id));
            //mudança agora só busca pelo pk, não mais pelo inep_id
            /*$return = ($student_inep_id === 'null' || empty($student_inep_id))
                    ? StudentDocumentsAndAddress::model()->findByPk($id)
                    : StudentDocumentsAndAddress::model()->findByAttributes(array('student_fk' => $student_inep_id));*/
        } else if ($model == $this->STUDENT_ENROLLMENT) {
            $return = StudentEnrollment::model()->findAllByAttributes(array('student_fk' => $id));
            array_push($return, new StudentEnrollment);
        }
        if ($return === null) {
            //throw new CHttpException(404, 'The requested page does not exist.');
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
