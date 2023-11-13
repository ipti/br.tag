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
     * Botão de deletar.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnDelete()
    {
        $this->tester->waitForElement('.delete');
        $this->tester->executeJS("document.querySelector('.delete').click();");
    }

    /**
     * Botão de editar turmas.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnEdit()
    {
        $this->tester->waitForElement('img[alt="Editar"]');
        $this->tester->executeJS('document.querySelector(\'img[alt="Editar"]\').click();');
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
     * Selecione o curso.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function educationCourse($educationCourse)
    {
        $this->tester->selectOption('#Classroom_edcenso_professional_education_course_fk', $educationCourse);
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
    public function days($weeekDays)
    {
        $script = "document.querySelector('$weeekDays').click();";
        $this->tester->executeJS($script);
    }

    /**
     * Checkbox para marcar o tipo de atendimento.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function typeService($typeService)
    {
        $script = "document.querySelector('$typeService').click();";
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

    public function disciplines($disciplines)
    {
        $this->tester->selectOption('#Disciplines', $disciplines);
    }

    /**
     * Selecionar um cargo para turma.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function role($role)
    {
        $this->tester->selectOption('#Role', $role);
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


    // sedsp

    /**
     * Selecione a unidade escolar.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function unity($unity)
    {
        $this->tester->selectOption('#Classroom_sedsp_school_unity_fk', $unity);
    }

    /**
     * Inserir a turma.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function classroom($classroom)
    {
        $this->tester->selectOption('#Classroom_sedsp_acronym', $classroom);
    }

    /**
     * Inserir a sala de aula.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function numberClassroom($numberClassroom)
    {
        $this->tester->fillField('#Classroom_sedsp_classnumber', $numberClassroom);
    }

    /**
     * Inserir a capacidade fisica máxima.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function capacity($capacity)
    {
        $this->tester->fillField('#Classroom_sedsp_max_physical_capacity', $capacity);
    }
}
