<?php

	class TimesheetController extends Controller {
		/**
		 * @return array action filters
		 */
		public function filters() {
			return [
				'accessControl', // perform access control for CRUD operations
				'postOnly + delete', // we only allow deletion via POST request
			];
		}

		/**
		 * Specifies the access control rules.
		 * This method is used by the 'accessControl' filter.
		 * @return array access control rules
		 */
		public function accessRules() {
			return [
				[
					'allow',  // allow all users to perform 'index' and 'view' actions
					'actions' => [], 'users' => ['*'],
				], [
					'allow', // allow authenticated user to perform 'create' and 'update' actions
					'actions' => ['index', 'instructors', 'GetInstructorDisciplines', 'addInstructors', 'loadUnavailability'],
					'users' => ['@'],
				], [
					'allow', // allow admin user to perform 'admin' and 'delete' actions
					'actions' => [], 'users' => ['admin'],
				], [
					'deny',  // deny all users
					'users' => ['*'],
				],
			];
		}


		public function actionIndex() {
			$this->render('index');
		}

		public function actionInstructors() {
			$this->render('instructors');
		}

		public function actionGetInstructorDisciplines($id) {
			/** @var $istructorDisciplines InstructorDisciplines[]
			 * @var $idisc InstructorDisciplines
			 */
			$response = [];
			$instructorDisciplines = InstructorDisciplines::model()->findAllByAttributes(["instructor_fk" => $id]);
			foreach ($instructorDisciplines as $idisc) {
				array_push($response, [
					"instructor" => $id, "discipline" => $idisc->discipline_fk,
					"discipline_name" => $idisc->disciplineFk->name, "stage" => $idisc->stage_vs_modality_fk,
					"stage_name" => $idisc->stageVsModalityFk->name,
				]);
			}
			echo json_encode($response);
		}

		public function actionAddInstructors() {
			$ids = $_POST["add-instructors-ids"];
			$school = Yii::App()->user->school;
			foreach ($ids as $id) {
				$instructor = InstructorSchool::model()->findAllByAttributes([
					"instructor_fk" => $id, "school_fk" => $school
				]);
				if (count($instructor) == 0) {
					$instructor = new InstructorSchool();
					$instructor->school_fk = $school;
					$instructor->instructor_fk = $id;
					if ($instructor->validate()) {
						$instructor->save();
					}
				}
			}
			$this->render('instructors');
		}


		public function actionAddInstructorsUnavailability() {
			$instructorsIds = $_POST["add-instructors-unavailability-ids"];
			$initials = $_POST["add-instructors-unavailability-initial"];
			$finals = $_POST["add-instructors-unavailability-final"];
			$weekDays = $_POST["add-instructors-unavailability-week-day"];

			foreach ($instructorsIds as $instructorId) {
				foreach ($initials as $key => $initial) {
					$final = $finals[$key];
					$weekDay = $weekDays[$key];
					$unavailability = new Unavailability();
					$unavailability->instructor_fk = $instructorId;
					$unavailability->week_day = $weekDay;
					$unavailability->initial_hour = $initial;
					$unavailability->final_hour = $final;
					$unavailability->save();
				}
			}
			$this->render('instructors');
		}

		public function actionLoadUnavailability(){
			/** @var  $iu Unavailability */
			$instructorId = $_POST["id"];
			$instructorUnavailability = Unavailability::model()->findAll("instructor_fk = :instructor", [":instructor"=>$instructorId]);
			$response = [];
			foreach($instructorUnavailability as $iu){
				$response[$iu->week_day] = ['initial'=>$iu->initial_hour, 'final'=>$iu->final_hour];
			}
			echo json_encode($response);
		}
	}