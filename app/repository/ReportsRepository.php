<?php

include_once __DIR__ . '/../vendor/yiisoft/yii/framework/db/schema/CDbCriteria.php';

class ReportsRepository
{

    public $repository;
    private $currentYear;
    private $currentSchool;

    public function __construct()
    {
        $this->currentYear = Yii::app()->user->year;
        $this->currentSchool = Yii::app()->user->school;
    }

    public function getIndexData(): array
    {
        $classrooms = Classroom::model()->findAll(
            array(
                'condition' => 'school_inep_fk=' . $this->currentSchool . ' && school_year = ' . $this->currentYear,
                'order' => 'name'
            )
        );

        $students = StudentIdentification::model()->findAll(
            array(
                'condition' => 'school_inep_id_fk = ' . $this->currentSchool . ' && send_year = ' . $this->currentYear,
                'order' => 'name'
            )
        );

        $schools = SchoolIdentification::model()->findAll();

        $stages = EdcensoStageVsModality::model()->findAll();

        return array('classrooms' => $classrooms, 'students' => $students, 'schools' => $schools, 'stages' => $stages);


    }

    /**
     * Total de Alunos Matriculados por Escola
     */
    public function getTotalNumberOfStudentsEnrolled(): array
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
                    c.school_year = :school_year AND ((`se`.`status` IN (1, 6, 7, 8, 9, 10) or `se`.`status` is null))
                GROUP BY
                    si.inep_id, si.name;";

        $result = Yii::app()->db->createCommand($sql)
            ->bindParam(":school_year", $this->currentYear)
            ->queryAll();

