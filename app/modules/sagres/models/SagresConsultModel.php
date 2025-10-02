<?php

namespace SagresEdu;

use Datetime;
use ErrorException;
use Exception;
use fileManager;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\SerializerBuilder;
use PDOException;
use Symfony\Component\Validator\Validation;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\BaseTypesHandler;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\XmlSchemaDateHandler;
use TagUtils;
use ValidationSagresModel;
use Classroom;
use PeriodOptions;
use Yii;
use ZipArchive;

define('TURMA_STRONG', '<strong>TURMA<strong>');
define('SERIE_STRONG', '<strong>SÉRIE<strong>');
define('STUDENT_STRONG', '<strong>ESTUDANTE<strong>');
define('DATA_MATRICULA_INV', 'Data da matrícula no formato inválido: ');
define('DATE_FORMAT', 'd/m/Y');
//Variavéis de inconsistência
define('INCONSISTENCY_BIRTH_AFTER_LIMIT', 'A data de nascimento não pode ser posterior a 30 de agosto de 2024');
define('INCONSISTENCY_BIRTH_BEFORE_LIMIT', 'A data de nascimento não pode ser inferior a 01 de janeiro de 1930');
define('INCONSISTENCY_STUDENT_NAME_TOO_SHORT', 'Nome do estudante com menos de 5 caracteres');
define('INCONSISTENCY_ACTION_STUDENT_NAME_TOO_SHORT', 'Adicione um nome para o estudante com pelo menos 5 caracteres');
define('INCONSISTENCY_PCD_CODE_INVALID', 'Código pcd é inválido');
define('INCONSISTENCY_ACTION_PCD_CODE_INVALID', 'Adicione um valor válido para o pcd');
define('INCONSISTENCY_BIRTH_DATE_INVALID', 'Data de nascimento inválida');
define('INCONSISTENCY_ACTION_BIRTH_DATE_INVALID', 'Altere para uma data válida');
define('INCONSISTENCY_ACTION_BIRTH_DATE_INVALID_FORMAT', 'Altere o formato de data para dd/mm/aaaa');
define('INCONSISTENCY_SEX_INVALID', 'Sexo não é válido');
define('INCONSISTENCY_ACTION_SEX_INVALID', 'Adicione um sexo válido para o estudante');
define('INCONSISTENCY_STUDENT_NOT_FOUND_FOR_CLASS_REGISTRATION', 'Estudante não existe para a matrícula da turma: ');
define('INCONSISTENCY_ACTION_STUDENT_NOT_FOUND_FOR_CLASS_REGISTRATION', 'Adicione um estudante à turma da escola');
define('INCONSISTENCY_NO_REGISTRATION_FOR_CLASS', 'Não há matrícula para a turma');
define('INCONSISTENCY_ACTION_NO_REGISTRATION_FOR_CLASS', 'Adicione uma matrícula para a turma');
define('INCONSISTENCY_INVALID_ABSENCE_VALUE', 'O valor para o número de faltas é inválido');
define('INCONSISTENCY_ACTION_INVALID_ABSENCE_VALUE', 'Coloque um valor válido para o número de faltas');

define('INCONSISTENCY_INVALID_APPROVED_STATUS_VALUE', 'Valor inválido para o status aprovado');
define('INCONSISTENCY_ACTION_INVALID_APPROVED_STATUS_VALUE', 'Adicione um valor válido para o campo aprovado do aluno');

/**
 * Summary of SagresConsultModel
 */
class SagresConsultModel
{
    public function cleanInconsistences()
    {
        $connection = Yii::app()->db;
        //$transaction = $connection->beginTransaction();

        try {
            $deleteQuery = 'DELETE FROM inconsistency_sagres';
            $connection->createCommand($deleteQuery)->execute();

            $resetQuery = 'ALTER TABLE inconsistency_sagres AUTO_INCREMENT = 1';
            $connection->createCommand($resetQuery)->execute();

            //    $transaction->commit();
        } catch (Exception $e) {
            //    $transaction->rollback();
            throw $e;
        }
    }

    public function getSagresEdu($referenceYear, $month, $finalClass, $noMovement, $withoutCpf): EducacaoTType
    {
        $education = new EducacaoTType();
        $managementUnitId = $this->getManagementId();

        if ($noMovement) {
            $education->setPrestacaoContas($this->getManagementUnit($managementUnitId, $referenceYear, $month));
            return $education;
        }

        try {
            $education
                ->setPrestacaoContas($this->getManagementUnit($managementUnitId, $referenceYear, $month))
                ->setEscola($this->getSchools($referenceYear, $month, $finalClass, $withoutCpf))
                ->setProfissional($this->getProfessionals($referenceYear, $month));

            $this->enrolledSimultaneouslyInRegularClasses($referenceYear);
            //$this->getStudentAEE($referenceYear);
        } catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }

