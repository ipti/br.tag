<?php

require_once __DIR__.'/../robots/LoginRobots.php';

class LoginCest
{
    // tests

    public function frontpageWorks(AcceptanceTester $tester)
    {
        $user = '';
        $secret = '';

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($user);
        $robots->fieldPassword($secret);
        $robots->submit();
        sleep(2);
        $tester->see('Bem vindo ao');
    }

    public function errorUser(AcceptanceTester $tester)
    {
        $user = 'coentro';
        $secret = '';

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($user);
        $robots->fieldPassword($secret);
        $robots->submit();
        sleep(5);
        $tester->see('Usuário ou senha incorretos');
    }

    public function errorPassword(AcceptanceTester $tester)
    {
        $user = 'admin';
        $secret = 'coentro';

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($user);
        $robots->fieldPassword($secret);
        $robots->submit();
        sleep(5);
        $tester->see('Usuário ou senha incorretos');
    }
}
