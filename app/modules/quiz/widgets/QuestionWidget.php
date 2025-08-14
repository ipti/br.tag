<?php

class QuestionWidget extends CWidget
{
    public $model;
    protected $view;

    public function init()
    {
        switch ($this->model->question->type) {
            case '1':
                $this->view = 'subjective';
                break;
            case '2':
                $this->view = 'objective';
                break;
            case '3':
                $this->view = 'objectiveMultiple';
                break;
            case '4':
                $this->view = 'subjectiveText';
                break;
        }
    }

    public function run()
    {
        if (isset($this->view)) {
            $this->render($this->view, ['model' => $this->model]);
        }
    }
}
