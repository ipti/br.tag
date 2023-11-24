<?php


/**
 * Summary of GetFormacaoClasseFromSEDUseCase
 * @property ClassStudentsRelationSEDDataSource $classStudentsRelationSEDDataSource
 * @property GetExibirFichaAlunoFromSEDUseCase $getExibirFichaAlunoFromSEDUseCase
 */
class GetFormacaoClasseFromSEDUseCase
{

    /**
     * Summary of exec
     * @param InFormacaoClasse $inNumClasse
     * @throws InvalidArgumentException
     */

    public function __construct(
        ClassStudentsRelationSEDDataSource $classStudentsRelationSEDDataSource = null,
        GetExibirFichaAlunoFromSEDUseCase $getExibirFichaAlunoFromSEDUseCase = null
    )
    {
        $this->classStudentsRelationSEDDataSource = isset($classStudentsRelationSEDDataSource) ? $classStudentsRelationSEDDataSource : new ClassStudentsRelationSEDDataSource();
        $this->getExibirFichaAlunoFromSEDUseCase = isset($getExibirFichaAlunoFromSEDUseCase) ? $getExibirFichaAlunoFromSEDUseCase : new GetExibirFichaAlunoFromSEDUseCase();
    }

    /**
     * Summary of exec
     * @param InFormacaoClasse $inNumClasse
     * @return bool
     */
    public function exec(InFormacaoClasse $inFormacaoClasse)
    {
        try {
            $response = $this->classStudentsRelationSEDDataSource->getClassroom($inFormacaoClasse);

            $mapper = (object)ClassroomMapper::parseToTAGFormacaoClasse($response);

            $numClass = $inFormacaoClasse->getInNumClasse();
            $tagClassroom = Classroom::model()->find('inep_id = :govId or gov_id = :govId', [':govId' => $numClass]);

            $status = true;
            $students = $mapper->Students;
            foreach ($students as $student) {
                try {
                    $inAluno = new InAluno($student->gov_id, null, $student->uf);
                    $studentIdentification = $this->getExibirFichaAlunoFromSEDUseCase->exec($inAluno);
                    $this->createEnrollment($tagClassroom, $studentIdentification);
                
                } catch (\Throwable $th) {
                    $log = new LogError();
                    $log->salvarDadosEmArquivo($th->getMessage());
                    $status = false;
                }
            }

            $count = StudentEnrollment::model()->count(
                'classroom_fk = :classroomId', array(':classroomId' => $tagClassroom->id)
            );

            $dados = [[$tagClassroom->gov_id, $count, $response->outQtdAtual],];
            $this->createCSVFile($tagClassroom->gov_id, $dados);

            return $status;
        } catch (Exception $e) {
            $log = new LogError();
            $log->salvarDadosEmArquivo($e->getMessage());
            return false;
        }
    }

    public function createCSVFile($name, $dados)
    {

        $path = 'app/modules/sedsp/numberOfStudentsCSV/';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $name = $path . $name . ".csv";
        $arquivo = fopen($name, 'w');

        // Verifica se o arquivo foi aberto com sucesso
        if ($arquivo === false) {
            return false; // Falha ao abrir o arquivo
        }

        foreach ($dados as $linha) {
            fputcsv($arquivo, $linha, ';');
        }

        fclose($arquivo);

        return true;
    }

    /**
     * Summary of createEnrollment
     * @param Classroom $classroom
     * @param StudentIdentification $studentModel
     *
     * @return bool
     */
    private function createEnrollment($classroom, $studentModel)
    {
        $enrollments = StudentMapper::getListMatriculasRa($studentModel->gov_id);
        foreach($enrollments as $enrollment) {
            if ($enrollment->getOutNumClasse() == $classroom->gov_id) {
                $studentEnrollment = StudentEnrollment::model()->find('student_fk = :student_fk AND classroom_fk = :classroom_fk', [':student_fk' => $studentModel->id, ':classroom_fk' => $classroom->id]);
                if ($studentEnrollment == null) {
                    $studentEnrollment = new StudentEnrollment();
                    $studentEnrollment->school_inep_id_fk = $classroom->school_inep_fk;
                    $studentEnrollment->student_inep_id = $studentModel->inep_id;
                    $studentEnrollment->student_fk = $studentModel->id;
                    $studentEnrollment->classroom_fk = $classroom->id;
                    $studentEnrollment->create_date = date("d/m/Y");
                }
                $studentEnrollment->status = StudentMapper::mapSituationEnrollmentToTag($enrollment->getOutCodSitMatricula());
                $studentEnrollment->sedsp_sync = 1;
                $studentEnrollment->save();

                if ($studentEnrollment->validate() && $studentEnrollment->save()) {
                    Yii::log('Aluno matriculado com sucesso.', CLogger::LEVEL_INFO);
                    return true;
                } else {
                    Yii::log($studentEnrollment->getErrors(), CLogger::LEVEL_ERROR);
                    return false;
                }
                break;
            }
        }
    }

    /**
     * Summary of findStudentIdentificationByGovId
     * @param string $studentGovId
     * @return StudentIdentification
     */
    public function findStudentIdentificationByGovId($studentGovId)
    {
        return StudentIdentification::model()->find('gov_id = :govId or inep_id = :govId', [':govId' => $studentGovId]);
    }

    /**
     * Summary of findStudentIdentificationByName
     * @param mixed $studentName
     * @return mixed
     */
    public function findStudentIdentificationByName($studentName)
    {
        return StudentIdentification::model()->find('name = :name', [':name' => $studentName])->id;
    }

    /**
     * Summary of createNewStudent
     * @param mixed $inNumRA
     * @param mixed $inDigitoRA
     * @param mixed $inSiglaUFRA
     * @return InAluno
     */
    public function createNewStudent($inNumRA, $inDigitoRA = null, $inSiglaUFRA)
    {
        return new InAluno($inNumRA, $inDigitoRA, $inSiglaUFRA);
    }

    /**
     * Summary of getFichaAluno
     * @param InAluno $inAluno
     */
    public function getFichaAluno(InAluno $inAluno)
    {
        return $this->getExibirFichaAlunoFromSEDUseCase->exec($inAluno);
    }
}