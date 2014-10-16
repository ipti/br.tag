<?php

class ClassroomConfigurationController extends Controller {
	public function actionIndex() {
		if(isset($_POST['Classrooms'])){
			$Classrooms_ids = $_POST['Classrooms'];
			$year = Yii::app()->user->year;
			foreach($Classrooms_ids as $id){
				$classroom = Classroom::model()->findByPk($id);
				$class_board = ClassBoard::model()->findAllByAttributes(array('classroom_fk'=>$id));
				$teaching_data = InstructorTeachingData::model()->findAllByAttributes(array('classroom_id_fk'=>$id));
				
				$newClassroom = new Classroom();
				$newClassroom->attributes = $classroom->attributes;
				$newClassroom->school_year = $year;
				$newClassroom->id = null;
				$newClassroom->inep_id = null;
				$save = false;
				if($newClassroom->save()){
					$save = true;
					foreach ($class_board as $cb){
						$newClassBorad = new ClassBoard();
						$newClassBorad->attributes = $cb->attributes;
						$newClassBorad->id = null;
						$newClassBorad->classroom_fk = $newClassroom->id;
						$save = $save && $newClassBorad->save();
					}
					foreach ($teaching_data as $td){
						$newTeachingData = new InstructorTeachingData();
						$newTeachingData->attributes = $td->attributes;
						$newTeachingData->id = null;
						$newTeachingData->classroom_id_fk =  $newClassroom->id;
						$newTeachingData->classroom_inep_id = null;
						$save = $save && $newTeachingData->save();
					}
				}
			}
			if($save)
         		Yii::app()->user->setFlash('success', Yii::t('default', 'Turmas reutilizadas com sucesso!'));
			else
         		Yii::app()->user->setFlash('error', Yii::t('default', 'Erro na reutilização das Turmas.'));
		}
		$this->render('index');
	}
}