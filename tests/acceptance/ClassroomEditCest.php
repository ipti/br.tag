<?php

require_once __DIR__ . "/../acceptance/ClassroomCest.php";
class ClassroomEditCest
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
     * Edita turmas.
     * Tipo de Mediação Didático-Pedagógica - Educação a Distância - EAD.
     */
    public function editClassroomEAD(AcceptanceTester $teste)
    {
        sleep(5);
        $classroom = new ClassroomCest();
        $addClassroom = $classroom->allFieldsAddClassroomEAD($teste);
        $robots = new ClassroomRobots($teste);
        $robots->pageClassroom();

        $search = $addClassroom['name'];

        $robots->search($search);
        sleep(2);
        $robots->btnEdit();
        sleep(2);

        $builder = new ClassroomBuilder();
        $dataClassroom = $builder->buildCompleted();

        // Classroom
        $robots->name($dataClassroom['name']);
        $robots->typeMediation($dataClassroom['pedagogical_mediation_type_EAD']);
        $robots->modality($dataClassroom['modality']);
        $robots->educationCourse($dataClassroom['edcenso_professional_education_course_fk']);
        $robots->initialTime($dataClassroom['initial_time']);
        $robots->finalTime($dataClassroom['final_time']);
        $robots->turn($dataClassroom['turn']);
        $robots->days($dataClassroom['week_days_tuesday']);
        $robots->typeService($dataClassroom['assistance_type_schooling']);
        $robots->typeService($dataClassroom['assistance_complementary_activity']);
        $robots->activitiesEducation($dataClassroom['aee_optical_and_non']);
        $robots->btn2Instructors();

        // Instructors
        sleep(2);
        $robots->btnInstructor();
        sleep(2);
        $robots->instructorsToClassroom($dataClassroom['Instructors']);
        $robots->role($dataClassroom['Role']);
        $robots->contractType($dataClassroom['ContractType']);
        $robots->createNewComponent();
        sleep(2);

        $robots->btnCriar();
        sleep(2);

        $robots->editSucess();
    }

    /**
     * Edita turmas.
     * Tipo de Mediação Didático-Pedagógica - Presencial.
     */
    public function editClassroomInPerson(AcceptanceTester $teste)
    {
        sleep(5);
        $classroom = new ClassroomCest();
        $addClassroom = $classroom->allFieldsAddInPerson($teste);
        $robots = new ClassroomRobots($teste);
        $robots->pageClassroom();

        $search = $addClassroom['name'];

        $robots->search($search);
        sleep(2);
        $robots->btnEdit();
        sleep(2);

        $builder = new ClassroomBuilder();
        $dataClassroom = $builder->buildCompleted();

        // Classroom
        $robots->name($dataClassroom['name']);
        $robots->typeMediation($dataClassroom['pedagogical_mediation_type_IN_PERSON']);
        $robots->modality($dataClassroom['modality']);
        $robots->location($dataClassroom['diff_location']);
        $robots->educationCourse($dataClassroom['edcenso_professional_education_course_fk']);
        $robots->initialTime($dataClassroom['initial_time']);
        $robots->finalTime($dataClassroom['final_time']);
        $robots->turn($dataClassroom['turn']);
        $robots->days($dataClassroom['week_days_tuesday']);
        $robots->typeService($dataClassroom['assistance_type_schooling']);
        $robots->typeService($dataClassroom['assistance_complementary_activity']);
        $robots->activitiesEducation($dataClassroom['aee_optical_and_non']);
        $robots->btn2Instructors();

        // Instructors
        sleep(2);
        $robots->btnInstructor();
        sleep(2);
        $robots->instructorsToClassroom($dataClassroom['Instructors']);
        $robots->role($dataClassroom['Role']);
        $robots->contractType($dataClassroom['ContractType']);
        $robots->createNewComponent();
        sleep(2);

        $robots->btnCriar();
        sleep(2);

        $robots->editSucess();
    }
}
