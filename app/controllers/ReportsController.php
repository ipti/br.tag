<?php

class ReportsController extends Controller
{

    public $layout = 'reportsclean';
    public $year = 0;

    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'BFReport', 'numberStudentsPerClassroomReport',
                    'InstructorsPerClassroomReport', 'StudentsFileReport',
                    'getStudentsFileInformation', 'ResultBoardReport',
                    'StatisticalDataReport', 'StudentsDeclarationReport',
                    'EnrollmentPerClassroomReport', 'AtaSchoolPerformance',
                    'EnrollmentDeclarationReport', 'TransferForm',
                    'StudentsWithDisabilitiesPerClassroom', 'StudentsWithDisabilitiesPerSchool',
                    'EnrollmentNotification', 'TransferRequirement',
                    'EnrollmentComparativeAnalysisReport', 'SchoolProfessionalNumberByClassroomReport',
                    'ComplementarActivityAssistantByClassroomReport', 'EducationalAssistantPerClassroomReport',
                    'DisciplineAndInstructorRelationReport', 'ClassroomWithoutInstructorRelationReport',
                    'StudentInstructorNumbersRelationReport', 'StudentPendingDocument',
                    'BFRStudentReport', 'ElectronicDiary', 'OutOfTownStudentsReport', 'StudentSpecialFood',
                    'ClassCouncilReport', 'QuarterlyReport', 'GetStudentClassrooms', 'QuarterlyFollowUpReport', 
                    'EvaluationFollowUpStudentsReport', 'CnsPerClassroomReport', 'CnsSchools', 'CnsPerSchool',
                    'TeacherTrainingReport','ClassroomTransferReport', 'SchoolTransferReport', 'AllSchoolsTransferReport',
                    'TeachersByStage', 'TeachersBySchool', 'StatisticalData', 'NumberOfStudentsEnrolledPerPeriodPerClassroom',
                    'NumberOfStudentsEnrolledPerPeriodPerSchool', 'NumberOfStudentsEnrolledPerPeriodAllSchools'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function beforeAction($action)
    {
        if (Yii::app()->user->isGuest) {
            $this->redirect(yii::app()->createUrl('site/login'));
        }

        $this->year = Yii::app()->user->year;

        return true;
    }

    public function actionNumberOfStudentsEnrolledPerPeriodAllSchools()
    {
        $initialDate = $_POST['initial-date'];
        $endDate = $_POST['end-date'];

        $sql = "SELECT si.name, si.birthday, sdaa.cpf, si.responsable_name, 
                    si.responsable_telephone, si2.name as school_name,
                CASE se.status
                WHEN 1 THEN 'Matriculado'
                WHEN 2 THEN 'Transferido'
                WHEN 3 THEN 'Cancelado'
                WHEN 4 THEN 'Deixou de Frequentar'
                WHEN 5 THEN 'Remanejado'
                WHEN 6 THEN 'Aprovado'
                WHEN 7 THEN 'Aprovado pelo Conselho'
                WHEN 8 THEN 'Reprovado'
                WHEN 9 THEN 'Concluinte'
                WHEN 10 THEN 'Indeterminado'
                WHEN 11 THEN 'Obito'
                ELSE ''
                END AS status_descricao
                FROM 
                student_enrollment se
                JOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN classroom c ON se.classroom_fk = c.id
                JOIN school_identification si2 ON c.school_inep_fk = si2.inep_id 
                WHERE c.school_year = :school_year AND se.create_date BETWEEN :initial_date AND :end_date
                ORDER BY si.name;";
        
        $result = Yii::app()->db->createCommand($sql)
        ->bindParam(":school_year", Yii::app()->user->year)
        ->bindParam(":initial_date", $initialDate)
        ->bindParam(":end_date", $endDate)
        ->queryAll();

        $allSchools = true;

        $title = "QUANTITATIVO DE ALUNOS MATRICULADOS POR PERÍODO <br>DE TODAS AS ESCOLAS";

        $this->render('NumberOfStudentsEnrolledPerPeriod', array(
            "report" => $result,
            "title" => $title,
            "allSchools" => $allSchools
        ));
    }

    public function actionNumberOfStudentsEnrolledPerPeriodPerSchool()
    {
        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
        $initialDate = $_POST['initial-date'];
        $endDate = $_POST['end-date'];

        $sql = "SELECT si.name, si.birthday, sdaa.cpf, si.responsable_name, 
                    si.responsable_telephone, c.name as classroom_name,
                CASE se.status
                WHEN 1 THEN 'Matriculado'
                WHEN 2 THEN 'Transferido'
                WHEN 3 THEN 'Cancelado'
                WHEN 4 THEN 'Deixou de Frequentar'
                WHEN 5 THEN 'Remanejado'
                WHEN 6 THEN 'Aprovado'
                WHEN 7 THEN 'Aprovado pelo Conselho'
                WHEN 8 THEN 'Reprovado'
                WHEN 9 THEN 'Concluinte'
                WHEN 10 THEN 'Indeterminado'
                WHEN 11 THEN 'Obito'
                ELSE ''
                END AS status_descricao
                FROM 
                student_enrollment se
                JOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN classroom c ON se.classroom_fk = c.id
                JOIN school_identification si2 ON c.school_inep_fk = si2.inep_id 
                WHERE c.school_year = :school_year AND si2.inep_id = :school_inep_id 
                AND se.create_date BETWEEN :initial_date AND :end_date
                ORDER BY si.name;";
        
        $result = Yii::app()->db->createCommand($sql)
        ->bindParam(":school_year", Yii::app()->user->year)
        ->bindParam(":school_inep_id", $school->inep_id)
        ->bindParam(":initial_date", $initialDate)
        ->bindParam(":end_date", $endDate)
        ->queryAll();

        $allClassrooms = true;

        $title = "QUANTITATIVO DE ALUNOS MATRICULADOS POR PERÍODO<br>".$school->name;

        $this->render('NumberOfStudentsEnrolledPerPeriod', array(
            "report" => $result,
            "title" => $title,
            "allClassrooms" => $allClassrooms
        ));
    }

    public function actionNumberOfStudentsEnrolledPerPeriodPerClassroom()
    {
        $classroom = Classroom::model()->findByPk($_POST['classroom']);
        $initialDate = $_POST['initial-date'];
        $endDate = $_POST['end-date'];

        $sql = "SELECT si.name, si.birthday, sdaa.cpf, si.responsable_name, si.responsable_telephone,
                CASE se.status
                WHEN 1 THEN 'Matriculado'
                WHEN 2 THEN 'Transferido'
                WHEN 3 THEN 'Cancelado'
                WHEN 4 THEN 'Deixou de Frequentar'
                WHEN 5 THEN 'Remanejado'
                WHEN 6 THEN 'Aprovado'
                WHEN 7 THEN 'Aprovado pelo Conselho'
                WHEN 8 THEN 'Reprovado'
                WHEN 9 THEN 'Concluinte'
                WHEN 10 THEN 'Indeterminado'
                WHEN 11 THEN 'Obito'
                ELSE ''
                END AS status_descricao
                FROM 
                student_enrollment se
                JOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN classroom c ON se.classroom_fk = c.id
                WHERE c.id = :classroom AND se.create_date BETWEEN :initial_date AND :end_date
                ORDER BY si.name;";
        
        $result = Yii::app()->db->createCommand($sql)
        ->bindParam(":classroom", $classroom->id)
        ->bindParam(":initial_date", $initialDate)
        ->bindParam(":end_date", $endDate)
        ->queryAll();

        $title = "QUANTITATIVO DE ALUNOS MATRICULADOS POR PERÍODO<br>".$classroom->name;

        $this->render('NumberOfStudentsEnrolledPerPeriod', array(
            "report" => $result,
            "title" => $title
        ));
    }


    public function actionTeacherTrainingReport()
    {
        $classroom = $_POST['classroom'];
        $day = $_POST['count_days'];
        $hour = str_replace(":", "h", $_POST['hour']);
        $year = $_POST['year'];
        $mounth = $_POST['mounth'];
        $quarterly = $_POST['quarterly'];
        $model_report = $_POST['model_report'];
        $school_inep_id = Yii::app()->user->school;
        
        $sql = "SELECT 
                    e.name as school_name, c.name as classroom_name, c.id as classroom_id,
                    s.*, se.status, se.create_date, ii.name as prof_name, ed.name as discipline,
                    c.turn as turno, esvm.stage as stage_id ,esvm.name as class_stage, se.date_cancellation_enrollment as date_cancellation
                FROM
                    student_enrollment as se
                    INNER JOIN classroom as c on se.classroom_fk=c.id
                    INNER JOIN student_identification as s on s.id=se.student_fk
                    INNER JOIN school_identification as e on c.school_inep_fk = e.inep_id
                    INNER JOIN instructor_teaching_data as itd on c.id = itd.classroom_id_fk
                    INNER JOIN teaching_matrixes as tm on itd.id = tm.teaching_data_fk 
                    INNER JOIN curricular_matrix as cm on tm.curricular_matrix_fk = cm.id 
                    INNER JOIN edcenso_discipline as ed on cm.discipline_fk = ed.id 
                    INNER JOIN instructor_identification as ii on itd.instructor_fk = ii.id
                    INNER JOIN edcenso_stage_vs_modality as esvm on c.edcenso_stage_vs_modality_fk = esvm.id 
                WHERE
                    c.school_year = :year AND
                    c.school_inep_fk = :school_inep_id AND
                    c.id = :classroom
                ORDER BY c.id, se.daily_order";
        $result = Yii::app()->db->createCommand($sql)
        ->bindParam(":year", $year)
        ->bindParam(":school_inep_id", $school_inep_id)
        ->bindParam(":classroom", $classroom)
        ->queryAll();

        $disciplines = array();
        foreach ($result as $r) {
            array_push($disciplines, $r['discipline']);
        }
        $disciplines = array_unique($disciplines);

        $title = $model_report."º Ano - Formação de Professores na Modalidade Normal, em Nível Médio";

        $this->render('buzios/TeacherTraining', array(
            "classroom" => $result,
            "count_days" => $day,
            "mounth" => $mounth,
            "hour" => $hour,
            "quarterly" => $quarterly,
            "year" => $year,
            "title" => $title,
            "disciplines" => $disciplines
     ));
    }

    public function actionStatisticalData()
    {
        $sql = "SELECT
                si.name,
                si.inep_id,
                sdaa.cpf,
                sdaa.rg_number,
                si.birthday,
                si.school_inep_id_fk,
                c.edcenso_stage_vs_modality_fk AS stage
                FROM student_identification si 
                JOIN student_documents_and_address sdaa ON sdaa.id = si.id
                JOIN student_enrollment se ON se.student_fk = si.id
                JOIN classroom c ON se.classroom_fk = c.id 
                WHERE c.school_year = :school_year
                GROUP BY si.id
                ORDER BY si.name;";
        $students = Yii::app()->db->createCommand($sql)
        ->bindParam(":school_year", Yii::app()->user->year)
        ->queryAll();

        $stages = EdcensoStageVsModality::model()->findAll();
        $result = [];
        foreach ($stages as $stage) {
            $studentsByStage = array_filter($students, function ($student) use ($stage) {
                return $student['stage'] == $stage->id;
            });
            array_push($result, ["stage" => $stage, "students" => $studentsByStage]);
        }

        $this->render('StatisticalData', array(
            "report" => $result
        ));
    }

