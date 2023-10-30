<?php

class StudentRemoveRobots {

    public AcceptanceTester $tester;

    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    public function pageStudents ()
    {
        $this->tester->amOnPage('?r=student');
    }

    public function btnDelete ()
    {
        $this->tester->waitForElement('#student-delete');
        $this->tester->executeJS("document.querySelector('#student-delete').click();");
    }

    public function alertDelete ()
    {
        $this->tester->executeJS('
        window.confirm = function(message)
        {
            return true;
        };');

    }

    public function search ($search)
    {
        $this->tester->click('.dataTables_filter input[type="search"]');
        $this->tester->fillField('.dataTables_filter input[type="search"]', $search);
    }

}
