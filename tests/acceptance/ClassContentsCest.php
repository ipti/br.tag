<?php

require_once __DIR__ . '\\ClassroomCest.php';
class ClassContentsCest
{
    public function _before(AcceptanceTester $tester)
    {
        $builder = new LoginBuilder();
        $login = $builder->buildCompleted();

        $builder = new LoginBuilder();
        $login = $builder->buildCompleted();

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($login['user']);
        $robots->fieldPassword($login['secret']);
        $robots->submit();
    }

    // tests
    public function register(AcceptanceTester $teste)
    {
        $builder = new ClassContentsBuilder();
        $dataClassContents = $builder->buildCompleted();

        $robots = new ClassContentsRobots($teste);
        $robots->page();
        $robots->classroom($dataClassContents['classroom']);
        $robots->month($dataClassContents['month']);
        $robots->component($dataClassContents['disciplines']);
        $robots->btnSave();
        sleep(2);

        $robots->updateSucess();
    }
}
