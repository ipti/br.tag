<?php

class ReportsController extends Controller {

    public $layout = 'fullmenu';

    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'BFReport', 'numberStudentsPerClassroomReport',
                                    'InstructorsPerClassroomReport'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionNumberStudentsPerClassroomReport() {
        $sql = "Select c.id, c.`name`,
                    CONCAT_WS(' - ',CONCAT_WS(':',initial_hour,initial_minute), CONCAT_WS(':',final_hour, final_minute)) as `time`, 
                    IF(c.assistance_type = 0, 'NÃO SE APLICA', IF(c.assistance_type = 1, 'CLASSE HOSPITALAR', IF(c.assistance_type = 2, 'UNIDADE DE ATENDIMENTO SOCIOEDUCATIVO', IF(c.assistance_type = 3, 'UNIDADE PRISIONAL ATIVIDADE COMPLEMENTAR', 'ATENDIMENTO EDUCACIONALESPECIALIZADO (AEE)')))) as assistance_type,
                    IF(c.modality = 1, 'REGULAR', IF(c.modality = 2, 'ESPECIAL', 'EJA')) as modality, 
                    esm.`name` as stage, count(c.id) as students from classroom as c
                join student_enrollment as se on (c.id = se.classroom_fk)
                join edcenso_stage_vs_modality as esm on (c.edcenso_stage_vs_modality_fk = esm.id)
                where c.school_year = year(now())
                group by c.id;";
       
        $result = Yii::app()->db->createCommand($sql)->queryAll();
                
        $this->render('NumberStudentsPerClassroomReport', array(
            'report' => $result,
        ));
                
    }
    
    public function actionInstructorsPerClassroomReport() {
        $sql = "Select c.id, c.`name`,
	CONCAT_WS(' - ',CONCAT_WS(':',initial_hour,initial_minute), CONCAT_WS(':',final_hour, final_minute)) as `time`, 
	IF(c.assistance_type = 0, 'NÃO SE APLICA', IF(c.assistance_type = 1, 'CLASSE HOSPITALAR', IF(c.assistance_type = 2, 'UNIDADE DE ATENDIMENTO SOCIOEDUCATIVO', IF(c.assistance_type = 3, 'UNIDADE PRISIONAL ATIVIDADE COMPLEMENTAR', 'ATENDIMENTO EDUCACIONALESPECIALIZADO (AEE)')))) as assistance_type,
	IF(c.modality = 1, 'REGULAR', IF(c.modality = 2, 'ESPECIAL', 'EJA')) as modality, 
	esm.`name` as stage,
	CONCAT_WS(' - ',if(c.week_days_sunday = 1,'DOMINGO', null),	if(c.week_days_monday = 1,'SEGUNDA', null),	if(c.week_days_tuesday = 1,'TERÇA', null),	if(c.week_days_wednesday = 1,'QUARTA', null),	if(c.week_days_thursday = 1,'QUINTA', null),	if(c.week_days_friday = 1,'SEXTA', null),	if(c.week_days_saturday = 1,'SÁBADO', null)) as week_days,
	inf.id as instructor_id, inf.birthday_date, inf.`name` as instructor_name, 
	IF(ivd.scholarity=1,'Fundamental Incompleto',IF(ivd.scholarity=2,'Fundamental Completo',IF(ivd.scholarity=3,'Ensino Médio Normal/Magistério',IF(ivd.scholarity=4,'Ensino Médio Normal/Magistério Indígena',IF(ivd.scholarity=6,'Ensino Médio','Superior'))))) as scholarity,
	CONCAT_WS('<br>',d1.`name`, d2.`name`, d3.`name`, d4.`name`, d5.`name`, d6.`name`, d7.`name`, d8.`name`, d9.`name`, d10.`name`, d11.`name`, d12.`name`, d13.`name`) as disciplines
from classroom as c
join instructor_teaching_data as itd on (itd.classroom_id_fk = c.id)
join instructor_identification as inf on (inf.id = itd.instructor_fk)
join instructor_variable_data as ivd on (inf.id = ivd.id)
join edcenso_stage_vs_modality as esm on (c.edcenso_stage_vs_modality_fk = esm.id)
left join edcenso_discipline as d1 on (d1.id = itd.discipline_1_fk)
left join edcenso_discipline as d2 on (d2.id = itd.discipline_2_fk)
left join edcenso_discipline as d3 on (d3.id = itd.discipline_3_fk)
left join edcenso_discipline as d4 on (d4.id = itd.discipline_4_fk)
left join edcenso_discipline as d5 on (d5.id = itd.discipline_5_fk)
left join edcenso_discipline as d6 on (d6.id = itd.discipline_6_fk)
left join edcenso_discipline as d7 on (d7.id = itd.discipline_7_fk)
left join edcenso_discipline as d8 on (d8.id = itd.discipline_8_fk)
left join edcenso_discipline as d9 on (d9.id = itd.discipline_9_fk)
left join edcenso_discipline as d10 on (d10.id = itd.discipline_10_fk)
left join edcenso_discipline as d11 on (d11.id = itd.discipline_11_fk)
left join edcenso_discipline as d12 on (d12.id = itd.discipline_12_fk)
left join edcenso_discipline as d13 on (d13.id = itd.discipline_13_fk)
where c.school_year = year(now())-1
order by c.id;";

        $result = Yii::app()->db->createCommand($sql)->queryAll();
                
        $this->render('InstructorsPerClassroomReport', array(
            'report' => $result,
        ));
                
    }
    public function actionBFReport() {
        
        //@done s3 - Verificar se a frequencia dos últimos 3 meses foi adicionada(existe pelo menso 1 class cadastrado no mês)
        //@done S3 - Selecionar todas as aulas de todas as turmas ativas dos ultimos 3 meses
        //@done s3 - Pegar todos os alunos matriculados nas turmas atuais.
        //@done s3 - Enviar dados pre-processados para a página.
        $month = (int)date('m');
        $monthI = $month <= 3 ? 1 : $month-3;
        $monthF = $month <= 1 ? 1 : $month-1;
        $year = date('Y');
        /*
        select c.name classroom, si.name student, si.nis nis, si.birthday, t.month, count(*) count , cf.faults
        from class t
        left join classroom c on c.id = t.classroom_fk
        left join student_enrollment se on se.classroom_fk = t.classroom_fk
        left join student_identification si on se.student_fk = si.id
        left join (
            SELECT class.classroom_fk, class.month, student_fk, count(*) faults 
            FROM class_faults cf
            left join class on class.id = class_fk
            group by student_fk, class.month, class.classroom_fk) cf 
        on (c.id = cf.classroom_fk AND se.student_fk = cf.student_fk AND cf.month = t.month)
        where c.school_year = 2013 
            AND t.month >= 1 
            AND t.month <= 3
            AND t.given_class = 1
        group by c.id, t.month, si.id
        order by student;
         */
        
        $command = Yii::app()->db->createCommand();
        //day é um armengo, se colocar colunas que não estão na tabela o count não aparece na array
        $command->select = 'c.name classroom, si.name student, si.nis nis, si.birthday, t.month, count(*) count , cf.faults ';
        $command->from = 'class t ';
        $command->join  ='left join classroom c on c.id = t.classroom_fk ';
        $command->join .='left join student_enrollment se on se.classroom_fk = t.classroom_fk ';
        $command->join .='left join student_identification si on se.student_fk = si.id ';
        $command->join .='left join (
            SELECT class.classroom_fk, class.month, student_fk, count(*) faults 
            FROM class_faults cf
            left join class on class.id = class_fk
            group by student_fk, class.month,class.classroom_fk) cf 
        on (c.id = cf.classroom_fk AND se.student_fk = cf.student_fk AND cf.month = t.month) ';
        $command->where('c.school_year = :year '
                . 'AND t.month >= :monthI '
                . 'AND t.month <= :monthF '
                . 'AND t.given_class = 1 ',//0 não, 1 sim
                array(":year" => $year, ":monthI" => $monthI, ":monthF" => $monthF));
        $command->group = "c.id, t.month, si.id";
        $command->order = "student, month";
        $query = $command->queryAll();
        
