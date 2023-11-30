<?php

require_once __DIR__ . "\\MatrixCest.php";

class ClassroomCest
{
    public function _before(AcceptanceTester $tester)
    {
        $builder = new LoginBuilder();
        $login = $builder->buildCompleted();

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($login['user']);
        $robots->fieldPassword($login['secret']);
        $robots->submit();
        sleep(2);
    }

    // tests

    /**
     * Adicionar turmas, não preenchendo nenhum campo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function fieldsNotFilledIn(AcceptanceTester $teste)
    {
        sleep(5);
        $robots = new ClassroomRobots($teste);
        $robots->pageAddClassroom();
        $robots->btnCriar();

        $teste->see('Campo Nome é obrigatório.
        Campo Modalidade é obrigatório.
        Campo Etapa de Ensino é obrigatório.
        Campo Horário Inicial é obrigatório.
        Campo Horário Final é obrigatório.
        Campo Dias da semana é obrigatório. Selecione ao menos um dia.
        Campo Tipo de Mediação Didático-Pedagógica é obrigatório.
        Campo Tipos de Atendimento é obrigatório. Selecione ao menos uma opção.');
    }

    /**
     * Adicionar turma, preenchendo apenas os campos obrigatórios.
     * Tipo de Mediação Didático-Pedagógica - Educação a Distância - EAD.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function addClassroomEAD(AcceptanceTester $teste)
    {
        sleep(5);
        $robots = new ClassroomRobots($teste);
        $robots->pageAddClassroom();

        $builder = new ClassroomBuilder();
        $dataClassroom = $builder->buildCompleted();

        $robots->name($dataClassroom['name']);
        $robots->stageVsModalaty($dataClassroom['edcenso_stage_vs_modality_fk']);
        $robots->typeMediation($dataClassroom['pedagogical_mediation_type_EAD']);
        $robots->modality($dataClassroom['modality']);
        $robots->initialTime($dataClassroom['initial_time']);
        $robots->finalTime($dataClassroom['final_time']);
        $robots->days($dataClassroom['week_days_monday']);
        $robots->typeService($dataClassroom['assistance_complementary_activity']);
        sleep(2);
        $robots->activitiesComplementary($dataClassroom['complementary_activity_type_1']);
        $robots->btnCriar();
        sleep(2);

        $robots->addSucess();

        return $dataClassroom;
    }

    /**
     * Adicionar turma, preenchendo apenas os campos obrigatórios.
     * Tipo de Mediação Didático-Pedagógica - Presencial.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function addClassroomInPerson(AcceptanceTester $teste)
    {
        sleep(5);
        $robots = new ClassroomRobots($teste);
        $robots->pageAddClassroom();

        $builder = new ClassroomBuilder();
        $dataClassroom = $builder->buildCompleted();

        $robots->name($dataClassroom['name']);
        $robots->stageVsModalaty($dataClassroom['edcenso_stage_vs_modality_fk']);
        $robots->typeMediation($dataClassroom['pedagogical_mediation_type_IN_PERSON']);
        $robots->modality($dataClassroom['modality']);
        $robots->location($dataClassroom['diff_location']);
        $robots->initialTime($dataClassroom['initial_time']);
        $robots->finalTime($dataClassroom['final_time']);
        $robots->days($dataClassroom['week_days_monday']);
        $robots->typeService($dataClassroom['assistance_complementary_activity']);
        sleep(2);
        $robots->activitiesComplementary($dataClassroom['complementary_activity_type_1']);
        $robots->btnCriar();
        sleep(2);

        $robots->addSucess();

        return $dataClassroom;
    }

    /**
     * Adicionar turma, preenchendo todos os campos.
     * Tipo de Mediação Didático-Pedagógica - Educação a Distância - EAD.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function allFieldsAddClassroomEAD(AcceptanceTester $teste)
    {
        sleep(5);
        $matrix = new MatrixCest();
        $addMatrix = $matrix->addMatrix($teste);

        $robots = new ClassroomRobots($teste);
        $robots->pageAddClassroom();

        $builder = new ClassroomBuilder();
        $dataClassroom = $builder->buildCompleted();

        // Classroom
        $robots->name($dataClassroom['name']);
        $robots->stageVsModalaty($addMatrix['stages']);
        $robots->typeMediation($dataClassroom['pedagogical_mediation_type_EAD']);
        $robots->modality($dataClassroom['modality']);
        $robots->educationCourse($dataClassroom['edcenso_professional_education_course_fk']);
        $robots->initialTime($dataClassroom['initial_time']);
        $robots->finalTime($dataClassroom['final_time']);
        $robots->turn($dataClassroom['turn']);
        $robots->days($dataClassroom['week_days_monday']);
        $robots->typeService($dataClassroom['assistance_complementary_activity']);
        sleep(2);
        $robots->activitiesComplementary($dataClassroom['complementary_activity_type_1']);
        $robots->activitiesEducation($dataClassroom['aee_braille']);
        $robots->btn2Instructors();

        // Instructors
        sleep(2);
        $robots->btnInstructor();
        sleep(2);
        $robots->instructorsToClassroom($dataClassroom['Instructors']);
        $robots->disciplines($addMatrix['disciplines']);
        $robots->role($dataClassroom['Role']);
        $robots->contractType($dataClassroom['ContractType']);
        $robots->createNewComponent();
        sleep(2);

        $robots->btnCriar();
        sleep(2);

        $robots->addSucess();

        return $dataClassroom;
    }

    /**
     * Adicionar turma, preenchendo todos os campos.
     * Tipo de Mediação Didático-Pedagógica - Presencial
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function allFieldsAddInPerson(AcceptanceTester $teste)
    {
        sleep(5);
        $matrix = new MatrixCest();
        $addMatrix = $matrix->addMatrix($teste);

        $robots = new ClassroomRobots($teste);
        $robots->pageAddClassroom();

        $builder = new ClassroomBuilder();
        $dataClassroom = $builder->buildCompleted();

        // Classroom
        $robots->name($dataClassroom['name']);
        $robots->stageVsModalaty($addMatrix['stages']);
        $robots->typeMediation($dataClassroom['pedagogical_mediation_type_IN_PERSON']);
        $robots->modality($dataClassroom['modality']);
        $robots->location($dataClassroom['diff_location']);
        $robots->educationCourse($dataClassroom['edcenso_professional_education_course_fk']);
        $robots->initialTime($dataClassroom['initial_time']);
        $robots->finalTime($dataClassroom['final_time']);
        $robots->turn($dataClassroom['turn']);
        $robots->days($dataClassroom['week_days_monday']);
        $robots->typeService($dataClassroom['assistance_complementary_activity']);
        sleep(2);
        $robots->activitiesComplementary($dataClassroom['complementary_activity_type_1']);
        $robots->activitiesEducation($dataClassroom['aee_braille']);
        $robots->btn2Instructors();

        // Instructors
        sleep(2);
        $robots->btnInstructor();
        sleep(2);
        $robots->instructorsToClassroom($dataClassroom['Instructors']);
        $robots->disciplines($addMatrix['disciplines']);
        $robots->role($dataClassroom['Role']);
        $robots->contractType($dataClassroom['ContractType']);
        $robots->createNewComponent();
        sleep(2);

        $robots->btnCriar();
        sleep(2);

        $robots->addSucess();

        return $dataClassroom;
    }
}
