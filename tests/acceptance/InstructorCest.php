<?php

require_once 'vendor/autoload.php';
require_once __DIR__."/../robots/LoginRobots.php";
require_once __DIR__."/../robots/InstructorRobots.php";
require_once __DIR__."/../builders/InstructorBuilder.php";

class InstructorCest
{
    public function _before(AcceptanceTester $tester)
    {

        $user = "admin";
        $password = "p@s4ipti";

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($user);
        $robots->fieldPassword($password);
        $robots->submit();
        sleep(2);
    }

    public function identificationPage($robots, $builderInstructor)
    {
        // Página de Identificação
        $robots->name($builderInstructor->instructor["name"]);
        $robots->email($builderInstructor->instructor["email"]);
        $robots->nationality($builderInstructor->instructor["nationality"]);
        sleep(2);
        $robots->cpf($builderInstructor->instructorDocumentsAddress["cpf"]);
        $robots->state($builderInstructor->instructor["edcenso_uf_fk"]);
        sleep(2);
        $robots->city($builderInstructor->instructor["edcenso_city_fk"]);
        $robots->nis($builderInstructor->instructor["nis"]);
        $robots->dateOfBirth($builderInstructor->instructor["birthday_date"]);
        $robots->gender($builderInstructor->instructor["sex"]);
        $robots->colorRace($builderInstructor->instructor["color_race"]);
        $robots->filiationSelect($builderInstructor->instructor["filiation"]);
        $robots->filiationSelect1($builderInstructor->instructor["filiation_1"]);
    }

    public function addressPage($robots, $builderInstructor)
    {
        // Página de Endereço
        $robots->stateAddress($builderInstructor->instructor["edcenso_uf_fk"]);
        sleep(3);
        $robots->cityAddress($builderInstructor->instructor["edcenso_city_fk"]);
        $robots->address($builderInstructor->instructorDocumentsAddress["address"]);
        $robots->number($builderInstructor->instructorDocumentsAddress["address_number"]);
        $robots->neighborhood($builderInstructor->instructorDocumentsAddress["neighborhood"]);
        $robots->location($builderInstructor->instructorDocumentsAddress["diff_location"]);
        $robots->zone($builderInstructor->instructorDocumentsAddress["area_of_residence"]);
    }
    public function addInstructorNoDegree(AcceptanceTester $test)
    {
        $robots = new InstructorRobots($test);
        $builder = new InstructorBuilder();
        $robots->pageAddInstructor();
        $builderInstructor = $builder->buildComplete();

        // Preencher página de identificação
        $this->identificationPage($robots, $builderInstructor);
        $robots->btnProximo();

        // Preencher página de endereço
        $this->addressPage($robots, $builderInstructor);
        $robots->btnProximo();

        // Página de Dados Educacionais
        $robots->scholarity("7");
        $robots->btnSave();
        $test->see("O professor ".$builder->instructor["name"]." foi criado com sucesso!");
    }

    public function addInstructorWithDegree(AcceptanceTester $test)
    {
        $robots = new InstructorRobots($test);
        $builder = new InstructorBuilder();
        $robots->pageAddInstructor();
        $builderInstructor = $builder->buildComplete();
        $builderInstructorScholarity = $builder->scholarityDegree();

        // Preencher página de identificação
        $this->identificationPage($robots, $builderInstructor);
        $robots->btnProximo();

        // Preencher página de endereço
        $this->addressPage($robots, $builderInstructor);
        $robots->btnProximo();

        // Página de Dados Educacionais
        $robots->scholarity($builderInstructorScholarity->instructorVariable['scholarity']);
        sleep(2);
        $robots->highCourseSituation($builderInstructorScholarity->instructorVariable['high_education_situation_1']);
        $robots->highCourseCode($builderInstructorScholarity->instructorVariable['high_education_course_code_1_fk']);
        $robots->highCourseYear($builderInstructorScholarity->instructorVariable['high_education_final_year_1']);
        $robots->btnSave();
        sleep(10);

        $test->see("O professor ".$builder->instructor["name"]." foi criado com sucesso!");
    }
}
