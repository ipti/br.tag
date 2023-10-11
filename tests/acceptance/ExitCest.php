<?php

require_once __DIR__."/../robots/LoginRobots.php";

class ExitCest
{

    public function _before(AcceptanceTester $tester)
    {
        $user = "admin";
        $password = "p@s4ipti";

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($user);
        $robots->fieldPassword($password);
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
