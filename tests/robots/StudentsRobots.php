<?php

class StudentsRobots
{
    public AcceptanceTester $tester;

    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * Página de estudantes.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function pageStudents ()
    {
        $this->tester->amOnPage('?r=student');
    }

    /**
     * Página de adicionar estudantes (normal).
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function pageAddStudents ()
    {
        $this->tester->amOnPage('?r=student/create');
    }

    /**
     * Página de adicionar estudantes (rápido).
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function pageRapidAddStudents ()
    {
        $this->tester->amOnPage('?r=student/create&simple=1');
    }

    /**
     * Pesquisar estudante.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function search ($search)
    {
        $this->tester->click('.dataTables_filter input[type="search"]');
        $this->tester->fillField('.dataTables_filter input[type="search"]', $search);
    }

    /**
     * Botão de próximo nas telas de cadastros de estudantes.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnProximo ()
    {
        $this->tester->waitForElement('.t-button-primary.next');
        $this->tester->executeJS("document.querySelector('.t-button-primary.next').click();");
    }

    /**
     * Botão de criar na tela de cadastros de estudantes.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnCriar ()
    {
        $this->tester->waitForElement('.save-student');
        $this->tester->executeJS("document.querySelector('.save-student').click();");
    }

    /**
     * Botão 1: Dados do aluno.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btn1Identify ()
    {
        $this->tester->waitForElementVisible('#tab-student-identify');
        $this->tester->executeJS("document.querySelector('#tab-student-identify a').click();");
    }

    /**
     * Botão 2: Filiação.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btn2Affiliation ()
    {
        $this->tester->waitForElementVisible('#tab-student-affiliation');
        $this->tester->executeJS("document.querySelector('#tab-student-affiliation a').click();");
    }

    /**
     * Botão 3: Dados sociais.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btn3Documents ()
    {
        $this->tester->waitForElementVisible('#tab-student-documents');
        $this->tester->executeJS("document.querySelector('#tab-student-documents a').click();");
    }

    /**
     * Botão 4: Endereço.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btn4Address ()
    {
        $this->tester->waitForElementVisible('#tab-student-address');
        $this->tester->executeJS("document.querySelector('#tab-student-address a').click();");
    }

    /**
     * Botão 5: Matricula.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btn5Enrollment ()
    {
        $this->tester->waitForElementVisible('#tab-student-enrollment');
        $this->tester->executeJS("document.querySelector('#tab-student-enrollment a').click();");
    }

    /**
     * Botão 6: Saúde.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btn6Health ()
    {
        $this->tester->waitForElementVisible('#tab-student-health');
        $this->tester->executeJS("document.querySelector('#tab-student-health a').click();");
    }

    /**
     * Botão de editar estudantes.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnEdit ()
    {
        $this->tester->waitForElement('#student-edit');
        $this->tester->executeJS("document.querySelector('#student-edit').click();");
    }

    /**
     * Preencher nome do estudante.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function name ($name)
    {
        $this->tester->fillField('#nameStudents input[type=text]', $name);
    }

    /**
     * Checkbox para nome social.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function civilNamebox ()
    {
        $this->tester->click('#show-student-civil-name');
    }

    /**
     * Preencher o nome social.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function civilName ($civilName)
    {
        $this->tester->fillField('#civilName input[type=text]', $civilName);
    }

    /**
     * Preencher data de nascimento do estudante.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function dateOfBirth ($dateOfBirth)
    {
        $this->tester->fillField('#dateOfBirth input[type=text]', $dateOfBirth);
    }

    /**
     * Preencher cpf do estudante.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function cpf ($cpf)
    {
        $this->tester->fillField('#cpfStudents input[type=text]', $cpf);
    }

    /**
     * Selecionar o sexo do estudante.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function gender ($gender)
    {
        $this->tester->selectOption('#gender-select select', $gender);
    }

    /**
     * Selecionar a cor do estudante.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function color ($color)
    {
        $this->tester->selectOption('#color select', $color);
    }

    /**
     * Selecionar a nacionalidade do estudante.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function nationality ($nationality)
    {
        $this->tester->selectOption('#nationality-select select', $nationality);
    }

    /**
     * Selecionar o estado do estudante.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function state ($state)
    {
        $this->tester->selectOption('#state-select select', $state);
    }

    /**
     * Selecionar a cidade do estudante.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function city ($city)
    {
        $this->tester->selectOption('#city-select select', $city);
    }

    /**
     * Preencher o email do estudante.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function email ($email)
    {
        $this->tester->fillField('#email input[type=text]',$email);
    }

    /**
     * Seleciona a escolaridade do estudante.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function scholarity ($scholarity)
    {
        $this->tester->selectOption('#scholarity-select select', $scholarity);
    }

    /**
     * Selecionar o estado do estudante.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function filiation ($filiation)
    {
        $this->tester->selectOption('#filiation-select select', $filiation);
    }

    /**
     * Selecionar o responsável da filiação do estudante.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function responsable ($responsable)
    {
        $this->tester->selectOption('#responsable-select select', $responsable);
    }

    /**
     * Preencher telefone do responsável.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function responsableTelephone ($telephone)
    {
        $this->tester->fillField('#telephone input[type=text]', $telephone);
    }

    /**
     * Preencher nome do responsável.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function nameResponsable ($nameResponsable)
    {
        $this->tester->fillField('#nameResponsable input[type=text]', $nameResponsable);
    }

    /**
     * Preencher email do responsável.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function emailResponsable ($emailResponsable)
    {
        $this->tester->fillField('#emailResponsable input[type=text]', $emailResponsable);
    }

    /**
     * Preencher profissão do responsável.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
    */
    public function  responsableJob ($responsableJob)
    {
        $this->tester->fillField('#responsableJob input[type=text]', $responsableJob);
    }

