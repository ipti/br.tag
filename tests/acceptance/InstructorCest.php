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

    public function addInstructor(AcceptanceTester $test)
    {
        sleep(5);
        $robots = new InstructorRobots($test);
        $builder = new InstructorBuilder();
        $robots->pageAddInstructor();

        $robots->name($builder->instructor->name);
        $robots->email($builder->instructor->email);
        $robots->nationality($builder->instructor->nationality);
        $robots->cpf($builder->instructorDocumentsAddress->cpf);
        $robots->state($builder->instructorDocumentsAddress->edcenso_uf_fk);
        $robots->state($builder->instructorDocumentsAddress->edcenso_city_fk);
        $robots->nis($builder->instructor->nis);
        $robots->dateOfBirth($builder->instructor->birthday_date);
        $robots->gender($builder->instructor->color_race);
        $robots->filiationSelect($builder->instructor->filiation);
        $robots->cep($builder->instructorDocumentsAddress->cep);
        $robots->address($builder->instructorDocumentsAddress->address);
        $robots->number($builder->instructorDocumentsAddress->address_number);
        $robots->neighborhood($builder->instructorDocumentsAddress->neighborhood);
        $robots->location($builder->instructorDocumentsAddress->diff_location);
        $robots->zone($builder->instructorDocumentsAddress->area_of_residence);
        $robots->scholarity($builder->instructorVariable->scholarity);

        $robots->btnProximo();
        sleep(2);
    }
}
