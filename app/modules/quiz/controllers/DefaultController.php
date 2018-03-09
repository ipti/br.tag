<?php

class DefaultController extends Controller
{

	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionCreateQuiz()
	{
		$quiz = new Quiz;

		if(isset($_POST['Quiz'])){
			$quiz->attributes = $_POST['Quiz'];
			if($quiz->validate()){
				$quiz->create_date = date('Y-m-d');
				if($quiz->save()){
					Yii::app()->user->setFlash('success', Yii::t('default', 'Questionário cadastrado com sucesso'));
				}
			}
		}
		$this->render('quiz/create', ['quiz' => $quiz]);
	}
}