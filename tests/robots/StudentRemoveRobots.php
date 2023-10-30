<?php

class StudentRemoveRobots {

    public AcceptanceTester $tester;

    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * Url da página de estudantes.
     */
    public function pageStudents ()
    {
        $this->tester->amOnPage('?r=student');
    }

    /**
     * Botão de deletar.
     */
    public function btnDelete ()
    {
        $this->tester->waitForElement('#student-delete');
        $this->tester->executeJS("document.querySelector('#student-delete').click();");
    }

    /**
     * Pesquisar usuários.
     */
    public function search ($search)
    {
        $this->tester->click('.dataTables_filter input[type="search"]');
        $this->tester->fillField('.dataTables_filter input[type="search"]', $search);
    }

}
