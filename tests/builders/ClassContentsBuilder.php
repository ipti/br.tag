<?php
/**
 * @property [] $classContents
 * @property Faker\Generator $faker
 * @property CustomProvider $fakerCustom
 */
class ClassContentsBuilder
{
    private $faker = null;
    private $fakerCustom = null;

    /**
     * Summary of class contents
     * @var $classContents
     */
    public function __construct()
    {
        $this->faker = Faker\Factory::create('pt_BR');
        $this->fakerCustom = new CustomProvider($this->faker);
        $this->classContents = [];
    }

    public function buildCompleted()
    {
        $this->classContents['classroom'] = '62'; // ACAMPAMENTO
        $this->classContents['month'] = $this->faker->randomElement(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12));
        $this->classContents['disciplines'] = $this->faker->randomElement(array(4, 7, 3));

        return $this->classContents;
    }
}
