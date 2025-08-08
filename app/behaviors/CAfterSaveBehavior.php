<?php

class CAfterSaveBehavior extends CActiveRecordBehavior
{
    public $schoolInepId = '';

    public function afterSave($event)
    {// Noncompliant - method is empty
    }
}
