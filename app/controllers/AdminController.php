<?php
	class AdminController extends Controller {
		public $layout = 'fullmenu';
		public function accessRules() {
			return [
				[
					'allow', // allow authenticated user to perform 'create' and 'update' actions
					'actions' => ['CreateUser', 'index', 'conflicts'], 'users' => ['*'],
				], [
					'allow', // allow authenticated user to perform 'create' and 'update' actions
					'actions' => [
						'import', 'export', 'clearDB', 'acl', 'backup', 'data', 'exportStudentIdentify', 'syncExport',
						'syncImport', 'exportToMaster', 'clearMaster', 'importFromMaster'
					], 'users' => ['@'],
				],
			];
		}
		/**
		 * Show the Index Page.
		 */
		public function actionIndex() {
			$this->render('index');
		}
		public function actionSyncImport() {
			set_time_limit(0);
			ini_set('memory_limit', '-1');
			ignore_user_abort();
			$time1 = time();
			$path = Yii::app()->basePath;
			$myfile = $_FILES['file'];
			$uploadfile = $path . '/import/' . basename($myfile['name']);
			move_uploaded_file($myfile["tmp_name"], $uploadfile);
			$fileDir = $uploadfile;
			$mode = 'r';

			$fileImport = fopen($fileDir, $mode);
			if ($fileImport == FALSE) {
				die('O arquivo não existe.');
			}

			$jsonSyncTag = "";
			while (!feof($fileImport)) {
				$linha = fgets($fileImport, filesize($uploadfile));
				$jsonSyncTag .= $linha;
			}
			fclose($fileImport);

			$json = json_decode($jsonSyncTag, TRUE);

		}
		public function actionExportStudentIdentify() {

			$fileDir = Yii::app()->basePath . '/export/alunos-' . date('Y_') . Yii::app()->user->school . '.TXT';

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
            $export .= "|" . $a->id
                . "|" . $a->name
                . "|" . $a->birthday
                . "|" . $a->filiation_1
                . "|" . $a->filiation_2
                . "|" . $a->edcenso_uf_fk
                . "|" . $a->edcenso_city_fk
                . "|\n";
        }

        $result = $fm->write($fileDir, $export);
        if ($result) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Exportação dos Alunos Concluida com Sucesso.'));
        } else {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Houve algum erro na Exportação dos Alunos.'));
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
							$save = TRUE;
							foreach ($_POST['schools'] as $school) {
								$userSchool = new UsersSchool;
								$userSchool->user_fk = $model->id;
								$userSchool->school_fk = $school;
								$save = $save && $userSchool->validate() && $userSchool->save();
							}
							if ($save) {
								$auth = Yii::app()->authManager;
								$auth->assign($_POST['Role'], $model->id);
								Yii::app()->user->setFlash('success', Yii::t('default', 'Usuário cadastrado com sucesso!'));
								$this->redirect(['index']);
							}
						}
					} else {
						$model->addError('password', Yii::t('default', 'Confirm Password') . ': ' . Yii::t('help', 'Confirm'));
					}
				}
			}
			$this->render('createUser', ['model' => $model]);
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
			//delete from users_school;
			//delete from users;
			// delete from auth_assignment;

			$command = "
			SET FOREIGN_KEY_CHECKS=0;
			
			delete from auth_assignment;
			delete from users;
			delete from users_school;
			
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
		public function mres($value)
		{
			$search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
			$replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");

			return str_replace($search, $replace, $value);
		}
		public function actionLoadToMaster(){
			$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
			$classrooms = Classroom::model()->findAllByAttributes(["school_inep_fk" => yii::app()->user->school, "school_year" => Yii::app()->user->year]);
			$loads['school'] = $school->attributes;
			$loads['school']['hash'] = hexdec(crc32($school->inep_id.$school->name));
			foreach ($classrooms as $iclass => $classroom) {
				$hash_classroom = hexdec(crc32($school->inep_id.$classroom->id.$classroom->school_year));
				$loads['classrooms'][$iclass] = $classroom->attributes;
				$loads['classrooms'][$iclass]['hash'] = $hash_classroom;
				foreach ($classroom->studentEnrollments as $ienrollment => $enrollment) {
					if(!isset($loads['students'][$enrollment->student_fk])){
						$hash_student = hexdec(crc32($enrollment->studentFk->name.$enrollment->studentFk->birthday));
						$loads['students'][$enrollment->student_fk] = $enrollment->studentFk->attributes;
						$loads['students'][$enrollment->student_fk]['hash'] = $hash_student;
					}
					if(!isset($loads['documentsaddress'][$enrollment->student_fk])){
						$loads['documentsaddress'][$enrollment->student_fk] = StudentDocumentsAndAddress::model()->findByPk($enrollment->student_fk)->attributes;
						$loads['documentsaddress'][$enrollment->student_fk]['hash'] = $hash_student;
					}
					$hash_enrollment = hexdec(crc32($hash_classroom.$hash_student));
					$loads['enrollments'][$ienrollment] = $enrollment->attributes;
					$loads['enrollments'][$ienrollment]['hash'] = $hash_enrollment;
					$loads['enrollments'][$ienrollment]['hash_classroom'] = $hash_classroom;
					$loads['enrollments'][$ienrollment]['hash_student'] = $hash_student;
				}
				foreach ($classroom->instructorTeachingDatas as $iteaching => $teachingData) {
					//CARREGAR AS INFORMAÇÕES DE TEACHING DATA;
					$hash_instructor = hexdec(crc32($teachingData->instructorFk->name.$teachingData->instructorFk->birthday_date));
					$hash_teachingdata = hexdec(crc32($hash_classroom.$hash_instructor));
					$loads['instructorsteachingdata'][$teachingData->instructor_fk][$classroom->id] = $teachingData->attributes;
					$loads['instructorsteachingdata'][$teachingData->instructor_fk][$classroom->id]['hash_instructor'] = $hash_instructor;
					$loads['instructorsteachingdata'][$teachingData->instructor_fk][$classroom->id]['hash_classroom'] = $hash_classroom;
					$loads['instructorsteachingdata'][$teachingData->instructor_fk][$classroom->id]['hash'] = $hash_teachingdata;

					//CARREGAR AS INFORMAÇÕES DE TEACHING DATA;
					if(!isset($loads['instructors'][$teachingData->instructor_fk])){
						$loads['instructors'][$teachingData->instructor_fk]['identification'] = $teachingData->instructorFk->attributes;
						$loads['instructors'][$teachingData->instructor_fk]['identification']['hash'] = $hash_instructor;
						$loads['idocuments'][$teachingData->instructor_fk]['documents'] = $teachingData->instructorFk->documents->attributes;
						$loads['idocuments'][$teachingData->instructor_fk]['documents']['hash'] = $hash_instructor;

					}
					if(!isset($loads['instructorsvariabledata'][$teachingData->instructor_fk])) {
						$loads['instructorsvariabledata'][$teachingData->instructor_fk] = $teachingData->instructorFk->instructorVariableData->attributes;
						$loads['instructorsvariabledata'][$teachingData->instructor_fk]['hash'] = $hash_instructor;
					}
				}

			}
			$sql = json_encode($loads);
			$importToFile = FALSE;
			try {
				Yii::app()->db2;
			} catch (Exception $e) {
				$importToFile = TRUE;
			}
			if ($importToFile) {
				ini_set('memory_limit', '128M');
				$fileName = "./app/export/export" . date("Y-m-d") . ".sql";
				$file = fopen($fileName, "w");
				fwrite($file, $sql);
				fclose($file);
				header("Content-Disposition: attachment; filename=\"" . basename($fileName) . "\"");
				header("Content-Type: application/force-download");
				header("Content-Length: " . filesize($fileName));
				header("Connection: close");

				$file = fopen($fileName, "r");
				fpassthru($file);
				fclose($file);
				unlink($fileName);

				return;
			} else {
				$saveschool = new SchoolIdentification();
				$saveschool->setDb2Connection(true);
				$saveschool->attributes = $loads['school'];
				$saveschool->save();

				foreach ($loads['classrooms'] as $index => $class) {
					$saveclass = new Classroom();
					$saveclass->setDb2Connection(true);
					$saveclass->refreshMetaData();
					$saveclass->attributes = $class;
					$saveclass->hash = $class['hash'];
					$saveclass->save();
				}
				foreach ($loads['students'] as $i => $student) {
					$savestudent = new StudentIdentification();
					$savestudent->setDb2Connection(true);
					$savestudent->refreshMetaData();
					$savestudent->attributes = $student;
					$savestudent->hash = $student['hash'];
					$savestudent->save();
				}

				foreach ($loads['documentsaddress'] as $i => $documentsaddress) {
					$savedocument = new StudentDocumentsAndAddress();
					$savedocument->setDb2Connection(true);
					$savedocument->refreshMetaData();
					$savedocument->attributes = $documentsaddress;
					$savedocument->hash = $documentsaddress['hash'];
					$savedocument->save();
				}
				foreach ($loads['enrollments'] as $index => $enrollment) {
					$saveenrollment = new StudentEnrollment();
					$saveenrollment->setDb2Connection(true);
					$saveenrollment->refreshMetaData();
					$saveenrollment->attributes = $enrollment;
					$saveenrollment->hash = $enrollment['hash'];
					$saveenrollment->hash_classroom = $enrollment['hash_classroom'];
					$saveenrollment->hash_student = $enrollment['hash_student'];
					$saveenrollment->save();
				}


				//@TODO FAZER A PARTE DE PROFESSORES A PARTIR DAQUI

				Yii::app()->user->setFlash('success', Yii::t('default', 'Escola exportada com sucesso!'));
				$this->redirect(['index']);
			}





		}
	}

?>
