<?php
require_once 'vendor/autoload.php';
require_once 'app/vendor/autoload.php';
require_once __DIR__.'/../providers/CustomProvider.php';

$yiit= __DIR__.'\..\..\app\vendor\yiisoft\yii\framework\yiit.php';
require_once($yiit);

$config= __DIR__.'/../../app/config/test.php';

Yii::createWebApplication($config);

/**
 * @property [] $classroom
 * @property Faker\Generator $faker
 * @property CustomProvider $fakerCustom
*/
class ClassroomBuilder
{
    private $faker = null;
    private $fakerCustom = null;

     /**
     * Summary of group registration
     * @var $classroom
     */
    public function __construct ()
    {
        $this->faker = Faker\Factory::create('pt_BR');
        $this->fakerCustom = new CustomProvider($this->faker);
        $this->classroom = [];
    }

    public function buildCompleted ()
    {
        $this->classroom['name'] = $this->fakerCustom->generateRandomClassName();
        $this->classroom['edcenso_stage_vs_modality_fk'] = '1';
        $this->classroom['pedagogical_mediation_type_IN_PERSON'] = '1';
        $this->classroom['pedagogical_mediation_type_EAD'] = '3';
        $this->classroom['modality'] = $this->faker->randomElement(array (1,2,3,4));
        $this->classroom['diff_location'] = $this->faker->randomElement(array (0,1,2,3));
        $this->classroom['edcenso_professional_education_course_fk'] = '8120'; // Ações de Comandos
        $this->classroom['initial_time'] = $this->fakerCustom->generateRandomTime();
        $this->classroom['final_time'] = $this->fakerCustom->generateRandomEndTime($this->classroom['initial_time']);
        $this->classroom['turn'] = $this->faker->randomElement(array ('M','T','N','I'));
        $this->classroom['complementary_activity_type_1'] = '10102'; // Robótica Educacional
        $this->classroom['aee_braille'] = '#Ensino\ do\ Sistema\ Braille';

        return $this->classroom;
    }

}
