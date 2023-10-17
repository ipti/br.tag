<?php

require_once __DIR__."/../robots/LoginRobots.php";
require_once __DIR__."/../robots/RegisterUserRobots.php";

class RegisterUserCest
{
    public function _before (AcceptanceTester $tester)
    {
        $user = "admin";
        $password = "p@s4ipti";

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($user);
        $robots->fieldPassword($password);
        $robots->submit();
        sleep(5);
    }

    // tests
    public function registerUser(AcceptanceTester $teste)
    {
        $teste->amOnPage('?r=admin/createUser');

        $name = 'Harry Styles';
        $userName = 'harrystyles';
        $password = 'harry@123';

        $robots = new RegisterUserRobots($teste);
        $robots->name($name);
        $robots->userName($userName);
        $robots->password($password);

        $robots->save();
        sleep(5);
        $teste->canSeeInCurrentUrl('admin/index');
    }
}
