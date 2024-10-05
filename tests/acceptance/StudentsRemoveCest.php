<?php

require_once __DIR__ . "/../robots/StudentRemoveRobots.php";
require_once __DIR__ . "/../acceptance/StudentsCest.php";

class StudentsRemoveCest
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
    public function falied(AcceptanceTester $teste)
    {
        sleep(5);
        $student = new StudentsCest();
        $addStudent = $student->allFilledInOldCivil($teste);
        $robots = new StudentRemoveRobots($teste);
        $robots->pageStudents();

        $search = $addStudent->student['name'];

        $robots->search($search);
        sleep(2);
        $robots->btnDelete();
        $teste->acceptPopup();
        $teste->see('Esse aluno não pode ser excluído, pois existem dados de frequência,
        notas ou matrículadas vinculadas a ele!');
        $teste->canSeeInCurrentUrl('?r=student');
    }

    public function sucess(AcceptanceTester $teste)
    {
        sleep(5);
        $student = new StudentsCest();
        $addStudent = $student->addStudentsRapidFieldsRequired($teste);
        $robots = new StudentRemoveRobots($teste);
        $robots->pageStudents();

        $search = $addStudent->student['name'];

        $robots->search($search);
        sleep(2);
        $robots->btnDelete();
        $teste->acceptPopup();
        $teste->see('Aluno excluído com sucesso!');
        $teste->canSeeInCurrentUrl('?r=student/index');
    }
}
