<?php

require_once __DIR__ . "/../robots/RegisterUserRobots.php";

class RegisterUserCest
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
