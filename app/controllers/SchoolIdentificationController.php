<?php

class SchoolIdentificationController extends Controller {

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
                'actions' => array('index', 'view', 'create', 'update', 'edcenso_import', 'getcities', 'getdistricts'),
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


        $filedir = '/home/ipti009/Área de Trabalho/2013_98018493.TXT';
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
            for ($line = 0; $line <= $totalLines; $line++) {
                $preInsertsTableIndex = 0;
                $preInserts[$regType][$preInsertsTableIndex] = "";
                $totalColumns = count($lines[$line]) - 2;
                for ($column = 0; $column <= $totalColumns; $column++) {
                    if ($column == 0) {
                        $insertValue[$regType].= "(";
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
                    $value = $lines[$line][$column];
                    if ($value == "GILLIANY DA SILVA LEITE") {
                        $lines[$line][sizeof($lines[$line])] = 'null';
                        $totalColumns++;
                    }
                    $value = ($value == 'null') ? $value : "\"" . $value . "\"";
                    //}


                    if ($column + 1 > $totalColumns) {
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
                        $str_fields[$regType] = "INSERT INTO instructor_teaching_data VALUES " . $lines;
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
        echo "done 30<br>";
        Yii::app()->db->createCommand($str_fields['40'])->query();
        echo "done 40<br>";
        Yii::app()->db->createCommand($str_fields['50'])->query();
        echo "done 50<br>";
        Yii::app()->db->createCommand($str_fields['51'])->query();
        echo "done 51<br>";
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

    public function actionGetCities() {
        $school = new SchoolIdentification();
        $school->attributes = $_POST[$this->SCHOOL_IDENTIFICATION];

        $data = EdcensoCity::model()->findAll('edcenso_uf_fk=:uf_id', array(':uf_id' => (int) $school->edcenso_uf_fk));
        $data = CHtml::listData($data, 'id', 'name');

        echo CHtml::tag('option', array('value' => 'NULL'), '(Select a city)', true);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    public function actionGetDistricts() {
        $school = new SchoolIdentification();
        $school->attributes = $_POST[$this->SCHOOL_IDENTIFICATION];

        $data = EdcensoDistrict::model()->findAll('edcenso_city_fk=:city_id', array(':city_id' => $school->edcenso_city_fk));
        $data = CHtml::listData($data, 'code', 'name');

        echo CHtml::tag('option', array('value' => 'NULL'), '(Select a district)', true);

        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
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
            $modelSchoolIdentification->attributes = $_POST[$this->SCHOOL_IDENTIFICATION];
            $modelSchoolStructure->attributes = $_POST[$this->SCHOOL_STRUCTURE];
            if ($modelSchoolIdentification->validate() && $modelSchoolStructure->validate()) {
                $modelSchoolStructure->school_inep_id_fk = $modelSchoolIdentification->inep_id;
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
                        Yii::app()->user->setFlash('success', Yii::t('default', 'School Created Successful:'));
                        $this->redirect(array('index'));
                    }
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
            $modelSchoolIdentification->attributes = $_POST[$this->SCHOOL_IDENTIFICATION];
            $modelSchoolStructure->attributes = $_POST[$this->SCHOOL_STRUCTURE];
            if ($modelSchoolIdentification->validate() && $modelSchoolStructure->validate()) {
                $modelSchoolStructure->school_inep_id_fk = $modelSchoolIdentification->inep_id;
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
                        $this->redirect(array('view', 'id' => $modelSchoolIdentification->inep_id));
                    }
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
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id, $this->SCHOOL_STRUCTURE)->delete();
            $this->loadModel($id, $this->SCHOOL_IDENTIFICATION)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider($this->SCHOOL_IDENTIFICATION,
                        array('pagination' => array(
                                'pageSize' => 12,
                        )));
        $this->render('index', array(
            'dataProvider' => $dataProvider,
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
        }else if ($model == $this->SCHOOL_STRUCTURE) {
            $return = SchoolStructure::model()->findByPk($id);
        }

        if ($return === null)
            throw new CHttpException(404, 'The requested page does not exist.');
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
