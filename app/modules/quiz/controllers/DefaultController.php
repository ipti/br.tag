<?php

class DefaultController extends Controller
{

	public function actionIndex() {
        $this->render('index');
	}
	
	public function actionQuiz() {
        $filter = new Quiz('search');
		$filter->unsetAttributes();
		
        if (isset($_GET['Quiz'])) {
            $filter->attributes = $_GET['Quiz'];
		}
		
        $dataProvider = new CActiveDataProvider('Quiz', array('pagination' => array(
                'pageSize' => 12,
		)));
		
        $this->render('quiz/quiz', array(
            'dataProvider' => $dataProvider,
            'filter' => $filter
        ));
    }

	public function actionCreateQuiz()
	{
		$quiz = new Quiz;
		$quizQuestion = new QuizQuestion;

		if(isset($_POST['Quiz'])){
			$quiz->attributes = $_POST['Quiz'];
			if($quiz->validate()){
				$quiz->create_date = date('Y-m-d');
				if($quiz->save()){
					Yii::app()->user->setFlash('success', Yii::t('default', 'Questionário cadastrado com sucesso'));
					$url = Yii::app()->createUrl('quiz/default/quiz');
					return $this->redirect($url);
				}
			}
		}
		$this->render('quiz/create', ['quiz' => $quiz, 'quizQuestion' => $quizQuestion]);
	}

	public function actionUpdateQuiz($id)
	{
		$quiz = Quiz::model()->findByPk($id);
		$quizQuestion = new QuizQuestion;

		if(isset($_POST['Quiz'])){
			$quiz->attributes = $_POST['Quiz'];
			if($quiz->validate()){
				$quiz->create_date = date('Y-m-d');
				if($quiz->save()){
					Yii::app()->user->setFlash('success', Yii::t('default', 'Questionário atualizado com sucesso'));
				}
			}
		}
		$this->render('quiz/update', ['quiz' => $quiz, 'quizQuestion' => $quizQuestion]);
	}

