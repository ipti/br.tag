<?php

class ManageStagesCest
{
    public function _before(AcceptanceTester $teste)
    {
        $builder = new LoginBuilder();
        $login = $builder->buildCompleted();

        $robots = new LoginRobots($teste);
        $robots->pageLogin();
        $robots->fieldUser($login['user']);
        $robots->fieldPassword($login['secret']);
        $robots->submit();
        sleep(2);
    }

    // tests
    public function addStageRequired(AcceptanceTester $teste)
    {
        $robots = new StagesRobots($teste);
        $robots->pageAddStage();
        $builder = new ManageStagesBuilder();
        $targetUrl = '?r=stages/default/index';

        $stage = $builder->buildCompleted();

        //stages
        $robots->name($stage['name']);
        $robots->stage($stage['stage']);
        $robots->alias($stage['alias']);

        $robots->btnCriar();

        $teste->see('O Cadastro foi criado com sucesso!');
        $teste->canSeeInCurrentUrl($targetUrl);
    }
    public function addStageFilledIn(AcceptanceTester $teste)
    {
        $robots = new StagesRobots($teste);
        $robots->pageAddStage();
        $builder = new ManageStagesBuilder();
        $targetUrl = '?r=stages/default/index';

        $stage = $builder->buildCompleted();

        //stages
        $robots->name($stage['name']);
        $robots->stage($stage['stage']);

        $robots->btnCriar();

        $teste->see('O Cadastro foi criado com sucesso!');
        $teste->canSeeInCurrentUrl($targetUrl);
    }
    public function updateStage(AcceptanceTester $teste)
    {
        $robots = new StagesRobots($teste);
        $robots->pageAddStage();
        $builder = new ManageStagesBuilder();
        $targetUrl = '?r=stages/default/update';

        $stage = $builder->buildCompleted();

        //stages
        $robots->name($stage['name']);
        $robots->stage($stage['stage']);
        $robots->alias($stage['alias']);

        $robots->btnCriar();

        $robots->setSearchValue($stage['name']);
        $robots->clickUpdate();

        $newStage = $builder->buildCompleted();

        $robots->name($newStage['name']);
        $robots->stage($newStage['stage']);
        $robots->alias($newStage['alias']);

        $robots->btnCriar();

        $robots->checkUpdate($newStage['name'], $newStage['stage'], $newStage['alias']);

        $teste->canSeeInCurrentUrl($targetUrl);
    }
    public function deleteStage(AcceptanceTester $teste)
    {
        $robots = new StagesRobots($teste);
        $robots->pageAddStage();
        $builder = new ManageStagesBuilder();
        $targetUrl = '?r=stages/default/index';

        $stage = $builder->buildCompleted();

        //stages
        $robots->name($stage['name']);
        $robots->stage($stage['stage']);
        $robots->alias($stage['alias']);

        $robots->btnCriar();

        $robots->setSearchValue($stage['name']);
        $robots->clickDelete();
        $robots->acceptPopUp();
        sleep(3);

        $teste->see('Aluno excluído com sucesso!');
        $teste->canSeeInCurrentUrl($targetUrl);
    }
}
