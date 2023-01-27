<?php

namespace SagresEdu;

use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\BaseTypesHandler;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\XmlSchemaDateHandler;

use Yii as yii;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Validator\Validation;

use \Datetime;

class SagresConsult {

    static public function actionExport(){
        $sagres = new SagresConsult;
        $sagres->getEducacao();
        //$sagresEduXML = $sagres->generatesSagresEduXML();
        //$sagres->actionExportSagresXML($sagresEduXML);
    }

    //Class MAIN EducacaoTType
    public function getEducacao(){
        $escola_t = new EscolaTType;
        $prestacao_constas_t = new PrestacaoContasTType;

        $this->getPrestacaocontas();

        $escolas = $this->getAllEscolas();
        foreach($escolas as $escola){
            $escola_t->setIdEscola($escola['inep_id']); 
            $this->setTurma($escola['inep_id']);
            $this->setDiretor($escola['inep_id']);
            $this->setCardapio($escola['inep_id']); 
        } 

        $this->setAtendimento();          
    }


    //Class TurmaTType
    public function setTurma($id_escola) {
        $turma_t = new TurmaTType;
    
        $sql = "SELECT c.id, c.name, c.turn, se.enrollment_id  FROM classroom c
                join student_enrollment se on se.classroom_fk = c.id where c.inep_id = ". $id_escola. ";";
        $turmas = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($turmas as $turma) {
            $turma_t->setPeriodo(0);
            $turma_t->setDescricao($turma['name']);
            $turma_t->setTurno($turma['turn']);
            $turma_t->setMatricula($turma);
            $turma_t->setFinalTurma(0); 

            $this->setSerie($turma['id']);
            $this->setHorario($turma['id']);     
        }
    }

    //Class SerieTType
    public function setSerie($id_turma) {
        $serie_t = new SerieTType;
        /*  private $descricao = null;
            private $modalidade = null; 
        */
        $sql = "SELECT name as descricao, modality as modalidade FROM classroom where id = " . $id_turma . ";"; 
        $series = Yii::app()->db->createCommand($sql)->queryAll();

        foreach($series as $serie){
            $serie_t->setDescricao($serie['descricao']);
            $serie_t->setModalidade($serie['modalidade']);
        }
    }


    //Class HorarioTType
    public function setHorario($id_turma){
        $horario_t = new HorarioTType;
        /*         
            private $diaSemana = null;
            private $duracao = null;
            private $horaInicio = null;
            private $disciplina = null;
            private $cpfProfessor = []
        */
    
        $sql = "SELECT s.week_day as diaSemana, (c.final_hour - c.initial_hour) as duracao, c.initial_hour as hora_inicio, ed.name as disciplina, idaa.cpf as cpfProfessor  FROM classroom c      
                join school_identification si on si.inep_id = c.school_inep_fk 
                join instructor_documents_and_address idaa on idaa.school_inep_id_fk = si.inep_id 
                join schedule s on  s.classroom_fk = c.id 
                join edcenso_discipline ed on ed.id = s.discipline_fk
                where id = " . $id_turma; 
        
        $horarios = Yii::app()->db->createCommand($sql)->queryAll();

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
    }

    //Class complexType EscolaTType
    public function getAllEscolas() {
    
        /*  private $idEscola = null;
            private $turma = [];
            private $diretor = null;
            private $cardapio = []; 
        */
        $sql = "SELECT inep_id FROM school_identification";
        $escolas = Yii::app()->db->createCommand($sql)->queryAll();
       
        return $escolas;
    }

