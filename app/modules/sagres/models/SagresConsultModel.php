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
    public function getEducacaoData($year, $data_inicio, $data_final): EducacaoTType
    {
        $education = new EducacaoTType;

        $education->setPrestacaoContas($this->getUnidadeGestora(1))
            ->setEscola($this->getAllSchools($year, $data_inicio, $data_final))
            ->setProfissional($this->getProfessionalData($year, $data_inicio, $data_final));

        return $education;
    }


    public function getUnidadeGestora($id_unidadeGestora): CabecalhoTType
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
                FROM provision_accounts WHERE id =" . $id_unidadeGestora . ";";

        $unidadeGestora = Yii::app()->db->createCommand($query)->queryRow();

        $cabecalhoType = new CabecalhoTType;

        return $cabecalhoType->setCodigoUnidGestora($unidadeGestora['cod_unidade_gestora'])
            ->setNomeUnidGestora($unidadeGestora['name_unidade_gestora'])
            ->setCpfResponsavel($unidadeGestora['cpf_responsavel'])
            ->setCpfGestor($unidadeGestora['cpf_gestor'])
            ->setAnoReferencia(intval($unidadeGestora['ano_referencia']))
            ->setMesReferencia(intval($unidadeGestora['mes_referencia']))
            ->setVersaoXml(intval($unidadeGestora['versao_xml']))
            ->setDiaInicPresContas(date("d", strtotime($unidadeGestora['dia_inicio_prest_contas'])))
            ->setDiaFinaPresContas(date("d", strtotime($unidadeGestora['dia_final_prest_contas'])));

    }

    /**
     * Summary of EscolaTType
     * @return EscolaTType[] 
     */
    public function getAllSchools($year, $data_inicio, $data_final)
    {
        $schoolList = [];

        $query = "SELECT inep_id FROM school_identification";
        $schools = Yii::app()->db->createCommand($query)->queryAll();

        foreach ($schools as $school) {
            $schoolType = new EscolaTType;
            $schoolType->setIdEscola($school['inep_id'])
                ->setTurma($this->getTurmaType($school['inep_id'], $year, $data_inicio, $data_final))
                ->setDiretor($this->getDiretorType($school['inep_id']))
                ->setCardapio($this->getCardapioType($school['inep_id'], $year, $data_inicio, $data_final));

            $schoolList[] = $schoolType;
        }

        return $schoolList;
    }

    /**
     * Summary of TurmaTType
     * @return TurmaTType[]
     */
    public function getTurmaType($inep_id, $year, $data_inicio, $data_final)
    {
        $turmaList = [];

        $query = "SELECT 
                    c.school_inep_fk, 
                    c.id, 
                    c.name, 
                    c.turn
                FROM classroom c
                WHERE c.school_inep_fk = " . $inep_id .
            " and c.school_year = " . $year .
            " and Date(c.create_date) BETWEEN '" . $data_inicio . "' and '" . $data_final . "';";

        $turmas = Yii::app()->db->createCommand($query)->queryAll();

        foreach ($turmas as $turma) {
            $turmaType = new TurmaTType;
            $turmaType->setPeriodo('0')
                ->setDescricao($turma["name"])
                ->setTurno($this->convertTurn($turma['turn']))
                ->setSerie($this->getSerieType($turma['id']))
                ->setMatricula($this->getMatriculaType($turma['id']))
                ->setHorario($this->setHorario($turma['id']))
                ->setFinalTurma('0');

            $turmaList[] = $turmaType;
        }

        return $turmaList;
    }


    /**
     * Summary of SerieTType
     * @return SerieTType[] 
     */
    public function getSerieType($id_turma)
    {
        $serieList = [];

        $query = "SELECT 
                    c.name as descricao, 
                    c.modality as modalidade 
                FROM classroom c 
                where c.id = " . $id_turma . ";";

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
    public function setHorario($id_turma)
    {
        $horarioList = [];

        $query = "  SELECT DISTINCT 
                    s.week_day AS diaSemana, 
                    (c.final_hour - c.initial_hour) AS duracao, 
                    c.initial_hour AS hora_inicio, 
                    ed.name AS disciplina, 
                    idaa.cpf AS cpfProfessor  
                FROM classroom c      
                    join school_identification si ON si.inep_id = c.school_inep_fk 
                    join instructor_documents_and_address idaa ON idaa.school_inep_id_fk = si.inep_id 
                    join schedule s ON s.classroom_fk = c.id 
                    join edcenso_discipline ed ON ed.id = s.discipline_fk
                    WHERE idaa.cpf is not null and c.id = " . $id_turma . ";";


        $horarios = Yii::app()->db->createCommand($query)->queryAll();


        foreach ($horarios as $horario) {
            $horario_t = new HorarioTType;
            $horario_t->setDiaSemana($horario['diaSemana'])
                ->setDuracao($horario['duracao'])
                ->setHoraInicio(new DateTime())
                ->setDisciplina($horario['disciplina'])
                ->setCpfProfessor(empty($horario['cpfProfessor']));

            $horarioList[] = $horario_t;
        }

        return $horarioList;
    }

    /**
     * Summary of EscolaTType
     * @return AtendimentoTType[] 
     */
    public function geAttendance($professional_id)
    {
        $attendanceList = [];

        $query = "SELECT 
                    date, 
                    local 
                FROM attendance 
                WHERE professional_fk = " . $professional_id . ";";
        $attendances = Yii::app()->db->createCommand($query)->queryAll();

        foreach ($attendances as $attendance) {
            $attendanceType = new AtendimentoTType;
            $attendanceType->setData(new DateTime($attendance['date']))
                ->setLocal($attendance['local']);
            $attendanceList[] = $attendanceType;
        }

        return $attendanceList;
    }

    public function getStudent($student_fk): AlunoTType
    {
        $query = "SELECT 
                    si2.responsable_cpf AS cpfAluno, 
                    si2.birthday AS data_nascimento, 
                    si2.name AS nome, 
                    si2.deficiency AS pcd, 
                    si2.sex AS sexo 
                    FROM student_identification si2 
                WHERE si2.id = " . $student_fk . ";";

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
                WHERE si.inep_id = " . $id_escola . " and cr.school_year = " . $year . " and lm.date  BETWEEN '" . $data_inicio . "' and '" . $data_final . "';";

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

    public function getDiretorType($id_escola): DiretorTType
    {

        $query = "SELECT manager_cpf AS cpfDiretor, number_ato AS nrAto 
                FROM school_identification 
                WHERE inep_id = " . $id_escola . ";";

        $diretor = Yii::app()->db->createCommand($query)->queryRow();

        $diretorType = new DiretorTType;
        return $diretorType->setCpfDiretor($diretor['cpfDiretor'])
            ->setNrAto($diretor['nrAto']);
    }


    /**
     * Summary of ProfissionalTType
     * @return ProfissionalTType[] 
     */
    public function getProfessionalData($anoAtendimento, $data_inicio, $data_final)
    {
        $profissionaisList = [];

        $query = "SELECT p.id_professional AS id_professional, p.cpf_professional  AS cpfProfissional, epec.name  AS especialidade, p.inep_id_fk AS idEscola, fundeb 
                FROM professional p
                JOIN edcenso_professional_education_course epec ON p.speciality_fk = epec.id
                JOIN attendance a ON p.id_professional  = a.professional_fk  
                WHERE YEAR(a.date) = " . $anoAtendimento . " and a.date  BETWEEN '" . $data_inicio . "' and '" . $data_final . "';";

        $profissionais = Yii::app()->db->createCommand($query)->queryAll();

        foreach ($profissionais as $profissional) {
            $profissionalType = new ProfissionalTType;
            $profissionalType->setCpfProfissional($profissional['cpfProfissional'])
                ->setEspecialidade($profissional['especialidade'])
                ->setIdEscola($profissional['idEscola'])
                ->setFundeb($profissional['fundeb'])
                ->setAtendimento($this->geAttendance($profissional['id_professional']));

            $profissionaisList[] = $profissionalType;
        }

        return $profissionaisList;
    }

    /**
     * Sets a new MatriculaTType
     *
     * @return MatriculaTType[]
     */
    public function getMatriculaType($id)
    {
        $matriculasList = [];

        $query = "SELECT se.student_fk,
                    se.create_date AS data_matricula, 
                    se.date_cancellation_enrollment AS data_cancelamento  
                  FROM student_enrollment se 
                  WHERE se.classroom_fk  =  " . $id . ";";

        $matriculas = Yii::app()->db->createCommand($query)->queryAll();

        foreach ($matriculas as $matricula) {
            $matriculaType = new MatriculaTType;
            $matriculaType->setNumero($matricula['numero'])
                ->setDataMatricula(new DateTime($matricula['data_matricula']))
                ->setDataCancelamento(new DateTime($matricula['data_cancelamento']))
                //->setNumeroFaltas($this->returnNumberFaults($matricula['numero']))
                ->setAprovado('0')
                ->setAluno($this->getStudent($matricula['student_fk']));
            $matriculasList[] = $matriculaType;
        }

        return $matriculasList;
    }

    public function returnNumberFaults($matricula)
    {
        $query = "select cf.faults from schedule t
                    left join classroom c on c.id = t.classroom_fk
                    left join student_enrollment se on se.classroom_fk = t.classroom_fk
                    left join student_identification si on se.student_fk = si.id
                    left join student_documents_and_address sd on sd.id = si.id
                    left join (
                        SELECT schedule.classroom_fk, schedule.month, student_fk, count(*) faults
                        FROM class_faults cf
                        left join schedule on schedule.id = schedule_fk
                        group by student_fk, schedule.month, schedule.classroom_fk) cf
                    on (c.id = cf.classroom_fk AND se.student_fk = cf.student_fk AND cf.month = t.month)
                    where c.school_year = 2022
                        AND t.month = 3
                        and se.enrollment_id = " . $matricula . "
                        and si.name is not null";

        $numeroFaltas = Yii::app()->db->createCommand($query)->queryRow();

        return intval(($numeroFaltas == null) ? '0' : $numeroFaltas);
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