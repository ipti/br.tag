<?php

require_once __DIR__."/../robots/LoginRobots.php";

class LoginCest
{
    // tests
    public function frontpageWorks(AcceptanceTester $tester)
    {
        $user = "admin";
        $password = "p@s4ipti";

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($user);
        $robots->fieldPassword($password);
        $robots->submit();
        sleep(2);
        $tester->see('Bem vindo ao');
    }

    public function errorUser(AcceptanceTester $tester)
    {
        $user = "coentro";
        $password = "p@s4ipti";

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($user);
        $robots->fieldPassword($password);
        $robots->submit();
        sleep(5);
        $tester->see('Usuário ou senha incorretos');
    }

    public function errorPassword(AcceptanceTester $tester)
    {
        $user = "admin";
        $password = "coentro";

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($user);
        $robots->fieldPassword($password);
        $robots->submit();
        sleep(5);
        $tester->see('Usuário ou senha incorretos');
    }

}