        return $education;
    }

    public function getManagementUnit($managementUnitId, $referenceYear, $month): CabecalhoTType
    {
        $finalDay = (int) date('t', strtotime("$referenceYear-$month-01"));
        $month = (int) $month;
        try {
            $query = 'SELECT
                        pa.id AS managementUnitId,
                        pa.cod_unidade_gestora AS managementUnitCode,
                        pa.name_unidade_gestora AS managementUnitName,
                        pa.cpf_responsavel AS responsibleCpf,
                        pa.cpf_gestor AS managerCpf
                    FROM
                        provision_accounts pa
                    WHERE
                        pa.id = :managementUnitId';

            $managementUnit = Yii::app()->db->createCommand($query)
                ->bindValue(':managementUnitId', $managementUnitId)
                ->queryRow();

            $headerType = new CabecalhoTType();
            $finalDay = $this->ajustarUltimoDiaUtil($referenceYear, $month, $finalDay);
            $headerType
                ->setCodigoUnidGestora($managementUnit['managementUnitCode'])
                ->setNomeUnidGestora($managementUnit['managementUnitName'])
                ->setCpfResponsavel(str_replace(['.', '-'], '', $managementUnit['responsibleCpf']))
                ->setCpfGestor(str_replace(['.', '-'], '', $managementUnit['managerCpf']))
                ->setAnoReferencia((int) $referenceYear)
                ->setMesReferencia($month)
                ->setVersaoXml(1)
                ->setDiaInicPresContas((int) 01)
                ->setDiaFinaPresContas($finalDay);

            if (empty($managementUnit['managementUnitCode'])) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'UNIDADE GESTORA: ' . $managementUnit['managementUnitName'];
                $inconsistencyModel->school = 'Unidade Gestora';
                $inconsistencyModel->description = 'Código da Unidade Gestora não informado';
                $inconsistencyModel->action = 'Por favor, informe o código de identificação da Unidade Gestora';
                $inconsistencyModel->identifier = '0';
                $inconsistencyModel->insert();
            }

            if (empty($managementUnit['managementUnitName'])) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'UNIDADE GESTORA';
                $inconsistencyModel->school = 'Unidade Gestora';
                $inconsistencyModel->description = 'Nome da Unidade Gestora não informado';
                $inconsistencyModel->action = 'Por favor, informe um nome para a Unidade Gestora';
                $inconsistencyModel->identifier = '0';
                $inconsistencyModel->insert();
            }

            if (empty($managementUnit['responsibleCpf'])) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'UNIDADE GESTORA: ' . $managementUnit['managementUnitName'];
                $inconsistencyModel->school = 'Unidade Gestora';
                $inconsistencyModel->description = 'CPF do responsável não informado';
                $inconsistencyModel->action = 'Por favor, informe um CPF válido para o responsável';
                $inconsistencyModel->identifier = '0';
                $inconsistencyModel->insert();
            }

            if (empty($managementUnit['managerCpf'])) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'UNIDADE GESTORA: ' . $managementUnit['managementUnitName'];
                $inconsistencyModel->school = 'Unidade Gestora';
                $inconsistencyModel->description = 'CPF do gestor não informado';
                $inconsistencyModel->action = 'Por favor, informe um CPF válido para o gestor';
                $inconsistencyModel->identifier = '0';
                $inconsistencyModel->insert();
            }

            return $headerType;
        } catch (Exception $e) {
            throw new Exception('Ocorreu um erro ao buscar a unidade gestora');
        }
    }

    private function ajustarUltimoDiaUtil($referenceYear, $month, $finalDay)
    {
        $url = 'https://brasilapi.com.br/api/feriados/v1/' . $referenceYear;
        $responseFeriados = file_get_contents($url);

        if ($responseFeriados !== false) {
            $datas = json_decode($responseFeriados, true);
            if ($datas !== null) {
                foreach ($datas as $data) {
                    $mes = (int) substr($data['date'], 5, 2);
                    if ($mes < $month) {
                        continue;
                    }
                    if ($mes > $month) {
                        break;
                    }

                    $day = (int) substr($data['date'], -2);
                    if ($day === $finalDay) {
                        $finalDay -= 1;
                    }
                }
            }
        }
        return $finalDay;
    }

    /**
     * Summary of getManagementId
     * @throws Exception
     * @return int|null
     */
    public function getManagementId()
    {
        $query = 'SELECT id, cod_unidade_gestora FROM provision_accounts';

        try {
            $managementUnitCode = Yii::app()->db->createCommand($query)->queryRow();
        } catch (PDOException $e) {
            throw new Exception('Erro ao buscar o código da unidade gestora: ' . $e->getMessage());
        }

        if (!$managementUnitCode || $managementUnitCode['id'] === null) {
            return null;
        }

        return (int) $managementUnitCode['id'];
    }

    /**
     * Summary of EscolaTType
     * @return EscolaTType[]
     */
    public function getSchools($referenceYear, $month, $finalClass, $withoutCpf)
    {
        $schoolList = [];

        $query = 'SELECT inep_id, name FROM school_identification where situation = 1'; // 1: Ecolas Ativas
        $schools = Yii::app()->db->createCommand($query)->queryAll();

        foreach ($schools as $school) {
            $schoolType = new EscolaTType();

            $turmas = $this->getClasses($school['inep_id'], $referenceYear, $month, $finalClass, $withoutCpf);

            $schoolType
                ->setIdEscola($school['inep_id'])
                ->setTurma($turmas)
                ->setDiretor($this->getDirectorSchool($school['inep_id']))
                ->setCardapio($this->getMenuList($school['inep_id'], $referenceYear, $month));

            $schoolList[] = $schoolType;

            $this->getSchoolsValidation($schoolType->getDiretor(), $school);
        }

        return $schoolList;
    }

    private function getSchoolsValidation($diretor, $school)
    {
        $strMaxLength = 100;
        $inconsistencies = [];

        if ($diretor->getNrAto() == null) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = 'DIRETOR';
            $inconsistencyModel->school = $school['name'];
            $inconsistencyModel->description = 'Número do ato de nomeação não pode ser vazio';
            $inconsistencyModel->action = 'Informar um número do ato de nomeação para o diretor';
            $inconsistencyModel->identifier = '4';
            $inconsistencyModel->idSchool = $school['inep_id'];
            $inconsistencyModel->insert();
        }

        if (strlen($diretor->getNrAto()) > $strMaxLength) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = 'DIRETOR';
            $inconsistencyModel->school = $school['name'];
            $inconsistencyModel->description = 'Número do ato de nomeação com mais de 100 caracteres';
            $inconsistencyModel->action = 'Informar um número do ato de nomeação com até 100 caracteres';
            $inconsistencyModel->identifier = '4';
            $inconsistencyModel->idSchool = $school['inep_id'];
            $inconsistencyModel->insert();
        }

        if ($diretor->getCpfDiretor() === null || !preg_match('/^[0-9]{11}$/', $diretor->getCpfDiretor())) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = 'DIRETOR';
            $inconsistencyModel->school = $school['name'];
            $inconsistencyModel->description = 'CPF não cadastrado ou CPF no formato inválido para o diretor';
            $inconsistencyModel->action = 'Informar um CPF válido para o diretor';
            $inconsistencyModel->identifier = '4';
            $inconsistencyModel->idSchool = $school['inep_id'];
            $inconsistencyModel->insert();
        }

        if (!$this->validaCPF($diretor->getCpfDiretor())) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = 'DIRETOR';
            $inconsistencyModel->school = $school['name'];
            $inconsistencyModel->description = 'CPF do diretor inválido';
            $inconsistencyModel->action = 'Informar um CPF válido para o diretor';
            $inconsistencyModel->identifier = '4';
            $inconsistencyModel->idSchool = $school['inep_id'];
            $inconsistencyModel->insert();
        }

        if (is_null($inconsistencies)) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = 'DIRETOR';
            $inconsistencyModel->school = $school['name'];
            $inconsistencyModel->description = 'Não existe diretor cadastrado para a escola';
            $inconsistencyModel->action = 'Adicione um diretor para a escola';
            $inconsistencyModel->identifier = '4';
            $inconsistencyModel->idSchool = $school['inep_id'];
            $inconsistencyModel->insert();
        }
    }

    private function enrolledSimultaneouslyInRegularClasses(int $year)
    {
        $query = 'SELECT DISTINCT student_fk
                    FROM student_enrollment se
                    JOIN classroom c ON c.id = se.classroom_fk
                    WHERE c.school_year  = :year AND (se.status = 1 or se.status is null) AND student_fk IN (
                        SELECT se.student_fk
                        FROM student_enrollment se
                        JOIN classroom c ON c.id = se.classroom_fk
                        WHERE c.school_year = :year AND (se.status = 1 or se.status is null)
                        GROUP BY student_fk
                        HAVING COUNT(*) > 1
                    );';

        $command = Yii::app()->db->createCommand($query);
        $command->bindValue(':year', $year);
        $students = $command->queryAll();

        $processedStudents = [];

        foreach ($students as $student) {
            $infoStudent = $this->getStudentInfo($student['student_fk']);
            $count = $this->getCountOfClassrooms($student, $infoStudent, $year);
            $this->checkStudentEnrollment($student['student_fk'], $year, $infoStudent);

            if (!in_array($student['student_fk'], $processedStudents)) {
                $this->createInconsistencyModel($student, $infoStudent, $count);
                $processedStudents[] = $student['student_fk'];
            }
        }
    }

    private function getCountOfClassrooms($student, $infoStudent, $year)
    {
        $query = 'SELECT complementary_activity, aee, school_inep_id_fk, c.name
                  FROM student_enrollment se
                  JOIN classroom c ON se.classroom_fk = c.id
                  WHERE se.student_fk = :student_fk and (se.status = 1 or se.status is null) and c.school_year = :year and modality = 1;';

        $command = Yii::app()->db->createCommand($query);
        $command->bindValue(':student_fk', $student['student_fk']);
        $command->bindValue(':year', $year);
        $result = $command->queryAll();

        $count = count($result);

        $classNames = [];
        $schoolInepIds = [];

        foreach ($result as $row) {
            $classNames[] = $row['name'];
            $schoolInepIds[] = $row['school_inep_id_fk'];
        }

        /*
        if (count(array_unique($schoolInepIds)) > 1) {
            $this->duplicatedSchool($student, $infoStudent);
        }
        */

        if ($count > 2) {
            $classNamesString = implode(', ', $classNames);
            return [
                'count' => $count,
                'classNames' => $classNamesString
            ];
        } elseif ($count == 2) {
            $allComplementaryZero = true;
            foreach ($result as $row) {
                if ($row['complementary_activity'] != '0' || $row['aee'] != '0') {
                    $allComplementaryZero = false;
                    break;
                }
            }

            if ($allComplementaryZero) {
                $classNamesString = implode(', ', $classNames);
                return [
                    'count' => 3,
                    'classNames' => $classNamesString
                ];
            }
        }

        return [
            'count' => $count,
            'classNames' => implode(', ', $classNames)
        ];
    }

    private function checkStudentEnrollment($studentfk, $year, $infoStudent)
    {
        $acceptedStatus = $this->getAcceptedEnrollmentStatus();
        $strAcceptedStatus = implode(',', $acceptedStatus);
        // Query to get the modalities
        $sql = "SELECT c.modality, c.complementary_activity, se.classroom_fk, se.school_inep_id_fk
        FROM student_enrollment se
        JOIN classroom c ON se.classroom_fk = c.id
        WHERE se.student_fk = :student_fk
        AND (se.status in ($strAcceptedStatus) or se.status is null)
        AND c.school_year = :year";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':student_fk', $studentfk);
        $command->bindParam(':year', $year);
        $results = $command->queryAll();

        // Check if there are exactly 2 records
        if (count($results) === 2) {
            $complem = array_column($results, 'complementary_activity');
            $complem = array_map('intval', $complem);

            $modalities = array_column($results, 'modality');
            $modalities = array_map('intval', $modalities);

            if ($complem[0] === 1) {
                $modalities[0] = 6;
            } elseif ($complem[1] === 1) {
                $modalities[1] = 6;
            }

            // Check if the combination of modalities is one of the specified combinations
            if (
                ($modalities[0] === 1 && $modalities[1] === 2) ||
                ($modalities[0] === 2 && $modalities[1] === 1) ||
                ($modalities[0] === 1 && $modalities[1] === 3) ||
                ($modalities[0] === 3 && $modalities[1] === 1) ||
                ($modalities[0] === 3 && $modalities[1] === 3)
            ) {
                $studentData = $this->getStudentDataById($studentfk);

                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = '<strong>ALUNO</strong>';
                $inconsistencyModel->school = $studentData['name'];
                $inconsistencyModel->identifier = '9';
                $inconsistencyModel->idStudent = $studentfk;
                $inconsistencyModel->idClass = $infoStudent['classroom_fk'];
                $inconsistencyModel->idSchool = $infoStudent['school_inep_id_fk'];
                $inconsistencyModel->description = 'CPF <strong>' . $studentData['cpf'] . '</strong> do aluno <strong>' . $infoStudent['name'] . '</strong> duplicado';
                $inconsistencyModel->action = 'Remova a matrícula do aluno de uma das turmas';
                $inconsistencyModel->save();
            } else {
                $schoolInepId = array_column($results, 'school_inep_id_fk');
                $schoolInepId = array_map('intval', $schoolInepId);

                if (
                    (
                        $schoolInepId[0] !== $schoolInepId[1]
                    ) &&
                    (
                        ($modalities[0] === 1 && $modalities[1] === 1) ||
                        ($modalities[0] === 3 && $modalities[1] === 2) ||
                        ($modalities[0] === 2 && $modalities[1] === 3)
                    )
                ) {
                    $studentData = $this->getStudentDataById($studentfk);

                    $inconsistencyModel = new ValidationSagresModel();
                    $inconsistencyModel->enrollment = '<strong>ALUNO</strong>';
                    $inconsistencyModel->school = $studentData['name'];
                    $inconsistencyModel->identifier = '9';
                    $inconsistencyModel->idStudent = $studentfk;
                    $inconsistencyModel->idClass = $infoStudent['classroom_fk'];
                    $inconsistencyModel->idSchool = $infoStudent['school_inep_id_fk'];
                    $inconsistencyModel->description = 'CPF <strong>' . $studentData['cpf'] . '</strong> do aluno <strong>' . $infoStudent['name'] . '</strong> duplicado';
                    $inconsistencyModel->action = 'Remova a matrícula do aluno de uma das turmas';
                    $inconsistencyModel->save();
                }
            }
        } elseif (count($results) > 2) {
            $modalityCount = 0;
            foreach ($results as $infoStudent) {
                if ($infoStudent['modality'] == 1) {
                    $modalityCount++;
                }
            }

            $studentData = $this->getStudentDataById($studentfk);

            if ($modalityCount > 1) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = '<strong>ALUNO</strong>';
                $inconsistencyModel->school = $studentData['name'];
                $inconsistencyModel->identifier = '9';
                $inconsistencyModel->idStudent = $studentfk;
                $inconsistencyModel->idClass = $infoStudent['classroom_fk'];
                $inconsistencyModel->idSchool = $infoStudent['school_inep_id_fk'];
                $inconsistencyModel->description = 'CPF <strong>' . $studentData['cpf'] . '</strong> do aluno <strong>' . $infoStudent['name'] . '</strong> duplicado';
                $inconsistencyModel->action = 'Remova a matrícula do aluno de uma das turmas';
                $inconsistencyModel->save();
            }
        }
    }

    private function getStudentDataById($id)
    {
        $sql = 'SELECT sdaa.cpf, si.name
                FROM student_documents_and_address sdaa
                JOIN school_identification si ON si.inep_id = sdaa.school_inep_id_fk
                WHERE sdaa.id = :id';

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':id', $id);
        $result = $command->queryRow();

        return $result;
    }

    private function getStudentInfo($studentfk)
    {
        $sql = 'SELECT si.name, sdaa.cpf FROM student_identification si
                JOIN student_documents_and_address sdaa ON sdaa.id = si.id
                WHERE si.id = :id';

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':id', $studentfk);
        return $command->queryRow();
    }

    private function getSchoolName($inepId)
    {
        $sql = 'SELECT si.name FROM school_identification si WHERE si.inep_id = :inepId';

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':inepId', $inepId);
        return $command->queryScalar();
    }

    private function checkAge($age, $educationLevel, $arrayStudentInfo)
    {
        // [2,3,7] -> Ensino fundamental
        // 4 -> Ensino Médio
        if (in_array($educationLevel, [2, 3, 7]) && $age < 15) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = '<strong>ALUNO</strong>';
            $inconsistencyModel->school = $this->getSchoolName($arrayStudentInfo['schoolInepIdFk']);
            $inconsistencyModel->identifier = '9';
            $inconsistencyModel->idStudent = $arrayStudentInfo['studentFk'];
            $inconsistencyModel->idClass = $arrayStudentInfo['classroomFk'];
            $inconsistencyModel->idSchool = $arrayStudentInfo['schoolInepIdFk'];
            $inconsistencyModel->description = 'O aluno não tem a idade mínima de 15 anos para o Ensino Fundamental.';
            $inconsistencyModel->action = 'O aluno deve der a idade compatível com a turma';
            $inconsistencyModel->save();
        } elseif ($educationLevel === 4 && $age < 18) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = '<strong>ALUNO</strong>';
            $inconsistencyModel->school = $this->getSchoolName($arrayStudentInfo['schoolInepIdFk']);
            $inconsistencyModel->identifier = '9';
            $inconsistencyModel->idStudent = $arrayStudentInfo['studentFk'];
            $inconsistencyModel->idClass = $arrayStudentInfo['classroomFk'];
            $inconsistencyModel->idSchool = $arrayStudentInfo['schoolInepIdFk'];
            $inconsistencyModel->description = 'O aluno não tem a idade mínima de 18 anos para o Ensino Médio';
            $inconsistencyModel->action = 'O aluno deve der a idade compatível com a turma';
            $inconsistencyModel->save();
        }
    }

    public function createInconsistencyModel($student, $infoStudent, $count)
    {
        if ($count['count'] >= 3) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = '<strong>MATRÍCULA</strong>';
            $inconsistencyModel->school = $this->getSchoolName($student['school_inep_id_fk']);
            $inconsistencyModel->description = 'Estudante <strong>' . $infoStudent['name'] . '</strong> com CPF <strong>' . $infoStudent['cpf'] . '</strong> está matriculado em mais de uma turma regular';
            $inconsistencyModel->action = 'Um aluno não deve estar matriculado simultaneamente em mais de uma turma regular';
            $inconsistencyModel->identifier = '9';
            $inconsistencyModel->idStudent = $student['student_fk'];
            $inconsistencyModel->idClass = $student['classroom_fk'];
            $inconsistencyModel->idSchool = $student['school_inep_id_fk'];
            $inconsistencyModel->save();
        }
    }

    public function getInconsistenciesCount()
    {
        $authAssignment = \AuthAssignment::model()->find(
            [
                'condition' => 'userid = :userid',
                'params' => [':userid' => Yii::app()->user->loginInfos->id]
            ]
        )->itemname;

        if ($authAssignment === 'manager') {
            $idSchool = Yii::app()->user->school;
            $query = "SELECT count(*) FROM inconsistency_sagres is2 WHERE is2.idSchool = $idSchool";
        } else {
            $query = 'SELECT count(*) FROM inconsistency_sagres';
        }

        return Yii::app()->db->createCommand($query)->queryScalar();
    }

    public function getNameSchool($idSchool)
    {
        $query = 'SELECT name FROM school_identification where inep_id = :idSchool';

        return Yii::app()->db->createCommand($query)->bindValue(':idSchool', $idSchool)->queryScalar();
    }

    /**
     * Summary of TurmaTType
     * @return TurmaTType[]
     */
    public function getClasses($inepId, $referenceYear, $month, $finalClass, $withoutCpf)
    {
        $classList = [];
        $schoolName = $this->getSchoolName($inepId);
        $turmas = $this->getTurmasInClasses($inepId, $referenceYear);

        if (empty($turmas)) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = '<strong>ESCOLA<strong>';
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = 'Não há turmas para a escola: ' . $schoolName;
            $inconsistencyModel->action = 'Adicione turmas para a escola';
            $inconsistencyModel->identifier = '4';
            $inconsistencyModel->idSchool = $inepId;
            $inconsistencyModel->insert();
        }

        foreach ($turmas as $turma) {
            if ($turma['ignore_on_sagres'] == 1) {
                continue;
            }

            $classType = new TurmaTType();
            $classId = $turma['classroomId'];

            if (\TagUtils::isStageEJA($turma['stage']) && $turma['period'] == 0) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = TURMA_STRONG;

                $inconsistencyModel->school = $schoolName;
                $inconsistencyModel->description = 'A turma <strong>' . $classType->getDescricao() . '</strong> é do tipo EJA, mas o perído está selecionado como anual.';
                $inconsistencyModel->action = 'Altere o periodo para 1º ou 2º semestre: ' . $classType->getDescricao();
                $inconsistencyModel->identifier = '10';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->idSchool = $inepId;
                $inconsistencyModel->insert();
            }

            $serie = $this->getSeries2025($classId, $inepId, $referenceYear, $month, $finalClass, $withoutCpf);

            $multiserie = $this->isMulti($classId);

            $classType
                ->setPeriodo($turma['period']) //0 - Anual
                ->setDescricao($turma['classroomName'])
                ->setTurno($this->convertTurn($turma['classroomTurn']))
                ->setSerie($serie)
                ->setHorario($this->getSchedules($classId, $month, $inepId))
                ->setFinalTurma(filter_var($finalClass, FILTER_VALIDATE_BOOLEAN))
                ->setMultiseriada($multiserie);

            $temHorario = $classType->getHorario() !== null;
            $seriePreenchida = !empty($serie);
            $temMatricula = $this->hasAtLeastOneRegistration($seriePreenchida, $serie);

            if ($temHorario && $seriePreenchida && $temMatricula) {
                $classList[] = $classType;
            }

            $count = (int) \StudentEnrollment::model()->count([
                'condition' => 'classroom_fk = :classroomId',
                'params' => [':classroomId' => $classId],
            ]);

            $this->getClassesValidation($count, $schoolName, $classType, $classId, $inepId);
        }

        return $classList;
    }

    private function hasAtLeastOneRegistration($seriePreenchida, $serie)
    {
        if ($seriePreenchida) {
            $matricula = $serie[0]->getMatricula();
            $numMatriculas = count($matricula);
            return $numMatriculas > 0;
        }
        return false;
    }

    private function getTurmasInClasses($inepId, $referenceYear)
    {
        $query = 'SELECT
                    c.initial_hour AS initialHour,
                    c.school_inep_fk AS schoolInepFk,
                    c.id AS classroomId,
                    c.name AS classroomName,
                    c.turn AS classroomTurn,
                    COALESCE(esvm.edcenso_associated_stage_id, c.edcenso_stage_vs_modality_fk) as stage,
                    c.period,
                    c.ignore_on_sagres
                FROM
                    classroom c
                    join edcenso_stage_vs_modality esvm on c.edcenso_stage_vs_modality_fk = esvm.id
                WHERE
                    c.school_inep_fk = :schoolInepFk and c.classroom_status = 1
                    AND c.school_year = :referenceYear';

        $params = [
            ':schoolInepFk' => $inepId,
            ':referenceYear' => $referenceYear
        ];

        $turmas = Yii::app()->db->createCommand($query)
            ->bindValues($params)
            ->queryAll();
        return $turmas;
    }

    private function getClassesValidation($count, $schoolName, $classType, $classId, $inepId)
    {
        $strMaxLength = 50;
        $strlen = 2;

        if ($count === 0) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = TURMA_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = 'Não há matrículas ativas para a turma: <strong>' . $classType->getDescricao() . '</strong>';
            $inconsistencyModel->action = 'Adicione alunos para a turma: ' . $classType->getDescricao();
            $inconsistencyModel->identifier = '10';
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->idSchool = $inepId;
            $inconsistencyModel->insert();
        }

        /*
         *  [0 : Anual], [1 : 1°], [2 : 2º] Semestre
         */
        if (!in_array($classType->getPeriodo(), [0, 1, 2])) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = TURMA_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = 'Valor inválido para o período';
            $inconsistencyModel->action = 'Adicione um valor válido para o período da turma: <strong>' . $classType->getDescricao() . '</strong>';
            $inconsistencyModel->identifier = '10';
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->idSchool = $inepId;
            $inconsistencyModel->insert();
        }

        if (strlen($classType->getDescricao()) <= $strlen && !is_null($classType->getDescricao())) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = TURMA_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = 'Descrição para a turma: <strong>' . $classType->getDescricao() . ' </strong> menor que 3 caracteres';
            $inconsistencyModel->action = 'Adicione uma descrição mais detalhada, contendo mais de 5 caracteres';
            $inconsistencyModel->identifier = '10';
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->idSchool = $inepId;
            $inconsistencyModel->insert();
        }

        if (strlen($classType->getDescricao()) > $strMaxLength) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = TURMA_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = 'Descrição para a turma: <strong>' . $classType->getDescricao() . ' </strong> com mais de 50 caracteres';
            $inconsistencyModel->action = 'Adicione uma descrição menos detalhada, contendo até 50 caracteres';
            $inconsistencyModel->identifier = '10';
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->idSchool = $inepId;
            $inconsistencyModel->insert();
        }
        if ($classType->getTurno() === 0) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = TURMA_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = 'Valor inválido para o turno da turma: <strong>' . $classType->getDescricao() . '<strong>';
            $inconsistencyModel->action = 'Selecione um turno válido para o horário de funcionamento';
            $inconsistencyModel->identifier = '10';
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->idSchool = $inepId;
            $inconsistencyModel->insert();
        }

        if ($classType->getTurno() !== 0 && !in_array($classType->getTurno(), [1, 2, 3, 4])) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = TURMA_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = 'Valor inválido para o turno da turma: <strong>' . $classType->getDescricao() . '</strong>';
            $inconsistencyModel->action = 'Selecione um turno válido para o horário de funcionamento';
            $inconsistencyModel->identifier = '10';
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->idSchool = $inepId;
            $inconsistencyModel->insert();
        }

        if (!is_bool($classType->getFinalTurma())) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = TURMA_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = 'Valor inválido para o final turma';
            $inconsistencyModel->action = 'Selecione um valor válido para o encerramento do período';
            $inconsistencyModel->identifier = '10';
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->idSchool = $inepId;
            $inconsistencyModel->insert();
        }
    }

    /**
     * Summary of SerieTType
     * @return SerieTType[]
     */
    public function getSeries2025($classId, $inepId, $referenceYear, $month, $finalClass, $withoutCpf)
    {
        $seriesList = [];

        $school = (object) \SchoolIdentification::model()->findByAttributes(['inep_id' => $inepId]);

        $classroom = (object) \Classroom::model()->with('edcensoStageVsModalityFk')->findByPk($classId);

        $easId = $classroom->edcensoStageVsModalityFk->edcenso_associated_stage_id;

        $multiStage = TagUtils::isMultiStage($easId);

        $query = $this->getSeriesQuery($multiStage);
        $series = Yii::app()->db->createCommand($query)->bindValue(':id', $classId)->queryAll();

        $seriesList = $this->seriesAssembly($series, $school->name, $classId, $referenceYear, $finalClass, $inepId, $withoutCpf, $multiStage);
        $this->seriesNumberValidation($series, 3, $school->name, $classId);

        return $seriesList;
    }

    private function seriesAssembly($series, $schoolName, $classId, $referenceYear, $finalClass, $inepId, $withoutCpf, $multiStage): array
    {
        $seriesList = [];
        $edcensoCodes = [
            1 => 'INF1',
            2 => 'INF2',
            14 => 'FUN1',
            15 => 'FUN2',
            16 => 'FUN3',
            17 => 'FUN4',
            18 => 'FUN5',
            19 => 'FUN6',
            20 => 'FUN7',
            21 => 'FUN8',
            41 => 'FUN9',
            69 => 'EJA1',
            70 => 'EJA2',
            75 => 'AEE1'
        ]; // Deve ser transformado em um enum

        foreach ($series as $serie) {
            $serie = (object) $serie;
            $serieType = new SerieTType();
            $edcensoCode = $serie->edcensoCode;

            $idSerie = $this->getSerieID($serie, $edcensoCode, $edcensoCodes);

            if ($this->isIssetSerieId($idSerie, $schoolName, $classId, $multiStage)) {
                continue;
            }

            $serieType->setIdSerie($idSerie);

            $this->getSerieValidation($serieType, $schoolName, $classId, $edcensoCode, $edcensoCodes);

            $matriculas = $this->getEnrollments($classId, $referenceYear, $finalClass, $inepId, $withoutCpf);

            if (!isset($matriculas)) {
                continue;
            }

            $matriculas = $this->filterSeries($multiStage, $idSerie, $matriculas, $serie);

            foreach ($matriculas as $matricula) {
                $matricula->setEnrollmentStage(null);
            }

            $serieType->setMatricula($matriculas);

            $seriesList[] = $serieType;
        }
        return $seriesList;
    }

    private function isIssetSerieId($idSerie, $schoolName, $classId, $multiStage)
    {
        if (!isset($idSerie) && $multiStage) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = SERIE_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = 'Há alunos na turma com etapa de ensino não associada a nenhuma etapa válida';
            $inconsistencyModel->action = 'Atualize a etapa de ensino de cada aluno para uma etapa válida.';
            $inconsistencyModel->identifier = '13';
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
            return true;
        }
        return false;
    }

    private function getSeriesQuery($multiStage): string
    {
        if ($multiStage) {
            return 'SELECT
                esvm.edcenso_associated_stage_id as edcensoCode,
                c.edcenso_stage_vs_modality_fk as edcensoCodeOriginal,
                c.complementary_activity as complementaryActivity,
                c.schooling as schooling,
                c.aee as aee
            FROM
                classroom c
            JOIN student_enrollment se on se.classroom_fk = c.id
            JOIN  edcenso_stage_vs_modality esvm on esvm.id = se.edcenso_stage_vs_modality_fk
            WHERE
                c.id = :id
            And esvm.edcenso_associated_stage_id is not NULL
            GROUP by se.edcenso_stage_vs_modality_fk
        ';
        } else {
            return 'SELECT
                esvm.edcenso_associated_stage_id as edcensoCode,
                c.edcenso_stage_vs_modality_fk as edcensoCodeOriginal,
                c.complementary_activity as complementaryActivity,
                c.schooling as schooling,
                c.aee as aee
            FROM
                classroom c
            JOIN edcenso_stage_vs_modality esvm on
                esvm.id = c.edcenso_stage_vs_modality_fk
            WHERE
                c.id = :id; ';
        }
    }

    private function getSerieID($serie, $edcensoCode, $edcensoCodes): string|null
    {
        if ((int) $serie->complementaryActivity === 1 && (int) $serie->schooling === 0) {
            return 'COM1';
        } elseif ((int) $serie->aee === 1 || (int) $edcensoCode == 75) {
            return 'AEE1';
        } else {
            return $edcensoCodes[(int) $edcensoCode];
        }
    }

    private function filterSeries($multiStage, $idSerie, $matriculas, $serie): array
    {
        $response = $matriculas;
        if ($multiStage && $idSerie !== 'COM1' && $idSerie !== 'AEE1') {
            return array_filter(
                $matriculas,
                fn ($e) => $e->getEnrollmentStage() == $serie->edcensoCode
            );
        }
        return $response;
    }

    private function getSerieValidation($serieType, $schoolName, $classId, $edcensoCode, $edcensoCodes): void
    {
        if (empty($serieType)) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = SERIE_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = 'Não há série para a escola: ' . $schoolName;
            $inconsistencyModel->action = 'Adicione uma série para a turma';
            $inconsistencyModel->identifier = '10';
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        } elseif (!isset($edcensoCodes[$edcensoCode])) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = SERIE_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = 'Há alunos na turma com etapa de ensino não associada a nenhuma etapa válida.';
            $inconsistencyModel->action = 'Atualize a etapa de ensino de cada aluno para uma etapa válida.';
            $inconsistencyModel->identifier = '13';
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }
    }

    private function seriesNumberValidation($series, $maxNumber, $schoolName, $idClass): void
    {
        if (count($series) > $maxNumber) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = TURMA_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = 'Turmas multiseriadas apenas aceitam 3 etapas de ensino diferentes ' . $schoolName;
            $inconsistencyModel->action = 'Avalie as etapas das matrículas e deixe apenas 3 etapas diferentes';
            $inconsistencyModel->identifier = '13';

            $inconsistencyModel->idClass = $idClass;
            $inconsistencyModel->insert();
        }
    }

    private function isMulti($classId): bool
    {
        $classroom = (object) \Classroom::model()->with('edcensoStageVsModalityFk')->findByPk($classId);

        $easId = $classroom->edcensoStageVsModalityFk->edcenso_associated_stage_id;
        return \TagUtils::isMultiStage($easId);
    }

    /**
     * Summary of SerieTType
     *
     * @return HorarioTType[]
     *
     */
    public function getSchedules($classId, $month, $inepId)
    {
        $scheduleList = [];
        $strlen = 3;
        $maxLength = 100;

        $school = (object) \SchoolIdentification::model()->findByAttributes(['inep_id' => $inepId]);

        $query = 'SELECT DISTINCT
                    s.schedule AS schedule,
                    s.week_day AS weekDay,
                    ed.name AS disciplineName,
                    c.turn AS turn,
                    idaa.cpf AS cpfInstructor
                FROM instructor_teaching_data itd
                    JOIN teaching_matrixes tm on itd.id = tm.teaching_data_fk
                    JOIN curricular_matrix cm on tm.curricular_matrix_fk = cm.id
                    JOIN schedule s on s.discipline_fk = cm.discipline_fk and s.classroom_fk = itd.classroom_id_fk
                    JOIN instructor_documents_and_address idaa on itd.instructor_fk = idaa.id
                    JOIN edcenso_discipline ed ON ed.id = cm.discipline_fk
                    JOIN classroom c on c.id = itd.classroom_id_fk
                WHERE
                    c.id = :classId and
                    s.month <= :referenceMonth
                ORDER BY
                    c.create_date DESC';

        $params = [
            ':classId' => $classId,
            ':referenceMonth' => $month
        ];

        $schedules = Yii::app()->db->createCommand($query)->bindValues($params)->queryAll();

        if (empty($schedules)) {
            $this->checkScheduleInconsistencies($classId, $month, $school->name, $inepId);
        }

        $class = (object) \Classroom::model()->findByAttributes(['id' => $classId]);

        $timetable = $this->getTimetableByClassroom($classId, $month);
        if (empty($timetable)) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = TURMA_STRONG;
            $inconsistencyModel->school = $school->name;
            $inconsistencyModel->description = 'Quadro de horários está vazio para a turma: <strong>' . $class->name . '<strong>';
            $inconsistencyModel->action = 'Adicione um quadro de horários para a turma';
            $inconsistencyModel->identifier = '10';
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->idSchool = $inepId;
            $inconsistencyModel->insert();
        }

        $getTeachersForClass = $this->getTeachersForClass($classId);
        if (empty($getTeachersForClass)) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = TURMA_STRONG;
            $inconsistencyModel->school = $school->name;
            $inconsistencyModel->description = 'Não há professores registrados para a turma: <strong>' . $class->name . '<strong>';
            $inconsistencyModel->action = 'Adicione os professores juntamente com os seus componentes curriculares';
            $inconsistencyModel->identifier = '10';
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->idSchool = $inepId;
            $inconsistencyModel->insert();
        }

        if (!empty($getTeachersForClass)) {
            foreach ($getTeachersForClass as $teachers) {
                $name = $teachers['name'];
                $idTeacher = $teachers['instructor_fk'];
                $infoTeacher = \InstructorDocumentsAndAddress::model()->findByPk($idTeacher, ['select' => 'id, cpf']);
                $cpfInstructor = $infoTeacher['cpf'];

                if ($cpfInstructor === null) {
                    $inconsistencyModel = new ValidationSagresModel();
                    $inconsistencyModel->enrollment = '<strong>PROFESSOR<strong>';
                    $inconsistencyModel->school = $school->name;
                    $inconsistencyModel->description = 'CPF não foi informado para o professor(a): <strong>' . $name . '</strong>';
                    $inconsistencyModel->action = 'Informar um CPF válido para o professor';
                    $inconsistencyModel->identifier = '3';
                    $inconsistencyModel->idProfessional = $idTeacher;
                    $inconsistencyModel->idClass = $classId;
                    $inconsistencyModel->idSchool = $inepId;
                    $inconsistencyModel->insert();
                } else {
                    if (!$this->validaCPF($cpfInstructor)) {
                        $inconsistencyModel = new ValidationSagresModel();
                        $inconsistencyModel->enrollment = '<strong>PROFESSOR<strong>';
                        $inconsistencyModel->school = $school->name;
                        $inconsistencyModel->description = 'CPF do professor(a) <strong>' . $name . '</strong> é inválido: <strong>' . $cpfInstructor . '</strong>';
                        $inconsistencyModel->action = 'Informar um CPF válido para o professor(a)';
                        $inconsistencyModel->identifier = '3';
                        $inconsistencyModel->idProfessional = $idTeacher;
                        $inconsistencyModel->idClass = $classId;
                        $inconsistencyModel->idSchool = $inepId;
                        $inconsistencyModel->insert();
                    }
                }

                $componentesCurriculares = $this->getComponentesCurriculares($classId, $idTeacher);
                $roleType = (int) $this->getInstructorRole($classId, $idTeacher);

                if (empty($componentesCurriculares) && $roleType === 1) {
                    $inconsistencyModel = new ValidationSagresModel();
                    $inconsistencyModel->enrollment = TURMA_STRONG;
                    $inconsistencyModel->school = $school->name;
                    $inconsistencyModel->description = 'O professor <strong>' . $teachers['name'] . '</strong> está sem seus componentes curriculares para a turma: <strong>' . $class->name . '<strong>';
                    $inconsistencyModel->action = 'Adicione os componentes curriculares para o professor: <strong>' . $teachers['name'] . '</strong>';
                    $inconsistencyModel->identifier = '10';
                    $inconsistencyModel->idClass = $classId;
                    $inconsistencyModel->idSchool = $inepId;
                    $inconsistencyModel->insert();
                }
            }
        }

        foreach ($schedules as $schedule) {
            $scheduleType = new HorarioTType();

            $queryGetDuration = "SELECT
                            ROUND( (t.credits / COUNT(*))) AS duration
                        FROM (
                            SELECT ed.name AS disciplineName, cm.credits AS credits
                                FROM schedule s
                                JOIN edcenso_discipline ed ON ed.id = s.discipline_fk
                                JOIN classroom c ON c.id = s.classroom_fk
                                JOIN curricular_matrix cm ON cm.discipline_fk = ed.id
                            WHERE s.classroom_fk = $classId and s.month <= $month
                            GROUP BY s.week_day
                        ) t
                        WHERE t.disciplineName = '" . $schedule['disciplineName'] . "'";

            $duration = Yii::app()->db->createCommand($queryGetDuration)->queryRow();

            $disciplina = mb_convert_encoding(substr($schedule['disciplineName'], 0, 50), 'UTF-8', 'UTF-8');

            $scheduleType
                ->setDiaSemana(((int) $schedule['weekDay'] === 0 ? 7 : $schedule['weekDay']))
                ->setDuracao(2)
                ->setHoraInicio($this->getStartTime($schedule['schedule'], $this->convertTurn($schedule['turn'])))
                ->setDisciplina($disciplina)
                ->setCpfProfessor([str_replace(['.', '-'], '', $schedule['cpfInstructor'])]);

            if (empty($scheduleType)) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = '<strong>HORÁRIO<strong>';
                $inconsistencyModel->school = $school->name;
                $inconsistencyModel->description = 'Não há um professor, horários ou componentes curriculares para a turma: ';
                $inconsistencyModel->action = 'Adicione um professor ou componentes curriculares à turma';
                $inconsistencyModel->identifier = '10';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->idSchool = $inepId;
                $inconsistencyModel->insert();
            }

            if (!in_array($scheduleType->getDiaSemana(), [1, 2, 3, 4, 5, 6, 7])) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = '<strong>HORÁRIO<strong>';
                $inconsistencyModel->school = $school->name;
                $inconsistencyModel->description = 'Dia da semana inválido: ' . $scheduleType->getDiaSemana();
                $inconsistencyModel->action = 'Adicione um dia da semana válido para a disciplina';
                $inconsistencyModel->identifier = '10';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->idSchool = $inepId;
                $inconsistencyModel->insert();
            }

            if (!is_int($scheduleType->getDuracao())) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = '<strong>HORÁRIO<strong>';
                $inconsistencyModel->school = $school->name;
                $inconsistencyModel->description = 'Duração inválida';
                $inconsistencyModel->action = 'Adicione um dia da semana válido para a disciplina';
                $inconsistencyModel->identifier = '10';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->idSchool = $inepId;
                $inconsistencyModel->insert();
            }

            if (strlen($scheduleType->getDisciplina()) < $strlen) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = '<strong>HORÁRIO<strong>';
                $inconsistencyModel->school = $school->name;
                $inconsistencyModel->description = 'Nome da disciplina muito curta';
                $inconsistencyModel->action = 'Adicione um nome para a disciplina com pelo menos 5 caracteres';
                $inconsistencyModel->identifier = '10';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->idSchool = $inepId;
                $inconsistencyModel->insert();
            }

            if (strlen($scheduleType->getDisciplina()) > $maxLength) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = '<strong>HORÁRIO<strong>';
                $inconsistencyModel->school = $school->name;
                $inconsistencyModel->description = 'Nome da disciplina com mais de 50 caracteres - ' . $scheduleType->getDisciplina();
                $inconsistencyModel->action = 'Adicione um nome para a disciplina com até 50 caracteres';
                $inconsistencyModel->identifier = '10';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->idSchool = $inepId;
                $inconsistencyModel->insert();
            }

            $scheduleList[] = $scheduleType;
        }

        return $scheduleList;
    }

    private function checkScheduleInconsistencies($classId, $referenceMonth, $schoolName, $inepId)
    {
        $results = Yii::app()->db->createCommand('
            SELECT DISTINCT
                s.discipline_fk as schedules, cm.discipline_fk as curricularMatrix, ed.name, c.name as className
            FROM instructor_teaching_data itd
                JOIN teaching_matrixes tm on itd.id = tm.teaching_data_fk
                JOIN curricular_matrix cm on cm.id = tm.curricular_matrix_fk
                JOIN schedule s on s.classroom_fk = itd.classroom_id_fk
                JOIN instructor_documents_and_address idaa on itd.instructor_fk = idaa.id
                JOIN edcenso_discipline ed ON ed.id = cm.discipline_fk
                JOIN classroom c on c.id = itd.classroom_id_fk
            WHERE
                c.id = :classId and
                s.month <= :referenceMonth
            ORDER BY
                c.create_date desc
        ')
            ->bindParam(':classId', $classId)
            ->bindParam(':referenceMonth', $referenceMonth)
            ->queryAll();

        $schedules = [];
        $curricularMatrixChecked = [];

        foreach ($results as $row) {
            $schedules[] = $row['schedules'];
        }

        foreach ($results as $row) {
            $matrixId = $row['curricularMatrix'];
            if (!in_array($matrixId, $schedules) && !in_array($matrixId, $curricularMatrixChecked)) {
                $curricularMatrixChecked[] = $matrixId;

                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = TURMA_STRONG;
                $inconsistencyModel->school = $schoolName;
                $inconsistencyModel->description = 'Componente curricular: <strong>' . $row['name'] . '</strong> não está no quadro de horários.';
                $inconsistencyModel->action = 'Adicione o componente curricular ao quadro de horários para a turma: <strong>' . $row['className'] . '</strong>';
                $inconsistencyModel->identifier = '10';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->idSchool = $inepId;
                $inconsistencyModel->insert();
            }
        }
    }

    private function getInstructorRole($classroomIdFk, $instructorId)
    {
        $sql = 'SELECT itd.role
                FROM instructor_teaching_data itd
                WHERE itd.instructor_fk = :instructorId
                AND classroom_id_fk = :classroomIdFk';

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':instructorId', $instructorId);
        $command->bindParam(':classroomIdFk', $classroomIdFk);

        return $command->queryScalar();
    }

    private function getTimetableByClassroom($classId, $month)
    {
        return \Schedule::model()->findAllByAttributes([
            'classroom_fk' => $classId,
        ], [
            'condition' => 'month <= :month',
            'params' => [':month' => $month],
        ]);
    }

    private function getComponentesCurriculares($classId, $instructorId)
    {
        $query = 'SELECT
                        *
                    FROM instructor_teaching_data itd
                        JOIN teaching_matrixes tm on itd.id = tm.teaching_data_fk
                        JOIN curricular_matrix cm on tm.curricular_matrix_fk = cm.id
                        JOIN classroom c on c.id = itd.classroom_id_fk
                    WHERE
                        c.id = :classId and
                        itd.instructor_fk = :instructorId
                    ORDER BY
                        c.create_date DESC;';
        $params = [
            ':classId' => $classId,
            ':instructorId' => $instructorId
        ];

        return Yii::app()->db->createCommand($query)->bindValues($params)->queryAll();
    }

    private function getTeachersForClass($classId)
    {
        $query = 'SELECT itd.instructor_fk, ii.name
                    FROM instructor_teaching_data itd
                        JOIN instructor_identification ii ON ii.id = itd.instructor_fk
                        JOIN classroom c ON c.id = itd.classroom_id_fk
                    WHERE
                        c.id = :classId
                    ORDER BY
                        c.create_date DESC;';
        $params = [
            ':classId' => $classId
        ];

        return Yii::app()->db->createCommand($query)->bindValues($params)->queryAll();
    }

    /**
     * Calculates the start time for a given schedule and initial hour.
     *
     * @param int $schedule The schedule number (1-10).
     * @param string $turn The turn type: "1: Morning", "2: Afternoon", "3: Night" or "4: FullTime".
     * @return DateTime The start time for the given schedule and initial hour.
     */
    public function getStartTime($schedule, $turn): DateTime
    {
        $startTimes = [
            1 => [
                1 => 7,
                2 => 8,
                3 => 9,
                4 => 10,
                5 => 11
            ],
            2 => [
                1 => 12,
                2 => 13,
                3 => 14,
                4 => 15,
                5 => 16,
                6 => 17,
                7 => 18
            ],
            3 => [
                1 => 18,
                2 => 19,
                3 => 20,
                4 => 21
            ],
            4 => [
                1 => 7,
                2 => 8,
                3 => 9,
                4 => 10,
                5 => 11,
                6 => 12,
                7 => 13,
                8 => 14,
                9 => 15,
                10 => 16
            ]
        ];

        $startTime = $startTimes[$turn][$schedule] ?? null;

        if (isset($startTime)) {
            return $this->getDateTimeFromInitialHour($startTime);
        } else {
            return $this->getDateTimeFromInitialHour('00');
        }
    }

    public function getDateTimeFromInitialHour($initialHour)
    {
        $timeFormatted = date('H:i:s', strtotime($initialHour . ':00:00'));
        return new DateTime($timeFormatted);
    }

    /**
     * Summary of CardapioTType
     * @return CardapioTType[]
     */
    public function getMenuList($schoolId, $year, $month)
    {
        $menuList = [];
        $strlen = 4;
        $maxLen = 1000;

        $isFoodEnabled = (object) \InstanceConfig::model()->findByAttributes(['parameter_key' => 'FEAT_FOOD']);

        if ($isFoodEnabled->value) {
            $query = 'SELECT
	                    fm.start_date as data,
	                    fmmc.description  AS descricaoMerenda,
	                    fm.adjusted AS ajustado,
	                    fmm.turn AS turno,
	                    fmm.food_menuId
                    FROM food_menu fm JOIN food_menu_meal fmm on fmm.food_menuId = fm.id
                    JOIN food_menu_meal_component fmmc on fmmc.food_menu_mealId = fmm.id
                    WHERE
	                    YEAR(fm.start_date) = :year
	                    and month(fm.start_date) <= :month;';
            $params = [
                ':year' => $year,
                ':month' => $month
            ];
        } else {
            $query = 'SELECT
                    lm.date AS data,
                    lm.turn AS turno,
                    lm2.restrictions  AS descricaoMerenda,
                    lm.adjusted AS ajustado,
                    lmm.menu_fk
                FROM lunch_menu lm
                    JOIN lunch_menu_meal lmm ON lm.id = lmm.menu_fk
                    JOIN lunch_meal lm2 on lmm.meal_fk = lm2.id
                WHERE lm.school_fk =  :schoolId AND YEAR(lm.date) = :year AND MONTH(lm.date) <= :month';

            $params = [
                ':schoolId' => $schoolId,
                ':year' => $year,
                ':month' => $month
            ];
        }

        $menus = Yii::app()->db->createCommand($query)->bindValues($params)->queryAll();

        foreach ($menus as $menu) {
            $menuType = new CardapioTType();

            $descMeren = str_replace('ª', '', $menu['descricaoMerenda']);
            $descMeren = preg_replace('/[\x00-\x1F\x7F]/u', '', $descMeren);
            $descMeren = str_replace("\r", '', $descMeren);

            $menuType
                ->setData(new DateTime($menu['data']))
                ->setTurno($this->convertTurn($menu['turno']))
                ->setDescricaoMerenda($descMeren)
                ->setAjustado(isset($menu['ajustado']) ? $menu['ajustado'] : false);

            $menuList[] = $menuType;

            $sql = 'SELECT name FROM school_identification WHERE inep_id = :inepId';
            $params = [':inepId' => $schoolId];
            $schoolRes = Yii::app()->db->createCommand($sql)->bindValues($params)->queryRow();

            if (!in_array($menuType->getTurno(), [1, 2, 3, 4])) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'CARDÁPIO';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'Turno inválido';
                $inconsistencyModel->action = 'Informar um turno válido para o cardápio';
                $inconsistencyModel->identifier = '11';
                $inconsistencyModel->idLunch = $menu['menu_fk'];
                $inconsistencyModel->idSchool = $schoolId;
                $inconsistencyModel->insert();
            }

            if (strlen($menuType->getDescricaoMerenda()) <= $strlen) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'CARDÁPIO';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'Descrição para merenda menor que 5 caracteres';
                $inconsistencyModel->action = 'Informar uma descrição para merenda maior que 4 caracteres';
                $inconsistencyModel->identifier = '11';
                $inconsistencyModel->idLunch = $menu['menu_fk'];
                $inconsistencyModel->idSchool = $schoolId;
                $inconsistencyModel->insert();
            }

            if (strlen($menuType->getDescricaoMerenda()) > $maxLen) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'CARDÁPIO';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'Descrição para a merenda maior que 1000 caracteres';
                $inconsistencyModel->action = 'Informar uma descrição para a merenda menor que 1000 caracteres';
                $inconsistencyModel->identifier = '11';
                $inconsistencyModel->idLunch = $menu['menu_fk'];
                $inconsistencyModel->idSchool = $schoolId;
                $inconsistencyModel->insert();
            }

            if (!in_array($menuType->getAjustado(), [0, 1])) { // 0: Not, 1: True
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'CARDÁPIO';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'Valor inválido para o campo ajustado';
                $inconsistencyModel->action = 'Marque ou desmarque o checkbox para o campo ajustado';
                $inconsistencyModel->identifier = '11';
                $inconsistencyModel->idLunch = $menu['menu_fk'];
                $inconsistencyModel->idSchool = $schoolId;
                $inconsistencyModel->insert();
            }

            if (!$this->validateDate($menuType->getData(), 2)) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'CARDÁPIO';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = DATA_MATRICULA_INV . '<strong>' . $menuType->getData()->format(DATE_FORMAT) . '</strong>';
                $inconsistencyModel->action = 'Adicione uma data no formato válido';
                $inconsistencyModel->identifier = '11';
                $inconsistencyModel->idLunch = $menu['menu_fk'];
                $inconsistencyModel->idSchool = $schoolId;
                $inconsistencyModel->insert();
            }
        }

        return $menuList;
    }

    public function getDirectorSchool($idSchool): DiretorTType
    {
        $query = 'SELECT
                    cpf AS cpfDiretor,
                    number_ato AS nrAto
                FROM
                    manager_identification
                WHERE
                    school_inep_id_fk = :idSchool;';

        $director = Yii::app()->db->createCommand($query)
            ->bindValue(':idSchool', $idSchool)
            ->queryRow();

        $directorType = new DiretorTType();
        $directorType
            ->setCpfDiretor($director['cpfDiretor'])
            ->setNrAto($director['nrAto']);

        return $directorType;
    }

    /**
     * Summary of ProfissionalTType
     * @return ProfissionalTType[]
     */
    public function getProfessionals($referenceYear, $month)
    {
        $professionalList = [];
        $query = 'SELECT DISTINCT
                    p.id_professional AS id_professional,
                    p.cpf_professional  AS cpfProfissional,
                    p.speciality  AS especialidade,
                    p.inep_id_fk AS idEscola,
                    fundeb
                FROM professional p
                    JOIN attendance a ON p.id_professional  = a.professional_fk  and MONTH(a.date) <= :currentMonth
                WHERE
                    YEAR(a.date) = :reference_year';

        $command = Yii::app()->db->createCommand($query);
        $command->bindValues([
            ':reference_year' => $referenceYear,
            ':currentMonth' => $month
        ]);

        $professionals = $command->queryAll();
        $strMaxLength = 50;

        foreach ($professionals as $professional) {
            $professionalType = new ProfissionalTType();
            $professionalType
                ->setCpfProfissional(str_replace(['.', '-'], '', $professional['cpfProfissional']))
                ->setEspecialidade($professional['especialidade'])
                ->setIdEscola($professional['idEscola'])
                ->setFundeb($professional['fundeb'])
                ->setAtendimento(
                    $this->getAttendances(
                        $professional['id_professional'],
                        $referenceYear,
                        $month
                    )
                );
            $professionalList[] = $professionalType;

            $sql = 'SELECT name FROM school_identification WHERE inep_id = :inepId';
            $params = [':inepId' => $professional['idEscola']];
            $schoolRes = Yii::app()->db->createCommand($sql)->bindValues($params)->queryRow();

            if (!$this->validaCPF($professionalType->getCpfProfissional())) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'PROFISSIONAL';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'CPF inválido: ' . $professional['cpfProfissional'];
                $inconsistencyModel->action = 'Informar um CPF válido';
                $inconsistencyModel->identifier = '2';
                $inconsistencyModel->idProfessional = $professional['id_professional'];
                $inconsistencyModel->idSchool = $professional['idEscola'];
                $inconsistencyModel->insert();
            }

            if (strlen($professionalType->getEspecialidade()) > $strMaxLength) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'PROFISSIONAL';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'Especialidade com mais de 50 caracteres';
                $inconsistencyModel->action = 'Informar uma descrição para a especialidade com até 50 caracteres';
                $inconsistencyModel->identifier = '2';
                $inconsistencyModel->idProfessional = $professional['id_professional'];
                $inconsistencyModel->idSchool = $professional['idEscola'];
                $inconsistencyModel->insert();
            }
        }

        return $professionalList;
    }

    private function getAcceptedEnrollmentStatus(): array
    {
        if (Yii::app()->features->isEnable(TFeature::FEAT_INTEGRATIONS_SAGRES_STATUS_ENROLL)) {
            return [
                \StudentEnrollment::getStatusId(\StudentEnrollment::STATUS_ACTIVE),
                //    \StudentEnrollment::getStatusId(\StudentEnrollment::STATUS_TRANSFERRED),
                \StudentEnrollment::getStatusId(\StudentEnrollment::STATUS_APPROVED),
                \StudentEnrollment::getStatusId(\StudentEnrollment::STATUS_APPROVEDBYCOUNCIL),
                \StudentEnrollment::getStatusId(\StudentEnrollment::STATUS_DISAPPROVED),
            ];
        }

        return [\StudentEnrollment::getStatusId(status: \StudentEnrollment::STATUS_ACTIVE)];
    }

    public function getAttendances($professionalId, $referenceYear, $month)
    {
        $attendanceList = [];

        $query = '
            SELECT
                date AS attendanceDate,
                local AS attendanceLocation
            FROM
                attendance
            WHERE
                professional_fk = :professionalId
                and YEAR(`date`) = :year
                and MONTH(`date`) = :month
        ';

        $attendances = Yii::app()->db->createCommand($query)
            ->bindParam(':professionalId', $professionalId, \PDO::PARAM_INT)
            ->bindParam(':year', $referenceYear, \PDO::PARAM_INT)
            ->bindParam(':month', $month, \PDO::PARAM_INT)
            ->queryAll();

        foreach ($attendances as $attendance) {
            $attendanceType = new AtendimentoTType();
            $attendanceType
                ->setData(new DateTime($attendance['attendanceDate']))
                ->setLocal($attendance['attendanceLocation']);

            $attendanceList[] = $attendanceType;
        }

        return $attendanceList;
    }

    /**
     * Sets a new MatriculaTType
     *
     * @return MatriculaTType[] | null
     */
    public function getEnrollments($classId, $referenceYear, $finalClass, $inepId, $withoutCpf): array|null
    {
        $enrollmentList = [];
        $enrollments = $this->getEnrollmentsInDB($classId, $referenceYear);

        if (empty($enrollments)) {
            return null;
        }

        foreach ($enrollments as $enrollment) {
            $schoolName = $enrollment['school_name'];
            $convertedBirthdate = $this->convertBirthdate($enrollment['birthdate']);

            if ($convertedBirthdate === false) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = STUDENT_STRONG;
                $inconsistencyModel->school = $schoolName;
                $inconsistencyModel->description = INCONSISTENCY_BIRTH_DATE_INVALID . ': <strong>' . $enrollment['birthdate'] . '</strong>';
                $inconsistencyModel->action = INCONSISTENCY_ACTION_BIRTH_DATE_INVALID;
                $inconsistencyModel->identifier = '9';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->idStudent = $enrollment['student_fk'];
                $inconsistencyModel->idSchool = $inepId;
                $inconsistencyModel->insert();
                continue;
            }

            $cpf = $this->getStudentCpf($enrollment);

            $studentType = $this->generateStudentType($withoutCpf, $convertedBirthdate, $enrollment, $cpf);

            $enrollmentType = new MatriculaTType();
            $enrollmentType
                ->setNumero($enrollment['numero'])
                ->setDataMatricula(new DateTime($enrollment['data_matricula'] ?? ''))
                ->setNumeroFaltas((int) $enrollment['faults'])
                ->setAluno($studentType)
                ->setEnrollmentStage($enrollment['enrollment_stage']);

            $this->matriculaValidation($enrollment, $enrollmentType, $studentType, $schoolName, $classId, $finalClass);
            $this->checkSingleStudentWithoutCpf($enrollments, $cpf, $classId, $referenceYear, $schoolName, $inepId);

            $enrollmentList[] = $enrollmentType;
        }

        return $enrollmentList;
    }

    private function generateStudentType($withoutCpf, $convertedBirthdate, $enrollment, $cpf)
    {
        return $withoutCpf
            ? $this->studentTypeCaseWithoutCpf($convertedBirthdate, $enrollment, $cpf)
            : $this->studentTypeCaseWithCpf($enrollment, $convertedBirthdate, $cpf);
    }

    private function studentTypeCaseWithCpf($enrollment, $convertedBirthdate, $cpf)
    {
        $birthdate = DateTime::createFromFormat(DATE_FORMAT, $convertedBirthdate);
        $strlen = 5;
        $schoolName = $enrollment['school_name'];
        $classId = $enrollment['class_id'];

        $studentType = new AlunoTType();
        $studentType
            ->setNome($enrollment['name'])
            ->setDataNascimento($birthdate)
            ->setPcd($enrollment['deficiency'])
            ->setSexo($enrollment['gender']);

        if (!empty($cpf)) {
            $studentType->setCpfAluno($cpf);
            $this->studentValidation($studentType, $schoolName, $cpf, $classId, $enrollment, $strlen);
        } else {
            $studentType->setJustSemCpf($enrollment['cpf_reason']);
            $this->studentValidationWhioutCpf($studentType, $schoolName, $enrollment['cpf_reason'], $classId, $enrollment, $strlen);
        }

        $this->checkStudentModality($enrollment, $convertedBirthdate);
        $this->isNullStudentType($studentType, $schoolName, $enrollment, $classId);
        return $studentType;
    }

    private function studentTypeCaseWithoutCpf($convertedBirthdate, $enrollment, $cpf)
    {
        $strlen = 5;
        $schoolName = $enrollment['school_name'];
        $classId = $enrollment['class_id'];
        $birthdate = DateTime::createFromFormat(DATE_FORMAT, $convertedBirthdate);
        $studentType = new AlunoTType();
        $studentType
            ->setNome($enrollment['name'])
            ->setDataNascimento($birthdate)
            ->setPcd($enrollment['deficiency'])
            ->setSexo($enrollment['gender']);

        if (!empty($cpf)) {
            $studentType->setCpfAluno($cpf);
            $this->studentValidation($studentType, $schoolName, $cpf, $classId, $enrollment, $strlen);
        } else {
            $studentType->setJustSemCpf($enrollment['cpf_reason']);
            $this->studentValidationWhioutCpf($studentType, $schoolName, $enrollment['cpf_reason'], $classId, $enrollment, $strlen);
        }

        $this->checkStudentModality($enrollment, $convertedBirthdate);
        $this->isNullStudentType($studentType, $schoolName, $enrollment, $classId);
        return $studentType;
    }

    private function checkStudentModality($enrollment, $birthdate)
    {
        $arrayStudentInfo = [
            'studentFk' => $enrollment['student_fk'],
            'classroomFk' => $enrollment['class_id'],
            'schoolInepIdFk' => $enrollment['inep_id']
        ];
        $modality = $enrollment['modality'];
        //3 - EJA
        if ($modality === 3) {
            $educationLevel = (int) $this->getStageById($enrollment['edcenso_stage_vs_modality_fk']);
            $age = $this->calculateAge($birthdate);
            $this->checkAge($age, $educationLevel, $arrayStudentInfo);
        }
    }

    private function getStudentCpf($enrollment)
    {
        $query1 = 'SELECT cpf from student_documents_and_address WHERE id = :idStudent';
        $command = Yii::app()->db->createCommand($query1);
        $command->bindValues([':idStudent' => $enrollment['id']]);
        return $command->queryScalar();
    }

    private function isNullStudentType($studentType, $school, $enrollment, $classId)
    {
        if (is_null($studentType)) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = STUDENT_STRONG;
            $inconsistencyModel->school = $school->name;
            $inconsistencyModel->description = INCONSISTENCY_STUDENT_NOT_FOUND_FOR_CLASS_REGISTRATION;
            $inconsistencyModel->action = INCONSISTENCY_ACTION_STUDENT_NOT_FOUND_FOR_CLASS_REGISTRATION;
            $inconsistencyModel->identifier = '9';
            $inconsistencyModel->idStudent = $enrollment['student_fk'];
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }
    }

    private function getEnrollmentsInDB($classId, $referenceYear)
    {
        $acceptedStatus = $this->getAcceptedEnrollmentStatus();

        $strAcceptedStatus = implode(',', $acceptedStatus);

        $query = "SELECT
                        c.edcenso_stage_vs_modality_fk,
                        c.modality,
                        se.id as numero,
                        c.school_inep_fk as inep_id,
                        se.classroom_fk as class_id,
                        se.student_fk,
                        se.create_date AS data_matricula,
                        se.date_cancellation_enrollment AS data_cancelamento,
                        se.status AS situation,
                        se.edcenso_stage_vs_modality_fk AS enrollment_stage,
                        si.responsable_cpf AS cpfStudent,
                        si.birthday AS birthdate,
                        si.name AS name,
                        sdaa.cpf_reason,
                        ifnull(si.deficiency, 0) AS deficiency,
                        si.sex AS gender,
                        si.id,
                        si2.name AS school_name,
                        CASE
                            WHEN c.edcenso_stage_vs_modality_fk IN (2, 3, 4, 5, 6, 7, 8, 9, 14, 15, 16, 17, 18, 19, 75)  THEN
                                (SELECT if(((SELECT COUNT(schedule) FROM class_faults cf
                                    JOIN schedule s ON s.id = cf.schedule_fk
                                    WHERE s.year = :referenceYear AND cf.student_fk = si.id) / (SELECT COUNT(DISTINCT day) FROM class_faults cf
                                    JOIN schedule s ON s.id = cf.schedule_fk
                                    WHERE s.year = :referenceYear AND cf.student_fk = si.id)) = (SELECT MAX(schedule)
                                    FROM class_faults cf
                                    JOIN schedule s ON s.id = cf.schedule_fk
                                    WHERE s.year = :referenceYear AND classroom_fk = :classId), (SELECT COUNT(schedule) FROM class_faults cf
                                    JOIN schedule s ON s.id = cf.schedule_fk
                                    WHERE s.year = :referenceYear AND cf.student_fk = si.id) / (SELECT COUNT(schedule) / COUNT(DISTINCT day)  FROM class_faults cf
                                    JOIN schedule s ON s.id = cf.schedule_fk
                                    WHERE s.year = :referenceYear AND cf.student_fk = si.id), IF(count(day) IS NULL, 0, count(day))) FROM class_faults cf
                                    JOIN schedule s ON s.id = cf.schedule_fk
                                    WHERE s.year = :referenceYear AND cf.student_fk = si.id)
                            ELSE
                                (SELECT COUNT(*) FROM class_faults cf
                                    JOIN schedule s ON s.id = cf.schedule_fk
                                    WHERE s.year = :referenceYear AND cf.student_fk = si.id)
                        END AS faults
                  FROM
                        student_enrollment se
                        join classroom c on se.classroom_fk = c.id
                        join student_identification si on si.id = se.student_fk
                        join student_documents_and_address sdaa on si.id = sdaa.id
                        left join class_faults cf on cf.student_fk = si.id
                        left join schedule s on cf.schedule_fk = s.id
                        join school_identification si2 on si2.inep_id= c.school_inep_fk
                  WHERE
                        se.classroom_fk  =  :classId AND
                        (se.status in ($strAcceptedStatus) or se.status is null) AND
                        c.school_year = :referenceYear
                  GROUP BY se.id
                  order by si.name asc;

                ";

        $command = Yii::app()->db->createCommand($query);
        $command->bindValues([
            ':classId' => $classId,
            ':referenceYear' => $referenceYear,
        ]);

        return $command->queryAll();
    }

    private function studentValidation($studentType, $schoolName, $cpf, $classId, $enrollment, $strlen): void
    {
        if (!is_null($studentType->getCpfAluno())) {
            if ($this->cpfLength($studentType->getCpfAluno())) {
                if (!$this->validaCPF($studentType->getCpfAluno())) {
                    $inconsistencyModel = new ValidationSagresModel();
                    $inconsistencyModel->enrollment = STUDENT_STRONG;
                    $inconsistencyModel->school = $schoolName;
                    $inconsistencyModel->description = 'Cpf do estudante é inválido: <strong>' . $cpf . '<strong>';
                    $inconsistencyModel->action = 'Informe um cpf válido para o estudante: <strong>' . $enrollment['name'] . '<strong>';
                    $inconsistencyModel->identifier = '9';
                    $inconsistencyModel->idStudent = $enrollment['student_fk'];
                    $inconsistencyModel->idClass = $classId;
                    $inconsistencyModel->insert();
                }
            } else {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = STUDENT_STRONG;
                $inconsistencyModel->school = $schoolName;
                $inconsistencyModel->description = 'CPF do estudante não contém 11 números: <strong>' . $cpf . '<strong>';
                $inconsistencyModel->action = ' Insira um CPF válido com exatamente 11 números para o estudante: <strong>' . $enrollment['name'] . '<strong>';
                $inconsistencyModel->identifier = '9';
                $inconsistencyModel->idStudent = $enrollment['student_fk'];
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->insert();
            }
        }

        if (is_null($studentType->getCpfAluno())) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = STUDENT_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = 'É obrigatório informar o cpf do estudante';
            $inconsistencyModel->action = 'Informe um cpf para o estudante: <strong>' . $enrollment['name'] . '<strong>';
            $inconsistencyModel->identifier = '9';
            $inconsistencyModel->idStudent = $enrollment['student_fk'];
            $inconsistencyModel->insert();
        }

        if (!$this->validateDate($studentType->getDataNascimento(), 1)) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = STUDENT_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = 'Data de nascimento não é válida: <strong>' . $studentType->getDataNascimento()->format(DATE_FORMAT) . '</strong>';
            $inconsistencyModel->action = 'Adicione uma data válida para o estudante: <strong>' . $studentType->getNome() . '</strong>';
            $inconsistencyModel->identifier = '9';
            $inconsistencyModel->idStudent = $enrollment['student_fk'];
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }

        if ($this->dataMax($studentType->getDataNascimento())) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = STUDENT_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = INCONSISTENCY_BIRTH_AFTER_LIMIT;
            $inconsistencyModel->action = 'Adicione uma data válida para o estudante: <strong>' . $studentType->getNome() . '</strong>';
            $inconsistencyModel->identifier = '9';
            $inconsistencyModel->idStudent = $enrollment['student_fk'];
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }

        if ($this->dataMin($studentType->getDataNascimento())) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = STUDENT_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = INCONSISTENCY_BIRTH_BEFORE_LIMIT;
            $inconsistencyModel->action = 'Adicione uma data válida para o estudante: <strong>' . $studentType->getNome() . '</strong>';
            $inconsistencyModel->identifier = '9';
            $inconsistencyModel->idStudent = $enrollment['student_fk'];
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }

        if (strlen($studentType->getNome()) < $strlen) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = STUDENT_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = INCONSISTENCY_STUDENT_NAME_TOO_SHORT;
            $inconsistencyModel->action = INCONSISTENCY_ACTION_STUDENT_NAME_TOO_SHORT;
            $inconsistencyModel->identifier = '9';
            $inconsistencyModel->idStudent = $enrollment['student_fk'];
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }

        if (!is_bool(boolval($studentType->getPcd()))) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = STUDENT_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = INCONSISTENCY_PCD_CODE_INVALID;
            $inconsistencyModel->action = INCONSISTENCY_ACTION_PCD_CODE_INVALID;
            $inconsistencyModel->identifier = '9';
            $inconsistencyModel->idStudent = $enrollment['student_fk'];
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }

        if ($studentType->getDataNascimento() === false) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = STUDENT_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = INCONSISTENCY_BIRTH_DATE_INVALID;
            $inconsistencyModel->action = INCONSISTENCY_ACTION_BIRTH_DATE_INVALID_FORMAT;
            $inconsistencyModel->identifier = '9';
            $inconsistencyModel->idStudent = $enrollment['student_fk'];
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }

        if (!in_array($studentType->getSexo(), [1, 2, 3])) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = STUDENT_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = INCONSISTENCY_SEX_INVALID;
            $inconsistencyModel->action = INCONSISTENCY_ACTION_SEX_INVALID;
            $inconsistencyModel->identifier = '9';
            $inconsistencyModel->idStudent = $enrollment['student_fk'];
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }
    }

    private function studentValidationWhioutCpf($studentType, $schoolName, $cpf_reason, $classId, $enrollment, $strlen): void
    {
        $cpfReasons = [1, 2, 3];
        if (!in_array($cpf_reason, $cpfReasons)) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = '<strong>ESTUDANTE</strong>';
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = 'Justificativa incorreta para ausência de cpf';
            $inconsistencyModel->action = 'Adicione uma justificativa válida para <strong>' . $studentType->getNome() . '</strong> está sem cpf';
            $inconsistencyModel->identifier = '9';
            $inconsistencyModel->idStudent = $enrollment['id'];
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }

        if (!$this->validateDate($studentType->getDataNascimento(), 1)) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = STUDENT_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = 'Data de nascimento não é válida: <strong>' . $studentType->getDataNascimento()->format(DATE_FORMAT) . '</strong>';
            $inconsistencyModel->action = 'Adicione uma data válida para o estudante: <strong>' . $studentType->getNome() . '</strong>';
            $inconsistencyModel->identifier = '9';
            $inconsistencyModel->idStudent = $enrollment['student_fk'];
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }

        if ($this->dataMax($studentType->getDataNascimento())) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = STUDENT_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = INCONSISTENCY_BIRTH_AFTER_LIMIT;
            $inconsistencyModel->action = 'Adicione uma data válida para o estudante: <strong>' . $studentType->getNome() . '</strong>';
            $inconsistencyModel->identifier = '9';
            $inconsistencyModel->idStudent = $enrollment['student_fk'];
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }

        if ($this->dataMin($studentType->getDataNascimento())) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = STUDENT_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = INCONSISTENCY_BIRTH_BEFORE_LIMIT;
            $inconsistencyModel->action = 'Adicione uma data válida para o estudante: <strong>' . $studentType->getNome() . '</strong>';
            $inconsistencyModel->identifier = '9';
            $inconsistencyModel->idStudent = $enrollment['student_fk'];
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }

        if (strlen($studentType->getNome()) < $strlen) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = STUDENT_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = INCONSISTENCY_STUDENT_NAME_TOO_SHORT;
            $inconsistencyModel->action = INCONSISTENCY_ACTION_STUDENT_NAME_TOO_SHORT;
            $inconsistencyModel->identifier = '9';
            $inconsistencyModel->idStudent = $enrollment['student_fk'];
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }

        if (!is_bool(boolval($studentType->getPcd()))) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = STUDENT_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = INCONSISTENCY_PCD_CODE_INVALID;
            $inconsistencyModel->action = INCONSISTENCY_ACTION_PCD_CODE_INVALID;
            $inconsistencyModel->identifier = '9';
            $inconsistencyModel->idStudent = $enrollment['student_fk'];
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }

        if ($studentType->getDataNascimento() === false) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = STUDENT_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = INCONSISTENCY_BIRTH_DATE_INVALID;
            $inconsistencyModel->action = INCONSISTENCY_ACTION_BIRTH_DATE_INVALID_FORMAT;
            $inconsistencyModel->identifier = '9';
            $inconsistencyModel->idStudent = $enrollment['student_fk'];
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }

        if (!in_array($studentType->getSexo(), [1, 2, 3])) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = STUDENT_STRONG;
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = INCONSISTENCY_SEX_INVALID;
            $inconsistencyModel->action = INCONSISTENCY_ACTION_SEX_INVALID;
            $inconsistencyModel->identifier = '9';
            $inconsistencyModel->idStudent = $enrollment['student_fk'];
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }
    }

    private function matriculaValidation($enrollment, $enrollmentType, $studentType, $schoolName, $classId, $finalClass): void
    {
        if (filter_var($finalClass, FILTER_VALIDATE_BOOLEAN)) {
            $enrollmentType->setAprovado($this->getStudentSituation($enrollment['situation']));
        }

        if (empty($enrollmentType)) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = 'MATRÍCULA';
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = INCONSISTENCY_NO_REGISTRATION_FOR_CLASS;
            $inconsistencyModel->action = INCONSISTENCY_ACTION_NO_REGISTRATION_FOR_CLASS;
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }

        if (!$this->validateDate($enrollmentType->getDataMatricula(), 2)) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = 'MATRÍCULA';
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = DATA_MATRICULA_INV . '<strong>' . $enrollmentType->getDataMatricula()->format(DATE_FORMAT) . '</strong>';
            $inconsistencyModel->action = 'Adicione uma data no formato válido';
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }

        if (!is_int($enrollmentType->getNumeroFaltas())) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = 'MATRÍCULA';
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = INCONSISTENCY_INVALID_ABSENCE_VALUE;
            $inconsistencyModel->action = INCONSISTENCY_ACTION_INVALID_ABSENCE_VALUE;
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }

        if (filter_var($finalClass, FILTER_VALIDATE_BOOLEAN) && !is_bool($enrollmentType->getAprovado())) {
            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = 'MATRÍCULA';
            $inconsistencyModel->school = $schoolName;
            $inconsistencyModel->description = INCONSISTENCY_ACTION_INVALID_ABSENCE_VALUE;
            $inconsistencyModel->action = INCONSISTENCY_ACTION_INVALID_APPROVED_STATUS_VALUE . ': ' . $studentType->getNome();
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->insert();
        }
    }

    private function checkSingleStudentWithoutCpf(array $enrollments, $cpf, $classId, $referenceYear, $school, $inepId)
    {
        if (count($enrollments) === 1 && empty($cpf)) {
            $className = $this->getClassName($classId, $referenceYear);

            $inconsistencyModel = new ValidationSagresModel();
            $inconsistencyModel->enrollment = TURMA_STRONG;
            $inconsistencyModel->school = $school->name;
            $inconsistencyModel->description = 'A turma: <strong>' . $className . '</strong> possui apenas um estudante sem CPF. Como o sistema não aceita matrículas sem CPF, esta turma é considerada sem alunos matriculados.';
            $inconsistencyModel->action = 'Adicione o CPF do aluno ou delete a turma.';
            $inconsistencyModel->identifier = '10';
            $inconsistencyModel->idClass = $classId;
            $inconsistencyModel->idSchool = $inepId;
            $inconsistencyModel->insert();
        }
    }

    private function getStageById($id)
    {
        $command = Yii::app()->db->createCommand('SELECT esvm.stage FROM edcenso_stage_vs_modality esvm WHERE id = :id');
        $command->bindParam(':id', $id);
        $stage = $command->queryScalar();

        return $stage;
    }

    private function calculateAge($birthdate): int
    {
        $today = new DateTime();
        $newBirthdate = DateTime::createFromFormat('d/m/Y', $birthdate);

        if ($newBirthdate === false) {
            $formats = [
                'Y-m-d',
                'd/m/Y',
                'd-m-Y',
                'm/d/Y',
                'd M Y',
                'd F Y',
                DateTime::RFC3339,
                DateTime::ATOM
            ];
            foreach ($formats as $format) {
                $dt = DateTime::createFromFormat($format, $birthdate);
                if ($dt && $dt->format($format) === $birthdate) {
                    $newBirthdate = $dt;
                    break;
                }
            }
        }
        $age = $today->diff($newBirthdate);
        return (int) $age->y;
    }

    private function getClassName($id, $year)
    {
        $sql = 'SELECT c.name from classroom c WHERE c.id = :id and c.school_year = :year';
        return Yii::app()->db->createCommand($sql)
            ->bindParam(':id', $id)
            ->bindParam(':year', $year)
            ->queryScalar();
    }

    public function convertBirthdate($birthdate)
    {
        $date = DateTime::createFromFormat('Y-m-d', $birthdate);
        if ($date && $date->format('Y-m-d') === $birthdate) {
            return $date->format(DATE_FORMAT);
        }

        $date = DateTime::createFromFormat(DATE_FORMAT, $birthdate);
        if ($date && $date->format(DATE_FORMAT) === $birthdate) {
            return $birthdate;
        }

        return false;
    }

    public function getStudentSituation($situation)
    {
        $situations = [
            0 => false, // Não frequentou
            1 => false, // Reprovado
            2 => false, // Afastado por transferência
            3 => false, // Afastado por abandono
            4 => false, // Matrícula final em Educação Infantil
            5 => true   // Promovido
        ];

        if (isset($situations[$situation])) {
            return $situations[$situation];
        }
        return false;
    }

    public function returnNumberFaults($studentId, $referenceYear)
    {
        $sql = 'SELECT
                    COUNT(*)
                FROM
                    class_faults cf
                    JOIN schedule s ON s.id = cf.schedule_fk
                    JOIN classroom c on c.id = s.classroom_fk
                WHERE
                    cf.student_fk = :studentId AND
                    c.school_year = :referenceYear;';

        $numberFaults = Yii::app()->db->createCommand($sql)
            ->bindValues([
                ':studentId' => $studentId,
                ':referenceYear' => $referenceYear
            ])->queryScalar();

        return $numberFaults ?? 0;
    }

    public function generatesSagresEduXML($sagresEduObject)
    {
        $serializerBuilder = SerializerBuilder::create();
        $serializerBuilder->addMetadataDir('app/modules/sagres/soap/metadata/sagresEduMetadata', 'DataSagresEdu');
        $serializerBuilder->configureHandlers(function (HandlerRegistryInterface $handler) use ($serializerBuilder) {
            $serializerBuilder->addDefaultHandlers();
            $handler->registerSubscribingHandler(new BaseTypesHandler()); // XMLSchema List handling
            $handler->registerSubscribingHandler(new XmlSchemaDateHandler()); // XMLSchema date handling
        });
        $serializer = $serializerBuilder->build();

        $xmlString = $serializer->serialize($sagresEduObject, 'xml');

        return $this->clearSpecialCharacters($xmlString);
    }

    private function clearSpecialCharacters($string)
    {
        return preg_replace("/\xEF\xBF\xBD/", '', $string);
    }

    public function actionExportSagresXML($xml)
    {
        $fileName = 'Educacao.xml';

        $inst = 'File_' . INSTANCE . '/';
        $path = './app/export/SagresEdu/' . $inst;

        if (!file_exists($path)) {
            mkdir($path);
        }

        $fileDir = './app/export/SagresEdu/' . $inst . $fileName;
        Yii::import('ext.FileManager.fileManager');
        $fm = new fileManager();
        $result = $fm->write($fileDir, $xml);
        if ($result === false) {
            throw new ErrorException('Ocorreu um erro ao exportar o arquivo XML.');
        }
        $content = file_get_contents($fileDir);
        $zipName = './app/export/SagresEdu/' . $inst . 'Educacao.zip';
        $tempArchiveZip = new ZipArchive();
        $tempArchiveZip->open($zipName, ZipArchive::CREATE);
        $tempArchiveZip->addFromString(pathinfo($fileDir, PATHINFO_BASENAME), $content);
        $tempArchiveZip->close();
        $content = null;
    }

    public function validatorSagresEduExportXML($object)
    {
        // get the validator
        $builder = Validation::createValidatorBuilder();
        foreach (glob('C:\Users\JoseNatan\Documents\Developer\br.tag\app\modules\sagres\soap\metadata\sagresEduMetadata') as $file) {
            $builder->addYamlMapping($file);
        }
        $validator = $builder->getValidator();

        // validate $object
        return $validator->validate($object, null, ['xsd_rules']);
    }

    /**
     * This function takes a single character string representing a turn abbreviation and returns an integer value
     * that corresponds to the turn type. The valid turn types and their corresponding integer values are:
     * - 'M': 1 (MATUTINO)
     * - 'V': 2 (VESPERTINO)
     * - 'N': 3 (NOTURNO)
     * - 'I': 4 (INTEGRAL)
     * @param string $turn A single character string representing a turn abbreviation.
     * @return int The corresponding integer value of the turn type
     */
    public function convertTurn($turn)
    {
        $turnos = [
            'M' => 1,
            'T' => 2,
            'N' => 3,
            'I' => 4,
        ];

        if (isset($turnos[$turn])) {
            return $turnos[$turn];
        } else {
            return 0;
        }
    }

    public function validateDate($date, int $type): bool
    {
        $format = 'Y-m-d';
        if ($date instanceof Datetime) {
            $dat = $date->format('Y-m-d');
        } else {
            $dt = DateTime::createFromFormat($format, $date);
            if ($dt === false) {
                return false;
            }

            $dat = $dt->format('Y-m-d');
        }

        $d = DateTime::createFromFormat($format, $dat);
        if ($d === false) {
            return false;
        }
        $year = intval($d->format('Y'));
        $currentYear = intval(date('Y'));

        /*
         * 1 - Data de nascimento
         * 2 - Data de matrícula
         */
        if ($type === 1) {
            if ($year < 1924 || $year > $currentYear) {
                return false;
            }
        } elseif ($type === 2) {
            if ($year < 1924) {
                return false;
            }
        }

        return $d && $d->format($format) == $dat;
    }

    public function validaCPF(string $cpf): bool
    {
        // Remove caracteres não numéricos
        $cpf = preg_replace('/\D/', '', $cpf);

        // Verifica tamanho e repetições
        if (strlen($cpf) !== 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Calcula e verifica os dígitos verificadores
        for ($t = 9; $t < 11; $t++) {
            $digit = 0;
            for ($c = 0; $c < $t; $c++) {
                $digit += $cpf[$c] * (($t + 1) - $c);
            }
            $digit = ((10 * $digit) % 11) % 10;

            // Verifica o dígito atual
            if ((int) $cpf[$c] !== $digit) {
                return false;
            }
        }

        return true;
    }

    private function dataMax(DateTime $data): bool
    {
        $dataMaxima = new DateTime('2024-08-30');

        return $data > $dataMaxima;
    }

    private function dataMin(DateTime $data): bool
    {
        $dataMinima = new DateTime('1923-01-01');

        return $data < $dataMinima;
    }

    private function cpfLength($cpf): bool
    {
        return strlen($cpf) === 11;
    }
}
