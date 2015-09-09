<?php

class DefaultController extends CController
{
	public function actionIndex(){
		$this->render('index');
	}
	public function actionGetGMapInfo(){

		$schools = SchoolIdentification::model()->findAll();
		$schoolsArray = [];
		foreach($schools as $school){
			array_push($schoolsArray, $school->attributes);
		}

		echo json_encode($schoolsArray);
	}
}