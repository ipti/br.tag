<?php
require_once 'vendor/autoload.php';
require_once 'app/vendor/autoload.php';
require_once __DIR__ . '/../providers/CustomProvider.php';

$yiit = __DIR__ . '\..\..\app\vendor\yiisoft\yii\framework\yiit.php';
require_once($yiit);

$config = __DIR__ . '/../../app/config/test.php';

Yii::createWebApplication($config);

/**
 * @property [] $matrix
 * @property Faker\Generator $faker
 * @property CustomProvider $fakerCustom
 */
class MatrixBuilder
{
    private $faker = null;
    private $fakerCustom = null;

    /**
     * Summary of matrix
     * @var $matrix
     */
    public function __construct()
    {
        $this->faker = Faker\Factory::create('pt_BR');
        $this->fakerCustom = new CustomProvider($this->faker);
        $this->matrix = [];
    }

    public function builderAddMatrix()
    {
        $this->matrix['stages'] = $this->faker->randomElement(
            array(
                1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20
            )
        );
        $this->matrix['disciplines'] = $this->faker->randomElement(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10));
        $this->matrix['workload'] = $this->faker->randomDigit();
        $this->matrix['credits'] = $this->faker->randomDigit();

        return $this->matrix;
    }
}