    public function actionClassroomTransferReport()
    {
        $classroom_id = $_POST['classroom'];
        $sql = "SELECT si.name, c.name AS classroom_name, si2.name AS school_name, 
                sdaa.cpf, si.responsable_name, si.responsable_telephone, se.transfer_date  
                FROM student_enrollment se 
                JOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN classroom c ON se.classroom_fk = c.id
                JOIN school_identification si2 ON c.school_inep_fk = si2.inep_id 
                WHERE c.id = :classroom_id AND se.transfer_date IS NOT NULL;";
        $result =  Yii::app()->db->createCommand($sql)
        ->bindParam(":classroom_id", $classroom_id)
        ->queryAll();

        $title = "RELATÓRIO TRANSFERÊNCIA DA TURMA";
        $header = $result[0]['classroom_name'];

        $this->render('TransferReport', array(
            "report" => $result,
            "title" => $title,
            "header" => $header
        ));
    }

    public function actionSchoolTransferReport()
    {
        $sql = "SELECT si.name, c.name AS classroom_name, si2.name AS school_name, 
                sdaa.cpf, si.responsable_name, si.responsable_telephone, se.transfer_date  
                FROM student_enrollment se 
                JOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN classroom c ON se.classroom_fk = c.id
                JOIN school_identification si2 ON c.school_inep_fk = si2.inep_id 
                WHERE si2.inep_id = :school_inep_id AND se.transfer_date IS NOT NULL;";
        $result =  Yii::app()->db->createCommand($sql)
        ->bindParam(":school_inep_id", Yii::app()->user->school)
        ->queryAll();

        $title = "RELATÓRIO TRANSFERÊNCIA DA ESCOLA";
        $header = $result[0]['school_name'];

        $this->render('TransferReport', array(
            "report" => $result,
            "title" => $title,
            "header" => $header
        ));
    }

    public function actionAllSchoolsTransferReport()
    {
        $sql = "SELECT si.name, c.name AS classroom_name, si2.name AS school_name, 
                sdaa.cpf, si.responsable_name, si.responsable_telephone, se.transfer_date  
                FROM student_enrollment se 
                JOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN classroom c ON se.classroom_fk = c.id
                JOIN school_identification si2 ON c.school_inep_fk = si2.inep_id 
                WHERE se.transfer_date IS NOT NULL;";
        $result =  Yii::app()->db->createCommand($sql)->queryAll();

        $title = "RELATÓRIO TRANSFERÊNCIA DE TODAS AS ESCOLAS";
        $header = '';

        $this->render('TransferReport', array(
            "report" => $result,
            "title" => $title,
            "header" => $header
        ));
    }
    public function actionTeachersByStage()
    {
        $sql = "SELECT 
                ii.name,
                ii.birthday_date,
                ii.inep_id,
                ivd.scholarity,
                ii.school_inep_id_fk,
                c.edcenso_stage_vs_modality_fk AS stage
                FROM instructor_teaching_data itd 
                JOIN instructor_identification ii on ii.id = itd.instructor_fk 
                JOIN instructor_variable_data ivd ON ii.id = ivd.id
                JOIN classroom c ON itd.classroom_id_fk = c.id
                GROUP BY ii.name;";
        $instructors = Yii::app()->db->createCommand($sql)->queryAll();

        $stages = EdcensoStageVsModality::model()->findAll();
        $result = [];
        foreach ($stages as $stage) {
            $instructorByStage = array_filter($instructors, function ($instructor) use ($stage) {
                return $instructor['stage'] == $stage->id;
            });
            array_push($result, ["stage" => $stage, "instructors" => $instructorByStage]);
        }

        $this->render('TeachersByStage', array(
            "report" => $result
        ));
    }

    public function actionTeachersBySchool()
    {
        $sql = "SELECT 
                ii.name,
                ii.birthday_date,
                ii.inep_id,
                ivd.scholarity,
                ii.school_inep_id_fk
            FROM instructor_identification ii
            JOIN instructor_variable_data ivd ON ii.id = ivd.id
            GROUP BY ii.name
            ORDER BY ii.name;";
        $instructors = Yii::app()->db->createCommand($sql)->queryAll();

        $schools = SchoolIdentification::model()->findAll();
        $result = [];
        foreach ($schools as $school) {
            $instructorBySchool = array_filter($instructors, function ($instructor) use ($school) {
                return $instructor['school_inep_id_fk'] == $school->inep_id;
            });
            array_push($result, ["school" => $school, "instructors" => $instructorBySchool]);
        }

        $this->render('TeachersBySchool', array(
            "report" => $result
        ));
    }

    public function actionCnsPerClassroomReport()
    {
        $classroom_id = $_POST['cns_classroom_id'];
        $sql = "SELECT 
                si.name, si.birthday, sdaa.cns, c.name AS classroom_name,
                si.responsable_name, si.responsable_telephone
                FROM student_enrollment se
                JOIN classroom c ON se.classroom_fk = c.id
                JOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.inep_id = sdaa.student_fk 
                WHERE c.id = :classroom_id
                GROUP BY name;";

        $result = Yii::app()->db->createCommand($sql)
        ->bindParam(":classroom_id", $classroom_id)
        ->queryAll();

        $title = "RELATÓRIO CNS DA TURMA";
        $header = $result[0]['classroom_name'];
        
        $this->render('CnsReport', array(
            "report" => $result,
            "title" => $title,
            "header" => $header
        ));
    }

    public function actionCnsSchools()
    {
        $sql = "SELECT 
        si2.name AS school_name, si.name, si.birthday, sdaa.cns,
        si.responsable_name, si.responsable_telephone
        FROM student_enrollment se 
        JOIN classroom c ON se.classroom_fk = c.id
        JOIN school_identification si2 ON c.school_inep_fk = si2.inep_id 
        JOIN student_identification si ON se.student_fk = si.id
        JOIN student_documents_and_address sdaa ON si.inep_id = sdaa.student_fk
        WHERE c.school_year = :year
        GROUP BY name
        ORDER BY si2.inep_id;";

        $result =  Yii::app()->db->createCommand($sql)
        ->bindParam(":year", Yii::app()->user->year)
        ->queryAll();
        $allSchools = true;
        $countTotal = true;
        $title = "RELATÓRIO CNS ESCOLAS";
        $this->render('CnsReport', array(
            "report" => $result,
            "title" => $title,
            "allSchools" => $allSchools,
            "countTotal" => $countTotal
        ));
    }

    public function actionCnsPerSchool()
    {
        $sql = "SELECT 
                sch.name AS school_name, si.name, si.birthday, sdaa.cns,
                si.responsable_name, si.responsable_telephone
                FROM school_identification sch
                JOIN student_enrollment se ON se.school_inep_id_fk = sch.inep_id
                JOIN classroom c ON se.classroom_fk = c.id
                JOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.inep_id = sdaa.student_fk
                WHERE sch.inep_id = :school_id AND c.school_year = :year
                GROUP BY name;";

        $result =  Yii::app()->db->createCommand($sql)
        ->bindParam(":school_id", Yii::app()->user->school)
        ->bindParam(":year", Yii::app()->user->year)
        ->queryAll();

        $countTotal = true;
        $title = "RELATÓRIO CNS DA ESCOLA";
        $header = $result[0]['school_name'];

        $this->render('CnsReport', array(
            "report" => $result,
            "title" => $title,
            "header" => $header,
            "countTotal" => $countTotal
        ));
    }

    public function actionQuarterlyReport()
    {
        if (isset($_POST['student']) && isset($_POST['quartely_report_classroom_student']) && isset($_POST['model_quartely'])) {
            $student_id = $_POST['student'];
            $classroom_id = $_POST['quartely_report_classroom_student'];
            $model = $_POST['model_quartely'];
            $student_identification = StudentIdentification::model()->findByPk($student_id);
            $student_enrollment = StudentEnrollment::model()->findByAttributes(array('student_fk' => $student_id));
            $classroom = Classroom::model()->findByPk($classroom_id);
            $classroom_etapa = EdcensoStageVsModality::model()->findByPk($classroom->edcenso_stage_vs_modality_fk);
            $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
            $current_year = Yii::app()->user->year;

            // Verificação de Formato de data
            $dateFormatCorrect = false;
            $date_str = $student_identification->birthday;
            $date_format = 'd/m/Y';
            // Cria um objeto DateTime a partir da string de data e formato
            $date = DateTime::createFromFormat($date_format, $date_str);
            // Verifica se a string de data original corresponde ao formato esperado
            if ($date && $date->format($date_format) === $date_str) {
                $dateFormatCorrect = true;
            }

            $sql = "SELECT ii.name as instructor_name FROM classroom c
                    JOIN instructor_teaching_data itd on itd.classroom_id_fk = c.id 
                    JOIN instructor_identification ii on itd.instructor_fk = ii.id
                    WHERE c.id = :classroom_id AND itd.regent = 1;";

            $regentTeachers = Yii::app()->db->createCommand($sql)
            ->bindParam(":classroom_id", $classroom->id)
            ->queryAll();

            // Modelos de Relatório Trimestral
            if ($model == 1) { // 1º ANO
                $this->render('buzios/quarterly/QuarterlyReportFirstYear', array(
                    "student_identification" => $student_identification,
                    "student_enrollment" => $student_enrollment,
                    "classroom" => $classroom,
                    "school" => $school,
                    "current_year" => $current_year,
                    "regentTeachers" => $regentTeachers
                ));
            } else if ($model == 2) { // 2º ANO
                $this->render('buzios/quarterly/QuarterlyReportSecondYear', array(
                    "student_identification" => $student_identification,
                    "student_enrollment" => $student_enrollment,
                    "classroom" => $classroom,
                    "school" => $school,
                    "current_year" => $current_year,
                    "regentTeachers" => $regentTeachers
                ));
            } else if ($model == 3) { // 3º ANO
                $this->render('buzios/quarterly/QuarterlyReportThreeYear', array(
                    "student_identification" => $student_identification,
                    "student_enrollment" => $student_enrollment,
                    "classroom" => $classroom,
                    "school" => $school,
                    "current_year" => $current_year,
                    "regentTeachers" => $regentTeachers
                ));
            } else if ($model == 4) { // CRECHE II
                $this->render('buzios/quarterly/QuarterlyReportNurseryrII', array(
                    "student_identification" => $student_identification,
                    "student_enrollment" => $student_enrollment,
                    "classroom" => $classroom,
                    "classroom_etapa" => $classroom_etapa,
                    "school" => $school,
                    "current_year" => $current_year,
                    "dateFormatCorrect" => $dateFormatCorrect,
                    "regentTeachers" => $regentTeachers
                ));
            } else if ($model == 5) { // CRECHE III
                $this->render('buzios/quarterly/QuarterlyReportNurseryrIII', array(
                    "student_identification" => $student_identification,
                    "student_enrollment" => $student_enrollment,
                    "classroom" => $classroom,
                    "classroom_etapa" => $classroom_etapa,
                    "school" => $school,
                    "current_year" => $current_year,
                    "dateFormatCorrect" => $dateFormatCorrect,
                    "regentTeachers" => $regentTeachers
                ));
            } else if ($model == 6) { // CRECHE IV
                $this->render('buzios/quarterly/QuarterlyReportNurseryrIV', array(
                    "student_identification" => $student_identification,
                    "student_enrollment" => $student_enrollment,
                    "classroom" => $classroom,
                    "classroom_etapa" => $classroom_etapa,
                    "school" => $school,
                    "current_year" => $current_year,
                    "dateFormatCorrect" => $dateFormatCorrect,
                    "regentTeachers" => $regentTeachers
                ));
            } else if ($model == 7) { // PRÉ I
                $this->render('buzios/quarterly/QuarterlyReportPreI', array(
                    "student_identification" => $student_identification,
                    "student_enrollment" => $student_enrollment,
                    "classroom" => $classroom,
                    "classroom_etapa" => $classroom_etapa,
                    "school" => $school,
                    "current_year" => $current_year,
                    "dateFormatCorrect" => $dateFormatCorrect,
                    "regentTeachers" => $regentTeachers
                ));
            } else if ($model == 8) { // PRÉ II
                $this->render('buzios/quarterly/QuarterlyReportPreII', array(
                    "student_identification" => $student_identification,
                    "student_enrollment" => $student_enrollment,
                    "classroom" => $classroom,
                    "classroom_etapa" => $classroom_etapa,
                    "school" => $school,
                    "current_year" => $current_year,
                    "dateFormatCorrect" => $dateFormatCorrect,
                    "regentTeachers" => $regentTeachers
                ));
            }
        }
        Yii::app()->user->setFlash('error', Yii::t('default', 'Selecione ao menos uma opção'));
        return $this->redirect(array('index'));
    }

