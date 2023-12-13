<?php
require_once 'vendor/autoload.php';
require_once __DIR__."/../robots/LoginRobots.php";
require_once __DIR__.'/../robots/ClassroomRobots.php';
require_once __DIR__.'/../builders/ClassroomBuilder.php.php';

class ClassroomRemoveCest
{
    public function _before(AcceptanceTester $tester)
    {
        $user = "admin";
        $secret = "p@s4ipti";

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($user);
        $robots->fieldPassword($secret);
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
