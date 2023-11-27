<?php

class TimesheetCest
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
    public function toGenerate(AcceptanceTester $teste)
    {
        $builder = new TimesheetBuilder();
        $dataBuilder = $builder->buildCompleted();

        $robots = new TimesheetRobots($teste);
        $robots->pageTimesheet();
        sleep(2);
        $robots->classroom($dataBuilder['classroom_fk']);
        sleep(2);
        $robots->btnGenerate();
        sleep(10);

        return $dataBuilder;
    }
}
