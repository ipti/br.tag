<?php

class DefaultController extends CController
{
	public $headerDescription = "";

	public function actionIndex(){
		$this->render('index');
	}
	public function actionGetGMapInfo($lat, $lng){
		$year = Yii::app()->user->year;
		$sql = "SELECT si.name, si.inep_id, IFNULL(si.latitude,:lat)latitude, IFNULL(si.longitude,:lng)longitude, si.situation, si.location,
				  IFNULL(
				  (SELECT
						COUNT(c.id) AS classroomCount
				  FROM school_identification
						JOIN classroom c ON c.school_inep_fk = school_identification.inep_id
				  WHERE c.school_year = :year AND school_identification.inep_id = si.inep_id
				  GROUP BY school_identification.inep_id), 0) as classroomCount,
				  IFNULL(
				  (SELECT
						COUNT(se.id) AS enrollmentId
				  FROM school_identification school_identification
						JOIN classroom c ON c.school_inep_fk = school_identification.inep_id
						JOIN student_enrollment se ON c.id = se.classroom_fk
				  WHERE c.school_year = :year AND school_identification.inep_id = si.inep_id
				  GROUP BY school_identification.inep_id), 0) as enrollmentCount
				FROM school_identification si WHERE si.situation = 1 ORDER BY si.name;";

		$schools = Yii::app()->db->createCommand($sql)->queryAll(true, ["lat"=>$lat, "lng"=>$lng, "year"=>$year]);
		$schoolsArray = [];

		foreach($schools as $school){
			array_push($schoolsArray, $school);
		}
		echo json_encode($schoolsArray);
	}
}