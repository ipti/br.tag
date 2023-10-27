<?php

require_once 'vendor/autoload.php';
require_once __DIR__."/../robots/LoginRobots.php";
require_once __DIR__.'/../robots/ManageStagesRobots.php';
require_once __DIR__.'/../builders/ManageStagesBuilder.php';

class ManageStagesCest
{
    public function _before(AcceptanceTester $teste)
    {
        $user = "admin";
        $secret = "p@s4ipti";

        $robots = new LoginRobots($teste);
        $robots->pageLogin();
        $robots->fieldUser($user);
        $robots->fieldPassword($secret);
        $robots->submit();
        sleep(3);
    }

    // tests
    public function addStageRequired(AcceptanceTester $teste)
    {
        $robots = new StagesRobots($teste);
        $robots->pageAddStage();
        $builder = new ManageStagesBuilder();
        $targetUrl = '?r=stages/default/index';

        $stage= $builder->buildCompleted();

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

        $stage= $builder->buildCompleted();

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

        $robots->checkUpdate($newStage['name'],$newStage['stage'],$newStage['alias']);

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
