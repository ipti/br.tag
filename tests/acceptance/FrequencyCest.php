<?php

class FrequencyCest
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
     * Seleciona o ano 2023.
     * Adiciona frequência.
     */
    public function addFrequency(AcceptanceTester $teste)
    {
        $builder = new FrequencyBuilder();
        $dataFrequency = $builder->buildCompleted();

        $robots = new FrequencyRobots($teste);

        $robots->yearSelect('2023');
        $robots->pageFrequency();
        $robots->month($dataFrequency['month']);

        sleep(4);

    }
}
