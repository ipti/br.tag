<?php

class WizardCest
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
     * Matricula em grupo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function groupEnrollment(AcceptanceTester $teste)
    {
        sleep(5);
        $robots = new WizardRobots($teste);
        $robots->pageGroupEnrollment();
        $builder = new WizardBuilder();
        $dataWizard = $builder->buildCompleted();

        $robots->classrooms($dataWizard['classrooms']);
        $robots->oneClassrom($dataWizard['oneClassrom']);
        $robots->btnSave();
        sleep(2);

        $robots->addSucess();
    }
}
