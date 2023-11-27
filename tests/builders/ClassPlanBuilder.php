<?php

/**
 * @property [] $classplan
 * @property Faker\Generator $faker
 * @property CustomProvider $fakerCustom
 */
class ClassPlanBuilder
{
    private $faker = null;
    private $fakerCustom = null;

    /**
     * Summary of class plan
     * @var $classplan
     */
    public function __construct()
    {
        $this->faker = Faker\Factory::create('pt_BR');
        $this->fakerCustom = new CustomProvider($this->faker);
        $this->classplan = [];
    }

    public function buildCompleted()
    {
        $this->classplan['name'] = $this->fakerCustom->classPlan();
        $this->classplan['search_remove'] = 'Gramática Básica';

        return $this->classplan;
    }

}
