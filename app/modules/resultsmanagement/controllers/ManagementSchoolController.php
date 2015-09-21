<?php

class ManagementSchoolController extends CController
{
	public $headerDescription = "";

	public function actionIndex($sid){
		$school = SchoolIdentification::model()->findByPk($sid);
		$this->render('index', ["school"=>$school]);
	}

	public function actionPerformance($sid){
		$school = SchoolIdentification::model()->findByPk($sid);
		$this->render('performance', ["school"=>$school]);
	}

	public function actionFrequency($sid){
		/* @var $classrooms Classroom[]*/
		$school = SchoolIdentification::model()->findByPk($sid);
		$classrooms = Classroom::model()->findAll(["condition" => "school_inep_fk = :sid and school_year = :year", "order"=>"name", "params"=>[":sid"=> $sid,":year"=> yii::app()->user->year]]);
		$classroomOptions = [];
		foreach($classrooms as $classroom)
			$classroomOptions[$classroom->id] = CHtml::encode($classroom->name);
		$classroomOptions["all"] = yii::t("resultsmanagementModule.managementSchool","All Classrooms");
		$this->render('frequency', ["school"=>$school, "classrooms"=>$classroomOptions]);
	}

	public function actionLoadClassroomInfos($sid,$cid){
		$results = null;
		if($cid == "all"){
			$sql = "SELECT distinct cs.month, IFNULL(d.id,'-1') did, IFNULL(d.name,'Disciplinas Fundamental Menor') discipline FROM classroom as c
					join class cs on (cs.classroom_fk = c.id)
					left join edcenso_discipline d on (cs.discipline_fk = d.id)
				where school_inep_fk = :sid and school_year = :year;";
			$results = yii::app()->db->createCommand($sql)->queryAll(true,[":sid"=>$sid, ":year"=>yii::app()->user->year]);
		}else if(!empty($cid)) {
			$sql = "SELECT distinct cs.month, IFNULL(d.id,'-1') did, IFNULL(d.name,'Disciplinas Fundamental Menor') discipline FROM classroom as c
					join class cs on (cs.classroom_fk = :cid)
					left join edcenso_discipline d on (cs.discipline_fk = d.id);";
			$results = yii::app()->db->createCommand($sql)->queryAll(true,[":cid"=>$cid]);
		}else{
			return false;
		}
		echo json_encode($results);

	}

	public function actionLoadChartData($sid, $cid, $mid, $did){
		$sql = "Select cr.id cid, cr.name cname, day, month,  d.id did, IFNULL(d.name,'Disciplinas Fundamental Menor') dname, count(cf.class_fk) as faults from class c
					left join class_faults cf on (c.id = cf.class_fk)
					join classroom cr on (cr.id = c.classroom_fk)
					left join edcenso_discipline d on (discipline_fk = d.id)
					where cr.school_year = :year and cr.school_inep_fk = :sid " .
			($cid != "all" ? "and cr.id = :cid " : " ") .
			($mid != "all" ? "and month = :mid " : " ") .
			($did == "all" ? " " : ($did == "-1" ? " and d.id is null " : "and d.id  = :did ")) .
			"group by c.day, c.month, c.discipline_fk
					order by c.month, c.day, c.discipline_fk;";
		$i = 0;
		$params = [":sid" => $sid,":year" => yii::app()->user->year,];
		($cid != "all" ? $params[":cid"] = $cid : $i );
		($mid != "all" ? $params[":mid"] = $mid : $i );
		($did != "all" && $did != "-1" ? $params[":did"] = $did : $i);
		$faults = yii::app()->db->createCommand($sql)->queryAll(true, $params);

		$sql = "Select count(*) from student_enrollment e
					join classroom cr on (cr.id = e.classroom_fk)
					where cr.school_year = :year and cr.school_inep_fk = :sid;";
		$enrollments = yii::app()->db->createCommand($sql)->queryAll(true, [":sid" => $sid,":year" => yii::app()->user->year,]);

		echo json_encode(["faults"=>$faults,"enrollments"=>$enrollments]);
	}
}