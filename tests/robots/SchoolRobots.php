<?php

class SchoolRobots
{
    public AcceptanceTester $tester;
    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    public function pageCreate()
    {
        $this->tester->amOnPage('?r=school/create');
    }

    public function btnCreate()
    {
        $this->tester->click('.save-school-button');
    }

    public function btn1Indentify()
    {
        $this->tester->waitForElementVisible('#tab-school-indentify');
        $this->tester->executeJS("document.querySelector('#tab-school-indentify a').click();");
    }

    public function btn2Address()
    {
        $this->tester->waitForElementVisible('#tab-school-addressContact');
        $this->tester->executeJS("document.querySelector('#tab-school-addressContact a').click();");
    }

    public function btn3Structure()
    {
        $this->tester->waitForElementVisible('#tab-school-structure');
        $this->tester->executeJS("document.querySelector('#tab-school-structure a').click();");
    }

    public function btn4Equipment()
    {
        $this->tester->waitForElementVisible('#tab-school-equipment');
        $this->tester->executeJS("document.querySelector('#tab-school-equipment a').click();");
    }

    public function btn5Manager()
    {
        $this->tester->waitForElementVisible('#tab-school-manager');
        $this->tester->executeJS("document.querySelector('#tab-school-manager a').click();");
    }

    public function btn6Education()
    {
        $this->tester->waitForElementVisible('#tab-school-education');
        $this->tester->executeJS("document.querySelector('#tab-school-education a').click();");
    }

    public function name($name)
    {
        $this->tester->fillField("#SchoolIdentification_name", $name);
    }

    public function codInep($codInep)
    {
        $this->tester->executeJS("
            document.querySelector('.t-field-text > #SchoolIdentification_inep_id').value = '$codInep';
        ");
    }

    public function administrativeDependency($administrativeDependency)
    {
        $this->tester->selectOption('#SchoolIdentification_administrative_dependence', $administrativeDependency);
    }

    public function regulation($regulation)
    {
        $this->tester->selectOption('#SchoolIdentification_regulation', $regulation);
    }

    public function linkedSchool()
    {
        $script = "document.querySelector('#SchoolIdentification_linked_mec').click();";
        $this->tester->executeJS($script);
    }

    public function organRegulation()
    {
        $script = "document.querySelector('#SchoolIdentification_regulation_organ_federal').click();";
        $this->tester->executeJS($script);
    }

    public function codIes($codIes)
    {
        $this->tester->fillField('#SchoolIdentification_ies_code', $codIes);
    }

    public function state($state)
    {
        $this->tester->waitForElement('#SchoolIdentification_edcenso_uf_fk');
        $this->tester->selectOption('#SchoolIdentification_edcenso_uf_fk', $state);
        $this->tester->wait(5);
    }

    public function city($city)
    {
        $this->tester->waitForElement('#SchoolIdentification_edcenso_city_fk');
        $this->tester->selectOption('#SchoolIdentification_edcenso_city_fk', $city);
        $this->tester->wait(5);
    }

    public function location($location)
    {
        $this->tester->selectOption('#SchoolIdentification_location', $location);
    }

    public function district($district)
    {
        $this->tester->selectOption('#SchoolIdentification_edcenso_district_fk', $district);
    }

    public function locationDifferent($location)
    {
        $this->tester->selectOption('#SchoolIdentification_id_difflocation', $location);
    }

    public function linkedUnity($linkedUnity)
    {
        $this->tester->selectOption('#SchoolIdentification_offer_or_linked_unity', $linkedUnity);
    }

    public function operationLocation()
    {
        $script = "document.querySelector('#SchoolStructure_operation_location_building').click();";
        $this->tester->executeJS($script);
    }

    public function occupation($occupation)
    {
        $this->tester->selectOption('#SchoolStructure_building_occupation_situation', $occupation);
    }

    public function dependencies()
    {
        $script = "document.querySelector('#SchoolStructure_dependencies_warehouse').click();";
        $this->tester->executeJS($script);
    }

    public function water()
    {
        $script = "document.querySelector('#SchoolStructure_water_supply_public').click();";
        $this->tester->executeJS($script);
    }

    public function energety()
    {
        $script = "document.querySelector('#SchoolStructure_energy_supply_public').click();";
        $this->tester->executeJS($script);
    }

    public function sewage()
    {
        $script = "document.querySelector('#SchoolStructure_sewage_public').click();";
        $this->tester->executeJS($script);
    }

    public function destinationCollect()
    {
        $script = "document.querySelector('#SchoolStructure_garbage_destination_collect').click();";
        $this->tester->executeJS($script);
    }

    public function garbage()
    {
        $script = "document.querySelector('#SchoolStructure_treatment_garbage_parting_garbage').click();";
        $this->tester->executeJS($script);
    }

    public function acessability()
    {
        $script = "document.querySelector('#SchoolStructure_acessability_handrails_guardrails').click();";
        $this->tester->executeJS($script);
    }

    public function association()
    {
        $script = "document.querySelector('#SchoolStructure_board_organ_association_parent').click();";
        $this->tester->executeJS($script);
    }

    public function food()
    {
        $script = "document.querySelector('#SchoolStructure_supply_food').click();";
        $this->tester->executeJS($script);
    }

    public function alimenting ($alimenting)
    {
        $this->tester->selectOption('#SchoolStructure_feeding', $alimenting);
    }

    public function satellite()
    {
        $script = "document.querySelector('#SchoolStructure_equipments_satellite_dish').click();";
        $this->tester->executeJS($script);
    }

    public function multimedea()
    {
        $script = "document.querySelector('#SchoolStructure_equipments_multimedia_collection').click();";
        $this->tester->executeJS($script);
    }

    public function internet()
    {
        $script = "document.querySelector('#SchoolStructure_internet_access_administrative').click();";
        $this->tester->executeJS($script);
    }

    public function acessLocal()
    {
        $script = "document.querySelector('#SchoolStructure_internet_access_local_cable').click();";
        $this->tester->executeJS($script);
    }

    public function managerName($managerName)
    {
        $this->tester->fillField('#ManagerIdentification_name', $managerName);
    }

    public function managerBirthday($birthday)
    {
        $this->tester->fillField('#ManagerIdentification_birthday_date', $birthday);
    }

    public function colorRace($color)
    {
        $this->tester->selectOption('#ManagerIdentification_color_race', $color);
    }

    public function sex($sex)
    {
        $this->tester->selectOption('#ManagerIdentification_sex', $sex);
    }

    public function managerNacionality($nacionality)
    {
        $this->tester->selectOption('#ManagerIdentification_nationality', $nacionality);
        $this->tester->wait(2);
    }

    public function managerFiliation($filiation)
    {
        $this->tester->selectOption('#ManagerIdentification_filiation', $filiation);
    }

    public function zone($zone)
    {
        $this->tester->selectOption('#ManagerIdentification_residence_zone', $zone);
    }
}
