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
    public function register(AcceptanceTester $teste, $addMatrix)
    {
        sleep(5);

        $classroom = new ClassroomCest();
        $addClassroom = $classroom->allFieldsAddClassroomEAD($teste);

        $builder = new ClassContentsBuilder();
        $dataClassContents = $builder->buildCompleted();

        $robots = new ClassContentsRobots($teste);
        $robots->page();
        sleep(5);
        $robots->classroom($addClassroom['name']);
        sleep(2);
        $robots->month($dataClassContents['month']);
        sleep(2);
        $robots->component($addMatrix['disciplines']);
        sleep(10);
        // $robots->component();
    }
}
