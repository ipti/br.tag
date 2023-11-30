<?php

require_once __DIR__ . '\\MatrixCest.php';
class ClassPlanCest
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
    public function createPlan(AcceptanceTester $teste)
    {
        sleep(5);
        $matrix = new MatrixCest();
        $addMatrix = $matrix->addMatrix($teste);

        sleep(5);
        $builder = new ClassPlanBuilder();
        $classPlan = $builder->buildCompleted();
        $robots = new ClassPlanRobots($teste);
        $robots->pageCreate();

        $robots->name($classPlan['name']);
        $robots->stage($addMatrix['stages']);
        sleep(5);
        $robots->component($addMatrix['disciplines']);

        $robots->btn2Class();
        $robots->btnSave();
        sleep(5);

        $robots->saveSucess();

        return $classPlan;
    }
}
