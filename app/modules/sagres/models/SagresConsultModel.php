<?php

namespace SagresEdu;

use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\XmlSchemaDateHandler;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\BaseTypesHandler;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use Symfony\Component\Validator\Validation;
use JMS\Serializer\SerializerBuilder;
use Yii as yii;
use Datetime;

class SagresConsultModel
{
    private $dbCommand;

    public function __construct()
    {
        $this->dbCommand = Yii::app()->db->createCommand();
    }

    public function getEducacaoData($reference_year, $dateStart, $dateEnd): EducacaoTType
    {
        $education = new EducacaoTType;

        $education->setPrestacaoContas($this->getManagementUnit(1))
            ->setEscola($this->getAllSchools($reference_year, $dateStart, $dateEnd))
            ->setProfissional($this->getProfessionalData($reference_year, $dateStart, $dateEnd));

        return $education;
    }


    public function getManagementUnit($idManagementUnit): CabecalhoTType
    {
        $query = "SELECT id,
                        cod_unidade_gestora,
                        name_unidade_gestora,
                        cpf_responsavel,
                        cpf_gestor,
                        ano_referencia,
                        mes_referencia,
                        versao_xml,
                        dia_inicio_prest_contas,
                        dia_final_prest_contas
                FROM provision_accounts WHERE id = :idManagementUnit;";

        $managementUnit = Yii::app()->db->createCommand($query)
            ->bindValue(':idManagementUnit', $idManagementUnit)
            ->queryRow();

        $headerType = new CabecalhoTType;

        $headerType->setCodigoUnidGestora($managementUnit['cod_unidade_gestora'])
            ->setNomeUnidGestora($managementUnit['name_unidade_gestora'])
            ->setCpfResponsavel($managementUnit['cpf_responsavel'])
            ->setCpfGestor($managementUnit['cpf_gestor'])
            ->setAnoReferencia((int) $managementUnit['ano_referencia'])
            ->setMesReferencia((int) $managementUnit['mes_referencia'])
            ->setVersaoXml((int) $managementUnit['versao_xml'])
            ->setDiaInicPresContas(date("d", strtotime($managementUnit['dia_inicio_prest_contas'])))
            ->setDiaFinaPresContas(date("d", strtotime($managementUnit['dia_final_prest_contas'])));

        return $headerType;
    }


