<?php
require_once 'vendor/autoload.php';
require_once __DIR__."/../robots/LoginRobots.php";
require_once __DIR__."/../robots/WizardRobots.php";
require_once __DIR__.'/../builders/WizardBuilder.php';

class WizardCest
{
    public function _before(AcceptanceTester $tester)
    {
        $user = "admin";
        $secret = "p@s4ipti";

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($user);
        $robots->fieldPassword($secret);
        $robots->submit();
        sleep(2);
    }

    // tests

    /**
     * Matricula em grupo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function groupEnrollment (AcceptanceTester $teste)
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

        $teste->see('Alunos matriculados com sucesso!');
        $teste->canSeeInCurrentUrl('?r=wizard/configuration/student');
    }

}