        return array("report" => $result);


    }

    /**
     * Alunos com CPF, RG e NIS de todas as Escolas
     */
    public function getStudentCpfRgNisAllSchools(): array
    {
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);
        $sql = "SELECT si.name, si.birthday, sdaa.cpf, sdaa.rg_number,
                    sdaa.nis, si.responsable_name, si.responsable_telephone, si2.name as school_name
                FROM student_enrollment se
                JOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN classroom c ON se.classroom_fk = c.id
                JOIN school_identification si2 ON c.school_inep_fk = si2.inep_id
                WHERE ((`se`.`status` IN (1, 6, 7, 8, 9, 10) or `se`.`status` is null))
                GROUP BY si.name
                ORDER BY si.name;";

        $result = Yii::app()->db->createCommand($sql)
            ->queryAll();

        $allSchools = true;

        $title = "RELATÓRIO DE ALUNOS DE TODAS AS ESCOLAS (CPF, RG E NIS)<br>" . $school->name;

        return array("report" => $result, "allSchools" => $allSchools, "title" => $title);


    }

    /**
     * Alunos com CPF, RG e NIS de todas as Turmas
     */
    public function getStudentCpfRgNisAllClassrooms(): array
    {
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);
        $sql = "SELECT si.name, si.birthday, sdaa.cpf, sdaa.rg_number,
                    sdaa.nis, si.responsable_name, si.responsable_telephone, c.name as classroom_name
                FROM student_enrollment se
                jOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN classroom c ON se.classroom_fk = c.id
                WHERE c.school_inep_fk = :school_inep_id AND ((`se`.`status` IN (1, 6, 7, 8, 9, 10) or `se`.`status` is null))
                GROUP BY si.name
                ORDER BY si.name;";
        $result = Yii::app()->db->createCommand($sql)
            ->bindParam(":school_inep_id", $school->inep_id)
            ->queryAll();

        $allClassrooms = true;

        $title = "RELATÓRIO DE ALUNOS POR ESCOLA (CPF, RG E NIS)<br>" . $school->name;

        return array("report" => $result, "allClassrooms" => $allClassrooms, "title" => $title);


    }

    /**
     * Alunos com CPF, RG e NIS por Turma
     */
    public function getStudentCpfRgNisPerClassroom(CHttpRequest $request): array
    {
        $classroom = $request->getPost('classroom');
        $classroomModel = Classroom::model()->findByPk($classroom);
        $sql = "SELECT si.name, si.birthday, sdaa.cpf, sdaa.rg_number,
                    sdaa.nis, si.responsable_name, si.responsable_telephone
                FROM student_enrollment se
                JOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN classroom c ON se.classroom_fk = c.id
                WHERE c.id = :classroom AND ((`se`.`status` IN (1, 6, 7, 8, 9, 10) or `se`.`status` is null))
                GROUP BY si.name
                ORDER BY si.name;";

        $result = Yii::app()->db->createCommand($sql)
            ->bindParam(":classroom", $classroom)
            ->queryAll();

        $title = "RELATÓRIO DE ALUNOS POR TURMA (CPF, RG E NIS)<br>" . $classroomModel->name;


        return array("report" => $result, "title" => $title);


    }

    /**
     * Número de Alunos Matriculados por Período em todas as Escolas
     */
    public function getNumberOfStudentsEnrolledPerPeriodAllSchools(CHttpRequest $request): array
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

        return array("report" => $result, "allSchools" => $allSchools, "title" => $title);


    }

    /**
     * Número de Alunos Matriculados por Período por Escola
     */
    public function getNumberOfStudentsEnrolledPerPeriodPerSchool(CHttpRequest $request): array
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

        $title = "QUANTITATIVO DE ALUNOS MATRICULADOS POR PERÍODO<br>" . $school->name;

        return array("report" => $result, "allClassrooms" => $allClassrooms, "title" => $title);


    }

    /**
     * Número de Alunos Matriculados por Período por Turma
     */
    public function getNumberOfStudentsEnrolledPerPeriodPerClassroom(CHttpRequest $request): array
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

        $title = "QUANTITATIVO DE ALUNOS MATRICULADOS POR PERÍODO<br>" . $classroom->name;

        return array("report" => $result, "title" => $title);


    }

    /**
     * Alunos Beneficiários do Bolsa Família de todas as Turmas
     */
    public function getAllClassroomsReportOfStudentsBenefitingFromTheBF(): array
    {
        $sql = "SELECT si.name, si.birthday, sdaa.nis, si.responsable_name,
                        si.responsable_telephone, c.name AS classroom_name
                FROM student_identification si
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN student_enrollment se ON se.student_fk = si.id
                JOIN classroom c ON se.classroom_fk = c.id
                WHERE si.bf_participator = 1 AND c.school_year = :school_year
                    AND c.school_inep_fk = :school_inep_id AND ((`se`.`status` IN (1, 6, 7, 8, 9, 10) or `se`.`status` is null))
                GROUP BY si.name
                ORDER BY si.name;";

        $school = SchoolIdentification::model()->findByPk($this->currentSchool);
        $allSchools = false;
        $title = "BENEFICIÁRIOS DO BOLSA FAMÍLIA DE TODAS AS TURMAS<br>" . $school->name;

        $result = Yii::app()->db->createCommand($sql)
            ->bindParam(":school_year", $this->currentYear)
            ->bindParam(":school_inep_id", $this->currentSchool)
            ->queryAll();

        return array("report" => $result, "allSchools" => $allSchools, "title" => $title);


    }

    /**
     * Alunos Beneficiários do Bolsa Família de todas as Escolas
     */
    public function getAllSchoolsReportOfStudentsBenefitingFromTheBF(): array
    {
        $sql = "SELECT si.name, si.birthday, sdaa.nis, si.responsable_name,
                        si.responsable_telephone, si2.name AS school_name
                FROM student_identification si
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN student_enrollment se ON se.student_fk = si.id
                JOIN classroom c ON se.classroom_fk = c.id
                JOIN school_identification si2 ON c.school_inep_fk = si2.inep_id
                WHERE si.bf_participator = 1 AND c.school_year = :school_year
                    AND ((`se`.`status` IN (1, 6, 7, 8, 9, 10) or `se`.`status` is null))
                GROUP BY si.name
                ORDER BY si2.name, si.name;";

        $title = "BENEFICIÁRIOS DO BOLSA FAMÍLIA DE TODAS AS ESCOLAS";
        $allSchools = true;
        $result = Yii::app()->db->createCommand($sql)
            ->bindParam(":school_year", $this->currentYear)
            ->queryAll();

        return array("report" => $result, "allSchools" => $allSchools, "title" => $title);


    }

    /**
     * Alunos Beneficiários do Bolsa Família por Turma
     */
    public function getReportOfStudentsBenefitingFromTheBFPerClassroom(CHttpRequest $request): array
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
                     AND c.id = :classroom_id AND ((`se`.`status` IN (1, 6, 7, 8, 9, 10) or `se`.`status` is null))
                GROUP BY si.name
                ORDER BY si2.name, si.name;";

        $allSchools = false;
        $classroom = Classroom::model()->findByPk($classroomId);

        $title = "BENEFICIÁRIOS DO BOLSA FAMÍLIA<br> " . $classroom->name;
        $result = Yii::app()->db->createCommand($sql)
            ->bindParam(":school_year", $this->currentYear)
            ->bindParam(":classroom_id", $classroomId)
            ->queryAll();

        return array("report" => $result, "allSchools" => $allSchools, "title" => $title);


    }

    /**
     * Quantidade de Turmas por Escola
     */
    public function getNumberOfClassesPerSchool(): array
    {
        $criteria = new CDbCriteria();
        $criteria->condition = "school_year = '" . $this->currentYear . "'";

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
            )
            );
        }

        return array("report" => $result, "title" => $title);


    }

    /**
     * Ata de Formação de Professores
     */
    public function getTeacherTrainingReport(CHttpRequest $request): array
    {
        $classroom = $request->getPost('classroom');
        $day = $request->getPost('count_days');
        $hour = str_replace(":", "h", $request->getPost('hour'));
        $year = $request->getPost('year');
        $mounth = $request->getPost('mounth');
        $quarterly = $request->getPost('quarterly');
        $modelReport = $request->getPost('model_report');
        $schoolInepId = $this->currentSchool;

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
                ORDER BY se.daily_order, c.name";
        $result = Yii::app()->db->createCommand($sql)
            ->bindParam(":year", $year)
            ->bindParam(":school_inep_id", $schoolInepId)
            ->bindParam(":classroom", $classroom)
            ->queryAll();

        $disciplines = array();
        foreach ($result as $r) {
            array_push($disciplines, $r['discipline']);
        }
        $disciplines = array_unique($disciplines);

        $title = $modelReport . "º Ano - Formação de Professores na Modalidade Normal, em Nível Médio";

        return array(
            "classroom" => $result,
            "count_days" => $day,
            "mounth" => $mounth,
            "hour" => $hour,
            "quarterly" => $quarterly,
            "year" => $year,
            "title" => $title,
            "disciplines" => $disciplines
        );
    }

    /**
     * Dados Estatísticos
     */
    public function getStatisticalData(): array
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

        return array("report" => $result);


    }

    /**
     * Relatório de Transferência da Turma
     */
    public function getClassroomTransferReport(CHttpRequest $request): array
    {
        $classroomId = $request->getPost('classroom');
        $sql = "SELECT si.name, c.name AS classroom_name, si2.name AS school_name,
                sdaa.cpf, si.responsable_name, si.responsable_telephone, se.transfer_date
                FROM student_enrollment se
                JOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN classroom c ON se.classroom_fk = c.id
                JOIN school_identification si2 ON c.school_inep_fk = si2.inep_id
                WHERE c.id = :classroom_id AND se.transfer_date IS NOT NULL;";
        $result = Yii::app()->db->createCommand($sql)
            ->bindParam(":classroom_id", $classroomId)
            ->queryAll();

        $title = "RELATÓRIO TRANSFERÊNCIA DA TURMA";
        $header = $result[0]['classroom_name'];

        return array("report" => $result, "title" => $title, "header" => $header);
    }

    /**
     * Relatório de Transferência da Escola
     */
    public function getSchoolTransferReport(): array
    {
        $sql = "SELECT si.name, c.name AS classroom_name, si2.name AS school_name,
                sdaa.cpf, si.responsable_name, si.responsable_telephone, se.transfer_date
                FROM student_enrollment se
                JOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN classroom c ON se.classroom_fk = c.id
                JOIN school_identification si2 ON c.school_inep_fk = si2.inep_id
                WHERE si2.inep_id = :school_inep_id AND se.transfer_date IS NOT NULL;";
        $result = Yii::app()->db->createCommand($sql)
            ->bindParam(":school_inep_id", $this->currentSchool)
            ->queryAll();

        $title = "RELATÓRIO TRANSFERÊNCIA DA ESCOLA";
        $header = $result[0]['school_name'];

        return array("report" => $result, "title" => $title, "header" => $header);


    }

    /**
     * Relatório de Transferência de todas as Escolas
     */
    public function getAllSchoolsTransferReport(): array
    {
        $sql = "SELECT si.name, c.name AS classroom_name, si2.name AS school_name,
                sdaa.cpf, si.responsable_name, si.responsable_telephone, se.transfer_date
                FROM student_enrollment se
                JOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN classroom c ON se.classroom_fk = c.id
                JOIN school_identification si2 ON c.school_inep_fk = si2.inep_id
                WHERE se.transfer_date IS NOT NULL;";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $title = "RELATÓRIO TRANSFERÊNCIA DE TODAS AS ESCOLAS";
        $header = '';

        return array("report" => $result, "title" => $title, "header" => $header);


    }

    /**
     * Professores por Etapa
     */
    public function getTeachersByStage(): array
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

        return array("report" => $result);


    }

    /**
     * Professores por Escola
     */
    public function getTeachersBySchool(): array
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

        return array("report" => $result);


    }

    /**
     * Relatório CNS por Turma
     */
    public function getCnsPerClassroomReport(CHttpRequest $request): array
    {
        $classroomId = $request->getPost('cns_classroom_id');
        $sql = "SELECT
                si.name, si.birthday, sdaa.cns, c.name AS classroom_name,
                si.responsable_name, si.responsable_telephone
                FROM student_enrollment se
                JOIN classroom c ON se.classroom_fk = c.id
                JOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                WHERE c.id = :classroom_id AND ((`se`.`status` IN (1, 6, 7, 8, 9, 10) or `se`.`status` is null))
                GROUP BY name;";

        $result = Yii::app()->db->createCommand($sql)
            ->bindParam(":classroom_id", $classroomId)
            ->queryAll();

        $title = "RELATÓRIO CNS DA TURMA";
        $header = $result[0]['classroom_name'];

        return array("report" => $result, "title" => $title, "header" => $header);


    }

    /**
     * Relatório CNS de todas as Escolas
     */
    public function getCnsSchools(): array
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

        $result = Yii::app()->db->createCommand($sql)
            ->bindParam(":year", $this->currentYear)
            ->queryAll();
        $allSchools = true;
        $countTotal = true;
        $title = "RELATÓRIO CNS ESCOLAS";

        return array("report" => $result, "title" => $title, "allSchools" => $allSchools, "countTotal" => $countTotal);


    }

    /**
     * Relatório CNS por Escola
     */
    public function getCnsPerSchool(): array
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

        $result = Yii::app()->db->createCommand($sql)
            ->bindParam(":school_id", $this->currentSchool)
            ->bindParam(":year", $this->currentYear)
            ->queryAll();

        $countTotal = true;
        $title = "RELATÓRIO CNS DA ESCOLA";
        $header = $result[0]['school_name'];

        return array("report" => $result, "title" => $title, "header" => $header, "countTotal" => $countTotal);


    }

    /**
     * Relatório Trimestral
     */
    public function getQuarterlyReport(CHttpRequest $request): array
    {
        $studentId = $request->getPost('student');
        $classroomId = $request->getPost('quartely_report_classroom_student');
        $model = $request->getPost('model_quartely');
        $studentIdent = StudentIdentification::model()->findByPk($studentId);
        $studentEnrollment = StudentEnrollment::model()->findByAttributes(array('student_fk' => $studentId));
        $classroom = Classroom::model()->findByPk($classroomId);
        $classroomEtapa = EdcensoStageVsModality::model()->findByPk($classroom->edcenso_stage_vs_modality_fk);
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);
        $currentYear = $this->currentYear;

        // Verificação de Formato de data
        $dateFormatCorrect = false;
        $dateStr = $studentIdent->birthday;
        $dateFormat = 'd/m/Y';
        // Cria um objeto DateTime a partir da string de data e formato
        $date = DateTime::createFromFormat($dateFormat, $dateStr);
        // Verifica se a string de data original corresponde ao formato esperado
        if ($date && $date->format($dateFormat) === $dateStr) {
            $dateFormatCorrect = true;
        }

        $sql = "SELECT ii.name as instructor_name FROM classroom c
                JOIN instructor_teaching_data itd on itd.classroom_id_fk = c.id
                JOIN instructor_identification ii on itd.instructor_fk = ii.id
                WHERE c.id = :classroom_id AND itd.regent = 1;";

        $regentTeachers = Yii::app()->db->createCommand($sql)
            ->bindParam(":classroom_id", $classroom->id)
            ->queryAll();

        if ($model == 1) {
            $view = 'buzios/quarterly/QuarterlyReportFirstYear';
            $result = array(
                "student_identification" => $studentIdent,
                "student_enrollment" => $studentEnrollment,
                "classroom" => $classroom,
                "school" => $school,
                "current_year" => $currentYear,
                "regentTeachers" => $regentTeachers
            );
        } elseif ($model == 2) {
            $view = 'buzios/quarterly/QuarterlyReportSecondYear';
            $result = array(
                "student_identification" => $studentIdent,
                "student_enrollment" => $studentEnrollment,
                "classroom" => $classroom,
                "school" => $school,
                "current_year" => $currentYear,
                "regentTeachers" => $regentTeachers
            );
        } elseif ($model == 3) {
            $view = 'buzios/quarterly/QuarterlyReportThreeYear';
            $result = array(
                "student_identification" => $studentIdent,
                "student_enrollment" => $studentEnrollment,
                "classroom" => $classroom,
                "school" => $school,
                "current_year" => $currentYear,
                "regentTeachers" => $regentTeachers
            );
        } elseif ($model == 4) {
            $view = 'buzios/quarterly/QuarterlyReportNurseryrII';
            $result = array(
                "student_identification" => $studentIdent,
                "student_enrollment" => $studentEnrollment,
                "classroom" => $classroom,
                "classroom_etapa" => $classroomEtapa,
                "school" => $school,
                "current_year" => $currentYear,
                "dateFormatCorrect" => $dateFormatCorrect,
                "regentTeachers" => $regentTeachers
            );
        } elseif ($model == 5) {
            $view = 'buzios/quarterly/QuarterlyReportNurseryrIII';
            $result = array(
                "student_identification" => $studentIdent,
                "student_enrollment" => $studentEnrollment,
                "classroom" => $classroom,
                "classroom_etapa" => $classroomEtapa,
                "school" => $school,
                "current_year" => $currentYear,
                "dateFormatCorrect" => $dateFormatCorrect,
                "regentTeachers" => $regentTeachers
            );
        } elseif ($model == 6) {
            $view = 'buzios/quarterly/QuarterlyReportNurseryrIV';
            $result = array(
                "student_identification" => $studentIdent,
                "student_enrollment" => $studentEnrollment,
                "classroom" => $classroom,
                "classroom_etapa" => $classroomEtapa,
                "school" => $school,
                "current_year" => $currentYear,
                "dateFormatCorrect" => $dateFormatCorrect,
                "regentTeachers" => $regentTeachers
            );
        } elseif ($model == 7) {
            $view = 'buzios/quarterly/QuarterlyReportPreI';
            $result = array(
                "student_identification" => $studentIdent,
                "student_enrollment" => $studentEnrollment,
                "classroom" => $classroom,
                "classroom_etapa" => $classroomEtapa,
                "school" => $school,
                "current_year" => $currentYear,
                "dateFormatCorrect" => $dateFormatCorrect,
                "regentTeachers" => $regentTeachers
            );
        } elseif ($model == 8) {
            $view = 'buzios/quarterly/QuarterlyReportPreII';
            $result = array(
                "student_identification" => $studentIdent,
                "student_enrollment" => $studentEnrollment,
                "classroom" => $classroom,
                "classroom_etapa" => $classroomEtapa,
                "school" => $school,
                "current_year" => $currentYear,
                "dateFormatCorrect" => $dateFormatCorrect,
                "regentTeachers" => $regentTeachers
            );
        }

        return array("response" => $result, "view" => $view);


    }

    /**
     * Relatório Trimestral de Acompanhamento
     */
    public function getQuarterlyFollowUpReport(CHttpRequest $request): array
    {

        $classroomId = $request->getPost('quarterly_follow_up_classroom');
        $disciplineId = $request->getPost('quarterly_follow_up_disciplines');
        $classroom_model = Classroom::model()->findByPk($classroomId);
        $classroom_stage_name = $classroom_model->edcensoStageVsModalityFk->name;
        $discipline_model = EdcensoDiscipline::model()->findByPk($disciplineId);
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
            ->bindParam(":discipline_id", $disciplineId)
            ->bindParam(":classroom_id", $classroomId)
            ->queryAll();
        $turno = $result[0]['classroom_turn'];
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

        $students = Yii::app()->db->createCommand($sql)->bindParam(":classroom_id", $classroomId)->queryAll();

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
        foreach ($anos2 as $value) {
            if (strpos($stage_name, $value) !== false) {
                $anosTitulo = "4º E 5º ANOS";
                $anosVerify = 2;
                $anosPosition = $i + 4;
                $stageVerify = true;
                break;
            }
        }

        if (!$stageVerify) {
            $error = true;
            $message = "A turma " . $classroom_model->name . " não possui uma etapa correspondente ao relatório. Etapa da Turma: " . $classroom_stage_name;
        } else {
            $result = array(
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

        if ($result == null) {
            $error = true;
            $message = "A turma " . $classroom_model->name . " não possui professores para a disciplina de " . $discipline_model->name;
        }

        return array("error" => $error, "message" => $message, "response" => $result);


    }

    /**
     * Acompanhamento avaliativo dos alunos
     */
    public function getEvaluationFollowUpStudentsReport(CHttpRequest $request): array
    {
        $disciplineId = $request->getPost('evaluation_follow_up_disciplines');
        $classroomId = $request->getPost('evaluation_follow_up_classroom');
        $quarterly = $request->getPost('quarterly');

        $classroom = Classroom::model()->findByPk($classroomId);
        $discipline = EdcensoDiscipline::model()->findByPk($disciplineId);

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
            ->bindParam(":discipline_id", $disciplineId)
            ->bindParam(":classroom_id", $classroomId)
            ->queryAll();

        $sql = "SELECT si.name AS student_name FROM student_enrollment se
                JOIN student_identification si on si.id = se.student_fk
                WHERE se.classroom_fk = :classroom_id
                ORDER BY se.daily_order, si.name;";

        $students = Yii::app()->db->createCommand($sql)->bindParam(":classroom_id", $classroomId)->queryAll();

        $classroom_stage_name = $classroom->edcensoStageVsModalityFk->name;
        $parts = explode("-", $classroom_stage_name);
        $stage_name = trim($parts[1]);

        $anos1 = array("1º", "2º", "3º");
        $anos2 = array("4º", "5º");

        $anosTitulo = '';
        $anosVerify = 0;
        $anosPosition = 0;
        $stageVerify = false;

        foreach ($anos1 as $key => $value) {
            if (strpos($stage_name, $value) !== false) {
                $anosTitulo = "1º, 2º e 3º ANOS";
                $anosVerify = 1;
                $anosPosition = $key + 1;
                $stageVerify = true;
                break;
            }
        }

        foreach ($anos2 as $key => $value) {
            if (strpos($stage_name, $value) !== false) {
                $anosTitulo = "4º E 5º ANOS";
                $anosVerify = 2;
                $anosPosition = $key + 4;
                $stageVerify = true;
                break;
            }
        }

        if (!$stageVerify) {
            $error = true;
            $message = "A turma " . $classroom->name . " não possui uma etapa correspondente ao relatório. Etapa da Turma: " . $classroom_stage_name;
        }

        if ($instructor) {
            if ($students) {
                $result = array(
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
                $message = "A turma " . $classroom->name . " não possui alunos matriculados";
            }
        } else {
            $error = true;
            $message = "A turma " . $classroom->name . " não possui professores para a disciplina de " . $discipline->name;
        }

        return array("error" => $error, "message" => $message, "response" => $result);


    }

    /**
     * Ata de Conselho de Classe
     */
    public function getClassCouncilReport(CHttpRequest $request): array
    {
        $countDays = $request->getPost('count_days');
        $mounth = $request->getPost('mounth');
        $hour = str_replace(":", "h", $request->getPost('hour'));
        $quarterly = $request->getPost('quarterly');
        $schoolInepId = $this->currentSchool;
        $infantil = $request->getPost('infantil-model');
        $year = $request->getPost('year');
        $classroomId = $request->getPost('classroom');

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
            ORDER BY se.daily_order, s.name";

        $classrooms = Yii::app()->db->createCommand($sql)
            ->bindParam(":year", $year)
            ->bindParam(":school_inep_id", $schoolInepId)
            ->bindParam(":classroom_id", $classroomId)
            ->queryAll();

        $stageId = $classrooms[0]['stage_id'];
        $currentReport = 0;
        if ($stageId == 1 || $stageId == 2) {
            $currentReport = 1;
        } elseif ($stageId == 3 || $stageId == 7) {
            $currentReport = 2;
        } elseif ($stageId == 4) {
            $currentReport = 3;
        }

        $title = '';
        if ($infantil) {
            $title = "EDUCAÇÃO INFANTIL";
        }

        if ($classrooms[0] != null) {
            if ($currentReport == 1) {
                $view = 'buzios/quarterly/QuarterlyClassCouncil';
                $result = array(
                    "classroom" => $classrooms,
                    "count_days" => $countDays,
                    "mounth" => $mounth,
                    "hour" => $hour,
                    "quarterly" => $quarterly,
                    "year" => $year,
                    "title" => $title
                );
            } elseif ($currentReport == 2) {
                $view = 'buzios/quarterly/QuarterlyClassCouncilSixNineYear';
                $result = array(
                    "classroom" => $classrooms,
                    "count_days" => $countDays,
                    "mounth" => $mounth,
                    "hour" => $hour,
                    "quarterly" => $quarterly,
                    "year" => $year
                );
            } elseif ($currentReport == 3) {
                $view = 'buzios/quarterly/QuarterlyClassCouncilHighSchool';
                $result = array(
                    "classroom" => $classrooms,
                    "count_days" => $countDays,
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

        return array("error" => $error, "message" => $message, "view" => $view, "response" => $result);


    }

    /**
     * Relação Transporte Escolar
     */
    public function getStudentsUsingSchoolTransportationRelationReport(): array
    {
        $schoolInepId = $this->currentSchool;
        $year = $this->currentYear;
        $school = SchoolIdentification::model()->findByPk($schoolInepId);
        $sql = "SELECT DISTINCT si.inep_id,si.name,si.birthday,sd.residence_zone, sd.neighborhood, sd.address , se.*
                FROM (student_identification as si join student_enrollment as se on si.id = se.student_fk)
                join classroom as c on se.classroom_fk = c.id
                join student_documents_and_address as sd on si.id = sd.id
                where (se.public_transport = 1 or se.vehicle_type_bus=1) and se.school_inep_id_fk = :school_inep_id
                AND c.school_year = :year AND ((`se`.`status` IN (1, 6, 7, 8, 9, 10) or `se`.`status` is null)) order by si.name";

        $students = Yii::app()->db->createCommand($sql)
            ->bindParam(":school_inep_id", $schoolInepId)
            ->bindParam(":year", $year)
            ->queryAll();

        $sql1 = "select c.*, q.modality,q.stage
                from classroom as c join classroom_qtd_students as q
                on c.school_inep_fk = q.school_inep_fk
                where c.school_year = :year AND q.school_year = :year and c.school_inep_fk = :school_inep_id AND q.school_inep_fk = :school_inep_id  AND c.id = q.id
                order by name";

        $classrooms = Yii::app()->db->createCommand($sql1)
            ->bindParam(":school_inep_id", $schoolInepId)
            ->bindParam(":year", $year)
            ->queryAll();

        return array("school" => $school, "students" => $students, "classrooms" => $classrooms);
    }

    /**
     * Relação acessibilidade por Turma
     */
    public function getStudentsWithDisabilitiesPerClassroom(CHttpRequest $request): array
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

        return array('students' => $students, 'classroom' => $classroom);


    }

    /**
     * Relação acessibilidade de todas as Escolas
     */
    public function getStudentsWithDisabilitiesPerSchool(): array
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

        return array(
            'students' => $students,
            'schools' => $schools,
            'report' => $result
        );


    }

    /**
     * Relação Acessibilidade
     */
    public function getStudentsWithDisabilitiesRelationReport(): array
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
                    ((`se`.`status` IN (1, 6, 7, 8, 9, 10) or `se`.`status` is null))
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

        return array('school' => $school, 'students' => $students, 'classrooms' => $classrooms);


    }

    /**
     * Relação de alunos em ordem alfabética
     */
    public function getStudentsInAlphabeticalOrderRelationReport(): array
    {
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);

        $sql = "SELECT si.name AS studentName, si.inep_id AS studentInepId, se.classroom_inep_id, si.birthday,cq.*
                    FROM (student_identification AS si
                JOIN student_enrollment AS se ON si.id = se.student_fk)
                JOIN classroom_qtd_students AS cq ON cq.id = se.classroom_fk
                WHERE se.school_inep_id_fk = :school_inep_id_fk  AND
                      si.school_inep_id_fk = :school_inep_id_fk AND
                      cq.school_year = :school_year AND
                      ((`se`.`status` IN (1, 6, 7, 8, 9, 10) or `se`.`status` is null))
                ORDER BY si.name";

        $students = Yii::app()->db->createCommand($sql)
            ->bindParam(':school_inep_id_fk', $this->currentSchool)
            ->bindParam(':school_year', $this->currentYear)
            ->queryAll();

        return array(
            'school' => $school,
            'students' => $students
        );


    }

    /**
     * Relatório de Matrícula
     */
    public function getEnrollmentPerClassroomReport($classroomId): array
    {
        $sql = "SELECT * FROM classroom_enrollment
                WHERE `year` = :school_year AND
                classroom_id = :classroom_id
                ORDER BY daily_order, name;";

        $result = Yii::app()->db->createCommand($sql)
            ->bindParam(':school_year', $this->currentYear)
            ->bindParam('classroom_id', $classroomId)
            ->queryAll();

        $classroom = Classroom::model()->findByPk($classroomId);

        return array('report' => $result, 'classroom' => $classroom);


    }

    /**
     * Alunos com documentos pendentes
     */
    public function getStudentPendingDocument(): array
    {
        $sql = "SELECT *, d.name as nome_aluno, d.inep_id as inep_id
                    FROM student_enrollment se
                    JOIN classroom b ON(se.`classroom_fk`=b.id)
                    JOIN student_documents_and_address c ON(se.`student_fk`=c.`id`)
                    JOIN student_identification d ON(c.`id`=d.`id`)
                    WHERE b.`school_inep_fk` = :school_inep_fk AND
                          b.school_year = :school_year AND
                          ((`se`.`status` IN (1, 6, 7, 8, 9, 10) or `se`.`status` is null)) AND
                          (received_cc = 0 OR received_address = 0 OR received_photo = 0
                    OR received_nis = 0 OR received_responsable_rg = 0 OR received_responsable_cpf = 0)";

        $result = Yii::app()->db->createCommand($sql)
            ->bindParam(':school_inep_fk', $this->currentSchool)
            ->bindParam(':school_year', $this->currentYear)
            ->queryAll();

        return array('report' => $result, );


    }

    /**
     * Lista de Alunos
     */
    public function getStudentPerClassroom($classroomId): array
    {
        $sql = "SELECT * FROM classroom_enrollment
                    WHERE `year`  = :year AND
                    classroom_id = :classroom_id AND
                    ((`status` IN (1, 6, 7, 8, 9, 10) or `status` is null))
                ORDER BY name;";

        $result = Yii::app()->db->createCommand($sql)
            ->bindParam(':year', $this->currentYear)
            ->bindParam(':classroom_id', $classroomId)
            ->queryAll();

        $classroom = Classroom::model()->findByPk($classroomId);

        return array('report' => $result, 'classroom' => $classroom);


    }

    /**
     * Relação Cloc por Turma (descontinuada)
     */
    public function getClocPerClassroom($classroomId): array
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
                ((`status` IN (1, 6, 7, 8, 9, 10) or `status` is null))
        ORDER BY name;";

        $result = Yii::app()->db->createCommand($sql)
            ->bindParam(':school_year', $this->currentYear)
            ->bindParam(':classroom_id', $classroomId)
            ->queryAll();

        $classroom = Classroom::model()->findByPk($classroomId);

        return array('report' => $result, 'classroom' => $classroom);


    }

    /**
     * Relação de alunos por Turma
     */
    public function getStudentsByClassroomReport(): array
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
                      ((`se`.`status` IN (1, 6, 7, 8, 9, 10) or `se`.`status` is null))
                ORDER BY si.name";

        $students = Yii::app()->db->createCommand($sql)
            ->bindParam(':school_year', $this->currentYear)
            ->bindParam(':school_inep_id_fk', $this->currentSchool)
            ->queryAll();

        return array('school' => $school, 'classroom' => $classrooms, 'students' => $students);


    }

    /**
     * Alunos com idade entre 5 e 14 anos (SUS)
     */
    public function getStudentsBetween5And14YearsOldReport(): array
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
                      (q.status IN (1, 6, 7, 8, 9, 10) OR q.status IS NULL)
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
                      ((`se`.`status` IN (1, 6, 7, 8, 9, 10) or `se`.`status` is null))
                ORDER BY si.name ";

        $students = Yii::app()->db->createCommand($sql)
            ->bindParam(":year", $this->currentYear)
            ->bindParam(":school", $this->currentSchool)
            ->queryAll();

        return array('school' => $school, 'classroom' => $classrooms, 'students' => $students);


    }

    /**
     * Monitores de Atividade Complementar
     */
    public function getComplementarActivityAssistantByClassroomReport(): array
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

        return array("school" => $school, "classroom" => $classrooms, 'instructor' => $instructor);


    }

    /**
     * Relação componente curricular por docente
     */
    public function getDisciplineAndInstructorRelationReport(): array
    {
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);

        $sql = "SELECT c.*, q.modality, q.stage
                    FROM classroom AS c
                JOIN classroom_qtd_students AS q ON c.school_inep_fk = q.school_inep_fk
                WHERE c.school_year = :school_year AND
                      q.school_year = :school_year AND
                      c.school_inep_fk = :school_inep_fk AND
                      q.school_inep_fk = :school_inep_fk AND
                      c.id = q.id
                ORDER BY name";

        $classrooms = Yii::app()->db->createCommand($sql)
            ->bindParam(':school_year', $this->currentYear)
            ->bindParam(':school_inep_fk', $this->currentSchool)
            ->queryAll();
        foreach ($classrooms as &$classroom) {

            $sql = "SELECT ii.*, iv.scholarity, it.id AS teaching_data_id
                        FROM instructor_teaching_data it
                    JOIN instructor_identification ii ON ii.id = it.instructor_fk
                    LEFT JOIN instructor_variable_data iv ON iv.id = ii.id
                    WHERE it.classroom_id_fk = :classroom_id_fk AND
                          it.role = 1
                    ORDER BY ii.name";

            $classroom["instructors"] = Yii::app()->db->createCommand($sql)
                ->bindParam(':classroom_id_fk', $classroom["id"])
                ->queryAll();

            foreach ($classroom["instructors"] as &$instructor) {

                $sql = "SELECT ed.name FROM teaching_matrixes tm
                        JOIN curricular_matrix cm ON tm.curricular_matrix_fk = cm.id
                        JOIN edcenso_discipline ed ON ed.id = cm.discipline_fk
                        WHERE tm.teaching_data_fk = :teaching_data_fk";

                $instructor["disciplines"] = Yii::app()->db->createCommand($sql)
                    ->bindParam(':teaching_data_fk', $instructor["teaching_data_id"])
                    ->queryAll();
            }
            unset($instructor);
        }
        unset($classroom);

        return array('school' => $school, 'classroom' => $classrooms);


    }

    /**
     * Alunos com Idade Incompatível por Turma
     */
    public function getIncompatibleStudentAgeByClassroomReport(): array
    {
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);

        $sql = "SELECT c.*, q.modality,q.stage
                    FROM classroom AS c
                JOIN classroom_qtd_students AS q ON c.school_inep_fk = q.school_inep_fk
                WHERE c.school_year = :school_year AND
                      q.school_year = :school_year AND
                      c.school_inep_fk = :school_inep_fk AND
                      q.school_inep_fk = :school_inep_fk AND
                      c.id = q.id
                ORDER BY name";

        $classroom = Yii::app()->db->createCommand($sql)
            ->bindParam(':school_year', $this->currentYear)
            ->bindParam(':school_inep_fk', $this->currentSchool)
            ->queryAll();

        $sql = "SELECT se.classroom_fk,si.inep_id,si.name,si.birthday
                    FROM (student_identification AS si
                JOIN student_enrollment AS se ON si.id = se.student_fk )
                JOIN classroom AS c ON se.classroom_fk = c.id
                WHERE c.school_year = :school_year AND
                      se.school_inep_id_fk = :school_inep_id_fk AND
                      ((`se`.`status` IN (1, 6, 7, 8, 9, 10) or `se`.`status` is null))";

        $students = Yii::app()->db->createCommand($sql)
            ->bindParam(':school_year', $this->currentYear)
            ->bindParam(':school_inep_id_fk', $this->currentSchool)
            ->queryAll();

        return array('school' => $school, 'classroom' => $classroom, 'students' => $students);


    }

    /**
     * Alunos com matrícula em outra escola (descontinuada)
     */
    public function getStudentsWithOtherSchoolEnrollmentReport(): array
    {
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);

        $sql = "SELECT si.inep_id AS student_id , si.name AS student_name, si.birthday
                        AS student_birthday, s1.school1, s1.school2, c1.name AS classroom_name1
                FROM(
                    SELECT DISTINCT least(se.school_inep_id_fk, s2.school_inep_id_fk) as school1,
                                    greatest(se.school_inep_id_fk, s2.school_inep_id_fk) as school2,
                                    se.student_fk, se.id se1, s2.id se2, se.classroom_fk classroom1,
                                    s2.classroom_fk classroom2
                    FROM student_enrollment se
                    JOIN student_enrollment s2 ON se.student_fk = s2.student_fk
                    WHERE se.school_inep_id_fk != s2.school_inep_id_fk AND
                          se.school_inep_id_fk = :school
                ) AS s1
                JOIN classroom c1 ON(s1.classroom1 = c1.id)
                JOIN classroom c2 ON(s1.classroom2 = c2.id)
                JOIN student_identification si ON (si.id = s1.student_fk)
                WHERE c1.school_year = :year";

        $students = Yii::app()->db->createCommand($sql)
            ->bindParam(":school", $this->currentSchool)
            ->bindParam(":year", $this->currentYear)
            ->queryAll();

        return array('school' => $school, 'students' => $students);


    }

    /**
     * Auxiliar Educacional por Turma
     */
    public function getEducationalAssistantPerClassroomReport(): array
    {
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);

        $sql = "SELECT c.*, q.modality,q.stage
                    FROM classroom as c
                JOIN classroom_qtd_students AS q ON c.school_inep_fk = q.school_inep_fk
                WHERE c.school_year = :year AND
                      q.school_year = :year AND
                      c.school_inep_fk = :school AND
                      q.school_inep_fk = :school AND
                      c.id = q.id
                ORDER BY name";

        $classrooms = Yii::app()->db->createCommand($sql)
            ->bindParam(":school", $this->currentSchool)
            ->bindParam(":year", $this->currentYear)
            ->queryAll();

        foreach ($classrooms as &$classroom) {
            $sql = "SELECT DISTINCT c.id AS classroomID ,c.name AS className,id.inep_id,id.name,
                                    id.birthday_date, iv.scholarity
                        FROM instructor_teaching_data AS i
                    JOIN instructor_identification AS id ON id.id = i.instructor_fk
                    JOIN instructor_variable_data AS iv ON iv.id = id.id
                    JOIN classroom AS c ON i.classroom_id_fk = c.id
                    WHERE c.id = :id AND (i.role = 8 OR i.role = 2)
                    ORDER BY id.name";
            $classroom["professors"] = Yii::app()->db->createCommand($sql)->bindParam(":id", $classroom["id"])->queryAll();
        }
        unset($classroom);

        return array('school' => $school, 'classrooms' => $classrooms);


    }

    /**
     * Relação de turmas sem instrutor
     */
    public function getClassroomWithoutInstructorRelationReport(): array
    {
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);

        $sql_classrooms =
            "SELECT c.name, esvm.name stage
            FROM classroom c
            LEFT JOIN instructor_teaching_data itd ON itd.classroom_id_fk = c.id
            LEFT JOIN edcenso_stage_vs_modality esvm ON c.edcenso_stage_vs_modality_fk = esvm.id
            WHERE school_inep_fk = :school_id AND c.school_year = :school_year
            GROUP by c.id
            HAVING count(itd.id) = 0
            ORDER BY c.id";

        $classroom = Yii::app()->db->createCommand($sql_classrooms)
            ->bindParam(":school_year", $this->currentYear)
            ->bindParam(":school_id", $this->currentSchool)
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
            ->bindParam(":school_year", $this->currentYear)
            ->bindParam(":school_id", $school->inep_id)
            ->queryAll();

        return array('school' => $school, 'classroom' => $classroom, 'disciplina' => $disciplina);


    }

    /**
     * Relação de Número de Alunos e Professores por Turma
     */
    public function getStudentInstructorNumbersRelationReport(): array
    {
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);

        $sql = "SELECT q.*, c.mais_educacao_participator,c.inep_id
                    FROM classroom as c
                JOIN classroom_qtd_students AS q ON c.id = q.id
                WHERE c.school_year = :school_year AND
                      q.school_inep_fk = :school_inep_fk
                ORDER BY q.name";
        $classrooms = Yii::app()->db->createCommand($sql)
            ->bindParam(':school_year', $this->currentYear)
            ->bindParam(':school_inep_fk', $this->currentSchool)
            ->queryAll();

        $sql = "SELECT i.role, i.classroom_inep_id,c.id as classroomId
                    FROM instructor_teaching_data AS i
                JOIN classroom AS c ON i.classroom_id_fk = c.id
                WHERE i.school_inep_id_fk = :school_inep_id_fk AND
                      c.school_year = :school_year";

        $instrutors = Yii::app()->db->createCommand($sql)
            ->bindParam(':school_inep_id_fk', $this->currentSchool)
            ->bindParam(':school_year', $this->currentYear)
            ->queryAll();

        return array('school' => $school, 'classroom' => $classrooms, 'instructor' => $instrutors);


    }

    /**
     * Numero de profissionais da escola por turma
     */
    public function getSchoolProfessionalNumberByClassroomReport(): array
    {
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);

        $sql = "SELECT c.inep_id, q.*
                    FROM classroom AS c
                JOIN classroom_qtd_students AS q ON c.school_inep_fk = q.school_inep_fk
                WHERE c.school_year = :school_year AND
                      q.school_year = :school_year and
                      c.school_inep_fk = :school_inep_fk AND
                      q.school_inep_fk = :school_inep_fk AND
                      c.id = q.id
                ORDER BY c.name";

        $classrooms = Yii::app()->db->createCommand($sql)
            ->bindParam(':school_year', $this->currentYear)
            ->bindParam(':school_inep_fk', $this->currentSchool)
            ->queryAll();

        $sql = "SELECT i.role, i.classroom_inep_id,c.name
                    FROM instructor_teaching_data AS i
                JOIN classroom AS c ON i.classroom_id_fk = c.id
                WHERE i.school_inep_id_fk = :school_inep_id_fk AND
                      c.school_year = :school_year";

        $role = Yii::app()->db->createCommand($sql)
            ->bindParam(':school_year', $this->currentYear)
            ->bindParam(':school_inep_id_fk', $this->currentSchool)
            ->queryAll();

        return array('school' => $school, 'role' => $role, 'classroom' => $classrooms);


    }

    /**
     * Alunos por Turma
     */
    public function getStudentByClassroom(CHttpRequest $request): array
    {
        $classroomId = $request->getPost('classroom');
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
                c.school_inep_fk = :school_inep_fk AND
                c.id = :classroom_id
            GROUP BY s.name
            ORDER BY c.id";

        $classrooms = Yii::app()->db->createCommand($sql)
            ->bindParam(":year", $this->currentYear)
            ->bindParam(":classroom_id", $classroomId)
            ->bindParam(":school_inep_fk", $this->currentSchool)
            ->queryAll();

        return array("classroom" => $classrooms);


    }

    /**
     * Análise Comparativa de Matrículas
     */
    public function getEnrollmentComparativeAnalysis(): array
    {
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);

        $sql = "SELECT * FROM classroom_qtd_students
                WHERE `school_year` >= :school_year-1 AND school_inep_fk = :school_inep_fk
                ORDER BY name;";

        $classrooms = Yii::app()->db->createCommand($sql)
            ->bindParam(':school_year', $this->currentYear)
            ->bindParam(':school_inep_fk', $this->currentSchool)
            ->queryAll();

        $sql = "SELECT `c`.`id` AS `classe_id` , count(`s`.`id`) AS `contador`
                    FROM ((`student_identification` `s`
                JOIN `student_enrollment` `se` ON((`s`.`id` = `se`.`student_fk`)))
                JOIN `classroom` `c` ON((`se`.`classroom_fk` = `c`.`id`)))
                WHERE `c`.`school_year` = :school_year-1 AND school_inep_fk = :school_inep_fk
                GROUP BY `c`.`id`";

        $enrollment1 = Yii::app()->db->createCommand($sql)
            ->bindParam(':school_year', $this->currentYear)
            ->bindParam(':school_inep_fk', $this->currentSchool)
            ->queryAll();

        $sql = "SELECT `c`.`id` AS `classe_id` , count(`s`.`id`) AS `contador`
                    FROM ((`student_identification` `s`
                JOIN `student_enrollment` `se` ON((`s`.`id` = `se`.`student_fk`)))
                JOIN `classroom` `c` ON((`se`.`classroom_fk` = `c`.`id`)))
                WHERE `c`.`school_year` = :school_year AND school_inep_fk = :school_inep_fk
                GROUP BY `c`.`id`";

        $enrollment2 = Yii::app()->db->createCommand($sql)
            ->bindParam(':school_year', $this->currentYear)
            ->bindParam(':school_inep_fk', $this->currentSchool)
            ->queryAll();

        return array(
            'classrooms' => $classrooms,
            'school' => $school,
            'matricula1' => $enrollment1,
            'matricula2' => $enrollment2
        );


    }

    /**
     * Número de Alunos por Turma
     */
    public function getNumberStudentsPerClassroom(): array
    {
        $sql = "SELECT * FROM classroom_qtd_students
                WHERE school_year  = :school_year AND
                      school_inep_fk = :school_inep_fk
                ORDER BY name;";

        $result = Yii::app()->db->createCommand($sql)
            ->bindParam(':school_year', $this->currentYear)
            ->bindParam(':school_inep_fk', $this->currentSchool)
            ->queryAll();

        return array('report' => $result);


    }

    /**
     * Número de Alunos por Etapa da Turma (descontinuada)
     */
    public function getClocReport(): array
    {
        $sql = "SELECT
        `c`.`school_inep_fk` AS `school_inep_fk`,
        `c`.`id`             AS `id`,
        `c`.`name`           AS `name`,
        CONCAT_WS(' - ',CONCAT_WS(':',`c`.`initial_hour`,`c`.`initial_minute`),CONCAT_WS(':',`c`.`final_hour`,`c`.`final_minute`)) AS `time`,
        (CASE `c`.`assistance_type`
            WHEN 0 THEN 'NÃO SE APLICA'
            WHEN 1 THEN 'CLASSE HOSPITALAR'
            WHEN 2 THEN 'UNIDADE DE ATENDIMENTO SOCIOEDUCATIVO'
            WHEN 3 THEN 'UNIDADE PRISIONAL ATIVIDADE COMPLEMENTAR'
            ELSE 'ATENDIMENTO EDUCACIONALESPECIALIZADO (AEE)' END) AS `assistance_type`,
        (CASE `c`.`modality`
            WHEN 1 THEN 'REGULAR'
            WHEN 2 THEN 'ESPECIAL'
            ELSE 'EJA' END) AS `modality`,
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

        return array('report' => $result);


    }

    /**
     * Professores por Turma
     */
    public function getInstructorsPerClassroom(): array
    {
        $sql = "SELECT * FROM classroom_instructors
                WHERE school_year = :school_year AND
                      school_inep_fk = :school_inep_fk
                ORDER BY name;";

        $result = Yii::app()->db->createCommand($sql)
            ->bindParam(':school_year', $this->currentYear)
            ->bindParam(':school_inep_fk', $this->currentSchool)
            ->queryAll();

        return array('report' => $result);


    }

    /**
     * Relatório do Bolsa Família
     * @done s3 - Verificar se a frequencia dos últimos 3 meses foi adicionada(existe pelo menso 1 class cadastrado no mês)
     * @done S3 - Selecionar todas as aulas de todas as turmas ativas dos ultimos 3 meses
     * @done s3 - Pegar todos os alunos matriculados nas turmas atuais.
     * @done s3 - Enviar dados pre-processados para a página
     */
    public function getAttendanceForBF(CHttpRequest $request): array
    {
        $month = (int) date('m');
        $monthI = $month <= 3 ? 1 : $month - 3;
        $monthF = $month <= 1 ? 1 : $month - 1;
        $year = date('Y');

        $groupByClassroom = [];

        //FUNDAMENTAL MENOR
        $arrFields = [":year" => $year, ":school" => Yii::app()->user->school];
        $conditions = " AND c.school_inep_fk = :school";
        $conditions .= " AND c.id = :id_classroom ";
        $arrFields[':id_classroom'] = $request->getPost('classroom');
        $criteria = new CDbCriteria();
        $criteria->alias = "c";
        $criteria->join = "join edcenso_stage_vs_modality svm on svm.id = c.edcenso_stage_vs_modality_fk";
        $criteria->condition = "c.school_year = :year and svm.id >= 14 and svm.id <= 16 " . $conditions;
        $criteria->params = $arrFields;
        $criteria->order = "c.name";
        $classrooms = Classroom::model()->findAll($criteria);
        foreach ($classrooms as $classroom) {
            $days = [];
            $faultDays = [];
            $schedules = Schedule::model()->findAll(
                "classroom_fk = :classroom_fk and month >= :monthI and month <= :monthF and unavailable = 0",
                [
                    "classroom_fk" => $classroom->id,
                    ":monthI" => $monthI,
                    ":monthF" => $monthF
                ]
            );
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
        $arrFields[':id_classroom'] = $request->getPost('classroom');

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
        $command->where(
            'c.school_year = :year and (svm.id < 14 or svm.id > 16) '
            . $conditions,
            $arrFields
        );
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

        return array('reports' => $groupByClassroom);


    }

    /**
     * Alunos Participantes do Bolsa Família
     */
    public function getStudentsParticipatingInBF(): array
    {
        $sql = "SELECT su.name, su.inep_id, su.birthday, cl.name AS turma
                    FROM student_enrollment se
                JOIN classroom cl ON(se.classroom_fk = cl.id)
                JOIN school_identification si ON (si.inep_id = cl.school_inep_fk)
                JOIN student_identification su ON(su.id= se.student_fk)
                WHERE bf_participator = 1 AND
                    si.`inep_id` = :school_inep_id AND
                    ((`se`.`status` IN (1, 6, 7, 8, 9, 10) or `se`.`status` is null))
                ORDER BY name;";

        $result = Yii::app()->db->createCommand($sql)
            ->bindParam(':school_inep_id', $this->currentSchool)
            ->queryAll();

        return array('report' => $result);


    }

    /**
     * Diário Eletrônico
     */
    public function getElectronicDiary(): array
    {
        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $criteria = new CDbCriteria();
            $criteria->alias = "c";
            $criteria->join = ""
                . " join instructor_teaching_data on instructor_teaching_data.classroom_id_fk = c.id "
                . " join instructor_identification on instructor_teaching_data.instructor_fk = instructor_identification.id ";
            $criteria->condition = "c.school_year = :school_year and c.school_inep_fk = :school_inep_fk and instructor_identification.users_fk = :users_fk";
            $criteria->order = "name";
            $criteria->params = array(':school_year' => $this->currentYear, ':school_inep_fk' => $this->currentSchool, ':users_fk' => Yii::app()->user->loginInfos->id);

            $classrooms = Classroom::model()->findAll($criteria);
        } else {
            $classrooms = Classroom::model()->findAll('school_year = :school_year and school_inep_fk = :school_inep_fk order by name', ['school_year' => $this->currentYear, 'school_inep_fk' => $this->currentSchool]);
        }

        return array('classrooms' => $classrooms, 'schoolyear' => $this->currentYear);


    }

    /**
     * Alunos fora da cidade
     */
    public function getOutOfTownStudents(): array
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
                WHERE si.`inep_id` = :school_inep_id AND ((`se`.`status` IN (1, 6, 7, 8, 9, 10) or `se`.`status` is null))
                AND (si.edcenso_city_fk != std.edcenso_city_fk)
                AND (cl.school_year = :school_year)
                ORDER BY NAME;";

        $result = Yii::app()->db->createCommand($sql)
            ->bindParam(':school_year', $this->currentYear)
            ->bindParam(':school_inep_id', $this->currentSchool)
            ->queryAll();

        return array('report' => $result);


    }

    /**
     * Alunos Cardápios Especiais
     */
    public function getStudentSpecialFood(): array
    {
        $sql = "SELECT si.inep_id , si.name as nome_aluno, si.birthday, sr.*
                    FROM student_identification si
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

        return array('report' => $result);


    }

    /**
     * Carregar caixa de seleção de disciplinas em Diário Eletrônico
     */
    public function getDisciplines(CHttpRequest $request): void
    {
        $classroom = Classroom::model()->findByPk($request->getPost('classroom'));
        $disciplinesLabels = ClassroomController::classroomDisciplineLabelArray();
        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $sql = "SELECT ed.id FROM teaching_matrixes tm
                    JOIN instructor_teaching_data itd ON itd.id = tm.teaching_data_fk
                    JOIN instructor_identification ii ON ii.id = itd.instructor_fk
                    JOIN curricular_matrix cm ON cm.id = tm.curricular_matrix_fk
                    JOIN edcenso_discipline ed ON ed.id = cm.discipline_fk
                    WHERE ii.users_fk = :userid AND itd.classroom_id_fk = :crid
                    ORDER BY ed.name";

            $disciplines = Yii::app()->db->createCommand($sql)
                ->bindParam(":userid", Yii::app()->user->loginInfos->id)
                ->bindParam(":crid", $classroom->id)
                ->queryAll();

            foreach ($disciplines as $discipline) {
                echo htmlspecialchars(CHtml::tag('option', array('value' => $discipline['id']), CHtml::encode($disciplinesLabels[$discipline['id']]), true));
            }
        } else {
            echo CHtml::tag('option', array('value' => ""), CHtml::encode('Selecione...'), true);

            $sql = "SELECT curricular_matrix.discipline_fk
                        FROM curricular_matrix
                    WHERE stage_fk = :stage_fk AND school_year = :year";

            $classr = Yii::app()->db->createCommand($sql)
                ->bindParam(":stage_fk", $classroom->edcenso_stage_vs_modality_fk)
                ->bindParam(":year", $this->currentYear)
                ->queryAll();

            foreach ($classr as $i => $discipline) {
                if (isset($discipline['discipline_fk'])) {
                    echo htmlspecialchars(CHtml::tag('option', array('value' => $discipline['discipline_fk']), CHtml::encode($disciplinesLabels[$discipline['discipline_fk']]), true));
                }
            }
        }
    }

    /**
     * Carregar a caixa de seleção com os alunos matriculados pelo id da turma
     */
    public function getStudentClassroomsOptions($id): void
    {
        $classroom = Classroom::model()->findByPk($id);
        $enrollments = $classroom->studentEnrollments;
        foreach ($enrollments as $enrollment) {
            echo htmlspecialchars(CHtml::tag('option', array('value' => $enrollment->studentFk->id), $enrollment->studentFk->name, true));
        }
    }

    /**
     * Carregar a caixa de seleção com os alunos matriculados
     */
    public function getEnrollments(CHttpRequest $request): void
    {
        $criteria = new CDbCriteria();
        $criteria->alias = "se";
        $criteria->join = "join student_identification si on si.id = se.student_fk";
        $criteria->condition = "classroom_fk = :classroom_fk";
        $criteria->params = array(':classroom_fk' => $request->getPost('classroom'));
        $criteria->order = "si.name";
        $studentEnrollments = StudentEnrollment::model()->findAll($criteria);
        echo CHtml::tag('option', array('value' => ""), CHtml::encode('Selecione...'), true);
        foreach ($studentEnrollments as $studentEnrollment) {
            echo htmlspecialchars(CHtml::tag('option', array('value' => $studentEnrollment['id']), $studentEnrollment->studentFk->name, true));
        }
    }

    /**
     * Monta o relatório de frequência
     */
    private function getFrequencyData($initialDate, $finalDate, $fundamentalMaior, $classroom): array
    {
        $arr = explode('/', $initialDate);
        $initialDate = $arr[2] . "-" . $arr[1] . "-" . $arr[0];
        $arr = explode('/', $finalDate);
        $finalDate = $arr[2] . "-" . $arr[1] . "-" . $arr[0];
        $students = [];
        if ($fundamentalMaior == "1") {
            $schedules = Schedule::model()
                ->findAll(
                    "classroom_fk = :classroom_fk and date_format(concat(" . Yii::app()->user->year . ", '-', month, '-', day), '%Y-%m-%d') between :initial_date and :final_date and discipline_fk = :discipline_fk and unavailable = 0 order by month, day, schedule",
                    ["classroom_fk" => $classroom, "initial_date" => $initialDate, "final_date" => $finalDate, "discipline_fk" => $_POST["discipline"]]
                );
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
                unset($student);
            }
        } else {
            $schedules = Schedule::model()
                ->findAll(
                    "classroom_fk = :classroom_fk and date_format(concat(" . Yii::app()->user->year . ", '-', month, '-', day), '%Y-%m-%d') between :initial_date and :final_date and unavailable = 0 order by month, day",
                    ["classroom_fk" => $classroom, "initial_date" => $initialDate, "final_date" => $finalDate]
                );
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

    /**
     * Monta o relatório de notas
     */
    private function getGradesData($classroomId, $studentId)
    {
        $classroom = Classroom::model()
            ->with('edcensoStageVsModalityFk.gradeUnities')
            ->find("t.id = :classroom", [":classroom" => $classroomId]);

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
            ")->bindParam(":classroom", $classroomId)->queryAll();

            foreach ($disciplines as $discipline) {
                $arr["disciplineName"] = $discipline["name"];

                $arr["grades"] = [];
                foreach ($gradeUnitiesByClassroom as $gradeUnity) {
                    array_push($arr["grades"], $gradeUnity->type == "UR"
                        ? ["unityId" => $gradeUnity->id, "unityGrade" => "", "unityRecoverGrade" => "", "gradeUnityType" => $gradeUnity->type]
                        : ["unityId" => $gradeUnity->id, "unityGrade" => "", "gradeUnityType" => $gradeUnity->type]);
                }

                $gradeResult = GradeResults::model()->find("enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk", ["enrollment_fk" => $studentId, "discipline_fk" => $discipline["id"]]);
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

    /**
     * Monta os dados do relatório de frequência e notas
     */
    public function getElectronicDiaryData(CHttpRequest $request): void
    {
        $result = [];
        if ($request->getPost("type") === "frequency") {
            $initialDate = $request->getPost("initialDate");
            $finalDate = $request->getPost("finalDate");
            $fundamentalMaior = $request->getPost("fundamentalMaior");
            $classroom = $request->getPost("classroom");
            $result = $this->getFrequencyData($initialDate, $finalDate, $fundamentalMaior, $classroom);
        } elseif ($request->getPost("type") === "gradesByStudent") {
            $result = $this->getGradesData($request->getPost("classroom"), $request->getPost("student"));
        }
        echo json_encode($result);
    }
}
