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
    public function pageClassroom()
    {
        $this->tester->amOnPage('?r=classroom');
    }

    /**
     * Url da página de adicionar turma.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function pageAddClassroom()
    {
        $this->tester->amOnPage('?r=classroom/create');
    }

    /**
     * Botão para adicionar professor / componentes curriculares.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnInstructor()
    {
        $this->tester->click('#newDiscipline');
    }

    /**
     * Botão de criar na tela de cadastros de turmas.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnCriar()
    {
        $this->tester->waitForElement('.save-classroom');
        $this->tester->executeJS("document.querySelector('.save-classroom').click();");
    }

    /**
     * Botão 1: Dados da turma.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btn1Classroom()
    {
        $this->tester->waitForElementVisible('#tab-classroom');
        $this->tester->executeJS("document.querySelector('#tab-classroom a').click();");
    }

    /**
     * Botão 2: Dados dos professores.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btn2Instructors()
    {
        $this->tester->waitForElementVisible('#tab-instructors');
        $this->tester->executeJS("document.querySelector('#tab-instructors a').click();");
    }

    /**
     * Botão 3: Dados dos alunos.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btn3Students()
    {
        $this->tester->waitForElementVisible('#tab-students');
        $this->tester->executeJS("document.querySelector('#tab-students a').click();");
    }

    /**
     * Botão 3: Ordem no diário.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btn4Order()
    {
        $this->tester->waitForElementVisible('#tab-daily');
        $this->tester->executeJS("document.querySelector('#tab-students a').click();");
    }

    /**
     * Pesquisa a turma.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function search($search)
    {
        $this->tester->click('.dataTables_filter input[type="search"]');
        $this->tester->fillField('.dataTables_filter input[type="search"]', $search);
    }

    /**
     * Preenche o nome da turma.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function name($name)
    {
        $this->tester->fillField('#Classroom_name', $name);
    }

    /**
     * Seleciona o estágio vs modalidade.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function stageVsModalaty($stage)
    {
        $this->tester->selectOption('#Classroom_edcenso_stage_vs_modality_fk', $stage);
    }

    /**
     * Selecione o tipo de Mediação Didático-Pedagógica.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function typeMediation($type)
    {
        $this->tester->selectOption('#Classroom_pedagogical_mediation_type', $type);
    }

    /**
     * Selecione a Modalidade.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function modality($modality)
    {
        $this->tester->selectOption('#Classroom_modality', $modality);
    }

    /**
     * Seleciona a localização, caso a turma seja presencial.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function location($location)
    {
        $this->tester->selectOption('#Classroom_diff_location', $location);
    }

    /**
     * Selecione o curso.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function educationCourse($educationCourse)
    {
        $this->tester->selectOption('#Classroom_edcenso_professional_education_course_fk', $educationCourse);
    }

    /**
     * Preenche o horário inicial.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function initialTime($initialTime)
    {
        $this->tester->fillField('#Classroom_initial_time', $initialTime);
    }

    /**
     * Preenche o horário final.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function finalTime($finalTime)
    {
        $this->tester->fillField('#Classroom_final_time', $finalTime);
        $this->tester->pressKey('#Classroom_final_time', \Facebook\WebDriver\WebDriverKeys::TAB);
    }

    /**
     * Seleciona o turno.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function turn($turn)
    {
        $this->tester->selectOption('#Classroom_turn', $turn);
    }

    /**
     * Checkbox para marcar os dias da semana.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function days()
    {
        $script = "document.querySelector('#Classroom_week_days_monday').click();";
        $this->tester->executeJS($script);
    }

    /**
     * Checkbox para marcar o tipo de atendimento.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function typeService()
    {
        $script = "document.querySelector('#Classroom_schooling').click();";
        $this->tester->executeJS($script);
    }

    /**
     * Checkbox para marcar que o tipo de atividade é atividade complementar.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function typeServiceActivitiesComplementary()
    {
        $script = "document.querySelector('#Classroom_complementary_activity').click();";
        $this->tester->executeJS($script);
    }

    /**
     * Selecione o tipo de atividade complementar.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function activitiesComplementary($complementary)
    {
        $this->tester->selectOption('#Classroom_complementary_activity_type_1', $complementary);
    }

    /**
     * Checkbox para atividades do atendimento educacional especializado.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function activitiesEducation($activitiesEducation)
    {
        $this->tester->click($activitiesEducation);
    }

    /**
     * Selecionar professor para turma.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function instructorsToClassroom($instructors)
    {
        $this->tester->selectOption('#Instructors', $instructors);
    }

    /**
     * Selecionar um cargo para turma.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function role($role)
    {
        $this->tester->selectOption('#role', $role);
    }

    /**
     * Selecionar o tipo de contrato.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function contractType($contractType)
    {
        $this->tester->selectOption('#ContractType', $contractType);
    }

    /**
     * Botão de criar novo componente.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function createNewComponent()
    {
        $this->tester->click('.ui-dialog-buttonset > button:nth-child(1)');
    }
}
