<?php


class QuizWidget extends CWidget{

    public $type;
    public $questions;

    public function init(){

        $quiz = Quiz::model()->findByPk($this->quiz);
        $questions = $quiz->questions;
        $groups = $quiz->questionsGroups;
        $groupedQuestions = [];
        $query = Yii::app()->db->createCommand()
                    ->select()
                    ->from('question q')
                    ->join('quiz_question qq', 'q.id=qq.question_id')
                    ->join('quiz qz', 'qz.id=qq.quiz_id')
                    ->leftJoin('question_group qg', 'qz.id=qg.quiz_id')
                    ->leftJoin('question_group_question qgq', 'qg.id=qgq.question_group_id AND q.id=qgq.question_id');

        foreach ($questions as $question) {
            foreach ($question->questionGroups as $group) {
                $groupedQuestions[$group->id][] = $question;
            }
        }
    }

    public function run(){
        $this->render('form', ['questions' => $this->questions]);
    }
}
?>