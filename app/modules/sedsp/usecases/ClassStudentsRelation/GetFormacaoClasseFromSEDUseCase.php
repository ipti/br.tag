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

            $numClass = $inFormacaoClasse->getInNumClasse();
            $tagClassroom = Classroom::model()->find('inep_id = :govId or gov_id = :govId', [':govId' => $numClass]);

            $status = true;
            $students = $mapper->Students;
            foreach ($students as $student) {
                try {
                    $studentModel = self::findStudentIdentificationByGovId($student->gov_id);
                    
                    if (!isset($studentModel)) {
                        $inAluno = new InAluno($student->gov_id, null, $student->uf);
                        $statusSaveStudent = $this->getExibirFichaAlunoFromSEDUseCase->exec($inAluno);
                        
                        if($statusSaveStudent === true) {
                            $studentModels = self::findStudentIdentificationByGovId($student->gov_id);
                            $this->createEnrollment($tagClassroom, $studentModels);
                        }
                    } else {
                        $statusEnrollment = StudentEnrollment::model()->find(
                            'classroom_fk = :classroomFk and student_fk = :studentFk',
                            [':classroomFk' => $numClass, ':studentFk' => $studentModel->id]
                        );
                        
                        if(!isset($statusEnrollment)) {
                            $this->createEnrollment($tagClassroom, $studentModel);
                        }
                    }
                }
                catch (\Throwable $th) {
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

    public function createCSVFile($name, $dados) {
        
        $path = 'app/modules/sedsp/numberOfStudentsCSV/';
        if(!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $name = $path . $name .".csv";
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
        $findedEnrollment = $this->studentDatabaseSearch($classroom->school_inep_fk, $studentModel->id, $classroom->id);

        if ($findedEnrollment !== null) {
            return false; // Já existe um aluno matriculado
        }

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
     * Summary of studentDatabaseSearch
     * @param string $schoolInepFk
     * @param string $studentGovId
     * @param string $classroomGovId
     * @return StudentEnrollment
     */
    private function studentDatabaseSearch($schoolInepFk, $studentFk, $classroomGovId)
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