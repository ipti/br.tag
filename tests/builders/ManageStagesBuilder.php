<?php

/**
 * @property EdcensoStageVsModality $stageVsModality
 * @property Faker\Generator $faker
 * @property CustomProvider $fakerCustom
 */
class ManageStagesBuilder
{

    private $faker = null;
    private $fakerCustom = null;
    /**
     * Summary of EdcensoStageVsModality
     * @var EdcensoStageVsModality $stageVsModality
     */

    public function __construct()
    {
        $this->faker = Faker\Factory::create('pt_BR');
        $this->fakerCustom = new CustomProvider($this->faker);
        $this->stageVsModality = [];
    }

    /**
     * @return ManageStagesBuilder
     */
    public function buildCompleted()
    {
        $stageVsModality['name'] = $this->faker->name();
        $stageVsModality['stage'] = $this->faker->randomElement(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10));
        $stageVsModality['alias'] = substr($this->faker->name(), 15);
        return $stageVsModality;
    }
}
