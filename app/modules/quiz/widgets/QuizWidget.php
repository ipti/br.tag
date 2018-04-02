<?php


class QuizWidget extends CWidget{

    public $type;
    public $quizId;
    public $studentId;
    protected $groups;
    protected $groupedQuestions;
    protected $quiz;

    public function init(){

        $this->quiz = Quiz::model()->findByPk($this->quizId);
        $questions = $this->quiz->questions;
        $this->groups = $this->quiz->questionGroups;
        $student = StudentIdentification::model()->findByPk($this->studentId);

        $this->groupedQuestions = [];

        foreach ($questions as $question) {
            if(is_array($this->groups)){
                foreach ($this->groups as $group) {
                    foreach ($question->questionGroups as $questionGroup) {
                        if($group->id == $questionGroup->id){
                            $this->groupedQuestions[$group->id][] = new FormQuestion($this->quiz, $question, $student);
                        }
                    }
                }
            }
            else{
                $this->groupedQuestions[0][] = new FormQuestion($this->quiz, $question, $student);
            }
        }
    }

    public function run(){
        $this->render('form', ['groups' => $this->groups, 'questions' => $this->groupedQuestions, 'quiz' => $this->quiz]);
    }
}
?>