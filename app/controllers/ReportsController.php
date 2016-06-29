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
                                    'EnrollmentPerClassroomReport','AtaSchoolPerformance',
                                    'EnrollmentDeclarationReport', 'TransferForm',
                                    'EnrollmentNotification', 'TransferRequirement',
                                    'EnrollmentComparativeAnalysisReport','SchoolProfessionalNumberByClassroomReport',
                                    'ComplementarActivityAssistantByClassroomReport','EducationalAssistantPerClassroomReport',
                                    'DisciplineAndInstructorRelationReport','ClassroomWithoutInstructorRelationReport',
                                    'StudentInstructorNumbersRelationReport' ),
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

    public function actionStudentsUsingSchoolTransportationRelationReport(){
        $school = SchoolIdentification::model()->findByPk($_GET['id']);
        $sql = "SELECT DISTINCT si.inep_id,si.name,si.birthday,sd.residence_zone, se.*
                FROM (student_identification as si join student_enrollment as se on si.id = se.student_fk)
                join classroom as c on se.classroom_fk = c.id
                join student_documents_and_address as sd on si.inep_id = sd.student_fk
                where se.public_transport = 1 and si.school_inep_id_fk = ".$_GET['id']." AND se.school_inep_id_fk =  ".$_GET['id']."
                AND c.school_year = ".$this->year." order by si.name";

        $students = Yii::app()->db->createCommand($sql)->queryAll();

        $sql1 = "select c.*, q.modality,q.stage
                from classroom as c join classroom_qtd_students as q
                on c.school_inep_fk = q.school_inep_fk
                where c.school_year = ".$this->year." AND q.school_year = ".$this->year." and c.school_inep_fk = ".$_GET['id']." AND q.school_inep_fk = ".$_GET['id']."  AND c.id = q.id
                order by name";
        $classrooms = Yii::app()->db->createCommand($sql1)->queryAll();

        $this->render('StudentsUsingSchoolTransportationRelationReport',array(
            'school' => $school,
            'students' => $students,
            'classrooms' => $classrooms
        ));
    }

    public function actionStudentsWithDisabilitiesRelationReport(){
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql = "SELECT si.*, se.classroom_fk
                FROM student_identification as si join student_enrollment as se on si.id = se.student_fk join classroom as c on se.classroom_fk = c.id
                where si.deficiency = 1 and si.school_inep_id_fk = ".$_GET['id']." and se.school_inep_id_fk = ".$_GET['id']." and c.school_year = ".$this->year." order by si.name";

        $students = Yii::app()->db->createCommand($sql)->queryAll();

        $sql1 = "select c.*, q.modality,q.stage
                from classroom as c join classroom_qtd_students as q
                on c.school_inep_fk = q.school_inep_fk
                where c.school_year = ".$this->year." AND q.school_year = ".$this->year." and c.school_inep_fk = ".$_GET['id']." AND q.school_inep_fk = ".$_GET['id']."  AND c.id = q.id
                order by name";
        $classrooms = Yii::app()->db->createCommand($sql1)->queryAll();

        $this->render('StudentsWithDisabilitiesRelationReport', array(
            'school' => $school,
            'students' => $students,
            'classrooms' => $classrooms
        ));
    }

    public function actionStudentsInAlphabeticalOrderRelationReport(){
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql = "select si.name as studentName, si.inep_id as studentInepId, se.classroom_inep_id, si.birthday,cq.*
                from (student_identification as si join student_enrollment as se on si.id = se.student_fk)
                join classroom_qtd_students as cq on cq.id = se.classroom_fk
                where se.school_inep_id_fk = ".$_GET['id']."  AND si.school_inep_id_fk = ".$_GET['id']." AND cq.school_year = ".$this->year."
                order by si.name";

        $students = Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('StudentsInAlphabeticalOrderRelationReport',array(
            'school' => $school,
            'students' => $students
        ));
    }



    public function actionEnrollmentPerClassroomReport($id){
        $sql = "SELECT * FROM classroom_enrollment
                    where `year`  = ".$this->year.""
            . " AND classroom_id = $id"
            . " ORDER BY name;";
       
        $result = Yii::app()->db->createCommand($sql)->queryAll();
               
        $classroom = Classroom::model()->findByPk($id);
        
        $this->render('EnrollmentPerClassroomReport', array(
            'report' => $result,
            'classroom' => $classroom
        ));          
    }


    public function actionStudentsByClassroomReport(){
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql = "select c.*, q.modality,q.stage
                from classroom as c join classroom_qtd_students as q
                on c.school_inep_fk = q.school_inep_fk
                where c.school_year = ".$this->year." AND q.school_year = ".$this->year." and c.school_inep_fk = ".$_GET['id']." AND q.school_inep_fk = ".$_GET['id']."  AND c.id = q.id
                order by name";

        $classrooms = Yii::app()->db->createCommand($sql)->queryAll();

        $sql1 = "SELECT DISTINCT se.classroom_fk,si.inep_id,si.name,si.birthday
                FROM ((student_identification as si join student_enrollment as se on si.id = se.student_fk )
                join classroom as c on se.classroom_fk = c.id)
                where se.school_inep_id_fk = ".$_GET['id']." and c.school_year = ".$this->year." order by si.name" ;

        $students = Yii::app()->db->createCommand($sql1)->queryAll();

        $this->render( 'StudentsByClassroomReport', array(
            'school' => $school,
            'classroom' => $classrooms,
            'students' => $students
        ));
    }

    public function actionStudentsBetween5And14YearsOldReport(){
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql = "select c.*, q.modality,q.stage
                from classroom as c join classroom_qtd_students as q
                on c.school_inep_fk = q.school_inep_fk
                where c.school_year = ".$this->year." AND q.school_year = ".$this->year." and c.school_inep_fk = ".$_GET['id']." AND q.school_inep_fk = ".$_GET['id']."  AND c.id = q.id
                order by name";

        $classrooms = Yii::app()->db->createCommand($sql)->queryAll();

        $sql1 = "SELECT se.classroom_fk,si.inep_id,si.name,si.birthday , si.filiation_1, si.filiation_2
                FROM (student_identification as si join student_enrollment as se on si.id = se.student_fk join classroom as c on se.classroom_fk = c.id )
                where se.school_inep_id_fk =".$_GET['id']." and c.school_year = ".$this->year." order by si.name ";

        $students = Yii::app()->db->createCommand($sql1)->queryAll();

        $this->render('StudentsBetween5And14YearsOldReport',array(
            'school' => $school,
            'classroom' => $classrooms,
            'students' => $students

        ));
    }

    public function actionComplementarActivityAssistantByClassroomReport(){
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql = "select c.*, q.modality,q.stage
                from classroom as c join classroom_qtd_students as q
                on c.school_inep_fk = q.school_inep_fk
                where c.assistance_type = 4 AND c.school_year = ".$this->year." AND q.school_year = ".$this->year." and c.school_inep_fk = ".$_GET['id']." AND q.school_inep_fk = ".$_GET['id']."  AND c.id = q.id
                order by name";

        $classrooms = Yii::app()->db->createCommand($sql)->queryAll();

        $sql = "select id.*,it.classroom_id_fk , iv.scholarity
                from (((instructor_identification as id join instructor_teaching_data as it on it.instructor_fk = id.id)
                join instructor_variable_data as iv on iv.id = id.id) join classroom as c on c.id = it.classroom_id_fk)
                where id.school_inep_id_fk = ".$_GET['id']."  AND (it.role = 2 or it.role = 3) AND c.school_year = ".$this->year." order by c.name";

        $professor = Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('ComplementarActivityAssistantByClassroomReport', array(
            "school" => $school,
            "classroom" => $classrooms,
            'professor' => $professor
        ));
    }


    public function actionDisciplineAndInstructorRelationReport(){
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql = "select c.*, q.modality,q.stage
                from classroom as c join classroom_qtd_students as q
                on c.school_inep_fk = q.school_inep_fk
                where c.school_year = ".$this->year." AND q.school_year = ".$this->year." and c.school_inep_fk = ".$_GET['id']." AND q.school_inep_fk = ".$_GET['id']."  AND c.id = q.id
                order by name";

        $classrooms = Yii::app()->db->createCommand($sql)->queryAll();

        $sql = "select id.*,it.classroom_id_fk, iv.scholarity
                from (((instructor_identification as id join instructor_teaching_data as it on it.instructor_fk = id.id)
                join instructor_variable_data as iv on iv.id = id.id)join classroom as c on c.inep_id = it.classroom_inep_id)
                where id.school_inep_id_fk = ".$_GET['id']."  AND it.role = 1 AND c.school_year = ".$this->year." order by c.name";

        $professor = Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('DisciplineAndInstructorRelationReport', array(
            'school' => $school,
            'professor' => $professor,
            'classroom' => $classrooms
        ));
    }

    public function actionIncompatibleStudentAgeByClassroomReport(){
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql = "select c.*, q.modality,q.stage
                from classroom as c join classroom_qtd_students as q
                on c.school_inep_fk = q.school_inep_fk
                where c.school_year = ".$this->year." AND q.school_year = ".$this->year." and c.school_inep_fk = ".$_GET['id']." AND q.school_inep_fk = ".$_GET['id']."  AND c.id = q.id
                order by name";

        $classroom = Yii::app()->db->createCommand($sql)->queryAll();

        $sql1 = "SELECT se.classroom_fk,si.inep_id,si.name,si.birthday
                FROM (student_identification as si join student_enrollment as se on si.id = se.student_fk ) join classroom as c on se.classroom_fk = c.id
                where c.school_year = ".$this->year." AND se.school_inep_id_fk = ".$_GET['id']." ";

        $students = Yii::app()->db->createCommand($sql1)->queryAll();

        $this->render('IncompatibleStudentAgeByClassroomReport',array(
            'school' => $school,
            'classroom' => $classroom,
            'students' => $students
        ));
    }

    public function actionStudentsWithOtherSchoolEnrollmentReport(){
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql1 = "select si.inep_id as student_id , si.name as student_name, si.birthday as student_birthday, s1.school1, s1.school2, c1.name as classroom_name1 from(
                select distinct least(se.school_inep_id_fk, s2.school_inep_id_fk) as school1, greatest(se.school_inep_id_fk, s2.school_inep_id_fk) as school2, se.student_fk, se.id se1, s2.id se2, se.classroom_fk classroom1, s2.classroom_fk classroom2
                from student_enrollment se
                join student_enrollment s2 on se.student_fk = s2.student_fk
                where se.school_inep_id_fk != s2.school_inep_id_fk
                and se.school_inep_id_fk = ".$_GET['id']."
                ) as s1
                join classroom c1 on(s1.classroom1 = c1.id)
                join classroom c2 on(s1.classroom2 = c2.id)
                join student_identification si on (si.id = s1.student_fk)
                where c1.school_year = ".$this->year." ";

        $students = Yii::app()->db->createCommand($sql1)->queryAll();

        $this->render('StudentsWithOtherSchoolEnrollmentReport',array(
            'school' => $school,
            'students' => $students
        ));
    }


    public function actionEducationalAssistantPerClassroomReport(){
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql = "select c.*, q.modality,q.stage
                from classroom as c join classroom_qtd_students as q
                on c.school_inep_fk = q.school_inep_fk
                where c.school_year = ".$this->year." AND q.school_year = ".$this->year." and c.school_inep_fk = ".$_GET['id']." AND q.school_inep_fk = ".$_GET['id']."  AND c.id = q.id
                order by name";
        $classrooms = Yii::app()->db->createCommand($sql)->queryAll();

        $sql1 = "select DISTINCT c.id as classroomID ,c.name as className,id.inep_id,id.name, id.birthday_date, iv.scholarity
                from (((instructor_teaching_data as i join instructor_identification as id on id.id = i.instructor_fk)
                join instructor_variable_data as iv on iv.id = id.id) join classroom as c on i.school_inep_id_fk = c.school_inep_fk)
                WHERE  c.school_inep_fk = ".$_GET['id']." AND c.school_year = ".$this->year." AND i.role = 2 order by id.name";

        $professor = Yii::app()->db->createCommand($sql1)->queryAll();

        $this->render('EducationalAssistantPerClassroomReport', array(
            'school' => $school,
            'professor' => $professor,
            'classroom' => $classrooms
        ));
    }

 public function actionClassroomWithoutInstructorRelationReport(){
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql = "select * from classroom_qtd_students
                where school_year = ".$this->year." and school_inep_fk = ".$_GET['id']." order by name ";

        $classroom = Yii::app()->db->createCommand($sql)->queryAll();

        $sql1 = "SELECT ca.* , c.*, ed.name as discipline_name FROM class_board as ca join classroom_qtd_students as c on ca.classroom_fk = c.id join edcenso_discipline ed on ed.id = ca.discipline_fk
                  where c.school_year =  ".$this->year." and c.school_inep_fk = ".$this->year." and ca.instructor_fk is null
                  order by c.name";

        $disciplina =  Yii::app()->db->createCommand($sql1)->queryAll();

        $this->render('ClassroomWithoutInstructorRelationReport',array(
            'school' => $school,
            'classroom' => $classroom,
            'disciplina'=> $disciplina
        ));
    }


    public function actionStudentInstructorNumbersRelationReport(){
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

            $sql1 = "select q.*, c.mais_educacao_participator,c.inep_id
                    from classroom as c join classroom_qtd_students as q
                    on c.id = q.id
                    WHERE c.school_year = ".$this->year." AND q.school_inep_fk = ".$_GET['id']." order by q.name";
        $classrooms = Yii::app()->db->createCommand($sql1)->queryAll();

        $sql2 = "SELECT i.role, i.classroom_inep_id,c.id as classroomId
                from instructor_teaching_data as i join classroom as c on i.classroom_id_fk = c.id
                WHERE i.school_inep_id_fk = ".$_GET['id']." and  c.school_year = ".$this->year."";
        $instrutors = Yii::app()->db->createCommand($sql2)->queryAll();

        $this->render('StudentInstructorNumbersRelationReport',array(
            'school' => $school,
            "classroom" => $classrooms,
            "instructor" => $instrutors

        ));
    }

    public function actionSchoolProfessionalNumberByClassroomReport(){
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql = "select c.inep_id, q.*
                from classroom as c join classroom_qtd_students as q
                on c.school_inep_fk = q.school_inep_fk
                where c.school_year = ".$this->year." AND q.school_year = ".$this->year." and c.school_inep_fk = ".$_GET['id']." AND q.school_inep_fk = ".$_GET['id']."  AND c.id = q.id
                order by c.name";

        $classrooms = Yii::app()->db->createCommand($sql)->queryAll();

        $sql1 = "SELECT i.role, i.classroom_inep_id,c.name
                 from instructor_teaching_data as i join classroom as c on i.classroom_id_fk = c.id
                 WHERE i.school_inep_id_fk = ".$_GET['id']." and  c.school_year = ".$this->year."";

        $role =  Yii::app()->db->createCommand($sql1)->queryAll();

        $this->render('SchoolProfessionalNumberByClassroomReport', array(
            'school' => $school,
            'role' => $role,
            "classroom" => $classrooms
        ));
    }

    public function actionEnrollmentComparativeAnalysisReport(){
        $school = SchoolIdentification::model()->findByPk($_GET['id']);
        $sql = "SELECT * FROM classroom_qtd_students
                    where `school_year` >= ".$this->year."-1 and school_inep_fk = ".$_GET['id']." order by name;";

        $classrooms = Yii::app()->db->createCommand($sql)->queryAll();

        $sql1 = "select `c`.`id` AS `classe_id` , count(`s`.`id`) AS `contador`
                 from ((`student_identification` `s` join `student_enrollment` `se` on((`s`.`id` = `se`.`student_fk`)))
                 join `classroom` `c` on((`se`.`classroom_fk` = `c`.`id`)))
                 where `c`.`school_year` = ".$this->year."-1 and school_inep_fk = ".$_GET['id']."
                 GROUP BY `c`.`id`";

        $enrollment1 = Yii::app()->db->createCommand($sql1)->queryAll();

        $sql2 = "select `c`.`id` AS `classe_id` , count(`s`.`id`) AS `contador`
                 from ((`student_identification` `s` join `student_enrollment` `se` on((`s`.`id` = `se`.`student_fk`)))
                 join `classroom` `c` on((`se`.`classroom_fk` = `c`.`id`)))
                 where `c`.`school_year` = ".$this->year." and school_inep_fk = ".$_GET['id']."
                 GROUP BY `c`.`id`";

        $enrollment2 = Yii::app()->db->createCommand($sql2)->queryAll();

        $this->render('EnrollmentComparativeAnalysisReport', array(
            'classrooms' => $classrooms,
            'school' => $school,
            'matricula1' => $enrollment1,
            'matricula2' => $enrollment2
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
        $sql = "SELECT * FROM classroom_qtd_students
                    where school_year  = ".$this->year." and school_inep_fk=".Yii::app()->user->school." order by name;";
       
        $result = Yii::app()->db->createCommand($sql)->queryAll();
                
        $this->render('NumberStudentsPerClassroomReport', array(
            'report' => $result,
        ));          
    }
    
    public function actionInstructorsPerClassroomReport() {
        $sql = "SELECT * FROM classroom_instructors "
                . "where school_year = ".$this->year." and school_inep_fk= ".Yii::app()->user->school." order by name;";

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
    
    public function actionGetStudentsFileBoquimInformation($enrollment_id){
        $sql = "SELECT * FROM studentsfile_boquim WHERE enrollment_id = ".$enrollment_id.";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        
        echo json_encode($result);
    }
    
    public function actionStudentsFileBoquimReport($enrollment_id) {
        $this->layout = "reports";
        $this->render('StudentsFileBoquimReport', array('enrollment_id'=>$enrollment_id));
    }
    
    public function actionEnrollmentDeclarationReport($enrollment_id) {
        $this->layout = "reports";
        $sql = "SELECT si.sex gender, svm.stage stage, svm.id class"
                . " FROM student_enrollment se JOIN student_identification si ON si.id = se.student_fk JOIN classroom c on se.classroom_fk = c.id JOIN edcenso_stage_vs_modality svm ON c.edcenso_stage_vs_modality_fk = svm.id"
                . " WHERE se.id = " . $enrollment_id . ";";
        $response = Yii::app()->db->createCommand($sql)->queryRow();
        $this->render('EnrollmentDeclarationReport', array('enrollment_id'=>$enrollment_id, 'gender'=>$response['gender'], 'stage'=>$response['stage'], 'class'=>$response['class']));         
    }
    
    public function actionGetEnrollmentDeclarationInformation($enrollment_id){
        $sql = "SELECT si.name name, si.filiation_1 filiation_1, si.filiation_2 filiation_2, si.birthday birthday, si.inep_id inep_id, si.nis nis, ec.name city, YEAR(se.create_date) enrollment_date"
                . " FROM student_enrollment se JOIN student_identification si ON si.id = se.student_fk JOIN student_documents_and_address sd ON si.id = sd.id JOIN edcenso_city ec ON si.edcenso_city_fk = ec.id"
                . " WHERE se.id = " . $enrollment_id . ";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        
        echo json_encode($result);        
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
            
            //$report[$student]['Classes'][$month] = $faults/$count or N/A
            //@done s3 - Calcular frequência para cada aluno: (Total de horários - faltas do aluno) / (Total de horários - Dias não ministrados)
            
            $report[$student]['Classes'][$month]  = 
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
                $report[$name]['Classes'][$i] = isset($c['Classes'][$i]) ? $c['Classes'][$i] : ('N/A');
            }
        }

        $this->render('BFReport', array(
            'report' => $report,
        ));
    }

    public function actionTransferForm($enrollment_id){
        $this->layout = 'reports';
        $sql = "SELECT si.nationality FROM student_identification si JOIN student_enrollment se ON se.student_fk = si.id WHERE se.id = " . $enrollment_id . ";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        $this->render('TransferForm', array('enrollment_id'=>$enrollment_id, 'nationality'=>$result['nationality']));
    }
    
    public function actionGetTransferFormInformation($enrollment_id){
        //nacionalidade vem de que tabela?
        
        $sql = "SELECT si.name, si.inep_id, ec.name birth_city, euf.acronym birth_state,  
                    si.birthday, sda.rg_number, sda.rg_number_expediction_date rg_date, 
                    eoe.name rg_emitter, euf.acronym rg_uf, 
                    sda.civil_certification_term_number civil_certification, sda.civil_certification_sheet, sda.civil_certification_book, 
                    ec.name civil_certification_city, euf.acronym civil_certification_uf, 
                    si.filiation_2 filiation_2, si.filiation_1 filiation_1
                FROM student_identification si
                    JOIN student_enrollment se ON se.student_fk = si.id
                    JOIN student_documents_and_address sda ON sda.student_fk = si.inep_id
                    JOIN edcenso_city ec ON si.edcenso_city_fk = ec.id
                    JOIN edcenso_uf euf ON si.edcenso_uf_fk = euf.id
                    JOIN edcenso_nation en ON si.edcenso_nation_fk = en.id
                    JOIN edcenso_organ_id_emitter eoe ON eoe.id = sda.rg_number_edcenso_organ_id_emitter_fk
                WHERE se.id = " . $enrollment_id . ";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        
        echo json_encode($result);
    }
    
    public function actionEnrollmentNotification($enrollment_id){
        $this->layout = 'reports';
        $sql = "SELECT si.sex gender, cr.turn shift"
                . " FROM student_identification si JOIN student_enrollment se ON se.student_fk = si.id JOIN classroom cr ON se.classroom_fk = cr.id"
                . " WHERE se.id = " . $enrollment_id . ";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        $this->render('EnrollmentNotification', array('enrollment_id'=>$enrollment_id, 'gender'=>$result['gender'], 'shift'=>$result['shift']));
    }
    
    public function actionGetEnrollmentNotificationInformation($enrollment_id){
        $sql = "SELECT si.name name, YEAR(se.create_date) enrollment_date"
                . " FROM student_enrollment se JOIN student_identification si ON si.id = se.student_fk"
                . " WHERE se.id = " . $enrollment_id . ";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        
        echo json_encode($result);
    }
    
    public function actionTransferRequirement($enrollment_id){
        $this->layout = 'reports';
        $sql = "SELECT si.sex gender, svm.stage stage, svm.id class"
                . " FROM student_identification si JOIN student_enrollment se ON se.student_fk = si.id JOIN classroom c on se.classroom_fk = c.id JOIN edcenso_stage_vs_modality svm ON c.edcenso_stage_vs_modality_fk = svm.id"
                . " WHERE se.id = " . $enrollment_id . ";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        $this->render('TransferRequirement', array('enrollment_id'=>$enrollment_id, 'gender'=>$result['gender'], 'stage'=>$result['stage'], 'class'=>$result['class']));
    }
    
    public function actionGetTransferRequirementInformation($enrollment_id){
        $sql = "SELECT si.name name, si.filiation_1 filiation_1, si.filiation_2 filiation_2, si.birthday birthday, ec.name city, euf.acronym state, YEAR(se.create_date) enrollment_date"
                . " FROM student_enrollment se JOIN student_identification si ON si.id = se.student_fk JOIN student_documents_and_address sd ON si.id = sd.id JOIN edcenso_city ec ON si.edcenso_city_fk = ec.id JOIN edcenso_uf euf ON si.edcenso_uf_fk = euf.id"
                . " WHERE se.id = " . $enrollment_id . ";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        
        echo json_encode($result);
    }
    
    public function actionIndex() {
        $this->render('index');
    }

}