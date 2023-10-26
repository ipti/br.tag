<?php

require_once __DIR__."/../robots/LoginRobots.php";

class ChangePasswordCest
{

    public function _before(AcceptanceTester $tester)
    {
        $user = "admin";
        $secret = "p@s4ipti";

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($user);
        $robots->fieldPassword($secret);
        $robots->submit();
        sleep(2);
    }

    // tests
    public function sucess(AcceptanceTester $teste)
    {
        $teste->amOnPage('?r=admin/editPassword&id=1');

        $newSecret = "novaSenha@2023";

        $teste->fillField("#Users_password", $newSecret);
        $teste->fillField("#Confirm", $newSecret);

        $teste->click("#save button");
        sleep(5);
        $teste->canSeeInCurrentUrl('admin/index');
    }
}
