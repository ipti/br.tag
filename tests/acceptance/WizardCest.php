<?php
require_once 'vendor/autoload.php';
require_once __DIR__."/../robots/LoginRobots.php";
require_once __DIR__."/../robots/WizardRobots.php";

class WizardCest
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
     * Matricula em grupo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function groupEnrollment (AcceptanceTester $teste)
    {
        sleep(5);
        $robots = new WizardRobots($teste);
        $robots->pageGroupEnrollment();

        $classrooms = '483'; // Turma 1
        $oneClassrom = '494'; // 261605877 1 ETAPA PREESCOLA D TARDE ANUAL

        $robots->classrooms($classrooms);
        $robots->oneClassrom($oneClassrom);
        $robots->btnSave();
        sleep(5);

        $teste->see('Alunos matriculados com sucesso!');
        $teste->canSeeInCurrentUrl('?r=wizard/configuration/student');
    }

}