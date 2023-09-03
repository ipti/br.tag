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
    ){
        $this->classStudentsRelationSEDDataSource = isset($classStudentsRelationSEDDataSource) ? $classStudentsRelationSEDDataSource: new ClassStudentsRelationSEDDataSource();
        $this->getExibirFichaAlunoFromSEDUseCase = isset($getExibirFichaAlunoFromSEDUseCase) ? $getExibirFichaAlunoFromSEDUseCase : new GetExibirFichaAlunoFromSEDUseCase();
    }

    /**
     * Summary of exec
     * @param InFormacaoClasse $inNumClasse
     * @return bool
     */
    public function exec(InFormacaoClasse $inNumClasse)
    {
        try {
            $response = $this->classStudentsRelationSEDDataSource->getClassroom($inNumClasse);
            $mapper = (object) ClassroomMapper::parseToTAGFormacaoClasse($response);
            $students = $mapper->Students;
            $status = false;
            foreach ($students as $student) { 
                $inAluno = new InAluno($student->gov_id, null, $student->uf); 
                $student = $this->getExibirFichaAlunoFromSEDUseCase->exec($inAluno);

                $modelEnrollment = new StudentEnrollment;
                $modelEnrollment->school_inep_id_fk = $student->school_inep_id_fk;
                $modelEnrollment->student_fk = $student->id;
                $modelEnrollment->
                $modelEnrollment->create_date = date('Y-m-d');
                $modelEnrollment->daily_order = $modelEnrollment->getDailyOrder();
                $saved = false;
                if ($modelEnrollment->validate()) {
                    $saved = $modelEnrollment->save();
                }

            }  

            return $status;
        } catch (Exception $e) {            
            CVarDumper::dump($e->getMessage(), 10, true);
            exit(1);
        }
    }

    /**
     * Summary of findStudentIdentificationByGovId
     * @param mixed $studentGovId
     * @return mixed
     */
    public function findStudentIdentificationByGovId($studentGovId)
    {
        return StudentIdentification::model()->find('gov_id = :govId', [':govId' => $studentGovId])->id;
    }

    /**
     * Summary of findStudentIdentificationByName
     * @param mixed $studentName
     * @return mixed
     */
    public  function findStudentIdentificationByName($studentName)
    {
        return StudentIdentification::model()->find('name = :name', [':name' => $studentName])->id;
    }

    /**
     * Summary of searchStudentEnrollmentInDb
     * @param mixed $schoolInepFk
     * @param mixed $studentGovId
     * @param mixed $classroomGovId
     * @return mixed
     */
    public function searchStudentEnrollmentInDb($schoolInepFk, $studentGovId, $classroomGovId)
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

    /**
     * Summary of createNewStudent
     * @param mixed $inNumRA
     * @param mixed $inDigitoRA
     * @param mixed $inSiglaUFRA
     * @return InAluno
     */
    public   function createNewStudent($inNumRA, $inDigitoRA = null,  $inSiglaUFRA)
    {
        return new InAluno($inNumRA, $inDigitoRA, $inSiglaUFRA);
    }

    /**
     * Summary of getFichaAluno
     * @param InAluno $inAluno
     * @return bool
     */
    public  function getFichaAluno(InAluno $inAluno) 
    {       
        return $this->getExibirFichaAlunoFromSEDUseCase->exec($inAluno);
    }

    /**
     * Summary of registrarLog
     * @param mixed $mensagem
     * @param mixed $caminhoArquivoLog
     * @return void
     */
    public  function registrarLog($mensagem, $caminhoArquivoLog = 'C:\br.tag\app\modules\sedsp\controllers\meu_log.txt') {
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