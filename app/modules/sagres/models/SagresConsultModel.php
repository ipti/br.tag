<?php

namespace SagresEdu;

require_once 'vendor/autoload.php';

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

use ValidationSagresModel;

use Yii;
use ZipArchive;

/**
 * Summary of SagresConsultModel
 */
class SagresConsultModel
{
    private $dbCommand;

    public function __construct()
    {
        $this->dbCommand = Yii::app()->db->createCommand();
    }

    public function cleanInconsistences()
    {
        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();

        try {
            $deleteQuery = "DELETE FROM inconsistency_sagres";
            $connection->createCommand($deleteQuery)->execute();

            $resetQuery = "ALTER TABLE inconsistency_sagres AUTO_INCREMENT = 1";
            $connection->createCommand($resetQuery)->execute();

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }

    public function getSagresEdu($referenceYear, $month, $finalClass, $noMovement): EducacaoTType
    {
        $education = new EducacaoTType();
        $managementUnitId = $this->getManagementId();

        if($noMovement) {
            $education->setPrestacaoContas($this->getManagementUnit($managementUnitId, $referenceYear, $month));
            return $education;
        }

        try {
            $education
                ->setPrestacaoContas($this->getManagementUnit($managementUnitId, $referenceYear, $month))
                ->setEscola($this->getSchools($referenceYear, $month, $finalClass))
                ->setProfissional($this->getProfessionals($referenceYear, $month));
        } catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }

        return $education;
    }

    public function getManagementUnit($managementUnitId, $referenceYear, $month): CabecalhoTType
    {

        $finalDay = date('t', strtotime("$referenceYear-$month-01"));

        try {
            $query = "SELECT
                        pa.id AS managementUnitId,
                        pa.cod_unidade_gestora AS managementUnitCode,
                        pa.name_unidade_gestora AS managementUnitName,
                        pa.cpf_responsavel AS responsibleCpf,
                        pa.cpf_gestor AS managerCpf
                    FROM
                        provision_accounts pa
                    WHERE
                        pa.id = :managementUnitId";

            $managementUnit = Yii::app()->db->createCommand($query)
                ->bindValue(':managementUnitId', $managementUnitId)
                ->queryRow();

            $headerType = new CabecalhoTType();

            $headerType
                ->setCodigoUnidGestora($managementUnit['managementUnitCode'])
                ->setNomeUnidGestora($managementUnit['managementUnitName'])
                ->setCpfResponsavel(str_replace([".", "-"], "", $managementUnit['responsibleCpf']))
                ->setCpfGestor(str_replace([".", "-"], "", $managementUnit['managerCpf']))
                ->setAnoReferencia((int) $referenceYear)
                ->setMesReferencia((int) $month)
                ->setVersaoXml(1)
                ->setDiaInicPresContas((int) 01)
                ->setDiaFinaPresContas((int) $finalDay);

            if (empty($managementUnit['managementUnitCode'])) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'UNIDADE GESTORA: ' . $managementUnit['managementUnitName'];
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'CÓDIGO DA UNIDADE GESTORA NÃO INFORMADO';
                $inconsistencyModel->action = 'POR FAVOR, INFORME O CÓDIGO DE IDENTIFICAÇÃO DA UNIDADE GESTORA';
                $inconsistencyModel->insert();
            }

            if (empty($managementUnit['managementUnitName'])) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'UNIDADE GESTORA';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'NOME DA UNIDADE GESTORA NÃO INFORMADO';
                $inconsistencyModel->action = 'POR FAVOR, INFORME UM NOME PARA A UNIDADE GESTORA';
                $inconsistencyModel->insert();
            }