    //Class complexType AtendimentoTType
    public function setAtendimento(){
        $atendimento_t = new AtendimentoTType;
        $profissional_t = new ProfissionalTType;
       /*         
        private $data = null;
        private $local = null;
        private $profissional = null;
        
        CREATE TABLE attendance (
            id_attendance INT NOT NULL PRIMARY KEY,
            date DATE NOT NULL,
            local varchar(100) NOT NULL,
            professional_fk INT NOT NULL
        );
    
        ALTER TABLE attendance CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci
        ALTER TABLE attendance ADD CONSTRAINT fk_professional_attendance FOREIGN KEY (professional_fk) REFERENCES professional(id_professional)
        */     
        
        $sql = "SELECT att.date, att.local, pro.cpf_professional as cpfProfissional, pro.specialty as especialidade, pro.inep_id_fk as idEscola, pro.fundeb FROM attendance att
                JOIN professional pro ON att.professional_fk = pro.id_professional"; 
    
        $atendimentos = Yii::app()->db->createCommand($sql)->queryAll();
    
        foreach($atendimentos as $atendimento){
            $atendimento_t->setData($atendimento['data']);
            $atendimento_t->setLocal($atendimento['local']);
               
            $profissional_t->setCpfProfissional($atendimento['cpfProfissional']);
            $profissional_t->setEspecialidade($atendimento['especialidade']);
            $profissional_t->setIdEscola($atendimento['idEscola']);
            $profissional_t->setFundeb($atendimento['fundeb']);
        }   
    }

    public function getPrestacaocontas(){
        
    }

    //Class AlunoTType
    public function getAluno($id_matricula) {
        $aluno_t = new AlunoTType;
        /* private $nome = null;
            private $dataNascimento = null;
            private $cpfAluno = null;
            private $pcd = null;
            private $sexo = null;
        */

        $sql = "SELECT responsable_cpf as cpfAluno, birthday as data_nascimento, name as nome, deficiency as pcd, sex as sexo from student_identification";
        $alunos = Yii::app()->db->createCommand($sql)->queryAll();

        foreach($alunos as $aluno){
            $aluno_t->setNome($aluno['nome']);
            $aluno_t->setDataNascimento($aluno['data_nascimento']);
            $aluno_t->setCpfAluno($aluno['cpfAluno']);
            $aluno_t->setSexo($aluno['sexo']);
        }   
        
        return $alunos;
    }  

    //Class CardapioTType
    public function setCardapio($id_escola){
        $cardapio_t = new CardapioTType;
        /* 
            private $data = null;
            private $turno = null;
            private $descricaoMerenda = null;
            private $ajustado = null; 
        */       
        $sql = "SELECT lm.date as data, cr.turn as turno, li.description as descricaoMerenda, lm.adjusted as ajustado from classroom cr 
                JOIN school_identification si ON si.inep_id = cr.school_inep_fk 
                JOIN lunch_menu lm ON lm.school_fk = si.inep_id 
                JOIN lunch_menu_meal lmm ON lm.id = lmm.menu_fk 
                JOIN lunch_meal lme ON lme.id = lmm.meal_fk 
                JOIN lunch_meal_portion lmp ON lmp.meal_fk = lme.id 
                JOIN lunch_portion lp ON lp.id = lmp.portion_fk 
                JOIN lunch_item li ON li.id = lp.item_fk
                WHERE inep_id = " . $id_escola; 

        $cardapios = Yii::app()->db->createCommand($sql)->queryAll();  
        
        foreach($cardapios as $cardapio){
            $cardapio_t->setData($cardapio['data']);
            $cardapio_t->setTurno($cardapio['turno']);
            $cardapio_t->setDescricaoMerenda($cardapio['descricaoMerenda']);
            $cardapio_t->setAjustado($cardapio['ajustado']);
        }      
    }

    //Class DiretorTType
    //ALTER TABLE school_identification ADD number_ato VARCHAR(30) NOT NULL AFTER final_date;
    public function setDiretor($id_escola){
        $diretor_t = new DiretorTType;
        /*  private $cpfDiretor = null
            private $nrAto = null; 
        */  
        $sql = "SELECT manager_cpf as cpfDiretor, number_ato as nrAto FROM school_identification where inep_id = " . $id_escola . ";";
        $diretor = Yii::app()->db->createCommand($sql)->queryRow();

        $diretor_t->setCpfDiretor($diretor['cpfDiretor']);
        $diretor_t->setNrAto($diretor['nrAto']);
    }

