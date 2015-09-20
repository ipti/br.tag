<?php

class CAfterSaveBehavior extends CActiveRecordBehavior {
    
    public $schoolInepId = '';
    
    public function afterSave($event) {
       // $id = $this->getOwner()->id;
       // $newId = $this->schoolInepId.";" . $id;
       // Yii::app()->db->createCommand('UPDATE `course_plan` SET `fkid` = "'.$newId.'" WHERE `id` = '.$id.';')->query();
    }
    
}