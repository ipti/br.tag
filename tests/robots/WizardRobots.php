<?php

class WizardRobots
{

    public AcceptanceTester $tester;
    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * Caso de teste para adicionar matricula de aluno.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function addSucess()
    {
        $this->tester->see('Alunos matriculados com sucesso!');
        $this->tester->canSeeInCurrentUrl('?r=wizard/configuration/student');
    }

    /**
     * Página de matricula em grupo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function pageGroupEnrollment()
    {
        $this->tester->amOnPage('?r=wizard/configuration/student');
    }

    /**
     * Selecione uma ou mais Turmas.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function classrooms($classrooms)
    {
        $this->tester->selectOption('#classrooms-select select', $classrooms);
    }

    /**
     * Selecione uma turma.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function oneClassrom($oneClassrom)
    {
        $this->tester->selectOption('#oneClassrom-select select', $oneClassrom);
    }

    /**
     * Botão de salvar.
     */
    public function btnSave()
    {
        $this->tester->waitForElement('.t-button-primary.last');
        $this->tester->executeJS("document.querySelector('#save button').click();");
    }
}
