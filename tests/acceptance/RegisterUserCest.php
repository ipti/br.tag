<?php

require_once __DIR__.'/../robots/LoginRobots.php';
require_once __DIR__.'/../robots/RegisterUserRobots.php';

class RegisterUserCest
{
    public function _before(AcceptanceTester $tester)
    {
        $user = '';
        $secret = '';

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($user);
        $robots->fieldPassword($secret);
        $robots->submit();
        sleep(5);
    }

    // tests
    public function registerUser(AcceptanceTester $teste)
    {
        $teste->amOnPage('?r=admin/createUser');

        $name = 'Harry Styles';
        $userName = 'harrystyles';
        $secret = 'harry@123';

        $robots = new RegisterUserRobots($teste);
        $robots->name($name);
        $robots->userName($userName);
        $robots->password($secret);

        $robots->save();
        sleep(5);
        $teste->canSeeInCurrentUrl('admin/index');
    }
}
