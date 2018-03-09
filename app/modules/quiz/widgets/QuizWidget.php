<?php


class QuizWidget extends CWidget{

    public $type;
    public $model;
    protected $view;

    public function init(){
        switch ($this->type) {
            case '1':
                $this->view = 'subjective';
                break;
            case '2':
                $this->view = 'ojective';
                break;
            case '3':
                $this->view = 'ojectiveMultiple';
                break;
            case '4':
                $this->view = 'selection';
                break;
        }
    }

    public function run(){
        if(isset($this->view)){
            $this->render($this->view, ['model' => $this->model]);
        }
    }
}
?>