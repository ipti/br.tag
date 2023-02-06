<?php

namespace SagresEdu;

use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\XmlSchemaDateHandler;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\BaseTypesHandler;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use Symfony\Component\Validator\Validation;
use JMS\Serializer\SerializerBuilder;
use Yii as yii;
use Datetime;

class SagresConsult {

    static public function actionExport(){
        $sagres = new SagresConsult;
        $educacao_t = new EducacaoTType;

        $educacao_t = $sagres->getEducacao();
        $sagresEduXML = $sagres->generatesSagresEduXML($educacao_t);
        $sagres->actionExportSagresXML($sagresEduXML);
        //$sagres->validatorSagresEduExportXML($educacao_t);
    }

    //Class MAIN EducacaoTType
    /**
     * Summary of EducacaoTType
     * @return EducacaoTType 
     */
    public function getEducacao(){
        $escolas_t = new EscolaTType;
        $educacao_t = new EducacaoTType;
        $atendimento_t = new AtendimentoTType;
        $profissional_t = new ProfissionalTType;
        //$prestacao_contas_t = new PrestacaoContasTType;

        //$prestacao_contas_t = $this->setPrestacaocontas();
        $escolas_t = $this->getAllEscolas(); 
        $educacao_t->setEscola($escolas_t);
        $profissional_t = $this->getProfissional();
        $educacao_t->setProfissional([$profissional_t]);

        return $educacao_t;      
    }


        //Class complexType EscolaTType
    /**
     * Summary of EscolaTType
     * @return \SagresEdu\EscolaTType[] 
     */
    public function getAllEscolas() {
        $escola_t = new EscolaTType;
       
        $query = "SELECT si.inep_id FROM school_identification si WHERE si.inep_id = 28022041";
        $escolas = Yii::app()->db->createCommand($query)->queryAll();

        foreach($escolas as $escola){
            $escola_t->setIdEscola($escola['inep_id']); 

            $turma = $this->getTurmaType($escola['inep_id']);
            $escola_t->setTurma([$turma]);
           
            $diretor = $this->getDiretorType($escola['inep_id']);
            $escola_t->setDiretor($diretor);

            $cardapio = $this->getCardapioType($escola['inep_id']);
            $escola_t->setCardapio([$cardapio]);
        } 

       
        return [$escola_t];
    }


    //Class TurmaTType
    /**
     * Summary of TurmaTType
     * @return TurmaTType 
     */
    public function getTurmaType($inep_id) {
        $turma_t = new TurmaTType;
        $matricula_t = new MatriculaTType;
        $serie_t = new SerieTType;
        $horario_t = new HorarioTType;
    
        $query = "SELECT c.school_inep_fk, c.id, c.name, c.turn, se.enrollment_id  
                FROM classroom c
                    JOIN student_enrollment se ON se.classroom_fk = c.id 
                WHERE c.school_inep_fk = ". $inep_id. ";";

        $turmas = Yii::app()->db->createCommand($query)->queryAll();

        foreach ($turmas as $turma) {
            $turma_t->setPeriodo('0');
            $turma_t->setDescricao($turma["name"]);
            $turma_t->setTurno($turma['turn']);

            $serie_t = $this->getSerieType($turma['id']);
            $turma_t->setSerie([$serie_t]);
            $matricula_t = $this->getMatriculaType(98);
            $turma_t->setMatricula([$matricula_t]);
            $horario_t = $this->setHorario($turma['id']);
            $turma_t->setHorario([$horario_t]);    

            $turma_t->setFinalTurma('0'); 

        }

        return $turma_t;
    }

    //Class SerieTType
       /**
     * Summary of SerieTType
     * @return SerieTType[] 
     */
    public function getSerieType($id_turma) {
        $serie_t = new SerieTType;

        $query = "SELECT c.name as descricao, c.modality as modalidade 
                FROM classroom c 
                where c.id = " . $id_turma . ";"; 

        $series = Yii::app()->db->createCommand($query)->queryAll();

        foreach($series as $serie){
            $serie_t->setDescricao($serie['descricao']);
            $serie_t->setModalidade($serie['modalidade']);
        }
        return [$serie_t];
    }


