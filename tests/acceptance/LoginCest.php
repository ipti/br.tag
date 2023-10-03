
<?php

class LoginCest
{
    // tests
    public function frontpageWorks(AcceptanceTester $tester)
    {
        $tester->amOnPage('/');//
        $tester->fillField("#LoginForm_username", "admin");
        $tester->fillField("#LoginForm_password", "p@s4ipti");
        $tester->click('.submit-button-login');
        sleep(5);
        $tester->see('Bem vindo ao');
    }
}
