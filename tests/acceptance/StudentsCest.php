<?php

 require_once __DIR__ . '\\ClassroomCest.php';

class StudentsCest
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
     * Adicionar (rápido) estudantes, preenchendo apenas campos obrigatórios.
     * Filiado - Não declarado/Ignorado
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function addStudentsRapidFieldsRequired(AcceptanceTester $teste)
    {
        sleep(5);
        $robots = new StudentsRobots($teste);
        $robots->pageRapidAddStudents();
        $builder = new StudentBuilder();
        $dataStudent = $builder->buildCompleted();

        // students
        $robots->name($dataStudent->student['name']);
        $robots->dateOfBirth($dataStudent->student['birthday']);
        $robots->gender($dataStudent->student['sex']);
        $robots->color($dataStudent->student['color_race']);
        $robots->nationality($dataStudent->student['nationality']);
        sleep(2);
        $robots->state($dataStudent->student['state']);
        sleep(2);
        $robots->city($dataStudent->student['city']);
        $robots->btnProximo();
        sleep(2);

        // filiation
        $robots->filiation($dataStudent->student['filiation_no_declared']);
        $robots->btnProximo();
        sleep(2);

        // address
        $robots->address($dataStudent->studentDocument['address']);
        $robots->zone($dataStudent->studentDocument['residence_zone']);
        $robots->btnProximo();
        sleep(2);

        // matriculation
        $robots->btnAddMatriculation();
        sleep(2);
        $robots->btnProximo();

        // health
        $robots->btnCriar();

        $teste->see('O Cadastro de ' . $dataStudent->student['name'] . ' foi criado com sucesso!');
        $teste->canSeeInCurrentUrl('?r=student/index&');

        return $dataStudent;
    }

    /**
     * Adicionar (rápido) estudantes, preenchendo apenas campos obrigatórios.
     * Filiado - Pai e/ou Mãe
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function addStudentsRapidFieldsRequiredWithMotherAndFather(AcceptanceTester $teste)
    {
        sleep(5);
        $robots = new StudentsRobots($teste);
        $robots->pageRapidAddStudents();
        $builder = new StudentBuilder();
        $dataStudent = $builder->buildCompleted();

        // students
        $robots->name($dataStudent->student['name']);
        $robots->dateOfBirth($dataStudent->student['birthday']);
        $robots->gender($dataStudent->student['sex']);
        $robots->color($dataStudent->student['color_race']);
        $robots->nationality($dataStudent->student['nationality']);
        sleep(2);
        $robots->state($dataStudent->student['state']);
        sleep(2);
        $robots->city($dataStudent->student['city']);
        $robots->btnProximo();
        sleep(2);

        // filiation
        $robots->filiation($dataStudent->student['filiation_with_and_father']);
        $robots->filiationMain($dataStudent->student['filiation_1']);
        $robots->cpfFiliation1($dataStudent->student['filiation_1_cpf']);
        $robots->dateOfBirthFiliation($dataStudent->student['filiation_1_birthday']);
        $robots->rgFiliation1($dataStudent->student['filiation_1_rg']);
        $robots->scholarityFiliation1($dataStudent->student['filiation_1_scholarity']);
        $robots->professionFiliation1($dataStudent->student['filiation_1_job']);
        $robots->filiationSecondary($dataStudent->student['filiation_2']);
        $robots->cpfFiliation2($dataStudent->student['filiation_2_cpf']);
        $robots->dateOfBirthFiliationSecondary($dataStudent->student['filiation_2_birthday']);
        $robots->rgFiliation2($dataStudent->student['filiation_2_rg']);
        $robots->scholarityFiliation2($dataStudent->student['filiation_2_scholarity']);
        $robots->jobFiliation2($dataStudent->student['filiation_2_job']);
        $robots->btnProximo();
        sleep(2);

        // address
        $robots->address($dataStudent->studentDocument['address']);
        $robots->zone($dataStudent->studentDocument['residence_zone']);
        $robots->btnProximo();
        sleep(2);

        // matriculation
        $robots->btnAddMatriculation();
        sleep(2);
        $robots->btnProximo();

        // health
        $robots->btnCriar();

        $teste->see('O Cadastro de ' . $dataStudent->student['name'] . ' foi criado com sucesso!');
        $teste->canSeeInCurrentUrl('?r=student/index&');

        return $dataStudent;
    }


    /**
     * Adicionar (rápido) estudantes, preenchendo todos os campos.
     * Filiação - Não declarado/Ignorado.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function addStudentsRapidAllFilledIn(AcceptanceTester $teste)
    {
        sleep(5);
        $classroom = new ClassroomCest();
        $addClassroom = $classroom->addClassroomEAD($teste);

        sleep(5);
        $robots = new StudentsRobots($teste);
        $robots->pageRapidAddStudents();
        $builder = new StudentBuilder();
        $dataStudent = $builder->buildCompleted();

        // students
        $robots->name($dataStudent->student['name']);
        $robots->civilNamebox();
        sleep(1);
        $robots->civilName($dataStudent->student['civil_name']);
        $robots->dateOfBirth($dataStudent->student['birthday']);
        $robots->cpf($dataStudent->studentDocument['cpf']);
        $robots->gender($dataStudent->student['sex']);
        $robots->color($dataStudent->student['color_race']);
        $robots->nationality($dataStudent->student['nationality']);
        sleep(2);
        $robots->state($dataStudent->student['state']);
        sleep(2);
        $robots->city($dataStudent->student['city']);
        $robots->email($dataStudent->student['id_email']);
        $robots->scholarity($dataStudent->student['scholarity']);
        $robots->btnProximo();
        sleep(2);

        // filiation
        $robots->filiation($dataStudent->student['filiation_no_declared']);
        $robots->responsable($dataStudent->student['responsable']);
        $robots->btnProximo();
        sleep(2);

        // address
        $robots->address($dataStudent->studentDocument['address']);
        $robots->zone($dataStudent->studentDocument['residence_zone']);
        $robots->btnProximo();
        sleep(2);

        // matriculation
        $robots->btnAddMatriculation();
        sleep(2);
        $robots->classroom($addClassroom['name']);
        $robots->btnProximo();

        // health
        $robots->deficiency();
        $robots->typeDeficiency();
        $robots->resourcesInep();
        $robots->vaccine();
        $robots->restrictions();
        $robots->btnCriar();
        sleep(2);

        $teste->see('O Cadastro de ' . $dataStudent->student['name'] . ' foi criado com sucesso!');
        $teste->canSeeInCurrentUrl('?r=student/index&');

        return $dataStudent;
    }

    /**
     * Adicionar (normal) estudantes, preenchidos apenas campos obrigatórios.
     * Filiação - Pai e/ou mãe.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function addStudentsFieldsRequiredWithMotherAndFather(AcceptanceTester $teste)
    {
        sleep(5);
        $robots = new StudentsRobots($teste);
        $robots->pageAddStudents();
        $builder = new StudentBuilder();
        $dataStudent = $builder->buildCompleted();

        //Data Students
        $robots->name($dataStudent->student['name']);
        $robots->dateOfBirth($dataStudent->student['birthday']);
        $robots->gender($dataStudent->student['sex']);
        $robots->color($dataStudent->student['color_race']);
        $robots->nationality($dataStudent->student['nationality']);
        $robots->state($dataStudent->student['state']);
        sleep(2);
        $robots->city($dataStudent->student['city']);
        $robots->btnProximo();
        sleep(2);

        // filiation
        $robots->filiation($dataStudent->student['filiation_with_and_father']);
        $robots->filiationMain($dataStudent->student['filiation_1']);
        $robots->cpfFiliation1($dataStudent->student['filiation_1_cpf']);
        $robots->dateOfBirthFiliation($dataStudent->student['filiation_1_birthday']);
        $robots->rgFiliation1($dataStudent->student['filiation_1_rg']);
        $robots->scholarityFiliation1($dataStudent->student['filiation_1_scholarity']);
        $robots->professionFiliation1($dataStudent->student['filiation_1_job']);
        $robots->filiationSecondary($dataStudent->student['filiation_2']);
        $robots->cpfFiliation2($dataStudent->student['filiation_2_cpf']);
        $robots->dateOfBirthFiliationSecondary($dataStudent->student['filiation_2_birthday']);
        $robots->rgFiliation2($dataStudent->student['filiation_2_rg']);
        $robots->scholarityFiliation2($dataStudent->student['filiation_2_scholarity']);
        $robots->jobFiliation2($dataStudent->student['filiation_2_job']);
        $robots->btnProximo();
        sleep(2);

        // social data
        $robots->btnProximo();
        sleep(2);

        // residence
        $robots->zone($dataStudent->studentDocument['residence_zone']);
        $robots->btnProximo();
        sleep(2);

        // registration
        $robots->btnProximo();

        // health
        $robots->btnCriar();
        sleep(12);

        $teste->see('O Cadastro de ' . $dataStudent->student['name'] . ' foi criado com sucesso!');
        $teste->canSeeInCurrentUrl('?r=student/index&');

        return $dataStudent;
    }

    /**
     * Adicionar (normal) estudantes, preenchidos apenas campos obrigatórios.
     * Filiação - Não declarado/Ignorado.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function addStudentsFieldsRequired(AcceptanceTester $teste)
    {
        sleep(5);
        $robots = new StudentsRobots($teste);
        $robots->pageAddStudents();
        $builder = new StudentBuilder();
        $dataStudent = $builder->buildCompleted();

        //Data Students
        $robots->name($dataStudent->student['name']);
        $robots->dateOfBirth($dataStudent->student['birthday']);
        $robots->gender($dataStudent->student['sex']);
        $robots->color($dataStudent->student['color_race']);
        $robots->nationality($dataStudent->student['nationality']);
        $robots->state($dataStudent->student['state']);
        sleep(2);
        $robots->city($dataStudent->student['city']);
        $robots->btnProximo();
        sleep(2);

        // filiation
        $robots->filiation($dataStudent->student['filiation_no_declared']);
        $robots->responsable($dataStudent->student['responsable']);
        $robots->responsableTelephone($dataStudent->student['responsable_telephone']);
        $robots->nameResponsable($dataStudent->student['responsable_name']);
        $robots->emailResponsable($dataStudent->student['responsable_email']);
        $robots->responsableJob($dataStudent->student['responsable_job']);
        $robots->scholarityResponsable($dataStudent->student['responsable_scholarity']);
        $robots->rgResposable($dataStudent->student['responsable_rg']);
        $robots->cpfResponsable($dataStudent->student['responsable_cpf']);
        $robots->btnProximo();

        // social data
        $robots->btnProximo();
        sleep(2);

        // residence
        $robots->zone($dataStudent->studentDocument['residence_zone']);
        $robots->btnProximo();
        sleep(2);

        // registration
        $robots->btnProximo();

        // health
        $robots->btnCriar();
        sleep(12);

        $teste->see('O Cadastro de ' . $dataStudent->student['name'] . ' foi criado com sucesso!');
        $teste->canSeeInCurrentUrl('?r=student/index&');

        return $dataStudent;
    }

    /**
     * Adicionar (normal) estudantes, não preenchido nenhum campo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function fieldsNotFilledIn(AcceptanceTester $teste)
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
     * Filiação - Pai e/ou mãe.
     * Dados Sociais - Modelo Novo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function allFilledInNewCivilWithMotherAndFather(AcceptanceTester $teste)
    {
        sleep(5);
        $classroom = new ClassroomCest();
        $addClassroom = $classroom->addClassroomEAD($teste);

        sleep(5);
        $robots = new StudentsRobots($teste);
        $robots->pageAddStudents();
        $builder = new StudentBuilder();
        $dataStudent = $builder->buildCompleted();

        //Data Students
        $robots->name($dataStudent->student['name']);
        $robots->civilNamebox();
        sleep(1);
        $robots->civilName($dataStudent->student['civil_name']);
        $robots->dateOfBirth($dataStudent->student['birthday']);
        $robots->cpf($dataStudent->studentDocument['cpf']);
        $robots->gender($dataStudent->student['sex']);
        $robots->color($dataStudent->student['color_race']);
        $robots->nationality($dataStudent->student['nationality']);
        $robots->state($dataStudent->student['state']);
        sleep(2);
        $robots->city($dataStudent->student['city']);
        $robots->email($dataStudent->student['id_email']);
        $robots->scholarity($dataStudent->student['scholarity']);
        $robots->btnProximo();
        sleep(2);

        // filiation
        $robots->filiation($dataStudent->student['filiation_with_and_father']);
        $robots->responsable($dataStudent->student['responsable']);
        $robots->responsableTelephone($dataStudent->student['responsable_telephone']);
        $robots->nameResponsable($dataStudent->student['responsable_name']);
        $robots->emailResponsable($dataStudent->student['responsable_email']);
        $robots->responsableJob($dataStudent->student['responsable_job']);
        $robots->scholarityResponsable($dataStudent->student['responsable_scholarity']);
        $robots->rgResposable($dataStudent->student['responsable_rg']);
        $robots->cpfResponsable($dataStudent->student['responsable_cpf']);
        $robots->filiationMain($dataStudent->student['filiation_1']);
        $robots->cpfFiliation1($dataStudent->student['filiation_1_cpf']);
        $robots->dateOfBirthFiliation($dataStudent->student['filiation_1_birthday']);
        $robots->rgFiliation1($dataStudent->student['filiation_1_rg']);
        $robots->scholarityFiliation1($dataStudent->student['filiation_1_scholarity']);
        $robots->professionFiliation1($dataStudent->student['filiation_1_job']);
        $robots->filiationSecondary($dataStudent->student['filiation_2']);
        $robots->cpfFiliation2($dataStudent->student['filiation_2_cpf']);
        $robots->dateOfBirthFiliationSecondary($dataStudent->student['filiation_2_birthday']);
        $robots->rgFiliation2($dataStudent->student['filiation_2_rg']);
        $robots->scholarityFiliation2($dataStudent->student['filiation_2_scholarity']);
        $robots->jobFiliation2($dataStudent->student['filiation_2_job']);
        $robots->btnProximo();
        sleep(2);

        // social data
        $robots->civilCertification($dataStudent->studentDocument['civil_certification_type_new']);
        $robots->numberRegistration($dataStudent->studentDocument['civil_certification_term_number']);
        $robots->numberCns($dataStudent->studentDocument['cns']);
        $robots->numberIdentity($dataStudent->studentDocument['rg_number']);
        $robots->rgOrgan($dataStudent->studentDocument['rg_number_edcenso_organ_id_emitter_fk']);
        $robots->identityDate($dataStudent->studentDocument['civil_certification_date']);
        $robots->identyUF($dataStudent->studentDocument['rg_number_edcenso_uf_fk']);
        $robots->justice($dataStudent->studentDocument['justice_restriction']);
        $robots->justification($dataStudent->studentDocument['justification']);
        $robots->nis($dataStudent->studentDocument['nis']);
        $robots->idInep($dataStudent->student['inep_id']);
        $robots->participantBF();
        $robots->postCensus();
        $robots->btnProximo();
        sleep(2);

        // residence
        $robots->stateAddress($dataStudent->studentDocument['edcenso_uf_fk']);
        $robots->cep($dataStudent->studentDocument['cep']);
        sleep(2);
        $robots->cityAddress($dataStudent->studentDocument['edcenso_city_fk']);
        sleep(2);
        $robots->address($dataStudent->studentDocument['address']);
        $robots->neighborhood($dataStudent->studentDocument['neighborhood']);
        $robots->number($dataStudent->studentDocument['number']);
        $robots->complement($dataStudent->studentDocument['complement']);
        $robots->location($dataStudent->studentDocument['diff_location']);
        $robots->zone($dataStudent->studentDocument['residence_zone']);
        $robots->btnProximo();
        sleep(2);

        // registration
        $robots->btnAddMatriculation();
        $robots->classroom($addClassroom['name']);
        $robots->ticketType($dataStudent->studentEnrollment['admission_type']);
        $robots->ticketDate($dataStudent->studentEnrollment['school_admission_date']);
        $robots->situationSerie($dataStudent->studentEnrollment['current_stage_situation']);
        $robots->registrationStatus($dataStudent->studentEnrollment['status']);
        $robots->situationYear($dataStudent->studentEnrollment['previous_stage_situation']);
        $robots->unifiedClassroom($dataStudent->studentEnrollment['unified_class']);
        $robots->schooling($dataStudent->studentEnrollment['another_scholarization_place']);
        $robots->stage($dataStudent->studentEnrollment['stage']);
        $robots->teachingStage($dataStudent->studentEnrollment['edcenso_stage_vs_modality_fk']);
        $robots->publicTransport();
        sleep(2);
        $robots->transportResponsable($dataStudent->studentEnrollment['transport_responsable_government']);
        $robots->typeTransport();
        $robots->typeOfService();
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

        $teste->see('O Cadastro de ' . $dataStudent->student['name'] . ' foi criado com sucesso!');
        $teste->canSeeInCurrentUrl('?r=student/index&');

        return $dataStudent;
    }

    /**
     * Adicionar (normal) estudantes, preenchidos todos os campos.
     * Filiação - Não declarado/Ignorado.
     * Dados Sociais - Modelo Novo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function allFilledInNewCivil(AcceptanceTester $teste)
    {
        sleep(5);
        $classroom = new ClassroomCest();
        $addClassroom = $classroom->addClassroomEAD($teste);

        sleep(5);
        $robots = new StudentsRobots($teste);
        $robots->pageAddStudents();
        $builder = new StudentBuilder();
        $dataStudent = $builder->buildCompleted();

        //Data Students
        $robots->name($dataStudent->student['name']);
        $robots->civilNamebox();
        sleep(1);
        $robots->civilName($dataStudent->student['civil_name']);
        $robots->dateOfBirth($dataStudent->student['birthday']);
        $robots->cpf($dataStudent->studentDocument['cpf']);
        $robots->gender($dataStudent->student['sex']);
        $robots->color($dataStudent->student['color_race']);
        $robots->nationality($dataStudent->student['nationality']);
        $robots->state($dataStudent->student['state']);
        sleep(2);
        $robots->city($dataStudent->student['city']);
        $robots->email($dataStudent->student['id_email']);
        $robots->scholarity($dataStudent->student['scholarity']);
        $robots->btnProximo();
        sleep(2);

        // filiation
        $robots->filiation($dataStudent->student['filiation_no_declared']);
        $robots->responsable($dataStudent->student['responsable']);
        $robots->responsableTelephone($dataStudent->student['responsable_telephone']);
        $robots->nameResponsable($dataStudent->student['responsable_name']);
        $robots->emailResponsable($dataStudent->student['responsable_email']);
        $robots->responsableJob($dataStudent->student['responsable_job']);
        $robots->scholarityResponsable($dataStudent->student['responsable_scholarity']);
        $robots->rgResposable($dataStudent->student['responsable_rg']);
        $robots->cpfResponsable($dataStudent->student['responsable_cpf']);
        $robots->btnProximo();

        // social data
        $robots->civilCertification($dataStudent->studentDocument['civil_certification_type_new']);
        $robots->numberRegistration($dataStudent->studentDocument['civil_certification_term_number']);
        $robots->numberCns($dataStudent->studentDocument['cns']);
        $robots->numberIdentity($dataStudent->studentDocument['rg_number']);
        $robots->rgOrgan($dataStudent->studentDocument['rg_number_edcenso_organ_id_emitter_fk']);
        $robots->identityDate($dataStudent->studentDocument['civil_certification_date']);
        $robots->identyUF($dataStudent->studentDocument['rg_number_edcenso_uf_fk']);
        $robots->justice($dataStudent->studentDocument['justice_restriction']);
        $robots->justification($dataStudent->studentDocument['justification']);
        $robots->nis($dataStudent->studentDocument['nis']);
        $robots->idInep($dataStudent->student['inep_id']);
        $robots->participantBF();
        $robots->postCensus();
        $robots->btnProximo();
        sleep(2);

        // residence
        $robots->stateAddress($dataStudent->studentDocument['edcenso_uf_fk']);
        $robots->cep($dataStudent->studentDocument['cep']);
        sleep(2);
        $robots->cityAddress($dataStudent->studentDocument['edcenso_city_fk']);
        sleep(2);
        $robots->address($dataStudent->studentDocument['address']);
        $robots->neighborhood($dataStudent->studentDocument['neighborhood']);
        $robots->number($dataStudent->studentDocument['number']);
        $robots->complement($dataStudent->studentDocument['complement']);
        $robots->location($dataStudent->studentDocument['diff_location']);
        $robots->zone($dataStudent->studentDocument['residence_zone']);
        $robots->btnProximo();
        sleep(2);

        // registration
        $robots->btnAddMatriculation();
        $robots->classroom($addClassroom['name']);
        $robots->ticketType($dataStudent->studentEnrollment['admission_type']);
        $robots->ticketDate($dataStudent->studentEnrollment['school_admission_date']);
        $robots->situationSerie($dataStudent->studentEnrollment['current_stage_situation']);
        $robots->registrationStatus($dataStudent->studentEnrollment['status']);
        $robots->situationYear($dataStudent->studentEnrollment['previous_stage_situation']);
        $robots->unifiedClassroom($dataStudent->studentEnrollment['unified_class']);
        $robots->schooling($dataStudent->studentEnrollment['another_scholarization_place']);
        $robots->stage($dataStudent->studentEnrollment['stage']);
        $robots->teachingStage($dataStudent->studentEnrollment['edcenso_stage_vs_modality_fk']);
        $robots->publicTransport();
        sleep(2);
        $robots->transportResponsable($dataStudent->studentEnrollment['transport_responsable_government']);
        $robots->typeTransport();
        $robots->typeOfService();
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

        $teste->see('O Cadastro de ' . $dataStudent->student['name'] . ' foi criado com sucesso!');
        $teste->canSeeInCurrentUrl('?r=student/index&');

        return $dataStudent;
    }

    /**
     * Adicionar (normal) estudantes, preenchidos todos os campos.
     * Filiação - Pai e/ou mãe.
     * Dados Sociais - Modelo Antigo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function  allFilledInOldCivilWithMotherAndFather(AcceptanceTester $teste)
    {
        sleep(5);
        $classroom = new ClassroomCest();
        $addClassroom = $classroom->addClassroomEAD($teste);

        sleep(5);
        $robots = new StudentsRobots($teste);
        $robots->pageAddStudents();
        $builder = new StudentBuilder();
        $dataStudent = $builder->buildCompleted();

        //Data Students
        $robots->name($dataStudent->student['name']);
        $robots->civilNamebox();
        sleep(1);
        $robots->civilName($dataStudent->student['civil_name']);
        $robots->dateOfBirth($dataStudent->student['birthday']);
        $robots->cpf($dataStudent->studentDocument['cpf']);
        $robots->gender($dataStudent->student['sex']);
        $robots->color($dataStudent->student['color_race']);
        $robots->nationality($dataStudent->student['nationality']);
        $robots->state($dataStudent->student['state']);
        sleep(2);
        $robots->city($dataStudent->student['city']);
        $robots->email($dataStudent->student['id_email']);
        $robots->scholarity($dataStudent->student['scholarity']);
        $robots->btnProximo();
        sleep(2);

        // filiation
        $robots->filiation($dataStudent->student['filiation_with_and_father']);
        $robots->responsable($dataStudent->student['responsable']);
        $robots->responsableTelephone($dataStudent->student['responsable_telephone']);
        $robots->nameResponsable($dataStudent->student['responsable_name']);
        $robots->emailResponsable($dataStudent->student['responsable_email']);
        $robots->responsableJob($dataStudent->student['responsable_job']);
        $robots->scholarityResponsable($dataStudent->student['responsable_scholarity']);
        $robots->rgResposable($dataStudent->student['responsable_rg']);
        $robots->cpfResponsable($dataStudent->student['responsable_cpf']);
        $robots->filiationMain($dataStudent->student['filiation_1']);
        $robots->cpfFiliation1($dataStudent->student['filiation_1_cpf']);
        $robots->dateOfBirthFiliation($dataStudent->student['filiation_1_birthday']);
        $robots->rgFiliation1($dataStudent->student['filiation_1_rg']);
        $robots->scholarityFiliation1($dataStudent->student['filiation_1_scholarity']);
        $robots->professionFiliation1($dataStudent->student['filiation_1_job']);
        $robots->filiationSecondary($dataStudent->student['filiation_2']);
        $robots->cpfFiliation2($dataStudent->student['filiation_2_cpf']);
        $robots->dateOfBirthFiliationSecondary($dataStudent->student['filiation_2_birthday']);
        $robots->rgFiliation2($dataStudent->student['filiation_2_rg']);
        $robots->scholarityFiliation2($dataStudent->student['filiation_2_scholarity']);
        $robots->jobFiliation2($dataStudent->student['filiation_2_job']);
        $robots->btnProximo();
        sleep(2);

        // social data
        $robots->civilCertification($dataStudent->studentDocument['civil_certification_type_old']);
        $robots->typeOfCivilCertificate($dataStudent->studentDocument['civil_certification_type']);
        $robots->termNumber($dataStudent->studentDocument['civil_certification_term_number']);
        $robots->sheet($dataStudent->studentDocument['civil_certification_sheet']);
        $robots->civilCertificationBook($dataStudent->studentDocument['civil_certification_book']);
        $robots->dateOfIssue($dataStudent->studentDocument['civil_certification_date']);
        $robots->ufRegistry($dataStudent->studentDocument['notary_office_uf_fk']);
        sleep(2);
        $robots->municipalityRegistry($dataStudent->studentDocument['notary_office_city_fk']);
        sleep(2);
        $robots->notaryOffice($dataStudent->studentDocument['edcenso_notary_office_fk']);
        $robots->numberCns($dataStudent->studentDocument['cns']);
        $robots->numberIdentity($dataStudent->studentDocument['rg_number']);
        $robots->rgOrgan($dataStudent->studentDocument['rg_number_edcenso_organ_id_emitter_fk']);
        $robots->identityDate($dataStudent->studentDocument['rg_number_expediction_date']);
        $robots->identyUF($dataStudent->studentDocument['rg_number_edcenso_uf_fk']);
        $robots->justice($dataStudent->studentDocument['justice_restriction']);
        $robots->justification($dataStudent->studentDocument['justification']);
        $robots->nis($dataStudent->studentDocument['nis']);
        $robots->idInep($dataStudent->student['inep_id']);
        $robots->participantBF();
        $robots->postCensus();
        $robots->btnProximo();
        sleep(2);

        // residence
        $robots->stateAddress($dataStudent->studentDocument['edcenso_uf_fk']);
        $robots->cep($dataStudent->studentDocument['cep']);
        sleep(2);
        $robots->cityAddress($dataStudent->studentDocument['edcenso_city_fk']);
        sleep(2);
        $robots->address($dataStudent->studentDocument['address']);
        $robots->neighborhood($dataStudent->studentDocument['neighborhood']);
        $robots->number($dataStudent->studentDocument['number']);
        $robots->complement($dataStudent->studentDocument['complement']);
        $robots->location($dataStudent->studentDocument['diff_location']);
        $robots->zone($dataStudent->studentDocument['residence_zone']);
        $robots->btnProximo();
        sleep(2);

        // registration
        $robots->btnAddMatriculation();
        $robots->classroom($addClassroom['name']);
        $robots->ticketType($dataStudent->studentEnrollment['admission_type']);
        $robots->ticketDate($dataStudent->studentEnrollment['school_admission_date']);
        $robots->situationSerie($dataStudent->studentEnrollment['current_stage_situation']);
        $robots->registrationStatus($dataStudent->studentEnrollment['status']);
        $robots->situationYear($dataStudent->studentEnrollment['previous_stage_situation']);
        $robots->unifiedClassroom($dataStudent->studentEnrollment['unified_class']);
        $robots->schooling($dataStudent->studentEnrollment['another_scholarization_place']);
        $robots->stage($dataStudent->studentEnrollment['stage']);
        $robots->teachingStage($dataStudent->studentEnrollment['edcenso_stage_vs_modality_fk']);
        $robots->publicTransport();
        sleep(2);
        $robots->transportResponsable($dataStudent->studentEnrollment['transport_responsable_government']);
        $robots->typeTransport();
        $robots->typeOfService();
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

        $teste->see('O Cadastro de ' . $dataStudent->student['name'] . ' foi criado com sucesso!');
        $teste->canSeeInCurrentUrl('?r=student/index&');

        return $dataStudent;
    }

    /**
     * Adicionar (normal) estudantes, preenchidos todos os campos.
     * Filiação - Não declarado/Ignorado.
     * Dados Sociais - Modelo Antigo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function  allFilledInOldCivil(AcceptanceTester $teste)
    {
        sleep(5);
        $classroom = new ClassroomCest();
        $addClassroom = $classroom->addClassroomEAD($teste);

        sleep(5);
        $robots = new StudentsRobots($teste);
        $robots->pageAddStudents();
        $builder = new StudentBuilder();
        $dataStudent = $builder->buildCompleted();

        //Data Students
        $robots->name($dataStudent->student['name']);
        $robots->civilNamebox();
        sleep(1);
        $robots->civilName($dataStudent->student['civil_name']);
        $robots->dateOfBirth($dataStudent->student['birthday']);
        $robots->cpf($dataStudent->studentDocument['cpf']);
        $robots->gender($dataStudent->student['sex']);
        $robots->color($dataStudent->student['color_race']);
        $robots->nationality($dataStudent->student['nationality']);
        sleep(2);
        $robots->state($dataStudent->student['state']);
        sleep(4);
        $robots->city($dataStudent->student['city']);
        $robots->email($dataStudent->student['id_email']);
        $robots->scholarity($dataStudent->student['scholarity']);
        $robots->btnProximo();
        sleep(2);

        // filiation
        $robots->filiation($dataStudent->student['filiation_no_declared']);
        $robots->responsable($dataStudent->student['responsable']);
        $robots->responsableTelephone($dataStudent->student['responsable_telephone']);
        $robots->nameResponsable($dataStudent->student['responsable_name']);
        $robots->emailResponsable($dataStudent->student['responsable_email']);
        $robots->responsableJob($dataStudent->student['responsable_job']);
        $robots->scholarityResponsable($dataStudent->student['responsable_scholarity']);
        $robots->rgResposable($dataStudent->student['responsable_rg']);
        $robots->cpfResponsable($dataStudent->student['responsable_cpf']);
        $robots->btnProximo();

        // social data
        $robots->civilCertification($dataStudent->studentDocument['civil_certification_type_old']);
        $robots->typeOfCivilCertificate($dataStudent->studentDocument['civil_certification_type']);
        $robots->termNumber($dataStudent->studentDocument['civil_certification_term_number']);
        $robots->sheet($dataStudent->studentDocument['civil_certification_sheet']);
        $robots->civilCertificationBook($dataStudent->studentDocument['civil_certification_book']);
        $robots->dateOfIssue($dataStudent->studentDocument['civil_certification_date']);
        $robots->ufRegistry($dataStudent->studentDocument['notary_office_uf_fk']);
        sleep(2);
        $robots->municipalityRegistry($dataStudent->studentDocument['notary_office_city_fk']);
        sleep(2);
        $robots->notaryOffice($dataStudent->studentDocument['edcenso_notary_office_fk']);
        $robots->numberCns($dataStudent->studentDocument['cns']);
        $robots->numberIdentity($dataStudent->studentDocument['rg_number']);
        $robots->rgOrgan($dataStudent->studentDocument['rg_number_edcenso_organ_id_emitter_fk']);
        $robots->identityDate($dataStudent->studentDocument['rg_number_expediction_date']);
        $robots->identyUF($dataStudent->studentDocument['rg_number_edcenso_uf_fk']);
        $robots->justice($dataStudent->studentDocument['justice_restriction']);
        $robots->justification($dataStudent->studentDocument['justification']);
        $robots->nis($dataStudent->studentDocument['nis']);
        $robots->idInep($dataStudent->student['inep_id']);
        $robots->participantBF();
        $robots->postCensus();
        $robots->btnProximo();
        sleep(2);


        // residence
        $robots->stateAddress($dataStudent->studentDocument['edcenso_uf_fk']);
        $robots->cep($dataStudent->studentDocument['cep']);
        sleep(2);
        $robots->cityAddress($dataStudent->studentDocument['edcenso_city_fk']);
        sleep(2);
        $robots->address($dataStudent->studentDocument['address']);
        $robots->neighborhood($dataStudent->studentDocument['neighborhood']);
        $robots->number($dataStudent->studentDocument['number']);
        $robots->complement($dataStudent->studentDocument['complement']);
        $robots->location($dataStudent->studentDocument['diff_location']);
        $robots->zone($dataStudent->studentDocument['residence_zone']);
        $robots->btnProximo();
        sleep(2);

        // registration
        $robots->btnAddMatriculation();
        $robots->classroom($addClassroom['name']);
        $robots->ticketType($dataStudent->studentEnrollment['admission_type']);
        $robots->ticketDate($dataStudent->studentEnrollment['school_admission_date']);
        $robots->situationSerie($dataStudent->studentEnrollment['current_stage_situation']);
        $robots->registrationStatus($dataStudent->studentEnrollment['status']);
        $robots->situationYear($dataStudent->studentEnrollment['previous_stage_situation']);
        $robots->unifiedClassroom($dataStudent->studentEnrollment['unified_class']);
        $robots->schooling($dataStudent->studentEnrollment['another_scholarization_place']);
        $robots->stage($dataStudent->studentEnrollment['stage']);
        $robots->teachingStage($dataStudent->studentEnrollment['edcenso_stage_vs_modality_fk']);
        $robots->publicTransport();
        sleep(2);
        $robots->transportResponsable($dataStudent->studentEnrollment['transport_responsable_government']);
        $robots->typeTransport();
        $robots->typeOfService();
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

        $teste->see('O Cadastro de ' . $dataStudent->student['name'] . ' foi criado com sucesso!');
        $teste->canSeeInCurrentUrl('?r=student/index&');

        return $dataStudent;
    }
}