    //Class ProfissionalTType
    public function getProfissional($id_professional){
        /*             
            private $cpfProfissional = null;
            private $especialidade = null;
            private $idEscola = null;
            private $fundeb = null;
        */

        /*
        CREATE TABLE professional (
            id_professional int not null PRIMARY key,
            cpf_professional varchar(14) not null,
            specialty varchar(100) not null,
            inep_id_fk varchar(8) not null,
            fundeb BOOLEAN NOT NULL
        )

        ALTER TABLE professional CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci
        ALTER TABLE professional ADD CONSTRAINT fk_schoolidentificationProfessional FOREIGN KEY (inep_id_fk) REFERENCES school_identification(inep_id)

        */

        $sql = "SELECT cpf_professional as cpfProfissional, specialty as especialidade, inep_id_fk as idEscola, fundeb FROM professional where id_professional = ". $id_professional . ";";
        $profissional = Yii::app()->db->createCommand($sql)->queryRow();

        return $profissional;    
    }

    //MatriculaTType
    public function getMatricula($id_turma){
        $matricula_t = new MatriculaTType;
        /* 
            <xsd:element name="numero">
            </xsd:element>
            <xsd:element name="data_matricula" type="edu:data_t" />
            <xsd:element name="data_cancelamento" type="edu:data_t" minOccurs="0" />
            <xsd:element name="numero_faltas" type="xsd:integer" />
            <xsd:element name="aprovado" type="xsd:boolean" minOccurs="0" />
            <xsd:element name="aluno" type="edu:aluno_t" />
       */

       // ALTER TABLE `student_enrollment` ADD `date_cancellation_enrollment` DATE NULL DEFAULT NULL AFTER `status`;
       // 'status', array("1" => "Matriculado", "2" => "Transferido", "3" => "Cancelado", "4" => "Evadido")

       $query = "SELECT se.enrollment_id as numero, se.create_date as data_matricula, se.date_cancellation_enrollment as data_cancelamento FROM student_enrollment se" ;
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
    }

    public function generatesSagresEduXML(){
        $serializerBuilder = SerializerBuilder::create();
        $serializerBuilder->addMetadataDir('C:\xampp\htdocs\br.tag\app\soap\metadata\sagresEduMetadata', 'DataSagresEdu');
        $serializerBuilder->configureHandlers(function (HandlerRegistryInterface $handler) use ($serializerBuilder) {
            $serializerBuilder->addDefaultHandlers();
            $handler->registerSubscribingHandler(new BaseTypesHandler()); // XMLSchema List handling
            $handler->registerSubscribingHandler(new XmlSchemaDateHandler()); // XMLSchema date handling
            // $handler->registerSubscribingHandler(new YourhandlerHere());
        });
    
        $serializer = $serializerBuilder->build();
    
        // deserialize the XML into Demo\MyObject object
        $sagresEduObject = $serializer->deserialize('<some xml/>', 'DataSagresEdu\MyObject', 'xml');
     
        return $serializer->serialize($sagresEduObject, 'xml'); // serialize the Object and return SagresEdu XML
           
    }


    
    public function actionExportSagresXML($xml){
        $fileName = "sagres.txt";
        $fileDir = "./app/export/SagresEdu/" . $fileName;
        $file = fopen($fileDir, 'w');
        $linha = '$xml';
        fwrite($file,$linha); 
        fclose($file);
        readfile($fileDir);
    }


    public function validatorSagresEduExportXML($object){
        // get the validator
        $builder = Validation::createValidatorBuilder();
        foreach (glob('C:\xampp\htdocs\br.tag\app\soap\metadata\sagresEduMetadata') as $file) {
            $builder->addYamlMapping($file);
        }
        $validator =  $builder->getValidator();

        // validate $object
        return $validator->validate($object, null, ['xsd_rules']);
    }

}