        //@done S3 - Organizar o resultado da query que estava ilegível.
        $report = array();
        foreach ($query as $v) {
            $classroom  = $v['classroom'];
            $student    = $v['student'];
            $month      = $v['month'];
            $birthday   = $v['birthday'];
            $nis        = isset($v['nis'])          ? $v['nis']         : "Não Informado";
            $count      = isset($v['count'])        ? $v['count']       : 0;
            $faults     = isset($v['faults'])       ? $v['faults']      : 0;
            
            //$report[$student]['Frequency'][$month] = $faults/$count or N/A
            //@done s3 - Calcular frequência para cada aluno: (Total de horários - faltas do aluno) / (Total de horários - Dias não ministrados)
            
            $report[$student]['Frequency'][$month]  = 
                        ($count == 0)   //Se Count for 0, então não houveram aulas cadastradas
                        ? ('N/A')       //Assim atribuimos N/A
                        : (floor(
                                (($count-$faults)/$count)*100   //Calcula a %
                                *100    //Multiplica por 100, para guardar 2 casas decimais
                            )/100       //Efetua o truncamento e divide por 100 para colocar as casas decimais em seus devidos lugares
                            )."%";      //coloca o sinal de % no final
            
            $report[$student]['Info']['Classroom']  = $classroom;
            $report[$student]['Info']['NIS']        = $nis;
            $report[$student]['Info']['birthday']   = $birthday;
        }
        
        //Se não houver aulas no mês, coloca 0 no lugar.
        foreach ($report as $name => $c){
            for ($i = $monthI; $i <= $monthF; $i++) {
                $report[$name]['Frequency'][$i] = isset($c['Frequency'][$i]) ? $c['Frequency'][$i] : ('N/A');
            }
        }

        $this->render('BFReport', array(
            'report' => $report,
        ));
    }

    public function actionIndex() {
        $this->render('index');
    }

}
