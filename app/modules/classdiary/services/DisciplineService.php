<?php

class DisciplineService
{
    public function getDiscipline($disciplineFk)
    {
        $sql = 'SELECT * FROM edcenso_discipline ed WHERE ed.id = :discipline_fk';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':discipline_fk', $disciplineFk, PDO::PARAM_INT);
        return $command->queryAll();
    }
}
