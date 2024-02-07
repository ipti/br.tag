<?php

/**
 * @property [] $stageVsModality
 * @property Faker\Generator $faker
 * @property CustomProvider $fakerCustom
 */
class ManageStagesBuilder
{

    private $faker = null;
    private $fakerCustom = null;
    /**
     * Summary of StageVsModality
     * @var [] $stageVsModality
     */

    public function __construct()
    {
        $this->faker = Faker\Factory::create('pt_BR');
        $this->fakerCustom = new CustomProvider($this->faker);
        $this->stageVsModality = [];
    }

    public function buildCompleted()
    {
        $this->stageVsModality['name'] = $this->faker->name();
        $this->stageVsModality['stage'] = $this->faker->randomElement(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10));
        $this->stageVsModality['alias'] = substr($this->faker->name(), 15);
        return $this->stageVsModality;
    }
}
