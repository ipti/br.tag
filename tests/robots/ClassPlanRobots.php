<?php

class ClassPlanRobots
{
    public AcceptanceTester $tester;
    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * Caso de teste para salvar plano de aula.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function saveSucess()
    {
        $this->tester->see('Plano de Curso salvo com sucesso!');
        $this->tester->canSeeInCurrentUrl('?r=courseplan/index');
    }

    /**
     * Caso de teste para excluir plano de aula.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function removeSucess()
    {
        $this->tester->see('Plano de aula excluído com sucesso!');
    }

    /**
     * Caso de teste para excluir plano de aula (erro).
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function removeErro()
    {
        $this->tester->see('Não se pode remover plano de aula utilizado em alguma turma.');
    }

    /**
     * Url da página principal de plano de curso.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function pageClassPlan()
    {
        $this->tester->amOnPage("?r=courseplan");
    }

    /**
     * Url da página de criar plano de curso.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function pageCreate()
    {
        $this->tester->amOnPage('?r=courseplan/create');
    }

    /**
     * Pesquisa o plano de curso.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function search($search)
    {
        $this->tester->click('.dataTables_filter input[type="search"]');
        $this->tester->fillField('.dataTables_filter input[type="search"]', $search);
    }

    /**
     * Botão de próximo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnNext()
    {
        $this->tester->waitForElement('.next');
        $this->tester->executeJS("document.querySelector('.next').click();");
    }

    /**
     * Botão de anterior.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnPrevious()
    {
        $this->tester->waitForElement('.prev');
        $this->tester->executeJS("document.querySelector('.prev').click();");
    }

    /**
     * Botão de salvar.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnSave()
    {
        $this->tester->waitForElement('.last');
        $this->tester->executeJS("document.querySelector('.last').click();");
    }

    /**
     * Botão de remover.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnRemove()
    {
        $this->tester->waitForElement('.delete');
        $this->tester->executeJS("document.querySelector('.delete').click();");
    }

    /**
     * Botão de adicionar novo plano.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btnNewPlan()
    {
        $this->tester->waitForElement("#new-course-class");
        $this->tester->executeJS("document.querySelector('#new-course-class').click();");
    }

    /**
     * Botão 1: Criar plano.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btn1CreatePlan()
    {
        $this->tester->waitForElementVisible('#tab-create-plan');
        $this->tester->executeJS("document.querySelector('#tab-create-plan a').click();");
    }

    /**
     * Botão 2: Aula.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function btn2Class()
    {
        $this->tester->waitForElementVisible('#tab-class');
        $this->tester->executeJS("document.querySelector('#tab-class a').click();");
    }

    /**
     * Preencher nome do plano de aula.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function name($name)
    {
        $this->tester->fillField('#CoursePlan_name', $name);
    }

    /**
     * Selecionar a etapa.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function stage($stage)
    {
        $this->tester->selectOption('#CoursePlan_modality_fk', $stage);
    }

    /**
     * Selecionar o componente curricular/eixo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function component($component)
    {
        $this->tester->selectOption('#CoursePlan_discipline_fk', $component);
    }

    public function classPlan($classPlan)
    {
        $xpathSelector = "//a[text()='$classPlan']";

        // Clique no link usando o seletor XPath
        $this->tester->click($xpathSelector);
    }
}
