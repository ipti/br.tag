<?php

class InstructorCest
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

    /**
     * Adicionar instructor sem ensino superior.
     */
    public function addInstructorNoDegree(AcceptanceTester $test)
    {
        $robots = new InstructorRobots($test);
        $builder = new InstructorBuilder();
        $robots->pageAddInstructor();
        $builderInstructor = $builder->buildComplete();

        // Preencher página de identificação
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
        $robots->btnProximo();

        // Preencher página de endereço
        // Página de Endereço
        $robots->stateAddress($builderInstructor->instructor["edcenso_uf_fk"]);
        sleep(3);
        $robots->cityAddress($builderInstructor->instructor["edcenso_city_fk"]);
        $robots->address($builderInstructor->instructorDocumentsAddress["address"]);
        $robots->number($builderInstructor->instructorDocumentsAddress["address_number"]);
        $robots->neighborhood($builderInstructor->instructorDocumentsAddress["neighborhood"]);
        $robots->location($builderInstructor->instructorDocumentsAddress["diff_location"]);
        $robots->zone($builderInstructor->instructorDocumentsAddress["area_of_residence"]);
        $robots->btnProximo();

        // Página de Dados Educacionais
        $robots->scholarity("7");
        $robots->btnSave();
        sleep(5);
        $test->see('Professor adicionado com sucesso!');
        $test->canSeeInCurrentUrl('?r=instructor/index');
    }

    /**
     * Adicionar professor com graduação.
     */
    public function addInstructorWithDegree(AcceptanceTester $test)
    {
        $robots = new InstructorRobots($test);
        $builder = new InstructorBuilder();
        $robots->pageAddInstructor();
        $builderInstructor = $builder->buildComplete();
        $builderInstructorScholarity = $builder->scholarityDegree();

        // Preencher página de identificação
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
        $robots->btnProximo();

        // Preencher página de endereço
        // Página de Endereço
        $robots->stateAddress($builderInstructor->instructor["edcenso_uf_fk"]);
        sleep(3);
        $robots->cityAddress($builderInstructor->instructor["edcenso_city_fk"]);
        $robots->address($builderInstructor->instructorDocumentsAddress["address"]);
        $robots->number($builderInstructor->instructorDocumentsAddress["address_number"]);
        $robots->neighborhood($builderInstructor->instructorDocumentsAddress["neighborhood"]);
        $robots->location($builderInstructor->instructorDocumentsAddress["diff_location"]);
        $robots->zone($builderInstructor->instructorDocumentsAddress["area_of_residence"]);
        $robots->btnProximo();

        // Página de Dados Educacionais
        $robots->scholarity($builderInstructorScholarity->instructorVariable['scholarity']);
        sleep(2);
        $robots->highCourseSituation($builderInstructorScholarity->instructorVariable['high_education_situation_1']);
        $robots->highCourseCode($builderInstructorScholarity->instructorVariable['high_education_course_code_1_fk']);
        $robots->highCourseYear($builderInstructorScholarity->instructorVariable['high_education_final_year_1']);
        $robots->btnSave();
        sleep(10);

        $test->see('Professor adicionado com sucesso!');
        $test->canSeeInCurrentUrl('?r=instructor/index');
    }
}
