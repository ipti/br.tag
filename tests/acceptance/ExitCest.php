<?php

class ExitCest
{
    public function _before(AcceptanceTester $tester)
    {
        $builder = new LoginBuilder();
        $login = $builder->buildCompleted();

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($login['user']);
        $robots->fieldPassword($login['secret']);
        $robots->submit();
        sleep(2);
    }

    // tests
    public function exitPage(AcceptanceTester $teste)
    {
        $teste->amOnPage('/');
        $teste->click('.t-button-tertiary');
        sleep(5);
        $teste->canSeeInCurrentUrl('site/login');
    }
}
