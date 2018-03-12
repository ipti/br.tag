<?php

class DefaultController extends Controller
{

	public function actionIndex() {
        $filter = new Quiz('search');
		$filter->unsetAttributes();
		
        if (isset($_GET['Quiz'])) {
            $filter->attributes = $_GET['Quiz'];
		}
		
        $dataProvider = new CActiveDataProvider('Quiz', array('pagination' => array(
                'pageSize' => 12,
		)));
		
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'filter' => $filter
        ));
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

	public function actionUpdateQuiz($id)
	{
		$quiz = Quiz::model()->findByPk($id);

		if(isset($_POST['Quiz'])){
			$quiz->attributes = $_POST['Quiz'];
			if($quiz->validate()){
				$quiz->create_date = date('Y-m-d');
				if($quiz->save()){
					Yii::app()->user->setFlash('success', Yii::t('default', 'Questionário atualizado com sucesso'));
				}
			}
		}
		$this->render('quiz/update', ['quiz' => $quiz]);
	}
}