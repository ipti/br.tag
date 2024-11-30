<?php

require_once __DIR__ . "/../acceptance/ClassroomCest.php";

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
        $classroom = new ClassroomCest();
        $addclassroom = $classroom->addClassroomInPerson($teste);
        $robots = new ClassroomRobots($teste);
        $robots->pageClassroom();

        $search = $addclassroom['name'];

        $robots->search($search);
        sleep(2);
        $robots->btnDelete();
        $teste->acceptPopup();
    }

    /**
     * Verificar caso de não conseguir excluir a turma.
     */
}
