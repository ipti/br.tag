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
    }

    // tests
    public function toGenerate(AcceptanceTester $teste)
    {
        $builder = new TimesheetBuilder();
        $dataBuilder = $builder->buildCompleted();

        $robots = new TimesheetRobots($teste);
        $robots->pageTimesheet();
        $robots->classroom($dataBuilder['classroom_fk']);
        $robots->btnGenerate();
        $robots->btnConfirm();

        return $dataBuilder;
    }
}
