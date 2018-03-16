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

		// ================== Question Group action ==================

		public function actionQuestionGroup() {
			$filter = new QuestionGroupQuestion('search');
			$filter->unsetAttributes();
			
			if (isset($_GET['QuestionGroupQuestion'])) {
				$filter->attributes = $_GET['QuestionGroupQuestion'];
			}
			
			$dataProvider = new CActiveDataProvider('QuestionGroupQuestion', array('pagination' => array(
					'pageSize' => 12,
			)));
			
			$this->render('questiongroup/index', array(
				'dataProvider' => $dataProvider,
				'filter' => $filter
			));
		}

		public function actionCreateQuestionGroup()
		{
			$questionGroup = new QuestionGroupQuestion;
	
			if(isset($_POST['QuestionGroupQuestion'])){
				$questionGroup->attributes = $_POST['QuestionGroupQuestion'];
				if($questionGroup->validate()){
					if($questionGroup->save()){
						Yii::app()->user->setFlash('success', Yii::t('default', 'Questão adicionada ao grupo'));
					}
				}
			}
			$this->render('questiongroup/create', ['questionGroup' => $questionGroup]);
		}

		public function actionUpdateQuestionGroup($questionId, $questionGroupId)
		{
			$questionGroup = QuestionGroupQuestion::model()->findByPk(['question_group_id' => $questionGroupId, 'question_id' => $questionId]);
	
			if(isset($_POST['QuestionGroupQuestion'])){
				$questionGroup->attributes = $_POST['QuestionGroupQuestion'];
				if($questionGroup->validate()){
					if($questionGroup->updateByPk(['question_group_id' => $questionGroupId, 'question_id' => $questionId], ['question_group_id' => $questionGroup->question_group_id, 'question_id' => $questionGroup->question_id])){
						Yii::app()->user->setFlash('success', Yii::t('default', 'Grupo de questões atualizado com sucesso'));
					}
					else{
						Yii::app()->user->setFlash('success', Yii::t('default', 'Erro ao atualizar grupo de questões'));
					}
				}
			}
			$this->render('questiongroup/update', ['questionGroup' => $questionGroup]);
		}

		public function actionDeleteQuestionGroup($questionId, $questionGroupId)
		{
			$questionGroup = QuestionGroupQuestion::model()->findByPk(['question_group_id' => $questionGroupId, 'question_id' => $questionId]);
	
			if(isset($_POST['QuestionGroupQuestion'])){
				if($questionGroup->delete()){
					Yii::app()->user->setFlash('success', Yii::t('default', 'Grupo de questões excluído'));
				}
				else{
					$questionGroup->attributes = $_POST['QuestionGroupQuestion'];
					Yii::app()->user->setFlash('success', Yii::t('default', 'Erro ao excluir grupo de questões'));
					return $this->render('questiongroup/update', ['questionGroup' => $questionGroup]);
				}
			}

			$this->actionQuestionGroup();
		}

	// ================== Question action ==================

	public function actionQuestion() {
		$filter = new Question('search');
		$filter->unsetAttributes();
		
		if (isset($_GET['Question'])) {
			$filter->attributes = $_GET['Question'];
		}
		
		$dataProvider = new CActiveDataProvider('Question', array('pagination' => array(
				'pageSize' => 12,
		)));
		
		$this->render('question/index', array(
			'dataProvider' => $dataProvider,
			'filter' => $filter
		));
	}

	public function actionCreateQuestion()
	{
		$question = new Question;
		$option = new QuestionOption;

		if(isset($_POST['Question'])){
			$question->attributes = $_POST['Question'];
			if($question->validate()){
				if($question->save()){
					Yii::app()->user->setFlash('success', Yii::t('default', 'Questão cadastrada com sucesso'));
				}
			}
		}
		$this->render('question/create', ['question' => $question, 'option' => $option]);
	}

	public function actionUpdateQuestion($id)
	{
		$question = Question::model()->findByPk($id);
		$option = new QuestionOption;

		if(isset($_POST['Question'])){
			$question->attributes = $_POST['Question'];
			if($question->validate()){
				if($question->save()){
					Yii::app()->user->setFlash('success', Yii::t('default', 'Questão atualizada com sucesso'));
				}
				else{
					Yii::app()->user->setFlash('success', Yii::t('default', 'Erro ao atualizar questões'));
				}
			}
		}
		$this->render('question/update', ['question' => $question, 'option' => $option]);
	}

	public function actionDeleteQuestion($id)
	{
		$question = Question::model()->findByPk($id);
		$option = new QuestionOption;

		if(isset($_POST['Question'])){
			if($question->delete()){
				Yii::app()->user->setFlash('success', Yii::t('default', 'Questão excluído com sucesso'));
			}
			else{
				$question->attributes = $_POST['QuestionGroup'];
				Yii::app()->user->setFlash('error', Yii::t('default', 'Erro ao excluir questão'));
				return $this->render('question/update', ['group' => $question, 'option' => $option]);
			}
		}

		$this->actionQuestion();
	}

	public function actionCreateOption()
	{
		$option = new QuestionOption;
		$data = null;

		if(isset($_POST['QuestionOption'])){
			$option->attributes = $_POST['QuestionOption'];
			if($option->validate()){
				if($option->save()){
					$data = array('errorCode' => 0);
					$data = array_merge($data, $option->getAttributes());
				}
			}
			else{
				$data = array('errorCode' => 1);
				$data = array_merge($data, $option->getErrors());
			}
		}
		else{
			$data = array('errorCode' => 2, 'msg' => 'Parametro inválido');
		}

		header('Content-Type: application/json; charset="UTF-8"');
		echo json_encode($data);
		Yii::app()->end();
	}
}