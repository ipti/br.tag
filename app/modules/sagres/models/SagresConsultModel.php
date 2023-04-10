<?php

namespace SagresEdu;

use Datetime;

use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\SerializerBuilder;

use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Validation;

use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\BaseTypesHandler;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\XmlSchemaDateHandler;

use Yii;

class SagresConsultModel
{
    private $dbCommand;

    public function __construct()
    {
        $this->dbCommand = Yii::app()->db->createCommand();
    }

    public function getSagresEdu($managementUnitId, $referenceYear, $dateStart, $dateEnd): EducacaoTType
    {
        $education = new EducacaoTType;

        $education
            ->setPrestacaoContas($this->getManagementUnit($managementUnitId, $referenceYear, $dateStart, $dateEnd))
            ->setEscola($this->getSchools($referenceYear, $dateStart, $dateEnd))
            ->setProfissional($this->getProfessionals($referenceYear, $dateStart, $dateEnd));

        return $education;
    }


    public function getManagementUnit($idManagementUnit, $referenceYear, $dateStart, $dateEnd): CabecalhoTType
    {
        $query = "SELECT 
                    pa.id AS managementUnitId,
                    pa.cod_unidade_gestora AS managementUnitCode,
                    pa.name_unidade_gestora AS managementUnitName,
                    pa.cpf_responsavel AS responsibleCpf,
                    pa.cpf_gestor AS managerCpf,
                    pa.ano_referencia AS referenceYear,
                    pa.mes_referencia AS referenceMonth,
                    pa.versao_xml AS xmlVersion,
                    pa.dia_inicio_prest_contas AS startDate,
                    pa.dia_final_prest_contas AS endDate
                FROM 
                    provision_accounts pa
                WHERE 
                    pa.id = :idManagementUnit;";

        $managementUnit = Yii::app()->db->createCommand($query)
            ->bindValue(':idManagementUnit', $idManagementUnit)
            ->queryRow();

        $headerType = new CabecalhoTType;

        $headerType
            ->setCodigoUnidGestora($managementUnit['managementUnitCode'])
            ->setNomeUnidGestora($managementUnit['managementUnitName'])
            ->setCpfResponsavel($managementUnit['responsibleCpf'])
            ->setCpfGestor($managementUnit['managerCpf'])
            ->setAnoReferencia((int) $referenceYear)
            ->setMesReferencia((int) date("m", strtotime($dateEnd)))
            ->setVersaoXml((int) $managementUnit['xmlVersion'])
            ->setDiaInicPresContas((int) date("d", strtotime($dateStart)))
            ->setDiaFinaPresContas((int) date("d", strtotime($dateEnd)));

        return $headerType;
    }


    /**
     * Summary of EscolaTType
     * @return EscolaTType[] 
     */
    public function getSchools($referenceYear, $dateStart, $dateEnd)
    {
        $schoolList = [];

        $query = "SELECT inep_id FROM school_identification";
        $schools = Yii::app()->db->createCommand($query)->queryAll();

        foreach ($schools as $school) {
            $schoolType = new EscolaTType;
            $schoolType
                ->setIdEscola($school['inep_id'])
                ->setTurma($this->getClasses($school['inep_id'], $referenceYear, $dateStart, $dateEnd))
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
                // Adiciona a escola à lista de escolas
                if (!empty($schoolType->getCardapio())) {
                    $schoolList[] = $schoolType;
                }
            }
        }

