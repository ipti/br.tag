<?php

class ReportsController extends Controller {

    public $layout = 'fullmenu';
    public $year = 0;

    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'BFReport', 'numberStudentsPerClassroomReport',
                                    'InstructorsPerClassroomReport','StudentsFileReport','StudentsFileBoquimReport',
                                    'getStudentsFileInformation', 'ResultBoardReport',
                                    'StatisticalDataReport', 'StudentsDeclarationReport',
                                    'EnrollmentPerClassroomReport','AtaSchoolPerformance'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }
    
    public function beforeAction($action){
        $this->year = Yii::app()->user->year;
        return true;
    }

    public function actionAtaSchoolPerformance($id){
        $sql = "SELECT * FROM ataPerformance
                    where `year`  = ".$this->year.""
                . " AND classroom_id = $id;";
       
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        setlocale(LC_ALL, NULL);
        setlocale(LC_ALL, "pt_BR.utf8", "pt_BR", "ptb", "ptb.utf8");
        $time = mktime(0, 0, 0, $result['month']);
        $result['month'] = strftime("%B", $time);
               
        $classroom = Classroom::model()->findByPk($id);
        $students = StudentEnrollment::model()->findAll('classroom_fk = '.$classroom->id);
        
        $this->render('AtaSchoolPerformance', array(
            'report' => $result,
            'classroom' => $classroom,
            'students' => $students
        ));          
    }

    public function actionEnrollmentPerClassroomReport($id){
        $sql = "SELECT * FROM EnrollmentPerClassroom
                    where `year`  = ".$this->year.""
                . " AND classroom_id = $id;";
       
        $result = Yii::app()->db->createCommand($sql)->queryAll();
               
        $classroom = Classroom::model()->findByPk($id);
        
        $this->render('EnrollmentPerClassroomReport', array(
            'report' => $result,
            'classroom' => $classroom
        ));          
    }

    public function actionStatisticalDataReport(){
        $result = null;
        $this->render('StatisticalDataReport', array(
            'report' => $result,
        ));  
    }
    
    public function actionResultBoardReport(){
        $result = null;
        $this->render('ResultBoardReport', array(
            'report' => $result,
        ));  
    }

    public function actionNumberStudentsPerClassroomReport() {
        $sql = "SELECT * FROM NumberOfStudentsPerClassroom
                    where school_year  = ".$this->year." and school_inep_fk=".Yii::app()->user->school;
       
        $result = Yii::app()->db->createCommand($sql)->queryAll();
                
        $this->render('NumberStudentsPerClassroomReport', array(
            'report' => $result,
        ));          
    }
    
    public function actionInstructorsPerClassroomReport() {
        $sql = "SELECT * FROM instructorsperclassroom "
                . "where school_year = ".$this->year." and school_inep_fk= ".Yii::app()->user->school.";";

        $result = Yii::app()->db->createCommand($sql)->queryAll();
                
        $this->render('InstructorsPerClassroomReport', array(
            'report' => $result,
        ));         
    }
    
    public function actionStudentsDeclarationReport($id) {
        $sql = "SELECT * FROM StudentsDeclaration WHERE student_id = ".$id." AND `year`  = ".$this->year.";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        $this->render('StudentsDeclarationReport', array(
            'report' => $result
        ));         
    }
 
    public function actionStudentsFileReport() {
        $this->render('StudentsFileReport', array());         
    }
    
    public function actionGetStudentsFileBoquimInformation($student_id){
        $sql = "SELECT * FROM StudentsFileBoquim WHERE id = ".$student_id.";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        
        echo json_encode($result);
    }
    
    public function actionStudentsFileBoquimReport($student_id) {
        $this->layout = "reports";
        $this->render('StudentsFileBoquimReport', array('student_id'=>$student_id));         
    }
    
    
    public function actionGetStudentsFileInformation($student_id){
        $sql = "SELECT * FROM StudentsFile WHERE id = ".$student_id.";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        
        echo json_encode($result);
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
