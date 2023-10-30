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
        sleep(2);
        $robots = new InstructorRobots($test);
        $builder = new InstructorBuilder();
        $robots->pageAddInstructor();
        $builderInstructor = $builder->buildComplete();

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
        sleep(2);
        $robots->btnProximo();
        $robots->cep($builderInstructor->instructorDocumentsAddress["cep"]);
        $robots->cityAddress($builderInstructor->instructor["edcenso_uf_fk"]);
        $robots->stateAddress($builderInstructor->instructor["edcenso_city_fk"]);

        sleep(5);
        $robots->address($builderInstructor->instructorDocumentsAddress["address"]);
        $robots->number($builderInstructor->instructorDocumentsAddress["address_number"]);
        $robots->neighborhood($builderInstructor->instructorDocumentsAddress["neighborhood"]);
        $robots->location($builderInstructor->instructorDocumentsAddress["diff_location"]);
        $robots->zone($builderInstructor->instructorDocumentsAddress["area_of_residence"]);
        $robots->btnProximo();
        $robots->scholarity($builderInstructor->instructorVariable["scholarity"]);
        $robots->btnSave();
        sleep(30);
        // $test->pause();
    }
}
