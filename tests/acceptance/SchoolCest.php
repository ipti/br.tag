<?php

class SchoolCest
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
     * Adiciona uma escola, prenchendo apenas os campos obrigatórios.
     * Unidade vinculada de Ed Básica ou ofertante de Ensino Superior: Não.
     */
    public function addSchoolRequired(AcceptanceTester $teste)
    {
        sleep(5);
        $builder = new SchoolBuilder();
        $dataBuilder = $builder->buildCompleted();

        $robots = new SchoolRobots($teste);
        $robots->pageCreate();
        $robots->name($dataBuilder['name']);
        $robots->codInep($dataBuilder['inep_id']);
        $robots->administrativeDependency($dataBuilder['administrative_dependence']);
        $robots->regulation($dataBuilder['regulation']);
        $robots->linkedSchool();
        $robots->organRegulation();
        // $robots->codIes($dataBuilder['ies_code']);
        $robots->btn2Address();

        sleep(2);
        $robots->state($dataBuilder['edcenso_uf_fk']);
        $robots->city($dataBuilder['edcenso_city_fk']);
        $robots->location($dataBuilder['location']);
        $robots->district($dataBuilder['edcenso_district_fk']);
        $robots->locationDifferent($dataBuilder['id_difflocation']);
        $robots->linkedUnity($dataBuilder['no_linked_unity']);
        $robots->btn3Structure();

        sleep(2);
        $robots->operationLocation();
        $robots->numberClassroom($dataBuilder['classroom_count']);
        $robots->occupation($dataBuilder['building_occupation_situation']);
        $robots->dependencies();
        $robots->water();
        $robots->energety();
        $robots->sewage();
        $robots->destinationCollect();
        $robots->garbage();
        $robots->acessability();
        $robots->association();
        $robots->food();
        $robots->alimenting($dataBuilder['feeding']);
        $robots->btn4Equipment();
        sleep(2);

        $robots->satellite();
        $robots->multimedea();
        $robots->internet();
        $robots->acessLocal();
        $robots->btn5Manager();
        sleep(2);

        $robots->managerName($dataBuilder['name_manager']);
        $robots->managerBirthday($dataBuilder['birthday_date_manager']);
        $robots->colorRace($dataBuilder['color_race']);
        $robots->sex($dataBuilder['sex']);
        $robots->managerNacionality($dataBuilder['nationality']);
        $robots->managerFiliation($dataBuilder['filiation_no_declared']);
        $robots->zone($dataBuilder['residence_zone']);
        $robots->btn6Education();

        sleep(2);
        $robots->btnCreate();
        sleep(5);

        $robots->saveSucess();

    }
}
