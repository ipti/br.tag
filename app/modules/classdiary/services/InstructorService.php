<?php

class InstructorService 
{
    public function getDisciplines() {
        $sql = "SELECT ed.id, ed.name from teaching_matrixes tm 
        join instructor_teaching_data itd on itd.id = tm.teaching_data_fk 
        join instructor_identification ii on ii.id = itd.instructor_fk
        join curricular_matrix cm on cm.id = tm.curricular_matrix_fk
        join edcenso_discipline ed on ed.id = cm.discipline_fk
        join classroom c on c.id = itd.classroom_id_fk  
        where ii.users_fk = :users_fk and c.school_year = :user_year order by ed.name";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':users_fk', Yii::app()->user->loginInfos->id, PDO::PARAM_INT)
        ->bindValue(':user_year', Yii::app()->user->year, PDO::PARAM_INT);

        $query = $command->queryAll();
        $disciplines = array();

        foreach ($query as $d) {
            $id = $d['id'];
            $name = $d['name'];
            $disciplines[$id] = $name;
          }
           
        return $disciplines;
    }
}