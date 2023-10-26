<?php

class RegisterUserRobots
{

    public AcceptanceTester $tester;

    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * Selecionar a opção de cargo ao cadastrar usuário.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function cargo()
    {
        $option = $this->tester->grabTextFrom("select2-chosen");
        $this->tester->selectOption("select", $option);
    }

    /**
     * Preencher o nome do usuário.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function name($name)
    {
        $this->tester->fillField('#Users_name', $name);
    }

    /**
     * Preencher o campo de usuário.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function userName($userName)
    {
        $this->tester->fillField('#Users_username', $userName);
    }

    /**
     * Preencher a senha do usuário.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function password($password)
    {
        $this->tester->fillField('#Users_password', $password);
    }

    /**
     * Preencher a escola do usuário.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function escola()
    {
        $option = $this->tester->grabTextFrom("select2-chosen");
        $this->tester->selectOption("select", $option);
    }

    /**
     * Preencher se o usuário está ativo.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function active()
    {
        $this->tester->canSeeCheckboxIsChecked("#Users_active");
    }

    /**
     * Salvar as informações do usuário.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function save()
    {
         $this->tester->click("#createUser button");
    }
}