<?php

namespace SagresEdu;

use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\XmlSchemaDateHandler;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\BaseTypesHandler;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use Symfony\Component\Validator\Validation;
use JMS\Serializer\SerializerBuilder;
use Yii as yii;
use Datetime;

class SagresConsult 
{

    static public function actionExport()
    {
        $yearSagresConsult = 2022;

        $sagres = new SagresConsult;
        $sagresEduXML = $sagres->generatesSagresEduXML($sagres->getEducacaoData($yearSagresConsult));
        $sagres->actionExportSagresXML($sagresEduXML);
    }

    public function getEducacaoData($year): EducacaoTType
    {
        $education = new EducacaoTType; 
        $education->setEscola($this->getAllSchools($year));
        $education->setProfissional($this->getProfessionalData($year));
    
        return $education;
    }

    /**
     * Summary of EscolaTType
     * @return EscolaTType[] 
     */
    public function getAllSchools($year) 
    {
        $schoolList = [];
    
        $query = "SELECT si.inep_id FROM school_identification si";
        $schools = Yii::app()->db->createCommand($query)->queryAll();
    
        foreach ($schools as $school) {
            $schoolType = new EscolaTType;
            $schoolType->setIdEscola($school['inep_id']); 
    
            $schoolType->setTurma($this->getTurmaType($school['inep_id'], $year));
            $schoolType->setDiretor($this->getDiretorType($school['inep_id']));
            $schoolType->setCardapio($this->getCardapioType($school['inep_id'], $year));
    
            $schoolList[] = $schoolType;
        }
    
        return $schoolList;
    }
    
