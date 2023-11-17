<?php

require_once __DIR__ . '\\ClassPlanCest.php';

class ClassPlanRemoveCest
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
    public function sucess(AcceptanceTester $teste)
    {
        $create = new ClassPlanCest();
        $classPlan = $create->createPlan($teste);

        $robots = new ClassPlanRobots($teste);
        $robots->pageClassPlan();

        $search = $classPlan['name'];
        $robots->search($search);
        sleep(2);
        $robots->btnRemove();
        $teste->acceptPopup();
        sleep(5);

        $teste->see('Plano de aula excluído com sucesso!');
    }

    public function error(AcceptanceTester $teste)
    {
        $create = new ClassPlanCest();
        $classPlan = $create->createPlan($teste);

        // criar aulas ministradas
        // quadro de horarios
        // matrix
        // turmas

        // isso tudo para excluir um plano de aula


        $robots = new ClassPlanRobots($teste);
        $robots->pageClassPlan();

        $search = $classPlan['name'];
        $robots->search($search);
        sleep(2);
        $robots->btnRemove();
        $teste->acceptPopup();
        sleep(2);

        $teste->see('Não se pode remover plano de aula utilizado em alguma turma.');
    }
}
