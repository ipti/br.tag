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
                'actions' => array( 'CreateUser','index'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('import', 'export',
                                    'clearDB', 'acl',
                                    'backup','data',
                                    'exportStudentIdentify'),
                'users' => array('@'),
            ),
        );
    }
    /**
     * Show the Index Page.
     */
    public function actionIndex() {
        $this->render('index');
    }
    
    /**
     * Update de database.
     * 
     * @return {Redirect} Return the index page with a FlashMessage
     * 
     */
    public function actionUpdateDB(){
        set_time_limit(0);
        ignore_user_abort();
        
        $updateDir = Yii::app()->basePath . '/../updates/';
        
        $dirFiles = scandir($updateDir);
        
        Yii::import('ext.FileManager.fileManager');
        $fm = new fileManager();
        
        $file = $fm->open($updateDir . '_version');
        $version = fgets($file);
        $fm->closeAll();
        
        $count = 0;
        
        foreach ($dirFiles as $fileName){
            
            if($fileName != '.' 
                && $fileName != '..' 
                && $fileName != 'readme' 
                && $fileName != '_version'
                && substr("abcdef", -1) != '~' ){
                
                if($version != "" &&  $version < $fileName){
                    $file = $fm->open($updateDir . $fileName);
                    $sql = "";
                    while (true) {
                        $fileLine = fgets($file);
                        $sql .= $fileLine;
                        if ($fileLine == null) break;
                    }
                    
                    $result = Yii::app()->db->createCommand($sql)->query();

                    if ($result) {
                        $file = $fm->write($updateDir . '_version', $fileName);
                        Yii::app()->user->setFlash('success', Yii::t('default', 'Atualização Concluída!'));
                        $count++;
                    } else {
                        Yii::app()->user->setFlash('error', Yii::t('default', 'Erro ao atualizar!'));
                    }
                }
            }
        }
        if($count == 0){
            Yii::app()->user->setFlash('notice', Yii::t('default', 'Não há atualizações!'));
        }
        $fm->closeAll();
        $this->render('index');
    }
    
    /**
     * Generate the BackupFile.
     * @param boolean $return - Defaults True
     * @return redirecrToIndex|boolean Return to the index page with a FlashMenssage or return $boolean
     */
    public static function actionBackup($return = TRUE) {
        Yii::import('ext.dumpDB.dumpDB');
        $dumper = new dumpDB();
        $dump = $dumper->getDump(false);
        
        $fileDir = Yii::app()->basePath . '/backup/'.date('Y-m-d').'.sql';
        
        Yii::import('ext.FileManager.fileManager');
        $fm = new fileManager();
        $result = $fm->write($fileDir, $dump);
        
        if ($return){        
            if($result){
                Yii::app()->user->setFlash('success', Yii::t('default', 'Backup efetuado com Sucesso!'));
            }else{
                Yii::app()->user->setFlash('error', Yii::t('default', 'Backup falhou!'));
            }
            Yii::app()->controller->redirect('?r=admin/index');
        }
        return $result;
        
    }
    
    /**
     * Generate some Data and a DataFile.
     * @param boolean $file - Defaults True
     * @return redirecrToData - Return to the Data page.
     */
    public function actionData($file = TRUE){
        $data = [];
        //Turma
        $where = "school_year = ".date('Y'); 
        $classrooms = Classroom::model()->count($where);
        $data['classroom'] = $classrooms;
        
        //Identificação do Professor
        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->join ='LEFT JOIN instructor_teaching_data ita ON ita.instructor_fk = t.id ';
        $criteria->join .='LEFT JOIN classroom c ON c.id = ita.classroom_id_fk';
        $criteria->condition = 'c.school_year = :value';
        $criteria->params = array(":value" => date('Y'));
        $criteria->group = 't.id';
        $instructors = InstructorIdentification::model()->count($criteria);
        $data['instructors'] = $instructors;
        
        //Identificação do Aluno
        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->join ='LEFT JOIN student_enrollment se ON se.student_fk = t.id ';
        $criteria->join .='LEFT JOIN classroom c ON c.id = se.classroom_fk';
        $criteria->condition = 'c.school_year = :value';
        $criteria->params = array(":value" => date('Y'));
        $criteria->group = 't.id';
        $students = StudentIdentification::model()->count($criteria);
        $data['students'] = $students;
        
        //Matricula do Aluno
        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->join .='LEFT JOIN classroom c ON c.id = t.classroom_fk';
        $criteria->condition = 'c.school_year = :value';
        $criteria->params = array(":value" => date('Y'));
        $enrollments = StudentEnrollment::model()->count($criteria);
        $data['enrollments'] = $enrollments;
        
        if($file){
            $fileDir = Yii::app()->basePath . '/backup/Data/'.date('Y-m-d').'.dat';
            $dataText = json_encode($data);

            Yii::import('ext.FileManager.fileManager');
            $fm = new fileManager();
            $result = $fm->write($fileDir, $dataText);  
            if($result){
                Yii::app()->user->setFlash('success', Yii::t('default', 'Dados salvos com Sucesso!'));
            }else{
                Yii::app()->user->setFlash('error', Yii::t('default', 'Não foi possível salvar os dados!'));
            }
        }
        
        $this->render('data', array('data' => $data));
    }
    
    public function actionExportStudentIdentify(){
        
        $fileDir = Yii::app()->basePath . '/export/alunos-'.date('Y_').Yii::app()->user->school.'.TXT';
        
        Yii::import('ext.FileManager.fileManager');
        $fm = new fileManager();
        
        $export = "";
        /* |id|nome|nascimento|mae|pai|UF|municipio| */
        
        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->condition = 't.inep_id is null '
                            . 'AND t.send_year <= :year';
        $criteria->params = array(":year" => date('Y'));
        $criteria->group = 't.id';
        $students = StudentIdentification::model()->findAll($criteria);
        foreach ($students as $key => $student) {
            $a = $student;
            $export .= "|".$a->id
                    ."|".$a->name
                    ."|".$a->birthday
                    ."|".$a->mother_name
                    ."|".$a->father_name
                    ."|".$a->edcenso_uf_fk
                    ."|".$a->edcenso_city_fk
                    ."|\n";
        }
        
        $result = $fm->write($fileDir, $export);
        if ($result) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Exportação dos Alunos Concluida com Sucesso.'));
        } else {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Houve algum erro na Exportação dos Alunos.'));
        }
        
        $this->render('index');
        
    }
    
    
    /**
     * Generate the ExportFile.
     * @return redirecrToIndex - Return to the index page with a FlashMenssage
     */
    public function actionExport(){
        $export = "";
        
        //Escolas
        $schools = SchoolIdentification::model()->findAll();
        foreach ($schools as $key => $school) {
            $export .= implode('|', $school->attributes);
            $export .= "|\n";
        }
        
        //Estrutura Escolar
        $schoolsStructure = SchoolStructure::model()->findAll();
        foreach ($schoolsStructure as $key => $schoolStructure) {
            $export .= implode('|', $schoolStructure->attributes);
            $export .= "|\n";
        }
        
        //Turma
        $where = "school_year = ".date('Y'); 
        $classrooms = Classroom::model()->findAll($where);
        foreach ($classrooms as $key => $classroom) {
            $attributes = $classroom->attributes;
            //Remove Turno
            array_pop($attributes);
            //Remove Ano
            array_pop($attributes);
            $export .= implode('|', $attributes);
            $export .= "|\n";
        }
        
        //Identificação do Professor
        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->join ='LEFT JOIN instructor_teaching_data ita ON ita.instructor_fk = t.id ';
        $criteria->join .='LEFT JOIN classroom c ON c.id = ita.classroom_id_fk';
        $criteria->condition = 'c.school_year = :value';
        $criteria->params = array(":value" => date('Y'));
        $criteria->group = 't.id';
        $instructors = InstructorIdentification::model()->findAll($criteria);
        foreach ($instructors as $key => $instructor) {
            $export .= implode('|', $instructor->attributes);
            $export .= "|\n";
            
            //Documentos do Professor
            $instructorDocs = InstructorDocumentsAndAddress::model()->findByPk($instructor->id);
            $export .= implode('|', $instructorDocs->attributes);
            $export .= "|\n";
            
            //Variáveis de Encino do Professor
            $instructorVariables = InstructorVariableData::model()->findByPk($instructor->id);
            $export .= implode('|', $instructorVariables->attributes);
            $export .= "|\n";
            
            //Dados de Docência do Professor
            $criteria->select = 't.*';
            $criteria->condition = 't.instructor_fk = :value';
            $criteria->params = array(":value" => $instructor->id);
            $instructorTeachingDatas = InstructorTeachingData::model()->findAll($criteria);
            foreach($instructorTeachingDatas as $itd){
                $attributes = $itd->attributes;
                //Remove Id
                array_pop($attributes);
                $export .= implode('|', $attributes);
                $export .= "|\n";
            }
            
        } 
                
        //Identificação do Aluno
        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->join = 'LEFT JOIN student_enrollment se ON se.student_fk = t.id ';
        $criteria->join .='LEFT JOIN classroom c ON c.id = se.classroom_fk';
        $criteria->condition = 'c.school_year = :value '
                            . 'AND t.send_year <= :year';
        $criteria->params = array(":value" => date('Y'), ":year" => date('Y'));
        $criteria->group = 't.id';
        $students = StudentIdentification::model()->findAll($criteria);
        foreach ($students as $key => $student) {
            $attributes = $student->attributes;
            //Remove send_year
            array_pop($attributes);
            $export .= implode('|',$attributes);
            $export .= "|\n";
            
            //Documentos do Aluno
            $studentDocs = StudentDocumentsAndAddress::model()->findByPk($student->id);
            $export .= implode('|', $studentDocs->attributes);
            $export .= "|\n";
            
            //Matricula do Aluno
            $criteria->select = 't.*';
            $criteria->condition = 't.student_fk = :value';
            $criteria->params = array(":value" => $student->id);
            $enrollments = StudentEnrollment::model()->findAll($criteria);
            foreach($enrollments as $enrollment){
                $attributes = $enrollment->attributes;
                //Remove Id
                array_pop($attributes);
                $export .= implode('|', $attributes);
                $export .= "|\n";
            }
        }
        
        $fileDir = Yii::app()->basePath . '/export/'.date('Y_').Yii::app()->user->school.'.TXT';
        
        Yii::import('ext.FileManager.fileManager');
        $fm = new fileManager();
        $result = $fm->write($fileDir, $export);
        
        if ($result) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Exportação Concluida com Sucesso.'));
        } else {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Houve algum erro na Exportação.'));
        }

        $this->render('index');
    }
    

    public function actionCreateUser() {
        $model = new Users;

        if (isset($_POST['Users'], $_POST['Confirm'])) {
            $model->attributes = $_POST['Users'];
            if ($model->validate()) {
                $password = md5($_POST['Users']['password']);
                $confirm = md5($_POST['Confirm']);
                if ($password == $confirm) {
                    $model->password = $password;
                    // form inputs are valid, do something here
                    if ($model->save()) {
                        $save = true;
                        foreach ($_POST['schools'] as $school){
                            $userSchool = new UsersSchool;
                            $userSchool->user_fk = $model->id;
                            $userSchool->school_fk = $school;
                            $save = $save && $userSchool->validate() && $userSchool->save();
                        }
                        if($save){
                            $auth = Yii::app()->authManager;
                            $auth->assign($_POST['Role'], $model->id);
                            Yii::app()->user->setFlash('success', Yii::t('default', 'Usuário cadastrado com sucesso!'));
                            $this->redirect(array('index'));
                        }
                    }
                } else {
                    $model->addError('password', Yii::t('default', 'Confirm Password') . ': ' . Yii::t('help', 'Confirm'));
                }
            }
        }
        $this->render('createUser', array('model' => $model));
    }

    public function addTestUsers() {
        set_time_limit(0);
        ignore_user_abort();
        $admin_login = 'admin';
        $admin_password = md5('p@s4ipti');

        $command = "INSERT INTO `users`VALUES
                        (1, 'Administrador', '$admin_login', '$admin_password', 1);";
        Yii::app()->db->createCommand($command)->query();

        $auth = Yii::app()->authManager;
        $auth->assign('admin', 1);
        
//        //Criar usuário de teste, remover depois.
//        /*         * ************************************************************************************************ */
//        /**/$command = "INSERT INTO `users`VALUES"
//                /**/ . "(2, 'Paulo Roberto', 'paulones', 'e10adc3949ba59abbe56e057f20f883e', 1);"
//                /**/ . "INSERT INTO `users_school` (`id`, `school_fk`, `user_fk`) VALUES (1, '28025911', 2);"
//                /**/ . "INSERT INTO `users_school` (`id`, `school_fk`, `user_fk`) VALUES (2, '28025970', 2);"
//                /**/ . "INSERT INTO `users_school` (`id`, `school_fk`, `user_fk`) VALUES (3, '28025989', 2);"
//                /**/ . "INSERT INTO `users_school` (`id`, `school_fk`, `user_fk`) VALUES (4, '28026012', 2);";
//        /**/Yii::app()->db->createCommand($command)->query();
//        /*         * ************************************************************************************************ */
    }

    public function actionClearDB() {
        $command = "
            delete from AuthAssignment;
            
            delete from users_school;
            delete from users;

            delete from class_board;
            delete from class_faults;
            delete from class;

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
        
        Yii::app()->user->setFlash('success', Yii::t('default', 'Banco limpado com sucesso. <br/>Faça o login novamente para atualizar os dados.'));
        $this->redirect(array('index'));
    }

    public function actionImport() {
        
        $path = Yii::app()->basePath;
            
        //Se não passar parametro, o valor será predefinido
        if (!empty($_FILES['file'])) {
            $fileDir = $path . '/import/2013_98018493.TXT';
        }else{
            $uploadfile = $path .'/import/'. basename($_FILES['file']['name']);
            move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile);
            $fileDir = $uploadfile;
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

        Yii::app()->user->setFlash('success', Yii::t('default', 'Arquivo do Educacenso importado com sucesso. <br/>Faça o login novamente para atualizar os dados.'));
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
        
        $auth->createOperation('updateFrequency', 'update a Frequency');
        
        $auth->createOperation('generateBFReport', 'generate BFReport');
        

     
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
        
        $role->addChild('updateFrequency');
        
        $role->addChild('generateBFReport');
        
        $role->addChild('updateSchool');


        $role = $auth->createRole('admin');
        $role->addChild('manager');
        $role->addChild('createSchool');
        $role->addChild('deleteSchool');


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
                            $year = '2013';//date("Y");
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
                        $str_fields[$regType] = "INSERT INTO `school_identification`(`register_type`,`inep_id`,`situation`,`initial_date`,`final_date`,`name`,`latitude`,`longitude`,`cep`,`address`,`address_number`,`address_complement`,`address_neighborhood`,`edcenso_uf_fk`,`edcenso_city_fk`,`edcenso_district_fk`,`ddd`,`phone_number`,`public_phone_number`,`other_phone_number`,`fax_number`,`email`,`edcenso_regional_education_organ_fk`,`administrative_dependence`,`location`,`private_school_category`,`public_contract`,`private_school_business_or_individual`,`private_school_syndicate_or_association`,`private_school_ong_or_oscip`,`private_school_non_profit_institutions`,`private_school_s_system`,`private_school_maintainer_cnpj`,`private_school_cnpj`,`regulation`) VALUES " . $lines;
                        break;
                    }
                case '10': {
                        $str_fields[$regType] = "INSERT INTO `school_structure`(`register_type`,`school_inep_id_fk`,`manager_cpf`,`manager_name`,`manager_role`,`manager_email`,`operation_location_building`,`operation_location_temple`,`operation_location_businness_room`,`operation_location_instructor_house`,`operation_location_other_school_room`,`operation_location_barracks`,`operation_location_socioeducative_unity`,`operation_location_prison_unity`,`operation_location_other`,`building_occupation_situation`,`shared_building_with_school`,`shared_school_inep_id_1`,`shared_school_inep_id_2`,`shared_school_inep_id_3`,`shared_school_inep_id_4`,`shared_school_inep_id_5`,`shared_school_inep_id_6`,`consumed_water_type`,`water_supply_public`,`water_supply_artesian_well`,`water_supply_well`,`water_supply_river`,`water_supply_inexistent`,`energy_supply_public`,`energy_supply_generator`,`energy_supply_other`,`energy_supply_inexistent`,`sewage_public`,`sewage_fossa`,`sewage_inexistent`,`garbage_destination_collect`,`garbage_destination_burn`,`garbage_destination_throw_away`,`garbage_destination_recycle`,`garbage_destination_bury`,`garbage_destination_other`,`dependencies_principal_room`,`dependencies_instructors_room`,`dependencies_secretary_room`,`dependencies_info_lab`,`dependencies_science_lab`,`dependencies_aee_room`,`dependencies_indoor_sports_court`,`dependencies_outdoor_sports_court`,`dependencies_kitchen`,`dependencies_library`,`dependencies_reading_room`,`dependencies_playground`,`dependencies_nursery`,`dependencies_outside_bathroom`,`dependencies_inside_bathroom`,`dependencies_child_bathroom`,`dependencies_prysical_disability_bathroom`,`dependencies_physical_disability_support`,`dependencies_bathroom_with_shower`,`dependencies_refectory`,`dependencies_storeroom`,`dependencies_warehouse`,`dependencies_auditorium`,`dependencies_covered_patio`,`dependencies_uncovered_patio`,`dependencies_student_accomodation`,`dependencies_instructor_accomodation`,`dependencies_green_area`,`dependencies_laundry`,`dependencies_none`,`classroom_count`,`used_classroom_count`,`equipments_tv`,`equipments_vcr`,`equipments_dvd`,`equipments_satellite_dish`,`equipments_copier`,`equipments_overhead_projector`,`equipments_printer`,`equipments_stereo_system`,`equipments_data_show`,`equipments_fax`,`equipments_camera`,`equipments_computer`,`administrative_computers_count`,`student_computers_count`,`internet_access`,`bandwidth`,`employees_count`,`feeding`,`aee`,`complementary_activities`,`modalities_regular`,`modalities_especial`,`modalities_eja`,`stage_regular_education_creche`,`stage_regular_education_preschool`,`stage_regular_education_fundamental_eigth_years`,`stage_regular_education_fundamental_nine_years`,`stage_regular_education_high_school`,`stage_regular_education_high_school_integrated`,`stage_regular_education_high_school_normal_mastership`,`stage_regular_education_high_school_preofessional_education`,`stage_special_education_creche`,`stage_special_education_preschool`,`stage_special_education_fundamental_eigth_years`,`stage_special_education_fundamental_nine_years`,`stage_special_education_high_school`,`stage_special_education_high_school_integrated`,`stage_special_education_high_school_normal_mastership`,`stage_special_education_high_school_professional_education`,`stage_special_education_eja_fundamental_education`,`stage_special_education_eja_high_school_education`,`stage_education_eja_fundamental_education`,`stage_education_eja_fundamental_education_projovem`,`stage_education_eja_high_school_education`,`basic_education_cycle_organized`,`different_location`,`sociocultural_didactic_material_none`,`sociocultural_didactic_material_quilombola`,`sociocultural_didactic_material_native`,`native_education`,`native_education_language_native`,`native_education_language_portuguese`,`edcenso_native_languages_fk`,`brazil_literate`,`open_weekend`,`pedagogical_formation_by_alternance`) VALUES " . $lines;
                        break;
                    }
                case '20': {
                        $str_fields[$regType] = "INSERT INTO `classroom`(`register_type`,`school_inep_fk`,`inep_id`,`id`,`name`,`initial_hour`,`initial_minute`,`final_hour`,`final_minute`,`week_days_sunday`,`week_days_monday`,`week_days_tuesday`,`week_days_wednesday`,`week_days_thursday`,`week_days_friday`,`week_days_saturday`,`assistance_type`,`mais_educacao_participator`,`complementary_activity_type_1`,`complementary_activity_type_2`,`complementary_activity_type_3`,`complementary_activity_type_4`,`complementary_activity_type_5`,`complementary_activity_type_6`,`aee_braille_system_education`,`aee_optical_and_non_optical_resources`,`aee_mental_processes_development_strategies`,`aee_mobility_and_orientation_techniques`,`aee_libras`,`aee_caa_use_education`,`aee_curriculum_enrichment_strategy`,`aee_soroban_use_education`,`aee_usability_and_functionality_of_computer_accessible_education`,`aee_teaching_of_Portuguese_language_written_modality`,`aee_strategy_for_school_environment_autonomy`,`modality`,`edcenso_stage_vs_modality_fk`,`edcenso_professional_education_course_fk`,`discipline_chemistry`,`discipline_physics`,`discipline_mathematics`,`discipline_biology`,`discipline_science`,`discipline_language_portuguese_literature`,`discipline_foreign_language_english`,`discipline_foreign_language_spanish`,`discipline_foreign_language_franch`,`discipline_foreign_language_other`,`discipline_arts`,`discipline_physical_education`,`discipline_history`,`discipline_geography`,`discipline_philosophy`,`discipline_social_study`,`discipline_sociology`,`discipline_informatics`,`discipline_professional_disciplines`,`discipline_special_education_and_inclusive_practices`,`discipline_sociocultural_diversity`,`discipline_libras`,`discipline_pedagogical`,`discipline_religious`,`discipline_native_language`,`discipline_others`,`instructor_situation`,`school_year`) VALUES " . $lines;
                        break;
                    }
                case '30': {
                        $str_fields[$regType] = "INSERT INTO `instructor_identification`(`register_type`,`school_inep_id_fk`,`inep_id`,`id`,`name`,`email`,`nis`,`birthday_date`,`sex`,`color_race`,`mother_name`,`nationality`,`edcenso_nation_fk`,`edcenso_uf_fk`,`edcenso_city_fk`,`deficiency`,`deficiency_type_blindness`,`deficiency_type_low_vision`,`deficiency_type_deafness`,`deficiency_type_disability_hearing`,`deficiency_type_deafblindness`,`deficiency_type_phisical_disability`,`deficiency_type_intelectual_disability`,`deficiency_type_multiple_disabilities`) VALUES " . $lines;
                        break;
                    }
                case '40': {
                        $str_fields[$regType] = "INSERT INTO `instructor_documents_and_address`(`register_type`,`school_inep_id_fk`,`inep_id`,`id`,`cpf`,`area_of_residence`,`cep`,`address`,`address_number`,`complement`,`neighborhood`,`edcenso_uf_fk`,`edcenso_city_fk`) VALUES " . $lines;
                        break;
                    }
                case '50': {
                        $str_fields[$regType] = "INSERT INTO `instructor_variable_data`(`register_type`,`school_inep_id_fk`,`inep_id`,`id`,`scholarity`,`high_education_situation_1`,`high_education_formation_1`,`high_education_course_code_1_fk`,`high_education_initial_year_1`,`high_education_final_year_1`,`high_education_institution_type_1`,`high_education_institution_code_1_fk`,`high_education_situation_2`,`high_education_formation_2`,`high_education_course_code_2_fk`,`high_education_initial_year_2`,`high_education_final_year_2`,`high_education_institution_type_2`,`high_education_institution_code_2_fk`,`high_education_situation_3`,`high_education_formation_3`,`high_education_course_code_3_fk`,`high_education_initial_year_3`,`high_education_final_year_3`,`high_education_institution_type_3`,`high_education_institution_code_3_fk`,`post_graduation_specialization`,`post_graduation_master`,`post_graduation_doctorate`,`post_graduation_none`,`other_courses_nursery`,`other_courses_pre_school`,`other_courses_basic_education_initial_years`,`other_courses_basic_education_final_years`,`other_courses_high_school`,`other_courses_education_of_youth_and_adults`,`other_courses_special_education`,`other_courses_native_education`,`other_courses_field_education`,`other_courses_environment_education`,`other_courses_human_rights_education`,`other_courses_sexual_education`,`other_courses_child_and_teenage_rights`,`other_courses_ethnic_education`,`other_courses_other`,`other_courses_none`) VALUES " . $lines;
                        break;
                    }

                case '51': {
                        $str_fields[$regType] = "INSERT INTO `instructor_teaching_data`(`register_type`,`school_inep_id_fk`,`instructor_inep_id`,`instructor_fk`,`classroom_inep_id`,`classroom_id_fk`,`role`,`contract_type`,`discipline_1_fk`,`discipline_2_fk`,`discipline_3_fk`,`discipline_4_fk`,`discipline_5_fk`,`discipline_6_fk`,`discipline_7_fk`,`discipline_8_fk`,`discipline_9_fk`,`discipline_10_fk`,`discipline_11_fk`,`discipline_12_fk`,`discipline_13_fk`) VALUES " . $lines;
                        break;
                    }
                case '60': {
                        $str_fields[$regType] = "INSERT INTO `student_identification`(`register_type`,`school_inep_id_fk`,`inep_id`,`id`,`name`,`nis`,`birthday`,`sex`,`color_race`,`filiation`,`mother_name`,`father_name`,`nationality`,`edcenso_nation_fk`,`edcenso_uf_fk`,`edcenso_city_fk`,`deficiency`,`deficiency_type_blindness`,`deficiency_type_low_vision`,`deficiency_type_deafness`,`deficiency_type_disability_hearing`,`deficiency_type_deafblindness`,`deficiency_type_phisical_disability`,`deficiency_type_intelectual_disability`,`deficiency_type_multiple_disabilities`,`deficiency_type_autism`,`deficiency_type_aspenger_syndrome`,`deficiency_type_rett_syndrome`,`deficiency_type_childhood_disintegrative_disorder`,`deficiency_type_gifted`,`resource_aid_lector`,`resource_aid_transcription`,`resource_interpreter_guide`,`resource_interpreter_libras`,`resource_lip_reading`,`resource_zoomed_test_16`,`resource_zoomed_test_20`,`resource_zoomed_test_24`,`resource_braille_test`,`resource_none`) VALUES " . $lines;
                        break;
                    }
                case '70': {
                        $str_fields[$regType] = "INSERT INTO `student_documents_and_address`(`register_type`,`school_inep_id_fk`,`student_fk`,`id`,`rg_number`,`rg_number_complement`,`rg_number_edcenso_organ_id_emitter_fk`,`rg_number_edcenso_uf_fk`,`rg_number_expediction_date`,`civil_certification`,`civil_certification_type`,`civil_certification_term_number`,`civil_certification_sheet`,`civil_certification_book`,`civil_certification_date`,`notary_office_uf_fk`,`notary_office_city_fk`,`edcenso_notary_office_fk`,`civil_register_enrollment_number`,`cpf`,`foreign_document_or_passport`,`nis`,`document_failure_lack`,`residence_zone`,`cep`,`address`,`number`,`complement`,`neighborhood`,`edcenso_uf_fk`,`edcenso_city_fk`) VALUES " . $lines;
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