    //Class HorarioTType
    public function setHorario($id_turma){
        $horario_t = new HorarioTType;
        
        $query = "SELECT s.week_day AS diaSemana, (c.final_hour - c.initial_hour) AS duracao, 
                       c.initial_hour AS hora_inicio, ed.name AS disciplina, idaa.cpf AS cpfProfessor  
                FROM classroom c      
                    join school_identification si ON si.inep_id = c.school_inep_fk 
                    join instructor_documents_and_address idaa ON idaa.school_inep_id_fk = si.inep_id 
                    join schedule s ON s.classroom_fk = c.id 
                    join edcenso_discipline ed ON ed.id = s.discipline_fk
                where ed.id = " . $id_turma . ";"; 
        
        $horarios = Yii::app()->db->createCommand($query)->queryAll();


        foreach ($horarios as $horario) {
            $horario_t->setDiaSemana($horario['diaSemana']);
            $horario_t->setDuracao($horario['duracao']);
            $horario_t->setHoraInicio(new DateTime());
            $horario_t->setDisciplina($horario['disciplina']);
            if ($horario['cpfProfessor'] == null)
                $horario_t->setCpfProfessor('00000000000');
            else
                $horario_t->setCpfProfessor($horario['cpfProfessor']);
        }

        return [$horario_t];
    }

    //Class complexType AtendimentoTType
    /**
     * Summary of EscolaTType
     * @return AtendimentoTType 
     */
    public function setAtendimento($id_professional){
        $atendimento_t = new AtendimentoTType;

        $query = "SELECT date, local FROM attendance WHERE professional_fk = " .$id_professional.";"; 
    
        $atendimentos = Yii::app()->db->createCommand($query)->queryAll();

        foreach($atendimentos as $atendimento){
            $atendimento_t->setData(new DateTime($atendimento['date']));
            $atendimento_t->setLocal($atendimento['local']);          
        }

        return $atendimento_t;
    }

    public function setPrestacaocontas(){
        $prestacao_contas_t = new PrestacaoContasTType;

        $query = "SELECT pa.id, pa.codigounidgestora, pa.nomeunidgestora, pa.cpfcontador, pa.cpfgestor, pa.anoreferencia, 
                       pa.mesreferencia, pa.versaoxml, pa.diainicprescontas, pa.diafinaprescontas
                FROM provision_accounts pa";

        $prestacaocontas = Yii::app()->db->createCommand($query)->queryAll();


        $prestacao_contas_t->setCodigoUnidGestora($prestacaocontas['codigounidgestora']);
        $prestacao_contas_t->setNomeUnidGestora($prestacaocontas['nomeunidgestora']);
        $prestacao_contas_t->setCpfContador($prestacaocontas['cpfcontador']);
        $prestacao_contas_t->setCpfGestor($prestacaocontas['cpfgestor']);
        $prestacao_contas_t->setAnoReferencia($prestacaocontas['anoreferencia']);
        $prestacao_contas_t->setMesReferencia($prestacaocontas['mesreferencia']);
        $prestacao_contas_t->setVersaoXml($prestacaocontas['versaoxml']);
        $prestacao_contas_t->setDiaInicPresContas($prestacaocontas['diainicprescontas']);
        $prestacao_contas_t->setDiaFinaPresContas($prestacaocontas['diafinaprescontas']);

        return $prestacao_contas_t;
    }

    //Class AlunoTType
    public function getAluno($id_matricula) {
        $aluno_t = new AlunoTType;

        $query = "SELECT si2.responsable_cpf AS cpfAluno, si2.birthday AS data_nascimento, 
                       si2.name AS nome, si2.deficiency AS pcd, si2.sex AS sexo 
                FROM student_enrollment se 
                    JOIN school_identification si ON si.inep_id = se.school_inep_id_fk 
                    JOIN student_identification si2 ON si.inep_id = si2.school_inep_id_fk
                WHERE se.enrollment_id = " . 11473602 . " and si2.responsable_cpf = 06215212563;";

        $aluno = Yii::app()->db->createCommand($query)->queryRow();
 
        $aluno_t->setNome($aluno['nome']);
        $aluno_t->setDataNascimento(new Datetime());
        $aluno_t->setCpfAluno($aluno['cpfAluno']);
        $aluno_t->setPcd($aluno['pcd']);
        $aluno_t->setSexo($aluno['sexo']);

        return $aluno_t;
    }  

