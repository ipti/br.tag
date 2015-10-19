<?php

class DefaultController extends CController{
	public $headerDescription = "";
    public $eid = 5294;

	public function actionIndex(){
		$this->render('index');
	}

	public function actionGrades(){
        /* @var $enrollment StudentEnrollment
         * @var $classroom Classroom
         * @var $classboards ClassBoard[]
         * @var $cb ClassBoard
         */
        $enrollment = StudentEnrollment::model()->findByPk($this->eid);
        $classroom = $enrollment->classroomFk;
        $classboards = $classroom->classBoards;
        $disciplines =[];
        foreach ($classboards as $cb) {
            $discipline = $cb->disciplineFk;
            $disciplines[$discipline->id] = $discipline->name;
        }
        asort($disciplines, SORT_STRING);
        $this->render('grades',['disciplines'=>$disciplines]);
	}

	public function actionGetGrades(){
        /* @var $grade Grade*/
        $grades = Grade::model()->findAll("enrollment_fk = :eid", [":eid"=>$this->eid]);

        $result = [];
        foreach($grades as $grade){
            $result[$grade->disciplineFk->name] = $grade->attributes;
        }
        echo json_encode($result);
    }
}