<?php

class EnrollmentonlinestudentidentificationRepository
{

    private $studentIdentification;

    public function __construct($model)
    {
        $this->studentIdentification = $model;
    }

    public function sanvePreEnrollment() {
        $this->studentIdentification->save();
        /*if($this->studentIdentification->save()){
            $
        } */

        // $transaction = Yii::app()->db->beginTransaction();
        // $transaction->commit();
        // $transaction->rollback();
    }

}
