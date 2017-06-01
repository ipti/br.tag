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

		public function actionMultiStageClassroomVerify($id){
				$year = Yii::app()->user->year;

				$sql = "SELECT si.name as studentName, si.id as studentId , cq.name as className, cq.stage 
						from classroom_qtd_students as cq
						join student_enrollment as se on se.classroom_fk = cq.id
						join student_identification as si on se.student_fk = si.id 
						where cq.school_inep_fk = $id  AND cq.school_year = $year AND se.edcenso_stage_vs_modality_fk is null
						AND(cq.name LIKE '%multi%' or cq.name LIKE '%MULTI%' or cq.name LIKE '%MULTIETAPA%' or cq.name LIKE '%multietapa%') order by studentName";					
				$student = Yii::app()->db->createCommand($sql)->queryAll();

				$school = SchoolIdentification::model()->findByPk($_GET['id']);


				$sql1 = "SELECT * FROM edcenso_stage_vs_modality where id in(1,2,14,15,16,17,18,19,20,21)";

				$stage = Yii::app()->db->createCommand($sql1)->queryAll();

				$this->render('MultiStageClassroomVerify', array(
					'school' => $school,
					'student' => $student,
					'stage' => $stage
					)); 
		}

		public function actionSaveMultiStage() {
			$student = json_decode($_POST["data"]);

			foreach($student as $st) {
				$sql = "UPDATE student_enrollment  set edcenso_stage_vs_modality_fk = $st->val  where student_fk = $st->idx ";
				Yii::app()->db->createCommand($sql)->query();
			}
				
		
		}

    	public function actionExportInstructorWithoutInepid($id){
        $year = Yii::app()->user->year;

        $sql = "SELECT DISTINCT id.school_inep_id_fk , id.inep_id , id.name , id.email, id.birthday_date , id.filiation_1 , id.filiation_2 , id.edcenso_uf_fk , id.edcenso_city_fk
                FROM (instructor_teaching_data as it join classroom as c on it.classroom_id_fk = c.id ) join instructor_identification as id on it.instructor_fk = id.id
                where c.school_year = $year AND id.school_inep_id_fk = $id order by id.name";

        $instructors = Yii::app()->db->createCommand($sql)->queryAll();

        if(count($instructors) == 0){
            echo "N&atilde;o h&aacute; professores cadastrados nesta escola no ano de " . $year;
        }
        else {
            $fileName = date("Y-i-d") . "_" . $id . "_instructors_without_inep_id.txt";
            $fileDir = "./app/export/" . $fileName;
            $file = fopen($fileDir, 'w');


            foreach ($instructors as $i) {
                $linha = "";

                if ($i['school_inep_id_fk'] == null) {
                    $linha .= "||";
                } else {
                    $linha .= $i['school_inep_id_fk'] . "|";
                }

                if ($i['name'] == null) {
                    $linha .= "|";
                } else {
                    $linha .= $i['name'] . "|";
                }

                if ($i['email'] == null) {
                    $linha .= "|";
                } else {
                    $linha .= $i['email'] . "|";
                }

                if ($i['birthday_date'] == null) {
                    $linha .= "|";
                } else {
                    $linha .= $i['birthday_date'] . "|";
                }

                if ($i['filiation_1'] == null) {
                    $linha .= "|";
                } else {
                    $linha .= $i['filiation_1'] . "|";
                }

                if ($i['filiation_2'] == null) {
                    $linha .= "|";
                } else {
                    $linha .= $i['filiation_2'] . "|";
                }

                if ($i['edcenso_uf_fk'] == null) {
                    $linha .= "|";
                } else {
                    $linha .= $i['edcenso_uf_fk'] . "|";
                }

                if ($i['edcenso_city_fk'] == null) {
                    $linha .= "|";
                } else {
                    $linha .= $i['edcenso_city_fk'] . "|";
                }

                if ($i['inep_id'] == null) {
                    $linha .= "|" . "\n";
                } else {
                    $linha .= $i['inep_id'] . "\n";
                }

                fwrite($file, $linha);
            }

            fclose($file);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileDir));
            readfile($fileDir);
        }
    }

    	public function actionExportStudentWithoutInepid($id)
    {
        $year = Yii::app()->user->year;
        $sql = "SELECT DISTINCT si.school_inep_id_fk , si.inep_id , si.name , si.birthday , si.filiation_1 , si.filiation_2 , si.edcenso_uf_fk , si.edcenso_city_fk
			FROM (student_enrollment as se join classroom as c on se.classroom_fk = c.id ) join student_identification as si on se.student_fk = si.id
			where c.school_year = $year  AND si.school_inep_id_fk = $id order by si.name";

        $students = Yii::app()->db->createCommand($sql)->queryAll();

        if(count($students) == 0){
            echo "N&atilde;o h&aacute; alunos cadastrados nesta escola no ano de " . $year;
        }else {
            $fileName = date("Y-i-d") . "_" . $id . "_students_without_inep_id.txt";
            $fileDir = "./app/export/" . $fileName;
            $file = fopen($fileDir, 'w');

            foreach ($students as $s) {
                $linha = "";

                if ($s['school_inep_id_fk'] == null) {
                    $linha .= "||";
                } else {
                    $linha .= $s['school_inep_id_fk'] . "|";
                }

                if ($s['name'] == null) {
                    $linha .= "|";
                } else {
                    $linha .= $s['name'] . "|";
                }
                if ($s['birthday'] == null) {
                    $linha .= "|";
                } else {
                    $linha .= $s['birthday'] . "|";
                }

                if ($s['filiation_1'] == null) {
                    $linha .= "|";
                } else {
                    $linha .= $s['filiation_1'] . "|";
                }

                if ($s['filiation_2'] == null) {
                    $linha .= "|";
                } else {
                    $linha .= $s['filiation_2'] . "|";
                }

                if ($s['edcenso_uf_fk'] == null) {
                    $linha .= "|";
                } else {
                    $linha .= $s['edcenso_uf_fk'] . "|";
                }

                if ($s['edcenso_city_fk'] == null) {
                    $linha .= "|";
                } else {
                    $linha .= $s['edcenso_city_fk'] . "|";
                }

                if ($s['inep_id'] == null) {
                    $linha .= "|" . "\n";
                } else {
                    $linha .= $s['inep_id'] . "\n";
                }

                fwrite($file, $linha);
            }

            fclose($file);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileDir));
            readfile($fileDir);
        }
    }


		public function actionSyncExport() {
			set_time_limit(0);
			ini_set('memory_limit', '-1');

			// Fazer Download no Final
			//Arquivo Json para adcionar no ZIP
			$json = [];
			$json['student'] = [];
			$json['classroom'] = [];

			$school = yii::app()->user->school;
			$year = yii::app()->user->year;
			$filterStudent = "school_inep_id_fk = " . $school;
			$filterClassroom = "school_inep_fk = " . $school . " and school_year = " . $year;

			$enrollments = StudentEnrollment::model()->findAll($filterStudent);

			foreach ($enrollments as $enrollment) {
				$student = $enrollment->studentFk;
				$classroom = $enrollment->classroomFk;
				if ($classroom->school_year == $year) {
					$student->school_inep_id_fk = $enrollment->school_inep_id_fk;
					$student->save();
				}
			}

			$students = StudentIdentification::model()->findAll($filterStudent);
			$classrooms = Classroom::model()->findAll($filterClassroom);

			$updateFKID = "UPDATE `class` as c
            SET `fkid` = CONCAT((select school_inep_fk from classroom as cr where cr.id = c.classroom_fk),';',c.id)
            WHERE true;
            UPDATE `class_board` as c
            SET `fkid` = CONCAT((select school_inep_fk from classroom as cr where cr.id = c.classroom_fk),';',c.id)
            WHERE true;
            UPDATE `class_faults` as c
            SET `fkid` = CONCAT(
                    (select cr.school_inep_fk
                            from class as cs
                    join classroom as cr on (cs.classroom_fk = cr.id)
                            where cs.id = c.class_fk),';',c.id)
            WHERE true;
            UPDATE `class_has_content` as c
            SET `fkid` = CONCAT(
                    (select cr.school_inep_fk
                            from class as cs
                    join classroom as cr on (cs.classroom_fk = cr.id)
                            where cs.id = c.class_fk),';',c.id)
            WHERE true;
            UPDATE `classroom` as c
            SET `fkid` = CONCAT(c.school_inep_fk,';',c.id)
            WHERE true;
            UPDATE `classroom_has_course_plan` as c
            SET `fkid` = CONCAT((select school_inep_fk from classroom as cr where cr.id = c.classroom_fk),';',c.id)
            WHERE true;
            UPDATE `course_class` as c
            SET `fkid` = CONCAT((select school_inep_fk from course_plan as cp where cp.id = c.course_plan_fk),';',c.id)
            WHERE true;
            UPDATE `course_class_has_class_resource` as c
            SET `fkid` = CONCAT(
                    (select cp.school_inep_fk
                            from course_class as cs
                    join course_plan as cp on (cs.course_plan_fk = cp.id)
                            where cs.id = c.course_class_fk),';',c.id)
            WHERE true;
            UPDATE `course_plan` as c
            SET `fkid` = CONCAT(c.school_inep_fk,';',c.id)
            WHERE true;
            UPDATE `grade` as g
            SET `fkid` = CONCAT(
                    (select e.school_inep_id_fk
                            from student_enrollment as e
                            where e.id = g.enrollment_fk),';',g.id)
            WHERE true;
            UPDATE `instructor_documents_and_address` as c
            SET `fkid` = CONCAT(c.school_inep_id_fk,';',c.id)
            WHERE true;
            UPDATE `instructor_identification` as c
            SET `fkid` = CONCAT(c.school_inep_id_fk,';',c.id)
            WHERE true;
            UPDATE `instructor_teaching_data` as c
            SET `fkid` = CONCAT(c.school_inep_id_fk,';',c.id)
            WHERE true;
            UPDATE `instructor_variable_data` as c
            SET `fkid` = CONCAT(c.school_inep_id_fk,';',c.id)
            WHERE true;
            UPDATE `student_documents_and_address` as c
            SET `fkid` = CONCAT(c.school_inep_id_fk,';',c.id)
            WHERE true;
            UPDATE `student_enrollment` as c
            SET `fkid` = CONCAT(c.school_inep_id_fk,';',c.id)
            WHERE true;
            UPDATE `student_identification` as c
            SET `fkid` = CONCAT(c.school_inep_id_fk,';',c.id)
            WHERE true;";
			yii::app()->db->schema->commandBuilder->createSqlCommand($updateFKID)->query();

			$studentArray = [];
			foreach ($students as $student) {
				$sfkid = $student->fkid;
				$studentArray[$sfkid] = [];
				$studentArray[$sfkid]['attributes'] = [];
				$studentArray[$sfkid]['documents'] = [];
				$studentArray[$sfkid]['documents']['attributes'] = [];
				$documents = $student->documentsFk;
				$studentArray[$sfkid]['documents']['attributes'] = $documents->attributes;
				$studentArray[$sfkid]['attributes'] = $student->attributes;
			}
			$json['student'] = $studentArray;


			$classroomArray = [];
			foreach ($classrooms as $classroom) {
				$cfkid = $classroom->fkid;
				$classroomArray[$cfkid] = $classroomArray[$cfkid]['attributes'] = [];
				$classroomArray[$cfkid]['attributes'] = $classroom->attributes;

				$classroomArray[$cfkid]['classes'] = [];
				$classes = $classroom->classes;
				$classesArray = [];
				foreach ($classes as $class) {
					$csfkid = $class->fkid;
					$classesArray[$csfkid] = [];
					$classesArray[$csfkid]['attributes'] = [];
					$classesArray[$csfkid]['attributes'] = $class->attributes;

					$classesArray[$csfkid]['faults'] = [];
					$faults = $class->classFaults;
					$faultArray = [];
					foreach ($faults as $fault) {
						$ffkid = $fault->fkid;
						$faultArray[$ffkid] = [];
						$faultArray[$ffkid]['attributes'] = [];
						$faultArray[$ffkid]['attributes'] = $fault->attributes;
					}
					$classesArray[$csfkid]['faults'] = $faultArray;
				}
				$classroomArray[$cfkid]['classes'] = $classesArray;


				$classroomArray[$cfkid]['classboards'] = [];
				$classBoards = $classroom->classBoards;
				$classBoardArray = [];
				foreach ($classBoards as $classboard) {
					$cbfkid = $classboard->fkid;
					$classBoardArray[$cbfkid] = [];
					$classBoardArray[$cbfkid]['attributes'] = [];
					$classBoardArray[$cbfkid]['attributes'] = $classboard->attributes;
				}
				$classroomArray[$cfkid]['classboards'] = $classBoardArray;


				$classroomArray[$cfkid]['enrollments'] = [];
				$enrollments = $classroom->studentEnrollments;
				$enrollmentsArray = [];
				foreach ($enrollments as $enrollment) {
					$efkid = $enrollment->fkid;
					$enrollmentsArray[$efkid] = [];
					$enrollmentsArray[$efkid]['attributes'] = [];
					$enrollmentsArray[$efkid]['attributes'] = $enrollment->attributes;
				}
				$classroomArray[$cfkid]['enrollments'] = $enrollmentsArray;
			}
			$json['classroom'] = $classroomArray;

			$json_encode = json_encode($json);
			$date = date('d_m_Y H_i_s');
			$zipName = 'ArquivoSincronizacaoTAG_' . $school . '_' . $date . '.zip';
			$tempArchiveZip = new ZipArchive;
			$tempArchiveZip->open($zipName, ZipArchive::CREATE);
			$tempArchiveZip->addFromString($school . "_" . $date . ".json", $json_encode);
			$tempArchiveZip->close();


			if (file_exists($zipName)) {
				header('Content-type: application/zip');
				header('Content-Disposition: attachment; filename="' . $zipName . '"');
				readfile($zipName);
				unlink($zipName);
			}
		}

		/**
		 *
		 * @param CActiveRecord $model
		 * @param array $attributes
		 * @return CDbCommand
		 */
		private function createMultipleInsertOnDuplicateKeyUpdate($model, $attributes) {
			if (count($attributes) > 0) {
				$builder = Yii::app()->db->schema->commandBuilder;
				$command = $builder->createMultipleInsertCommand($model->tableName(), $attributes);
				$sql = $command->getText();

				$values = [];
				$valuesUpdate = " ON DUPLICATE KEY UPDATE ";
				$i = 0;
				foreach ($model->attributes as $name => $value) {
					if ($i != 0) {
						$valuesUpdate .= ",";
					}
					$valuesUpdate .= " `" . $name . "`=VALUES(`" . $name . "`)";
					$i = 1;
				}
				$sql .= $valuesUpdate . ";";
				$i = 0;

				foreach ($attributes as $value) {
					foreach ($value as $name => $val) {
						$values[$name . "_" . $i] = $val;
					}
					$i++;
				}

				return Yii::app()->db->createCommand($sql)->bindValues($values);
			} else {
				return Yii::app()->db->createCommand("select 1+1;");
			}
		}

		/**
		 * php.ini
		 * @warning max_post_size = 200M
		 * @warning upload_max_filesize = 200M
		 *
		 * nginx
		 * @warning client_max_body_size 200M;
		 * @warning fastcgi_read_timeout 300;
		 */
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
			$students = isset($json['student']) ? $json['student'] : [];
			//student[fkid][attributes]
			//student[fkid][documents][attributes]
			$classrooms = isset($json['classroom']) ? $json['classroom'] : [];
			//classroom[fkid][attributes]
			//classroom[fkid][classes][fkid][attributes]
			//classroom[fkid][classes][fkid][faults][fkid][attributes]
			//classroom[fkid][classboards][fkid][attributes]
			//classroom[fkid][enrollments][fkid][attributes]
			$exit = "";


			$studentValues = [];
			$studentModel = StudentIdentification::model();
			foreach ($students as $student) {
				$myStudent = $studentModel->findByAttributes(['fkid' => $student['attributes']['fkid']]);
				if ($myStudent === NULL) {
					$student['attributes']['id'] = NULL;
				} else {
					$student['attributes']['id'] = $myStudent->id;
				}
				array_push($studentValues, $student['attributes']);
//            echo $student['attributes']['id']. '-' .$student['attributes']['fkid'].'<br>';
			}
			$this->createMultipleInsertOnDuplicateKeyUpdate($studentModel, $studentValues)->query();

			$documentValues = [];
			$documentModel = StudentDocumentsAndAddress::model();
			foreach ($students as $student) {
				$document = $student['documents'];
				$myDocument = $documentModel->findByAttributes(['fkid' => $document['attributes']['fkid']]);
				if ($myDocument === NULL) {
					$student['documents']['attributes']['id'] = NULL;
				} else {
					$document['attributes']['id'] = $myDocument->id;
				}
				array_push($documentValues, $document['attributes']);
			}
			$this->createMultipleInsertOnDuplicateKeyUpdate($documentModel, $documentValues)->query();


			//classroom[fkid][attributes]
			$classroomValues = [];
			$classroomModel = Classroom::model();
			foreach ($classrooms as $classroom) {
				$myClassroom = $classroomModel->findByAttributes(['fkid' => $classroom['attributes']['fkid']]);
				if ($myClassroom === NULL) {
					$classroom['attributes']['id'] = NULL;
				} else {
					$classroom['attributes']['id'] = $myClassroom->id;
				}
				array_push($classroomValues, $classroom['attributes']);
			}
			$this->createMultipleInsertOnDuplicateKeyUpdate($classroomModel, $classroomValues)->query();


			//classroom[fkid][classes][fkid][attributes]
			$classesValues = [];
			$classesModel = Classes::model();
			foreach ($classrooms as $classroom) {
				foreach ($classroom['classes'] as $class) {
					$myClass = $classesModel->findByAttributes(['fkid' => $class['attributes']['fkid']]);
					if ($myClass === NULL) {
						$myClassroom = $classroomModel->findByAttributes(['fkid' => $classroom['attributes']['fkid']]);
						$class['attributes']['id'] = NULL;
						$class['attributes']['classroom_fk'] = $myClassroom->id;
					} else {
						$class['attributes']['id'] = $myClass->id;
						$class['attributes']['classroom_fk'] = $myClass->classroom_fk;
					}
					array_push($classesValues, $class['attributes']);
				}
			}
			$this->createMultipleInsertOnDuplicateKeyUpdate($classesModel, $classesValues)->query();


			//classroom[fkid][enrollments][fkid][attributes]
			$enrollmentsValues = [];
			$enrollmentsModel = StudentEnrollment::model();
			foreach ($classrooms as $classroom) {
				foreach ($classroom['enrollments'] as $enrollment) {
					$myEnrollment = $enrollmentsModel->findByAttributes(['fkid' => $enrollment['attributes']['fkid']]);
					if ($myEnrollment === NULL) {
						$myClassroom = $classroomModel->findByAttributes(['fkid' => $classroom['attributes']['fkid']]);
						$studentFkid = NULL;
						foreach ($students as $student) {
							$id = explode(';', $student['attributes']['fkid'])[1];
							if ($enrollment['attributes']['student_fk'] == $id) {
								$studentFkid = $student['attributes']['fkid'];
								break;
							}
						}
						$myStudent = $studentModel->findByAttributes(['fkid' => $studentFkid]);
						$enrollment['attributes']['id'] = NULL;
						$enrollment['attributes']['classroom_fk'] = $myClassroom->id;
						$enrollment['attributes']['student_fk'] = $myStudent->id;
					} else {
						$enrollment['attributes']['id'] = $myEnrollment->id;
						$enrollment['attributes']['classroom_fk'] = $myEnrollment->classroom_fk;
						$enrollment['attributes']['student_fk'] = $myEnrollment->student_fk;
					}
					array_push($enrollmentsValues, $enrollment['attributes']);
				}
			}
			$this->createMultipleInsertOnDuplicateKeyUpdate($enrollmentsModel, $enrollmentsValues)->query();


			//classroom[fkid][classes][fkid][faults][fkid][attributes]
			$faultsValues = [];
			$faultsModel = ClassFaults::model();
			foreach ($classrooms as $classroom) {
				foreach ($classroom['classes'] as $class) {
					foreach ($class['faults'] as $fault) {
						$myFault = $faultsModel->findByAttributes(['fkid' => $fault['attributes']['fkid']]);
						if ($myFault === NULL) {
							$myClass = $classesModel->findByAttributes(['fkid' => $class['attributes']['fkid']]);
							$enrollmentFkid = NULL;
							foreach ($classroom['enrollments'] as $enrollment) {
								$id = $enrollment['attributes']['student_fk'];
								if ($fault['attributes']['student_fk'] == $id) {
									$enrollmentFkid = $enrollment['attributes']['fkid'];
									break;
								}
							}
							$myEnrollment = $enrollmentsModel->findByAttributes(['fkid' => $enrollmentFkid]);
							$fault['attributes']['id'] = NULL;
							$fault['attributes']['class_fk'] = $myClass->id;
							$fault['attributes']['student_fk'] = $myEnrollment->student_fk;
						} else {
							$fault['attributes']['id'] = $myFault->id;
							$fault['attributes']['class_fk'] = $myFault->class_fk;
							$fault['attributes']['student_fk'] = $myFault->student_fk;
						}
						array_push($faultsValues, $fault['attributes']);
					}
				}
			}
			$this->createMultipleInsertOnDuplicateKeyUpdate($faultsModel, $faultsValues)->query();


			//classroom[fkid][classboards][fkid][attributes]
			/**
			 * Precisa enviar junto o Instrutor
			 * $classboardsValues = [];
			 * $classboardsModel = ClassBoard::model();
			 * foreach ($classrooms as $classroom) {
			 * foreach ($classroom['classboards'] as $classboard) {
			 * $myClassboard = $classboardsModel->findByAttributes(['fkid' => $classboard['attributes']['fkid']]);
			 * if ($myClassboard === null) {
			 * $myClassroom = $classroomModel->findByAttributes(['fkid' => $classroom['attributes']['fkid']]);
			 * $classboard['attributes']['id'] = null;
			 * $classboard['attributes']['classroom_fk'] = $myClassroom->id;
			 * } else {
			 * $classboard['attributes']['id'] = $myClassboard->id;
			 * $classboard['attributes']['classroom_fk'] = $myClassboard->classroom_fk;
			 * }
			 * array_push($classboardsValues, $classboard['attributes']);
			 * }
			 * }
			 * $this->createMultipleInsertOnDuplicateKeyUpdate($classboardsModel, $classboardsValues)->query();
			 * */
			$time2 = time();
			echo $time2 - $time1;
			echo "<hr>";
			echo $exit;
		}

		/**
		 * Update de database.
		 *
		 * @return {Redirect} Return the index page with a FlashMessage
		 *
		 */
		public function actionUpdateDB() {
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

			foreach ($dirFiles as $fileName) {

				if ($fileName != '.' && $fileName != '..' && $fileName != 'readme' && $fileName != '_version' && substr("abcdef", -1) != '~') {

					if ($version != "" && $version < $fileName) {
						$file = $fm->open($updateDir . $fileName);
						$sql = "";
						while (TRUE) {
							$fileLine = fgets($file);
							$sql .= $fileLine;
							if ($fileLine == NULL) {
								break;
							}
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
			if ($count == 0) {
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
			/* Yii::import('ext.dumpDB.dumpDB');
			  $dumper = new dumpDB();
			  $dump = $dumper->getDump(false);

			  $fileDir = Yii::app()->basePath . '/backup/' . date('Y-m-d') . '.sql';

			  Yii::import('ext.FileManager.fileManager');
			  $fm = new fileManager();
			  $result = $fm->write($fileDir, $dump);

			  if ($return) {
			  if ($result) {
			  Yii::app()->user->setFlash('success', Yii::t('default', 'Backup efetuado com Sucesso!'));
			  } else {
			  Yii::app()->user->setFlash('error', Yii::t('default', 'Backup falhou!'));
			  }
			  Yii::app()->controller->redirect('?r=admin/index');
			  }
			  return $result; */
			return 0;
		}

		/**
		 * Generate some Data and a DataFile.
		 * @param boolean $file - Defaults True
		 * @return redirecrToData - Return to the Data page.
		 */
		public function actionData($file = TRUE) {
			$data = [];
			//Turma
			$where = "school_year = " . date('Y');
			$classrooms = Classroom::model()->count($where);
			$data['classroom'] = $classrooms;

        //Identificação do Professor
        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->join = 'LEFT JOIN instructor_teaching_data ita ON ita.instructor_fk = t.id ';
        $criteria->join .= 'LEFT JOIN classroom c ON c.id = ita.classroom_id_fk';
        $criteria->condition = 'c.school_year = :value';
        $criteria->params = array(":value" => date('Y'));
        $criteria->group = 't.id';
        $instructors = InstructorIdentification::model()->count($criteria);
        $data['instructors'] = $instructors;

        //Identificação do Aluno
        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->join = 'LEFT JOIN student_enrollment se ON se.student_fk = t.id ';
        $criteria->join .= 'LEFT JOIN classroom c ON c.id = se.classroom_fk';
        $criteria->condition = 'c.school_year = :value';
        $criteria->params = array(":value" => date('Y'));
        $criteria->group = 't.id';
        $students = StudentIdentification::model()->count($criteria);
        $data['students'] = $students;

        //Matricula do Aluno
        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->join .= 'LEFT JOIN classroom c ON c.id = t.classroom_fk';
        $criteria->condition = 'c.school_year = :value';
        $criteria->params = array(":value" => date('Y'));
        $enrollments = StudentEnrollment::model()->count($criteria);
        $data['enrollments'] = $enrollments;

        if ($file) {
            $fileDir = Yii::app()->basePath . '/backup/Data/' . date('Y-m-d') . '.dat';
            $dataText = json_encode($data);

            Yii::import('ext.FileManager.fileManager');
            $fm = new fileManager();
            $result = $fm->write($fileDir, $dataText);
            if ($result) {
                Yii::app()->user->setFlash('success', Yii::t('default', 'Dados salvos com Sucesso!'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('default', 'Não foi possível salvar os dados!'));
            }
        }

        $this->render('data', array('data' => $data));
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

		/**
		 * Generate the ExportFile.
		 * @return redirecrToIndex - Return to the index page with a FlashMenssage
		 */
		public function actionExport() {
			$export = "";

			//Escolas
			$schools = SchoolIdentification::model()->findAll();
			foreach ($schools as $key => $school) {
				$attributes = $school->attributes;
				// Remove
				array_pop($attributes);
				array_pop($attributes);
				array_pop($attributes);
				// Remove
				array_pop($attributes);
				$export .= implode('|', $attributes);
				$export .= "\n";
			}

			//Estrutura Escolar
			$schoolsStructure = SchoolStructure::model()->findAll();
			foreach ($schoolsStructure as $key => $schoolStructure) {
				$export .= implode('|', $schoolStructure->attributes);
				$export .= "\n";
			}

			//Turma
			$where = "school_year = " . date('Y');
			$classrooms = Classroom::model()->findAll($where);
			foreach ($classrooms as $key => $classroom) {
				$attributes = $classroom->attributes;

				// Remove fkid
				array_pop($attributes);
				//Remove create_time
				array_pop($attributes);
				//Remove Turno
				array_pop($attributes);
				//Remove Ano
				array_pop($attributes);

				$export .= implode('|', $attributes);
				$export .= "\n";
			}

        //Identificação do Professor
        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->join = 'LEFT JOIN instructor_teaching_data ita ON ita.instructor_fk = t.id ';
        $criteria->join .= 'LEFT JOIN classroom c ON c.id = ita.classroom_id_fk';
        $criteria->condition = 'c.school_year = :value';
        $criteria->params = array(":value" => date('Y'));
        $criteria->group = 't.id';
        $instructors = InstructorIdentification::model()->findAll($criteria);
        foreach ($instructors as $key => $instructor) {

				//Dados do Professor
				$instructorId = $instructor->attributes;
				// Remove fkid
				array_pop($instructorId);
				$export .= implode('|', $instructorId);
				$export .= "\n";

				//Documentos do Professor
				$instructorDocs = InstructorDocumentsAndAddress::model()->findByPk($instructor->id);
				$instructorDocsAtt = $instructorDocs->attributes;
				// Remove fkid
				array_pop($instructorDocsAtt);
				$export .= implode('|', $instructorDocsAtt);
				$export .= "\n";

				//Variáveis de Encino do Professor
				$instructorVariables = InstructorVariableData::model()->findByPk($instructor->id);
				$export .= implode('|', $instructorVariables->attributes);
				$export .= "\n";
				//$export .= "50|\n";

            //Dados de Docência do Professor
            $criteria->select = 't.*';
            $criteria->condition = 't.instructor_fk = :value';
            $criteria->params = array(":value" => $instructor->id);
            $instructorTeachingDatas = InstructorTeachingData::model()->findAll($criteria);
            foreach ($instructorTeachingDatas as $itd) {
                $attributes = $itd->attributes;
                //Remove fkid
                array_pop($attributes);
                //Remove Id
                array_pop($attributes);
                $export .= implode('|', $attributes);
                $export .= "\n";
            }
        }

        //Identificação do Aluno
        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->join = 'LEFT JOIN student_enrollment se ON se.student_fk = t.id ';
        $criteria->join .= 'LEFT JOIN classroom c ON c.id = se.classroom_fk';
        $criteria->condition = 'c.school_year = :value '
            . 'AND t.send_year <= :year';
        $criteria->params = array(":value" => date('Y'), ":year" => date('Y'));
        $criteria->group = 't.id';
        $students = StudentIdentification::model()->findAll($criteria);
        foreach ($students as $key => $student) {
            $attributes = $student->attributes;
            // Remove fkid
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            array_pop($attributes);
            // Remove
            array_pop($attributes);
            $export .= implode('|', $attributes);
            $export .= "\n";

				//Documentos do Aluno
				$studentDocs = StudentDocumentsAndAddress::model()->findByPk($student->id);
				$attributes = $studentDocs->attributes;
				// Remove
				array_pop($attributes);
				array_pop($attributes);
				array_pop($attributes);
				array_pop($attributes);
				array_pop($attributes);
				array_pop($attributes);
				array_pop($attributes);
				array_pop($attributes);
				array_pop($attributes);
				// Remove
				array_pop($attributes);;
				$export .= implode('|', $attributes);
				$export .= "\n";

            //Matricula do Aluno
            $criteria->select = 't.*';
            $criteria->condition = 't.student_fk = :value';
            $criteria->params = array(":value" => $student->id);
            $enrollments = StudentEnrollment::model()->findAll($criteria);
            foreach ($enrollments as $enrollment) {
                $attributes = $enrollment->attributes;
                //Remove create_time
                array_pop($attributes);
                array_pop($attributes);
                array_pop($attributes);
                array_pop($attributes);
                array_pop($attributes);
                array_pop($attributes);
                //Remove Id
                array_pop($attributes);
                $export .= implode('|', $attributes);
                $export .= "\n";
            }
        }

        $fileDir = Yii::app()->basePath . '/export/' . date('Y_') . Yii::app()->user->school . '.TXT';

        Yii::import('ext.FileManager.fileManager');
        $fm = new fileManager();
        $result = $fm->write($fileDir, $export);

			if ($result) {
				Yii::app()->user->setFlash('success', Yii::t('default', 'Exportação Concluida com Sucesso.<br><a href="/admin/downloadexportfile" class="btn btn-mini" target="_blank"><i class="icon-download-alt"></i>Clique aqui para fazer o Download do arquivo de exportação!!!</a>'));
			} else {
				Yii::app()->user->setFlash('error', Yii::t('default', 'Houve algum erro na Exportação.'));
			}

			$this->render('index');
		}

		public function actionDownloadExportFile() {
			$fileDir = Yii::app()->basePath . '/export/' . date('Y_') . Yii::app()->user->school . '.TXT';
			if (file_exists($fileDir)) {
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="' . basename($fileDir) . '"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($fileDir));
				readfile($fileDir);
			} else {
				Yii::app()->user->setFlash('error', Yii::t('default', 'Arquivo de exportação não encontrado!!! Tente exportar novamente.'));
				$this->render('index');
			}

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

		public function actionImport() {
			set_time_limit(0);
			ignore_user_abort();
			$path = Yii::app()->basePath;
			//Se não passar parametro, o valor será predefinido
			if (empty($_FILES['file']['name'])) {
				$fileDir = $path . '/import/IMPORTAR.TXT';
			} else {
				$myfile = $_FILES['file'];
				$uploadfile = $path . '/import/' . basename($myfile['name']);
				move_uploaded_file($myfile['tmp_name'], $uploadfile);
				$fileDir = $uploadfile;
			}


			$mode = 'r';

			//Abre o arquivo
			$file = fopen($fileDir, $mode);
			if ($file == FALSE) {
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
			while (TRUE) {
				//Próxima linha do arquivo
				$fileLine = fgets($file);
				if ($fileLine == NULL) {
					break;
				}

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
				$registerLines[$regType][$lineCount[$regType]++] = $lineFields;
			}
			//Pega os valores para preencher o insert.
			$values = $this->getInsertValues($registerLines);
			$instructorInepIds = $values['instructor'];
			$insertValue = $values['insert'];

			//Pega o código SQL com os valores passados
			$str_fields = $this->getInsertSQL($insertValue);

			set_time_limit(0);
			ignore_user_abort();
			Yii::app()->db->createCommand("SET foreign_key_checks = 0;")->query();
			Yii::app()->db->createCommand(utf8_encode($str_fields['00']))->query();
			Yii::app()->db->createCommand(utf8_encode($str_fields['10']))->query();
			Yii::app()->db->createCommand(utf8_encode($str_fields['20']))->query();
			Yii::app()->db->createCommand(utf8_encode($str_fields['30']))->query();
			Yii::app()->db->createCommand(utf8_encode($str_fields['40']))->query();
//        Solução paliativa para evitar explosão
			Yii::app()->db->createCommand(utf8_encode($str_fields['50']))->query();
			Yii::app()->db->createCommand(utf8_encode($str_fields['51']))->query();
			Yii::app()->db->createCommand(utf8_encode($str_fields['60']))->query();
			Yii::app()->db->createCommand(utf8_encode($str_fields['80']))->query();
			Yii::app()->db->createCommand(utf8_encode($str_fields['70']))->query();
			Yii::app()->db->createCommand("SET foreign_key_checks = 1;")->query();
			fclose($file);
			set_time_limit(30);


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

			$auth->createOperation('updateClasses', 'update a Classes');

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

			$role->addChild('updateClasses');

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
		/**
		 * Transforma as linhas em valores a serem inseridos no SQL
		 *
		 * @param type $registerLines
		 * @return array ['insert'] = values do SQL; ['instructor'] = INEPID dos instrutores
		 */
		private function getInsertValues($registerLines) {
			foreach ($registerLines as $regType => $lines):
				$insertValue[$regType] = "";

				$totalLines = count($lines) - 1;

				$isSchoolIdentification = ($regType == "00");
				$isSchoolStructure = ($regType == "10");

				$isRegInstructorIdentification = ($regType == "30");
				$isRegInstructorDocumentsAndAddress = ($regType == "40");
				$isRegInstructorVariableData = ($regType == "50");
				$isRegInstructorTeachingData = ($regType == "51");

				$isStudentIdentification = ($regType == "60");
				$isStudentDocumentsAndAddress = ($regType == "70");
				$isStudentEnrollment = ($regType == "80");
				if ($isRegInstructorIdentification) {
					$instructorInepIds[] = '';
				}
				for ($line = 0; $line <= $totalLines; $line++) {
					$totalColumns = count($lines[$line]) - 2;
					for ($column = 0; $column <= $totalColumns; $column++) {
						$value = $lines[$line][$column];
						$withoutcomma = FALSE;

						if ($column == 0) {
							$insertValue[$regType] .= "(";
						} else if ($regType != 00 && $regType != 10 && $regType != 51 && $regType != 80 && $column == 3) {
							$value = "null";
						} else if ($regType == 20 && $column == 4) {
							$value = str_replace("º", "", $value);
						} else if($regType == 50 && $column > 41 && $column < 44){
							continue;
						} else {
							if ($regType == '51' && $column == 3) {
								$withoutcomma = TRUE;
								$value = "(SELECT id FROM instructor_identification WHERE BINARY inep_id = BINARY " . $lines[$line][2] . " LIMIT 0,1)";
							} else if ($regType == '51' && $column == 5) {
								$withoutcomma = TRUE;
								$value = "(SELECT id FROM classroom WHERE BINARY inep_id = BINARY " . $lines[$line][4] . " LIMIT 0,1)";
							} else if ($regType == '80' && $column == 3) {
								$withoutcomma = TRUE;
								$value = "(SELECT id FROM student_identification WHERE BINARY inep_id = BINARY " . $lines[$line][2] . " LIMIT 0,1)";
							} else if ($regType == '80' && $column == 5) {
								$withoutcomma = TRUE;
								$value = "(SELECT id FROM classroom WHERE BINARY inep_id = BINARY " . $lines[$line][4] . " LIMIT 0,1)";
							}
						}

						if ($isSchoolIdentification && $column == 19) {
							$value = $value[7] . $value[8];
						}

						if ($isRegInstructorIdentification) {
							if ($column == 2) {
								$instructorInepIds[$line] = $value;
							}
							if ($column == 10) {
								$withoutcomma = TRUE;
								$value = "'0','" . $value . "',null";
							}
						}

						if ($isRegInstructorTeachingData && $totalColumns == 19) {
							$lines[$line][sizeof($lines[$line]) - 1] = 'null';
							$totalColumns++;
						}

						if (($isSchoolStructure && $totalColumns == 105) || ($isRegInstructorIdentification && $totalColumns == 22) || ($isRegInstructorDocumentsAndAddress && $totalColumns == 11) || ($isStudentIdentification && $totalColumns == 37) || ($isStudentEnrollment && $totalColumns == 22) || ($isStudentDocumentsAndAddress && $totalColumns == 27)) {
							$lines[$line][sizeof($lines[$line])] = 'null';
							$totalColumns++;
						}


						$value = ($value == 'null' || $withoutcomma) ? $value : "'$value'";
						if ($column + 1 > $totalColumns) {
							if ($regType == 20) {
								$value .= ',' . 2015;
							}
							$insertValue[$regType] .= $value;
							$insertValue[$regType] .= ($line == $totalLines) ? ")" : "),\n";

                    } else {
                        $insertValue[$regType] .= $value . ", ";
                    }
                }
            }
        endforeach;
        $return = array('insert' => $insertValue, 'instructor' => $instructorInepIds);
        return $return;
    }

		public function areThereByModalitie($sql){
			$people_by_modalitie = Yii::app()->db->createCommand($sql)->queryAll();
			$modalities_regular	= false;
			$modalities_especial = false;
			$modalities_eja = false;
			$modalities_professional = false;
			foreach ($people_by_modalitie as $key => $item) {
				switch ($item['modalities']) {

					case '1':
						if($item['number_of'] > '0')
							$modalities_regular = true;
						break;
					case '2':
						if($item['number_of'] > '0')
							$modalities_especial = true;
						break;

					case '3':
						if($item['number_of'] > '0')
							$modalities_eja = true;
						break;

					case '4':
						if($item['number_of'] > '0')
							$modalities_professional = true;
						break;
				}
			}
			return array("modalities_regular" => $modalities_regular,
				"modalities_especial" => $modalities_especial,
				"modalities_eja" => $modalities_eja,
				"modalities_professional" => $modalities_professional);
		}
		private function getInsertSQL($insertValue) {
			$str_fields = [];
			foreach ($insertValue as $regType => $lines):
				switch ($regType) {
					case '00': {
						$str_fields[$regType] = "INSERT INTO `school_identification`(`register_type`,`inep_id`,`manager_cpf`,`manager_name`,`manager_role`,`manager_email`,`situation`,`initial_date`,`final_date`,`name`,`latitude`,`longitude`,`cep`,`address`,`address_number`,`address_complement`,`address_neighborhood`,`edcenso_uf_fk`,`edcenso_city_fk`,`edcenso_district_fk`,`ddd`,`phone_number`,`public_phone_number`,`other_phone_number`,`fax_number`,`email`,`edcenso_regional_education_organ_fk`,`administrative_dependence`,`location`,`private_school_category`,`public_contract`,`private_school_business_or_individual`,`private_school_syndicate_or_association`,`private_school_ong_or_oscip`,`private_school_non_profit_institutions`,`private_school_s_system`,`private_school_maintainer_cnpj`,`private_school_cnpj`,`offer_or_linked_unity`,`inep_head_school`,`ies_code`) VALUES " . $lines . " ON DUPLICATE KEY UPDATE register_type = register_type;";
						break;
					}
					case '10': {
						$str_fields[$regType] = "INSERT INTO `school_structure`(`register_type`,`school_inep_id_fk`,`operation_location_building`,`operation_location_temple`,`operation_location_businness_room`,`operation_location_instructor_house`,`operation_location_other_school_room`,`operation_location_barracks`,`operation_location_socioeducative_unity`,`operation_location_prison_unity`,`operation_location_other`,`building_occupation_situation`,`shared_building_with_school`,`shared_school_inep_id_1`,`shared_school_inep_id_2`,`shared_school_inep_id_3`,`shared_school_inep_id_4`,`shared_school_inep_id_5`,`shared_school_inep_id_6`,`consumed_water_type`,`water_supply_public`,`water_supply_artesian_well`,`water_supply_well`,`water_supply_river`,`water_supply_inexistent`,`energy_supply_public`,`energy_supply_generator`,`energy_supply_other`,`energy_supply_inexistent`,`sewage_public`,`sewage_fossa`,`sewage_inexistent`,`garbage_destination_collect`,`garbage_destination_burn`,`garbage_destination_throw_away`,`garbage_destination_recycle`,`garbage_destination_bury`,`garbage_destination_other`,`dependencies_principal_room`,`dependencies_instructors_room`,`dependencies_secretary_room`,`dependencies_info_lab`,`dependencies_science_lab`,`dependencies_aee_room`,`dependencies_indoor_sports_court`,`dependencies_outdoor_sports_court`,`dependencies_kitchen`,`dependencies_library`,`dependencies_reading_room`,`dependencies_playground`,`dependencies_nursery`,`dependencies_outside_bathroom`,`dependencies_inside_bathroom`,`dependencies_child_bathroom`,`dependencies_prysical_disability_bathroom`,`dependencies_physical_disability_support`,`dependencies_bathroom_with_shower`,`dependencies_refectory`,`dependencies_storeroom`,`dependencies_warehouse`,`dependencies_auditorium`,`dependencies_covered_patio`,`dependencies_uncovered_patio`,`dependencies_student_accomodation`,`dependencies_instructor_accomodation`,`dependencies_green_area`,`dependencies_laundry`,`dependencies_none`,`classroom_count`,`used_classroom_count`,`equipments_tv`,`equipments_vcr`,`equipments_dvd`,`equipments_satellite_dish`,`equipments_copier`,`equipments_overhead_projector`,`equipments_printer`,`equipments_stereo_system`,`equipments_data_show`,`equipments_fax`,`equipments_camera`,`equipments_computer`,`equipments_multifunctional_printer`,`administrative_computers_count`,`student_computers_count`,`internet_access`,`bandwidth`,`employees_count`,`feeding`,`aee`,`complementary_activities`,`modalities_regular`,`modalities_especial`,`modalities_eja`,`modalities_professional`,`basic_education_cycle_organized`,`different_location`,`sociocultural_didactic_material_none`,`sociocultural_didactic_material_quilombola`,`sociocultural_didactic_material_native`,`native_education`,`native_education_language_native`,`native_education_language_portuguese`,`edcenso_native_languages_fk`,`brazil_literate`,`open_weekend`,`pedagogical_formation_by_alternance`) VALUES " . $lines . " ON DUPLICATE KEY UPDATE register_type = register_type;";
						break;
					}
					case '20': {
						$str_fields[$regType] = "INSERT INTO `classroom`(`register_type`,`school_inep_fk`,`inep_id`,`id`,`name`,`pedagogical_mediation_type`,`initial_hour`,`initial_minute`,`final_hour`,`final_minute`,`week_days_sunday`,`week_days_monday`,`week_days_tuesday`,`week_days_wednesday`,`week_days_thursday`,`week_days_friday`,`week_days_saturday`,`assistance_type`,`mais_educacao_participator`,`complementary_activity_type_1`,`complementary_activity_type_2`,`complementary_activity_type_3`,`complementary_activity_type_4`,`complementary_activity_type_5`,`complementary_activity_type_6`,`aee_braille_system_education`,`aee_optical_and_non_optical_resources`,`aee_mental_processes_development_strategies`,`aee_mobility_and_orientation_techniques`,`aee_libras`,`aee_caa_use_education`,`aee_curriculum_enrichment_strategy`,`aee_soroban_use_education`,`aee_usability_and_functionality_of_computer_accessible_education`,`aee_teaching_of_Portuguese_language_written_modality`,`aee_strategy_for_school_environment_autonomy`,`modality`,`edcenso_stage_vs_modality_fk`,`edcenso_professional_education_course_fk`,`discipline_chemistry`,`discipline_physics`,`discipline_mathematics`,`discipline_biology`,`discipline_science`,`discipline_language_portuguese_literature`,`discipline_foreign_language_english`,`discipline_foreign_language_spanish`,`discipline_foreign_language_franch`,`discipline_foreign_language_other`,`discipline_arts`,`discipline_physical_education`,`discipline_history`,`discipline_geography`,`discipline_philosophy`,`discipline_social_study`,`discipline_sociology`,`discipline_informatics`,`discipline_professional_disciplines`,`discipline_special_education_and_inclusive_practices`,`discipline_sociocultural_diversity`,`discipline_libras`,`discipline_pedagogical`,`discipline_religious`,`discipline_native_language`,`discipline_others`,`school_year`) VALUES " . $lines . " ON DUPLICATE KEY UPDATE register_type = register_type;";
						break;
					}
					case '30': {
						$str_fields[$regType] = "INSERT INTO `instructor_identification`(`register_type`,`school_inep_id_fk`,`inep_id`,`id`,`name`,`email`,`nis`,`birthday_date`,`sex`,`color_race`,`filiation`,`filiation_1`,`filiation_2`,`nationality`,`edcenso_nation_fk`,`edcenso_uf_fk`,`edcenso_city_fk`,`deficiency`,`deficiency_type_blindness`,`deficiency_type_low_vision`,`deficiency_type_deafness`,`deficiency_type_disability_hearing`,`deficiency_type_deafblindness`,`deficiency_type_phisical_disability`,`deficiency_type_intelectual_disability`,`deficiency_type_multiple_disabilities`) VALUES " . $lines . " ON DUPLICATE KEY UPDATE register_type = register_type;";
						break;
					}
					case '40': {
						$str_fields[$regType] = "INSERT INTO `instructor_documents_and_address`(`register_type`,`school_inep_id_fk`,`inep_id`,`id`,`cpf`,`area_of_residence`,`cep`,`address`,`address_number`,`complement`,`neighborhood`,`edcenso_uf_fk`,`edcenso_city_fk`) VALUES " . $lines . " ON DUPLICATE KEY UPDATE register_type = register_type;";
						break;
					}
					case '50': {
						$str_fields[$regType] = "INSERT INTO `instructor_variable_data`(`register_type`,`school_inep_id_fk`,`inep_id`,`id`,`scholarity`,`high_education_situation_1`,`high_education_formation_1`,`high_education_course_code_1_fk`,`high_education_initial_year_1`,`high_education_final_year_1`,`high_education_institution_code_1_fk`,`high_education_situation_2`,`high_education_formation_2`,`high_education_course_code_2_fk`,`high_education_initial_year_2`,`high_education_final_year_2`,`high_education_institution_code_2_fk`,`high_education_situation_3`,`high_education_formation_3`,`high_education_course_code_3_fk`,`high_education_initial_year_3`,`high_education_final_year_3`,`high_education_institution_code_3_fk`,`post_graduation_specialization`,`post_graduation_master`,`post_graduation_doctorate`,`post_graduation_none`,`other_courses_nursery`,`other_courses_pre_school`,`other_courses_basic_education_initial_years`,`other_courses_basic_education_final_years`,`other_courses_high_school`,`other_courses_education_of_youth_and_adults`,`other_courses_special_education`,`other_courses_native_education`,`other_courses_field_education`,`other_courses_environment_education`,`other_courses_human_rights_education`,`other_courses_sexual_education`,`other_courses_child_and_teenage_rights`,`other_courses_ethnic_education`,`other_courses_other`,`other_courses_none`) VALUES " . $lines . " ON DUPLICATE KEY UPDATE register_type = register_type;";
						break;
					}

					case '51': {
						$str_fields[$regType] = "INSERT INTO `instructor_teaching_data`(`register_type`,`school_inep_id_fk`,`instructor_inep_id`,`instructor_fk`,`classroom_inep_id`,`classroom_id_fk`,`role`,`contract_type`,`discipline_1_fk`,`discipline_2_fk`,`discipline_3_fk`,`discipline_4_fk`,`discipline_5_fk`,`discipline_6_fk`,`discipline_7_fk`,`discipline_8_fk`,`discipline_9_fk`,`discipline_10_fk`,`discipline_11_fk`,`discipline_12_fk`,`discipline_13_fk`) VALUES " . $lines . " ON DUPLICATE KEY UPDATE register_type = register_type;";
						break;
					}
					case '60': {
						$str_fields[$regType] = "INSERT INTO `student_identification`(`register_type`,`school_inep_id_fk`,`inep_id`,`id`,`name`,`birthday`,`sex`,`color_race`,`filiation`,`filiation_1`,`filiation_2`,`nationality`,`edcenso_nation_fk`,`edcenso_uf_fk`,`edcenso_city_fk`,`deficiency`,`deficiency_type_blindness`,`deficiency_type_low_vision`,`deficiency_type_deafness`,`deficiency_type_disability_hearing`,`deficiency_type_deafblindness`,`deficiency_type_phisical_disability`,`deficiency_type_intelectual_disability`,`deficiency_type_multiple_disabilities`,`deficiency_type_autism`,`deficiency_type_aspenger_syndrome`,`deficiency_type_rett_syndrome`,`deficiency_type_childhood_disintegrative_disorder`,`deficiency_type_gifted`,`resource_aid_lector`,`resource_aid_transcription`,`resource_interpreter_guide`,`resource_interpreter_libras`,`resource_lip_reading`,`resource_zoomed_test_16`,`resource_zoomed_test_20`,`resource_zoomed_test_24`,`resource_braille_test`,`resource_none`) VALUES " . $lines . " ON DUPLICATE KEY UPDATE register_type = register_type;";
						break;
					}
					case '70': {
						$str_fields[$regType] = "INSERT INTO `student_documents_and_address`(`register_type`,`school_inep_id_fk`,`student_fk`,`id`,`rg_number`,`rg_number_edcenso_organ_id_emitter_fk`,`rg_number_edcenso_uf_fk`,`rg_number_expediction_date`,`civil_certification`,`civil_certification_type`,`civil_certification_term_number`,`civil_certification_sheet`,`civil_certification_book`,`civil_certification_date`,`notary_office_uf_fk`,`notary_office_city_fk`,`edcenso_notary_office_fk`,`civil_register_enrollment_number`,`cpf`,`foreign_document_or_passport`,`nis`,`residence_zone`,`cep`,`address`,`number`,`complement`,`neighborhood`,`edcenso_uf_fk`,`edcenso_city_fk`) VALUES " . $lines . " ON DUPLICATE KEY UPDATE register_type = register_type;";
						break;
					}
					case '80': {
						$str_fields[$regType] = "INSERT INTO student_enrollment (`register_type`,`school_inep_id_fk`,`student_inep_id`,`student_fk`,`classroom_inep_id`,`classroom_fk`,`enrollment_id`,`unified_class`,`edcenso_stage_vs_modality_fk`,`another_scholarization_place`,`public_transport`,`transport_responsable_government`,`vehicle_type_van`,`vehicle_type_microbus`,`vehicle_type_bus`,`vehicle_type_bike`,`vehicle_type_animal_vehicle`,`vehicle_type_other_vehicle`,`vehicle_type_waterway_boat_5`,`vehicle_type_waterway_boat_5_15`,`vehicle_type_waterway_boat_15_35`,`vehicle_type_waterway_boat_35`,`vehicle_type_metro_or_train`,`student_entry_form`) VALUES " . $lines . " ON DUPLICATE KEY UPDATE register_type = register_type;";
						break;
					}
				}
			endforeach;

			return $str_fields;
		}
		public function validateSchool($collumn){
			$log = array();
			$siv = new SchoolIdentificationValidation();
			$result = $siv->isRegister("00", $collumn['register_type']);
			if(!$result["status"]) array_push($log, array("register_type"=>$result["erro"]));
			$result = $siv->isInepIdValid($collumn['inep_id']);
			if(!$result["status"]) array_push($log, array("inep_id"=>$result["erro"]));
			//campo 3
			$result = $siv->isManagerCPFValid($collumn['manager_cpf']);
			if(!$result["status"]) array_push($log, array("manager_cpf"=>$result["erro"]));

			//campo 4
			$result = $siv->isManagerNameValid($collumn['manager_name']);
			if(!$result["status"]) array_push($log, array("manager_name"=>$result["erro"]));

			//campo 5
			$result = $siv->isManagerRoleValid(intval($collumn['manager_role']));
			if(!$result["status"]) array_push($log, array("manager_role"=>$result["erro"]));

			//campo 6
			$result = $siv->isManagerEmailValid($collumn['manager_email']);
			if(!$result["status"]) array_push($log, array("manager_email"=>$result["erro"]));

			//campo 7
			$result = $siv->isSituationValid($collumn['situation']);
			if(!$result["status"]) array_push($log, array("situation"=>$result["erro"]));

			//campo 8 e 9
			$result = $siv->isSchoolYearValid($collumn['initial_date'], $collumn['final_date']);
			if(!$result["status"]) array_push($log, array("date"=>$result["erro"]));

			//campo 10
			$result = $siv->isSchoolNameValid($collumn['name']);
			if(!$result["status"]) array_push($log, array("name"=>$result["erro"]));

			//campo 11
			$result = $siv->isLatitudeValid($collumn['latitude']);
			if(!$result["status"]) array_push($log, array("latitude"=>$result["erro"]));

			//campo 12
			$result = $siv->isLongitudeValid($collumn['longitude']);
			if(!$result["status"]) array_push($log, array("longitude"=>$result["erro"]));

			//campo 13
			$result = $siv->isCEPValid($collumn['cep']);
			if(!$result["status"]) array_push($log, array("longitude"=>$result["erro"]));

			//campo 14
			$might_be_null = true;
			$might_not_be_null = false;


			$result = $siv->isAddressValid($collumn['address'], $might_not_be_null, 100);
			if(!$result["status"]) array_push($log, array("address"=>$result["erro"]));

			//campo 15
			$result = $siv->isAddressValid($collumn['address_number'], $might_be_null, 10);
			if(!$result["status"]) array_push($log, array("address_number"=>$result["erro"]));

			//campo 16
			$result = $siv->isAddressValid($collumn['address_complement'], $might_be_null, 20);
			if(!$result["status"]) array_push($log, array("address_complement"=>$result["erro"]));;

			//campo 17
			$result = $siv->isAddressValid($collumn['address_neighborhood'], $might_be_null, 50);
			if(!$result["status"]) array_push($log, array("address_neighborhood"=>$result["erro"]));

			//campo 18
			$result = $siv->isAddressValid($collumn['edcenso_uf_fk'], $might_not_be_null, 100);
			if(!$result["status"]) array_push($log, array("edcenso_uf_fk"=>$result["erro"]));

			//campo 19
			$result = $siv->isAddressValid($collumn['edcenso_city_fk'], $might_not_be_null, 100);
			if(!$result["status"]) array_push($log, array("edcenso_city_fk"=>$result["erro"]));

			//campo 20
			$result = $siv->isAddressValid($collumn['edcenso_district_fk'], $might_not_be_null, 100);
			if(!$result["status"]) array_push($log, array("edcenso_district_fk"=>$result["erro"]));

			//campo 21-25

			$phones = array($collumn['phone_number'],
				$collumn['public_phone_number'],
				$collumn['other_phone_number'],
				$collumn['fax_number']);
			var_dump($phones);
			$result = $siv->checkPhoneNumbers($collumn['ddd'], $phones);
			if(!$result["status"]) array_push($log, array("phones"=>$result["erro"]));

			//campo 26
			$result = $siv->isEmailValid($collumn['email']);
			if(!$result["status"]) array_push($log, array("email"=>$result["erro"]));

			//campo 28
			$result = $siv->isAdministrativeDependenceValid($collumn['administrative_dependence'], $collumn['edcenso_uf_fk']);
			if(!$result["status"]) array_push($log, array("administrative_dependence"=>$result["erro"]));

			//campo 29
			$result = $siv->isLocationValid($collumn['location']);
			if(!$result["status"]) array_push($log, array("location"=>$result["erro"]));

			//campo 30
			$result = $siv->checkPrivateSchoolCategory($collumn['private_school_category'],
				$collumn['situation'],
				$collumn['administrative_dependence']);
			if(!$result["status"]) array_push($log, array("private_school_category"=>$result["erro"]));

			//campo 31
			$result = $siv->isPublicContractValid($collumn['public_contract'],
				$collumn['situation'],
				$collumn['administrative_dependence']);
			if(!$result["status"]) array_push($log, array("public_contract"=>$result["erro"]));

			//campo 32 - 36
			$phones = array($collumn['private_school_business_or_individual'],
				$collumn['private_school_syndicate_or_association'],
				$collumn['private_school_ong_or_oscip'],
				$collumn['private_school_non_profit_institutions'],
				$collumn['private_school_s_system']);

			/*
			$result = $siv->checkPrivateSchoolCategory($keepers,
				$collumn['situation'],
				$collumn['administrative_dependence']);
			if(!$result["status"]) array_push($log, array("keepers"=>$result["erro"]));
			*/

			//campo 37
			$result = $siv->isCNPJValid($collumn['private_school_maintainer_cnpj'],
				$collumn['situation'],
				$collumn['administrative_dependence']);
			if(!$result["status"]) array_push($log, array("private_school_maintainer_cnpj"=>$result["erro"]));

			//campo 38
			$result = $siv->isCNPJValid($collumn['private_school_cnpj'],
				$collumn['situation'],
				$collumn['administrative_dependence']);
			if(!$result["status"]) array_push($log, array("private_school_cnpj"=>$result["erro"]));

			//campo 39
			$result = $siv->isRegulationValid($collumn['regulation'],
				$collumn['situation']);
			if(!$result["status"]) array_push($log, array("regulation"=>$result["erro"]));

			//campo 40
			$result = $siv->isRegulationValid($collumn['offer_or_linked_unity'],
				$collumn['situation']);
			if(!$result["status"]) array_push($log, array("offer_or_linked_unity"=>$result["erro"]));

			//campo 41
			$inep_head_school = $collumn['inep_head_school'];
			$sql = "SELECT 	si.inep_head_school, si.situation
			FROM 	school_identification AS si
			WHERE 	inep_id = '$inep_head_school';";
			$check =  Yii::app()->db->createCommand($sql)->queryAll();
			if(!empty($check)){
				$result = $siv->inepHeadSchool($collumn['inep_head_school'], $collumn['offer_or_linked_unity'],
					$collumn['inep_id'], $check[0]['situation'],
					$check[0]['inep_head_school']);
				if(!$result["status"]) array_push($log, array("inep_head_school"=>$result["erro"]));
			}
			//campo 42
			$ies_code = $collumn['ies_code'];
			$administrative_dependence = $collumn['administrative_dependence'];
			$sql = "SELECT 	COUNT(id) AS status
			FROM 	edcenso_ies
			WHERE 	id = '$ies_code' AND working_status = 'ATIVA'
					AND administrative_dependency_code = '$administrative_dependence';";
			$check =  Yii::app()->db->createCommand($sql)->queryAll();

			$result = $siv->iesCode($ies_code, $check[0]["status"],$collumn['offer_or_linked_unity']);
			if(!$result["status"]) array_push($log, array("ies_code"=>$result["erro"]));

			//Adicionando log da row
			return $log;
		}
		public function validateSchoolStructure($collumn,$school){
			$ssv = new SchoolStructureValidation();
			$school_inep_id_fk = $collumn["school_inep_id_fk"];
			$log = array();

			//campo 1
			$result = $ssv->isRegister("10", $collumn['register_type']);
			if(!$result["status"]) array_push($log, array("register_type"=>$result["erro"]));

			//campo 2
			$sql = "SELECT inep_id FROM school_identification;";
			$inep_ids = Yii::app()->db->createCommand($sql)->queryAll();
			foreach ($inep_ids as $key => $value) {
				$allowed_school_inep_ids[] = $value['inep_id'];
			}

			$result = $ssv->isAllowed($school_inep_id_fk,
				$allowed_school_inep_ids);
			if(!$result["status"]) array_push($log, array("school_inep_id_fk"=>$result["erro"]));

			//campo 3 à 11
			$operation_locations = array($collumn["operation_location_building"],
				$collumn["operation_location_temple"],
				$collumn["operation_location_businness_room"],
				$collumn["operation_location_instructor_house"],
				$collumn["operation_location_other_school_room"],
				$collumn["operation_location_barracks"],
				$collumn["operation_location_socioeducative_unity"],
				$collumn["operation_location_prison_unity"],
				$collumn["operation_location_other"]);
			$result = $ssv->atLeastOne($operation_locations);
			if(!$result["status"]) array_push($log, array("operation_locations"=>$result["erro"]));

			//campo 12
			$result = $ssv->buildingOccupationStatus($collumn["operation_location_building"],
				$collumn["operation_location_barracks"],
				$collumn["building_occupation_situation"]);
			if(!$result["status"]) array_push($log, array("building_occupation_situation"=>$result["erro"]));

			//campo 13
			$result = $ssv->sharedBuildingSchool($collumn["operation_location_building"],
				$collumn["shared_building_with_school"]);
			if(!$result["status"]) array_push($log, array("shared_building_with_school"=>$result["erro"]));

			//campos 14 à 19
			$shared_school_inep_ids = array($collumn["shared_school_inep_id_1"],
				$collumn["shared_school_inep_id_2"],
				$collumn["shared_school_inep_id_3"],
				$collumn["shared_school_inep_id_4"],
				$collumn["shared_school_inep_id_5"],
				$collumn["shared_school_inep_id_6"]);
			$result = $ssv->sharedSchoolInep($collumn["shared_building_with_school"],
				$collumn["school_inep_id_fk"],
				$shared_school_inep_ids);
			if(!$result["status"]) array_push($log, array("shared_school_inep_ids"=>$result["erro"]));

			//campo 20
			$result = $ssv->oneOfTheValues($collumn["consumed_water_type"]);
			if(!$result["status"]) array_push($log, array("consumed_water_type"=>$result["erro"]));

			//campos 21 à 25
			$water_supplys = array($collumn["water_supply_public"],
				$collumn["water_supply_artesian_well"],
				$collumn["water_supply_well"],
				$collumn["water_supply_river"],
				$collumn["water_supply_inexistent"]);
			$result = $ssv->supply($water_supplys);
			if(!$result["status"]) array_push($log, array("water_supplys"=>$result["erro"]));

			//campos 26 à 29
			$energy_supplys = array($collumn["energy_supply_public"],
				$collumn["energy_supply_generator"],
				$collumn["energy_supply_other"],
				$collumn["energy_supply_inexistent"]);
			$result = $ssv->supply($energy_supplys);
			if(!$result["status"]) array_push($log, array("energy_supplys"=>$result["erro"]));

			//campos 30 à 32
			$sewages = array($collumn["sewage_public"],
				$collumn["sewage_fossa"],
				$collumn["sewage_inexistent"]);
			$result = $ssv->supply($sewages);
			if(!$result["status"]) array_push($log, array("sewages"=>$result["erro"]));

			//campos 33 à 38
			$garbage_destinations = array($collumn["garbage_destination_collect"],
				$collumn["garbage_destination_burn"],
				$collumn["garbage_destination_throw_away"],
				$collumn["garbage_destination_recycle"],
				$collumn["garbage_destination_bury"],
				$collumn["garbage_destination_other"]);
			$result = $ssv->atLeastOne($garbage_destinations);
			if(!$result["status"]) array_push($log, array("garbage_destinations"=>$result["erro"]));

			//campos 39 à 68
			$dependencies = array($collumn["dependencies_principal_room"],
				$collumn["dependencies_instructors_room"],
				$collumn["dependencies_secretary_room"],
				$collumn["dependencies_info_lab"],
				$collumn["dependencies_science_lab"],
				$collumn["dependencies_aee_room"],
				$collumn["dependencies_indoor_sports_court"],
				$collumn["dependencies_outdoor_sports_court"],
				$collumn["dependencies_kitchen"],
				$collumn["dependencies_library"],
				$collumn["dependencies_reading_room"],
				$collumn["dependencies_playground"],
				$collumn["dependencies_nursery"],
				$collumn["dependencies_outside_bathroom"],
				$collumn["dependencies_inside_bathroom"],
				$collumn["dependencies_child_bathroom"],
				$collumn["dependencies_prysical_disability_bathroom"],
				$collumn["dependencies_physical_disability_support"],
				$collumn["dependencies_bathroom_with_shower"],
				$collumn["dependencies_refectory"],
				$collumn["dependencies_storeroom"],
				$collumn["dependencies_warehouse"],
				$collumn["dependencies_auditorium"],
				$collumn["dependencies_covered_patio"],
				$collumn["dependencies_uncovered_patio"],
				$collumn["dependencies_student_accomodation"],
				$collumn["dependencies_instructor_accomodation"],
				$collumn["dependencies_green_area"],
				$collumn["dependencies_laundry"],
				$collumn["dependencies_none"]);
			$result = $ssv->supply($dependencies);
			if(!$result["status"]) array_push($log, array("dependencies"=>$result["erro"]));

			//campo 69
			$result = $ssv->schoolsCount($collumn["operation_location_building"],
				$collumn["classroom_count"]);
			if(!$result["status"]) array_push($log, array("classroom_count"=>$result["erro"]));

			//campo 70
			$result = $ssv->isGreaterThan($collumn["used_classroom_count"], "0");
			if(!$result["status"]) array_push($log, array("used_classroom_count"=>$result["erro"]));

			//campo 71 à 83
			$result = $ssv->isGreaterThan($collumn["used_classroom_count"], "0");
			if(!$result["status"]) array_push($log, array("used_classroom_count"=>$result["erro"]));

			//campo 84
			$result = $ssv->pcCount($collumn["equipments_computer"],
				$collumn["administrative_computers_count"]);
			if(!$result["status"]) array_push($log, array("administrative_computers_count"=>$result["erro"]));

			//campo 85
			$result = $ssv->pcCount($collumn["equipments_computer"],
				$collumn["student_computers_count"]);
			if(!$result["status"]) array_push($log, array("student_computers_count"=>$result["erro"]));

			//campo 86
			$result = $ssv->internetAccess($collumn["equipments_computer"],
				$collumn["internet_access"]);
			if(!$result["status"]) array_push($log, array("internet_access"=>$result["erro"]));

			//campo 87
			$result = $ssv->bandwidth($collumn["internet_access"],
				$collumn["bandwidth"]);
			if(!$result["status"]) array_push($log, array("bandwidth"=>$result["erro"]));

			//campo 88
			$result = $ssv->isGreaterThan($collumn["employees_count"], "0");
			if(!$result["status"]) array_push($log, array("employees_count"=>$result["erro"]));

			$school_inep_fk = $school['inep_id'];
			//campo 89
			$sql = 'SELECT  COUNT(pedagogical_mediation_type) AS number_of
		FROM 	classroom
		WHERE 	school_inep_fk = "$school_inep_id_fk" AND
				(pedagogical_mediation_type =  "1" OR pedagogical_mediation_type =  "2");';
			$pedagogical_mediation_type = Yii::app()->db->createCommand($sql)->queryAll();


			$result = $ssv->schoolFeeding($school["administrative_dependence"],
				$collumn["feeding"],
				$pedagogical_mediation_type[0]["number_of"]);
			if(!$result["status"]) array_push($log, array("feeding"=>$result["erro"]));

			//campo 90
			$sql = "SELECT 	COUNT(assistance_type) AS number_of
			FROM 	classroom
			WHERE 	assistance_type = '5' AND
					school_inep_fk = '$school_inep_fk';" ;
			$assistance_type = Yii::app()->db->createCommand($sql)->queryAll();


			$modalities = array("modalities_regular" => $collumn["modalities_regular"],
				"modalities_especial" => $collumn["modalities_especial"],
				"modalities_eja" =>	$collumn["modalities_eja"],
				"modalities_professional" => $collumn["modalities_professional"]);

			$result = $ssv->aee($collumn["aee"], $collumn["complementary_activities"], $modalities,
				$assistance_type[0]["number_of"]);
			if(!$result["status"]) array_push($log, array("aee"=>$result["erro"]));

			//campo 91 - colocar o ano
			$sql = "SELECT 	COUNT(assistance_type) AS number_of
			FROM 	classroom
			WHERE 	assistance_type = '4' AND
					school_inep_fk = '$school_inep_fk';" ;
			$assistance_type = Yii::app()->db->createCommand($sql)->queryAll();


			$result = $ssv->aee($collumn["complementary_activities"], $collumn["aee"], $modalities,
				$assistance_type[0]["number_of"]);
			if(!$result["status"]) array_push($log, array("complementary_activities"=>$result["erro"]));

			//campo 92 à 95
			$year = Yii::app()->user->year;
			$sql = "SELECT  modalities, COUNT(se.student_fk) as number_of
			FROM	edcenso_stage_vs_modality_complementary as esmc
						INNER JOIN
					classroom AS cr
						ON esmc.fk_edcenso_stage_vs_modality = cr.edcenso_stage_vs_modality_fk
						INNER JOIN
					student_enrollment AS se
						ON cr.id = se.classroom_fk
			WHERE cr.school_year = '$year'
			GROUP BY esmc.modalities;";
			$are_there_students_by_modalitie = $this->areThereByModalitie($sql);
			$sql = "SELECT  modalities, COUNT(itd.instructor_fk) as number_of
		FROM	edcenso_stage_vs_modality_complementary as esmc
					INNER JOIN
				classroom AS cr
					ON esmc.fk_edcenso_stage_vs_modality = cr.edcenso_stage_vs_modality_fk
					INNER JOIN
				instructor_teaching_data AS itd
					ON cr.id = itd.classroom_id_fk
		WHERE cr.school_year = '$year'
		GROUP BY esmc.modalities;";
			$are_there_instructors_by_modalitie = $this->areThereByModalitie($sql);
			
			var_dump($are_there_instructors_by_modalitie);
			var_dump($are_there_students_by_modalitie);

			$result = $ssv->checkModalities($collumn["aee"],
				$collumn["complementary_activities"],
				$modalities,
				$are_there_students_by_modalitie,
				$are_there_instructors_by_modalitie);
			if(!$result["status"]) array_push($log, array("modalities"=>$result["erro"]));

			//campo 96
			$sql = "SELECT 	DISTINCT  COUNT(esm.id) AS number_of, cr.school_inep_fk
			FROM 	classroom AS cr
						INNER JOIN
					edcenso_stage_vs_modality AS esm
						ON esm.id = cr.edcenso_stage_vs_modality_fk
			WHERE 	stage IN (2,3,7) AND cr.school_inep_fk = '$school_inep_fk';";
			$number_of_schools = Yii::app()->db->createCommand($sql)->queryAll();

			$result = $ssv->schoolCicle($collumn["basic_education_cycle_organized"], $number_of_schools);
			if(!$result["status"]) array_push($log, array("basic_education_cycle_organized"=>$result["erro"]));

			//campo 97
			$result = $ssv->differentiatedLocation($school["inep_id"],
				$collumn["different_location"]);
			if(!$result["status"]) array_push($log, array("different_location"=>$result["erro"]));

			//campo 98 à 100
			$sociocultural_didactic_materials = array($collumn["sociocultural_didactic_material_none"],
				$collumn["sociocultural_didactic_material_quilombola"],
				$collumn["sociocultural_didactic_material_native"]);
			$result = $ssv->materials($sociocultural_didactic_materials);
			if(!$result["status"]) array_push($log, array("sociocultural_didactic_materials"=>$result["erro"]));

			//101
			$result = $ssv->isAllowed($collumn["native_education"], array("0", "1"));
			if(!$result["status"]) array_push($log, array("native_education"=>$result["erro"]));

			//102 à 103
			$native_education_languages = array($collumn["native_education_language_native"],
				$collumn["native_education_language_portuguese"]);
			$result = $ssv->languages($collumn["native_education"], $native_education_languages);
			if(!$result["status"]) array_push($log, array("native_education_languages"=>$result["erro"]));

			//104
			$result = $ssv->edcensoNativeLanguages($collumn["native_education_language_native"],
				$collumn["edcenso_native_languages_fk"]);
			if(!$result["status"]) array_push($log, array("edcenso_native_languages_fk"=>$result["erro"]));

			//105
			$result = $ssv->isAllowed($collumn["brazil_literate"], array("0", "1"));
			if(!$result["status"]) array_push($log, array("brazil_literate"=>$result["erro"]));

			//106
			$result = $ssv->isAllowed($collumn["open_weekend"], array("0", "1"));
			if(!$result["status"]) array_push($log, array("open_weekend"=>$result["erro"]));

			//107
			$sql = "SELECT 	COUNT(esm.id ) AS number_of
			FROM 	classroom AS cr
						INNER JOIN
					edcenso_stage_vs_modality AS esm
						ON esm.id = cr.edcenso_stage_vs_modality_fk
			WHERE 	cr.assistance_type NOT IN (4,5) AND
					cr.school_inep_fk =  '$school_inep_id_fk' AND
					esm.stage NOT IN (1,2);";
			$pedagogical_formation_by_alternance = Yii::app()->db->createCommand($sql)->queryAll();

			$result = $ssv->pedagogicalFormation($collumn["pedagogical_formation_by_alternance"],
				$pedagogical_formation_by_alternance[0]["number_of"]);
			if(!$result["status"]) array_push($log, array("pedagogical_formation_by_alternance"=>$result["erro"]));
			return $log;
		}
		public function validateClassroom($column,$school,$schoolstructure){
			$crv = new ClassroomValidation();
			$log = array();

			//campo 1
			$result = $crv->isRegister('20', $column['register_type']);
			if (!$result['status']) array_push($log, array('register_type' => $result['erro']));

			//campo 2
			$result = $crv->isEqual($column['school_inep_fk'], $school['inep_id'], 'Inep ids sao diferentes');
			if (!$result['status']) array_push($log, array('school_inep_fk' => $result['erro']));

			//campo 3
			$result = $crv->isEmpty($column['inep_id']);
			if (!$result['status']) array_push($log, array('inep_id' => $result['erro']));

			//campo 4
			$result = $crv->checkLength($column['id'], 20);
			if (!$result['status']) array_push($log, array('id' => $result['erro']));

			//campo 5
			$result = $crv->isValidClassroomName($column['name']);
			if (!$result['status']) array_push($log, array('name' => $result['erro']));

			//campo 6
			$result = $crv->isValidMediation($column['pedagogical_mediation_type']);
			if (!$result['status']) array_push($log, array('pedagogical_mediation_type' => $result['erro']));

			//campos 7 a 10
			$result = $crv->isValidClassroomTime($column['initial_hour'], $column['initial_minute'],
				$column['final_hour'], $column['final_minute'],
				$column['pedagogical_mediation_type']);
			if (!$result['status']) array_push($log, array('classroom_time' => $result['erro']));
			//acima: imprimir "classroom_time" no erro ou um erro pra cada um dos campos?

			//campos 11 a 17
			$result = $crv->areValidClassroomDays(array($column['week_days_sunday'], $column['week_days_monday'], $column['week_days_tuesday'],
				$column['week_days_wednesday'], $column['week_days_thursday'], $column['week_days_friday'],
				$column['week_days_saturday']), $column['pedagogical_mediation_type']);
			if (!$result['status']) array_push($log, array('classroom_days' => $result['erro']));
			//acima: imprimir "classroom_days" no erro ou um erro pra cada um dos campos?

			//campo 18
			$result = $crv->isValidAssistanceType($schoolstructure, $column['assistance_type'], $column['pedagogical_mediation_type']);
			if (!$result['status']) array_push($log, array('assistance_type' => $result['erro']));

			//campo 19
			$result = $crv->isValidMaisEducacaoParticipator($column['mais_educacao_participator'], $column['pedagogical_mediation_type'], $school['administrative_dependence'],
				$column['assistance_type'], $column['modality'], $column['edcenso_stage_vs_modality_fk']);
			if (!$result['status']) array_push($log, array('mais_educacao_participator' => $result['erro']));

			//campos 20 a 25
			$activities = array($column['complementary_activity_type_1'], $column['complementary_activity_type_2'], $column['complementary_activity_type_3'],
				$column['complementary_activity_type_4'], $column['complementary_activity_type_5'], $column['complementary_activity_type_6']);
			$result = $crv->isValidComplementaryActivityType($activities, $column['assistance_type']);
			if (!$result['status']) array_push($log, array('complementary_activity_types' => $result['erro']));

			//campos 26 a 36
			$aeeArray = array($column['aee_braille_system_education'], $column['aee_optical_and_non_optical_resources'],
				$column['aee_mental_processes_development_strategies'], $column['aee_mobility_and_orientation_techniques'],
				$column['aee_libras'], $column['aee_caa_use_education'], $column['aee_curriculum_enrichment_strategy'],
				$column['aee_soroban_use_education'], $column['aee_usability_and_functionality_of_computer_accessible_education'],
				$column['aee_teaching_of_Portuguese_language_written_modality'], $column['aee_strategy_for_school_environment_autonomy']);
			$result = $crv->isValidAEE($aeeArray, $column['assistance_type']);
			if (!$result['status']) array_push($log, array('aee' => $result['erro']));

			//campo 37
			$schoolStructureModalities = array($schoolstructure['modalities_regular'], $schoolstructure['modalities_especial'],
				$schoolstructure['modalities_eja'], $schoolstructure['modalities_professional']);
			$result = $crv->isValidModality($column['modality'], $column['assistance_type'], $schoolStructureModalities, $column['pedagogical_mediation_type']);
			if (!$result['status']) array_push($log, array('modality' => $result['erro']));

			//campo 38

			//abaixo: $column['stage'] ou $column['edcenso_stage_vs_modality_fk']?
			$result = $crv->isValidStage($column['edcenso_stage_vs_modality_fk'], $column['assistance_type'], $column['pedagogical_mediation_type']);
			if (!$result['status']) array_push($log, array('stage' => $result['erro']));

			//campo 39
			$result = $crv->isValidProfessionalEducation($column['edcenso_professional_education_course_fk'], $column['edcenso_stage_vs_modality_fk']);
			if (!$result['status']) array_push($log, array('edcenso_professional_education_course' => $result['erro']));

			//campos 40 a 65
			$disciplinesArray = array($column['discipline_chemistry'], $column['discipline_physics'], $column['discipline_mathematics'], $column['discipline_biology'], $column['discipline_science'],
				$column['discipline_language_portuguese_literature'], $column['discipline_foreign_language_english'], $column['discipline_foreign_language_spanish'], $column['discipline_foreign_language_franch'], $column['discipline_foreign_language_other'],
				$column['discipline_arts'], $column['discipline_physical_education'], $column['discipline_history'], $column['discipline_geography'], $column['discipline_philosophy'],
				$column['discipline_social_study'], $column['discipline_sociology'], $column['discipline_informatics'], $column['discipline_professional_disciplines'], $column['discipline_special_education_and_inclusive_practices'],
				$column['discipline_sociocultural_diversity'], $column['discipline_libras'], $column['discipline_pedagogical'], $column['discipline_religious'], $column['discipline_native_language'],
				$column['discipline_others']);
			$result = $crv->isValidDiscipline($disciplinesArray, $column['pedagogical_mediation_type'], $column['assistance_type'], $column['edcenso_stage_vs_modality_fk']);
			if (!$result['status']) array_push($log, array('disciplines' => $result['erro']));
			return $log;
		}
		public function validateInstructor($collumn,$instructor_documents_and_address){
			$sql = "SELECT inep_id FROM school_identification;";
			$inep_ids = Yii::app()->db->createCommand($sql)->queryAll();
			foreach ($inep_ids as $key => $value) {
				$allowed_school_inep_ids[] = $value['inep_id'];
			}

			$iiv = new InstructorIdentificationValidation();
			$school_inep_id_fk = $collumn["school_inep_id_fk"];
			$log = array();

			//campo 1
			$result = $iiv->isRegister("30", $collumn['register_type']);
			if(!$result["status"]) array_push($log, array("register_type"=>$result["erro"]));

			//campo 2
			$result = $iiv->isAllowedInepId($school_inep_id_fk,
				$allowed_school_inep_ids);
			if(!$result["status"]) array_push($log, array("school_inep_id_fk"=>$result["erro"]));

			//campo 3
			$result = $iiv->isNumericOfSize(12, $collumn['inep_id']);
			if(!$result["status"]) array_push($log, array("inep_id"=>$result["erro"]));

			//campo 4
			$result = $iiv->isNotGreaterThan($collumn['id'], 20);
			if(!$result["status"]) array_push($log, array("id"=>$result["erro"]));

			//campo 5
			$result = $iiv->isNameValid($collumn['name'], 100,
				$instructor_documents_and_address["cpf"]);
			if(!$result["status"]) array_push($log, array("name"=>$result["erro"]));

			//campo 6
			$result = $iiv->isEmailValid($collumn['email'], 100);
			if(!$result["status"]) array_push($log, array("email"=>$result["erro"]));

			//campo 7
			$result = $iiv->isNull($collumn['nis']);
			if(!$result["status"]) array_push($log, array("nis"=>$result["erro"]));

			//campo 8
			$year = Yii::app()->user->year;
			$result = $iiv->validateBirthday($collumn['birthday_date'], "13", "96", $year);
			if(!$result["status"]) array_push($log, array("birthday_date"=>$result["erro"]));

			//campo 9
			$result = $iiv->oneOfTheValues($collumn['sex']);
			if(!$result["status"]) array_push($log, array("sex"=>$result["erro"]));

			//campo 10
			$result = $iiv->isAllowed($collumn['color_race'], array("0", "1", "2", "3", "4", "5"));
			if(!$result["status"]) array_push($log, array("sex"=>$result["erro"]));

			//campo 11, 12, 13
			$result = $iiv->validateFiliation($collumn['filiation'], $collumn['filiation_1'], $collumn['filiation_2'],
				$instructor_documents_and_address["cpf"], 100);
			if(!$result["status"]) array_push($log, array("filiation"=>$result["erro"]));

			//campo 14, 15
			$result = $iiv->checkNation($collumn['edcenso_nation_fk'], $collumn['nationality'], array("1", "2", "3") );
			if(!$result["status"]) array_push($log, array("nationality_nation"=>$result["erro"]));

			//campo 16
			$result = $iiv->ufcity($collumn['nationality'],$collumn['edcenso_nation_fk'],$collumn['edcenso_uf_fk']);
			if(!$result["status"]) array_push($log, array("edcenso_uf_fk"=>$result["erro"]));

			//campo 17 -- @todo melhorar essa checagem
			/*
			$result = $iiv->ufcity($collumn['edcenso_city_fk'], $collumn['nationality']);
			if(!$result["status"]) array_push($log, array("edcenso_uf_fk"=>$result["erro"]));
			*/

			//campo 18
			$result = $iiv->isAllowed($collumn['deficiency'], array("0", "1"));
			if(!$result["status"]) array_push($log, array("deficiency"=>$result["erro"]));

			//campo 19 à 25
			$deficiencies = array($collumn['deficiency_type_blindness'],
				$collumn['deficiency_type_low_vision'],
				$collumn['deficiency_type_deafness'],
				$collumn['deficiency_type_disability_hearing'],
				$collumn['deficiency_type_deafblindness'],
				$collumn['deficiency_type_phisical_disability'],
				$collumn['deficiency_type_intelectual_disability']);

			$excludingdeficiencies = array($collumn['deficiency_type_blindness'] =>
				array($collumn['deficiency_type_low_vision'], $collumn['deficiency_type_deafness'],
					$collumn['deficiency_type_deafblindness']),
				$collumn['deficiency_type_low_vision'] =>
					array($collumn['deficiency_type_deafblindness']),
				$collumn['deficiency_type_deafness'] =>
					array($collumn['deficiency_type_disability_hearing'], $collumn['deficiency_type_disability_hearing']),
				$collumn['deficiency_type_disability_hearing'] =>
					array($collumn['deficiency_type_deafblindness']));

			$result = $iiv->checkDeficiencies($collumn['deficiency'], $deficiencies, $excludingdeficiencies);
			if(!$result["status"]) array_push($log, array("deficiencies"=>$result["erro"]));

			//campo 26

			$result = $iiv->checkMultiple($collumn['deficiency'], $collumn['deficiency_type_multiple_disabilities'], $deficiencies);
			if(!$result["status"]) array_push($log, array("deficiency_type_multiple_disabilities"=>$result["erro"]));

			return $log;
		}
		public function validateInstructorDocuments($collumn){
			$idav = new InstructorDocumentsAndAddressValidation();
			$log = array();

			$school_inep_id_fk = $collumn["school_inep_id_fk"];
			$instructor_inep_id = $collumn["inep_id"];

			//campo 1
			$result = $idav->isRegister("40", $collumn['register_type']);
			if(!$result["status"]) array_push($log, array("register_type"=>$result["erro"]));

			$sql = "SELECT inep_id FROM school_identification;";
			$inep_ids = Yii::app()->db->createCommand($sql)->queryAll();
			foreach ($inep_ids as $key => $value) {
				$allowed_school_inep_ids[] = $value['inep_id'];
			}
			//campo 2
			$result = $idav->isAllowedInepId($school_inep_id_fk,
				$allowed_school_inep_ids);
			if(!$result["status"]) array_push($log, array("school_inep_id_fk"=>$result["erro"]));

			//campo 3
			$sql = "SELECT COUNT(inep_id) AS status FROM instructor_documents_and_address WHERE inep_id =  '$instructor_inep_id'";
			$check = Yii::app()->db->createCommand($sql)->queryAll();
			$result = $idav->isEqual($check[0]['status'],'1', 'Não há tal inep_id $instructor_inep_id');
			if(!$result["status"]) array_push($log, array("inep_id"=>$result["erro"]));

			//campo 4
			$result = $idav->isNotGreaterThan($collumn['id'], 20);
			if(!$result["status"]) array_push($log, array("id"=>$result["erro"]));

			//campo 5
			$result = $idav->isCPFValid($collumn['cpf']);
			if(!$result["status"]) array_push($log, array("cpf"=>$result["erro"]));

			//campo 6
			$result = $idav->isAllowed($collumn['area_of_residence'], array("1", "2"));
			if(!$result["status"]) array_push($log, array("area_of_residence"=>$result["erro"]));

			//campo 7
			$result = $idav->isCEPValid($collumn['cep']);
			if(!$result["status"]) array_push($log, array("cep"=>$result["erro"]));

			//campo 8
			$result = $idav->isAdressValid($collumn['address'], $collumn['cep'], 100);
			if(!$result["status"]) array_push($log, array("address"=>$result["erro"]));

			//campo 9
			$result = $idav->isAdressValid($collumn['address_number'], $collumn['cep'], 10);
			if(!$result["status"]) array_push($log, array("address_number"=>$result["erro"]));

			//campo 10
			$result = $idav->isAdressValid($collumn['complement'], $collumn['cep'], 20);
			if(!$result["status"]) array_push($log, array("complement"=>$result["erro"]));

			//campo 11
			$result = $idav->isAdressValid($collumn['neighborhood'], $collumn['cep'], 50);
			if(!$result["status"]) array_push($log, array("neighborhood"=>$result["erro"]));

			//campo 12
			$result = $idav->isAdressValid($collumn['edcenso_uf_fk'], $collumn['cep'], 2);
			if(!$result["status"]) array_push($log, array("edcenso_uf_fk"=>$result["erro"]));

			//campo 13
			$result = $idav->isAdressValid($collumn['edcenso_city_fk'], $collumn['cep'], 7);
			if(!$result["status"]) array_push($log, array("edcenso_city_fk"=>$result["erro"]));

			return $log;
		}
		public function validateInscrutorData($collumn){
			$sql = "SELECT inep_id FROM school_identification;";
			$inep_ids = Yii::app()->db->createCommand($sql)->queryAll();
			foreach ($inep_ids as $key => $value) {
				$allowed_school_inep_ids[] = $value['inep_id'];
			}

			$itdv = new instructorTeachingDataValidation();
			$school_inep_id_fk = $collumn["school_inep_id_fk"];
			$instructor_inep_id = $collumn["instructor_inep_id"];
			$instructor_fk = $collumn['instructor_fk'];
			$classroom_fk = $collumn['classroom_id_fk'];
			$log = array();

			//campo 1
			$result = $itdv->isRegister("51", $collumn['register_type']);
			if(!$result["status"]) array_push($log, array("register_type"=>$result["erro"]));

			//campo 2
			$result = $itdv->isAllowedInepId($school_inep_id_fk,
				$allowed_school_inep_ids);
			if(!$result["status"]) array_push($log, array("school_inep_id_fk"=>$result["erro"]));

			//campo 03
			$sql = "SELECT COUNT(inep_id) AS status FROM instructor_identification WHERE inep_id =  '$instructor_inep_id'";
			$check = Yii::app()->db->createCommand($sql)->queryAll();

			$result = $itdv->isEqual($check[0]['status'],'1', 'Não há tal instructor_inep_id $instructor_inep_id');
			if(!$result["status"]) array_push($log, array("instructor_inep_id"=>$result["erro"]));

			//campo 4
			$sql = "SELECT COUNT(id) AS status FROM instructor_identification WHERE id =  '$instructor_fk'";
			$check = Yii::app()->db->createCommand($sql)->queryAll();

			$result = $itdv->isEqual($check[0]['status'],'1', 'Não há tal instructor_fk $instructor_fk');
			if(!$result["status"]) array_push($log, array("instructor_fk"=>$result["erro"]));

			//campo 5
			$result = $itdv->isNull($collumn['classroom_inep_id']);
			if(!$result["status"]) array_push($log, array("classroom_inep_id"=>$result["erro"]));

			//campo 6
			$sql = "SELECT COUNT(id) AS status FROM classroom WHERE id = '$classroom_fk';";
			$check = Yii::app()->db->createCommand($sql)->queryAll();

			$result = $itdv->isEqual($check[0]['status'],'1', 'Não há tal classroom_id_fk $classroom_fk');
			if(!$result["status"]) array_push($log, array("classroom_id_fk"=>$result["erro"]));

			//campo 7
			$sql = "SELECT assistance_type, pedagogical_mediation_type, edcenso_stage_vs_modality_fk
			FROM classroom
			WHERE id = '$classroom_fk';";
			$check = Yii::app()->db->createCommand($sql)->queryAll();;
			$assistance_type = $check[0]['assistance_type'];
			$pedagogical_mediation_type = $check[0]['pedagogical_mediation_type'];
			$edcenso_svm = $check[0]['edcenso_stage_vs_modality_fk'];

			$sql = "SELECT count(cr.id) AS status_instructor
			FROM 	classroom as cr
						INNER JOIN
					instructor_teaching_data AS itd
						ON itd.classroom_id_fk = cr.id
			WHERE 	cr.id = '$classroom_fk' AND itd.id != '$instructor_fk';";
			$check = Yii::app()->db->createCommand($sql)->queryAll();
			$status_instructor = $check[0]['status_instructor'];


			$sql = "SELECT count(si.id) AS status_student
			FROM 	classroom AS cr
						INNER JOIN
					instructor_teaching_data AS itd
						ON itd.classroom_id_fk = cr.id
						INNER JOIN
					instructor_identification as ii
						ON ii.id = itd.instructor_fk
						INNER JOIN
					student_enrollment AS se
						ON se.classroom_fk =cr.id
						INNER JOIN
					student_identification AS si
					 	on si.id = se.student_fk
			WHERE 	cr.id = '$classroom_fk' AND ii.id = '$instructor_fk'
					AND
					(ii.deficiency_type_deafness = '1' OR ii.deficiency_type_disability_hearing = '1' OR
					ii.deficiency_type_deafblindness = '1' OR si.deficiency_type_deafness = '1' OR
					si.deficiency_type_deafblindness = '1');";
			$check = Yii::app()->db->createCommand($sql)->queryAll();
			$status_student = $check[0]['status_student'];

			$result = $itdv->checkRole($collumn['role'], $pedagogical_mediation_type,
				$assistance_type, $status_instructor, $status_student );
			if(!$result["status"]) array_push($log, array("role"=>$result["erro"]));

			//campo 08
			$sql = "SELECT se.administrative_dependence
			FROM school_identification AS se
			WHERE se.inep_id = '$school_inep_id_fk';";

			$check = Yii::app()->db->createCommand($sql)->queryAll();

			$administrative_dependence = $check[0]['administrative_dependence'];

			$result = $itdv->checkContactType($collumn['contract_type'], $collumn['role'], $administrative_dependence);
			if(!$result["status"]) array_push($log, array("contract_type"=>$result["erro"]));

			//campo 09
			$result = $itdv->disciplineOne($collumn['discipline_1_fk'], $collumn['role'], $assistance_type, $edcenso_svm);
			if(!$result["status"]) array_push($log, array("discipline_1_fk"=>$result["erro"]));

			//campo 09 à 21

			$disciplines_codes = array(	$collumn['discipline_1_fk'],
				$collumn['discipline_2_fk'],
				$collumn['discipline_3_fk'],
				$collumn['discipline_4_fk'],
				$collumn['discipline_5_fk'],
				$collumn['discipline_6_fk'],
				$collumn['discipline_7_fk'],
				$collumn['discipline_8_fk'],
				$collumn['discipline_9_fk'],
				$collumn['discipline_10_fk'],
				$collumn['discipline_11_fk'],
				$collumn['discipline_12_fk'],
				$collumn['discipline_13_fk']);


			$sql = "SELECT discipline_chemistry, discipline_physics, discipline_mathematics, discipline_biology,
						discipline_science, discipline_language_portuguese_literature,
						discipline_foreign_language_english, discipline_foreign_language_spanish,
						discipline_foreign_language_franch, discipline_foreign_language_other,
						discipline_arts, discipline_physical_education, discipline_history, discipline_geography,
						discipline_philosophy, discipline_social_study, discipline_sociology, discipline_informatics,
						discipline_professional_disciplines, discipline_special_education_and_inclusive_practices,
						discipline_sociocultural_diversity, discipline_libras, discipline_pedagogical,
						discipline_religious, discipline_native_language, discipline_others
			FROM 		classroom
			WHERE 	id = '$classroom_fk';";

			$check = Yii::app()->db->createCommand($sql)->queryAll();

			$disciplines = array_values($check[0]);

			$result = $itdv->checkDisciplineCode($disciplines_codes, $collumn['role'], $assistance_type,
				$edcenso_svm, $disciplines);
			if(!$result["status"]) array_push($log, array("disciplines_codes"=>$result["erro"]));

			return $log;
		}
		public function validateStudentIdentification($collumn,$studentdocument){

			$sql = "SELECT inep_id FROM school_identification;";
			$inep_ids = Yii::app()->db->createCommand($sql)->queryAll();
			foreach ($inep_ids as $key => $value) {
				$allowed_school_inep_ids[] = $value['inep_id'];
			}

			$stiv = new studentIdentificationValidation();
			$school_inep_id_fk = $collumn["school_inep_id_fk"];
			$log = array();

			//campo 1
			$result = $stiv->isRegister("60", $collumn['register_type']);
			if(!$result["status"]) array_push($log, array("register_type"=>$result["erro"]));

			//campo 2
			$result = $stiv->isAllowedInepId($school_inep_id_fk,
				$allowed_school_inep_ids);
			if(!$result["status"]) array_push($log, array("school_inep_id_fk"=>$result["erro"]));

			//campo 3
			$result = $stiv->isNumericOfSize(12, $collumn['inep_id']);
			if(!$result["status"]) array_push($log, array("inep_id"=>$result["erro"]));

			//campo 4
			$result = $stiv->isNotGreaterThan($collumn['id'], 20);
			if(!$result["status"]) array_push($log, array("id"=>$result["erro"]));

			//campo 5
			$result = $stiv->isNameValid($collumn['name'], 100,
				$studentdocument["cpf"]);
			if(!$result["status"]) array_push($log, array("name"=>$result["erro"]));

			$year = Yii::app()->user->year;
			//campo 6
			$result = $stiv->validateBirthday($collumn['birthday'], 1910, $year);
			if(!$result["status"]) array_push($log, array("birthday"=>$result["erro"]));

			//campo 7
			$result = $stiv->oneOfTheValues($collumn['sex']);
			if(!$result["status"]) array_push($log, array("sex"=>$result["erro"]));

			//campo 8
			$result = $stiv->isAllowed($collumn['color_race'], array("0", "1", "2", "3", "4", "5"));
			if(!$result["status"]) array_push($log, array("sex"=>$result["erro"]));

			//campo 9, 10, 11
			$result = $stiv->validateFiliation($collumn['filiation'], $collumn['filiation_1'], $collumn['filiation_2'],
				$studentdocument["cpf"], 100);
			if(!$result["status"]) array_push($log, array("filiation"=>$result["erro"]));

			//campo 12, 13
			$result = $stiv->checkNation($collumn['nationality'], $collumn['edcenso_nation_fk'], array("1", "2", "3") );
			if(!$result["status"]) array_push($log, array("nationality_nation"=>$result["erro"]));

			//campo 14
			$result = $stiv->ufcity($collumn['nationality'],$collumn['edcenso_nation_fk'],$collumn['edcenso_uf_fk']);
			if(!$result["status"]) array_push($log, array("edcenso_uf_fk"=>$result["erro"]));

			//campo 15
			$result = $stiv->ufcity($collumn['nationality'],$collumn['edcenso_nation_fk'],$collumn['edcenso_city_fk']);
			if(!$result["status"]) array_push($log, array("edcenso_uf_fk"=>$result["erro"]));

			//campo 16
			$student_id = $collumn['id'];

			$sql = "SELECT 	COUNT(cr.id) AS status
			FROM 	student_identification as si
						INNER JOIN
					student_enrollment AS se
						ON si.id = se.student_fk
          				INNER JOIN
          			classroom AS cr
          				ON se.classroom_fk = cr.id
			WHERE si.id = '$student_id' AND (cr.assistance_type = 5 OR cr.modality = 2)
			GROUP BY si.id;";

			@$hasspecialneeds = Yii::app()->db->createCommand($sql)->queryAll();

			@$result = $stiv->specialNeeds($collumn['deficiency'], array("0", "1"),
				$hasspecialneeds["status"]);
			if(!$result["status"]) array_push($log, array("pedagogical_formation_by_alternance"=>$result["erro"]));

			//campo 17 à 24 e 26 à 29

			$deficiencies_whole = array($collumn['deficiency_type_blindness'],
				$collumn['deficiency_type_low_vision'],
				$collumn['deficiency_type_deafness'],
				$collumn['deficiency_type_disability_hearing'],
				$collumn['deficiency_type_deafblindness'],
				$collumn['deficiency_type_phisical_disability'],
				$collumn['deficiency_type_intelectual_disability'],
				$collumn['deficiency_type_autism'],
				$collumn['deficiency_type_aspenger_syndrome'],
				$collumn['deficiency_type_rett_syndrome'],
				$collumn['deficiency_type_childhood_disintegrative_disorder'],
				$collumn['deficiency_type_gifted']);

			$excludingdeficiencies = array(	$collumn['deficiency_type_blindness'] =>
				array($collumn['deficiency_type_low_vision'], $collumn['deficiency_type_deafness'],
					$collumn['deficiency_type_deafblindness']),
				$collumn['deficiency_type_low_vision'] =>
					array($collumn['deficiency_type_deafblindness']),
				$collumn['deficiency_type_deafness'] =>
					array($collumn['deficiency_type_disability_hearing'], $collumn['deficiency_type_disability_hearing']),
				$collumn['deficiency_type_disability_hearing'] =>
					array($collumn['deficiency_type_deafblindness']),
				$collumn['deficiency_type_autism'] =>
					array($collumn['deficiency_type_aspenger_syndrome'], $collumn['deficiency_type_rett_syndrome'],
						$collumn['deficiency_type_childhood_disintegrative_disorder']),
				$collumn['deficiency_type_aspenger_syndrome'] =>
					array($collumn['deficiency_type_rett_syndrome'], $collumn['deficiency_type_childhood_disintegrative_disorder']),
				$collumn['deficiency_type_rett_syndrome'] =>
					array($collumn['deficiency_type_childhood_disintegrative_disorder']));

			$result = $stiv->checkDeficiencies($collumn['deficiency'], $deficiencies_whole, $excludingdeficiencies);
			if(!$result["status"]) array_push($log, array("deficiencies"=>$result["erro"]));

			//campo 25

			$deficiencies_sample = array($collumn['deficiency_type_blindness'],
				$collumn['deficiency_type_low_vision'],
				$collumn['deficiency_type_deafness'],
				$collumn['deficiency_type_disability_hearing'],
				$collumn['deficiency_type_deafblindness'],
				$collumn['deficiency_type_phisical_disability'],
				$collumn['deficiency_type_intelectual_disability']);

			$result = $stiv->checkMultiple($collumn['deficiency'], $collumn['deficiency_type_multiple_disabilities'], $deficiencies_sample);
			if(!$result["status"]) array_push($log, array("deficiency_type_multiple_disabilities"=>$result["erro"]));

			//campo 30 à 39
			$sql = "SELECT  COUNT(si.id) AS status
			FROM 	student_identification AS si
						INNER JOIN
					student_enrollment AS se
						ON si.id = se.student_fk
			WHERE 	se.edcenso_stage_vs_modality_fk in (16, 7, 18, 11, 41, 27, 28, 32, 33, 37, 38)
					AND si.id = '$student_id';";
			$demandresources = Yii::app()->db->createCommand($sql)->queryAll();

			$resources = array($collumn['resource_aid_lector'],
				$collumn['resource_interpreter_guide'],
				$collumn['resource_interpreter_libras'],
				$collumn['resource_lip_reading'],
				$collumn['resource_zoomed_test_16'],
				$collumn['resource_zoomed_test_20'],
				$collumn['resource_zoomed_test_24'],
				$collumn['resource_braille_test'],
				$collumn['resource_none'],
				$collumn['resource_aid_transcription']);

			array_pop($deficiencies_whole);
			$result = $stiv->inNeedOfResources($deficiencies_whole,
				$demandresources,
				$resources,
				$collumn['deficiency_type_blindness'],
				$collumn['deficiency_type_deafblindness']);
			if(!$result["status"]) array_push($log, array("resources"=>$result["erro"]));
			return $log;
		}
		public function validateStudentDocumentsAddress($collumn){
			$student_inep_id = $collumn['student_fk'];
			$sql = "SELECT inep_id FROM school_identification;";
			$inep_ids = Yii::app()->db->createCommand($sql)->queryAll();
			foreach ($inep_ids as $key => $value) {
				$allowed_school_inep_ids[] = $value['inep_id'];
			}
			$sql = "SELECT inep_id FROM student_identification;";
			$array = Yii::app()->db->createCommand($sql)->queryAll();
			foreach ($array as $key => $value) {
				$allowed_students_inep_ids[] = $value['inep_id'];
			}

			$sda = new studentDocumentsAndAddressValidation();
			$school_inep_id_fk = $collumn["school_inep_id_fk"];
			$student_inep_id_fk = $collumn["student_fk"];
			$log = array();

			$sqlTable6012 = "SELECT nationality AS field12 FROM student_identification;";
			$checkSql6012 = Yii::app()->db->createCommand($sqlTable6012)->queryAll();
			$field6012 = $checkSql6012[0]['field12'];

			$foreign = $sda->isAllowed($field6012, array("3"));

			$sqlTable6006 = "SELECT birthday AS field06 FROM student_identification;";
			$checkSql6006 = Yii::app()->db->createCommand($sqlTable6006)->queryAll();
			$field6006 = $checkSql6006[0]['field06'];

			$sqlTable7005 = "SELECT rg_number AS field5 FROM student_documents_and_address;";
			$checkSql7005 = Yii::app()->db->createCommand($sqlTable7005)->queryAll();
			$field7005 = $checkSql7005[0]['field5'];

			date_default_timezone_set('America/Bahia');
			$date = date('d/m/Y');

			$field7009 = $sda->isAllowed($collumn['civil_certification'], array("1", "2"));

			//campo 1
			$result = $sda->isRegister("70", $collumn['register_type']);
			if (!$result["status"]) array_push($log, array("register_type"=>$result["erro"]));

			//campo 2
			$result = $sda->isAllowedInepId($school_inep_id_fk,
				$allowed_school_inep_ids);
			if(!$result["status"]) array_push($log, array("school_inep_id_fk"=>$result["erro"]));

			//campo 3
			$result = $sda->isAllowedInepId($student_inep_id_fk,
				$allowed_students_inep_ids);
			if(!$result["status"]) array_push($log, array("student_inep_id"=>$result["erro"]));

			//campo 4
			$sql = "SELECT COUNT(inep_id) AS status FROM student_identification WHERE inep_id = '$student_inep_id';";
			$check = Yii::app()->db->createCommand($sql)->queryAll();
			$result = $sda->isEqual($check[0]['status'],'1', 'Não há tal student_inep_id $student_inep_id');
			if(!$result["status"]) array_push($log, array("student_fk"=>$result["erro"]));

			//campo 5
			$result = $sda->isRgNumberValid($collumn['rg_number'], $field6012);
			if(!$result["status"]) array_push($log, array("rg_number"=>$result["erro"]));

			//campo 6
			$result = $sda->isRgEmissorOrganValid($collumn['rg_number_edcenso_organ_id_emitter_fk'], $field6012, $field7005);
			if(!$result["status"]) array_push($log, array("rg_number_edcenso_organ_id_emitter_fk"=>$result["erro"]));

			//campo 7
			$result = $sda->isRgUfValid($collumn['rg_number_edcenso_uf_fk'], $field6012, $field7005);
			if(!$result["status"]) array_push($log, array("rg_number_edcenso_uf_fk"=>$result["erro"]));

			//campo 8
			$result = $sda->isDateValid($field6012, $collumn['rg_number_expediction_date'] ,$field6006, $date, 0, 8);
			if(!$result["status"]) array_push($log, array("rg_number_expediction_date"=>$result["erro"]));

			//campo 9
			$result = $sda->isAllowed($collumn['civil_certification'], array("1", "2"));
			if(!$result["status"]) array_push($log, array("rg_number_expediction_date"=>$result["erro"]));

			//campo 10
			$result = $sda->isCivilCertificationTypeValid($field7009, $field7005, $field6012, $field6006, $date);
			if(!$result["status"]) array_push($log, array("civil_certification_type"=>$result["erro"]));

			//campo 11
			$result = $sda->isFieldValid(8, $collumn['civil_certification_term_number'], $field6012, $field7005);
			if(!$result["status"]) array_push($log, array("civil_certification_term_number"=>$result["erro"]));

			//campo 12
			$result = $sda->isFieldValid(4, $collumn['civil_certification_sheet'], $field6012, $field7005);
			if(!$result["status"]) array_push($log, array("civil_certification_sheet"=>$result["erro"]));

			//campo 13
			$result = $sda->isFieldValid(8, $collumn['civil_certification_book'], $field6012, $field7005);
			if(!$result["status"]) array_push($log, array("civil_certification_book"=>$result["erro"]));

			//campo 14
			$result = $sda->isDateValid($field6012, $collumn['civil_certification_date'] ,$field6006, $date, 1, 14);
			if(!$result["status"]) array_push($log, array("civil_certification_date"=>$result["erro"]));

			//campo 15
			$result = $sda->isFieldValid(2, $collumn['notary_office_uf_fk'], $field6012, $field7005);
			if(!$result["status"]) array_push($log, array("notary_office_uf_fk"=>$result["erro"]));

			//campo 16
			$result = $sda->isFieldValid(7, $collumn['notary_office_city_fk'], $field6012, $field7005);
			if(!$result["status"]) array_push($log, array("notary_office_city_fk"=>$result["erro"]));

			//campo 17
			$result = $sda->isFieldValid(6, $collumn['edcenso_notary_office_fk'], $field6012, $field7005);
			if(!$result["status"]) array_push($log, array("edcenso_notary_office_fk"=>$result["erro"]));

			//campo 18
			$result = $sda->isCivilRegisterNumberValid($collumn['civil_register_enrollment_number'], $field6012, $field7005);
			if(!$result["status"]) array_push($log, array("civil_register_enrollment_number"=>$result["erro"]));

			//campo 19
			$result = $sda->isCPFValid($collumn['cpf']);
			if(!$result["status"]) array_push($log, array("cpf"=>$result["erro"]));

			//campo 20
			$result = $sda->isPassportValid($collumn['foreign_document_or_passport'], $field6012);
			if(!$result["status"]) array_push($log, array("foreign_document_or_passport"=>$result["erro"]));

			//campo 21
			$result = $sda->isNISValid($collumn['nis']);
			if(!$result["status"]) array_push($log, array("nis"=>$result["erro"]));

			//campo 22
			$result = $sda->isAreaOfResidenceValid($collumn['residence_zone']);
			if(!$result["status"]) array_push($log, array("residence_zone"=>$result["erro"]));

			//campo 23
			$result = $sda->isCEPValid($collumn['cep']);
			if(!$result["status"]) array_push($log, array("cep"=>$result["erro"]));

			//campo 24
			$result = $sda->isAdressValid($collumn['address'], $collumn['cep'], 100);
			if(!$result["status"]) array_push($log, array("address"=>$result["erro"]));

			//campo 25
			$result = $sda->isAdressValid($collumn['number'], $collumn['cep'], 10);
			if(!$result["status"]) array_push($log, array("number"=>$result["erro"]));

			//campo 26
			$result = $sda->isAdressValid($collumn['complement'], $collumn['cep'], 20);
			if(!$result["status"]) array_push($log, array("complement"=>$result["erro"]));

			//campo 27
			$result = $sda->isAdressValid($collumn['neighborhood'], $collumn['cep'], 50);
			if(!$result["status"]) array_push($log, array("neighborhood"=>$result["erro"]));

			//campo 28
			$result = $sda->isAdressValid($collumn['edcenso_uf_fk'], $collumn['cep'], 2);
			if(!$result["status"]) array_push($log, array("edcenso_uf_fk"=>$result["erro"]));

			//campo 29
			$result = $sda->isAdressValid($collumn['edcenso_city_fk'], $collumn['cep'], 7);
			if(!$result["status"]) array_push($log, array("edcenso_city_fk"=>$result["erro"]));

			return $log;
		}
		public function validateEnrollment($collumn){
			$sql = "SELECT inep_id FROM school_identification;";
			$inep_ids = Yii::app()->db->createCommand($sql)->queryAll();
			foreach ($inep_ids as $key => $value) {
				$allowed_school_inep_ids[] = $value['inep_id'];
			}
			$sql = "SELECT inep_id FROM student_identification;";
			$array = Yii::app()->db->createCommand($sql)->queryAll();
			foreach ($array as $key => $value) {
				$allowed_students_inep_ids[] = $value['inep_id'];
			}

			$sev = new studentEnrollmentValidation();
			$school_inep_id_fk = $collumn["school_inep_id_fk"];
			$student_inep_id_fk = $collumn["student_inep_id"];
			$classroom_fk = $collumn['classroom_fk'];
			$log = array();

			//campo 1
			$result = $sev->isRegister("80", $collumn['register_type']);
			if(!$result["status"]) array_push($log, array("register_type"=>$result["erro"]));

			//campo 2
			$result = $sev->isAllowedInepId($school_inep_id_fk,
				$allowed_school_inep_ids);
			if(!$result["status"]) array_push($log, array("school_inep_id_fk"=>$result["erro"]));

			//campo 3
			$result = $sev->isAllowedInepId($student_inep_id_fk,
				$allowed_students_inep_ids);
			if(!$result["status"]) array_push($log, array("student_inep_id"=>$result["erro"]));

			//campo 4
			$sql = "SELECT COUNT(inep_id) AS status FROM student_identification WHERE inep_id = '$student_inep_id_fk';";
			$check = Yii::app()->db->createCommand($sql)->queryAll();

			$result = $sev->isEqual($check[0]['status'],'1', 'Não há tal student_inep_id $student_inep_id');
			if(!$result["status"]) array_push($log, array("student_fk"=>$result["erro"]));

			//campo 05
			$result = $sev->isNull($collumn['classroom_inep_id']);
			if(!$result["status"]) array_push($log, array("classroom_inep_id"=>$result["erro"]));

			//campo 6

			$sql = "SELECT COUNT(id) AS status FROM classroom WHERE id = '$classroom_fk';";
			$check = Yii::app()->db->createCommand($sql)->queryAll();

			$result = $sev->isEqual($check[0]['status'],'1', 'Não há tal classroom_id $classroom_fk');
			if(!$result["status"]) array_push($log, array("classroom_fk"=>$result["erro"]));

			//campo 07
			$result = $sev->isNull($collumn['enrollment_id']);
			if(!$result["status"]) array_push($log, array("enrollment_id"=>$result["erro"]));

			//campo 8

			$sql = "SELECT COUNT(id) AS status FROM classroom WHERE id = '$classroom_fk' AND edcenso_stage_vs_modality_fk = '3';";
			$check = Yii::app()->db->createCommand($sql)->queryAll();

			$result = $sev->ifDemandsCheckValues($check[0]['status'], $collumn['unified_class'], array('1', '2'));
			if(!$result["status"]) array_push($log, array("unified_class"=>$result["erro"]));

			//campo 9

			$sql = "SELECT edcenso_stage_vs_modality_fk FROM classroom WHERE id = '$classroom_fk';";
			$check = Yii::app()->db->createCommand($sql)->queryAll();

			$edcenso_svm = $check[0]['edcenso_stage_vs_modality_fk'];

			$result = $sev->multiLevel($collumn['edcenso_stage_vs_modality_fk'], $edcenso_svm);
			if(!$result["status"]) array_push($log, array("edcenso_stage_vs_modality_fk"=>$result["erro"]));

			//campo 10
			$sql = "SELECT assistance_type, pedagogical_mediation_type FROM classroom WHERE id = '$classroom_fk';";
			$check = Yii::app()->db->createCommand($sql)->queryAll();
			$assistance_type = $check[0]['assistance_type'];
			$pedagogical_mediation_type = $check[0]['pedagogical_mediation_type'];

			$result = $sev->anotherScholarizationPlace($collumn['another_scholarization_place'], $assistance_type, $pedagogical_mediation_type);
			if(!$result["status"]) array_push($log, array("another_scholarization_place"=>$result["erro"]));

			//campo 11
			$result = $sev->publicTransportation($collumn['public_transport'], $pedagogical_mediation_type);
			if(!$result["status"]) array_push($log, array("public_transport"=>$result["erro"]));

			//campo 12
			$result = $sev->ifDemandsCheckValues($collumn['public_transport'], $collumn['transport_responsable_government'], array('1', '2'));
			if(!$result["status"]) array_push($log, array("transport_responsable_government"=>$result["erro"]));

			//campo 13 à 23

			$vehicules_types = array($collumn['vehicle_type_van'],
				$collumn['vehicle_type_microbus'],
				$collumn['vehicle_type_bus'],
				$collumn['vehicle_type_bike'],
				$collumn['vehicle_type_other_vehicle'],
				$collumn['vehicle_type_waterway_boat_5'],
				$collumn['vehicle_type_waterway_boat_5_15'],
				$collumn['vehicle_type_waterway_boat_15_35'],
				$collumn['vehicle_type_waterway_boat_35'],
				$collumn['vehicle_type_metro_or_train']);

			$result = $sev->vehiculesTypes($collumn['public_transport'], $vehicules_types);
			if(!$result["status"]) array_push($log, array("vehicules_types"=>$result["erro"]));


			//24

			$sql = "SELECT se.administrative_dependence
			FROM school_identification AS se
			WHERE se.inep_id = '$school_inep_id_fk';";

			$check =  Yii::app()->db->createCommand($sql)->queryAll();

			$administrative_dependence = $check[0]['administrative_dependence'];



			$result = $sev->studentEntryForm($collumn['student_entry_form'], $administrative_dependence, $edcenso_svm);
			if(!$result["status"]) array_push($log, array("student_entry_form"=>$result["erro"]));
			return $log;
		}
		public function actionValidate(){
			Yii::import('ext.Validator.*');
			$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
			$schoolstructure = SchoolStructure::model()->findByPk(Yii::app()->user->school);
			$schoolcolumn = $school->attributes;
			$schoolstructurecolumn = $schoolstructure->attributes;
			$log[] = $this->validateSchool($schoolcolumn);
			$log[] = $this->validateSchoolStructure($schoolstructurecolumn,$schoolcolumn);
			$classrooms = Classroom::model()->findAllByAttributes(["school_inep_fk" => yii::app()->user->school, "school_year" => Yii::app()->user->year]);
			$loads['school'] = $school->attributes;
			$loads['school']['tag_id'] = hexdec(crc32($school->inep_id.$school->name));
			foreach ($classrooms as $iclass => $classroom) {
				$log[] = $this->validateClassroom($classroom,$schoolcolumn,$schoolstructure);
				foreach ($classroom->instructorTeachingDatas as $iteaching => $teachingData) {
					$log[] = $this->validateInstructor($teachingData->instructorFk->attributes,$teachingData->instructorFk->documents->attributes);
					$log[] = $this->validateInstructorDocuments($teachingData->instructorFk->documents->attributes);
					$log[] = $this->validateInscrutorData($teachingData->attributes);
				}
				foreach ($classroom->studentEnrollments as $ienrollment => $enrollment) {
					if(!isset($loads['students'][$enrollment->student_fk])){
						$log[] = $this->validateStudentIdentification($enrollment->studentFk->attributes,$enrollment->studentFk->documentsFk->attributes);
						$log[] = $this->validateStudentDocumentsAddress($enrollment->studentFk->documentsFk->attributes);
					}
					$log[] = $this->validateEnrollment($enrollment->attributes);
				}
			}
			var_dump($log);exit;
		}

		public function actionLoadToMaster(){
			$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
			$classrooms = Classroom::model()->findAllByAttributes(["school_inep_fk" => yii::app()->user->school, "school_year" => Yii::app()->user->year]);
			$loads['school'] = $school->attributes;
			$loads['school']['tag_id'] = hexdec(crc32($school->inep_id.$school->name));
			foreach ($classrooms as $iclass => $classroom) {
				$hash_classroom = hexdec(crc32($school->inep_id.$classroom->id.$classroom->school_year));
				$loads['classrooms'][$iclass] = $classroom->attributes;
				$loads['classrooms'][$iclass]['fkid'] =  $classroom->id;
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
					$loads['instructorsteachingdata'] = $teachingData->attributes;
					if(!isset($loads['instructors'][$teachingData->instructor_fk])){
						$loads['instructors'][$teachingData->instructor_fk] = $teachingData->instructorFk->attributes;
					}
					if(!isset($loads['instructorsvariabledata'][$teachingData->instructor_fk])) {
						$loads['instructorsvariabledata'][$teachingData->instructor_fk] = $teachingData->instructorFk->instructorVariableData->attributes;
					}
				}

			}
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

		}
		public function actionExportToMaster() {
			$importToFile = FALSE;
			try {
				Yii::app()->db2;
			} catch (Exception $e) {
				$importToFile = TRUE;
			}

			ini_set('memory_limit', '256M');

			$sql = "";

			$tables = [
				"school_identification", "school_structure", "classroom", "instructor_identification",
				"instructor_documents_and_address", "instructor_variable_data", "instructor_teaching_data",
				"student_identification", "student_documents_and_address", "student_enrollment"
			];

			$classroom_tagId = $studentIndetification_tagId = array();



			foreach ($tables as $index => $table) {
				$objects = array();
				switch ($table){
					case "school_identification":
						
						break;
					case "school_structure":
						$objects = SchoolStructure::model()->findAllByPk(yii::app()->user->school);
						break;
					case "classroom":
						$objects = Classroom::model()->findAllByAttributes(["school_inep_fk" => yii::app()->user->school, "school_year" => Yii::app()->user->year]);
						break;
				}
			}

			for ($i = 0; $i < count($tables); $i++) {
				$array = [];
				$objects = "";
				switch ($i) {
					case "0":
						$objects = SchoolIdentification::model()->findAllByPk(yii::app()->user->school);
						break;
					case "1":
						$objects = SchoolStructure::model()->findAllByPk(yii::app()->user->school);
						break;
					case "2":
						$objects = Classroom::model()->findAllByAttributes(["school_inep_fk" => yii::app()->user->school, "school_year" => date("Y")]);
						break;
					case "3":
						$query = "select ii.* from instructor_teaching_data itd join instructor_identification ii on ii.id = itd.instructor_fk join classroom c on itd.classroom_id_fk = c.id where itd.school_inep_id_fk = :school_inep_id_fk and c.school_year = :year";
						$objects = InstructorIdentification::model()->findAllBySql($query, [":school_inep_id_fk" => yii::app()->user->school, ":year" => date("Y")]);
						break;
					case "4":
						$query = "select idaa.* from instructor_teaching_data itd join instructor_documents_and_address idaa on idaa.id = itd.instructor_fk join classroom c on itd.classroom_id_fk = c.id where itd.school_inep_id_fk = :school_inep_id_fk and c.school_year = :year";
						$objects = InstructorDocumentsAndAddress::model()->findAllBySql($query, [":school_inep_id_fk" => yii::app()->user->school, ":year" => date("Y")]);
						break;
					case "5":
						$query = "select ivd.* from instructor_teaching_data itd join instructor_variable_data ivd on ivd.id = itd.instructor_fk join classroom c on itd.classroom_id_fk = c.id where itd.school_inep_id_fk = :school_inep_id_fk and c.school_year = :year";
						$objects = InstructorVariableData::model()->findAllBySql($query, [":school_inep_id_fk" => yii::app()->user->school, ":year" => date("Y")]);
						break;
					case "6":
						$query = "select itd.* from instructor_teaching_data itd join instructor_identification ii on ii.id = itd.instructor_fk join classroom c on itd.classroom_id_fk = c.id where itd.school_inep_id_fk = :school_inep_id_fk and c.school_year = :year";
						$objects = InstructorTeachingData::model()->findAllBySql($query, [":school_inep_id_fk" => yii::app()->user->school, ":year" => date("Y")]);
						break;
					case "7":
						$query = "select si.* from student_identification si join student_enrollment se on si.id = se.student_fk join classroom c on c.id = se.classroom_fk where c.school_year = :year and se.school_inep_id_fk = :school_inep_id_fk";
						$objects = StudentIdentification::model()->findAllBySql($query, [":school_inep_id_fk" => yii::app()->user->school, ":year" => date("Y")]);
						break;
					case "8":
						$query = "select sdaa.* from student_documents_and_address sdaa join student_enrollment se on sdaa.id = se.student_fk join classroom c on c.id = se.classroom_fk where c.school_year = :year and se.school_inep_id_fk = :school_inep_id_fk";
						$objects = StudentDocumentsAndAddress::model()->findAllBySql($query, [":school_inep_id_fk" => yii::app()->user->school, ":year" => date("Y")]);
						break;
					case "9":
						$query = "select se.* from student_enrollment se join classroom c on c.id = se.classroom_fk where c.school_year = :year and se.school_inep_id_fk = :school_inep_id_fk";
						$objects = StudentEnrollment::model()->findAllBySql($query, [":school_inep_id_fk" => yii::app()->user->school, ":year" => date("Y")]);
						break;
				}
				foreach ($objects as $object) {
					if ($i == 0) { //remover atributo blob do school_identification
						$object->logo_file_content = "";
					}
					$attributesArray = [];
					foreach($object->attributes as $key => $attr) {
						$attr = addslashes($attr);
						$attributesArray[$key] = $attr;
					}
					array_push($array, $attributesArray);
				}
				$keys = array_keys($array[0]);
				$sql .= "INSERT INTO $tables[$i]";
				switch ($i) {
					case "0":
						$sql .= " (`" . implode("`, `", $keys) . "`, `tag_id`) VALUES";
						break;
					case "1":
						$sql .= " (`" . implode("`, `", $keys) . "`, `tag_id`) VALUES";
						break;
					case "2":
						$sql .= " (`" . implode("`, `", $keys) . "`, `tag_id`) VALUES";
						break;
					case "3":
						$sql .= " (`" . implode("`, `", $keys) . "`, `tag_id`) VALUES";
						break;
					case "4":
						$sql .= " (`" . implode("`, `", $keys) . "`, `tag_id`) VALUES";
						break;
					case "5":
						$sql .= " (`" . implode("`, `", $keys) . "`, `tag_id`) VALUES";
						break;
					case "6":
						$sql .= " (`" . implode("`, `", $keys) . "`, `tag_id`,  `classroom_tag_id`) VALUES";
						break;
					case "7":
						$sql .= " (`" . implode("`, `", $keys) . "`, `tag_id`) VALUES";
						break;
					case "8":
						$sql .= " (`" . implode("`, `", $keys) . "`, `tag_id`) VALUES";
						break;
					case "9":
						$sql .= " (`" . implode("`, `", $keys) . "`, `tag_id`,  `student_identification_tag_id`, `fk_classroom_tag_id`) VALUES";
						break;
				}
				//$sql .= " (`" . implode("`, `", $keys) . "`, `tag_id`) VALUES";

				foreach ($array as $value) {
					$tagId = "";
					switch ($i) {
						case "0":
							$tagId = md5($value["inep_id"]);
							$sql .= " ('" . str_replace("''", "null", implode("', '", $value)) . "', '" . $tagId . "'),";
							break;
						case "1":
							$tagId = md5($value["school_inep_id_fk"]);
							$sql .= " ('" . str_replace("''", "null", implode("', '", $value)) . "', '" . $tagId . "'),";
							break;
						case "2":
							$tagId = md5($value["school_inep_fk"] . $value["name"] . $value["school_year"]);
							$sql .= " ('" . str_replace("''", "null", implode("', '", $value)) . "', '" . $tagId . "'),";
							$classroom_tagId[$value['id']] =  $tagId;
							break;
						case "3":
							$tagId = md5($value["name"] . $value["birthday_date"]);
							$sql .= " ('" . str_replace("''", "null", implode("', '", $value)) . "', '" . $tagId . "'),";
							break;
						case "4":
							$instructorIdentification = InstructorIdentification::model()->findByAttributes(["id" => $value["id"]]);
							$tagId = md5($instructorIdentification->name . $instructorIdentification->birthday_date);
							$sql .= " ('" . str_replace("''", "null", implode("', '", $value)) . "', '" . $tagId . "'),";
							break;
						case "5":
							$instructorIdentification = InstructorIdentification::model()->findByAttributes(["id" => $value["id"]]);
							$tagId = md5($instructorIdentification->name . $instructorIdentification->birthday_date);
							$sql .= " ('" . str_replace("''", "null", implode("', '", $value)) . "', '" . $tagId . "'),";
							break;
						case "6":
							$instructorIdentification = InstructorIdentification::model()->findByAttributes(["id" => $value["instructor_fk"]]);
							$classroom = Classroom::model()->findByAttributes(["id" => $value["classroom_id_fk"]]);
							$tagId = md5($instructorIdentification->name . $instructorIdentification->birthday_date . $classroom->name . $classroom->school_year);
							$sql .= " ('" . str_replace("''", "null", implode("', '", $value)) . "', '" . $tagId . "', '" . $classroom_tagId[$classroom->id] . "'),";
							break;
						case "7":
							$tagId = md5($value["name"] . $value["birthday"]);
							$sql .= " ('" . str_replace("''", "null", implode("', '", $value)) . "', '" . $tagId . "'),";
							$studentIndetification_tagId[$value['id']] = $tagId;
							break;
						case "8":
							$studentIdentification = StudentIdentification::model()->findByAttributes(["id" => $value["id"]]);
							$tagId = md5($studentIdentification->name . $studentIdentification->birthday);
							$sql .= " ('" . str_replace("''", "null", implode("', '", $value)) . "', '" . $tagId . "'),";
							break;
						case "9":
							$studentIdentification = StudentIdentification::model()->findByAttributes(["id" => $value["student_fk"]]);
							$classroom = Classroom::model()->findByAttributes(["id" => $value["classroom_fk"]]);
							$tagId = md5($studentIdentification->name . $studentIdentification->birthday . $classroom->name . $classroom->school_year);
							$sql .= " ('" . str_replace("''", "null", implode("', '", $value)) . "', '" . $tagId . "', '" . $studentIndetification_tagId[$studentIdentification->id] . "', '" . $classroom_tagId[$classroom->id] . "'),";
							break;
					}
					//$sql .= " ('" . str_replace("''", "null", implode("', '", $value)) . "', '" . $tagId . "'),";
				}
				$sql = substr($sql, 0, -1) . " ON DUPLICATE KEY UPDATE ";
				foreach ($keys as $key) {
					$sql .= "`" . $key . "` = VALUES(`" . $key . "`), ";
				}
				$sql = substr($sql, 0, -2) . ";";
			}
			if ($importToFile) {
				ini_set('memory_limit', '128M');
				$fileName = "./app/export/exportSQL " . date("Y-m-d") . ".sql";
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
				try {
					yii::app()->db2->schema->commandBuilder->createSqlCommand($sql)->query();
				}catch (Exception $e){
					var_dump($e);exit;
				}
				yii::app()->db2->schema->commandBuilder->createSqlCommand($sql)->query();
				ini_set('memory_limit', '128M');
				Yii::app()->user->setFlash('success', Yii::t('default', 'Escola exportada com sucesso!'));
				$this->redirect(['index']);
			}
		}

		/**
		 * Descobre similaridade em uma tabela.
		 *
		 * @param $table String Nome da tabela que será analisada.
		 * @param $column String Campo que será analisado.
		 * @param $distance Integer Valor para verificar similaridade, menor que esse valor é dado como igual.
		 * @return array Array contendo os Tag Ids das linhas semelhantes.
		 */
		private function getSimilarRows($table, $column, $distance) {
			$sql = "select distinct least(t1.tag_id, t2.tag_id) as tg1, greatest(t1.tag_id, t2.tag_id) as tg2
						from $table t1
					  join $table t2 on (
					  		t1.tag_id <> t2.tag_id
					  		and levenshtein(t1.$column, t2.$column) < $distance);";

			return Yii::app()->db2->schema->commandBuilder->createSqlCommand($sql)->query();
		}

		private function getTableRow($object, $value = NULL, $where = NULL) {
			$table = $object->tableName();
			if ($where == NULL) {
				$sql = "select * from $table where tag_id = '$value';";
			} else {
				$sql = "select * from $table where $where;";
			}
			$array = Yii::app()->db2->schema->commandBuilder->createSqlCommand($sql)->queryAll();
			if (count($array) > 1) {
				$objects = [];
				foreach ($array as $arr) {
					$ob = $object;
					$ob->id = $arr["id"];
					$ob->attributes = $arr;
					array_push($objects, $ob);
				}
				return $objects;
			} else {
				$object->id = $array[0]["id"];
				$object->attributes = $array[0];
				return $object;
			}

		}


		public function actionCleanMaster() {

			$conflicts = ['students' => [], 'instructors' => []];

			//Professor
			$instructorTgs = $this->getSimilarRows(InstructorIdentification::model()->tableName(), "name", 5);
			foreach ($instructorTgs as $row) {
				$instructor1 = $this->getTableRow(new InstructorIdentification(), $row["tg1"]);
				$instructor2 = $this->getTableRow(new InstructorIdentification(), $row["tg2"]);
				$instructor1->documents = $this->getTableRow(new InstructorDocumentsAndAddress(), NULL, "school_inep_id_fk = $instructor1->school_inep_id_fk and id = $instructor1->id");
				$instructor2->documents = $this->getTableRow(new InstructorDocumentsAndAddress(), NULL, "school_inep_id_fk = $instructor2->school_inep_id_fk and id = $instructor2->id");
				$instructor1->instructorVariableData = $this->getTableRow(new InstructorVariableData(), NULL, "school_inep_id_fk = $instructor1->school_inep_id_fk and id = $instructor1->id");
				$instructor2->instructorVariableData = $this->getTableRow(new InstructorVariableData(), NULL, "school_inep_id_fk = $instructor2->school_inep_id_fk and id = $instructor2->id");
				$instructor1->instructorTeachingDatas = $this->getTableRow(new InstructorTeachingData(), NULL, "school_inep_id_fk = $instructor1->school_inep_id_fk and instructor_fk = $instructor1->id");
				$instructor2->instructorTeachingDatas = $this->getTableRow(new InstructorTeachingData(), NULL, "school_inep_id_fk = $instructor2->school_inep_id_fk and instructor_fk = $instructor2->id");
				array_push($conflicts['instructors'], [$instructor1, $instructor2]);
			}

			//Turma
			$classroomSql = "select distinct c1.* from classroom c1 join classroom c2 on (c1.tag_id <> c2.tag_id and c1.school_inep_fk = c2.school_inep_fk and c1.name = c2.name and c1.school_year = c2.school_year) order by name";
			$classrooms = Yii::app()->db2->schema->commandBuilder->createSqlCommand($classroomSql)->query();
			foreach($classrooms as $c) {
				$classroom = new Classroom();
				$classroom->attributes = $c;
			}

//			//Professor
//			$instructorIdentificationSql = "select distinct c1.* from classroom c1 join classroom c2 on (c1.tag_id <> c2.tag_id and c1.school_inep_fk = c2.school_inep_fk and c1.name = c2.name and c1.school_year = c2.school_year) order by name";
//			$instructorsIdentification = Yii::app()->db2->schema->commandBuilder->createSqlCommand($instructorIdentificationSql)->query();
//			foreach($instructorsIdentification as $ii) {
//				$instructor = new InstructorIdentification();
//				$instructor->attributes = $ii;
//			}

			//Aluno
			$studentTgs = $this->getSimilarRows(StudentIdentification::model()->tableName(), "name", 5);
			foreach ($studentTgs as $row) {
				$student1 = $this->getTableRow(new StudentIdentification(), $row["tg1"]);
				$student2 = $this->getTableRow(new StudentIdentification(), $row["tg2"]);
				$student1->documentsFk = $this->getTableRow(new StudentDocumentsAndAddress(), NULL, "school_inep_id_fk = $student1->school_inep_id_fk and student_fk = $student1->id");
				$student2->documentsFk = $this->getTableRow(new StudentDocumentsAndAddress(), NULL, "school_inep_id_fk = $student2->school_inep_id_fk and student_fk = $student2->id");
				$student1->studentEnrollments = $this->getTableRow(new StudentEnrollment(), NULL, "school_inep_id_fk = $student1->school_inep_id_fk and student_fk = $student1->id");
				$student2->studentEnrollments = $this->getTableRow(new StudentEnrollment(), NULL, "school_inep_id_fk = $student2->school_inep_id_fk and student_fk = $student2->id");
				array_push($conflicts['students'], [$student1, $student2]);
			}

			$this->render('conflicts',["conflicts" => $conflicts]);
		}

		public function actionImportFromMaster() {

//			$tables = [
//				"school_identification", "school_structure", "classroom", "instructor_identification",
//				"instructor_documents_and_address", "instructor_variable_data", "instructor_teaching_data",
//				"student_identification", "student_documents_and_address", "student_enrollment"
//			];
//
//			for ($i = 0; $i < count($tables); $i++) {
//				$model = $pk = $object = null;
//				switch ($i) {
//					case "0":
//						$model = SchoolIdentification::model();
//						$pk = "inep_id";
//						$sqlCondition = "inep_id = :id";
//						$object = new SchoolIdentification();
//						break;
//					case "1":
//						$model = SchoolStructure::model();
//						$pk = "school_inep_id_fk";
//						$sqlCondition = "school_inep_id_fk = :id";
//						$object = new SchoolStructure();
//						break;
//					case "2":
//						$model = Classroom::model();
//						$pk = "id";
//						$sqlCondition = "school_inep_fk = :school and id = :id";
//						$schoolKey = "school_inep_fk";
//						$object = new Classroom();
//						break;
//					case "3":
//						$model = InstructorIdentification::model();
//						$pk = "id";
//						$sqlCondition = "school_inep_id_fk = :school and id = :id";
//						$schoolKey = "school_inep_id_fk";
//						$object = new InstructorIdentification();
//						break;
//					case "4":
//						$model = InstructorDocumentsAndAddress::model();
//						$pk = "id";
//						$sqlCondition = "school_inep_id_fk = :school and id = :id";
//						$schoolKey = "school_inep_id_fk";
//						$object = new InstructorDocumentsAndAddress();
//						break;
//					case "5":
//						$model = InstructorVariableData::model();
//						$pk = "id";
//						$sqlCondition = "school_inep_id_fk = :school and id = :id";
//						$schoolKey = "school_inep_id_fk";
//						$object = new InstructorVariableData();
//						break;
//					case "6":
//						$model = InstructorTeachingData::model();
//						$pk = "id";
//						$sqlCondition = "school_inep_id_fk = :school and id = :id";
//						$schoolKey = "school_inep_id_fk";
//						$object = new InstructorTeachingData();
//						break;
//					case "7":
//						$model = StudentIdentification::model();
//						$pk = "id";
//						$sqlCondition = "school_inep_id_fk = :school and id = :id";
//						$schoolKey = "school_inep_id_fk";
//						$object = new StudentIdentification();
//						break;
//					case "8":
//						$model = StudentDocumentsAndAddress::model();
//						$pk = "id";
//						$sqlCondition = "school_inep_id_fk = :school and id = :id";
//						$schoolKey = "school_inep_id_fk";
//						$object = new StudentDocumentsAndAddress();
//						break;
//					case "9":
//						$model = StudentEnrollment::model();
//						$pk = "id";
//						$sqlCondition = "school_inep_id_fk = :school and id = :id";
//						$schoolKey = "school_inep_id_fk";
//						$object = new StudentEnrollment();
//						break;
//				}
//				$sql = "select * from $tables[$i];";
//				$db2Arrays = yii::app()->db2->createCommand($sql)->queryAll();
//				foreach ($db2Arrays as $db2array) {
//					if($i < 2){
//						$dbObject = $model->find($sqlCondition, [
//							":id" => $db2array[$pk]
//						]);
//					}else {
//						$dbObject = $model->find($sqlCondition, [
//							":id" => $db2array[$pk], ":school" => $db2array[$schoolKey]
//						]);
//					}
//					if ($dbObject == NULL) {
//						$dbObject = $object;
//					}
//					if ($i == 0) {
//						$logoFileContent = $dbObject->logo_file_content;
//						$dbObject->attributes = $db2array;
//						$dbObject->logo_file_content = $logoFileContent;
//					} else {
//						$dbObject->attributes = $db2array;
//					}
//					$dbObject->save();
//				}
//			}
			$this->redirect(['index']);
		}
	}

?>
