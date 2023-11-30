<?php

class ChangePasswordCest
{
    public function _before(AcceptanceTester $tester)
    {
        $this->startTransaction($tester);

        $builder = new LoginBuilder();
        $login = $builder->buildCompleted();

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($login['user']);
        $robots->fieldPassword($login['secret']);
        $robots->submit();
        sleep(2);
    }

    public function _after(AcceptanceTester $tester)
    {
        $this->rollbackTransaction($tester);
    }

    // Teste
    public function success(AcceptanceTester $tester)
    {
        $tester->amOnPage('?r=admin/editPassword&id=1');

        $newSecret = "novaSenha@2023";

        $tester->fillField("#Users_password", $newSecret);
        $tester->fillField("#Confirm", $newSecret);

        $tester->click("#save button");
        sleep(5);
        $tester->canSeeInCurrentUrl('admin/index');
    }

    private function startTransaction(AcceptanceTester $tester)
    {
        $tester->comment("Iniciando transação...");
    }

    private function rollbackTransaction(AcceptanceTester $tester)
    {
        $tester->comment("Revertendo transação...");

        $tester->amOnPage('?r=admin/editPassword&id=1');

        $builder = new LoginBuilder();
        $login = $builder->buildCompleted();

        $tester->fillField("#Users_password", $login['secret']);
        $tester->fillField("#Confirm", $login['secret']);

        $tester->click("#save button");
        sleep(5);
        $tester->canSeeInCurrentUrl('admin/index');
    }
}
