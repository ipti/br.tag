<?php

//@done S2 - Modularizar o código do import
//@done S2 - Criar o controller de Import
//@done S2 - Mover o código do import de SchoolController.php para AdminController.php
//@done S2 - Mover o código do configACL de SchoolController.php para AdminController.php
//@done S2 - Criar método de limparBanco
//@done S2 - Criar tela de index do AdminController.php
//@done S2 - Criar usuários padrões.
//@done S2 - Mensagens de retorno ao executar os scripts.

class AdminController extends Controller {

    public $layout = 'fullmenu';

    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array( 'import', 'clearDB', 'acl', 'registerUser'),
                'users' => array('@'),
            ),
        );
    }

    public function actionIndex() {

        $this->render('index');
    }

    public function actionCreateUser() {
        $model = new Users;

        if (isset($_POST['Users'])) {
            $model->attributes = $_POST['Users'];
            if ($model->validate()) {
                // form inputs are valid, do something here
                return;
            }
        }
        $this->render('createUser', array('model' => $model));
    }

    public function addTestUsers() {
        set_time_limit(0);
        ignore_user_abort();
        $admin_login = 'admin';
        $admin_password = md5('admin');

        $command = "INSERT INTO `users`VALUES
                        (1, 'Administrador', '$admin_login', '$admin_password', 1);";
        Yii::app()->db->createCommand($command)->query();

        //Criar usuário de teste, remover depois.
        /*         * ************************************************************************************************ */
        /**/$command = "INSERT INTO `users`VALUES"
                /**/ . "(2, 'Paulo Roberto', 'paulones', 'e10adc3949ba59abbe56e057f20f883e', 1);"
                /**/ . "INSERT INTO `users_school` (`id`, `school_fk`, `user_fk`) VALUES (1, '28025911', 2);"
                /**/ . "INSERT INTO `users_school` (`id`, `school_fk`, `user_fk`) VALUES (2, '28025970', 2);"
                /**/ . "INSERT INTO `users_school` (`id`, `school_fk`, `user_fk`) VALUES (3, '28025989', 2);"
                /**/ . "INSERT INTO `users_school` (`id`, `school_fk`, `user_fk`) VALUES (4, '28026012', 2);";
        /**/Yii::app()->db->createCommand($command)->query();
        /*         * ************************************************************************************************ */
    }

    public function actionClearDB() {
        $command = "
            delete from AuthAssignment;
            delete from AuthItem;
            delete from AuthItemChild;
            
            delete from users;
            delete from users_school;

            delete from student_enrollment;
            delete from student_identification;
            delete from student_documents_and_address;

            delete from instructor_teaching_data;
            delete from instructor_identification;
            delete from instructor_documents_and_address;
            delete from instructor_variable_data;

            delete from classroom;

            delete from school_identification;
            delete from school_structure;";

        set_time_limit(0);
        ignore_user_abort();
        Yii::app()->db->createCommand($command)->query();

        $this->addTestUsers();

        Yii::app()->user->setFlash('success', Yii::t('default', 'Banco limpo com sucesso'));
        $this->redirect(array('index'));
    }

    public function actionImport($fileDir = null) {
        //Se não passar parametro, o valor será predefinido
        if (!isset($fileDir)) {
            $path = Yii::app()->basePath;
            $fileDir = $path . '/import/2013_98018493.TXT';
        }
        $mode = 'r';

        //Abre o arquivo
        $file = fopen($fileDir, $mode);
        if ($file == false) {
            die('O arquivo não existe.');
        }

        $registerLines = [];

        //Inicializa o contador de linhas
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
            //Próxima linha do arquivo
            $fileLine = fgets($file);
            if ($fileLine == null)
                break;

            //Tipo do registro são os 2 primeiros caracteres
            $regType = $fileLine[0] . $fileLine[1];
            //Querba a linha nos caracteres | 
            $lineFields_Aux = explode("|", $fileLine);
            $lineFields = [];

            //Troca os campos vazios por 'null'
            foreach ($lineFields_Aux as $key => $field) {
                $value = empty($field) ? 'null' : $field;
                $lineFields[$key] = $value;
            }

            //passa os campos do arquivo para a matriz [tipo][linha][coluna]
            $registerLines[$regType][$lineCount[$regType] ++] = $lineFields;
        }
        //Pega os valores para preencher o insert.
        $values = $this->getInsertValues($registerLines);
        $instructorInepIds = $values['instructor'];
        $insertValue = $values['insert'];

        //Pega o código SQL com os valores passados
        $str_fields = $this->getInsertSQL($insertValue);

        set_time_limit(0);
        ignore_user_abort();
        Yii::app()->db->createCommand($str_fields['00'])->query();
        Yii::app()->db->createCommand($str_fields['10'])->query();
        Yii::app()->db->createCommand($str_fields['20'])->query();
        Yii::app()->db->createCommand($str_fields['30'])->query();
        Yii::app()->db->createCommand($str_fields['40'])->query();
        Yii::app()->db->createCommand($str_fields['50'])->query();
        Yii::app()->db->createCommand($str_fields['51'])->query();
        Yii::app()->db->createCommand($str_fields['60'])->query();
        Yii::app()->db->createCommand($str_fields['80'])->query();
        Yii::app()->db->createCommand($str_fields['70'])->query();
        set_time_limit(30);
        fclose($file);

        Yii::app()->user->setFlash('success', Yii::t('default', 'Arquivo do Educacenso importado com sucesso.'));
        $this->redirect(array('index'));
    }
    
    public function actionRegisterUser(){
        Yii::app()->user->setFlash('success', Yii::t('default', 'Pegadinha do malandro! Rá!'));
        $this->redirect(array('index'));
    }

    public function actionACL() {
        $auth = Yii::app()->authManager;

        $auth->createOperation('createSchool', 'create a School');
        $auth->createOperation('updateSchool', 'update a School');
        $auth->createOperation('deleteSchool', 'delete a School');

        $auth->createOperation('createClassroom', 'create a classrrom');
        $auth->createOperation('updateClassroom', 'update a Classroom');
        $auth->createOperation('deleteClassroom', 'delete a Classroom');

        $auth->createOperation('createStudent', 'create a Student');
        $auth->createOperation('updateStudent', 'update a Student');
        $auth->createOperation('deleteStudent', 'delete a Student');

        $auth->createOperation('createInstructor', 'create a Instructor');
        $auth->createOperation('updateInstructor', 'update a Instructor');
        $auth->createOperation('deleteInstructor', 'delete a Instructor');

        $auth->createOperation('createEnrollment', 'create a Enrollment');
        $auth->createOperation('updateEnrollment', 'update a Enrollment');
        $auth->createOperation('deleteEnrollment', 'delete a Enrollment');


        $role = $auth->createRole('manager');
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


        $role = $auth->createRole('admin');
        $role->addChild('manager');
        $role->addChild('createSchool');
        $role->addChild('updateSchool');
        $role->addChild('deleteSchool');

        $auth->assign('admin', 1);
        $auth->assign('manager', 2);

        Yii::app()->user->setFlash('success', Yii::t('default', 'ACL configurada com sucesso.'));
        $this->redirect(array('index'));
    }

    //Retorna uma Array com 2 arrays. InsertValue e InstructorInepID
    private function getInsertValues($registerLines) {
        foreach ($registerLines as $regType => $lines):
            $insertValue[$regType] = "";

            $totalLines = count($lines) - 1;

            $isRegInstructorIdentification = ($regType == "30");
            if ($isRegInstructorIdentification) {
                $instructorInepIds[] = '';
            }
            for ($line = 0; $line <= $totalLines; $line++) {
                $totalColumns = count($lines[$line]) - 2;
                for ($column = 0; $column <= $totalColumns; $column++) {
                    $value = $lines[$line][$column];
                    $withoutcomma = false;

                    if ($column == 0) {
                        $insertValue[$regType].= "(";
                    } else if ($regType == '51' && $column == 3) {
                        $withoutcomma = true;
                        $value = "(SELECT id FROM instructor_identification WHERE BINARY inep_id = BINARY " . $lines[$line][2] . " LIMIT 0,1)";
                    } else if ($regType == '51' && $column == 5) {
                        $withoutcomma = true;
                        $value = "(SELECT id FROM classroom WHERE BINARY inep_id = BINARY " . $lines[$line][4] . " LIMIT 0,1)";
                    } else if ($regType == '80' && $column == 3) {
                        $withoutcomma = true;
                        $value = "(SELECT id FROM student_identification WHERE BINARY inep_id = BINARY " . $lines[$line][2] . " LIMIT 0,1)";
                    } else if ($regType == '80' && $column == 5) {
                        $withoutcomma = true;
                        $value = "(SELECT id FROM classroom WHERE BINARY inep_id = BINARY " . $lines[$line][4] . " LIMIT 0,1)";
                    }

                    if ($isRegInstructorIdentification && $column == 2) {
                        $instructorInepIds[$line] = $value;
                    }

                    if ($value == "GILLIANY DA SILVA LEITE") {
                        $lines[$line][sizeof($lines[$line])] = 'null';
                        $totalColumns++;
                    }


                    $value = ($value == 'null' || $withoutcomma) ? $value : "\"" . $value . "\"";

                    if ($column + 1 > $totalColumns) {
                        if ($regType == 20) {
                            $year = date("Y");
                            $value.= ',' . $year;
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
        $return = array('insert' => $insertValue, 'instructor' => $instructorInepIds);
        return $return;
    }

    private function getInsertSQL($insertValue) {
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
        return $str_fields;
    }

}

?>
