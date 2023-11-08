<?php

class ClassroomRemoveCest
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
        sleep(5);
        $classroom = new StudentsCest();
        $addclassroom = $classroom->addStudentsRapidFieldsRequired($teste);
        $robots = new ClassroomRobots($teste);
        $robots->pageClassroom();

        $search = $addclassroom->classroom['name'];

        $robots->search($search);
        sleep(2);
        $robots->btnDelete();
        $teste->acceptPopup();
        $teste->canSeeInCurrentUrl('?r=classroom/index');
    }
}
