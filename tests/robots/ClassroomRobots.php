<?php

class ClassroomRobots
{
    public AcceptanceTester $tester;
    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * Url do módulo de turma.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function pageClassroom ()
    {
        $this->tester->amOnPage('?r=classroom');
    }

    /**
     * Url da página de adicionar turma.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function pageAddClassroom ()
    {
        $this->tester->amOnPage('?r=classroom/create');
    }

    /**
     * Botão de criar na tela de cadastros de turmas.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnCriar ()
    {
        $this->tester->waitForElement('.save-classroom');
        $this->tester->executeJS("document.querySelector('.save-classroom').click();");
    }

    /**
     * Preenche o nome da turma.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function name ($name)
    {
        $this->tester->fillField('#Classroom_name', $name);
    }

    /**
     * Seleciona o estágio vs modalidade.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function stageVsModalaty ($stage)
    {
        $this->tester->selectOption('#Classroom_edcenso_stage_vs_modality_fk', $stage);
    }

    /**
     * Selecione o tipo de Mediação Didático-Pedagógica.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function typeMediation ($type)
    {
        $this->tester->selectOption('#Classroom_pedagogical_mediation_type', $type);
    }

    /**
     * Selecione a Modalidade.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function modality ($modality)
    {
        $this->tester->selectOption('#Classroom_modality', $modality);
    }

    /**
     * Selecione o curso.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function educationCourse ($educationCourse)
    {
        $this->tester->selectOption('#Classroom_edcenso_professional_education_course_fk', $educationCourse);
    }

    /**
     * Preenche o horário inicial.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function initialTime ($initialTime)
    {
        $this->tester->fillField('#Classroom_initial_time', $initialTime);
    }

    /**
     * Preenche o horário final.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function finalTime ($finalTime)
    {
        $this->tester->fillField('#Classroom_final_time', $finalTime);
    }

    /**
     * Seleciona o turno.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function turn ($turn)
    {
        $this->tester->selectOption('#Classroom_turn', $turn);
    }

}
