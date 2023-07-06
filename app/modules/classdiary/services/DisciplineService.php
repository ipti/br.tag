<?php

class DisciplineService
{
    public function getDiscipline($discipline_fk){
        $sql = "SELECT * FROM edcenso_discipline ed WHERE ed.id = :discipline_fk";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':discipline_fk', $discipline_fk, PDO::PARAM_INT);
        $discipline = $command->queryAll();
        return $discipline;
    }
}