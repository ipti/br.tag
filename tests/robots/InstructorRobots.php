<?php

class InstructorRobots
{
    public AcceptanceTester $tester;

    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    /* Acessando página de adicionar professor */
    public function pageAddInstructor ()
    {
        $this->tester->amOnPage('?r=instructor/create');
    }

    /* Clicar no botão próximo para mudar de página */
    public function btnProximo ()
    {
        $this->tester->waitForElement('#btnNext');
        $this->tester->executeJS("document.querySelector('#btnNext').click();");
    }

    public function btnSave()
    {
        $this->tester->waitForElement('#btnSection button');
        $this->tester->executeJS("document.querySelector('#btnSection button').click()");
    }

    /* Preencher o nome do professor */
    public function name($name)
    {
        $this->tester->fillField("#InstructorIdentification_name", $name);
    }

    /* Preencher o email */
    public function email($email)
    {
        $this->tester->fillField("#InstructorDocumentsAndAddress_cpf", $email);
    }

    /* Selecionar a nacionalidade */
    public function nationality ($nationality)
    {
        $this->tester->selectOption("#nationality_select select", $nationality);
    }

    /* Preencher o cpf */
    public function cpf ($cpf)
    {
        $this->tester->fillField('#cpfInstructor input[type=text]', $cpf);
    }

    /* Selecionar o estado */
    public function state ($state)
    {
        // $this->tester->selectOption('#state-select select', $state);
        $this->tester->executeJS("document.querySelector('#state-select select').value = '($state)';");
    }

    /* Selecionar a cidade */
    public function city ($city)
    {
        // $this->tester->selectOption('#city-select select', $city);
        $this->tester->executeJS("document.querySelector('#city-select select').value = '($city)';");

    }

    /* Preencher o nis */
    public function nis ($nis)
    {
        $this->tester->fillField('#InstructorIdentification_nis',$nis);
    }

    /* Preencher a data de nascimento */
    public function dateOfBirth ($dateOfBirth)
    {
        $this->tester->fillField('#InstructorIdentification_birthday_date', $dateOfBirth);
    }

    /* Selecionar o gênero */
    public function gender ($gender)
    {
        $this->tester->selectOption('#gender-select select', $gender);
    }

    /* Selecionar a raça */
    public function colorRace ($colorRace)
    {
        // $this->tester->fillField('#colorRace', $colorRace);
        $this->tester->executeJS("document.querySelector('#colorRace').value = '($colorRace)';");
    }

    /* Selecionar a filiação */
    public function filiationSelect ($filiation)
    {
        // $this->tester->selectOption('#filiation-select', $filiation);
        $this->tester->executeJS("document.querySelector('#filiation-select').value = '($filiation)';");
    }
    /* Preencher o campo de filiação 1 */
    public function filiationSelect1 ($filiationName){
        $this->tester->fillField("#InstructorIdentification_filiation_1", $filiationName);
        // $this->tester->executeJS("document.querySelector('#filiation-select_1').value = '($filiationName)';");

    }

    /* Preencher o cep */
    public function cep($cep)
    {
        $this->tester->fillField('#InstructorDocumentsAndAddress_cep',$cep);
    }
    /* Selecionar o estado */
    public function stateAddress ($state)
    {
        // $this->tester->selectOption('#state-select select', $state);
        $this->tester->executeJS("document.querySelector('#s2id_InstructorDocumentsAndAddress_edcenso_uf_fk').value = '($state)';");
    }

    /* Selecionar a cidade */
    public function cityAddress ($city)
    {
        // $this->tester->selectOption('#city-select select', $city);
        $this->tester->executeJS("document.querySelector('#s2id_InstructorDocumentsAndAddress_edcenso_city_fk).value = '($city)';");

    }
    /* Preencher o endereço */
    public function address ($address)
    {
        $this->tester->fillField('#InstructorDocumentsAndAddress_address', $address);
    }

    /* Preencher o número */
    public function number ($number)
    {
        $this->tester->fillField('#InstructorDocumentsAndAddress_address_number', $number);
    }

    /* Preencher o bairro/povoado */
    public function neighborhood ($neighborhood)
    {
        $this->tester->fillField('#InstructorDocumentsAndAddress_neighborhood', $neighborhood);
    }

    /* Preencher a localização diferenciada */
    public function location ($location)
    {
        $this->tester->selectOption('#location-select select', $location);
    }

    /* Selecionar a zona de residência */
    public function zone($zone)
    {
        $this->tester->executeJS("document.querySelector('#zone-select select').value = '{$zone}';");
    }

    /* Selecionar a escolaridade */
    public function scholarity ($scholarity)
    {
        $this->tester->selectOption('#instructor-data select', $scholarity);
    }

    /*  */
}
