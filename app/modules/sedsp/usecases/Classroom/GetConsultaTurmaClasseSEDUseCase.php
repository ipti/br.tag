<?php

class GetConsultaTurmaClasseSEDUseCase
{
    /**
     * Summary of exec.
     * @throws InvalidArgumentException
     */
    public function exec(InConsultaTurmaClasse $inConsultaTurmaClasse)
    {
        $classroomSEDDataSource = new ClassroomSEDDataSource();
        $response = $classroomSEDDataSource->getConsultClass($inConsultaTurmaClasse);
        $classroomModel = (object) ClassroomMapper::parseToTAGConsultaClasse($inConsultaTurmaClasse->getInNumClasse(), $response);

        return $classroomModel->validate() && $classroomModel->save();
    }
}
