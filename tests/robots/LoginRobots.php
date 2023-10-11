<?php

class LoginRobots 
{

    public AcceptanceTester $tester;
    
    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * Url da página de login.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function pageLogin()
    {
        $this->tester->amOnPage('/');
    }

    /**
     * Preencher campo de usuário da tela de login.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function fieldUser($user)
    {
        $this->tester->fillField("#LoginForm_username", $user);
    }

    /**
     * Preencher campo de senha da tela de login.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function fieldPassword($password)
    {
        $this->tester->fillField("#LoginForm_password", $password);
    }

    /**
     * Botão de submeter o login (entrar).
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function submit()
    {
        $this->tester->click('.submit-button-login');
    }

    /**
     * Checkbox par relembrar as informações de login.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function rememberMe()
    {
        $this->tester->click('#remember');
    }

}

?>