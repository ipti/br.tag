<?php

Yii::import('application.modules.sedsp.datasources.sed.Enrollment.*');

class EditStudentSedspUseCase
{

    /**
     * Summary of exec
     * @param OutListarAluno $outListStudent
     * @param object $student
     * @param mixed $studentIdentification
     * @return OutHandleApiResult
     */
    public function exec(OutListarAluno $outListStudent, object $student, $studentIdentification)
    {
        $numRA = $outListStudent->getOutListaAlunos()[0]->getOutNumRa();
        $student->InAluno->setInNumRA($numRA);

        $inManutencao = $this->createInManutencao($student);
                
        $studentIdentification->gov_id = $numRA;
        $studentIdentification->save();

        $dataSource = new StudentSEDDataSource();
        $statusEditStudent = $dataSource->editStudent($inManutencao);

        if($statusEditStudent->outErro === null){
            $studentIdentification->sedsp_sync = 1;
            $studentIdentification->save();
        }

        return $statusEditStudent;
    }

    // Função para criar objeto InManutencao em caso de aluno cadastrado
    /**
     * Summary of createInManutencao
     * @param mixed $student
     * @return InManutencao
     */
    public function createInManutencao($student) {
        return new InManutencao(
            $student->InAluno,
            $student->InDadosPessoais,
            $student->InDeficiencia,
            $student->InRecursoAvaliacao,
            $student->InDocumentos,
            null,
            null,
            $student->InEnderecoResidencial,
            null,
            null
        );
    }
}
