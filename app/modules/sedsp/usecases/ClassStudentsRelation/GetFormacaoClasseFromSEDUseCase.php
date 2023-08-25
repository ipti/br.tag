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
        $formacaoClasseDataSource = new ClassStudentsRelationSEDDataSource();
        $response = $formacaoClasseDataSource->getClassroom($inNumClasse);

        try {
            $mapper = (object) ClassroomMapper::parseToTAGFormacaoClasse($response);
            $classroom = $mapper->Classroom;
            
            foreach ($mapper->Students as $student) {    
                //Verifica se está cadastrado em student_identification
                $inAluno = new InAluno($student->gov_id, null, $student->uf);
                $registerStudent = new GetExibirFichaAlunoFromSEDUseCase();
                $registerStudent->exec($inAluno);

                $studentEnrollmentModel =  StudentEnrollment::model()->find(
                    'school_inep_id_fk = :school_inep_id_fk AND student_fk = :student_fk AND classroom_fk = :classroom_fk',
                    [
                        ':school_inep_id_fk' => $classroom->school_inep_fk,
                        ':student_fk' => StudentIdentification::model()->find('inep_id = :inep_id', [':inep_id' => $student->inep_id])->id,
                        ':classroom_fk' => $classroom->inep_id
                    ]
                );

                //Verifica se está cadastrado em student_enrollment
                if($studentEnrollmentModel === null){
                    $studentEnrollment = new StudentEnrollment();
                    $studentEnrollment->school_inep_id_fk = $classroom->school_inep_fk;
                    $studentEnrollment->student_inep_id = $student->inep_id;

                    $id = StudentIdentification::model()->find('inep_id = :inep_id', [':inep_id' => $student->inep_id])->id;
                    if($id !== null)
                        $studentEnrollment->student_fk = $id;
                    else
                        $studentEnrollment->student_fk = StudentIdentification::model()->find('name = :name', [':name' => $student->name])->id;
                    
                    $studentEnrollment->classroom_fk = Classroom::model()->find('inep_id = :inep_id', [':inep_id' => $classroom->inep_id])->id ;
                    if ($studentEnrollment->validate() && $studentEnrollment->save()) 
                        CVarDumper::dump('Aluno matriculado com sucesso.', 10, true);
                    else 
                        CVarDumper::dump($studentEnrollment->getErrors(), 10, true);
                } 
            }      
        } catch (Exception $e) {
            CVarDumper::dump($e->getMessage(), 10, true);
        }
    }
}