<?php

require_once __DIR__."/../robots/LoginRobots.php";

class ChangePasswordCest
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
    public function sucess(AcceptanceTester $teste)
    {
        $teste->amOnPage('?r=admin/editPassword&id=1');

        $newpassword = "novaSenha@2023";

        $teste->fillField("#Users_password", $newpassword);
        $teste->fillField("#Confirm", $newpassword);

        $teste->click("#save button");
        sleep(5);
        $teste->canSeeInCurrentUrl('admin/index');
    }
}
