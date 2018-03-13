<?php

class DefaultController extends Controller
{

	public function actionQuiz() {
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

	public function actionDeleteQuiz($id)
	{
		$quiz = Quiz::model()->findByPk($id);

		if(isset($_POST['Quiz'])){
			if($quiz->delete()){
				Yii::app()->user->setFlash('success', Yii::t('default', 'Questionário excluído com sucesso'));
			}
			else{
				$quiz->attributes = $_POST['Quiz'];
				Yii::app()->user->setFlash('error', Yii::t('default', 'Erro ao excluir questionário'));
				return $this->render('quiz/update', ['quiz' => $quiz]);
			}
		}

		$this->actionQuiz();
	}

	// ================== Group action ==================

	public function actionGroup() {
        $filter = new QuestionGroup('search');
		$filter->unsetAttributes();
		
        if (isset($_GET['QuestionGroup'])) {
            $filter->attributes = $_GET['QuestionGroup'];
		}
		
        $dataProvider = new CActiveDataProvider('QuestionGroup', array('pagination' => array(
                'pageSize' => 12,
		)));
		
        $this->render('group/index', array(
            'dataProvider' => $dataProvider,
            'filter' => $filter
        ));
	}
	
	public function actionCreateGroup()
	{
		$group = new QuestionGroup;

		if(isset($_POST['QuestionGroup'])){
			$group->attributes = $_POST['QuestionGroup'];
			if($group->validate()){
				if($group->save()){
					Yii::app()->user->setFlash('success', Yii::t('default', 'Grupo cadastrado com sucesso'));
				}
			}
		}
		$this->render('group/create', ['group' => $group]);
	}

	public function actionUpdateGroup($id)
	{
		$group = QuestionGroup::model()->findByPk($id);

		if(isset($_POST['QuestionGroup'])){
			$group->attributes = $_POST['QuestionGroup'];
			if($group->validate()){
				if($group->save()){
					Yii::app()->user->setFlash('success', Yii::t('default', 'Grupo atualizado com sucesso'));
				}
				else{
					Yii::app()->user->setFlash('success', Yii::t('default', 'Erro ao atualizar grupo'));
				}
			}
		}
		$this->render('group/update', ['group' => $group]);
	}

	public function actionDeleteGroup($id)
	{
		$group = QuestionGroup::model()->findByPk($id);

		if(isset($_POST['QuestionGroup'])){
			if($group->delete()){
				Yii::app()->user->setFlash('success', Yii::t('default', 'Grupo excluído com sucesso'));
			}
			else{
				$group->attributes = $_POST['QuestionGroup'];
				Yii::app()->user->setFlash('error', Yii::t('default', 'Erro ao excluir grupo'));
				return $this->render('group/update', ['group' => $group]);
			}
		}

		$this->actionGroup();
	}
}