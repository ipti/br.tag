<?php

/**
 * @property ClassroomSEDDataSource $studentSEDDataSource
 */
class CreateClassroom
{
    /**
     * Summary of exec
     * @param int $RA RA Number
     * @return Classroom
     */
    public function exec($num_classe)
    {
        $ucclassroom = new GetClassroomFromSED();
        $classroom = $ucclassroom->exec($num_classe);
        if($classroom["Classroom"]->inep_id != null) {
            return $classroom;
        }else {
            throw new Exception("Ocorreu um erro ao cadastrar a turma. Certifique-se de inserir dados válidos.", 500);
        }
    }
}
