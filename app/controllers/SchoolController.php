<?php

class SchoolController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'fullmenu';
    private $SCHOOL_IDENTIFICATION = "SchoolIdentification";
    private $SCHOOL_STRUCTURE = "SchoolStructure";

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'create', 'update', 
                    'edcenso_import', 'configacl',
                    'getcities','getdistricts', 'getorgans', 'updateufdependencies'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    //@later S2 - Mover para o controle de Import
    public function actionEdcenso_import() {
        $selects = [];
        $selects['00'][0] = 'SELECT id from `private_school_maintainer` where (
        `business_or_individual` = "%value0%"
        and `syndicate_or_association` = "%value1%" 
        and `ong_or_oscip` = "%value2%"
        and `non_profit_institutions` = "%value3%"
        and `s_system` = "%value4%")';

        $inserts = [];
        $inserts['00'][0] = "INSERT INTO `private_school_maintainer` 
            (`business_or_individual`, `syndicate_or_association`, `ong_or_oscip`, `non_profit_institutions`,`s_system`) VALUES ";

        $path = Yii::app()->basePath;
        $filedir = $path.'/import/2013_98018493.TXT';
        $mode = 'r';

        $file = fopen($filedir, $mode);
        if ($file == false)
            die('O arquivo não existe.');

        $registerLines = [];

        $lineCount = [];
        $lineCount['00'] = 0;
        $lineCount['10'] = 0;
        $lineCount['20'] = 0;
        $lineCount['30'] = 0;
        $lineCount['40'] = 0;
        $lineCount['50'] = 0;
        $lineCount['51'] = 0;
        $lineCount['60'] = 0;
        $lineCount['70'] = 0;
        $lineCount['80'] = 0;

        //Pega campos do arquivo
        while (true) {
            $fileLine = fgets($file);
            if ($fileLine == null)
                break;
            $regType = $fileLine[0] . $fileLine[1];
            $lineFields_Aux = explode("|", $fileLine);
            $lineFields = [];
            foreach ($lineFields_Aux as $key => $field) {
                $value = empty($field) ? 'null' : $field;
                $lineFields[$key] = $value;
            }
            //passa os campos do arquivo para a matriz [tipo][linha][coluna]
            $registerLines[$regType][$lineCount[$regType]++] = $lineFields;
        }

        $insertValue = [];
        $preInserts = [];

        foreach ($registerLines as $regType => $lines):
            $insertValue[$regType] = "";
            $preInserts[$regType] = [];

            $totalLines = count($lines) - 1;
            
            $isRegInstructorIdentification = ($regType == "30");
            if ($isRegInstructorIdentification) {
                $instructorInepIds[] = '';
            }
            for ($line = 0; $line <= $totalLines; $line++) {
                $preInsertsTableIndex = 0;
                $preInserts[$regType][$preInsertsTableIndex] = "";
                $totalColumns = count($lines[$line]) - 2;
                for ($column = 0; $column <= $totalColumns; $column++) {
                    if ($column == 0) {
                        $insertValue[$regType].= "(";
                    }

                    $value = $lines[$line][$column];
                    
                    if ($isRegInstructorIdentification && $column == 2) {
                        $instructorInepIds[$line] = $value;
                    }
                    /* $return = [];
                      //retorna [0] column, [1] values, [2] Array Values
                      $return = $this->getPreInsertValues($regType, $column, $lines[$line]);
                      $column = $return[0];
                      if ($return[1] != NULL){


                      $value = "(".$this->prepareSelect($selects[$regType][$preInsertsTableIndex],$return[2]).")";

                      $id = Yii::app()->db->createCommand($value)->queryRow()['id'];
                      if($id != NULL){
                      $value = $id;
                      $preInsertsTableIndex++;
                      }else{
                      //inserir agora ou depois?
                      //se inserir agora não haverá duplicatas.
                      //
                      $sql = $inserts[$regType][$preInsertsTableIndex].$return[1].";";
                      //Yii::app()->db->createCommand($sql)->queryAll();

                      echo $sql;
                      //$preInserts[$regType][$preInsertsTableIndex++] .= $return[1].",";
                      }
                      }
                      else{ */

                    if ($value == "GILLIANY DA SILVA LEITE") {
                        $lines[$line][sizeof($lines[$line])] = 'null';
                        $totalColumns++;
                    }


                    $value = ($value == 'null') ? $value : "\"" . $value . "\"";
                    //}

                    if ($column + 1 > $totalColumns) {
                        if($regType == 20){
                            $year = date("Y");
                            $value.= ','.$year;
                        }
                        if ($line == ($totalLines)) {
                            $insertValue[$regType].= $value . ");";
                        } else {
                            $insertValue[$regType].= $value . "),\n";
                        }
                    } else {
                        $insertValue[$regType].= $value . ", ";
                    }
                }
            };
        endforeach;
        $str_fields = [];
        $teachingData = [];
        foreach ($insertValue as $regType => $lines):
            switch ($regType) {
                case '00': {
                        $str_fields[$regType] = "INSERT INTO school_identification VALUES " . $lines;
                        break;
                    }
                case '10': {
                        $str_fields[$regType] = "INSERT INTO school_structure VALUES " . $lines;
                        break;
                    }
                case '20': {
                        $str_fields[$regType] = "INSERT INTO classroom VALUES " . $lines;
                        break;
                    }
                case '30': {
                        $str_fields[$regType] = "INSERT INTO instructor_identification VALUES " . $lines;
                        break;
                    }
                case '40': {
                        $str_fields[$regType] = "INSERT INTO instructor_documents_and_address VALUES " . $lines;
                        break;
                    }
                case '50': {
                        $str_fields[$regType] = "INSERT INTO instructor_variable_data VALUES " . $lines;
                        break;
                    }
                    
                case '51': {
                        $str_fields[$regType] = "INSERT INTO `TAG_SGE`.`instructor_teaching_data`(`register_type`,`school_inep_id_fk`,`instructor_inep_id`,`instructor_fk`,`classroom_inep_id`,`classroom_id_fk`,`role`,`contract_type`,`discipline_1_fk`,`discipline_2_fk`,`discipline_3_fk`,`discipline_4_fk`,`discipline_5_fk`,`discipline_6_fk`,`discipline_7_fk`,`discipline_8_fk`,`discipline_9_fk`,`discipline_10_fk`,`discipline_11_fk`,`discipline_12_fk`,`discipline_13_fk`) VALUES " . $lines;
                        break;
                    }
                case '60': {
                        $str_fields[$regType] = "INSERT INTO student_identification VALUES " . $lines;
                        break;
                    }
                case '70': {
                        $str_fields[$regType] = "INSERT INTO student_documents_and_address VALUES " . $lines;
                        break;
                    }
                case '80': {
                        $str_fields[$regType] = "INSERT INTO student_enrollment (`register_type`,`school_inep_id_fk`,`student_inep_id`,`student_fk`,`classroom_inep_id`,`classroom_fk`,`enrollment_id`,`unified_class`,`edcenso_stage_vs_modality_fk`,`another_scholarization_place`,`public_transport`,`transport_responsable_government`,`vehicle_type_van`,`vehicle_type_microbus`,`vehicle_type_bus`,`vehicle_type_bike`,`vehicle_type_animal_vehicle`,`vehicle_type_other_vehicle`,`vehicle_type_waterway_boat_5`,`vehicle_type_waterway_boat_5_15`,`vehicle_type_waterway_boat_15_35`,`vehicle_type_waterway_boat_35`,`vehicle_type_metro_or_train`,`student_entry_form`) VALUES " . $lines;
                        break;
                    }
            }
        endforeach;
        set_time_limit(0);
        echo "done?<br>";
        Yii::app()->db->createCommand($str_fields['00'])->query();
        echo "done 00<br>";
        Yii::app()->db->createCommand($str_fields['10'])->query();
        echo "done 10<br>";
        Yii::app()->db->createCommand($str_fields['20'])->query();
        echo "done 20<br>";
        Yii::app()->db->createCommand($str_fields['30'])->query();
