<?php

require_once 'vendor/autoload.php';
require_once __DIR__."/../robots/LoginRobots.php";
require_once __DIR__.'/../robots/ManageStagesRobots.php';

class ManageStagesCest
{
    public function _before(AcceptanceTester $teste)
    {
        $user = "admin";
        $password = "p@s4ipti";

        $robots = new LoginRobots($teste);
        $robots->pageLogin();
        $robots->fieldUser($user);
        $robots->fieldPassword($password);
        $robots->submit();
        sleep(2);
    }

    // tests
    public function addStageRequired(AcceptanceTester $teste)
    {
        $faker = Faker\Factory::create('pt_BR');
        $robots = new StagesRobots($teste);
        $robots->pageAddStage();

        $name = $faker->name();
        $stage = $faker->randomElement(array (1,2,3,4,5,6,7,8,9,10));
        $alias = substr($faker->name(), 15);

        //stages
        $robots->name($name);
        $robots->stage($stage);
        $robots->alias($alias);

        $robots->btnCriar();

        $teste->see('O Cadastro foi criado com sucesso!');
        $teste->canSeeInCurrentUrl('?r=stages/default/index');
    }
    public function addStageFilledIn(AcceptanceTester $teste)
    {
        $faker = Faker\Factory::create('pt_BR');
        $robots = new StagesRobots($teste);
        $robots->pageAddStage();

        $name = $faker->name();
        $stage = $faker->randomElement(array (1,2,3,4,5,6,7,8,9,10));
        $alias = substr($faker->name(), 15);

        //stages
        $robots->name($name);
        $robots->stage($stage);

        $robots->btnCriar();

        $teste->see('O Cadastro foi criado com sucesso!');
        $teste->canSeeInCurrentUrl('?r=stages/default/index');
    }
}
