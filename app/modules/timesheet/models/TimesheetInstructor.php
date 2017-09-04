<?php

	/**
	 *
	 * @property integer $id
	 * @property string $name
	 *
	 * The followings are the available model relations:
	 * @property EdcensoStageVsModality[] $stages
	 * @property EdcensoDiscipline[] $disciplines
	 * @property InstructorDisciplines[] $instructorStages
	 * @property InstructorDisciplines[] $instructorDisciplines
	 */
	class TimesheetInstructor extends CActiveRecord {
		public static function model($className = __CLASS__) {
			return parent::model($className);
		}

		/**
		 * @return string the associated database table name
		 */
		public function tableName() {
			return 'instructor_identification';
		}

		public function relations() {
			return [
				'stages' => [
					self::HAS_MANY, 'EdcensoStageVsModality', 'instructor_fk', 'with' => 'instructor_stages',
					'order' => 'stageVsModalityFk.name asc'
				], 'instructor_stages' => [
					self::MANY_MANY, 'InstructorStages', 'instructor_disciplines(instructor_fk, stage_vs_modality_fk)',
					'order' => 'name asc'
				],

				'disciplines' => [
					self::HAS_MANY, 'EdcensoDiscipline', 'instructor_fk', 'with' => 'instructor_disciplines',
					'order' => 'disciplineFk.name asc'
				], 'instructor_disciplines' => [
					self::MANY_MANY, 'InstructorDisciplines', 'instructor_disciplines(instructor_fk, discipline_fk)',
					'order' => 'name asc'
				],
			];
		}

		public function countConflicts($week_day, $turn, $schedule) {

			$schedules = Schedule::model()->findAll("instructor_fk = :instructor and turn = :turn and week_day = :week_day and schedule = :schedule", [
				":instructor" => $this->id, ":turn" => $turn, ":week_day" => $week_day, ":schedule" => $schedule
			]);

			return count($schedules);
		}

		public function isUnavailable($week_day, $turn, $schedule) {
			$instructorSchool = InstructorSchool::model()->find("instructor_fk = :instructor and school_fk = :school", [
				":instructor" => $this->id, ":school" => Yii::app()->user->school,
			]);
			$una = Unavailability::model()->findAll("instructor_school_fk = :instructorSchool and turn = :turn and week_day = :week_day and schedule = :schedule", [
				":instructorSchool" => $instructorSchool->id, ":turn" => $turn, ":week_day" => $week_day,
				":schedule" => $schedule
			]);

			return $una != NULL;
		}

		public function getInstructorUnavailabilities($turn) {
			$instructorSchool = InstructorSchool::model()->find("instructor_fk = :instructor and school_fk = :school", [
				":instructor" => $this->id, ":school" => Yii::app()->user->school,
			]);
			$unavailabilities = Unavailability::model()->findAll("instructor_school_fk = :instructorSchool and turn = :turn", [
				":instructorSchool" => $instructorSchool->id, ":turn" => $turn,
			]);
			$schedules = Schedule::model()->findAll("instructor_fk = :instructor and turn = :turn", [
				":instructor" => $this->id, ":turn" => $turn,
			]);

			$count = 0;
			$result = [0 => [], 1 => [], 2 => [], 3 => [], 4 => [], 5 => [], 6 => [], 'count' => 0];
			/**
			 * @var $un Unavailability
			 */
			foreach ($unavailabilities as $un) {
				array_push($result[$un->week_day], $un->schedule);
				$count++;
			}
			/**
			 * @var $sc Schedule
			 */
			foreach ($schedules as $sc) {
				array_push($result[$sc->week_day], $sc->schedule);
				$count++;
			}
			$result['count'] = $count;

			return $result;

		}


	}