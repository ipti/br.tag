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

    public function actionExportSagresXML($xml){
        $fileName = "sagres.txt";
        $fileDir = "./app/export/SagresEdu/" . $fileName;
        $file = fopen($fileDir, 'w');
        $linha = '$xml';
        fwrite($file,$linha); 
        fclose($file);
        readfile($fileDir);
    }

    //Class MAIN EducacaoTType
    public function getEducacao(){
        /*  
            private $escola = []
            private $atendimento = []; 
        */

        $sql = "SELECT inep_id FROM school_identification";
        $query_ineps = yii::app()->db->createCommand($sql)->queryAll();

        foreach( $query_ineps as $inep){
            $this->getEscola($inep['inep_id']);
            $this->getAtendimento();
        }

        return $query_ineps;      
    }

    public function getPrestacaocontas(){
        
    }

    //Class complexType EscolaTType
    public function getEscola($id_escola) {
        $escola_t = new EscolaTType;

        /*  private $idEscola = null;
            private $turma = [];
            private $diretor = null;
            private $cardapio = []; 
        */
        $sql = "SELECT inep_id FROM school_identification where inep_id = $id_escola";
        $idEscola = Yii::app()->db->createCommand($sql)->queryRow();
       
        $escola_t->setIdEscola($idEscola['inep_id']);
        $this->getTurma($idEscola['inep_id']);
        $this->getDiretor($idEscola['inep_id']);
        $this->getCardapio($idEscola['inep_id']);    

        return $idEscola;
    }

    //Class complexType AtendimentoTType
    public function getAtendimento(){
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
                
        return $atendimentos;     
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

    //Class SerieTType
    public function getSerie($id_turma) {
        $serie_t = new SerieTType;
        /*  private $descricao = null;
            private $modalidade = null; 
        */
        $sql = "SELECT name as descricao, modality as modalidade FROM classroom"; 
        $series = Yii::app()->db->createCommand($sql)->queryAll();

        foreach($series as $serie){
            $serie_t->setDescricao($serie['descricao']);
            $serie_t->setModalidade($serie['modalidade']);
        }

        return $series;
    }

    //Class TurmaTType
    public function getTurma($id_escola) {
        $turma_t = new TurmaTType;
        /*  
            <xsd:element name="periodo">			
            </xsd:element>
            <xsd:element name="descricao" minOccurs="0">
            </xsd:element>		
            <xsd:element name="turno" type="edu:turno_t" />
            <xsd:element name="serie" type="edu:serie_t" maxOccurs="unbounded" />	
            <xsd:element name="matricula" type="edu:matricula_t" maxOccurs="unbounded"/>
            <xsd:element name="horario" type="edu:horario_t" maxOccurs="unbounded">			
            </xsd:element>		
            <xsd:element name="finalTurma" type="xsd:boolean" minOccurs="0"/>
        */

        $sql = "SELECT c.id, c.name, c.turn, se.enrollment_id  FROM classroom c
                join student_enrollment se on se.classroom_fk = c.id";

        $turmas = Yii::app()->db->createCommand($sql)->queryAll();

        foreach($turmas as $turma){
            $turma_t->setPeriodo(0);
            $turma_t->setDescricao($turma['name']);
            $turma_t->setTurno($turma['turn']);
            $this->getSerie($turma['id']);
            $turma_t->setMatricula($turma);
            $this->getHorario($turma['id']);
            $turma_t->setFinalTurma(0);              
        }
       
        return $turmas;
    }

    //Class CardapioTType
    public function getCardapio($id_escola){
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
                JOIN lunch_item li ON li.id = lp.item_fk"; 

        $cardapios = Yii::app()->db->createCommand($sql)->queryAll();  
        
        foreach($cardapios as $cardapio){
            $cardapio_t->setData($cardapio['data']);
            $cardapio_t->setTurno($cardapio['turno']);
            $cardapio_t->setDescricaoMerenda($cardapio['descricaoMerenda']);
            $cardapio_t->setAjustado($cardapio['ajustado']);
        }      

        return $cardapios;
    }

    //Class DiretorTType
    //ALTER TABLE school_identification ADD number_ato VARCHAR(30) NOT NULL AFTER final_date;
    public function getDiretor($id_escola){
        $diretor_t = new DiretorTType;
        /*  private $cpfDiretor = null
            private $nrAto = null; 
        */  
        $sql = "SELECT manager_cpf as cpfDiretor, number_ato as nrAto FROM school_identification"; 
        $diretores = Yii::app()->db->createCommand($sql)->queryAll();

        foreach($diretores as $diretor){
            $diretor_t->setCpfDiretor($diretor['cpfDiretor']);
            $diretor_t->setNrAto($diretor['cpfDiretor']);
        } 

        return $diretores;
    }
    
    //Class HorarioTType
    public function getHorario($id_turma){
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
                join edcenso_discipline ed on ed.id = s.discipline_fk"; 
        
        $horarios = Yii::app()->db->createCommand($sql)->queryAll();

        foreach($horarios as $horario){
            $horario_t->setDiaSemana($horario['diaSemana']);
            $horario_t->setDuracao($horario['duracao']);
            $horario_t->setHoraInicio(DateTime::createFromFormat('m-d-Y', '10-16-2003')->format('Y-m-d'));
            $horario_t->setDisciplina($horario['disciplina']);    
            $horario_t->setCpfProfessor("757657576576");
        }
            
        return $horarios;      
    }

    //Class ProfissionalTType
    public function getProfissional($id_atendimento){
        $profissional_t = new ProfissionalTType;
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

        $sql = "SELECT cpf_professional as cpfProfissional, specialty as especialidade, inep_id_fk as idEscola, fundeb FROM professional"; 
        $profissionais = Yii::app()->db->createCommand($sql)->queryAll();
        
        foreach($profissionais as $profissional){
            $profissional_t->setCpfProfissional($profissional['cpfProfissional']);
            $profissional_t->setEspecialidade($profissional['especialidade']);
            $profissional_t->setIdEscola($profissional['idEscola']);
            $profissional_t->setFundeb($profissional['fundeb']);
        }

        return $profissionais;    
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