        return $schoolList;
    }

    /**
     * Summary of TurmaTType
     * @return TurmaTType[]
     */
    public function getClasses($inepId, $referenceYear, $dateStart, $dateEnd)
    {
        $classList = [];
        $referenceMonth = (int) date("m", strtotime($dateStart));

        $query = "SELECT 
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
                    Date(c.create_date) BETWEEN :dateStart AND :dateEnd";

        $params = [
            ':schoolInepFk' => $inepId,
            ':referenceYear' => $referenceYear,
            ':dateStart' => $dateStart,
            ':dateEnd' => $dateEnd,
        ];

        $turmas = $this->dbCommand->setText($query)
            ->bindValues($params)
            ->queryAll();

        foreach ($turmas as $turma) {
            $classType = new TurmaTType;
            $classId = $turma['classroomId'];

            // if (!empty($this->getEnrollments($classId, $referenceYear, $dateStart, $dateEnd))) {
            $classType
                ->setPeriodo(0) //0 - Anual
                ->setDescricao($turma["classroomName"])
                ->setTurno($this->convertTurn($turma['classroomTurn']))
                ->setSerie($this->getSeries($classId))
                ->setMatricula($this->getEnrollments($classId, $referenceYear, $dateStart, $dateEnd))
                ->setHorario($this->getSchedules($classId, $referenceMonth))
                ->setFinalTurma(false);

            $classList[] = $classType;
            //}
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

        $query = "SELECT  
                    s.schedule AS schedule,
                    s.week_day AS weekDay, 
                    ed.name AS disciplineName,
                    c.turn AS turn
                FROM 
                    schedule s 
                    JOIN edcenso_discipline ed ON ed.id = s.discipline_fk 
                    JOIN classroom c ON c.id = s.classroom_fk 
                    JOIN curricular_matrix cm ON cm.discipline_fk = ed.id 
                WHERE 
                    s.classroom_fk = :classId and 
                    s.month = :referenceMonth
                GROUP BY 
                    week_day";

        $params = [
            ':classId' => 441,
            ':referenceMonth' => 1
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
                            WHERE s.classroom_fk = 444 and s.month = 1
                            GROUP BY s.week_day
                        ) t
                        WHERE t.disciplineName = '" . $schedule['disciplineName'] . "'";

            $duration = Yii::app()->db->createCommand($query1)->queryRow();

            $scheduleType
                ->setDiaSemana($schedule['weekDay'])
                ->setDuracao($duration['duration'])
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
    public function getStartTime(int $schedule, int $turn): DateTime
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
                6 => 17
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

        if ($startTime !== null) {
            return $this->getDateTimeFromInitialHour($startTime);
        } else {
            throw new InvalidArgumentException("Invalid turn or schedule.");
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
                    si2.deficiency AS deficiency,
                    si2.sex AS gender
                FROM 
                    student_identification si2
                WHERE 
                    si2.id = :studentFk";

        $student = Yii::app()->db->createCommand($query)->bindValue(':studentFk', $studentFk)->queryRow();

        $studentType = new AlunoTType;
        $studentType
            ->setNome($student['name'])
            ->setDataNascimento(new DateTime($student['birthdate']))
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
                cr.turn AS turno, 
                li.description AS descricaoMerenda, 
                lm.adjusted AS ajustado 
            FROM classroom cr 
                JOIN school_identification si ON si.inep_id = cr.school_inep_fk 
                JOIN lunch_menu lm ON lm.school_fk = si.inep_id 
                JOIN lunch_menu_meal lmm ON lm.id = lmm.menu_fk 
                JOIN lunch_meal lme ON lme.id = lmm.meal_fk 
                JOIN lunch_meal_portion lmp ON lmp.meal_fk = lme.id 
                JOIN lunch_portion lp ON lp.id = lmp.portion_fk 
                JOIN lunch_item li ON li.id = lp.item_fk
            WHERE si.inep_id = :schoolId AND YEAR(lm.date) = :year AND lm.date BETWEEN :startDate AND :endDate";

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
                ->setDescricaoMerenda($menu['descricaoMerenda'])
                ->setAjustado($menu['ajustado']);

            $menuList[] = $menuType;
        }

        return $menuList;
    }


    public function getSchoolMenu($id_escola, $year)
    {
        $cardapioList = [];
        $query = "SELECT 
                lm.date AS data, 
                cr.turn AS turno, 
                li.description AS descricaoMerenda, 
                lm.adjusted AS ajustado 
            FROM classroom cr 
                JOIN school_identification si ON si.inep_id = cr.school_inep_fk 
                JOIN lunch_menu lm ON lm.school_fk = si.inep_id 
                JOIN lunch_menu_meal lmm ON lm.id = lmm.menu_fk 
                JOIN lunch_meal lme ON lme.id = lmm.meal_fk 
                JOIN lunch_meal_portion lmp ON lmp.meal_fk = lme.id 
                JOIN lunch_portion lp ON lp.id = lmp.portion_fk 
                JOIN lunch_item li ON li.id = lp.item_fk
            WHERE si.inep_id = " . $id_escola . ". and YEAR(lm.date) = " . $year . "
            GROUP BY lm.date DESC
            LIMIT 1";

        $cardapios = Yii::app()->db->createCommand($query)->queryAll();

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
                    epec.name  AS especialidade, 
                    p.inep_id_fk AS idEscola, 
                    fundeb 
                FROM professional p
                    JOIN edcenso_professional_education_course epec ON p.speciality_fk = epec.id
                    JOIN attendance a ON p.id_professional  = a.professional_fk  
                WHERE 
                    YEAR(a.date) = :reference_year AND 
                    a.date BETWEEN :dateStart AND :dateEnd;";

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
    public function getEnrollments($enrollmentId, $referenceYear, $dateStart, $dateEnd)
    {
        $enrollmentList = [];

        $query = "SELECT 
                        se.id as numero, se.student_fk,
                        se.create_date AS data_matricula, 
                        se.date_cancellation_enrollment AS data_cancelamento,
                        se.previous_stage_situation AS situation
                  FROM 
                        student_enrollment se 
                  WHERE 
                        se.classroom_fk  =  :enrollmentId AND 
                        YEAR(se.create_date) = :referenceYear AND 
                        create_date BETWEEN :dateStart AND :dateEnd;";

        $command = Yii::app()->db->createCommand($query);
        $command->bindValues([
            ':enrollmentId' => 441,
            ':referenceYear' => $referenceYear,
            ':dateStart' => $dateStart,
            ':dateEnd' => $dateEnd
        ])->queryAll();

        $enrollments = $command->queryAll();

        foreach ($enrollments as $enrollment) {
            $enrollmentType = new MatriculaTType;
            $enrollmentType
                ->setNumero($enrollment['numero'])
                ->setDataMatricula(new DateTime($enrollment['data_matricula']))
                ->setDataCancelamento(new DateTime($enrollment['data_cancelamento']))
                ->setNumeroFaltas((int) $this->returnNumberFaults($enrollment['student_fk'], $referenceYear))
                ->setAprovado($this->getStudentSituation($enrollment['situation']))
                ->setAluno($this->getStudents($enrollment['student_fk']));

            $enrollmentList[] = $enrollmentType;
        }

        return $enrollmentList;
    }

    public function getStudentSituation($situation)
    {
        /* "0" => "Não frequentou",
        "1" => "Reprovado",
        "2" => "Afastado por transferência",
        "3" => "Afastado por abandono",
        "4" => "Matrícula final em Educação Infantil",
        "5" => "Promovido" 
        */
        if ($situation == 1)
            return false;
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
            return "Ocorreu um erro ao exportar o arquivo XML.";
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

    public function convertTurn($turn)
    {
        $turnos = array(
            'M' => 1,
            'V' => 2,
            'N' => 3,
            'I' => 4,
        );

        return isset($turnos[$turn]) ? $turnos[$turn] : 1;
    }

    function transformXML($xml)
    {
        $xml = str_replace('<result>', '<edu:educacao xmlns:edu="http://www.tce.se.gov.br/sagres2023/xml/sagresEdu">', $xml);
        $xml = str_replace('</result>', '</edu:educacao>', $xml);
        $xml = str_replace('<edu:prestacaoContas>', '<edu:PrestacaoContas>', $xml);
        $xml = str_replace('</edu:prestacaoContas>', '</edu:PrestacaoContas>', $xml);

        return $xml;
    }
}