//        //=====================
        $instructorId_inepId = [];
        foreach($instructorInepIds as $inepId):
          $instructorId_inepId[$inepId] =  Yii::app()->db->createCommand("SELECT id FROM TAG_SGE.instructor_identification WHERE inep_id =". $inepId .";")->queryAll();
        endforeach;
//        //=====================
        echo "done 30<br>";
        Yii::app()->db->createCommand($str_fields['40'])->query();
        echo "done 40<br>";
        Yii::app()->db->createCommand($str_fields['50'])->query();
        echo "done 50<br>";
        Yii::app()->db->createCommand($str_fields['51'])->query();
        echo "done 51<br>";
//          //===============

          foreach ($instructorId_inepId as $inepId=>$id):
              $comando = "UPDATE TAG_SGE.instructor_teaching_data SET instructor_fk =".$id[0]['id']." WHERE instructor_inep_id =".$inepId. ";";
              Yii::app()->db->createCommand($comando)->query();
          endforeach;
//        //===============
        Yii::app()->db->createCommand($str_fields['60'])->query();
        echo "done 60<br>";
        Yii::app()->db->createCommand($str_fields['70'])->query();
        echo "done 70<br>";
        Yii::app()->db->createCommand($str_fields['80'])->query();
        echo "done 80<br>";
        echo "done!<br>";
        set_time_limit(30);
        fclose($file);
    }
 
    
    
    public function actionConfigACL(){
        $auth=Yii::app()->authManager;
        
        $auth->createOperation('createSchool','create a School');
        $auth->createOperation('updateSchool','update a School');
        $auth->createOperation('deleteSchool','delete a School');
        
        $auth->createOperation('createClassroom','create a classrrom');
        $auth->createOperation('updateClassroom','update a Classroom');
        $auth->createOperation('deleteClassroom','delete a Classroom');
        
        $auth->createOperation('createStudent','create a Student');
        $auth->createOperation('updateStudent','update a Student');
        $auth->createOperation('deleteStudent','delete a Student');
        
        $auth->createOperation('createInstructor','create a Instructor');
        $auth->createOperation('updateInstructor','update a Instructor');
        $auth->createOperation('deleteInstructor','delete a Instructor');
        
        $auth->createOperation('createEnrollment','create a Enrollment');
        $auth->createOperation('updateEnrollment','update a Enrollment');
        $auth->createOperation('deleteEnrollment','delete a Enrollment');

        
        $role=$auth->createRole('manager');
            $role->addChild('createClassroom');
            $role->addChild('updateClassroom');
            $role->addChild('deleteClassroom');

            $role->addChild('createStudent');
            $role->addChild('updateStudent');
            $role->addChild('deleteStudent');

            $role->addChild('createInstructor');
            $role->addChild('updateInstructor');
            $role->addChild('deleteInstructor');

            $role->addChild('createEnrollment');
            $role->addChild('updateEnrollment');
            $role->addChild('deleteEnrollment');
        
        
        $role=$auth->createRole('admin');
            $role->addChild('manager');
            $role->addChild('createSchool');
            $role->addChild('updateSchool');
            $role->addChild('deleteSchool');
        
        
        $auth->assign('manager',1);
        $auth->assign('admin',2);
        
        echo Yii::app()->user->loginInfos->name."<br>";
        $userId = Yii::app()->user->loginInfos->id;
        var_dump(Yii::app()->getAuthManager()->checkAccess('createSchool',$userId));
        var_dump(Yii::app()->getAuthManager()->checkAccess('createStudent',$userId));
        
    }
    
    //@done s1 - Funcionalidade de atualização dos Distritos e dos Órgãos Regionáis de Educação
    public function actionUpdateUfDependencies(){
        $school = new SchoolIdentification();
        $school->attributes = $_POST[$this->SCHOOL_IDENTIFICATION];
        
        $uf = $school->edcenso_uf_fk;
        
        $result = array('Organ'=>'', 'City'=>'');
        $result['City'] = $this->actionGetCities($uf);
        $result['Organ'] = $this->actionGetOrgans($uf);

        echo json_encode($result);
    }
    
    public function actionGetCities($Uf = null) {
        if(isset($_POST[$this->SCHOOL_IDENTIFICATION])){
            $school = new SchoolIdentification();
            $school->attributes = $_POST[$this->SCHOOL_IDENTIFICATION];
        }
        $uf = $Uf == null ? $school->edcenso_uf_fk : $Uf;

        $data = EdcensoCity::model()->findAll('edcenso_uf_fk=:uf_id', array(':uf_id' => (int) $school->edcenso_uf_fk));
        $data = CHtml::listData($data, 'id', 'name');

        $result =  CHtml::tag('option', array('value' => null), 'Selecione a cidade', true);
        foreach ($data as $value => $name) {
            $result .= CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }

        return $result;   
    }

    //@done s1 - Modificar a tabela EdcensoRegionalEducationOrgan para acidionar o campo de estado
    public function actionGetOrgans($Uf = null) {
        if(isset($_POST[$this->SCHOOL_IDENTIFICATION])){
            $school = new SchoolIdentification();
            $school->attributes = $_POST[$this->SCHOOL_IDENTIFICATION];
        }
        $uf = $Uf == null ? $school->edcenso_uf_fk : $Uf;
        
        $data = EdcensoRegionalEducationOrgan::model()->findAll('edcenso_uf_fk=:uf_id', array(':uf_id' => $uf));
        $data = CHtml::listData($data, 'code', 'name');

        $result = CHtml::tag('option', array('value' => null),CHtml::encode('Selecione o órgão'), true);
        foreach ($data as $value => $name) {
            $result .= CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }

        return $result;
    }
    
    public function actionGetDistricts() {
        $school = new SchoolIdentification();
        $school->attributes = $_POST[$this->SCHOOL_IDENTIFICATION];

        $data = EdcensoDistrict::model()->findAll('edcenso_city_fk=:city_id', array(':city_id' => $school->edcenso_city_fk ));
        $data = CHtml::listData($data, 'code', 'name');

        $result = CHtml::tag('option', array('value' => null), 'Selecione o distrito', true);

        foreach ($data as $value => $name) {
            $result .= CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
        
        echo $result;
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'modelSchoolIdentification' => $this->loadModel($id, $this->SCHOOL_IDENTIFICATION),
            'modelSchoolStructure' => $this->loadModel($id, $this->SCHOOL_STRUCTURE),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $modelSchoolIdentification = new SchoolIdentification;
        $modelSchoolStructure = new SchoolStructure;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($modelSchoolIdentification);

        if (isset($_POST[$this->SCHOOL_IDENTIFICATION]) && isset($_POST[$this->SCHOOL_STRUCTURE])) {
            if (isset($_POST[$this->SCHOOL_STRUCTURE]["shared_school_inep_id_1"])) {
                $sharedSchools = $_POST[$this->SCHOOL_STRUCTURE]["shared_school_inep_id_1"];
            }
            $_POST[$this->SCHOOL_STRUCTURE]["shared_school_inep_id_1"] =
                    isset($sharedSchools[0]) ? $sharedSchools[0] : null;
            $_POST[$this->SCHOOL_STRUCTURE]["shared_school_inep_id_2"] =
                    isset($sharedSchools[1]) ? $sharedSchools[1] : null;
            $_POST[$this->SCHOOL_STRUCTURE]["shared_school_inep_id_3"] =
                    isset($sharedSchools[2]) ? $sharedSchools[2] : null;
            $_POST[$this->SCHOOL_STRUCTURE]["shared_school_inep_id_4"] =
                    isset($sharedSchools[3]) ? $sharedSchools[3] : null;
            $_POST[$this->SCHOOL_STRUCTURE]["shared_school_inep_id_5"] =
                    isset($sharedSchools[4]) ? $sharedSchools[4] : null;
            $_POST[$this->SCHOOL_STRUCTURE]["shared_school_inep_id_6"] =
                    isset($sharedSchools[5]) ? $sharedSchools[5] : null;

            $modelSchoolIdentification->attributes = $_POST[$this->SCHOOL_IDENTIFICATION];
            $modelSchoolStructure->attributes = $_POST[$this->SCHOOL_STRUCTURE];

            $modelSchoolStructure->school_inep_id_fk = $modelSchoolIdentification->inep_id;

            if ($modelSchoolIdentification->validate() && $modelSchoolStructure->validate()) {
                if ($modelSchoolStructure->operation_location_building
                        || $modelSchoolStructure->operation_location_temple
                        || $modelSchoolStructure->operation_location_businness_room
                        || $modelSchoolStructure->operation_location_instructor_house
                        || $modelSchoolStructure->operation_location_other_school_room
                        || $modelSchoolStructure->operation_location_barracks
                        || $modelSchoolStructure->operation_location_socioeducative_unity
                        || $modelSchoolStructure->operation_location_prison_unity
                        || $modelSchoolStructure->operation_location_other) {
                    if ($modelSchoolIdentification->save() && $modelSchoolStructure->save()) {
                        Yii::app()->user->setFlash('success', Yii::t('default', 'Escola adicionada com sucesso!'));
                        $this->redirect(array('index'));
                    }
                } else {
                    $modelSchoolStructure->addError('operation_location_building', Yii::t('default', 'Operation Location') . ' ' . Yii::t('default', 'cannot be blank'));
                }
            }
        }

        $this->render('create', array(
            'modelSchoolIdentification' => $modelSchoolIdentification,
            'modelSchoolStructure' => $modelSchoolStructure
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $modelSchoolIdentification = $this->loadModel($id, $this->SCHOOL_IDENTIFICATION);
        $modelSchoolStructure = $this->loadModel($id, $this->SCHOOL_STRUCTURE);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($modelSchoolIdentification);

        if (isset($_POST[$this->SCHOOL_IDENTIFICATION]) && isset($_POST[$this->SCHOOL_STRUCTURE])) {

            if (isset($_POST[$this->SCHOOL_STRUCTURE]["shared_school_inep_id_1"])) {
                $sharedSchools = $_POST[$this->SCHOOL_STRUCTURE]["shared_school_inep_id_1"];
            }
            $_POST[$this->SCHOOL_STRUCTURE]["shared_school_inep_id_1"] =
                    isset($sharedSchools[0]) ? $sharedSchools[0] : null;
            $_POST[$this->SCHOOL_STRUCTURE]["shared_school_inep_id_2"] =
                    isset($sharedSchools[1]) ? $sharedSchools[1] : null;
            $_POST[$this->SCHOOL_STRUCTURE]["shared_school_inep_id_3"] =
                    isset($sharedSchools[2]) ? $sharedSchools[2] : null;
            $_POST[$this->SCHOOL_STRUCTURE]["shared_school_inep_id_4"] =
                    isset($sharedSchools[3]) ? $sharedSchools[3] : null;
            $_POST[$this->SCHOOL_STRUCTURE]["shared_school_inep_id_5"] =
                    isset($sharedSchools[4]) ? $sharedSchools[4] : null;
            $_POST[$this->SCHOOL_STRUCTURE]["shared_school_inep_id_6"] =
                    isset($sharedSchools[5]) ? $sharedSchools[5] : null;

            $modelSchoolIdentification->attributes = $_POST[$this->SCHOOL_IDENTIFICATION];
            $modelSchoolStructure->attributes = $_POST[$this->SCHOOL_STRUCTURE];

            $modelSchoolStructure->school_inep_id_fk = $modelSchoolIdentification->inep_id;

            if ($modelSchoolIdentification->validate() && $modelSchoolStructure->validate()) {
                if ($modelSchoolStructure->operation_location_building
                        || $modelSchoolStructure->operation_location_temple
                        || $modelSchoolStructure->operation_location_businness_room
                        || $modelSchoolStructure->operation_location_instructor_house
                        || $modelSchoolStructure->operation_location_other_school_room
                        || $modelSchoolStructure->operation_location_barracks
                        || $modelSchoolStructure->operation_location_socioeducative_unity
                        || $modelSchoolStructure->operation_location_prison_unity
                        || $modelSchoolStructure->operation_location_other) {
                    if ($modelSchoolIdentification->save() && $modelSchoolStructure->save()) {
                        Yii::app()->user->setFlash('success', Yii::t('default', 'Escola alterada com sucesso!'));
                        $this->redirect(array('index'));
                    }
                } else {
                    $modelSchoolStructure->addError('operation_location_building', Yii::t('default', 'Operation Location') . ' ' . Yii::t('default', 'cannot be blank'));
                }
            }
        }

        $this->render('update', array(
            'modelSchoolIdentification' => $modelSchoolIdentification,
            'modelSchoolStructure' => $modelSchoolStructure,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if ($this->loadModel($id, $this->SCHOOL_STRUCTURE)->delete()
                && $this->loadModel($id, $this->SCHOOL_IDENTIFICATION)->delete()) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Escola excluída com sucesso!'));
            $this->redirect(array('index'));
        } else {
            throw new CHttpException(404, 'A página requisitada não existe.');
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $filter = new SchoolIdentification('search');
        $filter->unsetAttributes();  // clear any default values
        if (isset($_GET['SchoolIdentification'])) {
            $filter->attributes = $_GET['SchoolIdentification'];
        }
        $dataProvider = new CActiveDataProvider($this->SCHOOL_IDENTIFICATION,
                        array('pagination' => array(
                                'pageSize' => 12,
                        )));
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'filter' => $filter
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $modelSchoolIdentification = new SchoolIdentification('search');
        $modelSchoolIdentification->unsetAttributes();  // clear any default values
        $modelSchoolStructure = new SchoolStructure('search');
        $modelSchoolStructure->unsetAttributes();  // clear any default values

        if (isset($_GET[$this->SCHOOL_IDENTIFICATION]) && isset($_GET[$this->SCHOOL_STRUCTURE])) {
            $modelSchoolIdentification->attributes = $_GET[$this->SCHOOL_IDENTIFICATION];
            $modelSchoolStructure->attributes = $_GET[$this->SCHOOL_STRUCTURE];
        }

        $this->render('admin', array(
            'modelSchoolIdentification' => $modelSchoolIdentification,
            'modelSchoolStructure' => $modelSchoolStructure,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id, $model) {

        $return = null;

        if ($model == $this->SCHOOL_IDENTIFICATION) {
            $return = SchoolIdentification::model()->findByPk($id);
        } else if ($model == $this->SCHOOL_STRUCTURE) {
            $return = SchoolStructure::model()->findByPk($id);
        }

        if ($return === null)
            throw new CHttpException(404, 'A página requisitada não existe.');
        return $return;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'school') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