    //Class CardapioTType
     /**
     * Summary of CardapioTType
     * @return CardapioTType 
     */
    public function getCardapioType($id_escola){
        $cardapio_t = new CardapioTType;    
        
        $query = "SELECT lm.date AS data, cr.turn AS turno, li.description AS descricaoMerenda, lm.adjusted AS ajustado 
                FROM classroom cr 
                    JOIN school_identification si ON si.inep_id = cr.school_inep_fk 
                    JOIN lunch_menu lm ON lm.school_fk = si.inep_id 
                    JOIN lunch_menu_meal lmm ON lm.id = lmm.menu_fk 
                    JOIN lunch_meal lme ON lme.id = lmm.meal_fk 
                    JOIN lunch_meal_portion lmp ON lmp.meal_fk = lme.id 
                    JOIN lunch_portion lp ON lp.id = lmp.portion_fk 
                    JOIN lunch_item li ON li.id = lp.item_fk
                WHERE si.inep_id = " . $id_escola . ";"; 

        $cardapio = Yii::app()->db->createCommand($query)->queryAll();  
           
        $cardapio_t->setData(new DateTime($cardapio['data']));

        if($cardapio['turno'] = 'M')
            $cardapio_t->setTurno('1');
        if($cardapio['turno'] = 'V')
            $cardapio_t->setTurno('2');
        if($cardapio['turno'] = 'N')
            $cardapio_t->setTurno('3');
        if($cardapio['turno'] = 'I')
            $cardapio_t->setTurno('4');

        $cardapio_t->setDescricaoMerenda($cardapio['descricaoMerenda']);
        $cardapio_t->setAjustado($cardapio['ajustado']);   

        return $cardapio_t;
    }

    //Class DiretorTType
     /**
     * Sets a new diretor
     *
     * @return DiretorTType
     */
    public function getDiretorType($id_escola){
        $diretor_t = new DiretorTType;
      
        $query = "SELECT manager_cpf AS cpfDiretor, number_ato AS nrAto 
                FROM school_identification 
                WHERE inep_id = " . $id_escola . ";";

        $diretor = Yii::app()->db->createCommand($query)->queryRow();

        $diretor_t->setCpfDiretor($diretor['cpfDiretor']);
        $diretor_t->setNrAto($diretor['nrAto']);

        return $diretor_t;
    }

    //Class ProfissionalTType
    public function getProfissional(){
        $profissional_t = new ProfissionalTType;
        $atendimento_t = new AtendimentoTType;

        $query = "SELECT id_professional, cpf_professional AS cpfProfissional, specialty AS especialidade, inep_id_fk AS idEscola, fundeb 
                FROM professional;";
       
        $profissionais = Yii::app()->db->createCommand($query)->queryAll();

        foreach ($profissionais as $profissional) {
            $profissional_t->setCpfProfissional($profissional['cpfProfissional']);
            $profissional_t->setEspecialidade($profissional['especialidade']);
            $profissional_t->setIdEscola($profissional['idEscola']);
            $profissional_t->setFundeb($profissional['fundeb']);
            $atendimento_t = $this->setAtendimento($profissional['id_professional']);
            $profissional_t->setAtendimento([$atendimento_t]);
        }
          
        return $profissional_t;    
    }

    //MatriculaTType
         /**
     * Sets a new MatriculaTType
     *
     * @return MatriculaTType[]
     */
    public function getMatriculaType($id){
        $matricula_t = new MatriculaTType;
        $aluno_t = new AlunoTType;

        $query = "SELECT se.enrollment_id AS numero, se.create_date AS data_matricula, 
                         se.date_cancellation_enrollment AS data_cancelamento 
                  FROM student_enrollment se 
                  WHERE se.classroom_fk  =  " . 98 .";";

        $matriculas = Yii::app()->db->createCommand($query)->queryAll();

        foreach($matriculas as $matricula){
            $matricula_t->setNumero($matricula['numero']);
            $matricula_t->setDataMatricula(new DateTime($matricula['data_matricula']));
            $matricula_t->setDataCancelamento(new DateTime($matricula['data_cancelamento']));
            $numberFaults = '0' ;//$this->returnNumberFaults($matricula);
            $matricula_t->setNumeroFaltas($numberFaults);
            $matricula_t->setAprovado('0');
            $aluno_t = $this->getAluno($matricula['numero']);
            $matricula_t->setAluno($aluno_t);
        }

        return [$matricula_t];
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

        $numero_faltas = Yii::app()->db->createCommand($query)->queryRow();

        return intval(($numero_faltas == null) ? '0' : $numero_faltas);
    }

    public function generatesSagresEduXML($sagresEduObject){
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


    
    public function actionExportSagresXML($xml){
        $fileName = "ExportSagres.xml";
        $fileDir = "./app/export/SagresEdu/" . $fileName;
        $file = fopen($fileDir, 'w');
        $linha = $xml;
        fwrite($file,$linha); 
        fclose($file);
        readfile($fileDir);
    }


    public function validatorSagresEduExportXML($object){
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