    /**
     * Selecionar escolaridade do responsável.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function scholarityResponsable ($scholarityRespons)
    {
        $this->tester->selectOption('#scholarityResponsable-select select', $scholarityRespons);
    }

    /**
     * Preencher RG do responsável.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function rgResposable ($rgResposable)
    {
        $this->tester->fillField('#rgResposable input[type=text]', $rgResposable);
    }

    /**
     * Preencher cpf do responsável.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function cpfResponsable ($cpfResponsable)
    {
        $this->tester->fillField('#cpfResponsable input[type=text]', $cpfResponsable);
    }

    /**
     * Preencher nome da filiação principal.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function filiationMain ($filiationMain)
    {
        $this->tester->fillField('#filiationMain input[type=text]', $filiationMain);
    }

    /**
     * Preencher o cpf da filiação 1.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function cpfFiliation1 ($cpfFiliation1)
    {
        $this->tester->fillField('#cpfFiliation1 input[type=text]', $cpfFiliation1);
    }

    /**
     * Preencher a data de nascimento da filiação principal.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function dateOfBirthFiliation ($dateFiliation)
    {
        $this->tester->fillField('#dateOfBirthFiliation input[type=text]', $dateFiliation);
    }

    /**
     * Preencher o rg da filiação 1.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function rgFiliation1 ($rgFiliation1)
    {
        $this->tester->fillField('#rgFiliation1 input[type=text]', $rgFiliation1);
    }

    /**
     * Selecionar a escolaridade da filiação 1.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function scholarityFiliation1 ($scholarityFiliation1)
    {
        $this->tester->selectOption('#scholarityFiliation1-select select', $scholarityFiliation1);
    }

    /**
     * Preencher a filiação da profissão 1.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function professionFiliation1 ($professionFiliation1)
    {
        $this->tester->fillField('#professionFiliation1 input[type=text]', $professionFiliation1);
    }

    /**
     * Preencher o nome completo da filiação secundária.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function filiationSecondary ($filiationSecondary)
    {
        $this->tester->fillField('#filiationSecondary input[type=text]', $filiationSecondary);
    }

    /**
     * Preencher o cpf da filiação 2.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function cpfFiliation2 ($cpfFiliation2)
    {
        $this->tester->fillField('#cpfFiliation2 input[type=text]', $cpfFiliation2);
    }

    /**
     * Preencher a data de nascimento da filiação secundária.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function dateOfBirthFiliationSecondary ($dateSecondary)
    {
        $this->tester->fillField('#dateSecondary input[type=text]', $dateSecondary);
    }

    /**
     * Preencher o rg da filiação 2.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function rgFiliation2 ($rgFiliation2)
    {
        $this->tester->fillField('#rgFiliation2 input[type=text]', $rgFiliation2);
    }

    /**
     * Selecionar a escolaridade da filiação 2.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function scholarityFiliation2 ($scholarityFiliation2)
    {
        $this->tester->selectOption('#scholarityFiliation2-select select', $scholarityFiliation2);
    }
    /**
     * Preencher a profissão da filiação 2.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function jobFiliation2 ($jobFiliation2)
    {
        $this->tester->fillField('#jobFiliation2 input[type=text]', $jobFiliation2);
    }

    /**
     * Selecionar modelo de Certidão Civil.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function civilCertification ($civilCertification)
    {
        $this->tester->selectOption('#civilCertification-select select', $civilCertification);
    }

    /**
     * Preencher Nº da Matrícula (Registro Civil - Certidão nova).
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function numberRegistration ($numberRegistration)
    {
        $this->tester->fillField('#numberRegistration input[type=text]', $numberRegistration);
    }

    /**
     * Selecione o tipo de certidão civil.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function typeOfCivilCertificate ($typeOfCivil)
    {
        $this->tester->selectOption('#typeOfCivil-select select', $typeOfCivil);
    }

    /**
     * Preencha o livro com os dados da certidão antiga.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function civilCertificationBook ($book)
    {
        $this->tester->fillField('#bookCertification input[type=text]', $book);
    }

    /**
     * Preencha a folha com os dados da certidão antiga.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function sheet ($sheet)
    {
        $this->tester->fillField('#sheet input[type=text]', $sheet);
    }

    /**
     * Selecione a UF da certidão antiga.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function ufRegistry ($ufRegistry)
    {
        $this->tester->selectOption('#ufRegistry-select select', $ufRegistry);
    }

    /**
     * Preencha a data de emissão da certidão.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function dateOfIssue ($dateOfIssue)
    {
        $this->tester->fillField('#certificationDate input[type=text]', $dateOfIssue);
    }

    /**
     * Preencha o Nº do termo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function termNumber ($termNumber)
    {
        $this->tester->fillField('#termNumber input[type=text]', $termNumber);
    }

    /**
     * Selecione o município do cartório.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function municipalityRegistry ($municipalityRegistry)
    {
        $this->tester->selectOption('#municipalityRegistry-select select', $municipalityRegistry);
    }

    /**
     * Selecione um cartório.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function notaryOffice ($notaryOffice)
    {
        $this->tester->selectOption('#notaryOffice-select select', $notaryOffice);
    }

    /**
     * Preencher o Nº do CNS.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function numberCns ($numberCns)
    {
        $this->tester->fillField('#numberCns input[type=text]', $numberCns);
    }

    /**
     * Preencher o Nº da identidade.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function numberIdentity($numberIdentity)
    {
        $this->tester->fillField('#numberIdentity input[type=text]', $numberIdentity);
    }

    /**
     *Selecione um órgão emissor da identidade.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function rgOrgan ($rgOrgan)
    {
        $this->tester->selectOption('#rgOrgan-select select', $rgOrgan);
    }

    /**
     * Preencher a data de expedição da identidade.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function identityDate ($identityDate)
    {
        $this->tester->fillField('#identityDate input[type=text]', $identityDate);
    }

    /**
     * Selecione a UF da identidade.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function identyUF ($identyUF)
    {
        $this->tester->selectOption('#identyUF-select select', $identyUF);
    }

    /**
     * Selecione a restrição na justiça.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function justice ($justice)
    {
        $this->tester->selectOption('#justice-select select', $justice);
    }

    /**
     * Selecione a justificativa.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function justification ($justification)
    {
        $this->tester->selectOption('#justification-select select', $justification);
    }

    /**
     * Preencha o número de identificação social (nis).
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function nis ($nis)
    {
        $this->tester->fillField('#nis input[type=text]', $nis);
    }

    /**
     * Preencha o ID INEP.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function idInep ($idInep)
    {
        $this->tester->fillField('#idInep input[type=text]',$idInep);
    }

    /**
     * Checkbox para participante do Bolsa Família.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function participantBF()
    {
        $this->tester->click('#participantBF input[type=checkbox]');
    }

    /**
     * Checkbox para pós censo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function postCensus()
    {
        $this->tester->click('#postCensus input[type=checkbox]');
    }

    /**
     * Na parte de endereço, selecionar o estado do aluno.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function stateAddress($stateAddress)
    {
        $this->tester->selectOption('#stateAddress-select select', $stateAddress);
    }

    /**
     * Preencher campo de cep do aluno.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function cep($cep)
    {
        $this->tester->fillField('#cepAddress input[type=text]',$cep);
    }

    /**
     * Na parte de endereço, selecionar a cidade do aluno.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function cityAddress($cityAddress)
    {
        $this->tester->selectOption('#cityAddress select', $cityAddress);
    }

    /**
     * Preencher o endereço do aluno.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function address ($address)
    {
        $this->tester->fillField('#address input[type=text]', $address);
    }

    /**
     * Preencher o bairro do estudante.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function neighborhood ($neighborhood)
    {
        $this->tester->fillField('#neighborhood input[type=text]', $neighborhood);
    }

    /**
     * Preencher o número da casa do estudante.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function number ($number)
    {
        $this->tester->fillField('#number input[type=text]', $number);
    }

    /**
     * Preencher o complemento do estudante.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function complement ($complement)
    {
        $this->tester->fillField('#complement input[type=text]', $complement);
    }

    /**
     * Preencher a localização.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function location ($location)
    {
        $this->tester->selectOption('#location-select select', $location);
    }


    /**
     * Selecionar a localização ou zona de residência do estudante.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function zone($zone)
    {
        $this->tester->executeJS("document.querySelector('#zone-select select').value = '{$zone}';");
    }

     /**
     * Botão de adicionar matricula.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnAddMatriculation ()
    {
        $this->tester->waitForElement('#new-enrollment-button');
        $this->tester->executeJS("document.querySelector('#new-enrollment-button').click();");
    }

     /**
     * Selecionar a sala de aula do estudante.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function classroom ($classroom)
    {
        $script = "document.querySelector('#class-select select').value = '{$classroom}';";
        $this->tester->executeJS($script);
    }

    /**
     * Selecione o tipo de ingresso.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function ticketType ($ticketType)
    {
        $this->tester->selectOption('#ticketType-select select', $ticketType);
    }

    /**
     * Preencha a data de ingresso na escola.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function ticketDate($ticketDate)
    {
        $script = "document.querySelector('#ticketDate input[type=text]').value = '{$ticketDate}';";
        $this->tester->executeJS($script);
    }

    /**
     * Selecione a situação na série/etapa atual.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function situationSerie ($situationSerie)
    {
        $this->tester->selectOption('#situationSerie-select select', $situationSerie);
    }

    /**
     * Selecione a situação da matrícula.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function registrationStatus ($registrationStatus)
    {
        $this->tester->selectOption('#registrationStatus-select select', $registrationStatus);
    }

    /**
     * Selecione a situação no ano anterior.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function situationYear ($situationYear)
    {
        $this->tester->selectOption('#situationYear-select select', $situationYear);
    }

    /**
     * Selecione o tipo de turma infantil.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function unifiedClassroom ($unifiedClassroom)
    {
        $this->tester->selectOption('#unifiedClassroom-select select', $unifiedClassroom);
    }

    /**
     * Selecione a escolarização.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function schooling ($schooling)
    {
        $this->tester->selectOption('#schooling-select select', $schooling);
    }

    /**
     * Selecione a modalidade.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function stage ($stage)
    {
        $this->tester->selectOption('#stage-select select', $stage);
    }

    /**
     * Selecione a etapa de ensino.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function teachingStage ($teachingStage)
    {
        $this->tester->selectOption('#teachingStage-select select', $teachingStage);
    }

    /**
     * Checkbox para transporte publico escolar.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function publicTransport ()
    {
        $script = "document.querySelector('#publicTransport input[type=checkbox]').click();";
        $this->tester->executeJS($script);
    }

    /**
     * Selecione o poder público do transporte.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function transportResponsable ($transportResponsable)
    {
        $script = "document.querySelector('#transport_responsable select').value = '{$transportResponsable}';";
        $this->tester->executeJS($script);
    }

    /**
     * Checkbox para o tipo de transporte
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function typeTransport ()
    {
        $script = "document.querySelector('#transport_type input[type=checkbox]').click();";
        $this->tester->executeJS($script);
    }

    /**
     * Preencher o Tipo de Atendimento Educacional Especializado.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function typeOfService ()
    {
        $script = "document.querySelector('#typeOfService input[type=checkbox]').click();";
        $this->tester->executeJS($script);
    }

     /**
     * Checkbox que indica que o estudante tem deficiência.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function deficiency ()
    {
        $script = "document.querySelector('#deficiency-checkbox input[type=checkbox]').click();";
        $this->tester->executeJS($script);
    }

     /**
     * Checkbox do tipo de deficiência.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function typeDeficiency ()
    {
        $script = "document.querySelector('#StudentIdentification_deficiencies input[type=checkbox]').click();";
        $this->tester->executeJS($script);
    }

     /**
     * Checkbox para os Recursos requeridos em avaliações do INEP (Prova Brasil, SAEB, outros).
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function resourcesInep ()
    {
        $script = "document.querySelector('#resources-checkbox input[type=checkbox]').click();";
        $this->tester->executeJS($script);
    }

    /**
     * Checkbox de vacina do aluno.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function vaccine ()
    {
        $script = "document.querySelector('#vaccine-checkbox input[type=checkbox]').click();";
        $this->tester->executeJS($script);
    }

    /**
     * Checkbox de restrição do aluno.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function restrictions ()
    {
        $script = "document.querySelector('#restrictions-checkbox input[type=checkbox]').click();";
        $this->tester->executeJS($script);
    }


}
