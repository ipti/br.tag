<?php

/**
 * @property [] $login
 * @property Faker\Generator $faker
 * @property CustomProvider $fakerCustom
 */
class LoginBuilder
{
    private $faker = null;
    private $fakerCustom = null;

    /**
     * Summary of login
     * @var $login
     */
    public function __construct()
    {
        $this->faker = Faker\Factory::create('pt_BR');
        $this->fakerCustom = new CustomProvider($this->faker);
        $this->login = [];
    }

    public function buildCompleted()
    {
        $this->login['user'] = 'admin';
        $this->login['secret'] = 'p@s4ipti';

        return $this->login;
    }
}
