<?php

class FormQuestion extends CModel
{
    public $quiz;
    public $question;
    public $answer;
    public $student;

    public function __construct($quiz, $question, $student, $answer = null)
    {
        // parent::__construct();
        $this->quiz = $quiz;
        $this->question = $question;
        $this->student = $student;
        if (!is_null($answer)) {
            $this->answer = $answer;
        } else {
            $this->answer = new Answer();
        }
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('quiz, question, answer, student', 'safe')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeNames()
    {
        return array(
            'quiz' => 'Quiz',
            'question' => 'Question',
            'student' => 'Student',
            'answer' => 'Answer'
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FormQuestion the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getIdentifier()
    {
        return 'FormQuestion['. $this->quiz->id .']['. $this->question->id .']';
    }
}
