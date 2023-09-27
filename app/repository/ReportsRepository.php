<?php

class ReportsRepository {

    public $repository;
    private $currentYear;
    private $currentSchool;

    public function __construct() {
       $this->currentYear = Yii::app()->user->year;
       $this->currentSchool = Yii::app()->user->school;
    }

    /**
     * Total de Alunos Matriculados por Escola
     */
    public function getTotalNumberOfStudentsEnrolled() : array
    {
        $sql = "SELECT
                    si.name AS school_name,
                    COUNT(DISTINCT c.id) AS count_class,
                    COUNT(se.id) AS count_enrollments
                FROM
                    school_identification si
                LEFT JOIN
                    classroom c ON c.school_inep_fk = si.inep_id
                LEFT JOIN
                    student_enrollment se ON se.classroom_fk = c.id
                WHERE 
                    c.school_year = :school_year AND se.status = 1
                GROUP BY
                    si.inep_id, si.name;";

        $result = Yii::app()->db->createCommand($sql)
        ->bindParam(":school_year", $this->currentYear)
        ->queryAll();

        $response = array("report" => $result);

        return $response;
    }

    /**
     * Alunos com CPF, RG e NIS de todas as Escolas
     */
    public function getStudentCpfRgNisAllSchools() : array
    {
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);
        $sql = "SELECT si.name, si.birthday, sdaa.cpf, sdaa.rg_number,
                    sdaa.nis, si.responsable_name, si.responsable_telephone, si2.name as school_name
                FROM student_enrollment se
                JOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN classroom c ON se.classroom_fk = c.id
                JOIN school_identification si2 ON c.school_inep_fk = si2.inep_id
                WHERE se.status = 1 OR se.status IS NULL
                GROUP BY si.name
                ORDER BY si.name;";

        $result = Yii::app()->db->createCommand($sql)
        ->queryAll();

        $allSchools = true;

        $title = "RELATÓRIO DE ALUNOS DE TODAS AS ESCOLAS (CPF, RG E NIS)<br>".$school->name;

        $response = array("report" => $result, "allSchools" => $allSchools, "title" => $title);

