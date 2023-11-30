<?php
require_once __DIR__ . '\\ClassPlanCest.php';
class ClassPlanEditCest
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
    public function edit(AcceptanceTester $teste)
    {
        $addClassPlan = new ClassPlanCest();
        $dataClassPlan = $addClassPlan->createPlan($teste);
        sleep(5);

        $builder = new ClassPlanBuilder();
        $classPlan = $builder->buildCompleted();
        $robots = new ClassPlanRobots($teste);
        $robots->pageClassPlan();

        $search = $dataClassPlan['name'];

        $robots->search($search);
        sleep(5);
        $robots->classPlan($search);

        $robots->name($classPlan['name']);

        $robots->btn2Class();
        $robots->btnSave();
        sleep(5);

        $robots->saveSucess();
    }
}
