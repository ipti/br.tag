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
        $educacao = $sagres->getEducacao();
        $sagresEduXML = $sagres->generatesSagresEduXML($educacao);
        $sagres->actionExportSagresXML($sagresEduXML);
    }

    //Class MAIN EducacaoTType
    /**
     * Summary of EducacaoTType
     * @return EducacaoTType 
     */
    public function getEducacao(){
        $educacao_t = new EducacaoTType;

        $this->setPrestacaocontas();

        $escolas = $this->getAllEscolas();
    
        $educacao_t->setEscola([$escolas]);
        
        $atendimento = $this->setAtendimento();
        $educacao_t->setAtendimento([$atendimento]);

        return $educacao_t;      
    }


    //Class TurmaTType
    /**
     * Summary of TurmaTType
     * @return TurmaTType 
     */
    public function getTurmaType($id_escola) {
        $turma_t = new TurmaTType;
        $matricula_t = new MatriculaTType;
    
        $query = "SELECT c.id, c.name, c.turn, se.enrollment_id  
                FROM classroom c
                    JOIN student_enrollment se ON se.classroom_fk = c.id 
                WHERE c.inep_id = ". $id_escola. ";";

        $turmas = Yii::app()->db->createCommand($query)->queryAll();

        foreach ($turmas as $turma) {
            $turma_t->setPeriodo(0);
            $turma_t->setDescricao($turma['name']);
            $turma_t->setTurno($turma['turn']);

            $matricula = $this->getMatriculaType($turma['id']);
            $turma_t->setMatricula([$matricula]);

            $turma_t->setFinalTurma(0); 

            $serie = $this->getSerieType($turma['id']);
            $turma_t->setSerie([$serie]);

            $horario = $this->setHorario($turma['id']);
            $turma_t->setHorario([$horario]);    
        }

        return $turma_t;
    }

    //Class SerieTType
       /**
     * Summary of SerieTType
     * @return SerieTType 
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

        return $serie_t;
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
                where id = " . $id_turma; 
        
        $horarios = Yii::app()->db->createCommand($query)->queryAll();

        foreach($horarios as $horario){
            $horario_t->setDiaSemana($horario['diaSemana']);
            $horario_t->setDuracao($horario['duracao']);
            $horario_t->setHoraInicio(new DateTime("2022-12-01 ".$horario['hora_inicio'].":00:00"));
            $horario_t->setDisciplina($horario['disciplina']);    
            if($horario['cpfProfessor'] == null)
                $horario_t->setCpfProfessor('00000000000');
            else
                $horario_t->setCpfProfessor($horario['cpfProfessor']);
        }

        return $horario_t;
    }

    //Class complexType EscolaTType
    /**
     * Summary of EscolaTType
     * @return EscolaTType 
     */
    public function getAllEscolas() {
        $escola_t = new EscolaTType;

        $query = "SELECT si.inep_id FROM school_identification si";
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

       
        return $escola_t;
    }

    //Class complexType AtendimentoTType
    public function setAtendimento(){
        $atendimento_t = new AtendimentoTType;
        $profissional_t = new ProfissionalTType;

        $query = "SELECT att.date, att.local, pro.cpf_professional AS cpfProfissional, pro.specialty AS especialidade, 
                       pro.inep_id_fk AS idEscola, pro.fundeb 
                FROM attendance att
                    JOIN professional pro ON att.professional_fk = pro.id_professional"; 
    
        $atendimentos = Yii::app()->db->createCommand($query)->queryAll();
    
        foreach($atendimentos as $atendimento){
            $atendimento_t->setData(new DateTime($atendimento['data']));
            $atendimento_t->setLocal($atendimento['local']);
               
            $profissional_t->setCpfProfissional($atendimento['cpfProfissional']);
            $profissional_t->setEspecialidade($atendimento['especialidade']);
            $profissional_t->setIdEscola($atendimento['idEscola']);
            $profissional_t->setFundeb($atendimento['fundeb']);
            $atendimento_t->setProfissional($profissional_t);      
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
        
    }

    //Class AlunoTType
    public function getAluno($id_matricula) {
        $aluno_t = new AlunoTType;

        $query = "SELECT si2.responsable_cpf AS cpfAluno, si2.birthday AS data_nascimento, 
                       si2.name AS nome, si2.deficiency AS pcd, si2.sex AS sexo 
                FROM student_enrollment se 
                    JOIN school_identification si ON si.inep_id = se.school_inep_id_fk 
                    JOIN student_identification si2 ON si.inep_id = si2.school_inep_id_fk
                WHERE se.enrollment_id = " . $id_matricula . ";";

        $aluno = Yii::app()->db->createCommand($query)->queryRow();
 
        $aluno_t->setNome($aluno['nome']);
        $aluno_t->setDataNascimento($aluno['data_nascimento']);
        $aluno_t->setCpfAluno($aluno['cpfAluno']);
        $aluno_t->setPcd($aluno['pcd']);
        $aluno_t->setSexo($aluno['sexo']);
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
             $cardapio_t->setTurno(1);
        if($cardapio['turno'] = 'V')
            $cardapio_t->setTurno(2);
        if($cardapio['turno'] = 'N')
            $cardapio_t->setTurno(3);
        if($cardapio['turno'] = 'I')
            $cardapio_t->setTurno(4);

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
    public function getProfissional($id_professional){

        $query = "SELECT cpf_professional AS cpfProfissional, specialty AS especialidade, inep_id_fk AS idEscola, fundeb 
                FROM professional 
                WHERE id_professional = ". $id_professional . ";";
       
       $profissional = Yii::app()->db->createCommand($query)->queryRow();

        return $profissional;    
    }

    //MatriculaTType
         /**
     * Sets a new MatriculaTType
     *
     * @return MatriculaTType
     */
    public function getMatriculaType($id_turma){
        $matricula_t = new MatriculaTType;

        $query = "SELECT se.enrollment_id AS numero, se.create_date AS data_matricula, 
                         se.date_cancellation_enrollment AS data_cancelamento 
                  FROM student_enrollment se 
                  WHERE se.enrollment_id = " . $id_turma .";";

        $matriculas = Yii::app()->db->createCommand($query)->queryAll();

        foreach($matriculas as $matricula){

            $numero_faltas = "select cf.faults from schedule t
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

            $matricula_t->setNumero($matricula['numero']);
            $matricula_t->setDataMatricula($matricula['data_matricula']);
            $matricula_t->setDataCancelamento($matricula['data_cancelamento']);
            $matricula_t->setNumeroFaltas($numero_faltas);
            $matricula_t->setAprovado(0);
            $this->getAluno($matricula['numero']);
        }

        return $matriculas;
    }

    public function generatesSagresEduXML($sagresEduObject){
        $serializerBuilder = SerializerBuilder::create();
        $serializerBuilder->addMetadataDir(dirname(__FILE__).'/metadata/sagresEduMetadata', 'DataSagresEdu');
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
        $fileName = "sagres.txt";
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

