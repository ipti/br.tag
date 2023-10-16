<?php

require_once 'vendor/autoload.php';
require_once __DIR__."/../robots/LoginRobots.php";
require_once __DIR__.'/../robots/StudentsRobots.php';
require_once __DIR__.'/../providers/CustomProvider.php';

class StudentsCest
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

    // tests

    /**
     * Adicionar (rápido) estudantes, preenchendo apenas campos obrigatórios.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function addStudentsRapidFieldsRequired(AcceptanceTester $teste)
    {
        sleep(5);
        $faker = Faker\Factory::create();
        $robots = new StudentsRobots($teste);
        $robots->pageRapidAddStudents();

        $name = $faker->name();
        $dateOfBirth = $faker->date('d/m/Y');
        $gender = $faker->randomElement(array (1,2));
        $color = $faker->randomElement(array (0,1,2,3,4,5));
        $nationality = '1';
        $state = '28';
        $city = '2800308';
        $filiation = $faker->randomElement(array (0,1));
        $zone = $faker->randomElement(array (1,2));

        // students
        $robots->name($name);
        $robots->dateOfBirth($dateOfBirth);
        $robots->gender($gender);
        $robots->color($color);
        $robots->nationality($nationality);
        sleep(2);
        $robots->state($state);
        sleep(2);
        $robots->city($city);
        $robots ->btnProximo();
        sleep(2);

        // filiation
        $robots->filiation($filiation);
        $robots ->btnProximo();
        sleep(2);

        // address
        $robots->zone($zone);
        $robots ->btnProximo();
        sleep(2);
        
        // matriculation
        $robots->btnAddMatriculation();
        sleep(2);
        $robots ->btnProximo();

        // health
        $robots->btnCriar();

        $teste->see('O Cadastro de ' . $name . ' foi criado com sucesso!');
        $teste->canSeeInCurrentUrl('?r=student/index&');
    }

    /**
     * Adicionar (rápido) estudantes, preenchendo todos os campos.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function addStudentsRapidAllFilledIn (AcceptanceTester $teste)
    {
        sleep(5);
        $faker = Faker\Factory::create('pt_BR');
        $robots = new StudentsRobots($teste);
        $robots->pageRapidAddStudents();

        $name = $faker->name();
        $civilName = $faker->name();
        $dateOfBirth = $faker->date('d/m/Y');
        $cpf = $faker->cpf(false);
        $gender = $faker->randomElement(array (1,2));
        $color = $faker->randomElement(array (0,1,2,3,4,5));
        $nationality = '1';
        $state = '28';
        $city = '2800308';
        $filiation = $faker->randomElement(array (0,1));
        $responsable = $faker->randomElement(array (0,1,2));
        $zone = $faker->randomElement(array (1,2));
        $classroom = $faker->randomElement(array (7,3,2,1));

        // students
        $robots->name($name);
        $robots->civilNamebox();
        sleep(1);
        $robots->civilName($civilName);
        $robots->dateOfBirth($dateOfBirth);
        $robots->cpf($cpf);
        $robots->gender($gender);
        $robots->color($color);
        $robots->nationality($nationality);
        sleep(2);
        $robots->state($state);
        sleep(2);
        $robots->city($city);
        $robots ->btnProximo();
        sleep(2);

        // filiation
        $robots->filiation($filiation);
        $robots->responsable($responsable);
        $robots ->btnProximo();
        sleep(2);

        // address
        $robots->zone($zone);
        $robots ->btnProximo();
        sleep(2);
        
        // matriculation
        $robots->btnAddMatriculation();
        sleep(2);
        $robots->classroom($classroom);
        $robots ->btnProximo();

        // health
        $robots->deficiency();
        $robots->typeDeficiency();
        $robots->resourcesInep();
        $robots->vaccine();
        $robots->restrictions();
        $robots->btnCriar();
        sleep(2);

        $teste->see('O Cadastro de ' . $name . ' foi criado com sucesso!');
        $teste->canSeeInCurrentUrl('?r=student/index&');
    }

    /**
     * Adicionar (normal) estudantes, preenchidos apenas campos obrigatórios.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function addStudentsFieldsRequired(AcceptanceTester $teste)
    {
        //Data Students
        sleep(5);
        $teste->amOnPage('?r=student/create');
        $robots = new StudentsRobots($teste);
        $faker = Faker\Factory::create('pt_BR');

        $name = $faker->name();
        $dateOfBirth = $faker->date('d/m/Y');
        $gender = $faker->randomElement(array (1,2));
        $color = $faker->randomElement(array (0,1,2,3,4,5));
        $nationality = '1';
        $state = '28';
        $city = '2800308';
        $filiation = $faker->randomElement(array (0,1));
        $zone = $faker->randomElement(array (1,2));

        $robots->name($name);
        $robots->dateOfBirth($dateOfBirth);
        $robots->gender($gender);
        $robots->color($color);
        $robots->nationality($nationality);
        $robots->state($state);
        sleep(2);
        $robots->city($city);
        $robots->btnProximo();
        sleep(2);

        // filiation
        $robots->filiation($filiation);
        $robots->btnProximo();
        sleep(2);

        // social data
        $robots->btnProximo();
        sleep(2);
        
        // residence
        $robots->zone($zone);
        $robots->btnProximo();
        sleep(2);

        // registration
        $robots->btnProximo();
        sleep(2);

        // health
        $robots->btnCriar();

        $teste->see('O Cadastro de ' . $name . ' foi criado com sucesso!');
        $teste->canSeeInCurrentUrl('?r=student/index&');
    }

    /**
     * Adicionar (normal) estudantes, não preenchido nenhum campo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function fieldsNotFilledIn (AcceptanceTester $teste)
    {
        //Data Students
        sleep(5);
        $teste->amOnPage('?r=student/create');
        $robots = new StudentsRobots($teste);
        $robots->btnProximo();
        sleep(2);

        // filiation
        $robots->btnProximo();
        sleep(2);

        // social data
        $robots->btnProximo();
        sleep(2);
        
        // residence
        $robots->btnProximo();
        sleep(2);

        // registration
        $robots->btnProximo();
        sleep(2);

        // health
        $robots->btnCriar();

        $teste->see('Campo Nome é obrigatório.
        Campo Data de Nascimento é obrigatório.
        Campo Sexo é obrigatório.
        Campo Cor / Raça é obrigatório.
        Campo Filiação é obrigatório.
        Campo Nacionalidade é obrigatório.
        Campo País de origem é obrigatório.
        Campo Localização / Zona de residência é obrigatório.');
    }

    /**
     * Adicionar (normal) estudantes, preenchidos todos os campos.
     * Dados Sociais - Modelo Novo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function allFilledInNewCivil (AcceptanceTester $teste)
    {
        sleep(5);
        $faker = Faker\Factory::create('pt_BR');
        $fakerCustom = new CustomProvider($faker);
        $robots = new StudentsRobots($teste);
        $robots->pageAddStudents();


        $name = $faker->name();
        $civilName = $faker->name();
        $dateOfBirth = $faker->date('d/m/Y');
        $cpf = $faker->cpf();
        $gender = $faker->randomElement(array (1,2));
        sleep(5);
        $color = $faker->randomElement(array (0,1,2,3,4,5));
        $nationality = '1';
        $state = '28';
        $city = '2800308';
        $email = $faker->email();
        $scholarity = $faker->randomElement(array (1,2,3,4));
        $filiation = $faker->randomElement(array (0,1));
        $responsable = $faker->randomElement(array (0,1,2));
        $telephone = $faker->cellphoneNumber();
        $nameResponsable = $faker->name();
        $emailResponsable = $faker->email();
        $responsableJob = $faker->jobTitle();
        $scholarityRespons = $faker->randomElement(array (1,2,3,4));
        $rgResposable = $faker->rg();
        $cpfResponsable = $faker->cpf();
        $civilNovo = '2';
        $numberRegistration = $fakerCustom->matriculaRegistroCivil();
        $numerCns = $fakerCustom->cnsNumber();
        $numberIdentity = $faker->rg();
        $rgOrgan = '10'; //SSP
        $identityDate = $faker->date('d/m/Y');
        $identyUF = '28'; //SERGIPE
        $justice = $faker->randomElement(array (0,1,2));
        $justification = $faker->randomElement(array (1,2));
        $nis = $fakerCustom->nisNumber();
        $idInep = $fakerCustom->inepId();
        $stateAddress = '28';
        $cep = $faker->postcode();
        $cityAddress = '2800308';
        $address = $faker->streetName();
        $neighborhood = $faker->region();
        $number = $faker->buildingNumber();
        $complement = $fakerCustom->complementLocation();
        $location = $faker->randomElement(array (1,2,3,7));
        $zone = $faker->randomElement(array (1,2));
        $classroom = $faker->randomElement(array (7,3,2,1));
        $ticketType = $faker->randomElement(array (1,2,3));
        $ticketDate = $faker->date('d/m/Y');
        $situationSerie = $faker->randomElement(array (0,1,2));
        $registrationStatus = $faker->randomElement(array (1,2,3,4,5,6,7,8,9,10,11));
        $situationYear = $faker->randomElement(array (0,1,2,3,4,5));
        $unifiedClassroom = $faker->randomElement(array (1,2));
        $schooling = $faker->randomElement(array (1,2,3));
        $stage = $faker->randomElement(array (0,1,2,3,4,5,6,7));
        $teachStage = '1';
        $transportResponsable = $faker->randomElement(array (1,2));

        //Data Students
        $robots->name($name);
        $robots->civilNamebox();
        sleep(1);
        $robots->civilName($civilName);
        $robots->dateOfBirth($dateOfBirth);
        $robots->cpf($cpf);
        $robots->gender($gender);
        $robots->color($color);
        $robots->nationality($nationality);
        $robots->state($state);
        sleep(2);
        $robots->city($city);
        $robots->email($email);
        $robots->scholarity($scholarity);
        $robots->btnProximo();
        sleep(2);

        // filiation
        $robots->filiation($filiation);
        $robots->responsable($responsable);
        $robots->responsableTelephone($telephone);
        $robots->nameResponsable($nameResponsable);
        $robots->emailResponsable($emailResponsable);
        $robots->responsableJob($responsableJob);
        $robots->scholarityResponsable($scholarityRespons);
        $robots->rgResposable($rgResposable);
        $robots->cpfResponsable($cpfResponsable);
        $robots->btnProximo();
        sleep(2);

        // social data
        $robots->civilCertification($civilNovo);
        $robots->numberRegistration($numberRegistration);
        $robots->numberCns($numerCns);
        $robots->numberIdentity($numberIdentity);
        $robots->rgOrgan($rgOrgan);
        $robots->identityDate($identityDate);
        $robots->identyUF($identyUF);
        $robots->justice($justice);
        $robots->justification($justification);
        $robots->nis($nis);
        $robots->idInep($idInep);
        $robots->participantBF();
        $robots->postCensus();
        $robots->btnProximo();
        sleep(2);
        
        // residence
        $robots->stateAddress($stateAddress);
        $robots->cep($cep);
        sleep(2);
        $robots->cityAddress($cityAddress);
        sleep(2);
        $robots->address($address);
        $robots->neighborhood($neighborhood);
        $robots->number($number);
        $robots->complement($complement);
        $robots->location($location);
        $robots->zone($zone);
        $robots->btnProximo();
        sleep(2);

        // registration
        $robots->btnAddMatriculation();
        $robots->classroom($classroom);
        $robots->ticketType($ticketType);
        $robots->ticketDate($ticketDate);
        $robots->situationSerie($situationSerie);
        $robots->registrationStatus($registrationStatus);
        $robots->situationYear($situationYear);
        $robots->unifiedClassroom($unifiedClassroom);
        $robots->schooling($schooling);
        $robots->stage($stage);
        $robots->teachingStage($teachStage);
        $robots->publicTransport();
        sleep(2);
        $robots->transportResponsable($transportResponsable);
        $robots->typeTransport();
        $robots->btnProximo();
        sleep(2);

        // health
        $robots->deficiency();
        $robots->typeDeficiency();
        $robots->resourcesInep();
        $robots->vaccine();
        $robots->restrictions();
        $robots->btnCriar();
        sleep(2);

        $teste->see('O Cadastro de ' . $name . ' foi criado com sucesso!');
        $teste->canSeeInCurrentUrl('?r=student/index&');

    }
    
    /**
     * Adicionar (normal) estudantes, preenchidos todos os campos.
     * Dados Sociais - Modelo Antigo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function  allFilledInOldCivil(AcceptanceTester $teste)
    {
        sleep(5);
        $faker = Faker\Factory::create('pt_BR');
        $fakerCustom = new CustomProvider($faker);
        $robots = new StudentsRobots($teste);
        $robots->pageAddStudents();


        $name = $faker->name();
        $civilName = $faker->name();
        $dateOfBirth = $faker->date('d/m/Y');
        $cpf = $faker->cpf();
        $gender = $faker->randomElement(array (1,2));
        sleep(5);
        $color = $faker->randomElement(array (0,1,2,3,4,5));
        $nationality = '1';
        $state = '28';
        $city = '2800308';
        $email = $faker->email();
        $scholarity = $faker->randomElement(array (1,2,3,4));
        $filiation = $faker->randomElement(array (0,1));
        $responsable = $faker->randomElement(array (0,1,2));
        $telephone = $faker->cellphoneNumber();
        $nameResponsable = $faker->name();
        $emailResponsable = $faker->email();
        $responsableJob = $faker->jobTitle();
        $scholarityRespons = $faker->randomElement(array (1,2,3,4));
        $rgResposable = $faker->rg();
        $cpfResponsable = $faker->cpf();
        $civilOld= '1';
        $typeOfCivil = $faker->randomElement(array (1,2));
        $civilBook = $fakerCustom->bookCivil();
        $sheet = $fakerCustom->sheedCivil();
        $ufRegistry = '28';
        $dateOfIssue = $faker->date('d/m/Y');
        $termNumber = $fakerCustom->termCivil();
        $municipalityRegistry = '2800308';
        $notaryOffice = '5573'; //13º ofício
        $numerCns = $fakerCustom->cnsNumber();
        $numberIdentity = $faker->rg();
        $rgOrgan = '10'; //SSP
        $identityDate = $dateOfBirth;
        $identyUF = '28'; //SERGIPE
        $justice = $faker->randomElement(array (0,1,2));
        $justification = $faker->randomElement(array (1,2));
        $nis = $fakerCustom->nisNumber();
        $idInep = $fakerCustom->inepId();
        $stateAddress = '28';
        $cep = $faker->postcode();
        $cityAddress = '2800308';
        $address = $faker->streetName();
        $neighborhood = $faker->region();
        $number = $faker->buildingNumber();
        $complement = $fakerCustom->complementLocation();
        $location = $faker->randomElement(array (1,2,3,7));
        $zone = $faker->randomElement(array (1,2));
        $classroom = $faker->randomElement(array (7,3,2,1));
        $ticketType = $faker->randomElement(array (1,2,3));
        $ticketDate = $faker->date('d/m/Y');
        $situationSerie = $faker->randomElement(array (0,1,2));
        $registrationStatus = $faker->randomElement(array (1,2,3,4,5,6,7,8,9,10,11));
        $situationYear = $faker->randomElement(array (0,1,2,3,4,5));
        $unifiedClassroom = $faker->randomElement(array (1,2));
        $schooling = $faker->randomElement(array (1,2,3));
        $stage = $faker->randomElement(array (0,1,2,3,4,5,6,7));
        $teachStage = '1';
        $transportResponsable = $faker->randomElement(array (1,2));

        //Data Students
        $robots->name($name);
        $robots->civilNamebox();
        sleep(1);
        $robots->civilName($civilName);
        $robots->dateOfBirth($dateOfBirth);
        $robots->cpf($cpf);
        $robots->gender($gender);
        $robots->color($color);
        $robots->nationality($nationality);
        sleep(2);
        $robots->state($state);
        sleep(4);
        $robots->city($city);
        $robots->email($email);
        $robots->scholarity($scholarity);
        $robots->btnProximo();
        sleep(2);

        // filiation
        $robots->filiation($filiation);
        $robots->responsable($responsable);
        $robots->responsableTelephone($telephone);
        $robots->nameResponsable($nameResponsable);
        $robots->emailResponsable($emailResponsable);
        $robots->responsableJob($responsableJob);
        $robots->scholarityResponsable($scholarityRespons);
        $robots->rgResposable($rgResposable);
        $robots->cpfResponsable($cpfResponsable);
        $robots->btnProximo();
        sleep(2);

        // social data
        $robots->civilCertification($civilOld);
        $robots->typeOfCivilCertificate($typeOfCivil);
        $robots->civilCertificationBook($civilBook);
        $robots->sheet($sheet);
        $robots->ufRegistry($ufRegistry);
        $robots->dateOfIssue($dateOfIssue);
        $robots->termNumber($termNumber);
        sleep(2);
        $robots->municipalityRegistry($municipalityRegistry);
        sleep(2);
        $robots->notaryOffice($notaryOffice);
        $robots->numberCns($numerCns);
        $robots->numberIdentity($numberIdentity);
        $robots->rgOrgan($rgOrgan);
        $robots->identityDate($identityDate);
        $robots->identyUF($identyUF);
        $robots->justice($justice);
        $robots->justification($justification);
        $robots->nis($nis);
        $robots->idInep($idInep);
        $robots->participantBF();
        $robots->postCensus();
        $teste->pause();
        $robots->btnProximo();
        sleep(2);
        
        // residence
        $robots->stateAddress($stateAddress);
        $robots->cep($cep);
        sleep(2);
        $robots->cityAddress($cityAddress);
        sleep(2);
        $robots->address($address);
        $robots->neighborhood($neighborhood);
        $robots->number($number);
        $robots->complement($complement);
        $robots->location($location);
        $robots->zone($zone);
        $robots->btnProximo();
        sleep(2);

        // registration
        $robots->btnAddMatriculation();
        $robots->classroom($classroom);
        $robots->ticketType($ticketType);
        $robots->ticketDate($ticketDate);
        $robots->situationSerie($situationSerie);
        $robots->registrationStatus($registrationStatus);
        $robots->situationYear($situationYear);
        $robots->unifiedClassroom($unifiedClassroom);
        $robots->schooling($schooling);
        $robots->stage($stage);
        $robots->teachingStage($teachStage);
        $robots->publicTransport();
        sleep(2);
        $robots->transportResponsable($transportResponsable);
        $robots->typeTransport();
        $robots->btnProximo();
        sleep(2);

        // health
        $robots->deficiency();
        $robots->typeDeficiency();
        $robots->resourcesInep();
        $robots->vaccine();
        $robots->restrictions();
        $robots->btnCriar();
        sleep(2);

        $teste->see('O Cadastro de ' . $name . ' foi criado com sucesso!');
        $teste->canSeeInCurrentUrl('?r=student/index&');
    }

}