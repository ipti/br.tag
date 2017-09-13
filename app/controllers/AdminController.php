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
		public function actionImportMaster(){
			set_time_limit(0);
			ini_set('memory_limit', '-1');
			ignore_user_abort();
			$time1 = time();
			$path = Yii::app()->basePath;
			$uploadfile = $path . '/import/28031610.json';
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
			$this->loadMaster($json);

		}
		public function loadMaster($loads){
			foreach ($loads['schools'] as $index => $scholl) {
				$saveschool = new SchoolIdentification();
				$saveschool->setDb2Connection(true);
				$saveschool->refreshMetaData();
				$exist = $saveschool->findByAttributes(array('inep_id'=>$scholl['inep_id']));
				if(!isset($exist)){
					$saveschool->attributes = $scholl;
					$saveschool->save();
				}
			}
			foreach ($loads['classrooms'] as $index => $class) {
				$saveclass = new Classroom();
				$saveclass->setScenario('search');
				$saveclass->setDb2Connection(true);
				$saveclass->refreshMetaData();
				$exist = $saveclass->findByAttributes(array('hash'=>$class['hash']));
				if (!isset($exist)){
					$saveclass->attributes = $class;
					$saveclass->hash = $class['hash'];
					$saveclass->save();
				}
			}
			foreach ($loads['students'] as $i => $student) {
				$savestudent = new StudentIdentification();
				$savestudent->setScenario('search');
				$savestudent->setDb2Connection(true);
				$savestudent->refreshMetaData();
				$exist = $savestudent->findByAttributes(array('hash'=>$student['hash']));
				if (!isset($exist)){
					$savestudent->attributes = $student;
					$savestudent->hash = $student['hash'];
					$savestudent->save();
				}
			}

			foreach ($loads['documentsaddress'] as $i => $documentsaddress) {
				$savedocument = new StudentDocumentsAndAddress();
				$savedocument->setScenario('search');
				$savedocument->setDb2Connection(true);
				$savedocument->refreshMetaData();
				$exist = $savedocument->findByAttributes(array('hash'=>$documentsaddress['hash']));
				if (!isset($exist)){
					$savedocument->attributes = $documentsaddress;
					$savedocument->hash = $documentsaddress['hash'];
					$savedocument->save();
				}
			}



			foreach ($loads['enrollments'] as $index => $enrollment) {
				$saveenrollment = new StudentEnrollment();
				$saveenrollment->setScenario('search');
				$saveenrollment->setDb2Connection(true);
				$saveenrollment->refreshMetaData();
				$exist = $saveenrollment->findByAttributes(array('hash'=>$enrollment['hash']));
				if (!isset($exist)){
					$saveenrollment->attributes = $enrollment;
					$saveenrollment->hash = $enrollment['hash'];
					$saveenrollment->hash_classroom = $enrollment['hash_classroom'];
					$saveenrollment->hash_student = $enrollment['hash_student'];
					$saveenrollment->save();
				}
			}
			//@TODO FAZER A PARTE DE PROFESSORES A PARTIR DAQUI

		}
		public function actionExportMaster(){
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '500M');
			$year = Yii::app()->user->year;
			$sql = "SELECT DISTINCT(school_inep_id_fk) FROM student_enrollment a
					JOIN classroom b ON(a.`classroom_fk`=b.id)
					WHERE
					b.`school_year`=$year";

			$schools = Yii::app()->db->createCommand($sql)->queryAll();
			$studentAll = StudentIdentification::model()->findAll();

			foreach ($studentAll as $index => $student) {
				$hash_student = hexdec(crc32($student->name.$student->birthday));
				if(!isset($loads['students'][$hash_student])){
					$loads['students'][$hash_student] = $student->attributes;
					$loads['students'][$hash_student]['hash'] = $hash_student;
				}
				if(!isset($loads['documentsaddress'][$hash_student])){
					$loads['documentsaddress'][$hash_student] = StudentDocumentsAndAddress::model()->findByPk($student->id)->attributes;
					$loads['documentsaddress'][$hash_student]['hash'] = $hash_student;
				}
			}

			foreach ($schools as $index => $schll) {
				$school = SchoolIdentification::model()->findByPk($schll['school_inep_id_fk']);
				$classrooms = Classroom::model()->findAllByAttributes(["school_inep_fk" => $schll['school_inep_id_fk'], "school_year" => Yii::app()->user->year]);
				$loads['schools'][$index] = $school->attributes;
				$loads['schools'][$index]['hash'] = hexdec(crc32($school->inep_id.$school->name));
				foreach ($classrooms as $iclass => $classroom) {
					$hash_classroom = hexdec(crc32($school->inep_id.$classroom->id.$classroom->school_year));
					$loads['classrooms'][$hash_classroom] = $classroom->attributes;
					$loads['classrooms'][$hash_classroom]['hash'] = $hash_classroom;
					foreach ($classroom->studentEnrollments as $ienrollment => $enrollment) {
						$hash_student = hexdec(crc32($enrollment->studentFk->name.$enrollment->studentFk->birthday));
						if(!isset($loads['students'][$hash_student])){
							$loads['students'][$hash_student] = $enrollment->studentFk->attributes;
							$loads['students'][$hash_student]['hash'] = $hash_student;
						}
						if(!isset($loads['documentsaddress'][$hash_student])){
							$loads['documentsaddress'][$hash_student] = StudentDocumentsAndAddress::model()->findByPk($enrollment->student_fk)->attributes;
							$loads['documentsaddress'][$hash_student]['hash'] = $hash_student;
						}
						$hash_enrollment = hexdec(crc32($hash_classroom.$hash_student));
						$loads['enrollments'][$hash_enrollment] = $enrollment->attributes;
						$loads['enrollments'][$hash_enrollment]['hash'] = $hash_enrollment;
						$loads['enrollments'][$hash_enrollment]['hash_classroom'] = $hash_classroom;
						$loads['enrollments'][$hash_enrollment]['hash_student'] = $hash_student;
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
			}
			$datajson = json_encode($loads);
			$importToFile = FALSE;
			try {
				Yii::app()->db2;
			} catch (Exception $e) {
				$importToFile = TRUE;
			}
			if ($importToFile) {
				ini_set('memory_limit', '288M');
				$fileName = "./app/export/" . Yii::app()->user->school . ".json";
				$file = fopen($fileName, "w");
				fwrite($file, $datajson);
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
				$this->loadMaster($loads);
				Yii::app()->user->setFlash('success', Yii::t('default', 'Escola exportada com sucesso!'));
				$this->redirect(['index']);
			}





		}
	}

?>
