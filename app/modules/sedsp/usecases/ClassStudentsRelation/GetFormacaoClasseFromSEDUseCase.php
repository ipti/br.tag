<?php


/**
 * Summary of GetFormacaoClasseSEDUseCase
 */
class GetFormacaoClasseFromSEDUseCase
{

    /**
     * Summary of exec
     * @param InFormacaoClasse $inNumClasse
     * @throws InvalidArgumentException 
     */
    function exec(InFormacaoClasse $inNumClasse)
    {
        try {
            $formacaoClasseDataSource = new ClassStudentsRelationSEDDataSource();
            $response = $formacaoClasseDataSource->getClassroom($inNumClasse);

            $mapper = (object) ClassroomMapper::parseToTAGFormacaoClasse($response);
           
            $classroom = $mapper->Classroom;
            $students = $mapper->Students;
            
            foreach ($students as $student) {    
                $inAluno = $this->createNewStudent($student->gov_id, null, $student->uf);
                $this->getFichaAluno($inAluno);
                
                /* $studentEnrollmentModel =  $this->searchStudentEnrollmentInDb($classroom->school_inep_fk, $student->gov_id, $classroom->gov_id);
                if($studentEnrollmentModel === null){
                    $studentEnrollment = new StudentEnrollment();
                    $studentEnrollment->school_inep_id_fk = $classroom->school_inep_fk;
                    $studentEnrollment->student_inep_id = $student->inep_id;

                    $studentEnrollment->student_fk = ($id !== null) ? $id : $this->findStudentIdentificationByName($student->name);
                    
                    $studentEnrollment->classroom_fk = Classroom::model()->find('gov_id = :govId', [':govId' => $classroom->gov_id])->id;
                    
                    if ($studentEnrollment->validate() && $studentEnrollment->save()) 
                        CVarDumper::dump('Aluno matriculado com sucesso.', 10, true);
                    else 
                        CVarDumper::dump($studentEnrollment->getErrors(), 10, true);
                }  */
            }      
        } catch (Exception $e) {
            CVarDumper::dump($e->getMessage(), 10, true);
        }
    }

    function findStudentIdentificationByGovId($studentGovId)
    {
        return StudentIdentification::model()->find('gov_id = :govId', [':govId' => $studentGovId])->id;
    }

    function findStudentIdentificationByName($studentName)
    {
        return StudentIdentification::model()->find('name = :name', [':name' => $studentName])->id;
    }

    function searchStudentEnrollmentInDb($schoolInepFk, $studentGovId, $classroomGovId)
    {
        return StudentEnrollment::model()->find(
            'school_inep_id_fk = :school_inep_id_fk AND student_fk = :student_fk AND classroom_fk = :classroom_fk',
            [
                ':school_inep_id_fk' => $schoolInepFk,
                ':student_fk' => StudentIdentification::model()->find('inep_id = :inep_id', [':inep_id' => $studentGovId])->id,
                ':classroom_fk' => $classroomGovId
            ]
        );
    }

    function createNewStudent($inNumRA, $inDigitoRA = null,  $inSiglaUFRA)
    {
        return new InAluno($inNumRA, $inDigitoRA, $inSiglaUFRA);
    }

    function getFichaAluno(InAluno $inAluno) 
    {       
        $registerStudent = new GetExibirFichaAlunoFromSEDUseCase();
        $registerStudent->exec($inAluno);
    }

    function registrarLog($mensagem, $caminhoArquivoLog = 'C:\br.tag\app\modules\sedsp\controllers\meu_log.txt') {
        $dataHora = date('Y-m-d H:i:s');
        $mensagemFormatada = "[$dataHora] $mensagem" . PHP_EOL;
    
        // Abre o arquivo em modo de escrita no final
        $arquivo = fopen($caminhoArquivoLog, 'a');
    
        if ($arquivo) {
            // Escreve a mensagem no arquivo
            fwrite($arquivo, $mensagemFormatada);
    
            // Fecha o arquivo
            fclose($arquivo);
        } else {
            // Lidar com erro ao abrir o arquivo
            error_log("Erro ao abrir o arquivo de log: $caminhoArquivoLog", 0);
        }
    }
}