    /**
     * Summary of TurmaTType
     * @return TurmaTType[]
     */
    public function getTurmaType($inep_id, $year) 
    {
        $turmaList = [];
    
        $query = "SELECT 
                    c.school_inep_fk, 
                    c.id, 
                    c.name, 
                    c.turn, 
                    se.enrollment_id  
                FROM classroom c
                JOIN student_enrollment se ON se.classroom_fk = c.id 
                WHERE c.school_inep_fk = ". $inep_id . " and c.school_year = ". $year . ";";

        $turmas = Yii::app()->db->createCommand($query)->queryAll();

        $turnos = array(
            'M' => '1',
            'V' => '2',
            'N' => '3',
            'I' => '4',
        );

        foreach ($turmas as $turma) 
        {
            $turmaType = new TurmaTType;
            $turmaType->setPeriodo('0');
            $turmaType->setDescricao($turma["name"]);
     
            switch ($turma['turn']) 
            {
                case 'M': $turmaType->setTurno($turnos['M']); break;
                case 'V': $turmaType->setTurno($turnos['V']); break;
                case 'N': $turmaType->setTurno($turnos['N']); break;
                case 'I': $turmaType->setTurno($turnos['I']); break;
                default:
                    // Código para lidar com valores inválidos
                    $turmaType->setTurno(0);
                    break;
            }

            $turmaType->setSerie($this->getSerieType($turma['id']));
            //$turmaType->setMatricula($this->getMatriculaType($turma['id']));
            //$turmaType->setHorario($this->setHorario($turma['id']));   
            $turmaType->setFinalTurma('0');  

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
    
        foreach($series as $serie){
            $serieType = new SerieTType;
            $serieType->setDescricao($serie['descricao']);
            $serieType->setModalidade($serie['modalidade']);
            $serieList[] =  $serieType;
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
        
        $query = "SELECT 
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
                where ed.id = " . $id_turma . ";"; 
        
        $horarios = Yii::app()->db->createCommand($query)->queryAll();


        foreach ($horarios as $horario) 
        {
            $horario_t = new HorarioTType;
            $horario_t->setDiaSemana($horario['diaSemana']);
            $horario_t->setDuracao($horario['duracao']);
            $horario_t->setHoraInicio(new DateTime());
            $horario_t->setDisciplina($horario['disciplina']);
            $horario_t->setCpfProfessor(empty($horario['cpfProfessor']) ? '00000000000' : $horario['cpfProfessor']);

            $horarioList[] = $horario_t;
        }

        return  $horarioList;
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
                WHERE professional_fk = " .$professional_id.";"; 
        $attendances = Yii::app()->db->createCommand($query)->queryAll();
    
        foreach($attendances as $attendance)
        {
            $attendanceType = new AtendimentoTType;
            $attendanceType->setData(new DateTime($attendance['date']));
            $attendanceType->setLocal($attendance['local']);
            $attendanceList[] = $attendanceType;
        }
    
        return $attendanceList;
    }
    

    public function getPrestacaocontas(): PrestacaoContasTType
    {
        $provisionAccountsType = new PrestacaoContasTType;

        $query = "SELECT 
                    pa.id, 
                    pa.codigounidgestora, 
                    pa.nomeunidgestora, 
                    pa.cpfcontador, 
                    pa.cpfgestor, 
                    pa.anoreferencia, 
                    pa.mesreferencia, 
                    pa.versaoxml, 
                    pa.diainicprescontas, 
                    pa.diafinaprescontas
                FROM provision_accounts pa";

        $financialReporting = Yii::app()->db->createCommand($query)->queryAll();

        $provisionAccountsType->setCodigoUnidGestora($financialReporting['codigounidgestora']);
        $provisionAccountsType->setNomeUnidGestora($financialReporting['nomeunidgestora']);
        $provisionAccountsType->setCpfContador($financialReporting['cpfcontador']);
        $provisionAccountsType->setCpfGestor($financialReporting['cpfgestor']);
        $provisionAccountsType->setAnoReferencia($financialReporting['anoreferencia']);
        $provisionAccountsType->setMesReferencia($financialReporting['mesreferencia']);
        $provisionAccountsType->setVersaoXml($financialReporting['versaoxml']);
        $provisionAccountsType->setDiaInicPresContas($financialReporting['diainicprescontas']);
        $provisionAccountsType->setDiaFinaPresContas($financialReporting['diafinaprescontas']);

        return $provisionAccountsType;
    }

    public function getStudent($id_matricula): AlunoTType 
    {
        $studentType = new AlunoTType;
    
        $query = "SELECT 
                    si2.responsable_cpf AS cpfAluno, 
                    si2.birthday AS data_nascimento, 
                    si2.name AS nome, 
                    si2.deficiency AS pcd, 
                    si2.sex AS sexo 
                FROM student_enrollment se 
                    JOIN school_identification si ON si.inep_id = se.school_inep_id_fk 
                    JOIN student_identification si2 ON si.inep_id = si2.school_inep_id_fk
                WHERE se.enrollment_id = :id_matricula;";
    
        $student = Yii::app()->db->createCommand($query)
            ->bindValue(':id_matricula', $id_matricula)
            ->queryRow();

        $studentType->setNome($student['nome']);
        $studentType->setDataNascimento(new DateTime($student['data_nascimento']));
        $studentType->setCpfAluno($student['cpfAluno']);
        $studentType->setPcd($student['pcd']);
        $studentType->setSexo($student['sexo']);
    
        return $studentType;
    }
    

    //Class CardapioTType
     /**
     * Summary of CardapioTType
     * @return CardapioTType[] 
     */
    public function getCardapioType($id_escola, $year)
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
                WHERE si.inep_id = " . $id_escola . " and cr.school_year = " . $year . ";"; 
    
        $cardapios = Yii::app()->db->createCommand($query)->queryAll();  
    
        foreach ($cardapios as $cardapio) 
        {
            $cardapioType = new CardapioTType;
            $cardapioType->setData(new DateTime($cardapio['data']));
            $cardapioType->setTurno($cardapio['turno']);
            $cardapioType->setDescricaoMerenda($cardapio['descricaoMerenda']);
            $cardapioType->setAjustado($cardapio['ajustado']); 
    
            $cardapioList[] = $cardapioType;
        }
    
        return $cardapioList;
    }

    public function getDiretorType($id_escola): DiretorTType
    {
        $diretorType = new DiretorTType;
      
        $query = "SELECT manager_cpf AS cpfDiretor, number_ato AS nrAto 
                FROM school_identification 
                WHERE inep_id = " . $id_escola . ";";

        $diretor = Yii::app()->db->createCommand($query)->queryRow();

        $diretorType->setCpfDiretor($diretor['cpfDiretor']);
        $diretorType->setNrAto($diretor['nrAto']);

        return $diretorType;
    }

    public function getProfessionalData($anoAtendimento)
    {
        $profissionaisList = [];

        $query = "SELECT id_professional, cpf_professional AS cpfProfissional, specialty AS especialidade, inep_id_fk AS idEscola, fundeb 
                FROM professional p
                JOIN attendance a ON p.id_professional = a.professional_fk 
                WHERE YEAR(a.date) = " . $anoAtendimento . ";";
       
        $profissionais = Yii::app()->db->createCommand($query)->queryAll();

        foreach ($profissionais as $profissional) 
        {
            $profissionalType = new ProfissionalTType;
            $profissionalType->setCpfProfissional($profissional['cpfProfissional']);
            $profissionalType->setEspecialidade($profissional['especialidade']);
            $profissionalType->setIdEscola($profissional['idEscola']);
            $profissionalType->setFundeb($profissional['fundeb']);
            $profissionalType->setAtendimento($this->geAttendance($profissional['id_professional']));
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
    
        $query = "SELECT se.enrollment_id AS numero, se.create_date AS data_matricula, 
                         se.date_cancellation_enrollment AS data_cancelamento 
                  FROM student_enrollment se 
                  WHERE se.classroom_fk  =  " . $id .";";
    
        $matriculas = Yii::app()->db->createCommand($query)->queryAll();
    
        foreach($matriculas as $matricula)
        {
            $matriculaType = new MatriculaTType;
            $matriculaType->setNumero($matricula['numero']);
            $matriculaType->setDataMatricula(new DateTime($matricula['data_matricula']));
            $matriculaType->setDataCancelamento(new DateTime($matricula['data_cancelamento']));
            $matriculaType->setNumeroFaltas($this->returnNumberFaults($matricula['numero']));
            $matriculaType->setAprovado('0');
            $matriculaType->setAluno($this->getStudent($matricula['numero']));
            $matriculasList[] = $matriculaType;
        }
    
        return $matriculasList;
    }
    

    public function returnNumberFaults($matricula){
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
                        and se.enrollment_id = " . $matricula['numero'] . "
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
        $fileName = "ExportSagres.xml";
        $fileDir = "./app/export/SagresEdu/" . $fileName;
        $file = fopen($fileDir, 'w');
        $linha = preg_replace("/<!\[CDATA\[(.*?)\]\]>/s", "\\1", $xml);
        fwrite($file,$linha); 
        fclose($file);
        readfile($fileDir);
    }


    public function validatorSagresEduExportXML($object)
    {
        // get the validator
        $builder = Validation::createValidatorBuilder();
        foreach (glob('C:\Users\JoseNatan\Documents\Developer\br.tag\app\modules\sagres\controllers\metadata\sagresEduMetadata') as $file) {
            $builder->addYamlMapping($file);
        }
        $validator =  $builder->getValidator();

        // validate $object
        return $validator->validate($object, null, ['xsd_rules']);
    }

}

