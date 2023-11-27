<?php

require_once __DIR__ . '\\ClassroomCest.php';
class ClassContentsCest
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
    public function register(AcceptanceTester $teste)
    {
        $builder = new ClassContentsBuilder();
        $dataClassContents = $builder->buildCompleted();

        $robots = new ClassContentsRobots($teste);
        $robots->page();
        sleep(5);
        $robots->classroom($dataClassContents['classroom']);
        sleep(2);
        $robots->month($dataClassContents['month']);
        sleep(2);
        $robots->btnSave();
        sleep(2);

        $robots->updateSucess();
    }
}