	public function actionDeleteQuiz($id)
	{
		$quiz = Quiz::model()->findByPk($id);
		$questions = $quiz->questions;
		$questionGroups = $quiz->questionGroups;
		$quizQuestion = new QuizQuestion;

		if(isset($_POST['Quiz'])){
			if(count($questions) == 0){
				if(count($questionGroups) == 0){

					if($quiz->delete()){
						Yii::app()->user->setFlash('success', Yii::t('default', 'Questionário excluído com sucesso'));
					}
					else{
						$quiz->attributes = $_POST['Quiz'];
						Yii::app()->user->setFlash('error', Yii::t('default', 'Erro ao excluir questionário'));
						return $this->render('quiz/update', ['quiz' => $quiz, 'quizQuestion' => $quizQuestion]);
					}
				}
				else{
					$quiz->attributes = $_POST['Quiz'];
					Yii::app()->user->setFlash('error', Yii::t('default', 'Existe grupo vinculado ao questionário'));
					return $this->render('quiz/update', ['quiz' => $quiz, 'quizQuestion' => $quizQuestion]);
				}
			}
			else{
				$quiz->attributes = $_POST['Quiz'];
				Yii::app()->user->setFlash('error', Yii::t('default', 'Existe questão vinculada ao questionário'));
				return $this->render('quiz/update', ['quiz' => $quiz, 'quizQuestion' => $quizQuestion]);
			}
		}

		$url = Yii::app()->createUrl('quiz/default/quiz');
		return $this->redirect($url);
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
					$url = Yii::app()->createUrl('quiz/default/group');
					return $this->redirect($url);
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
		$questions = $group->questions;

		if(isset($_POST['QuestionGroup'])){
			if(count($questions) == 0){
				if($group->delete()){
					Yii::app()->user->setFlash('success', Yii::t('default', 'Grupo excluído com sucesso'));
				}
				else{
					$group->attributes = $_POST['QuestionGroup'];
					Yii::app()->user->setFlash('error', Yii::t('default', 'Erro ao excluir grupo'));
					return $this->render('group/update', ['group' => $group]);
				}
			}
			else{
				$group->attributes = $_POST['QuestionGroup'];
				Yii::app()->user->setFlash('error', Yii::t('default', 'Existe questão vinculada ao grupo'));
				return $this->render('group/update', ['group' => $group]);
			}
		}

		$url = Yii::app()->createUrl('quiz/default/group');
		return $this->redirect($url);
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
				$existsQuestionGroup = QuestionGroupQuestion::model()->findByPk(['question_group_id' => $_POST['QuestionGroupQuestion']['question_group_id'], 'question_id' => $_POST['QuestionGroupQuestion']['question_id']]);
				
				if(is_null($existsQuestionGroup)){
					if($questionGroup->validate()){
						if($questionGroup->save()){
							Yii::app()->user->setFlash('success', Yii::t('default', 'Questão adicionada ao grupo'));
							return $this->actionQuestionGroup();
						}
					}
				}
				else{
					Yii::app()->user->setFlash('error', Yii::t('default', 'A questão já está presente no grupo'));
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

			$url = Yii::app()->createUrl('quiz/default/questionGroup');
			return $this->redirect($url);
		}

	// ================== Question action ==================

	public function actionQuestion() {
		$filter = new Question('search');
		$filter->unsetAttributes();
		
		if (isset($_GET['Question'])) {
			$filter->attributes = $_GET['Question'];
		}
		
		$dataProvider = new CActiveDataProvider('Question', array('pagination' => false));
		
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
					$url = Yii::app()->createUrl('quiz/default/question');
					return $this->redirect($url);
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

		$url = Yii::app()->createUrl('quiz/default/question');
		return $this->redirect($url);
	}

	public function actionCreateOption()
	{
		$option = new QuestionOption;
		$data = null;

		if(isset($_GET['QuestionOption'])){
			$option->attributes = $_GET['QuestionOption'];
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

	public function actionUpdateOption($id)
	{
		$data = null;
		$option = QuestionOption::model()->findByPk($id);

		if(isset($_GET['QuestionOption'])){
			$option->attributes = $_GET['QuestionOption'];
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

	public function actionDeleteOption($id)
	{
		$data = null;
		$option = QuestionOption::model()->findByPk($id);

		if(isset($_GET['QuestionOption'])){
			if($option->delete()){
				$data = array('errorCode' => 0, 'id' => $id, 'msg' => 'Erro ao excluir item');
			}
			else{
				$data = array('errorCode' => 1, 'msg' => 'Erro ao excluir item');
			}
		}
		else{
			$data = array('errorCode' => 2, 'msg' => 'Parametro inválido');
		}

		header('Content-Type: application/json; charset="UTF-8"');
		echo json_encode($data);
		Yii::app()->end();
	}

	public function actionSetQuizQuestion($quizId, $questionId)
	{
		$data = null;
		$quizQuestion = QuizQuestion::model()->findByPk(array('quiz_id' => $quizId, 'question_id' => $questionId));

		if(is_null($quizQuestion)){
			$quizQuestion = new QuizQuestion();
			$question = Question::model()->findByPk($questionId);
			$quizQuestion->attributes = $_GET['QuizQuestion'];
			if($quizQuestion->save()){
				$data = array('errorCode' => 0, 'quizId' => $quizId, 'questionId' => $questionId, 'description' => $question->description, 'msg' => 'Questão adicionada');
			}
			else{
				$data = array('errorCode' => 1, 'msg' => 'Erro ao adicionar questão');
			}
		}
		else{
			$data = array('errorCode' => 2, 'msg' => 'Questão já adicionada');
		}

		header('Content-Type: application/json; charset="UTF-8"');
		echo json_encode($data);
		Yii::app()->end();
	}

	public function actionUnsetQuizQuestion($quizId, $questionId)
	{
		$data = null;
		$quizQuestion = QuizQuestion::model()->findByPk(array('quiz_id' => $quizId, 'question_id' => $questionId));

		if(!is_null($quizQuestion)){
			$question = Question::model()->findByPk($questionId);
			if($quizQuestion->delete()){
				$data = array('errorCode' => 0, 'quizId' => $quizId, 'questionId' => $questionId, 'description' => $question->description, 'msg' => 'Questão excluída');
			}
			else{
				$data = array('errorCode' => 1, 'msg' => 'Erro ao excluir questão');
			}
		}
		else{
			$data = array('errorCode' => 2, 'msg' => 'Questão não encontrada');
		}

		header('Content-Type: application/json; charset="UTF-8"');
		echo json_encode($data);
		Yii::app()->end();
	}

	// ===================== Answer action =======================

	public function actionAnswer($quizId, $studentId) {
		if(isset($_POST['FormQuestion'])){
			$data = $_POST['FormQuestion'][$quizId];
			$connection = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			$answered = Answer::model()->find('quiz_id=:quiz_id AND student_id=:student_id', [':quiz_id' => $quizId, ':student_id' => $studentId]);

			if(is_null($answered)){
				try{
	
					foreach ($data as $questionId => $response) {
						$sql="INSERT INTO answer (quiz_id, question_id, student_id, seq, option_id, value, complement) VALUES(:quiz_id, :question_id, :student_id, :seq, :option_id, :value, :complement)";
						$seq = 1;
						$complementNull = NULL;
						if(is_array($response)){
							foreach ($response as $key => $value) {
								$command = $connection->createCommand($sql);
								$command->bindParam(":quiz_id", $quizId, PDO::PARAM_INT);
								$command->bindParam(":question_id", $questionId, PDO::PARAM_INT);
								$command->bindParam(":student_id", $studentId, PDO::PARAM_INT);
								$command->bindParam(":seq", $seq, PDO::PARAM_INT);
								$command->bindParam(":option_id", $key, PDO::PARAM_INT);
								$command->bindParam(":value", $value['response'], PDO::PARAM_STR);
								if(isset($value['complement'])){
									$command->bindParam(":complement", $value['complement'], PDO::PARAM_STR);
								}
								else{
									$command->bindParam(":complement", $complementNull, PDO::PARAM_NULL);
								}
								$command->execute();
								++$seq;
							}
						}
						else{
							$command = $connection->createCommand($sql);
							$command->bindParam(":quiz_id", $quizId, PDO::PARAM_INT);
							$command->bindParam(":question_id", $questionId, PDO::PARAM_INT);
							$command->bindParam(":student_id", $studentId, PDO::PARAM_INT);
							$command->bindParam(":seq", $seq, PDO::PARAM_INT);
							$command->bindParam(":option_id", $seq, PDO::PARAM_INT);
							$command->bindParam(":value", $response, PDO::PARAM_STR);
							$command->bindParam(":complement", $complementNull, PDO::PARAM_NULL);
							$command->execute();
						}
					}
					$transaction->commit();
					Yii::app()->user->setFlash('success', Yii::t('default', 'Questionário salvo com sucesso'));
				}
				catch(Exception $e){
					$transaction->rollback();
					Yii::app()->user->setFlash('error', Yii::t('default', 'Erro ao salvar questionário'));
				}
			}
			else{
				Yii::app()->user->setFlash('error', Yii::t('default', 'O aluno já respondeu este questionário'));
			}
		}

        $this->render('answer/view', array(
            'quizId' => $quizId,
            'studentId' => $studentId
        ));
    }


}