        return $response;
    }

    /**
     * Alunos com CPF, RG e NIS de todas as Turmas
     */
    public function getStudentCpfRgNisAllClassrooms() : array
    {
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);
        $sql = "SELECT si.name, si.birthday, sdaa.cpf, sdaa.rg_number,
                    sdaa.nis, si.responsable_name, si.responsable_telephone, c.name as classroom_name
                FROM student_enrollment se
                jOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN classroom c ON se.classroom_fk = c.id
                WHERE c.school_inep_fk = :school_inep_id AND (se.status = 1 OR se.status IS NULL)
                GROUP BY si.name
                ORDER BY si.name;";
        $result = Yii::app()->db->createCommand($sql)
        ->bindParam(":school_inep_id", $school->inep_id)
        ->queryAll();

        $allClassrooms = true;

        $title = "RELATÓRIO DE ALUNOS POR ESCOLA (CPF, RG E NIS)<br>".$school->name;

        $response = array("report" => $result, "allClassrooms" => $allClassrooms, "title" => $title);

        return $response;
    }

    /**
     * Alunos com CPF, RG e NIS por Turma
     */
    public function getStudentCpfRgNisPerClassroom(CHttpRequest $request) : array
    {
        $classroom = $request->getPost('classroom');
        $classroomModel = Classroom::model()->findByPk($classroom);
        $sql = "SELECT si.name, si.birthday, sdaa.cpf, sdaa.rg_number,
                    sdaa.nis, si.responsable_name, si.responsable_telephone
                FROM student_enrollment se
                JOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN classroom c ON se.classroom_fk = c.id
                WHERE c.id = :classroom AND (se.status = 1 OR se.status IS NULL)
                GROUP BY si.name
                ORDER BY si.name;";

        $result = Yii::app()->db->createCommand($sql)
        ->bindParam(":classroom", $classroom)
        ->queryAll();

        $title = "RELATÓRIO DE ALUNOS POR TURMA (CPF, RG E NIS)<br>".$classroomModel->name;

        
        $response = array("report" => $result, "title" => $title);

        return $response;
    }

    /**
     * Número de Alunos Matriculados por Período em todas as Escolas
     */
    public function getNumberOfStudentsEnrolledPerPeriodAllSchools(CHttpRequest $request) : array
    {
        $initialDate = $request->getPost('initial-date');
        $endDate = $request->getPost('end-date');

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
        ->bindParam(":school_year", $this->currentYear)
        ->bindParam(":initial_date", $initialDate)
        ->bindParam(":end_date", $endDate)
        ->queryAll();

        $allSchools = true;

        $title = "QUANTITATIVO DE ALUNOS MATRICULADOS POR PERÍODO <br>DE TODAS AS ESCOLAS";

        $response = array("report" => $result, "allSchools" => $allSchools, "title" => $title);

        return $response;
    }

    /**
     * Número de Alunos Matriculados por Período por Escola
     */
    public function getNumberOfStudentsEnrolledPerPeriodPerSchool(CHttpRequest $request) : array
    {
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);
        $initialDate = $request->getPost('initial-date');
        $endDate = $request->getPost('end-date');

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
        ->bindParam(":school_year", $this->currentYear)
        ->bindParam(":school_inep_id", $school->inep_id)
        ->bindParam(":initial_date", $initialDate)
        ->bindParam(":end_date", $endDate)
        ->queryAll();

        $allClassrooms = true;

        $title = "QUANTITATIVO DE ALUNOS MATRICULADOS POR PERÍODO<br>".$school->name;

        $response = array("report" => $result, "allClassrooms" => $allClassrooms, "title" => $title);

        return $response;
    }

    /**
     * Número de Alunos Matriculados por Período por Turma
     */
    public function getNumberOfStudentsEnrolledPerPeriodPerClassroom(CHttpRequest $request) : array
    {
        $classroom = Classroom::model()->findByPk($request->getPost('classroom'));
        $initialDate = $request->getPost('initial-date');
        $endDate = $request->getPost('end-date');

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

        $response = array("report" => $result, "title" => $title);

        return $response;
    }

    /**
     * Alunos Beneficiários do Bolsa Família de todas as Turmas
     */
    public function getAllClassroomsReportOfStudentsBenefitingFromTheBF() : array
    {
        $sql = "SELECT si.name, si.birthday, sdaa.nis, si.responsable_name,
                        si.responsable_telephone, c.name AS classroom_name
                FROM student_identification si
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN student_enrollment se ON se.student_fk = si.id
                JOIN classroom c ON se.classroom_fk = c.id
                WHERE si.bf_participator = 1 AND c.school_year = :school_year
                    AND c.school_inep_fk = :school_inep_id AND (se.status = 1 OR se.status IS NULL)
                GROUP BY si.name
                ORDER BY si.name;";

        $school = SchoolIdentification::model()->findByPk($this->currentSchool);
        $allSchools = false;
        $title = "BENEFICIÁRIOS DO BOLSA FAMÍLIA DE TODAS AS TURMAS<br>".$school->name;

        $result = Yii::app()->db->createCommand($sql)
        ->bindParam(":school_year", $this->currentYear)
        ->bindParam(":school_inep_id", $this->currentSchool)
        ->queryAll();

        $response = array("report" => $result, "allSchools" => $allSchools, "title" => $title);

        return $response;
    }

    /**
     * Alunos Beneficiários do Bolsa Família de todas as Escolas
     */
    public function getAllSchoolsReportOfStudentsBenefitingFromTheBF() : array
    {
        $sql = "SELECT si.name, si.birthday, sdaa.nis, si.responsable_name,
                        si.responsable_telephone, si2.name AS school_name
                FROM student_identification si
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN student_enrollment se ON se.student_fk = si.id
                JOIN classroom c ON se.classroom_fk = c.id
                JOIN school_identification si2 ON c.school_inep_fk = si2.inep_id
                WHERE si.bf_participator = 1 AND c.school_year = :school_year
                    AND (se.status = 1 OR se.status IS NULL)
                GROUP BY si.name
                ORDER BY si2.name, si.name;";

        $title = "BENEFICIÁRIOS DO BOLSA FAMÍLIA DE TODAS AS ESCOLAS";
        $allSchools = true;
        $result = Yii::app()->db->createCommand($sql)
        ->bindParam(":school_year", $this->currentYear)
        ->queryAll();

        $response = array("report" => $result, "allSchools" => $allSchools, "title" => $title);

        return $response;
    }

    /**
     * Alunos Beneficiários do Bolsa Família por Turma
     */
    public function getReportOfStudentsBenefitingFromTheBFPerClassroom(CHttpRequest $request) : array
    {
        $classroomId = $request->getPost('classroom');
        $sql = "SELECT si.name, si.birthday, sdaa.nis, si.responsable_name,
                        si.responsable_telephone, c.name AS classroom_name
                FROM student_identification si
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN student_enrollment se ON se.student_fk = si.id
                JOIN classroom c ON se.classroom_fk = c.id
                JOIN school_identification si2 ON c.school_inep_fk = si2.inep_id
                WHERE si.bf_participator = 1 AND c.school_year = :school_year
                     AND c.id = :classroom_id AND (se.status = 1 OR se.status IS NULL)
                GROUP BY si.name
                ORDER BY si2.name, si.name;";

        $allSchools = false;
        $classroom = Classroom::model()->findByPk($classroomId);

        $title = "BENEFICIÁRIOS DO BOLSA FAMÍLIA<br> ".$classroom->name;
        $result = Yii::app()->db->createCommand($sql)
        ->bindParam(":school_year", $this->currentYear)
        ->bindParam(":classroom_id", $classroomId)
        ->queryAll();

        $response = array("report" => $result, "allSchools" => $allSchools, "title" => $title);

        return $response;
    }

    /**
     * Quantidade de Turmas por Escola
     */
    public function getNumberOfClassesPerSchool() : array
    {
        $criteria = new CDbCriteria();
        $criteria->condition = "school_year = '".$this->currentYear."'";

        $schools = SchoolIdentification::model()->findAll();
        $classrooms = Classroom::model()->findAll($criteria);

        $title = "Quantidade de Turmas por Escola";

        $result = [];

        foreach ($schools as $school) {
            array_push($result, array(
                "school" => $school,
                "classrooms" => array_filter($classrooms, function ($classroom) use ($school) {
                    return $classroom->school_inep_fk == $school->inep_id;
                })
            ));
        }

        $response = array("report" => $result, "title" => $title);

        return $response;
    }

    /**
     * Ata de Formação de Professores
     */
    public function getTeacherTrainingReport(CHttpRequest $request) : array
    {
        $classroom = $request->getPost('classroom');
        $day = $request->getPost('count_days');
        $hour = str_replace(":", "h", $request->getPost('hour'));
        $year = $request->getPost('year');
        $mounth = $request->getPost('mounth');
        $quarterly = $request->getPost('quarterly');
        $model_report = $request->getPost('model_report');
        $school_inep_id = $this->currentSchool;

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

        $response = array("classroom" => $result,
                        "count_days" => $day,
                        "mounth" => $mounth,
                        "hour" => $hour,
                        "quarterly" => $quarterly,
                        "year" => $year,
                        "title" => $title,
                        "disciplines" => $disciplines);
        return $response;
    }

    /**
     * Dados Estatísticos
     */
    public function getStatisticalData() : array
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
        ->bindParam(":school_year", $this->currentYear)
        ->queryAll();

        $stages = EdcensoStageVsModality::model()->findAll();
        $result = [];
        foreach ($stages as $stage) {
            $studentsByStage = array_filter($students, function ($student) use ($stage) {
                return $student['stage'] == $stage->id;
            });
            array_push($result, ["stage" => $stage, "students" => $studentsByStage]);
        }

        $response = array("report" => $result);

        return $response;
    }

    /**
     * Relatório de Transferência da Turma
     */
    public function getClassroomTransferReport(CHttpRequest $request) : array
    {
        $classroom_id = $request->getPost('classroom');
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

        $response = array("report" => $result,"title" => $title,"header" => $header);

        return $response;
    }

    /**
     * Relatório de Transferência da Escola
     */
    public function getSchoolTransferReport() : array
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
        ->bindParam(":school_inep_id", $this->currentSchool)
        ->queryAll();

        $title = "RELATÓRIO TRANSFERÊNCIA DA ESCOLA";
        $header = $result[0]['school_name'];

        $response = array( "report" => $result,"title" => $title,"header" => $header);

        return $response;
    }

    /**
     * Relatório de Transferência de todas as Escolas
     */
    public function getAllSchoolsTransferReport() : array
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

        $response = array("report" => $result,"title" => $title,"header" => $header);

        return $response;
    }

    /**
     * Professores por Etapa
     */
    public function getTeachersByStage() : array
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

        $response = array("report" => $result);

        return $response;
    }

    /**
     * Professores por Escola
     */
    public function getTeachersBySchool() : array
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

        $response = array("report" => $result);

        return $response;
    }

    /**
     * Relatório CNS por Turma
     */
    public function getCnsPerClassroomReport(CHttpRequest $request) : array
    {
        $classroomId = $request->getPost('cns_classroom_id');
        $sql = "SELECT
                si.name, si.birthday, sdaa.cns, c.name AS classroom_name,
                si.responsable_name, si.responsable_telephone
                FROM student_enrollment se
                JOIN classroom c ON se.classroom_fk = c.id
                JOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                WHERE c.id = :classroom_id AND (se.status = 1 OR se.status IS NULL)
                GROUP BY name;";

        $result = Yii::app()->db->createCommand($sql)
        ->bindParam(":classroom_id", $classroomId)
        ->queryAll();

        $title = "RELATÓRIO CNS DA TURMA";
        $header = $result[0]['classroom_name'];

        $response = array("report" => $result,"title" => $title,"header" => $header);

        return $response;
    }

    /**
     * Relatório CNS de todas as Escolas
     */
    public function getCnsSchools() : array
    {
        $sql = "SELECT
        si2.name AS school_name, si.name, si.birthday, sdaa.cns,
        si.responsable_name, si.responsable_telephone
        FROM student_enrollment se
        JOIN classroom c ON se.classroom_fk = c.id
        JOIN school_identification si2 ON c.school_inep_fk = si2.inep_id
        JOIN student_identification si ON se.student_fk = si.id
        JOIN student_documents_and_address sdaa ON si.id = sdaa.id
        WHERE c.school_year = :year
        GROUP BY name
        ORDER BY si2.inep_id;";

        $result =  Yii::app()->db->createCommand($sql)
        ->bindParam(":year", $this->currentYear)
        ->queryAll();
        $allSchools = true;
        $countTotal = true;
        $title = "RELATÓRIO CNS ESCOLAS";

        $response = array("report" => $result,"title" => $title,"allSchools" => $allSchools,"countTotal" => $countTotal);

        return $response;
    }

    /**
     * Relatório CNS por Escola
     */
    public function getCnsPerSchool() : array
    {
        $sql = "SELECT
                sch.name AS school_name, si.name, si.birthday, sdaa.cns,
                si.responsable_name, si.responsable_telephone
                FROM school_identification sch
                JOIN student_enrollment se ON se.school_inep_id_fk = sch.inep_id
                JOIN classroom c ON se.classroom_fk = c.id
                JOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                WHERE sch.inep_id = :school_id AND c.school_year = :year
                GROUP BY name;";

        $result =  Yii::app()->db->createCommand($sql)
        ->bindParam(":school_id", $this->currentSchool)
        ->bindParam(":year", $this->currentYear)
        ->queryAll();

        $countTotal = true;
        $title = "RELATÓRIO CNS DA ESCOLA";
        $header = $result[0]['school_name'];

        $response = array("report" => $result,"title" => $title,"header" => $header,"countTotal" => $countTotal);

        return $response;
    }

    /**
     * Relatório Trimestral
     */
    public function getQuarterlyReport(CHttpRequest $request) : array
    {
        $student_id = $request->getPost('student');
        $classroom_id = $request->getPost('quartely_report_classroom_student');
        $model = $request->getPost('model_quartely');
        $student_identification = StudentIdentification::model()->findByPk($student_id);
        $student_enrollment = StudentEnrollment::model()->findByAttributes(array('student_fk' => $student_id));
        $classroom = Classroom::model()->findByPk($classroom_id);
        $classroom_etapa = EdcensoStageVsModality::model()->findByPk($classroom->edcenso_stage_vs_modality_fk);
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);
        $current_year = $this->currentYear;

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

        if($model == 1) {
            $view = 'buzios/quarterly/QuarterlyReportFirstYear';
            $result = array(
                "student_identification" => $student_identification,
                "student_enrollment" => $student_enrollment,
                "classroom" => $classroom,
                "school" => $school,
                "current_year" => $current_year,
                "regentTeachers" => $regentTeachers
            );
        }else if ($model == 2) {
            $view = 'buzios/quarterly/QuarterlyReportSecondYear';
            $result = array(
                "student_identification" => $student_identification,
                "student_enrollment" => $student_enrollment,
                "classroom" => $classroom,
                "school" => $school,
                "current_year" => $current_year,
                "regentTeachers" => $regentTeachers
            );
        }else if ($model == 3) {
            $view = 'buzios/quarterly/QuarterlyReportThreeYear';
            $result = array(
                "student_identification" => $student_identification,
                "student_enrollment" => $student_enrollment,
                "classroom" => $classroom,
                "school" => $school,
                "current_year" => $current_year,
                "regentTeachers" => $regentTeachers
            );
        }else if ($model == 4) {
            $view = 'buzios/quarterly/QuarterlyReportNurseryrII';
            $result = array(
                "student_identification" => $student_identification,
                "student_enrollment" => $student_enrollment,
                "classroom" => $classroom,
                "classroom_etapa" => $classroom_etapa,
                "school" => $school,
                "current_year" => $current_year,
                "dateFormatCorrect" => $dateFormatCorrect,
                "regentTeachers" => $regentTeachers
            );
        }else if ($model == 5) {
            $view = 'buzios/quarterly/QuarterlyReportNurseryrIII';
            $result = array(
                "student_identification" => $student_identification,
                "student_enrollment" => $student_enrollment,
                "classroom" => $classroom,
                "classroom_etapa" => $classroom_etapa,
                "school" => $school,
                "current_year" => $current_year,
                "dateFormatCorrect" => $dateFormatCorrect,
                "regentTeachers" => $regentTeachers
            );
        }else if ($model == 6) {
            $view = 'buzios/quarterly/QuarterlyReportNurseryrIV';
            $result = array(
                "student_identification" => $student_identification,
                "student_enrollment" => $student_enrollment,
                "classroom" => $classroom,
                "classroom_etapa" => $classroom_etapa,
                "school" => $school,
                "current_year" => $current_year,
                "dateFormatCorrect" => $dateFormatCorrect,
                "regentTeachers" => $regentTeachers
            );
        }else if ($model == 7) {
            $view = 'buzios/quarterly/QuarterlyReportPreI';
            $result = array(
                "student_identification" => $student_identification,
                "student_enrollment" => $student_enrollment,
                "classroom" => $classroom,
                "classroom_etapa" => $classroom_etapa,
                "school" => $school,
                "current_year" => $current_year,
                "dateFormatCorrect" => $dateFormatCorrect,
                "regentTeachers" => $regentTeachers
            );
        }else if ($model == 8) {
            $view = 'buzios/quarterly/QuarterlyReportPreII';
            $result = array(
                "student_identification" => $student_identification,
                "student_enrollment" => $student_enrollment,
                "classroom" => $classroom,
                "classroom_etapa" => $classroom_etapa,
                "school" => $school,
                "current_year" => $current_year,
                "dateFormatCorrect" => $dateFormatCorrect,
                "regentTeachers" => $regentTeachers
            );
        }
        
        $response = array("response" => $result, "view" => $view);

        return $response;
    }

    /**
     * Relatório Trimestral de Acompanhamento
     */
    public function getQuarterlyFollowUpReport(CHttpRequest $request) : array
    {
        
        $classroom_id = $request->getPost('quarterly_follow_up_classroom');
        $discipline_id = $request->getPost('quarterly_follow_up_disciplines');
        $classroom_model = Classroom::model()->findByPk($classroom_id);
        $classroom_stage_name = $classroom_model->edcensoStageVsModalityFk->name;
        $discipline_model = EdcensoDiscipline::model()->findByPk($discipline_id);
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);

        $trimestre = $request->getPost('quarterly');

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
        } elseif ($turno == 'T') {
            $turno = "Vesperitino";
        } elseif ($turno == 'N') {
            $turno = "Noturno";
        } elseif ($turno == '' || $turno == null) {
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

        for ($i = 0; $i < count($anos1); $i++) {
            if (strpos($stage_name, $anos1[$i]) !== false) {
                $anosTitulo = "1º, 2º e 3º ANOS";
                $anosVerify = 1;
                $anosPosition = $i + 1;
                $stageVerify = true;
                break;
            }
        }
        for ($i = 0; $i < count($anos2); $i++) {
            if (strpos($stage_name, $anos2[$i]) !== false) {
                $anosTitulo = "4º E 5º ANOS";
                $anosVerify = 2;
                $anosPosition = $i + 4;
                $stageVerify = true;
                break;
            }
        }

        if(!$stageVerify) {
            $error = true;
            $message = "A turma ".$classroom_model->name." não possui uma etapa correspondente ao relatório. Etapa da Turma: ".$classroom_stage_name;
        }else {
            $result =  array(
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
            );
        }

        if($result == null) {
            $error = true;
            $message = "A turma ".$classroom_model->name." não possui professores para a disciplina de ".$discipline_model->name;
        }

        $response = array("error" => $error, "message" => $message, "response" => $result);

        return $response;
    }

    /**
     * Acompanhamento avaliativo dos alunos
     */
    public function getEvaluationFollowUpStudentsReport(CHttpRequest $request) : array
    {
        $discipline_id = $request->getPost('evaluation_follow_up_disciplines');
        $classroom_id = $request->getPost('evaluation_follow_up_classroom');
        $quarterly = $request->getPost('quarterly');

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

        for ($i = 0; $i < count($anos1); $i++) {
            if (strpos($stage_name, $anos1[$i]) !== false) {
                $anosTitulo = "1º, 2º e 3º ANOS";
                $anosVerify = 1;
                $anosPosition = $i + 1;
                $stageVerify = true;
                break;
            }
        }
        for ($i = 0; $i < count($anos2); $i++) {
            if (strpos($stage_name, $anos2[$i]) !== false) {
                $anosTitulo = "4º E 5º ANOS";
                $anosVerify = 2;
                $anosPosition = $i + 4;
                $stageVerify = true;
                break;
            }
        }

        if(!$stageVerify) {
            $error = true;
            $message = "A turma ".$classroom->name." não possui uma etapa correspondente ao relatório. Etapa da Turma: ".$classroom_stage_name;
        }

        if($instructor) {
            if($students) {
                $result =  array(
                    "instructor" => $instructor,
                    "students" => $students,
                    "classroom" => $classroom,
                    "discipline" => $discipline,
                    "anosTitulo" => $anosTitulo,
                    "anosVerify" => $anosVerify,
                    "anosPosition" => $anosPosition,
                    "quarterly" => $quarterly
                );
            } else {
                $error = true;
                $message = "A turma ".$classroom->name." não possui alunos matriculados";
            }
        } else {
            $error = true;
            $message = "A turma ".$classroom->name." não possui professores para a disciplina de ".$discipline->name;
        }

        $response = array("error" => $error, "message" => $message, "response" => $result);

        return $response;
    }

    /**
     * Ata de Conselho de Classe
     */
    public function getClassCouncilReport(CHttpRequest $request) : array
    {
        $count_days = $request->getPost('count_days');
        $mounth = $request->getPost('mounth');
        $hour = str_replace(":", "h", $request->getPost('hour'));
        $quarterly = $request->getPost('quarterly');
        $school_inep_id = $this->currentSchool;
        $infantil = $request->getPost('infantil-model');
        $year = $request->getPost('year');
        $classroom_id = $request->getPost('classroom');

        $sql = "SELECT
                e.name as school_name, c.name as classroom_name, c.id as classroom_id,
                s.*, se.status, se.create_date, se.observation, ii.name as prof_name, ed.name as discipline,
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
                c.id = :classroom_id
            ORDER BY se.daily_order";

        $classrooms = Yii::app()->db->createCommand($sql)
                    ->bindParam(":year", $year)
                    ->bindParam(":school_inep_id", $school_inep_id)
                    ->bindParam(":classroom_id", $classroom_id)
                    ->queryAll();

        $stage_id = $classrooms[0]['stage_id'];
        $current_report = 0;
        if ($stage_id == 1 || $stage_id == 2) {
            $current_report = 1;
        } elseif ($stage_id == 3 || $stage_id == 7) {
            $current_report = 2;
        } elseif ($stage_id == 4) {
            $current_report = 3;
        }

        $title = '';
        if($infantil) {
            $title = "EDUCAÇÃO INFANTIL";
        }

        if($classrooms[0] != null) {
            if ($current_report == 1) {
                $view = 'buzios/quarterly/QuarterlyClassCouncil';
                $result = array(
                    "classroom" => $classrooms,
                    "count_days" => $count_days,
                    "mounth" => $mounth,
                    "hour" => $hour,
                    "quarterly" => $quarterly,
                    "year" => $year,
                    "title" => $title
                );
            } elseif ($current_report == 2) {
                $view = 'buzios/quarterly/QuarterlyClassCouncilSixNineYear';
                $result = array(
                    "classroom" => $classrooms,
                    "count_days" => $count_days,
                    "mounth" => $mounth,
                    "hour" => $hour,
                    "quarterly" => $quarterly,
                    "year" => $year
                );
            } elseif ($current_report == 3) {
                $view = 'buzios/quarterly/QuarterlyClassCouncilHighSchool';
                $result = array(
                    "classroom" => $classrooms,
                    "count_days" => $count_days,
                    "mounth" => $mounth,
                    "hour" => $hour,
                    "quarterly" => $quarterly,
                    "year" => $year
                );
            }
        } else {
            $error = true;
            $message = 'Certifique-se de que a turma selecionada tem professores, alunos e disciplinas cadastradas';
        }

        $response = array("error" => $error, "message" => $message, "view" => $view, "response" => $result);

        return $response;
    }

    /**
     * Relação Transporte Escolar
     */
    public function getStudentsUsingSchoolTransportationRelationReport() : array
    {
        $school_inep_id = $this->currentSchool;
        $year = $this->currentYear;
        $school = SchoolIdentification::model()->findByPk($school_inep_id);
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

        $response = array("school" => $school,"students" => $students, "classrooms" => $classrooms);

        return $response;
    }

    /**
     * Relação acessibilidade por Turma
     */
    public function getStudentsWithDisabilitiesPerClassroom(CHttpRequest $request) : array
    {
        $classroomId = $request->getPost('classroom');

        $sql = "SELECT si.*, se.classroom_fk
        FROM student_identification as si
        JOIN student_enrollment as se on si.id = se.student_fk
        JOIN classroom as c on se.classroom_fk = c.id
        WHERE si.deficiency = 1 and c.id = :classroom_id";

        $sql1 = "SELECT c.*
                FROM classroom as c
                WHERE c.id = :classroom_id";

        $students = Yii::app()->db->createCommand($sql)
                    ->bindParam(":classroom_id", $classroomId)
                    ->queryAll();
        $classroom = Yii::app()->db->createCommand($sql1)
                    ->bindParam(":classroom_id", $classroomId)
                    ->queryAll();

        $response = array('students' => $students,'classroom' => $classroom);

        return $response;
    }

    /**
     * Relação acessibilidade de todas as Escolas 
     */
    public function getStudentsWithDisabilitiesPerSchool() : array
    {
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

        $response = array(
            'students' => $students,
            'schools' => $schools,
            'report' => $result
        );

        return $response;
    }

    /**
     * Relação Acessibilidade
     */
    public function getStudentsWithDisabilitiesRelationReport() : array
    {
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);

        $sql = "SELECT si.*, se.classroom_fk
                FROM student_identification AS si 
                JOIN student_enrollment AS se ON si.id = se.student_fk 
                JOIN classroom AS c ON se.classroom_fk = c.id
                WHERE si.deficiency = 1 AND 
                    si.school_inep_id_fk = :school_inep_id_fk AND 
                    se.school_inep_id_fk = :school_inep_id_fk AND 
                    c.school_year = :school_year AND 
                    (se.status = 1 OR se.status IS NULL) 
                ORDER BY si.name";

        $students = Yii::app()->db->createCommand($sql)
                    ->bindParam(':school_inep_id_fk', $this->currentSchool)
                    ->bindParam('school_year', $this->currentYear)
                    ->queryAll();

        $sql = "SELECT c.*, q.modality,q.stage
                FROM classroom AS c 
                JOIN classroom_qtd_students AS q ON c.school_inep_fk = q.school_inep_fk
                WHERE c.school_year = :school_year AND 
                      q.school_year = :school_year AND 
                      c.school_inep_fk = :school_inep_fk AND 
                      q.school_inep_fk = :school_inep_fk  AND 
                      c.id = q.id
                ORDER BY c.name";

        $classrooms = Yii::app()->db->createCommand($sql)
                    ->bindParam(':school_inep_fk', $this->currentSchool)
                    ->bindParam('school_year', $this->currentYear)
                    ->queryAll();

        $response = array('school' => $school, 'students' => $students, 'classrooms' => $classrooms);

        return $response;
    }

    /**
     * Relação de alunos em ordem alfabética
     */
    public function getStudentsInAlphabeticalOrderRelationReport() : array
    {
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);

        $sql = "SELECT si.name AS studentName, si.inep_id AS studentInepId, se.classroom_inep_id, si.birthday,cq.*
                    FROM (student_identification AS si 
                JOIN student_enrollment AS se ON si.id = se.student_fk)
                JOIN classroom_qtd_students AS cq ON cq.id = se.classroom_fk
                WHERE se.school_inep_id_fk = :school_inep_id_fk  AND 
                      si.school_inep_id_fk = :school_inep_id_fk AND 
                      cq.school_year = :school_year AND 
                      (se.status = 1 OR se.status IS NULL)
                ORDER BY si.name";

        $students = Yii::app()->db->createCommand($sql)
                    ->bindParam(':school_inep_id_fk', $this->currentSchool)
                    ->bindParam(':school_year', $this->currentYear)
                    ->queryAll();

        $response = array(
            'school' => $school,
            'students' => $students
        );

        return $response;
    }

    /**
     * Relatório de Matrícula
     */
    public function getEnrollmentPerClassroomReport($classroomId) : array
    {
        $sql = "SELECT * FROM classroom_enrollment
                WHERE `year`  = :school_year AND 
                classroom_id = :classroom_id
                ORDER BY daily_order;";

        $result = Yii::app()->db->createCommand($sql)
                ->bindParam(':school_year', $this->currentYear)
                ->bindParam('classroom_id', $classroomId)
                ->queryAll();

        $classroom = Classroom::model()->findByPk($classroomId);

        $response = array('report' => $result, 'classroom' => $classroom);

        return $response;
    }

    /**
     * Alunos com documentos pendentes
     */
    public function getStudentPendingDocument() : array
    {
        $sql = "SELECT *, d.name as nome_aluno, d.inep_id as inep_id
                    FROM student_enrollment a
                    JOIN classroom b ON(a.`classroom_fk`=b.id)
                    JOIN student_documents_and_address c ON(a.`student_fk`=c.`id`)
                    JOIN student_identification d ON(c.`id`=d.`id`)
                    WHERE b.`school_inep_fk` = :school_inep_fk AND 
                          b.school_year = :school_year AND 
                          (a.status = 1 OR a.status IS NULL) AND
                          (received_cc = 0 OR received_address = 0 OR received_photo = 0
                    OR received_nis = 0 OR received_responsable_rg = 0 OR received_responsable_cpf = 0)";

        $result = Yii::app()->db->createCommand($sql)
                ->bindParam(':school_inep_fk', $this->currentSchool)
                ->bindParam(':school_year', $this->currentYear)
                ->queryAll();

        $response = array('report' => $result,);

        return $response;
    }

    /**
     * Lista de Alunos
     */
    public function getStudentPerClassroom($classroomId) : array
    {
        $this->layout = "reports";
        $sql =  "SELECT * FROM classroom_enrollment
                    WHERE `year`  = :year AND 
                    classroom_id = :classroom_id AND 
                    (status = 1 OR status IS NULL) 
                ORDER BY name;";

        $result = Yii::app()->db->createCommand($sql)
                ->bindParam(':year', $this->currentYear)
                ->bindParam(':classroom_id', $classroomId)
                ->queryAll();

        $classroom = Classroom::model()->findByPk($classroomId);

        $response = array('report' => $result,'classroom' => $classroom);

        return $response;
    }

    /**
     * Relação Cloc por Turma (descontinuada)
     */
    public function getClocPerClassroom($classroomId) : array
    {
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
                where  `c`.`school_year`  = :school_year AND 
                `c`.`id` = :classroom_id AND 
                (status = 1 OR status IS NULL) 
        ORDER BY name;";

        $result = Yii::app()->db->createCommand($sql)
                ->bindParam(':school_year', $this->currentYear)
                ->bindParam(':classroom_id', $classroomId)
                ->queryAll();

        $classroom = Classroom::model()->findByPk($classroomId);

        $response = array('report' => $result,'classroom' => $classroom);

        return $response;
    }

    /**
     * Alunos por Turma
     */
    public function getStudentsByClassroomReport() : array
    {
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);

        $sql = "SELECT c.*, q.modality,q.stage
                    FROM classroom AS c 
                JOIN classroom_qtd_students AS q ON c.school_inep_fk = q.school_inep_fk
                WHERE c.school_year = :school_year AND 
                      q.school_year = :school_year AND 
                      c.school_inep_fk = :school_inep_fk AND 
                      q.school_inep_fk = :school_inep_fk  AND 
                      c.id = q.id
                ORDER BY name";

        $classrooms = Yii::app()->db->createCommand($sql)
                    ->bindParam(':school_year', $this->currentYear)
                    ->bindParam(':school_inep_fk', $this->currentSchool)
                    ->queryAll();

        $sql = "SELECT DISTINCT se.classroom_fk,si.inep_id,si.name,si.birthday
                    FROM student_identification AS si
                JOIN student_enrollment AS se ON si.id = se.student_fk
                JOIN classroom AS c ON se.classroom_fk = c.id
                WHERE se.school_inep_id_fk = :school_inep_id_fk AND 
                      c.school_year = :school_year AND 
                      (se.status = 1 OR se.status IS NULL) 
                ORDER BY si.name";

        $students = Yii::app()->db->createCommand($sql)
                    ->bindParam(':school_year', $this->currentYear)
                    ->bindParam(':school_inep_id_fk', $this->currentSchool)
                    ->queryAll();

        $response = array('school' => $school, 'classroom' => $classrooms, 'students' => $students);

        return $response;
    }

    /**
     * Alunos com idade entre 5 e 14 anos (SUS)
     */
    public function getStudentsBetween5And14YearsOldReport() : array
    {
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);

        $sql = "SELECT c.*, q.modality,q.stage
                    FROM classroom AS c 
                JOIN classroom_qtd_students AS q ON c.school_inep_fk = q.school_inep_fk
                WHERE c.school_year = :year AND 
                      q.school_year = :year AND 
                      c.school_inep_fk = :school AND 
                      q.school_inep_fk = :school  AND 
                      c.id = q.id AND 
                      (q.status = 1 OR q.status IS NULL)
                ORDER BY name";

        $classrooms = Yii::app()->db->createCommand($sql)
                    ->bindParam(":year", $this->currentYear)
                    ->bindParam(":school", $this->currentSchool)
                    ->queryAll();

        $sql = "SELECT se.classroom_fk,si.inep_id,si.name,si.birthday , 
                        si.filiation_1, si.filiation_2
                    FROM (student_identification AS si 
                JOIN student_enrollment AS se ON si.id = se.student_fk 
                JOIN classroom AS c ON se.classroom_fk = c.id )
                WHERE se.school_inep_id_fk = :school AND 
                      c.school_year = :year AND 
                      (se.status = 1 OR se.status IS NULL) 
                ORDER BY si.name ";

        $students = Yii::app()->db->createCommand($sql)
                    ->bindParam(":year", $this->currentYear)
                    ->bindParam(":school", $this->currentSchool)
                    ->queryAll();

        $response = array('school' => $school,'classroom' => $classrooms,'students' => $students);

        return $response;
    }

    /**
     * Monitores de Atividade Complementar
     */
    public function getComplementarActivityAssistantByClassroomReport() : array
    {
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);

        $sql = "SELECT c.*, q.modality,q.stage
                    FROM classroom AS c 
                JOIN classroom_qtd_students AS q ON c.school_inep_fk = q.school_inep_fk
                WHERE c.assistance_type = 4 AND 
                      c.school_year = :school_year AND 
                      q.school_year = :school_year AND 
                      c.school_inep_fk = :school_inep_fk AND 
                      q.school_inep_fk = :school_inep_fk  AND 
                      c.id = q.id
                ORDER BY name";

        $classrooms = Yii::app()->db->createCommand($sql)
                    ->bindParam(':school_year', $this->currentYear)
                    ->bindParam(':school_inep_fk', $this->currentSchool)
                    ->queryAll();

        $sql = "SELECT id.*,it.classroom_id_fk , iv.scholarity
                    FROM (((instructor_identification AS id 
                JOIN instructor_teaching_data AS it ON it.instructor_fk = id.id)
                JOIN instructor_variable_data AS iv ON iv.id = id.id) 
                JOIN classroom AS c ON c.id = it.classroom_id_fk)
                WHERE id.school_inep_id_fk = :school_inep_id_fk AND 
                      (it.role = 2 or it.role = 3) AND 
                      c.school_year = :school_year 
                ORDER BY c.name";

        $instructor = Yii::app()->db->createCommand($sql)
                    ->bindParam(':school_year', $this->currentYear)
                    ->bindParam(':school_inep_id_fk', $this->currentSchool)
                    ->queryAll();

        $response = array("school" => $school, "classroom" => $classrooms, 'instructor' => $instructor);

        return $response;
    }
}