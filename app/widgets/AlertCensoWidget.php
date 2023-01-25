<?php


class AlertCensoWidget extends CWidget
{
    public $prefix;
    public $dataId;
    protected $data;

    public function init()
    {

        //$this->data = Yii::app()->cache->get($this->prefix.$this->dataId);
    }

    public function run()
    {
        /* if($this->data){
             $this->render('alert', ['data' => $this->data]);
         }*/
    }
}