            if (empty($managementUnit['responsibleCpf'])) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'UNIDADE GESTORA: ' . $managementUnit['managementUnitName'];
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'CPF DO RESPONSÁVEL NÃO INFORMADO';
                $inconsistencyModel->action = 'POR FAVOR, INFORME UM CPF VÁLIDO PARA O RESPONSÁVEL';
                $inconsistencyModel->insert();
            }

            if (empty($managementUnit['managerCpf'])) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'UNIDADE GESTORA: ' . $managementUnit['managementUnitName'];
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'CPF DO GESTOR NÃO INFORMADO';
                $inconsistencyModel->action = 'POR FAVOR, INFORME UM CPF VÁLIDO PARA O GESTOR';
                $inconsistencyModel->insert();
            }

            return $headerType;
        } catch (Exception $e) {
            throw new Exception("Ocorreu um erro ao buscar a unidade gestora");
        }
    }

    /**
     * Summary of getManagementId
     * @throws Exception
     * @return int|null
     */
    public function getManagementId()
    {
        $query = "SELECT id, cod_unidade_gestora FROM provision_accounts";

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
    public function getSchools($referenceYear, $month, $finalClass)
    {
        $schoolList = [];

        $query = "SELECT inep_id FROM school_identification";
        $schools = Yii::app()->db->createCommand($query)->queryAll();

        foreach ($schools as $school) {
            $schoolType = new EscolaTType();
            $schoolType
                ->setIdEscola($school['inep_id'])
                ->setTurma($this->getClasses($school['inep_id'], $referenceYear, $month, $finalClass))
                ->setDiretor($this->getDirectorSchool($school['inep_id']))
                ->setCardapio($this->getMenuList($school['inep_id'], $referenceYear, $month));

            $schoolList[] = $schoolType;

            $strMaxLength = 100;
            $diretor = $schoolType->getDiretor();
            $inconsistencies = [];

            $sql = "SELECT name FROM school_identification WHERE inep_id = :inepId";
            $params = array(':inepId' => $school['inep_id']);
            $schoolRes = Yii::app()->db->createCommand($sql)->bindValues($params)->queryRow();

            if ($diretor->getNrAto() == null) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'DIRETOR';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'NÚMERO DO ATO DE NOMEAÇÃO NÃO PODE SER VAZIO';
                $inconsistencyModel->action = 'INFORMAR UM NÚMERO DO ATO DE NOMEAÇÃO PARA O DIRETOR';
                $inconsistencyModel->identifier = '4';
                $inconsistencyModel->idSchool = $school['inep_id'];
                $inconsistencyModel->insert();
            }

            if (strlen($diretor->getNrAto()) > $strMaxLength) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'DIRETOR';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'NÚMERO DO ATO DE NOMEAÇÃO COM MAIS DE 100 CARACTERES';
                $inconsistencyModel->action = 'INFORMAR UM NÚMERO DO ATO DE NOMEAÇÃO COM ATÉ 100 CARACTERES';
                $inconsistencyModel->identifier = '4';
                $inconsistencyModel->idSchool = $school['inep_id'];
                $inconsistencyModel->insert();
            }

            if ($diretor->getCpfDiretor() === null || !preg_match('/^[0-9]{11}$/', $diretor->getCpfDiretor())) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'DIRETOR';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'CPF NÃO CADASTRADO OU CPF NO FORMATO INVÁLIDO PARA O DIRETOR';
                $inconsistencyModel->action = 'INFORMAR UM CPF VÁLIDO PARA O DIRETOR';
                $inconsistencyModel->identifier = '4';
                $inconsistencyModel->idSchool = $school['inep_id'];
                $inconsistencyModel->insert();
            }

            if (!$this->validaCPF($diretor->getCpfDiretor())) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'DIRETOR';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'CPF DO DIRETOR INVÁLIDO';
                $inconsistencyModel->action = 'INFORMAR UM CPF VÁLIDO PARA O DIRETOR';
                $inconsistencyModel->identifier = '4';
                $inconsistencyModel->idSchool = $school['inep_id'];
                $inconsistencyModel->insert();
            }

            if (is_null($inconsistencies)) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'DIRETOR';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'NÃO EXISTE DIRETOR CADASTRADO PARA A ESCOLA';
                $inconsistencyModel->action = 'ADICIONE UM DIRETOR PARA A ESCOLA';
                $inconsistencyModel->identifier = '4';
                $inconsistencyModel->idSchool = $school['inep_id'];
                $inconsistencyModel->insert();
            }
        }

        return $schoolList;
    }

    public function getInconsistenciesCount()
    {
        $query = "SELECT count(*) FROM inconsistency_sagres";
        return Yii::app()->db->createCommand($query)->queryScalar();
    }

    public function getNameSchool($idSchool)
    {
        $query = "SELECT name FROM school_identification where inep_id = :idSchool";

        return Yii::app()->db->createCommand($query)->bindValue(":idSchool", $idSchool)->queryScalar();
    }

    /**
     * Summary of TurmaTType
     * @return TurmaTType[]
     */
    public function getClasses($inepId, $referenceYear, $month, $finalClass)
    {
        $classList = [];
        $strMaxLength = 50;
        $strlen = 2;

        $query = "SELECT
                    c.initial_hour AS initialHour,
                    c.school_inep_fk AS schoolInepFk,
                    c.id AS classroomId,
                    c.name AS classroomName,
                    c.turn AS classroomTurn
                FROM
                    classroom c
                WHERE
                    c.school_inep_fk = :schoolInepFk
                    AND c.school_year = :referenceYear";

        $params = [
            ':schoolInepFk' => $inepId,
            ':referenceYear' => $referenceYear
        ];

        $turmas = $this->dbCommand->setText($query)
            ->bindValues($params)
            ->queryAll();

        foreach ($turmas as $turma) {
            $classType = new TurmaTType();
            $classId = $turma['classroomId'];

            $classType
                ->setPeriodo(0) //0 - Anual
                ->setDescricao($turma["classroomName"])
                ->setTurno($this->convertTurn($turma['classroomTurn']))
                ->setSerie($this->getSeries($classId))
                ->setMatricula($this->getEnrollments($classId, $referenceYear, $month, $finalClass))
                ->setHorario($this->getSchedules($classId, $month))
                ->setFinalTurma(filter_var($finalClass, FILTER_VALIDATE_BOOLEAN));


            if (!is_null($classType->getHorario()) && !is_null($classType->getMatricula())) {
                $classList[] = $classType;
            }

            $query = "SELECT id FROM classroom WHERE name = :descript AND school_inep_fk = :school_inep_fk";
            $idClassroom = Yii::app()->db->createCommand($query)
                ->bindValue(':descript', $turma["classroomName"])
                ->bindValue(':school_inep_fk', $inepId)
                ->queryScalar();

            $sql = "SELECT name FROM school_identification WHERE inep_id = :inepId";
            $params = array(':inepId' => $inepId);
            $schoolRes = Yii::app()->db->createCommand($sql)->bindValues($params)->queryRow();

            /*
             *  [0 : Anual], [1 : 1°], [2 : 2º] Semestre
             */
            if (!in_array($classType->getPeriodo(), [0, 1, 2])) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'TURMA';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'VALOR INVÁLIDO PARA O PERÍODO';
                $inconsistencyModel->action = 'ADICIONE UM VALOR VÁLIDO PARA O PERÍODO DA TURMA: ' . $classType->getDescricao();
                $inconsistencyModel->identifier = '10';
                $inconsistencyModel->idClass = $idClassroom;
                $inconsistencyModel->idSchool = $inepId;
                $inconsistencyModel->insert();
            }

            if (strlen($classType->getDescricao()) <= $strlen && !is_null($classType->getDescricao())) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'TURMA';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'DESCRIÇÃO PARA A TURMA MENOR QUE 3 CARACTERES';
                $inconsistencyModel->action = 'ADICIONE UMA DESCRIÇÃO MAIS DETALHADA, CONTENDO MAIS DE 5 CARACTERES';
                $inconsistencyModel->identifier = '10';
                $inconsistencyModel->idClass = $idClassroom;
                $inconsistencyModel->idSchool = $inepId;
                $inconsistencyModel->insert();
            }

            if (strlen($classType->getDescricao()) > $strMaxLength) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'TURMA';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'DESCRIÇÃO PARA A TURMA COM MAIS DE 50 CARACTERES';
                $inconsistencyModel->action = 'ADICIONE UMA DESCRIÇÃO MENOS DETALHADA, CONTENDO ATÉ 50 CARACTERES';
                $inconsistencyModel->identifier = '10';
                $inconsistencyModel->idClass = $idClassroom;
                $inconsistencyModel->idSchool = $inepId;
                $inconsistencyModel->insert();
            }

            if (!in_array($classType->getTurno(), [1, 2, 3, 4])) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'TURMA';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'VALOR INVÁLIDO PARA O TURNO DA TURMA';
                $inconsistencyModel->action = 'SELECIONE UM TURNO VÁLIDO PARA O HORÁRIO DE FUNCIONAMENTO';
                $inconsistencyModel->identifier = '10';
                $inconsistencyModel->idClass = $idClassroom;
                $inconsistencyModel->idSchool = $inepId;
                $inconsistencyModel->insert();
            }

            if (!is_bool($classType->getFinalTurma())) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'TURMA';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'VALOR INVÁLIDO PARA O FINAL TURMA';
                $inconsistencyModel->action = 'SELECIONE UM VALOR VÁLIDO PARA O ENCERRAMENTO DO PERÍODO';
                $inconsistencyModel->identifier = '10';
                $inconsistencyModel->idClass = $idClassroom;
                $inconsistencyModel->idSchool = $inepId;
                $inconsistencyModel->insert();
            }
        }

        return $classList;
    }

    /**
     * Summary of SerieTType
     * @return SerieTType[]
     */
    public function getSeries($classId)
    {
        $seriesList = [];
        $strlen = 2;
        $strMaxLength = 50;

        $query = "SELECT
                    c.name AS serieDescription,
                    c.modality AS serieModality
                FROM
                    classroom c
                WHERE
                    c.id = :id;";

        $series = Yii::app()->db->createCommand($query)->bindValue(":id", $classId)->queryAll();

        foreach ($series as $serie) {
            $serieType = new SerieTType();
            $serieType
                ->setDescricao($serie['serieDescription'])
                ->setModalidade($serie['serieModality']);

            if (empty($serieType)) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'SÉRIE';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'NÃO HÁ SÉRIE PARA A ESCOLA: ';
                $inconsistencyModel->action = 'ADICIONE UMA SÉRIE PARA A TURMA: ';
                $inconsistencyModel->identifier = '10';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->insert();
            }

            if (strlen($serieType->getDescricao()) <= $strlen) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'SÉRIE';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'DESCRIÇÃO PARA A SÉRIE: ' . $serieType->getDescricao() . ' MENOR QUE 3 CARACTERES';
                $inconsistencyModel->action = 'FORNEÇA UMA DESCRIÇÃO MAIS DETALHADA, CONTENDO MAIS DE 5 CARACTERES';
                $inconsistencyModel->identifier = '10';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->insert();
            }

                if (strlen($serieType->getDescricao()) > $strMaxLength) {
                    $inconsistencyModel = new ValidationSagresModel();
                    $inconsistencyModel->enrollment = 'SÉRIE';
                    $inconsistencyModel->school = '';
                    $inconsistencyModel->description = 'DESCRIÇÃO PARA A SÉRIE: ' . $serieType->getDescricao() . ' COM MAIS DE 50 CARACTERES';
                    $inconsistencyModel->action = 'FORNEÇA UMA DESCRIÇÃO MENOS DETALHADA, CONTENDO ATÉ 50 CARACTERES';
                    $inconsistencyModel->identifier = '10';
                    $inconsistencyModel->idClass = $classId;
                    $inconsistencyModel->insert();
                }
    
                /*
                 * 1 - Educação Infantil
                 * 2 - Ensino Fundamental
                 * 3 - Ensino Médio
                 * 4 - Educação de Jovens e Adultos
                 * 5 - Atendimento Educacional Especializado
                 */
                if (!in_array($serieType->getModalidade(), [1, 2, 3, 4, 5])) {
                    $inconsistencyModel = new ValidationSagresModel();
                    $inconsistencyModel->enrollment = 'SÉRIE';
                    $inconsistencyModel->school = '';
                    $inconsistencyModel->description = 'MODALIDADE INVÁLIDA';
                    $inconsistencyModel->action = 'SELECIONE UMA MODALIDADE VÁLIDA PARA A SÉRIE';
                    $inconsistencyModel->identifier = '10';
                    $inconsistencyModel->idClass = $classId;
                    $inconsistencyModel->insert();
                }

            $seriesList[] = $serieType;
        }

        return $seriesList;
    }

    /**
     * Summary of SerieTType
     * @return HorarioTType[]
     */
    public function getSchedules($classId, $month)
    {
        $scheduleList = [];
        $strlen = 3;
        $maxLength = 100;

        $query = "SELECT DISTINCT
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
                    c.create_date DESC";

        $params = [
            ':classId' => $classId,
            ':referenceMonth' => $month
        ];


        $schedules = Yii::app()->db->createCommand($query)->bindValues($params)->queryAll();

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

            $scheduleType
                ->setDiaSemana(((int) $schedule['weekDay']) + 1)
                ->setDuracao(2)
                ->setHoraInicio($this->getStartTime($schedule['schedule'], $this->convertTurn($schedule['turn'])))
                ->setDisciplina(substr($schedule['disciplineName'], 0, 50))
                ->setCpfProfessor([str_replace([".", "-"], "", $schedule['cpfInstructor'])]);

            if (empty($scheduleType)) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'HORÁRIO';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'NÃO HÁ UM PROFESSOR, HORÁRIOS OU COMPONETES CURRICULARES PARA A TURMA: ';
                $inconsistencyModel->action = 'ADICIONE UM PROFESSOR OU COMPONENTES CURRICULARES À TURMA';
                $inconsistencyModel->identifier = '10';
                $inconsistencyModel->idClass = $classId;
                #$inconsistencyModel->idSchool = $inepId;
                $inconsistencyModel->insert();
            }

            if (!in_array($scheduleType->getDiaSemana(), [1, 2, 3, 4, 5, 6, 7])) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'HORÁRIO';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'DIA DA SEMANA INVÁLIDO: ' . $scheduleType->getDiaSemana();
                $inconsistencyModel->action = 'ADICIONE UM DIA DA SEMANA VÁLIDO PARA A DISCIPLINA';
                $inconsistencyModel->identifier = '10';
                $inconsistencyModel->idClass = $classId;
                #$inconsistencyModel->idSchool = $inepId;
                $inconsistencyModel->insert();
            }

            if (!is_int($scheduleType->getDuracao())) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'HORÁRIO';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'DURAÇÃO INVÁLIDA';
                $inconsistencyModel->action = 'ADICIONE UM DIA DA SEMANA VÁLIDO PARA A DISCIPLINA';
                $inconsistencyModel->identifier = '10';
                $inconsistencyModel->idClass = $classId;
                #$inconsistencyModel->idSchool = $inepId;
                $inconsistencyModel->insert();
            }

            $cpfInstructor = $scheduleType->getCpfProfessor();
            if (!$this->validaCPF($cpfInstructor[0])) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'HORÁRIO';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'CPF DO PROFESSOR É INVÁLIDO, VINCULADO A TURMA: ';
                $inconsistencyModel->action = 'INFORMAR UM CPF VÁLIDO PARA O PROFESSOR';
                $inconsistencyModel->identifier = '10';
                $inconsistencyModel->idClass = $classId;
                #$inconsistencyModel->idSchool = $inepId;
                $inconsistencyModel->insert();
            }

            if (strlen($scheduleType->getDisciplina()) < $strlen) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'HORÁRIO';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'NOME DA DISCIPLINA MUITO CURTA';
                $inconsistencyModel->action = 'ADICIONE UM NOME PARA A DISCIPLINA COM PELO MENOS 5 CARACTERES';
                $inconsistencyModel->identifier = '10';
                $inconsistencyModel->idClass = $classId;
                #$inconsistencyModel->idSchool = $inepId;
                $inconsistencyModel->insert();
            }

            if (strlen($scheduleType->getDisciplina()) > $maxLength) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'HORÁRIO';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'NOME DA DISCIPLINA COM MAIS DE 50 CARACTERES - ' . $scheduleType->getDisciplina();
                $inconsistencyModel->action = 'ADICIONE UM NOME PARA A DISCIPLINA COM ATÉ 50 CARACTERES';
                $inconsistencyModel->identifier = '10';
                $inconsistencyModel->idClass = $classId;
                #$inconsistencyModel->idSchool = $inepId;
                $inconsistencyModel->insert();
            }

            $scheduleList[] = $scheduleType;

        }

        return $scheduleList;
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

        $query = "SELECT 
                    lm.date AS data,
                    lm.turn AS turno,
                    lm2.restrictions  AS descricaoMerenda,
                    lm.adjusted AS ajustado
                FROM lunch_menu lm
                    JOIN lunch_menu_meal lmm ON lm.id = lmm.menu_fk
                    JOIN lunch_meal lm2 on lmm.meal_fk = lm2.id
                WHERE lm.school_fk =  :schoolId AND YEAR(lm.date) = :year AND MONTH(lm.date) <= :month";

        $params = [
            ':schoolId' => $schoolId,
            ':year' => $year,
            ':month' => $month
        ];

        $menus = Yii::app()->db->createCommand($query)->bindValues($params)->queryAll();

        foreach ($menus as $menu) {
            $menuType = new CardapioTType();
            $menuType
                ->setData(new DateTime($menu['data']))
                ->setTurno($this->convertTurn($menu['turno']))
                ->setDescricaoMerenda(str_replace("ª", "", $menu['descricaoMerenda']))
                ->setAjustado(isset($menu['ajustado']) ? $menu['ajustado'] : false);

            $menuList[] = $menuType;

            $sql = "SELECT name FROM school_identification WHERE inep_id = :inepId";
            $params = array(':inepId' => $schoolId);
            $schoolRes = Yii::app()->db->createCommand($sql)->bindValues($params)->queryRow();

            if (!in_array($menuType->getTurno(), [1, 2, 3, 4])) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'CARDÁPIO';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'TURNO INVÁLIDO';
                $inconsistencyModel->action = 'INFORMAR UM TURNO VÁLIDO PARA O TURNO';
                $inconsistencyModel->idSchool = $schoolId;
                $inconsistencyModel->insert();
            }

            if (strlen($menuType->getDescricaoMerenda()) <= $strlen) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'CARDÁPIO';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'DESCRIÇÃO PARA MERENDA MENOR QUE 5 CARACTERES';
                $inconsistencyModel->action = 'INFORMAR UMA DESCRIÇÃO PARA MERENDA MAIOR QUE 4 CARACTERES';
                $inconsistencyModel->idSchool = $schoolId;
                $inconsistencyModel->insert();
            }

            if (strlen($menuType->getDescricaoMerenda()) > $maxLen) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'CARDÁPIO';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'DESCRIÇÃO PARA A MERENDA MAIOR QUE 1000 CARACTERES';
                $inconsistencyModel->action = 'INFORMAR UMA DESCRIÇÃO PARA A MERENDA MENOR QUE 1000 CARACTERES';
                $inconsistencyModel->idSchool = $schoolId;
                $inconsistencyModel->insert();
            }

            if (!in_array($menuType->getAjustado(), [0, 1])) { # 0: Not, 1: True
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'CARDÁPIO';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'VALOR INVÁLIDO PARA O CAMPO AJUSTADO';
                $inconsistencyModel->action = 'MARQUE OU DESMARQUE O CHECKBOX PARA O CAMPO AJUSTADO';
                $inconsistencyModel->idSchool = $schoolId;
                $inconsistencyModel->insert();
            }

            if (!$this->validateDate($menuType->getData())) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'CARDÁPIO';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'DATA NO FORMATO INVÁLIDO';
                $inconsistencyModel->action = 'ADICIONE UMA DATA NO FORMATO VÁLIDA';
                $inconsistencyModel->idSchool = $schoolId;
                $inconsistencyModel->insert();
            }
        }

        return $menuList;
    }

    public function getDirectorSchool($idSchool): DiretorTType
    {

        $query = "SELECT
                    cpf AS cpfDiretor,
                    number_ato AS nrAto
                FROM
                    manager_identification
                WHERE
                    school_inep_id_fk = :idSchool;";

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
        $query = "SELECT DISTINCT
                    p.id_professional AS id_professional,
                    p.cpf_professional  AS cpfProfissional,
                    p.speciality  AS especialidade,
                    p.inep_id_fk AS idEscola,
                    fundeb
                FROM professional p
                    JOIN attendance a ON p.id_professional  = a.professional_fk  and MONTH(a.date) <= :currentMonth
                WHERE
                    YEAR(a.date) = :reference_year";

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
                ->setCpfProfissional(str_replace([".", "-"], "", $professional['cpfProfissional']))
                ->setEspecialidade($professional['especialidade'])
                ->setIdEscola($professional['idEscola'])
                ->setFundeb($professional['fundeb'])
                ->setAtendimento($this->getAttendances($professional['id_professional'], $month));

            $professionalList[] = $professionalType;

            $sql = "SELECT name FROM school_identification WHERE inep_id = :inepId";
            $params = array(':inepId' => $professional['idEscola']);
            $schoolRes = Yii::app()->db->createCommand($sql)->bindValues($params)->queryRow();

            if (!$this->validaCPF($professionalType->getCpfProfissional())) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'PROFISSIONAL';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'CPF INVÁLIDO: ' . $professional['cpfProfissional'];
                $inconsistencyModel->action = 'INFORMAR UM CPF VÁLIDO';
                $inconsistencyModel->identifier = '2';
                $inconsistencyModel->idProfessional = $professional['id_professional'];
                $inconsistencyModel->idSchool = $professional['idEscola'];
                $inconsistencyModel->insert();
            }

            if (strlen($professionalType->getEspecialidade()) > $strMaxLength) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'PROFISSIONAL';
                $inconsistencyModel->school = $schoolRes['name'];
                $inconsistencyModel->description = 'ESPECIALIDADE COM MAIS DE 50 CARACTERES';
                $inconsistencyModel->action = 'INFORMAR UMA DESCRIÇÃO PARA A ESPECIALIDADE COM ATÉ 50 CARACTERES';
                $inconsistencyModel->identifier = '2';
                $inconsistencyModel->idProfessional = $professional['id_professional'];
                $inconsistencyModel->idSchool = $professional['idEscola'];
                $inconsistencyModel->insert();

            }
        }

        return $professionalList;
    }

    public function getAttendances($professionalId, $month)
    {
        $attendanceList = [];

        $query = "SELECT
                    date AS attendanceDate,
                    local AS attendanceLocation
                FROM
                    attendance
                WHERE
                    professional_fk = :professionalId
                    and MONTH(`date`) = " . $month . ";";

        $attendances = Yii::app()->db->createCommand($query)->bindValue(":professionalId", $professionalId)->queryAll();

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
     * @return MatriculaTType[]
     */
    public function getEnrollments($classId, $referenceYear, $month, $finalClass)
    {
        $enrollmentList = [];
        $strMaxLength = 200;
        $strlen = 5;

        $query = "SELECT
                        se.id as numero,
                        se.student_fk,
                        se.create_date AS data_matricula,
                        se.date_cancellation_enrollment AS data_cancelamento,
                        se.status AS situation,
                        si.responsable_cpf AS cpfStudent,
                        si.birthday AS birthdate,
                        si.name AS name,
                        ifnull(si.deficiency, 0) AS deficiency,
                        si.sex AS gender,
                        si.id,
                        SUM(IF(cf.id is null, 0, 1)) AS faults
                  FROM
                        student_enrollment se
                        join classroom c on se.classroom_fk = c.id
                        join student_identification si on si.id = se.student_fk
                        left join class_faults cf on cf.student_fk = si.id
                        left join schedule s on cf.schedule_fk = s.id
                  WHERE
                        se.classroom_fk  =  :classId AND
                        c.school_year = :referenceYear
                  GROUP BY se.id;
                ";

        $command = Yii::app()->db->createCommand($query);
        $command->bindValues([
            ':classId' => $classId,
            ':referenceYear' => $referenceYear,
        ]);

        $enrollments = $command->queryAll();

        foreach ($enrollments as $enrollment) {

            if (DateTime::createFromFormat("d/m/Y", $enrollment['birthdate']) === false) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'ESTUDANTE';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'DATA DE NASCIMENTO INVÁLIDA';
                $inconsistencyModel->action = 'ALTERE O FORMATO DE DATA PARA DD/MM/AAAA';
                $inconsistencyModel->identifier = '9';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->insert();
                continue;
            }


            $query1 = "SELECT cpf from student_documents_and_address WHERE id = :idStudent";
            $command = Yii::app()->db->createCommand($query1);
            $command->bindValues([':idStudent' => $enrollment['id']]);
            $cpf = $command->queryScalar();

            $studentType = new AlunoTType();
            $studentType
                ->setNome($enrollment['name'])
                ->setDataNascimento(DateTime::createFromFormat("d/m/Y", $enrollment['birthdate']))
                ->setCpfAluno(!empty($cpf) ? $cpf : null)
                ->setPcd($enrollment['deficiency'])
                ->setSexo($enrollment['gender']);


            if (!is_null($studentType->getCpfAluno())) {
                if (!$this->validaCPF($studentType->getCpfAluno())) {
                    $inconsistencyModel = new ValidationSagresModel();
                    $inconsistencyModel->enrollment = 'ESTUDANTE';
                    $inconsistencyModel->school = '';
                    $inconsistencyModel->description = 'CPF DO ESTUDANTE É INVÁLIDO';
                    $inconsistencyModel->action = 'INFORME UM CPF VÁLIDO PARA O ESTUDANTE: ' . $enrollment['name'];
                    $inconsistencyModel->identifier = '9';
                    $inconsistencyModel->idClass = $classId;
                    $inconsistencyModel->insert();
                }
            }

            if (is_null($studentType->getCpfAluno())) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'ESTUDANTE';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'É OBRIGATÓRIO INFORMAR O CPF DO ESTUDANTE';
                $inconsistencyModel->action = 'INFORME UM CPF PARA O ESTUDANTE: ' . $enrollment['name'];
                $inconsistencyModel->identifier = '9';
                $inconsistencyModel->idStudent = $enrollment['student_fk'];
                $inconsistencyModel->insert();
            }

            if (!$this->validateDate($studentType->getDataNascimento())) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'ESTUDANTE';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'DATA NO FORMATO INVÁLIDO: ' . $enrollment['birthdate'];
                $inconsistencyModel->action = 'ADICIONE UMA DATA NO FORMATO VÁLIDA';
                $inconsistencyModel->identifier = '9';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->insert();
            }


            if (strlen($studentType->getNome()) < $strlen) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'ESTUDANTE';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'NOME DO ESTUDANTE COM MENOS DE 5 CARACTERES';
                $inconsistencyModel->action = 'ADICIONE UM NOME PARA O ESTUDANTE COM PELO MENOS 5 CARACTERES';
                $inconsistencyModel->identifier = '9';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->insert();
            }

            if ($studentType->getNome() > $strMaxLength) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'ESTUDANTE';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'NOME DO ESTUDANTE COM MAIS DE 200 CARACTERES';
                $inconsistencyModel->action = 'ADICIONE UM NOME PARA O ESTUDANTE COM ATÉ 200 CARACTERES';
                $inconsistencyModel->identifier = '9';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->insert();
            }

            if (!is_bool(boolval($studentType->getPcd()))) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'ESTUDANTE';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'CÓDIGO PCD É INVÁLIDO';
                $inconsistencyModel->action = 'ADICIONE UM VALOR VÁLIDO PARA O PCD';
                $inconsistencyModel->identifier = '9';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->insert();
            }

            if ($studentType->getDataNascimento() === false) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'ESTUDANTE';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'DATA DE NASCIMENTO INVÁLIDA';
                $inconsistencyModel->action = 'ALTERE O FORMATO DE DATA PARA DD/MM/AAAA';
                $inconsistencyModel->identifier = '9';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->insert();
            }

            if (!in_array($studentType->getSexo(), [1, 2, 3])) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'ESTUDANTE';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'SEXO NÃO É VÁLIDO';
                $inconsistencyModel->action = 'ADICIONE UM SEXO VÁLIDO PARA O ESTUDANTE';
                $inconsistencyModel->identifier = '9';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->insert();
            }




            $enrollmentType = new MatriculaTType();
            $enrollmentType
                ->setNumero($enrollment['numero'])
                ->setDataMatricula(new DateTime($enrollment['data_matricula']))
                // ->setDataCancelamento(new DateTime($enrollment['data_cancelamento']))
                ->setNumeroFaltas((int) $enrollment['faults'])
                ->setAluno($studentType);

            if (is_null($studentType)) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'ESTUDANTE';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'ESTUDANTE NÃO EXISTE PARA A MATRÍCULA DA TURMA: ';
                $inconsistencyModel->action = 'ADICIONE UM ESTUDANTE À TURMA DA ESCOLA';
                $inconsistencyModel->identifier = '9';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->insert();
            }

            if (filter_var($finalClass, FILTER_VALIDATE_BOOLEAN)) {
                $enrollmentType->setAprovado($this->getStudentSituation($enrollment['situation']));
            }

            if (empty($enrollmentType)) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'MATRÍCULA';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'NÃO HÁ MATRÍCULA PARA A TURMA';
                $inconsistencyModel->action = 'ADICIONE UMA MATRÍCULA PARA A TURMA';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->insert();
            }

            if (!$this->validateDate($enrollmentType->getDataMatricula())) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'MATRÍCULA';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'DATA NO FORMATO INVÁLIDO';
                $inconsistencyModel->action = 'ADICIONE UMA DATA NO FORMATO VÁLIDO';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->insert();
            }

            if (!is_int($enrollmentType->getNumeroFaltas())) {
                $inconsistencyModel = new ValidationSagresModel();
                $inconsistencyModel->enrollment = 'MATRÍCULA';
                $inconsistencyModel->school = '';
                $inconsistencyModel->description = 'O VALOR PARA O NÚMERO DE FALTAS É INVÁLIDO';
                $inconsistencyModel->action = 'COLOQUE UM VALOR VÁLIDO PARA O NÚMERO DE FALTAS';
                $inconsistencyModel->idClass = $classId;
                $inconsistencyModel->insert();
            }
            if (filter_var($finalClass, FILTER_VALIDATE_BOOLEAN)) {
                if (!is_bool($enrollmentType->getAprovado())) {
                    $inconsistencyModel = new ValidationSagresModel();
                    $inconsistencyModel->enrollment = 'MATRÍCULA';
                    $inconsistencyModel->school = '';
                    $inconsistencyModel->description = 'VALOR INVÁLIDO PARA O STATUS APROVADO';
                    $inconsistencyModel->action = 'ADICIONE UM VALOR VÁLIDO PARA O CAMPO APROVADO DO ALUNO: ' . $studentType->getNome();
                    $inconsistencyModel->idClass = $classId;
                    $inconsistencyModel->insert();
                }
            }

            $enrollmentList[] = $enrollmentType;
        }

        return $enrollmentList;
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
    }

    public function returnNumberFaults($studentId, $referenceYear)
    {
        $sql = "SELECT
                    COUNT(*)
                FROM
                    class_faults cf
                    JOIN schedule s ON s.id = cf.schedule_fk
                    JOIN classroom c on c.id = s.classroom_fk
                WHERE
                    cf.student_fk = :studentId AND
                    c.school_year = :referenceYear;";

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

        return $serializer->serialize($sagresEduObject, 'xml'); // serialize the Object and return SagresEdu XML

    }

    public function actionExportSagresXML($xml)
    {
        $fileName = "Educacao.xml";

        $inst = "File_" . INSTANCE . "/";
        $path = "./app/export/SagresEdu/" . $inst;

        if(!file_exists($path)){
            mkdir($path);
        }

        $fileDir = "./app/export/SagresEdu/". $inst . $fileName;
        Yii::import('ext.FileManager.fileManager');
        $fm = new fileManager();
        $result = $fm->write($fileDir, $xml);
        if ($result === false) {
            throw new ErrorException("Ocorreu um erro ao exportar o arquivo XML.");
        }
        $content = file_get_contents($fileDir);
        $zipName = './app/export/SagresEdu/' . $inst . 'Educacao.zip';
        $tempArchiveZip = new ZipArchive;
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
        $turnos = array(
            'M' => 1,
            'T' => 2,
            'N' => 3,
            'I' => 4,
        );

        if (isset($turnos[$turn])) {
            return $turnos[$turn];
        } else {
            return 0;
        }
    }

    public function validateDate($date, $format = 'Y-m-d')
    {

        if ($date instanceof Datetime) {
            $dat = $date->format('Y-m-d');
        } else {
            $dt = new DateTime($date);
            $dat = $dt->format('Y-m-d');
        }

        $d = DateTime::createFromFormat($format, $dat);
        if (intval($d->format('Y')) <= 1900)
            return false;

        return $d && $d->format($format) == $dat;
    }

    public function validaCPF($cpf)
    {
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }
}
