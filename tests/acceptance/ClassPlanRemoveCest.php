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

        $robots->removeSucess();
    }

    public function error(AcceptanceTester $teste)
    {
        $builder = new ClassPlanBuilder();
        $dataBuilder = $builder->buildCompleted();

        $robots = new ClassPlanRobots($teste);
        $robots->pageClassPlan();

        $search = $dataBuilder['search_remove'];
        $robots->search($search);
        sleep(2);
        $robots->btnRemove();
        $teste->acceptPopup();
        sleep(2);

        $robots->removeErro();
    }
}
