<?php
require_once 'vendor/autoload.php';
require_once 'app/vendor/autoload.php';
require_once __DIR__.'/../providers/CustomProvider.php';

$yiit= __DIR__.'\..\..\app\vendor\yiisoft\yii\framework\yiit.php';
require_once($yiit);

$config= __DIR__.'/../../app/config/test.php';

Yii::createWebApplication($config);

/**
 * @property [] $wizard
 * @property Faker\Generator $faker
 * @property CustomProvider $fakerCustom
*/
class WizardBuilder {

    private $faker = null;
    private $fakerCustom = null;

     /**
     * Summary of group registration
     * @var $wizard
     */
    public function __construct ()
    {
        $this->faker = Faker\Factory::create('pt_BR');
        $this->fakerCustom = new CustomProvider($this->faker);
        $this->wizard = [];
    }

    public function buildCompleted ()
    {
        $this->wizard['classrooms'] = '483'; // Turma 1
        $this->wizard['oneClassrom'] = '494'; // 261605877 1 ETAPA PREESCOLA D TARDE ANUAL

        return $this->wizard;
    }

}
