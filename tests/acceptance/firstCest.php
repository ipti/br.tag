<?php

class firstCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function frontpageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->fillField("#LoginForm_username", "admin");
        $I->fillField("#LoginForm_password", "p@s4ipti");
        $I->click('.submit-button-login');
        sleep(5);
        $I->see('Bem vindo ao');
    }
}