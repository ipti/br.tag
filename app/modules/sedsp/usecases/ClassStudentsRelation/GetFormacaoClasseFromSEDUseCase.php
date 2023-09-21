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
    ) {
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
            $mapper = (object) ClassroomMapper::parseToTAGFormacaoClasse($response);
            $alunosTurma = $response->getOutAlunos();
            $students = $mapper->Students;
            $classroom = $mapper->Classroom;
            $tagClassroom = Classroom::model()->find('inep_id = :govId or gov_id = :govId', [':govId' => $inFormacaoClasse->getInNumClasse()]);

            $status = true;
            foreach ($students as $student) {
                try {
                    $studentModel = self::findStudentIdentificationByGovId($student->gov_id);
                    
                    if (!isset($studentModel)) {
                        $inAluno = new InAluno($student->gov_id, null, $student->uf);
                        $studentModel = $this->getExibirFichaAlunoFromSEDUseCase->exec($inAluno);
                    }
                    
                    $alunoTurma = $this->searchAlunoTurma($studentModel->gov_id, $alunosTurma);
                    $this->createEnrollment($tagClassroom, $studentModel, $alunoTurma);
                }
                catch (\Throwable $th) {
                    Yii::log($th->getMessage(), CLogger::LEVEL_WARNING);
                    $status = false;
                }
            }

            return $status;
        } catch (Exception $e) {
            CVarDumper::dump($e->getMessage(), 10, true);
            return false;
        }
    }

    /**
     * Summary of createEnrollment
     * @param Classroom $classroom
     * @param StudentIdentification $studentModel
     * 
     * @return StudentEnrollment|bool
     */
    private function createEnrollment($classroom, $studentModel)
    {
        $studentEnrollmentModel = $this->searchStudentEnrollmentInDb($classroom->school_inep_fk, $studentModel->id, $classroom->id);
        if ($studentEnrollmentModel === null) {
            $studentEnrollment = new StudentEnrollment();
            $studentEnrollment->school_inep_id_fk = $classroom->school_inep_fk;
            $studentEnrollment->student_inep_id = $studentModel->inep_id;
            $studentEnrollment->student_fk = $studentModel->id;
            $studentEnrollment->classroom_fk = $classroom->id;
            $studentEnrollment->status = $this->mapStatusEnrollmentFromSed("2");
            $studentEnrollment->school_admission_date = date("d/m/Y");
            
            if ($studentEnrollment->validate() && $studentEnrollment->save()) {
                Yii::log('Aluno matriculado com sucesso.', CLogger::LEVEL_INFO);
                return true;
            } else {
                Yii::log($studentEnrollment->getErrors(), CLogger::LEVEL_ERROR);
                return false;
            }
        }

        return $studentEnrollmentModel;
    }

    private function mapStatusEnrollmentFromSed($codSituation){
        $mapSEDToTAGSituations = [
            "0"  => 1, // ATIVO/ENCERRADO                     => MATRICULADO
            "2"  => 4, // ABANDONOU                           => DEIXOU DE FREQUENTAR
            "1"  => 2, // TRANSFERIDO                         => TRANSFERIDO
            "31" => 2, // BAIXA – TRANSFERÊNCIA               => TRANSFERIDO
            "19" => 2, // TRANSFERIDO - CEEJA / EAD           => TRANSFERIDO
            "16" => 2, // TRANSFERIDO (CONVERSÃO DO ABANDONO) => TRANSFERIDO
            "10" => 5, // REMANEJADO                          => Remanejado
            "17" => 5, // REMANEJADO (CONVERSÃO DO ABANDONO)  => Remanejado
            "4"  => 11, // FALECIDO                           => FALECIDO
            "5"  => 4, // NÃO COMPARECIMENTO                  => Deixou de Frequentar
            "18" => 4, // NÃO COMPARECIMENTO / FORA DO PRAZO  => Deixou de Frequentar
            "20" => 4, // NÃO COMPARECIMENTO - CEEJA / EAD    => Deixou de Frequentar
            "3"  => 5, // RECLASSIFICADO                      => Remanejado
        ];

        if(array_key_exists($codSituation, $mapSEDToTAGSituations)){
            return $mapSEDToTAGSituations[$codSituation];
        }

        return 10;
    }

    /**
     * Summary of searchAlunoTurma
     * @param string $govId
     * @param OutAlunos[] $enrollments
     * @return OutAlunos | false
     */
    private function searchAlunoTurma($govId, $enrollments)
    {
        foreach ($enrollments as $enrollment) {
            if ($enrollment->outNumRA === $govId) {
                return $enrollment;
            }
        }
        return false;
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
     * Summary of searchStudentEnrollmentInDb
     * @param string $schoolInepFk
     * @param string $studentGovId
     * @param string $classroomGovId
     * @return StudentEnrollment
     */
    private function searchStudentEnrollmentInDb($schoolInepFk, $studentFk, $classroomGovId)
    {
        return StudentEnrollment::model()->find(
            'school_inep_id_fk = :school_inep_id_fk AND student_fk = :student_fk AND classroom_fk = :classroom_fk',
            [
                ':school_inep_id_fk' => $schoolInepFk,
                ':student_fk' => $studentFk,
                ':classroom_fk' => $classroomGovId
            ]
        );
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