    /**
     * Summary of EscolaTType
     * @return EscolaTType[] 
     */
    public function getAllSchools($reference_year, $dateStart, $dateEnd)
    {
        $schoolList = [];

        $query = "SELECT inep_id FROM school_identification";
        $schools = Yii::app()->db->createCommand($query)->queryAll();

        foreach ($schools as $school) {
            $schoolType = new EscolaTType;
            $schoolType->setIdEscola($school['inep_id'])
                ->setTurma($this->getTurmaType($school['inep_id'], $reference_year, $dateStart, $dateEnd))
                ->setDiretor($this->getDiretorType($school['inep_id']))
                ->setCardapio($this->getCardapioType($school['inep_id'], $reference_year, $dateStart, $dateEnd));

            if (!empty($schoolType->getTurma())) {
                if (!empty($schoolType->getCardapio())) {
                    $schoolList[] = $schoolType;
                } else {
                    
                }         
            } else {
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
    public function getTurmaType($inep_id, $reference_year, $data_inicio, $data_final)
    {
        $classList = [];

        $query = "SELECT 
                c.school_inep_fk, 
                c.id, 
                c.name, 
                c.turn
            FROM classroom c
            WHERE c.school_inep_fk = :inep_id
                AND c.school_year = :reference_year
                AND Date(c.create_date) BETWEEN :data_inicio AND :data_final";

        $params = [
            ':inep_id' => $inep_id,
            ':reference_year' => $reference_year,
            ':data_inicio' => $data_inicio,
            ':data_final' => $data_final,
        ];

        $turmas = $this->dbCommand->setText($query)
            ->bindValues($params)
            ->queryAll();

        foreach ($turmas as $turma) {
            $classType = new TurmaTType;
            $classId = $turma['id'];

            $classType->setPeriodo('0')
                ->setDescricao($turma["name"])
                ->setTurno($this->convertTurn($turma['turn']))
                ->setSerie($this->getSerieType($classId))
                ->setMatricula($this->getMatriculaType($classId, $reference_year))
                ->setHorario($this->setHorario($classId))
                ->setFinalTurma('0');

            $classList[] = $classType;
        }

        return $classList;
    }

    /**
     * Summary of SerieTType
     * @return SerieTType[] 
     */
    public function getSerieType($classId)
    {
        $serieList = [];

        $query = "SELECT 
                    c.name as descricao, 
                    c.modality as modalidade 
                FROM classroom c 
                where c.id = " . $classId . ";";

        $series = Yii::app()->db->createCommand($query)->queryAll();

        foreach ($series as $serie) {
            $serieType = new SerieTType;
            $serieType->setDescricao($serie['descricao'])
                ->setModalidade($serie['modalidade']);
            $serieList[] = $serieType;
        }

        return $serieList;
    }


    /**
     * Summary of SerieTType
     * @return HorarioTType[] 
     */
    public function setHorario($classId)
    {
        $horarioList = [];

        $query = "SELECT DISTINCT 
                    (c.final_hour - c.initial_hour) AS duracao, 
                    c.initial_hour AS hora_inicio,
                    ed.name AS disciplina
                FROM classroom c  
                JOIN class c2 on c2.classroom_fk = c.id 
                JOIN edcenso_discipline ed ON ed.id = c2.discipline_fk     
                WHERE c.id = " . $classId . ";";

        $horarios = Yii::app()->db->createCommand($query)->queryAll();

        foreach ($horarios as $horario) {
            $horario_t = new HorarioTType;
            $horario_t->setDiaSemana($horario['diaSemana'])
                ->setDuracao($horario['duracao'])
                ->setHoraInicio($this->getDateTimeFromInitialHour($horario['hora_inicio']))
                ->setDisciplina($horario['disciplina'])
                ->setCpfProfessor(empty($horario['cpfProfessor']));

            $horarioList[] = $horario_t;
        }

        return $horarioList;
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
    public function geAttendance($professionalId)
    {
        $attendanceList = [];

        $query = "SELECT 
                    date, 
                    local 
                FROM attendance 
                WHERE professional_fk = " . $professionalId . ";";
        $attendances = Yii::app()->db->createCommand($query)->queryAll();

        foreach ($attendances as $attendance) {
            $attendanceType = new AtendimentoTType;
            $attendanceType->setData(new DateTime($attendance['date']))
                ->setLocal($attendance['local']);
            $attendanceList[] = $attendanceType;
        }

        return $attendanceList;
    }

    public function getStudent($studentFk): AlunoTType
    {
        $query = "SELECT 
                    si2.responsable_cpf AS cpfAluno, 
                    si2.birthday AS data_nascimento, 
                    si2.name AS nome, 
                    si2.deficiency AS pcd, 
                    si2.sex AS sexo 
                    FROM student_identification si2 
                WHERE si2.id = " . $studentFk . ";";

        $student = Yii::app()->db->createCommand($query)->queryRow();

        $studentType = new AlunoTType;
        return $studentType->setNome($student['nome'])
            ->setDataNascimento(new DateTime($student['data_nascimento']))
            ->setCpfAluno($student['cpfAluno'])
            ->setPcd($student['pcd'])
            ->setSexo($student['sexo']);
    }


    /**
     * Summary of CardapioTType
     * @return CardapioTType[] 
     */
    public function getCardapioType($id_escola, $year, $data_inicio, $data_final)
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
            WHERE si.inep_id = " . $id_escola . ". and YEAR(lm.date) = " . $year ." and lm.date BETWEEN '" . $data_inicio . "' and '" . $data_final . "'";

        $cardapios = Yii::app()->db->createCommand($query)->queryAll();

        foreach ($cardapios as $cardapio) {
            $cardapioType = new CardapioTType;
            $cardapioType->setData(new DateTime($cardapio['data']))
                ->setTurno($this->convertTurn($cardapio['turno']))
                ->setDescricaoMerenda($cardapio['descricaoMerenda'])
                ->setAjustado($cardapio['ajustado']);

            $cardapioList[] = $cardapioType;
        }

        return $cardapioList;
    }

    public function getDiretorType($idSchool): DiretorTType
    {

        $query = "SELECT manager_cpf AS cpfDiretor, number_ato AS nrAto 
                FROM school_identification 
                WHERE inep_id = :idSchool;";

        $director = Yii::app()->db->createCommand($query)
            ->bindValue(':idSchool', $idSchool)
            ->queryRow();

        $directorType = new DiretorTType;
        $directorType->setCpfDiretor($director['cpfDiretor'])
            ->setNrAto($director['nrAto']);

        return $directorType;
    }


    /**
     * Summary of ProfissionalTType
     * @return ProfissionalTType[] 
     */
    public function getProfessionalData($reference_year, $dateStart, $dateEnd)
    {
        $professionalList = [];
        $query = "SELECT p.id_professional AS id_professional, p.cpf_professional  AS cpfProfissional, epec.name  AS especialidade, p.inep_id_fk AS idEscola, fundeb 
                FROM professional p
                JOIN edcenso_professional_education_course epec ON p.speciality_fk = epec.id
                JOIN attendance a ON p.id_professional  = a.professional_fk  
                WHERE YEAR(a.date) = :reference_year AND a.date BETWEEN :dateStart AND :dateEnd;";

        $command = Yii::app()->db->createCommand($query);
        $command->bindValues([
            ':reference_year' => $reference_year,
            ':dateStart' => $dateStart,
            ':dateEnd' => $dateEnd
        ]);

        $professionals = $command->queryAll();

        foreach ($professionals as $professional) {
            $professionalType = new ProfissionalTType;
            $professionalType->setCpfProfissional($professional['cpfProfissional'])
                ->setEspecialidade($professional['especialidade'])
                ->setIdEscola($professional['idEscola'])
                ->setFundeb($professional['fundeb'])
                ->setAtendimento($this->geAttendance($professional['id_professional']));

            $professionalList[] = $professionalType;
        }

        return $professionalList;
    }

    /**
     * Sets a new MatriculaTType
     *
     * @return MatriculaTType[]
     */
    public function getMatriculaType($id, $reference_year)
    {
        $enrollmentList = [];

        $query = "SELECT se.enrollment_id AS numero, se.student_fk,
                    se.create_date AS data_matricula, 
                    se.date_cancellation_enrollment AS data_cancelamento,
                    se.previous_stage_situation AS situation
                  FROM student_enrollment se 
                  WHERE se.classroom_fk  =  :id;";



        $command = Yii::app()->db->createCommand($query);
        $command->bindValues([
            ':id' => $id
        ])
            ->queryAll();

        $enrollments = $command->queryAll();

        foreach ($enrollments as $enrollment) {
            $enrollmentType = new MatriculaTType;
            $enrollmentType->setNumero($enrollment['numero'])
                ->setDataMatricula(new DateTime($enrollment['data_matricula']))
                ->setDataCancelamento(new DateTime($enrollment['data_cancelamento']))
                ->setNumeroFaltas((int) $this->returnNumberFaults($enrollment['student_fk'], $reference_year))
                ->setAprovado($this->getStudentSituation($enrollment['situation']))
                ->setAluno($this->getStudent($enrollment['student_fk']));

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
        $sql = "SELECT COUNT(*) 
            FROM class_faults cf 
            JOIN schedule s ON s.id = cf.schedule_fk 
            JOIN classroom c on c.id = s.classroom_fk 
            WHERE cf.student_fk = :studentId AND c.school_year = :referenceYear;";

        $numberFaults = Yii::app()->db->createCommand($sql)
            ->bindValues([
                ':studentId' => $studentId,
                ':referenceYear' => $referenceYear
            ])
            ->queryScalar();

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
            'M' => '1',
            'V' => '2',
            'N' => '3',
            'I' => '4',
        );

        return isset($turnos[$turn]) ? $turnos[$turn] : 0;
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