    public function actionQuarterlyFollowUpReport()
    {
        $classroom_id = $_POST['quarterly_follow_up_classroom'];
        $discipline_id = $_POST['quarterly_follow_up_disciplines'];
        $trimestre = $_POST['quarterly'];
        
        $classroom_model = Classroom::model()->findByPk($classroom_id);
        $discipline_model = EdcensoDiscipline::model()->findByPk($discipline_id);
        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);

        $sql = "SELECT ii.name AS instructor_name, ed.name AS discipline_name, c.name AS classroom_name, c.turn as classroom_turn 
                FROM edcenso_discipline ed
                JOIN curricular_matrix cm ON cm.discipline_fk = ed.id
                JOIN teaching_matrixes tm ON tm.curricular_matrix_fk = cm.id 
                JOIN instructor_teaching_data itd ON itd.id = tm.teaching_data_fk 
                JOIN classroom c ON c.id = itd.classroom_id_fk 
                JOIN instructor_identification ii ON itd.instructor_fk = ii.id
                WHERE ed.id = :discipline_id AND c.id = :classroom_id
                GROUP BY ii.name;";

        $result = Yii::app()->db->createCommand($sql)
        ->bindParam(":discipline_id", $discipline_id)
        ->bindParam(":classroom_id", $classroom_id)
        ->queryAll();
        $turno =  $result[0]['classroom_turn'];
        if ($turno == 'M') {
            $turno = "Matutino";
        }else if ($turno == 'T') {
            $turno = "Vesperitino";
        }else if ($turno == 'N') {
            $turno = "Noturno";
        }else if ($turno == '' || $turno == null) {
            $turno = "___________";
        }

        $sql = "SELECT si.name AS student_name, se.daily_order FROM classroom c 
                JOIN student_enrollment se on c.id = se.classroom_fk 
                JOIN student_identification si on se.student_fk = si.id 
                WHERE c.id = :classroom_id
                ORDER BY se.daily_order, si.name;";

        $students = Yii::app()->db->createCommand($sql)->bindParam(":classroom_id", $classroom_id)->queryAll();

        $classroom_stage_name = $classroom_model->edcensoStageVsModalityFk->name;
        $parts = explode("-", $classroom_stage_name);
        $stage_name = trim($parts[1]);

        $anos1 = array("1º", "2º", "3º");
        $anos2 = array("4º", "5º");

        $anosTitulo = '';
        $anosVerify = 0;
        $anosPosition = 0;
        $stageVerify = false;

        for ($i=0; $i < count($anos1); $i++) { 
            if (strpos($stage_name, $anos1[$i]) !== false) {
                $anosTitulo = "1º, 2º e 3º ANOS";
                $anosVerify = 1;
                $anosPosition = $i + 1;
                $stageVerify = true;
                break;
            }
        }
        for ($i=0; $i < count($anos2); $i++) { 
            if (strpos($stage_name, $anos2[$i]) !== false) {
                $anosTitulo = "4º E 5º ANOS";
                $anosVerify = 2;
                $anosPosition = $i + 4;
                $stageVerify = true;
                break;
            }
        }

        if(!$stageVerify) {
            Yii::app()->user->setFlash('error', Yii::t('default', "A turma ".$classroom_model->name." não possui uma etapa correspondente ao relatório. Etapa da Turma: ".$classroom_stage_name));
            return $this->redirect(array('index'));
        }

