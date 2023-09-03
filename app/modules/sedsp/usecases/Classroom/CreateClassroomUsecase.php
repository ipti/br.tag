<?php

/**
 * @property ClassroomSEDDataSource $classroomSEDDatasource
 */
class CreateClassroomUsecase
{

    /**
     * Summary of __construct
     * @param ClassroomSEDDataSource $classroomSEDDatasource
     */
    public function __construct(ClassroomSEDDataSource $classroomSEDDatasource = null)
    {
        $this->classroomSEDDatasource = isset($classroomSEDDatasource) ? $classroomSEDDatasource : new ClassroomSEDDataSource();
    }

    /**
     * Summary of exec
     * @param int $RA RA Number
     * @return Classroom
     */
    public function exec($year, $sedCodClassroom)
    {
        $params = new InConsultaTurmaClasse($year, $sedCodClassroom);

        $classroomSed = $this->classroomSEDDatasource->getConsultClass($params);
        $classroomModel = ClassroomMapper::parseToTAGConsultaClasse($sedCodClassroom, $classroomSed);

        if ($classroomModel->validate() && $classroomModel->save()) {
            return $classroomModel;
        }

        throw new SedspException(CJSON::encode($classroomModel->getErrors()), 1);

    }
}