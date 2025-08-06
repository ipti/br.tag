<?php

class GenerateRaUseCase
{
    public function exec($studentId)
    {
        $student = StudentIdentification::model()->findByPk($studentId);
        $studentDocuments = StudentDocumentsAndAddress::model()->find('student_fk = :studentFk', [':studentFk' => $student->id]);

        if (null === $student->filiation_1) {
            return 'Nome completo da mãe não pode ser nulo';
        }

        $inConsultaRA = new InConsultaRA(null, $student->name, $student->filiation_1, $student->birthday);
        $studentSEDDataSource = new StudentSEDDataSource();
        $statusConsult = $studentSEDDataSource->getStudentRA($inConsultaRA);

        if (null !== $statusConsult->getOutErro()) {
            return $statusConsult->getOutErro();
        }

        $outStudent = $statusConsult->getOutAluno();

        if (null !== $outStudent->getOutNumRa()) {
            $student->gov_id = $outStudent->getOutNumRa();
            $student->save();

            return 'RA já cadastrado';
        }

        $studentToSedMapper = new StudentMapper();
        $studentInfo = (object) $studentToSedMapper->parseToSEDAlunoFicha($student, $studentDocuments);
        $inFichaAluno = new InFichaAluno(
            $studentInfo->InDadosPessoais,
            $studentInfo->InDeficiencia,
            $studentInfo->InRecursoAvaliacao,
            $studentInfo->InDocumentos,
            null,
            null,
            $studentInfo->InEnderecoResidencial,
            null
        );

        $studentSEDDataSource = new StudentSEDDataSource();

        return $studentSEDDataSource->addStudentToSed($inFichaAluno);
    }
}