        if($result) {
            $this->render('buzios/quarterly/QuarterlyFollowUpReport', array(
                "report" => $result,
                "school" => $school,
                "turno" => $turno,
                "trimestre" => $trimestre,
                "students" => $students,
                "classroom" => $classroom_model,
                "anosTitulo" => $anosTitulo,
                "anosVerify" => $anosVerify,
                "anosPosition" => $anosPosition,
                "stage_name" => $stage_name
            ));
        }else {
            Yii::app()->user->setFlash('error', Yii::t('default', "A turma ".$classroom_model->name." não possui professores para a disciplina de ".$discipline_model->name));
            return $this->redirect(array('index'));
        }
    }

    public function actionEvaluationFollowUpStudentsReport()
    {
        $discipline_id = $_POST['evaluation_follow_up_disciplines'];
        $classroom_id = $_POST['evaluation_follow_up_classroom'];
        $quarterly = $_POST['quarterly'];

        $classroom = Classroom::model()->findByPk($classroom_id);
        $discipline = EdcensoDiscipline::model()->findByPk($discipline_id);

        $sql = "SELECT ii.name AS instructor_name, ed.name AS discipline_name
                FROM edcenso_discipline ed
                JOIN curricular_matrix cm ON cm.discipline_fk = ed.id
                JOIN teaching_matrixes tm ON tm.curricular_matrix_fk = cm.id 
                JOIN instructor_teaching_data itd ON itd.id = tm.teaching_data_fk 
                JOIN classroom c ON c.id = itd.classroom_id_fk 
                JOIN instructor_identification ii ON itd.instructor_fk = ii.id
                WHERE ed.id = :discipline_id AND c.id = :classroom_id
                GROUP BY ii.name;";

        $instructor = Yii::app()->db->createCommand($sql)
        ->bindParam(":discipline_id", $discipline_id)
        ->bindParam(":classroom_id", $classroom_id)
        ->queryAll();

        $sql = "SELECT si.name AS student_name FROM student_enrollment se 
                JOIN student_identification si on si.id = se.student_fk
                WHERE se.classroom_fk = :classroom_id
                ORDER BY se.daily_order, si.name;";

        $students = Yii::app()->db->createCommand($sql)->bindParam(":classroom_id", $classroom_id)->queryAll();

        $classroom_stage_name = $classroom->edcensoStageVsModalityFk->name;
        $parts = explode("-", $classroom_stage_name);
        $stage_name = trim($parts[1]);

        $anos1 = array("1º", "2º", "3º");
        $anos2 = array("4º", "5º");

        $anosTitulo = '';
        $anosVerify = 0;
        $anosPosition = 0;
        $stageVerify = false;

        for ($i=0; $i < count($anos1); $i++) { 
            if (strpos($stage_name, $anos1[$i]) !== false) {
                $anosTitulo = "1º, 2º e 3º ANOS";
                $anosVerify = 1;
                $anosPosition = $i + 1;
                $stageVerify = true;
                break;
            }
        }
        for ($i=0; $i < count($anos2); $i++) { 
            if (strpos($stage_name, $anos2[$i]) !== false) {
                $anosTitulo = "4º E 5º ANOS";
                $anosVerify = 2;
                $anosPosition = $i + 4;
                $stageVerify = true;
                break;
            }
        }

        if(!$stageVerify) {
            Yii::app()->user->setFlash('error', Yii::t('default', "A turma ".$classroom->name." não possui uma etapa correspondente ao relatório. Etapa da Turma: ".$classroom_stage_name));
            return $this->redirect(array('index'));
        }

        if($instructor) {
            if($students) {
                $this->render('buzios/quarterly/EvaluationFollowUpReport', array(
                    "instructor" => $instructor,
                    "students" => $students,
                    "classroom" => $classroom,
                    "discipline" => $discipline,
                    "anosTitulo" => $anosTitulo,
                    "anosVerify" => $anosVerify,
                    "anosPosition" => $anosPosition,
                    "quarterly" => $quarterly
                ));
            }else {
                Yii::app()->user->setFlash('error', Yii::t('default', "A turma ".$classroom->name." não possui alunos matriculados"));
                return $this->redirect(array('index'));
            }
        }else {
            Yii::app()->user->setFlash('error', Yii::t('default', "A turma ".$classroom->name." não possui professores para a disciplina de ".$discipline->name));
            return $this->redirect(array('index'));
        }
    }

    public function actionGetStudentClassrooms($id)
    {
        $classroom = Classroom::model()->findByPk($id);
        $enrollments = $classroom->studentEnrollments;
        foreach ($enrollments as $enrollment) {
            echo "<option value='" . $enrollment->studentFk->id . "'>" . htmlspecialchars($enrollment->studentFk->name, ENT_QUOTES, 'UTF-8') . "</option>";
        }
    }

    public function actionClassCouncilReport()
    {
        $count_days = $_POST['count_days'];
        $mounth = $_POST['mounth'];
        $hour = str_replace(":", "h", $_POST['hour']);
        $quarterly = $_POST['quarterly'];
        $school_inep_id = Yii::app()->user->school;
        $infantil = $_POST['infantil-model'];
        $year = $_POST['year'];
        $condition = '';
        if (isset($_POST['classroom2']) && $_POST['classroom2'] != '') {
            $condition = " AND c.id = $_POST[classroom2] ";
            $sql = "SELECT 
                    e.name as school_name, c.name as classroom_name, c.id as classroom_id,
                    s.*, se.status, se.create_date, ii.name as prof_name, ed.name as discipline,
                    c.turn as turno, esvm.stage as stage_id ,esvm.name as class_stage, se.date_cancellation_enrollment as date_cancellation
                FROM
                    student_enrollment as se
                    INNER JOIN classroom as c on se.classroom_fk=c.id
                    INNER JOIN student_identification as s on s.id=se.student_fk
                    INNER JOIN school_identification as e on c.school_inep_fk = e.inep_id
                    INNER JOIN instructor_teaching_data as itd on c.id = itd.classroom_id_fk
                    INNER JOIN teaching_matrixes as tm on itd.id = tm.teaching_data_fk 
                    INNER JOIN curricular_matrix as cm on tm.curricular_matrix_fk = cm.id 
                    INNER JOIN edcenso_discipline as ed on cm.discipline_fk = ed.id 
                    INNER JOIN instructor_identification as ii on itd.instructor_fk = ii.id
                    INNER JOIN edcenso_stage_vs_modality as esvm on c.edcenso_stage_vs_modality_fk = esvm.id 
                WHERE
                    c.school_year = :year AND
                    c.school_inep_fk = :school_inep_id
                    $condition
                ORDER BY c.id, se.daily_order";

            $classrooms = Yii::app()->db->createCommand($sql)->bindParam(":year", $year)->bindParam(":school_inep_id", $school_inep_id)->queryAll();

            $stage_id = $classrooms[0]['stage_id'];
            $current_report = 0;
            if ($stage_id == 1 || $stage_id == 2) {
                $current_report = 1;
            }else if ($stage_id == 3) {
                $current_report = 2;
            }else if ($stage_id == 4) {
                $current_report = 3;
            }

            $title = '';
            if($infantil) {
                $title = "EDUCAÇÃO INFANTIL";
            }

            if($classrooms[0] != null) {
                if ($current_report == 1) {
                    $this->render('buzios/quarterly/QuarterlyClassCouncil', array(
                        "classroom" => $classrooms,
                        "count_days" => $count_days,
                        "mounth" => $mounth,
                        "hour" => $hour,
                        "quarterly" => $quarterly,
                        "year" => $year,
                        "title" => $title
                    ));
                }else if ($current_report == 2) {
                    $this->render('buzios/quarterly/QuarterlyClassCouncilSixNineYear', array(
                        "classroom" => $classrooms,
                        "count_days" => $count_days,
                        "mounth" => $mounth,
                        "hour" => $hour,
                        "quarterly" => $quarterly,
                        "year" => $year
                    ));
                }else if ($current_report == 3) {
                    $this->render('buzios/quarterly/QuarterlyClassCouncilHighSchool', array(
                        "classroom" => $classrooms,
                        "count_days" => $count_days,
                        "mounth" => $mounth,
                        "hour" => $hour,
                        "quarterly" => $quarterly,
                        "year" => $year
                    ));
                }
            }else {
                Yii::app()->user->setFlash('error', Yii::t('default', 'Certifique-se de que a turma selecionada tem professores, alunos e disciplinas cadastradas'));
                return $this->redirect(array('index'));
            }
        }
    }

    public function actionStudentsUsingSchoolTransportationRelationReport()
    {
        $school_inep_id = Yii::app()->user->school;
        $year = Yii::app()->user->year;
        $school = SchoolIdentification::model()->findByPk($_GET['id']);
        $sql = "SELECT DISTINCT si.inep_id,si.name,si.birthday,sd.residence_zone, sd.neighborhood, sd.address , se.*
                FROM (student_identification as si join student_enrollment as se on si.id = se.student_fk)
                join classroom as c on se.classroom_fk = c.id
                join student_documents_and_address as sd on si.id = sd.id
                where (se.public_transport = 1 or se.vehicle_type_bus=1) and se.school_inep_id_fk = :school_inep_id
                AND c.school_year = :year AND (se.status = 1 OR se.status IS NULL) order by si.name";

        $students = Yii::app()->db->createCommand($sql)
            ->bindParam(":school_inep_id", $school_inep_id)
            ->bindParam(":year", $year)
            ->queryAll();

        $sql1 = "select c.*, q.modality,q.stage
                from classroom as c join classroom_qtd_students as q
                on c.school_inep_fk = q.school_inep_fk
                where c.school_year = :year AND q.school_year = :year and c.school_inep_fk = :school_inep_id AND q.school_inep_fk = :school_inep_id  AND c.id = q.id
                order by name";

        $classrooms = Yii::app()->db->createCommand($sql1)
            ->bindParam(":school_inep_id", $school_inep_id)
            ->bindParam(":year", $year)
            ->queryAll();

        $this->render('StudentsUsingSchoolTransportationRelationReport', array(
            'school' => $school,
            'students' => $students,
            'classrooms' => $classrooms
        ));
    }

    public function actionStudentsWithDisabilitiesPerClassroom() {
        $classroomId = $_POST['classroom'];

        $sql = "SELECT si.*, se.classroom_fk
        FROM student_identification as si 
        JOIN student_enrollment as se on si.id = se.student_fk
        JOIN classroom as c on se.classroom_fk = c.id
        WHERE si.deficiency = 1 and c.id = $classroomId";

        $sql1 = "SELECT c.*
                FROM classroom as c 
                WHERE c.id = $classroomId";

        $students = Yii::app()->db->createCommand($sql)->queryAll();
        $classroom = Yii::app()->db->createCommand($sql1)->queryAll();

        /* var_dump($students); */

        $this->render('StudentsWithDisabilitiesPerClassroom', array(
            'students' => $students,
            'classroom' => $classroom
        ));
    }

    public function actionStudentsWithDisabilitiesPerSchool() {

        $sql = "SELECT si.*
                FROM student_identification si
                WHERE si.deficiency = 1";

        $students = Yii::app()->db->createCommand($sql)->queryAll();

        $schools = SchoolIdentification::model()->findAll();
        $result = [];
        foreach ($schools as $school) {
            $studentsBySchool = array_filter($students, function ($students) use ($school) {
                return $students['school_inep_id_fk'] == $school->inep_id;
            });
            array_push($result, ["school" => $school, "students" => $studentsBySchool]);
        }
       
        $this->render('StudentsWithDisabilitiesPerSchool', array(
            'students' => $students,
            'schools' => $schools,
            'report' => $result
        ));

        /* $sql = "SELECT 
                ii.name,
                ii.birthday_date,
                ii.inep_id,
                ivd.scholarity,
                ii.school_inep_id_fk
            FROM instructor_identification ii
            JOIN instructor_variable_data ivd ON ii.id = ivd.id
            GROUP BY ii.name
            ORDER BY ii.name;";
        $instructors = Yii::app()->db->createCommand($sql)->queryAll();

        $schools = SchoolIdentification::model()->findAll();
        $result = [];
        foreach ($schools as $school) {
            $instructorBySchool = array_filter($instructors, function ($instructor) use ($school) {
                return $instructor['school_inep_id_fk'] == $school->inep_id;
            });
            array_push($result, ["school" => $school, "instructors" => $instructorBySchool]);
        }

        $this->render('TeachersBySchool', array(
            "report" => $result
        )); */
    }

    public function actionStudentsWithDisabilitiesRelationReport()
    {
        $_GET['id'] = Yii::app()->user->school;
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql = "SELECT si.*, se.classroom_fk
                FROM student_identification as si join student_enrollment as se on si.id = se.student_fk join classroom as c on se.classroom_fk = c.id
                where si.deficiency = 1 and si.school_inep_id_fk = " . $_GET['id'] . " and se.school_inep_id_fk = " . $_GET['id'] . " and c.school_year = " . $this->year . " AND (se.status = 1 OR se.status IS NULL) order by si.name";

        $students = Yii::app()->db->createCommand($sql)->queryAll();

        $sql1 = "select c.*, q.modality,q.stage
                from classroom as c join classroom_qtd_students as q
                on c.school_inep_fk = q.school_inep_fk
                where c.school_year = " . $this->year . " AND q.school_year = " . $this->year . " and c.school_inep_fk = " . $_GET['id'] . " AND q.school_inep_fk = " . $_GET['id'] . "  AND c.id = q.id
                order by name";
        $classrooms = Yii::app()->db->createCommand($sql1)->queryAll();

        $this->render('StudentsWithDisabilitiesRelationReport', array(
            'school' => $school,
            'students' => $students,
            'classrooms' => $classrooms
        ));
    }

    public function actionStudentsInAlphabeticalOrderRelationReport()
    {
        $_GET['id'] = Yii::app()->user->school;
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql = "select si.name as studentName, si.inep_id as studentInepId, se.classroom_inep_id, si.birthday,cq.*
                from (student_identification as si join student_enrollment as se on si.id = se.student_fk)
                join classroom_qtd_students as cq on cq.id = se.classroom_fk
                where se.school_inep_id_fk = " . $_GET['id'] . "  AND si.school_inep_id_fk = " . $_GET['id'] . " AND cq.school_year = " . $this->year . "
                AND (se.status = 1 OR se.status IS NULL)order by si.name";

        $students = Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('StudentsInAlphabeticalOrderRelationReport', array(
            'school' => $school,
            'students' => $students
        ));
    }

    public function actionEnrollmentPerClassroomReport($id)
    {
        $this->layout = "reportsclean";
        $sql = "SELECT * FROM classroom_enrollment
                    where `year`  = " . $this->year . ""
            . " AND classroom_id = $id"
            . " ORDER BY daily_order;";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $classroom = Classroom::model()->findByPk($id);

        $this->render('EnrollmentPerClassroomReport', array(
            'report' => $result,
            'classroom' => $classroom
        ));
    }

    public function actionStudentPendingDocument()
    {
        $school_id = Yii::app()->user->school;
        $school = SchoolIdentification::model()->findByPk($school_id);
        $ano = Yii::app()->user->year;
        $sql1 = "SELECT *, d.name as nome_aluno, d.inep_id as inep_id
                    FROM student_enrollment a 
                    JOIN classroom b ON(a.`classroom_fk`=b.id) 
                    JOIN student_documents_and_address c ON(a.`student_fk`=c.`id`) 
                    JOIN student_identification d ON(c.`id`=d.`id`) 
                    WHERE b.`school_inep_fk` =" . $school_id . " and b.school_year=" . $ano . " AND (a.status = 1 OR a.status IS NULL) AND 
                    (received_cc = 0 OR received_address = 0 OR received_photo = 0 
                    OR received_nis = 0 OR received_responsable_rg = 0 OR received_responsable_cpf = 0)";
        $result = Yii::app()->db->createCommand($sql1)->queryAll();
        $this->render('StudentPendingDocument', array(
            'report' => $result,
        ));
    }

    public function actionStudentPerClassroom()
    {
        $classroom_id = $_POST['classroom'];
        $this->layout = "reports";
        $sql = "SELECT * FROM classroom_enrollment
                    where `year`  = :year"
            . " AND classroom_id = :classroom_id"
            . " AND (status = 1 OR status IS NULL) ORDER BY name;";

        $result = Yii::app()->db->createCommand($sql)
                ->bindParam(':year', $this->year)
                ->bindParam(':classroom_id', $classroom_id)
                ->queryAll();

        $classroom = Classroom::model()->findByPk($id);

        $this->render('StudentPerClassroom', array(
            'report' => $result,
            'classroom' => $classroom
        ));
    }

    public function actionClocPerClassroom($id)
    {
        $this->layout = "reports";
        $sql = "SELECT
        `s`.`id`                                AS `enrollment`,
        `s`.`name`                              AS `name`,
        IF((`s`.`sex` = 1),'M','F')             AS `sex`,
        `s`.`birthday`                          AS `birthday`,
        `s`.`responsable_telephone`             AS `phone`,
        `se`.`current_stage_situation`          AS `situation`,
        `se`.`admission_type`                   AS `admission_type`,
        `se`.`status`                           AS `status`,
        `en`.`acronym`                          AS `nation`,
        `ec`.`name`                             AS `city`,
        `euf`.`acronym`                         AS `uf`,
        `sd`.`address`                          AS `address`,
        `sd`.`number`                           AS `number`,
        `sd`.`complement`                       AS `complement`,
        `sd`.`neighborhood`                     AS `neighborhood`,
        `sd`.`civil_certification`              AS `cc`,
        `sd`.`civil_register_enrollment_number` AS `cc_new`,
        `sd`.`civil_certification_term_number`  AS `cc_number`,
        `sd`.`civil_certification_book`         AS `cc_book`,
        `sd`.`civil_certification_sheet`        AS `cc_sheet`,
        `s`.`filiation_1`                       AS `mother`,
        `s`.`deficiency`                        AS `deficiency`,
        `c`.`id`                                AS `classroom_id`,
        `c`.`school_year`                       AS `year`
      FROM ((((((`student_identification` `s`
              JOIN `student_documents_and_address` `sd`
                ON ((`s`.`id` = `sd`.`id`)))
             LEFT JOIN `edcenso_nation` `en`
               ON ((`s`.`edcenso_nation_fk` = `en`.`id`)))
            LEFT JOIN `edcenso_uf` `euf`
              ON ((`s`.`edcenso_uf_fk` = `euf`.`id`)))
           LEFT JOIN `edcenso_city` `ec`
             ON ((`s`.`edcenso_city_fk` = `ec`.`id`)))
          JOIN `student_enrollment` `se`
            ON ((`s`.`id` = `se`.`student_fk`)))
         JOIN `classroom` `c`
           ON ((`se`.`classroom_fk` = `c`.`id`)))
                    where  `c`.`school_year`  = " . $this->year . ""
            . " AND `c`.`id` = $id"
            . " AND (status = 1 OR status IS NULL) ORDER BY name;";

        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $classroom = Classroom::model()->findByPk($id);

        $this->render('ClocPerClassroom', array(
            'report' => $result,
            'classroom' => $classroom
        ));
    }

    public function actionStudentsByClassroomReport()
    {
        $_GET['id'] = Yii::app()->user->school;
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql = "select c.*, q.modality,q.stage
                from classroom as c join classroom_qtd_students as q
                on c.school_inep_fk = q.school_inep_fk
                where c.school_year = " . $this->year . " AND q.school_year = " . $this->year . " and c.school_inep_fk = " . $_GET['id'] . " AND q.school_inep_fk = " . $_GET['id'] . "  AND c.id = q.id
                order by name";

        $classrooms = Yii::app()->db->createCommand($sql)->queryAll();

        $sql1 = "SELECT DISTINCT se.classroom_fk,si.inep_id,si.name,si.birthday
                FROM student_identification as si 
                join student_enrollment as se on si.id = se.student_fk 
                join classroom as c on se.classroom_fk = c.id
                where se.school_inep_id_fk = " . $_GET['id'] . " and c.school_year = " . $this->year . " AND (se.status = 1 OR se.status IS NULL) order by si.name";

        $students = Yii::app()->db->createCommand($sql1)->queryAll();

        $this->render('StudentsByClassroomReport', array(
            'school' => $school,
            'classroom' => $classrooms,
            'students' => $students
        ));
    }

    public function actionStudentsBetween5And14YearsOldReport()
    {
        $this->layout = "reportsclean";
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql = "select c.*, q.modality,q.stage
                from classroom as c join classroom_qtd_students as q
                on c.school_inep_fk = q.school_inep_fk
                where c.school_year = :year AND q.school_year = :year and c.school_inep_fk = :school AND q.school_inep_fk = :school  AND c.id = q.id AND (q.status = 1 OR q.status IS NULL)
                order by name";

        $classrooms = Yii::app()->db->createCommand($sql)->bindParam(":year", $this->year)->bindParam(":school", $_GET['id'])->queryAll();

        $sql1 = "SELECT se.classroom_fk,si.inep_id,si.name,si.birthday , si.filiation_1, si.filiation_2
                FROM (student_identification as si join student_enrollment as se on si.id = se.student_fk join classroom as c on se.classroom_fk = c.id )
                where se.school_inep_id_fk = :school and c.school_year = :year AND (se.status = 1 OR se.status IS NULL) order by si.name ";

        $students = Yii::app()->db->createCommand($sql1)->bindParam(":year", $this->year)->bindParam(":school", $_GET['id'])->queryAll();

        $this->render('StudentsBetween5And14YearsOldReport', array(
            'school' => $school,
            'classroom' => $classrooms,
            'students' => $students

        ));
    }

    public function actionComplementarActivityAssistantByClassroomReport()
    {
        $_GET['id'] = Yii::app()->user->school;
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql = "select c.*, q.modality,q.stage
                from classroom as c join classroom_qtd_students as q
                on c.school_inep_fk = q.school_inep_fk
                where c.assistance_type = 4 AND c.school_year = " . $this->year . " AND q.school_year = " . $this->year . " and c.school_inep_fk = " . $_GET['id'] . " AND q.school_inep_fk = " . $_GET['id'] . "  AND c.id = q.id
                order by name";

        $classrooms = Yii::app()->db->createCommand($sql)->queryAll();

        $sql = "select id.*,it.classroom_id_fk , iv.scholarity
                from (((instructor_identification as id join instructor_teaching_data as it on it.instructor_fk = id.id)
                join instructor_variable_data as iv on iv.id = id.id) join classroom as c on c.id = it.classroom_id_fk)
                where id.school_inep_id_fk = " . $_GET['id'] . "  AND (it.role = 2 or it.role = 3) AND c.school_year = " . $this->year . " order by c.name";

        $professor = Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('ComplementarActivityAssistantByClassroomReport', array(
            "school" => $school,
            "classroom" => $classrooms,
            'professor' => $professor
        ));
    }

    public function actionDisciplineAndInstructorRelationReport()
    {
        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);

        $classrooms = Yii::app()->db->createCommand(
            "select c.*, q.modality, q.stage from classroom as c 
            join classroom_qtd_students as q on c.school_inep_fk = q.school_inep_fk
            where c.school_year = " . $this->year . " AND q.school_year = " . $this->year . " and c.school_inep_fk = " . Yii::app()->user->school . " AND q.school_inep_fk = " . Yii::app()->user->school . "  AND c.id = q.id
            order by name")->queryAll();
        foreach ($classrooms as &$classroom) {
            $classroom["instructors"] = Yii::app()->db->createCommand(
                "select ii.*, iv.scholarity, it.id as teaching_data_id
                from instructor_teaching_data it
                join instructor_identification ii on ii.id = it.instructor_fk
                left join instructor_variable_data iv on iv.id = ii.id
                where it.classroom_id_fk = '" . $classroom["id"] . "' and it.role = 1 order by ii.name")->queryAll();
            foreach ($classroom["instructors"] as &$instructor) {
                $instructor["disciplines"] = Yii::app()->db->createCommand(
                    "select ed.name
                    from teaching_matrixes tm
                    join curricular_matrix cm on tm.curricular_matrix_fk = cm.id
                    join edcenso_discipline ed on ed.id = cm.discipline_fk
                    where tm.teaching_data_fk = '" . $instructor["teaching_data_id"] . "'")->queryAll();
            }
        }

        $this->render('DisciplineAndInstructorRelationReport', array(
            'school' => $school,
            'classroom' => $classrooms
        ));
    }

    public function actionIncompatibleStudentAgeByClassroomReport()
    {
        $_GET['id'] = Yii::app()->user->school;
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql = "select c.*, q.modality,q.stage
                from classroom as c join classroom_qtd_students as q
                on c.school_inep_fk = q.school_inep_fk
                where c.school_year = " . $this->year . " AND q.school_year = " . $this->year . " and c.school_inep_fk = " . $_GET['id'] . " AND q.school_inep_fk = " . $_GET['id'] . "  AND c.id = q.id
                order by name";

        $classroom = Yii::app()->db->createCommand($sql)->queryAll();

        $sql1 = "SELECT se.classroom_fk,si.inep_id,si.name,si.birthday
                FROM (student_identification as si join student_enrollment as se on si.id = se.student_fk ) join classroom as c on se.classroom_fk = c.id
                where c.school_year = " . $this->year . " AND se.school_inep_id_fk = " . $_GET['id'] . " AND (se.status = 1 OR se.status IS NULL)";

        $students = Yii::app()->db->createCommand($sql1)->queryAll();

        $this->render('IncompatibleStudentAgeByClassroomReport', array(
            'school' => $school,
            'classroom' => $classroom,
            'students' => $students
        ));
    }

    public function actionStudentsWithOtherSchoolEnrollmentReport()
    {
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql1 = "select si.inep_id as student_id , si.name as student_name, si.birthday as student_birthday, s1.school1, s1.school2, c1.name as classroom_name1 from(
                select distinct least(se.school_inep_id_fk, s2.school_inep_id_fk) as school1, greatest(se.school_inep_id_fk, s2.school_inep_id_fk) as school2, se.student_fk, se.id se1, s2.id se2, se.classroom_fk classroom1, s2.classroom_fk classroom2
                from student_enrollment se
                join student_enrollment s2 on se.student_fk = s2.student_fk
                where se.school_inep_id_fk != s2.school_inep_id_fk
                and se.school_inep_id_fk = :school
                ) as s1
                join classroom c1 on(s1.classroom1 = c1.id)
                join classroom c2 on(s1.classroom2 = c2.id)
                join student_identification si on (si.id = s1.student_fk)
                where c1.school_year = :year";

        $students = Yii::app()->db->createCommand($sql1)->bindParam(":school", $_GET['id'])->bindParam(":year", $this->year)->queryAll();

        $this->render('StudentsWithOtherSchoolEnrollmentReport', array(
            'school' => $school,
            'students' => $students
        ));
    }

    public function actionEducationalAssistantPerClassroomReport()
    {
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql = "SELECT c.*, q.modality,q.stage
                FROM classroom as c JOIN classroom_qtd_students AS q
                ON c.school_inep_fk = q.school_inep_fk
                WHERE c.school_year = :year AND q.school_year = :year AND c.school_inep_fk = :school AND q.school_inep_fk = :school  AND c.id = q.id
                ORDER BY name";
        $classrooms = Yii::app()->db->createCommand($sql)->bindParam(":school", $_GET['id'])->bindParam(":year", $this->year)->queryAll();
        foreach ($classrooms as &$classroom) {
            $sql1 = "SELECT DISTINCT c.id AS classroomID ,c.name AS className,id.inep_id,id.name, id.birthday_date, iv.scholarity
                FROM instructor_teaching_data AS i 
                JOIN instructor_identification AS id ON id.id = i.instructor_fk
                JOIN instructor_variable_data AS iv ON iv.id = id.id 
                JOIN classroom AS c ON i.classroom_id_fk = c.id
                WHERE c.id = :id AND (i.role = 8 OR i.role = 2) ORDER BY id.name";
            $classroom["professors"] = Yii::app()->db->createCommand($sql1)->bindParam(":id", $classroom["id"])->queryAll();
        }

        $this->render('EducationalAssistantPerClassroomReport', array(
            'school' => $school,
            'classrooms' => $classrooms
        ));
    }

    public function actionClassroomWithoutInstructorRelationReport()
    {
        $_GET['id'] = Yii::app()->user->school;
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql_classrooms =
            "SELECT 
	            c.name, 
                esvm.name stage
            from classroom c
            left join instructor_teaching_data itd on itd.classroom_id_fk = c.id
            LEFT JOIN edcenso_stage_vs_modality esvm on c.edcenso_stage_vs_modality_fk = esvm.id 
            WHERE school_inep_fk = :school_id and c.school_year = :school_year
            GROUP by c.id 
            HAVING count(itd.id) = 0
            ORDER BY c.id";

        $classroom = Yii::app()->db->createCommand($sql_classrooms)
        ->bindParam(":school_year", $this->year)
        ->bindParam(":school_id", $school->inep_id)
        ->queryAll();

        $sql_discipline = 
                "SELECT 
                    GROUP_CONCAT(ed.name) `Disciplina`
                FROM classroom c 
                LEFT JOIN instructor_teaching_data itd ON itd.classroom_id_fk = c.id
                LEFT JOIN curricular_matrix cm ON cm.stage_fk = c.edcenso_stage_vs_modality_fk 
                LEFT JOIN edcenso_discipline ed ON cm.discipline_fk  = ed.id 
                WHERE school_inep_fk = :school_id AND c.school_year = :school_year
                GROUP by c.id 
                HAVING count(itd.id) = 0
                ORDER BY c.id";

        $disciplina = Yii::app()->db->createCommand($sql_discipline)
          ->bindParam(":school_year", $this->year)
          ->bindParam(":school_id", $school->inep_id)
        ->queryAll();

        $this->render('ClassroomWithoutInstructorRelationReport', array(
            'school' => $school,
            'classroom' => $classroom,
            'disciplina' => $disciplina
        ));
    }

    public function actionStudentInstructorNumbersRelationReport()
    {
        $_GET['id'] = Yii::app()->user->school;
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql1 = "select q.*, c.mais_educacao_participator,c.inep_id
                    from classroom as c join classroom_qtd_students as q
                    on c.id = q.id
                    WHERE c.school_year = " . $this->year . " AND q.school_inep_fk = " . $_GET['id'] . " order by q.name";
        $classrooms = Yii::app()->db->createCommand($sql1)->queryAll();

        $sql2 = "SELECT i.role, i.classroom_inep_id,c.id as classroomId
                from instructor_teaching_data as i join classroom as c on i.classroom_id_fk = c.id
                WHERE i.school_inep_id_fk = " . $_GET['id'] . " and  c.school_year = " . $this->year . "";
        $instrutors = Yii::app()->db->createCommand($sql2)->queryAll();

        $this->render('StudentInstructorNumbersRelationReport', array(
            'school' => $school,
            "classroom" => $classrooms,
            "instructor" => $instrutors

        ));
    }

    public function actionSchoolProfessionalNumberByClassroomReport()
    {
        $_GET['id'] = Yii::app()->user->school;
        $school = SchoolIdentification::model()->findByPk($_GET['id']);

        $sql = "select c.inep_id, q.*
                from classroom as c join classroom_qtd_students as q
                on c.school_inep_fk = q.school_inep_fk
                where c.school_year = " . $this->year . " AND q.school_year = " . $this->year . " and c.school_inep_fk = " . $_GET['id'] . " AND q.school_inep_fk = " . $_GET['id'] . "  AND c.id = q.id
                order by c.name";

        $classrooms = Yii::app()->db->createCommand($sql)->queryAll();

        $sql1 = "SELECT i.role, i.classroom_inep_id,c.name
                 from instructor_teaching_data as i join classroom as c on i.classroom_id_fk = c.id
                 WHERE i.school_inep_id_fk = " . $_GET['id'] . " and  c.school_year = " . $this->year . "";

        $role = Yii::app()->db->createCommand($sql1)->queryAll();

        $this->render('SchoolProfessionalNumberByClassroomReport', array(
            'school' => $school,
            'role' => $role,
            "classroom" => $classrooms
        ));
    }

    public function actionStudentByClassroomReport()
    {
        $school_year = Yii::app()->user->school;
        $year = Yii::app()->user->year;
        $classroom_id = $_POST['classroom'];
        $sql = "SELECT 
                e.name as school_name, c.name as classroom_name, 
                c.id as classroom_id, d.cpf, d.address, s.*
            FROM 
                student_enrollment as se
                INNER JOIN classroom as c on se.classroom_fk=c.id
                INNER JOIN student_identification as s on s.id=se.student_fk
                INNER JOIN school_identification as e on c.school_inep_fk = e.inep_id
                LEFT JOIN student_documents_and_address as d on s.id = d.id
            WHERE 
                c.school_year = :year AND 
                c.school_inep_fk = :schoolyear AND
                c.id = :classroom_id
            GROUP BY s.name
            ORDER BY c.id";

        $classrooms = Yii::app()->db->createCommand($sql)
                    ->bindParam(":year", $year)
                    ->bindParam(":classroom_id", $classroom_id)
                    ->bindParam(":schoolyear", $school_year)
                    ->queryAll();

        $this->render('StudentByClassroomReport', array(
            "classroom" => $classrooms
        ));
    }

    public function actionEnrollmentComparativeAnalysisReport()
    {
        $_GET['id'] = Yii::app()->user->school;
        $school = SchoolIdentification::model()->findByPk($_GET['id']);
        $sql = "SELECT * FROM classroom_qtd_students
                    where `school_year` >= " . $this->year . "-1 and school_inep_fk = " . $_GET['id'] . " order by name;";

        $classrooms = Yii::app()->db->createCommand($sql)->queryAll();

        $sql1 = "select `c`.`id` AS `classe_id` , count(`s`.`id`) AS `contador`
                 from ((`student_identification` `s` join `student_enrollment` `se` on((`s`.`id` = `se`.`student_fk`)))
                 join `classroom` `c` on((`se`.`classroom_fk` = `c`.`id`)))
                 where `c`.`school_year` = " . $this->year . "-1 and school_inep_fk = " . $_GET['id'] . "
                 GROUP BY `c`.`id`";

        $enrollment1 = Yii::app()->db->createCommand($sql1)->queryAll();

        $sql2 = "select `c`.`id` AS `classe_id` , count(`s`.`id`) AS `contador`
                 from ((`student_identification` `s` join `student_enrollment` `se` on((`s`.`id` = `se`.`student_fk`)))
                 join `classroom` `c` on((`se`.`classroom_fk` = `c`.`id`)))
                 where `c`.`school_year` = " . $this->year . " and school_inep_fk = " . $_GET['id'] . "
                 GROUP BY `c`.`id`";

        $enrollment2 = Yii::app()->db->createCommand($sql2)->queryAll();

        $this->render('EnrollmentComparativeAnalysisReport', array(
            'classrooms' => $classrooms,
            'school' => $school,
            'matricula1' => $enrollment1,
            'matricula2' => $enrollment2
        ));
    }

    public function actionStatisticalDataReport()
    {
        $result = null;
        $this->render('StatisticalDataReport', array(
            'report' => $result,
        ));
    }

    public function actionResultBoardReport()
    {
        $result = null;
        $this->render('ResultBoardReport', array(
            'report' => $result,
        ));
    }

    public function actionNumberStudentsPerClassroomReport()
    {
        $this->layout = "reportsclean";
        $sql = "SELECT * FROM classroom_qtd_students
                    where school_year  = " . $this->year . " and school_inep_fk=" . Yii::app()->user->school . " order by name;";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        $this->render('NumberStudentsPerClassroomReport', array(
            'report' => $result,
        ));
    }

    public function actionClocReport()
    {
        $this->layout = "reportsclean";
        $sql = "SELECT
        `c`.`school_inep_fk` AS `school_inep_fk`,
        `c`.`id`             AS `id`,
        `c`.`name`           AS `name`,
        CONCAT_WS(' - ',CONCAT_WS(':',`c`.`initial_hour`,`c`.`initial_minute`),CONCAT_WS(':',`c`.`final_hour`,`c`.`final_minute`)) AS `time`,
        (CASE `c`.`assistance_type` WHEN 0 THEN 'NÃO SE APLICA' WHEN 1 THEN 'CLASSE HOSPITALAR' WHEN 2 THEN 'UNIDADE DE ATENDIMENTO SOCIOEDUCATIVO' WHEN 3 THEN 'UNIDADE PRISIONAL ATIVIDADE COMPLEMENTAR' ELSE 'ATENDIMENTO EDUCACIONALESPECIALIZADO (AEE)' END) AS `assistance_type`,
        (CASE `c`.`modality` WHEN 1 THEN 'REGULAR' WHEN 2 THEN 'ESPECIAL' ELSE 'EJA' END) AS `modality`,
        `esm`.`name`         AS `stage`,
        COUNT(`c`.`id`)      AS `students`,
        `c`.`school_year`    AS `school_year`,
        `se`.`status`        AS `status`
      FROM ((`classroom` `c`
          JOIN `student_enrollment` `se`
            ON ((`c`.`id` = `se`.`classroom_fk`)))
         LEFT JOIN `edcenso_stage_vs_modality` `esm`
           ON ((`c`.`edcenso_stage_vs_modality_fk` = `esm`.`id`)))
      WHERE ((`se`.`status` = 1)
              OR ISNULL(`se`.`status`))
      GROUP BY `c`.`id`";

        $result = Yii::app()->db->createCommand($sql)->queryAll();
        $this->render('NumberStudentsPerClassroomReport', array(
            'report' => $result,
        ));
    }

    public function actionInstructorsPerClassroomReport()
    {
        $this->layout = "reportsclean";
        $sql = "SELECT * FROM classroom_instructors "
            . "where school_year = " . $this->year . " and school_inep_fk= " . Yii::app()->user->school . " order by name;";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        $this->render('InstructorsPerClassroomReport', array(
            'report' => $result,
        ));
    }

    public function actionBFReport()
    {

        $this->layout = "reportsclean";
        //@done s3 - Verificar se a frequencia dos últimos 3 meses foi adicionada(existe pelo menso 1 class cadastrado no mês)
        //@done S3 - Selecionar todas as aulas de todas as turmas ativas dos ultimos 3 meses
        //@done s3 - Pegar todos os alunos matriculados nas turmas atuais.
        //@done s3 - Enviar dados pre-processados para a página.
        $month = (int)date('m');
        $monthI = $month <= 3 ? 1 : $month - 3;
        $monthF = $month <= 1 ? 1 : $month - 1;
        $year = date('Y');

        $groupByClassroom = [];

        //FUNDAMENTAL MENOR
        $arrFields = [":year" => $year, ":school" => Yii::app()->user->school];
        $conditions = " AND c.school_inep_fk = :school";
            $conditions .= " AND c.id = :id_classroom ";
            $arrFields[':id_classroom'] = $_POST['classroom'];
            $criteria = new CDbCriteria;
            $criteria->alias = "c";
            $criteria->join = "join edcenso_stage_vs_modality svm on svm.id = c.edcenso_stage_vs_modality_fk";
            $criteria->condition = "c.school_year = :year and svm.id >= 14 and svm.id <= 16 " . $conditions;
            $criteria->params = $arrFields;
            $criteria->order = "c.name";
            $classrooms = Classroom::model()->findAll($criteria);
            foreach ($classrooms as $classroom) {
                $days = [];
                $faultDays = [];
                $schedules = Schedule::model()->findAll("classroom_fk = :classroom_fk and month >= :monthI and month <= :monthF and unavailable = 0", ["classroom_fk" => $classroom->id, ":monthI" => $monthI, ":monthF" => $monthF]);
                foreach ($schedules as $schedule) {
                    if (!isset($days[$schedule->month])) {
                        $days[$schedule->month] = [];
                    }
                    if (!in_array($schedule->day, $days[$schedule->month])) {
                        array_push($days[$schedule->month], $schedule->day);
                    }
                    foreach ($schedule->classFaults as $classFault) {
                        if (!isset($faultDays[$classFault->studentFk->studentFk->name][$schedule->month])) {
                            $faultDays[$classFault->studentFk->studentFk->name][$schedule->month] = [];
                        }
                        if (!in_array($schedule->day, $faultDays[$classFault->studentFk->studentFk->name][$schedule->month])) {
                            array_push($faultDays[$classFault->studentFk->studentFk->name][$schedule->month], $schedule->day);
                        }
                    }
                }
                foreach ($classroom->studentEnrollments as $studentEnrollment) {
                    for ($i = $monthI; $i <= $monthF; $i++) {
                        $groupByClassroom[$classroom->name][$studentEnrollment->studentFk->name]['Classes'][$i] = isset($days[$i]) ? (floor(((count($days[$i]) - count($faultDays[$studentEnrollment->studentFk->name][$i])) / count($days[$i])) * 100 * 100) / 100) . "%" : "N/A";
                    }
                    $groupByClassroom[$classroom->name][$studentEnrollment->studentFk->name]['Info']["Classroom"] = $classroom->name;
                    $groupByClassroom[$classroom->name][$studentEnrollment->studentFk->name]['Info']["NIS"] = $studentEnrollment->studentFk->documentsFk->nis == null ? "Não Informado" : $studentEnrollment->studentFk->documentsFk->nis;
                    $groupByClassroom[$classroom->name][$studentEnrollment->studentFk->name]['Info']["birthday"] = $studentEnrollment->studentFk->birthday;
                }
            }


            //FUNDAMENTAL MAIOR
            $arrFields = [":year" => $year, ":monthI" => $monthI, ":monthF" => $monthF, ":school" => Yii::app()->user->school];
            $conditions = " AND t.month >= :monthI AND t.month <= :monthF AND t.unavailable = 0 AND c.school_inep_fk = :school";
            $conditions .= " AND c.id = :id_classroom ";
            $arrFields[':id_classroom'] = $_POST['classroom'];

            /* EXEMPLO
            select c.name classroom, si.name student, sd.nis nis, si.birthday, t.month, count(*) count , cf.faults
            from schedule t
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
                AND t.month >= 1
                AND t.month <= 3
                AND t.unavailable = 0
            group by c.id, t.month, si.id
            order by student, month;
            */

            $command = Yii::app()->db->createCommand();
            $command->select = 'c.name classroom, si.name student, sd.nis nis, si.birthday, t.month, count(*) count , cf.faults ';
            $command->from = 'schedule t ';
            $command->join = 'left join classroom c on c.id = t.classroom_fk ';
            $command->join .= 'left join edcenso_stage_vs_modality svm on svm.id = c.edcenso_stage_vs_modality_fk ';
            $command->join .= 'left join student_enrollment se on se.classroom_fk = t.classroom_fk ';
            $command->join .= 'left join student_identification si on se.student_fk = si.id ';
            $command->join .= 'left join student_documents_and_address sd on sd.id = si.id ';
            $command->join .= 'left join (
                SELECT schedule.classroom_fk, schedule.month, student_fk, count(*) faults 
                FROM class_faults cf
                left join schedule on schedule.id = schedule_fk
                group by student_fk, schedule.month,schedule.classroom_fk) cf 
            on (c.id = cf.classroom_fk AND se.student_fk = cf.student_fk AND cf.month = t.month) ';
            $command->where('c.school_year = :year and (svm.id < 14 or svm.id > 16) '
                . $conditions,
                $arrFields);
            $command->group = "c.id, t.month, si.id, cf.faults";
            $command->order = "c.name, student, month";
            $query = $command->queryAll();

            foreach ($query as $result) {
                if ($result['student'] != null) {
                    $count = isset($result['count']) ? $result['count'] : 0;
                    $faults = isset($result['faults']) ? $result['faults'] : 0;
                    $groupByClassroom[$result['classroom']][$result['student']]['Classes'][$result['month']] = ($count == 0) ? ('N/A') : (floor((($count - $faults) / $count) * 100 * 100) / 100) . "%";

                    $groupByClassroom[$result['classroom']][$result['student']]['Info']['Classroom'] = $result['classroom'];
                    $groupByClassroom[$result['classroom']][$result['student']]['Info']['NIS'] = $result['nis'] !== "" && $result['nis'] !== null ? $result['nis'] : "Não Informado";
                    $groupByClassroom[$result['classroom']][$result['student']]['Info']['birthday'] = $result['birthday'];
                }
            }

            $this->render('BFReport', array(
                'reports' => $groupByClassroom,
            ));
    }


    public function actionBFRStudentReport()
    {
        $sql = "SELECT su.name, su.inep_id, su.birthday, cl.name as turma  
        FROM student_enrollment se
        JOIN classroom cl ON(se.classroom_fk = cl.id)
        JOIN school_identification si ON (si.inep_id = cl.school_inep_fk)
        JOIN student_identification su ON(su.id= se.student_fk)
        WHERE
        bf_participator = 1 AND
        si.`inep_id` =" . Yii::app()->user->school . " AND (se.status = 1 OR se.status IS NULL) order by name;";

        $result = Yii::app()->db->createCommand($sql)->queryAll();
        $this->render('BFRStudentReport', array(
            'report' => $result,
        ));
    }


    public function actionIndex()
    {
        $this->layout = "fullmenu";

        $classrooms = Classroom::model()->findAll(array(
            'condition' => 'school_inep_fk=' . Yii::app()->user->school . ' && school_year = ' . Yii::app()->user->year,
            'order' => 'name'
        ));

        $students = StudentIdentification::model()->findAll(array(
            'condition' => 'school_inep_id_fk = ' . Yii::app()->user->school . ' && send_year = ' . Yii::app()->user->year,
            'order' => 'name'
        ));

        $schools = SchoolIdentification::model()->findAll();

        $stages = EdcensoStageVsModality::model()->findAll();

        $this->render('index', ['classrooms' => $classrooms, 'students' => $students, 'schools' => $schools, 'stages' => $stages]);
    }

    public function actionElectronicDiary()
    {
        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $criteria = new CDbCriteria;
            $criteria->alias = "c";
            $criteria->join = ""
                . " join instructor_teaching_data on instructor_teaching_data.classroom_id_fk = c.id "
                . " join instructor_identification on instructor_teaching_data.instructor_fk = instructor_identification.id ";
            $criteria->condition = "c.school_year = :school_year and c.school_inep_fk = :school_inep_fk and instructor_identification.users_fk = :users_fk";
            $criteria->order = "name";
            $criteria->params = array(':school_year' => Yii::app()->user->year, ':school_inep_fk' => Yii::app()->user->school, ':users_fk' => Yii::app()->user->loginInfos->id);

            $classrooms = Classroom::model()->findAll($criteria);
        } else {
            $classrooms = Classroom::model()->findAll('school_year = :school_year and school_inep_fk = :school_inep_fk order by name', ['school_year' => Yii::app()->user->year, 'school_inep_fk' => Yii::app()->user->school]);
        }
        $this->layout = "fullmenu";
        $this->render('ElectronicDiary', array(
            'classrooms' => $classrooms,
            'schoolyear' => Yii::app()->user->year
        ));
    }

    public function actionGetDisciplines()
    {
        $classroom = Classroom::model()->findByPk($_POST["classroom"]);
        $disciplinesLabels = ClassroomController::classroomDisciplineLabelArray();
        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $disciplines = Yii::app()->db->createCommand(
                "select ed.id from teaching_matrixes tm 
                join instructor_teaching_data itd on itd.id = tm.teaching_data_fk 
                join instructor_identification ii on ii.id = itd.instructor_fk
                join curricular_matrix cm on cm.id = tm.curricular_matrix_fk
                join edcenso_discipline ed on ed.id = cm.discipline_fk
                where ii.users_fk = :userid and itd.classroom_id_fk = :crid order by ed.name")
                ->bindParam(":userid", Yii::app()->user->loginInfos->id)->bindParam(":crid", $classroom->id)->queryAll();
            foreach ($disciplines as $discipline) {
                echo htmlspecialchars(CHtml::tag('option', array('value' => $discipline['id']), CHtml::encode($disciplinesLabels[$discipline['id']]), true));
            }
        } else {
            echo CHtml::tag('option', array('value' => ""), CHtml::encode('Selecione...'), true);
            $classr = Yii::app()->db->createCommand("select curricular_matrix.discipline_fk from curricular_matrix where stage_fk = :stage_fk and school_year = :year")->bindParam(":stage_fk", $classroom->edcenso_stage_vs_modality_fk)->bindParam(":year", Yii::app()->user->year)->queryAll();
            foreach ($classr as $i => $discipline) {
                if (isset($discipline['discipline_fk'])) {
                    echo htmlspecialchars(CHtml::tag('option', array('value' => $discipline['discipline_fk']), CHtml::encode($disciplinesLabels[$discipline['discipline_fk']]), true));
                }
            }
        }
    }

    public function actionGetEnrollments()
    {
        $criteria = new CDbCriteria();
        $criteria->alias = "se";
        $criteria->join = "join student_identification si on si.id = se.student_fk";
        $criteria->condition = "classroom_fk = :classroom_fk";
        $criteria->params = array(':classroom_fk' => $_POST["classroom"]);
        $criteria->order = "si.name";
        $studentEnrollments = StudentEnrollment::model()->findAll($criteria);
        echo CHtml::tag('option', array('value' => ""), CHtml::encode('Selecione...'), true);
        foreach ($studentEnrollments as $studentEnrollment) {
            echo htmlspecialchars(CHtml::tag('option', array('value' => $studentEnrollment['id']), $studentEnrollment->studentFk->name, true));
        }
    }

    public function actionGenerateElectronicDiaryReport()
    {
        $result = [];
        if ($_POST["type"] === "frequency") {
            $initial_date = $_POST["initialDate"];
            $final_date = $_POST["finalDate"];
            $fundamental_maior = $_POST["fundamentalMaior"];
            $classroom = $_POST["classroom"];
            $result = $this->frequencyReport($initial_date, $final_date, $fundamental_maior, $classroom);
        } else if ($_POST["type"] === "gradesByStudent") {
            $result = $this->gradesReport($_POST["classroom"], $_POST["student"]);
        }
        echo json_encode($result);
    }

    private function frequencyReport($initial_date, $final_date, $fundamental_maior, $classroom){
        

        $arr = explode('/', $initial_date);
        $initialDate = $arr[2] . "-" . $arr[1] . "-" . $arr[0];
        $arr = explode('/', $final_date);
        $finalDate = $arr[2] . "-" . $arr[1] . "-" . $arr[0];
        $students = [];
        if ($fundamental_maior == "1") {
            $schedules = Schedule::model()
                ->findAll("classroom_fk = :classroom_fk and date_format(concat(" . Yii::app()->user->year . ", '-', month, '-', day), '%Y-%m-%d') between :initial_date and :final_date and discipline_fk = :discipline_fk and unavailable = 0 order by month, day, schedule",
                    ["classroom_fk" => $classroom, "initial_date" => $initialDate, "final_date" => $finalDate, "discipline_fk" => $_POST["discipline"]]);
            if ($schedules !== null) {
                foreach ($schedules[0]->classroomFk->studentEnrollments as $studentEnrollment) {
                    array_push($students, ["id" => $studentEnrollment->student_fk, "name" => $studentEnrollment->studentFk->name, "total" => count($schedules), "faults" => [], "frequency" => ""]);
                }
                foreach ($schedules as $schedule) {
                    foreach ($schedule->classFaults as $classFault) {
                        $key = array_search($classFault->student_fk, array_column($students, 'id'));
                        array_push($students[$key]["faults"], str_pad($schedule["day"], 2, "0", STR_PAD_LEFT) . "/" . str_pad($schedule["month"], 2, "0", STR_PAD_LEFT) . " (" . $schedule["schedule"] . "º Hor.)");
                    }
                }
                foreach ($students as &$student) {
                    $student["frequency"] = (floor((($student["total"] - count($student["faults"])) / $student["total"]) * 100 * 100) / 100) . "%";
                }
            }
        } else {
            $schedules = Schedule::model()
                ->findAll("classroom_fk = :classroom_fk and date_format(concat(" . Yii::app()->user->year . ", '-', month, '-', day), '%Y-%m-%d') between :initial_date and :final_date and unavailable = 0 order by month, day",
                    ["classroom_fk" => $classroom, "initial_date" => $initialDate, "final_date" => $finalDate]);
            if ($schedules !== null) {
                foreach ($schedules[0]->classroomFk->studentEnrollments as $studentEnrollment) {
                    array_push($students, ["id" => $studentEnrollment->student_fk, "name" => $studentEnrollment->studentFk->name, "days" => 0, "faults" => [], "frequency" => ""]);
                }
                $days = [];
                foreach ($schedules as $schedule) {
                    if (!in_array($schedule["day"] . $schedule["month"], $days)) {
                        array_push($days, $schedule["day"] . $schedule["month"]);
                    }
                    foreach ($schedule->classFaults as $classFault) {
                        $key = array_search($classFault->student_fk, array_column($students, 'id'));
                        if (!in_array(str_pad($schedule["day"], 2, "0", STR_PAD_LEFT) . "/" . str_pad($schedule["month"], 2, "0", STR_PAD_LEFT), $students[$key]["faults"])) {
                            array_push($students[$key]["faults"], str_pad($schedule["day"], 2, "0", STR_PAD_LEFT) . "/" . str_pad($schedule["month"], 2, "0", STR_PAD_LEFT));
                        }
                    }
                }
                foreach ($students as &$student) {
                    $student["total"] = count($days);
                    $student["frequency"] = (floor((($student["total"] - count($student["faults"])) / $student["total"]) * 100 * 100) / 100) . "%";
                }
            }
        }
        $col = array_column($students, "name");
        array_multisort($col, SORT_ASC, $students);
        $result["students"] = $students;
        
        return $result;
    }

    private function gradesReport($classroom_id, $student_id){
        $classroom = Classroom::model()
            ->with('edcensoStageVsModalityFk.gradeUnities')
            ->find("t.id = :classroom", [":classroom" => $classroom_id]);

        $gradeUnitiesByClassroom = $classroom->edcensoStageVsModalityFk->gradeUnities; 
        if ($gradeUnitiesByClassroom !== null) {
            $result["isUnityConcept"] = $gradeUnitiesByClassroom[0]->type == "UC";
            $result["unityNames"] = [];
            $result["subunityNames"] = [];

            foreach ($gradeUnitiesByClassroom as $gradeUnity) {
                array_push($result["unityNames"], ["name" => $gradeUnity["name"], "colspan" => $gradeUnity->type == "UR" ? 2 : 1]);
                $commonModalitiesName = "";
                $recoverModalityName = "";
                $firstCommonModality = false;
                foreach ($gradeUnity->gradeUnityModalities as $index => $gradeUnityModality) {
                    if ($gradeUnityModality->type == "C") {
                        if (!$firstCommonModality) {
                            $commonModalitiesName .= $gradeUnityModality->name;
                            $firstCommonModality = true;
                        } else {
                            $commonModalitiesName .= " + " . $gradeUnityModality->name;
                        }
                    } else {
                        $recoverModalityName = $gradeUnityModality->name;
                    }
                }
                array_push($result["subunityNames"], $commonModalitiesName);
                if ($recoverModalityName !== "") {
                    array_push($result["subunityNames"], $recoverModalityName);
                }
            }

            //Montar linhas das disciplinas e notas
            $result["rows"] = [];
            $disciplines = Yii::app()->db->createCommand("
                select ed.id, ed.name from curricular_matrix cm
                join edcenso_discipline ed on ed.id = cm.discipline_fk
                join edcenso_stage_vs_modality esvm on esvm.id = cm.stage_fk
                join classroom c on c.edcenso_stage_vs_modality_fk = esvm.id
                where c.id = :classroom
                order by ed.name
            ")->bindParam(":classroom", $classroom_id)->queryAll();

            foreach ($disciplines as $discipline) {
                $arr["disciplineName"] = $discipline["name"];

                $arr["grades"] = [];
                foreach ($gradeUnitiesByClassroom as $gradeUnity) {
                    array_push($arr["grades"], $gradeUnity->type == "UR"
                        ? ["unityId" => $gradeUnity->id, "unityGrade" => "", "unityRecoverGrade" => "", "gradeUnityType" => $gradeUnity->type]
                        : ["unityId" => $gradeUnity->id, "unityGrade" => "", "gradeUnityType" => $gradeUnity->type]);
                }

                $gradeResult = GradeResults::model()->find("enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk", ["enrollment_fk" => $student_id, "discipline_fk" => $discipline["id"]]);
                $recSemIndex = 0;
                $gradeIndex = 0;
                foreach ($arr["grades"] as &$grade) {
                    switch ($grade["gradeUnityType"]) {
                        case "U":
                            $grade["unityGrade"] = $gradeResult["grade_" . ($gradeIndex + 1)] != null ? $gradeResult["grade_" . ($gradeIndex + 1)] : "";
                            $gradeIndex++;
                            break;
                        case "UR":
                            $grade["unityGrade"] = $gradeResult["grade_" . ($gradeIndex + 1)] != null ? $gradeResult["grade_" . ($gradeIndex + 1)] : "";
                            $grade["unityRecoverGrade"] = $gradeResult["rec_bim_" . ($gradeIndex + 1)] != null ? $gradeResult["rec_bim_" . ($gradeIndex + 1)] : "";
                            $gradeIndex++;
                            break;
                        case "RS":
                            $grade["unityGrade"] = $gradeResult["rec_sem_" . ($recSemIndex + 1)] != null ? $gradeResult["rec_sem_" . ($recSemIndex + 1)] : "";
                            $recSemIndex++;
                            break;
                        case "RF":
                            $grade["unityGrade"] = $gradeResult["rec_final"] != null ? $gradeResult["rec_final"] : "";
                            break;
                        case "UC":
                            $grade["unityGrade"] = $gradeResult["grade_concept_" . ($gradeIndex + 1)] != null ? $gradeResult["grade_concept_" . ($gradeIndex + 1)] : "";
                            $gradeIndex++;
                            break;
                    }
                }

                $arr["finalMedia"] = $gradeResult != null ? $gradeResult->final_media : "";
                $arr["situation"] = $gradeResult != null ? ($gradeResult->situation != null ? $gradeResult->situation : "") : "";
                array_push($result["rows"], $arr);
            }
            $result["valid"] = true;
        } else {
            $result["valid"] = false;
        }
    
        return $result;
    }

    public function actionOutOfTownStudentsReport()
    {
        $sql = "SELECT DISTINCT su.name, su.inep_id, su.birthday, std.address, 
                edcstd.name AS city_student, edcsch.name AS city_school, 
                si.name AS school
                FROM student_documents_and_address std
                JOIN edcenso_city edcstd ON(std.edcenso_city_fk = edcstd.id)
                JOIN student_enrollment se ON(std.id = se.student_fk)
                JOIN classroom cl ON(se.classroom_fk = cl.id)
                JOIN school_identification si ON (si.inep_id = cl.school_inep_fk)
                JOIN edcenso_city edcsch ON(si.edcenso_city_fk = edcsch.id)
                JOIN student_identification su ON(su.id= std.id)
                WHERE si.`inep_id` =" . Yii::app()->user->school . " AND (se.status = 1 OR se.status IS NULL)
                AND (si.edcenso_city_fk != std.edcenso_city_fk) 
                AND (cl.school_year =" . Yii::app()->user->year . ") 
                ORDER BY NAME;";

        $result = Yii::app()->db->createCommand($sql)->queryAll();
        $this->render('OutOfTownStudentsReport', array(
            'report' => $result,
        ));
    }

    public function actionStudentSpecialFood()
    {
        $sql = "SELECT si.inep_id , si.name as nome_aluno, si.birthday, sr.* FROM student_identification si
        JOIN student_restrictions sr ON(sr.student_fk = si.id)
        WHERE sr.celiac != 0 
        OR sr.celiac != 0
        OR sr.diabetes  != 0
        OR sr.hypertension  != 0
        OR sr.iron_deficiency_anemia != 0
        OR sr.sickle_cell_anemia != 0
        OR sr.lactose_intolerance != 0
        OR sr.malnutrition != 0
        OR sr.obesity != 0
        OR sr.`others` != ''";

        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('StudentSpecialFood', array(
            'report' => $result,
        ));
    }

}