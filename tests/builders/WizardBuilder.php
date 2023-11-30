<?php

/**
 * @property [] $wizard
 * @property Faker\Generator $faker
 * @property CustomProvider $fakerCustom
 */
class WizardBuilder
{

    private $faker = null;
    private $fakerCustom = null;

    /**
     * Summary of group registration
     * @var $wizard
     */
    public function __construct()
    {
        $this->faker = Faker\Factory::create('pt_BR');
        $this->fakerCustom = new CustomProvider($this->faker);
        $this->wizard = [];
    }

    public function buildCompleted()
    {
        $this->wizard['classrooms'] = '494'; // 261605877 1 ETAPA PREESCOLA D TARDE ANUAL
        $this->wizard['oneClassrom'] = '571'; // DATA

        return $this->wizard;
    }
}
