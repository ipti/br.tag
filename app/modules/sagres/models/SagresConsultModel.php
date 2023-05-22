<?php

namespace SagresEdu;

use Datetime;

use ErrorException;
use Exception;

use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\SerializerBuilder;

use PDO;
use PDOException;

use Symfony\Component\Validator\Validation;

use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\BaseTypesHandler;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\XmlSchemaDateHandler;

use ValidationSagresModel;
use SagresEdu\SagresValidations;

use Yii;

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

    public function getSagresEdu($referenceYear, $dateStart, $dateEnd, $finalClass): EducacaoTType
    {
        $education = new EducacaoTType;
        $managementUnitId = $this->getManagementId();
        $validationSagres = new \SagresValidations;

        try {
            $education
                ->setPrestacaoContas($this->getManagementUnit($managementUnitId, $referenceYear, $dateStart, $dateEnd))
                ->setEscola($this->getSchools($referenceYear, $dateStart, $dateEnd, $finalClass))
                ->setProfissional($this->getProfessionals($referenceYear, $dateStart, $dateEnd));
        } catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }

        $inconsistencyList = $validationSagres->validator($education->getEscola(), $education->getProfissional());
        $inconsistencyModel = new ValidationSagresModel;

        foreach ($inconsistencyList as $value) {
            $inconsistencyModel = new ValidationSagresModel;

            $inconsistencyModel->enrollment = $value["enrollment"];
            $inconsistencyModel->school =  $value["school"] ." - ".$this->getNameSchool($value["school"]);
            $inconsistencyModel->description = $value["description"];
            $inconsistencyModel->action = $value["action"];
            $inconsistencyModel->save();
        }

        return $education;
    }


    public function getManagementUnit($managementUnitId, $referenceYear, $dateStart, $dateEnd): CabecalhoTType
    {
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

            $headerType = new CabecalhoTType;

            $headerType
                ->setCodigoUnidGestora($managementUnit['managementUnitCode'])
                ->setNomeUnidGestora($managementUnit['managementUnitName'])
                ->setCpfResponsavel(str_replace([".", "-"], "", $managementUnit['responsibleCpf']))
                ->setCpfGestor(str_replace([".", "-"], "", $managementUnit['managerCpf']))
                ->setAnoReferencia((int) $referenceYear)
                ->setMesReferencia((int) date("m", strtotime($dateEnd)))
                ->setVersaoXml(1)
                ->setDiaInicPresContas((int) date("d", strtotime($dateStart)))
                ->setDiaFinaPresContas((int) date("d", strtotime($dateEnd)));

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
            $managementUnitCode = Yii::app()->db->createCommand($query)->queryRow(PDO::PARAM_INT);
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
    public function getSchools($referenceYear, $dateStart, $dateEnd, $finalClass)
    {
        $schoolList = [];

        $query = "SELECT inep_id FROM school_identification";
        $schools = Yii::app()->db->createCommand($query)->queryAll();
        $validationSagres = new \SagresValidations;

        foreach ($schools as $school) {
            $schoolType = new EscolaTType;
            $schoolType
                ->setIdEscola($school['inep_id'])
                ->setTurma($this->getClasses($school['inep_id'], $referenceYear, $dateStart, $dateEnd, $finalClass))
                ->setDiretor($this->getDirectorSchool($school['inep_id']))
                ->setCardapio($this->getMenuList($school['inep_id'], $referenceYear, $dateStart, $dateEnd));

            // Verifica se a escola tem turmas no período
            if (!empty($schoolType->getTurma())) {
                // Verifica se a escola tem cardápio disponível no período
                if (!empty($schoolType->getCardapio())) {
                    $schoolList[] = $schoolType;
                } else {
                    // Busca o cardápio mais rescente para o período
                    $schoolType->setCardapio($this->getSchoolMenu($school['inep_id'], $referenceYear));
                    // Verifica se o cardápio foi encontrado
                    if (!empty($schoolType->getCardapio())) {
                        $schoolList[] = $schoolType;
                    }
                }
            } else { //Não tem turma no periodo mas tem cardapio   
                if (!empty($schoolType->getCardapio())) {
                    $schoolList[] = $schoolType;
                }
            }

        }
       
        return $schoolList;
    }

    public function getInconsistenciesCount(){
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
    public function getClasses($inepId, $referenceYear, $dateStart, $dateEnd, $finalClass)
    {
        $classList = [];
        $referenceMonth = (int) date("m", strtotime($dateStart));

        $query = "SELECT 
                    Date(COALESCE(c.create_date, (SELECT date FROM log WHERE reference_ids = c.id AND crud = 'C' AND reference = 'classroom' order by reference_ids limit 1))) AS createDate,
                    c.initial_hour AS initialHour,
                    c.school_inep_fk AS schoolInepFk,
                    c.id AS classroomId,
                    c.name AS classroomName,
                    c.turn AS classroomTurn
                FROM 
                    classroom c
                WHERE 
                    c.school_inep_fk = :schoolInepFk AND 
                    c.school_year = :referenceYear AND 
                    (c.create_date IS NULL OR Date(c.create_date) BETWEEN :dateStart AND :dateEnd)";

        $params = [
            ':schoolInepFk' => $inepId,
            ':referenceYear' => $referenceYear,
            ':dateStart' => $dateStart,
            ':dateEnd' => $dateEnd
        ];

        $turmas = $this->dbCommand->setText($query)
            ->bindValues($params)
            ->queryAll();

        foreach ($turmas as $turma) {
            $classType = new TurmaTType;
            $classId = $turma['classroomId'];

            $classType
                ->setPeriodo(0) //0 - Anual
                ->setDescricao($turma["classroomName"])
                ->setTurno($this->convertTurn($turma['classroomTurn']))
                ->setSerie($this->getSeries($classId))
                ->setMatricula(
                    empty($this->getEnrollments($classId, $referenceYear, $dateStart, $dateEnd, $finalClass))
                    ? $this->getRecentEnrollments($classId)
                    : $this->getEnrollments($classId, $referenceYear, $dateStart, $dateEnd, $finalClass)
                )
                ->setHorario(
                    empty($this->getSchedules($classId, $referenceMonth))
                    ? $this->getRecentSchedules($classId)
                    : $this->getSchedules($classId, $referenceMonth)
                )
                ->setFinalTurma((bool)$finalClass);
           
            if (!is_null($classType->getHorario()) && !is_null($classType->getMatricula())) {
                $classList[] = $classType;
            }
        }

        return $classList;
    }

    /**
     * Sets a new MatriculaTType
     *
     * @return MatriculaTType[]
     */
    public function getRecentEnrollments($classId)
    {
        $enrollmentList = [];

        $query = "SELECT
                        se.id as numero, 
                        se.student_fk,
                        se.create_date AS data_matricula, 
                        se.date_cancellation_enrollment AS data_cancelamento,
                        se.status AS situation
                FROM 
                        student_enrollment se 
                WHERE 
                        se.classroom_fk  =  :classId 
                ORDER BY 
                        se.create_date DESC";

        $command = Yii::app()->db->createCommand($query);
        $command->bindValues([
            ':classId' => $classId
        ]);

        $enrollments = $command->queryAll();

        foreach ($enrollments as $enrollment) {
            $enrollmentType = new MatriculaTType;
            $enrollmentType
                ->setNumero($enrollment['numero'])
                ->setDataMatricula(new DateTime($enrollment['data_matricula']))
                ->setDataCancelamento(new DateTime($enrollment['data_cancelamento']))
                ->setAprovado($this->getStudentSituation($enrollment['situation']))
                ->setAluno($this->getStudents($enrollment['student_fk']));

            $enrollmentList[] = $enrollmentType;
        }

        return $enrollmentList;
    }


    /**
     * Summary of SerieTType
     * @return HorarioTType[] 
     */
    public function getRecentSchedules($classId)
    {
        $scheduleList = [];

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
                    c.id = :classId
                ORDER BY 
                    c.create_date DESC";

        $params = [
            ':classId' => $classId
        ];


        $schedules = Yii::app()->db->createCommand($query)->bindValues($params)->queryAll();

        foreach ($schedules as $schedule) {
            $scheduleType = new HorarioTType;

            $query1 = "SELECT distinct 
                        ed.name AS disciplineName, ed.id as discipline_fk, cm.credits AS credits
                    FROM schedule s 
                        join classroom c on s.classroom_fk = s.classroom_fk
                        join edcenso_discipline ed on ed.id  = s.discipline_fk 
                        join curricular_matrix cm on cm.stage_fk = c.edcenso_stage_vs_modality_fk and s.discipline_fk = cm.discipline_fk 
                    where c.id = " . $classId . " and ed.name = '" . $schedule['disciplineName'] . "'";

            $duration = Yii::app()->db->createCommand($query1)->queryRow();

            $cpf_instructor = isset($schedule['cpfInstructor']) ? $schedule['cpfInstructor'] : "";

            $scheduleType
                ->setDiaSemana($schedule['weekDay'])
                ->setHoraInicio($this->getStartTime($schedule['schedule'], $this->convertTurn($schedule['turn'])))
                ->setDuracao((int) isset($duration['duration']) ? $duration['duration'] : 2)
                ->setDisciplina($schedule['disciplineName'])
                ->setCpfProfessor([str_replace([".", "-"], "", $cpf_instructor)]);

            if (isset($cpf_instructor)) {
                $scheduleList[] = $scheduleType;
            }
        }

        return $scheduleList;
    }

    /**
     * Summary of SerieTType
     * @return SerieTType[] 
     */
    public function getSeries($classId)
    {
        $seriesList = [];

        $query = "SELECT 
                    c.name AS serieDescription, 
                    c.modality AS serieModality
                FROM 
                    classroom c
                WHERE 
                    c.id = :id;";

        $series = Yii::app()->db->createCommand($query)->bindValue(":id", $classId)->queryAll();

        foreach ($series as $serie) {
            $serieType = new SerieTType;
            $serieType
                ->setDescricao($serie['serieDescription'])
                ->setModalidade($serie['serieModality']);

            $seriesList[] = $serieType;
        }

        return $seriesList;
    }

    /**
     * Summary of SerieTType
     * @return HorarioTType[] 
     */
    public function getSchedules($classId, $referenceMonth)
    {
        $scheduleList = [];

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
                    s.month = :referenceMonth
                GROUP BY 
                    week_day";

        $params = [
            ':classId' => $classId,
            ':referenceMonth' => $referenceMonth
        ];


        $schedules = Yii::app()->db->createCommand($query)->bindValues($params)->queryAll();

        foreach ($schedules as $schedule) {
            $scheduleType = new HorarioTType;

            $query1 = "SELECT 
                            ROUND( (t.credits / COUNT(*))) AS duration
                        FROM (
                            SELECT ed.name AS disciplineName, cm.credits AS credits
                                FROM schedule s 
                                JOIN edcenso_discipline ed ON ed.id = s.discipline_fk 
                                JOIN classroom c ON c.id = s.classroom_fk 
                                JOIN curricular_matrix cm ON cm.discipline_fk = ed.id 
                            WHERE s.classroom_fk = $classId and s.month = $referenceMonth
                            GROUP BY s.week_day
                        ) t
                        WHERE t.disciplineName = '" . $schedule['disciplineName'] . "'";

            $duration = Yii::app()->db->createCommand($query1)->queryRow();

            $scheduleType
                ->setDiaSemana($schedule['weekDay'])
                ->setDuracao(2)
                ->setHoraInicio($this->getStartTime($schedule['schedule'], $this->convertTurn($schedule['turn'])))
                ->setDisciplina($schedule['disciplineName'])
                ->setCpfProfessor([$schedule['cpfInstructor']]);

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

    function getDateTimeFromInitialHour($initialHour)
    {
        $timeFormatted = date('H:i:s', strtotime($initialHour . ':00:00'));
        return new DateTime($timeFormatted);
    }

    /**
     * Summary of EscolaTType
     * @return AtendimentoTType[] 
     */
    public function getAttendances($professionalId)
    {
        $attendanceList = [];

        $query = "SELECT
                    date AS attendanceDate,
                    local AS attendanceLocation
                FROM 
                    attendance
                WHERE 
                    professional_fk = :professionalId;";

        $attendances = Yii::app()->db->createCommand($query)->bindValue(":professionalId", $professionalId)->queryAll();

        foreach ($attendances as $attendance) {
            $attendanceType = new AtendimentoTType;
            $attendanceType
                ->setData(new DateTime($attendance['attendanceDate']))
                ->setLocal($attendance['attendanceLocation']);

            $attendanceList[] = $attendanceType;
        }

        return $attendanceList;
    }

    public function getStudents($studentFk): AlunoTType
    {
        $query = "SELECT
                    si2.responsable_cpf AS cpfStudent,
                    si2.birthday AS birthdate,
                    si2.name AS name,
                    ifnull(si2.deficiency, 0) AS deficiency,
                    si2.sex AS gender
                FROM 
                    student_identification si2
                WHERE 
                    si2.id = :studentFk";

        $student = Yii::app()->db->createCommand($query)->bindValue(':studentFk', $studentFk)->queryRow();

        $studentType = new AlunoTType;
        $studentType
            ->setNome($student['name'])
            ->setDataNascimento(DateTime::createFromFormat("d/m/Y", $student['birthdate']))
            ->setCpfAluno(!empty($student['cpfStudent']) ? $student['cpfStudent'] : null)
            ->setPcd($student['deficiency'])
            ->setSexo($student['gender']);

        return $studentType;
    }


    /**
     * Summary of CardapioTType
     * @return CardapioTType[] 
     */
    public function getMenuList($schoolId, $year, $startDate, $endDate)
    {
        $menuList = [];

        $query = "SELECT 
                    lm.date AS data,
                    lm.turn AS turno,
                    lm2.restrictions  AS descricaoMerenda, 
                    lm.adjusted AS ajustado 
                FROM lunch_menu lm 
                    JOIN lunch_menu_meal lmm ON lm.id = lmm.menu_fk   
                    JOIN lunch_meal lm2 on lmm.meal_fk = lm2.id
                WHERE lm.school_fk =  :schoolId AND YEAR(lm.date) = :year AND lm.date BETWEEN :startDate AND :endDate";

        $params = [
            ':schoolId' => $schoolId,
            ':year' => $year,
            ':startDate' => $startDate,
            'endDate' => $endDate
        ];

        $menus = Yii::app()->db->createCommand($query)->bindValues($params)->queryAll();

        foreach ($menus as $menu) {
            $menuType = new CardapioTType;
            $menuType
                ->setData(new DateTime($menu['data']))
                ->setTurno($this->convertTurn($menu['turno']))
                ->setDescricaoMerenda(str_replace("ª", "", $menu['descricaoMerenda']))
                ->setAjustado($menu['ajustado']);

            $menuList[] = $menuType;
        }

        return $menuList;
    }


    public function getSchoolMenu($schoolId, $selectedYear)
    {
        $cardapioList = [];
        $query = "SELECT 
                    lm.date AS data,
                    lm.turn AS turno,
                    lm2.restrictions  AS descricaoMerenda, 
                    lm.adjusted AS ajustado 
                FROM lunch_menu lm 
                    JOIN lunch_menu_meal lmm ON lm.id = lmm.menu_fk   
                    JOIN lunch_meal lm2 on lmm.meal_fk = lm2.id
                WHERE lm.school_fk = :schoolId and YEAR(lm.date) = :selectedYear
                GROUP BY lm.date DESC
                LIMIT 1";

        $params = [
            ':schoolId' => $schoolId,
            ':selectedYear' => $selectedYear
        ];

        $cardapios = Yii::app()->db->createCommand($query)->bindValues($params)->queryAll();

        foreach ($cardapios as $cardapio) {
            $cardapioType = new CardapioTType;
            $cardapioType
                ->setData(new DateTime($cardapio['data']))
                ->setTurno($this->convertTurn($cardapio['turno']))
                ->setDescricaoMerenda($cardapio['descricaoMerenda'])
                ->setAjustado($cardapio['ajustado']);

            $cardapioList[] = $cardapioType;
        }

        return $cardapioList;
    }

    public function getDirectorSchool($idSchool): DiretorTType
    {

        $query = "SELECT 
                    manager_cpf AS cpfDiretor, 
                    number_ato AS nrAto 
                FROM 
                    school_identification 
                WHERE 
                    inep_id = :idSchool;";

        $director = Yii::app()->db->createCommand($query)
            ->bindValue(':idSchool', $idSchool)
            ->queryRow();

        $directorType = new DiretorTType;
        $directorType
            ->setCpfDiretor($director['cpfDiretor'])
            ->setNrAto($director['nrAto']);

        return $directorType;
    }


    /**
     * Summary of ProfissionalTType
     * @return ProfissionalTType[] 
     */
    public function getProfessionals($reference_year, $dateStart, $dateEnd)
    {
        $professionalList = [];
        $query = "SELECT 
                    p.id_professional AS id_professional, 
                    p.cpf_professional  AS cpfProfissional, 
                    p.speciality  AS especialidade, 
                    p.inep_id_fk AS idEscola, 
                    fundeb 
                FROM professional p
                    JOIN attendance a ON p.id_professional  = a.professional_fk  and a.date BETWEEN :dateStart AND :dateEnd
                WHERE 
                    YEAR(a.date) = :reference_year";

        $command = Yii::app()->db->createCommand($query);
        $command->bindValues([
            ':reference_year' => $reference_year,
            ':dateStart' => $dateStart,
            ':dateEnd' => $dateEnd
        ]);

        $professionals = $command->queryAll();

        foreach ($professionals as $professional) {
            $professionalType = new ProfissionalTType;
            $professionalType
                ->setCpfProfissional($professional['cpfProfissional'])
                ->setEspecialidade($professional['especialidade'])
                ->setIdEscola($professional['idEscola'])
                ->setFundeb($professional['fundeb'])
                ->setAtendimento($this->getAttendances($professional['id_professional']));

            $professionalList[] = $professionalType;
        }

        return $professionalList;
    }

    /**
     * Sets a new MatriculaTType
     *
     * @return MatriculaTType[]
     */
    public function getEnrollments($classId, $referenceYear, $dateStart, $dateEnd, $finalClass)
    {
        $enrollmentList = [];

        $query = "SELECT 
                        se.id as numero, 
                        se.student_fk,
                        se.create_date AS data_matricula, 
                        se.date_cancellation_enrollment AS data_cancelamento,
                        se.status AS situation
                  FROM 
                        student_enrollment se
                        join classroom c on se.classroom_fk = c.id 
                  WHERE 
                        se.classroom_fk  =  :classId AND 
                        c.school_year = :referenceYear";

        $command = Yii::app()->db->createCommand($query);
        $command->bindValues([
            ':classId' => $classId,
            ':referenceYear' => $referenceYear
        ]);

        $enrollments = $command->queryAll();

        foreach ($enrollments as $enrollment) {
            $enrollmentType = new MatriculaTType;
            $enrollmentType
                ->setNumero($enrollment['numero'])
                ->setDataMatricula(new DateTime($enrollment['data_matricula']))
                // ->setDataCancelamento(new DateTime($enrollment['data_cancelamento']))
                ->setNumeroFaltas((int) $this->returnNumberFaults($enrollment['student_fk'], $referenceYear))
                ->setAluno($this->getStudents($enrollment['student_fk']));

            if($finalClass){
                $enrollmentType->setAprovado($this->getStudentSituation($enrollment['situation']));
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
        } else {
            throw new ErrorException("O valor " . $situation . " para a situação do estudante não é válido.");
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
            // $handler->registerSubscribingHandler(new YourhandlerHere());
        });
        $serializer = $serializerBuilder->build();

        return $serializer->serialize($sagresEduObject, 'xml'); // serialize the Object and return SagresEdu XML

    }

    public function actionExportSagresXML($xml)
    {
        $fileName = "Educacao.xml";
        $fileDir = "./app/export/SagresEdu/" . $fileName;

        // Limpa o conteúdo dentro de CDATA
        $linha = $this->transformXML(preg_replace("/<!\[CDATA\[(.*?)\]\]>/s", "\\1", $xml));

        // Escreve o conteúdo no arquivo
        $result = file_put_contents($fileDir, $linha);

        if ($result !== false) {
            return file_get_contents($fileDir);
        } else {
            throw new ErrorException("Ocorreu um erro ao exportar o arquivo XML.");
        }
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

    function transformXML($xml)
    {
        $year = date('Y');
        $xml = str_replace('<result>', sprintf('<edu:educacao xmlns:edu="http://www.tce.se.gov.br/sagres%s/xml/sagresEdu">', $year), $xml);
        $xml = str_replace('</result>', '</edu:educacao>', $xml);
        $xml = str_replace('<edu:prestacaoContas>', '<edu:PrestacaoContas>', $xml);
        $xml = str_replace('</edu:prestacaoContas>', '</edu:PrestacaoContas>', $xml);

        return $xml;
    }
}