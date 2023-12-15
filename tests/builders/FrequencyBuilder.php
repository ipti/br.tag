<?php
/**
 * @property [] $frequency
 * @property Faker\Generator $faker
 * @property CustomProvider $fakerCustom
 */
class FrequencyBuilder
{
    private $faker = null;
    private $fakerCustom = null;

    /**
     * Summary of frequency
     * @var $frequency
     */
    public function __construct()
    {
        $this->faker = Faker\Factory::create('pt_BR');
        $this->fakerCustom = new CustomProvider($this->faker);
        $this->frequency = [];
    }

    public function buildCompleted()
    {
        $this->frequency['month'] = $this->faker->randomElement(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12));
        $this->frequency['classroom'] = 'TURMA 2023';

        return $this->frequency;
    }
}
