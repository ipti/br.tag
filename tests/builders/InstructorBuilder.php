<?php

/**
* @property InstructorIdentification $instructor
* @property InstructorTeachingData $instructorTeaching
* @property InstructorVariableData $instructorVariable
* @property InstructorDocumentsAndAddress $instructorDocumentsAddress
* @property Faker\Generator $faker
* @property CustomProvider $fakerCustom
*/


require_once 'vendor/autoload.php';
require_once 'app/vendor/autoload.php';
require_once __DIR__.'/../providers/CustomProvider.php';

$yiit= __DIR__.'\..\..\app\vendor\yiisoft\yii\framework\yiit.php';
require_once($yiit);
$config= __DIR__.'/../../app/config/test.php';
Yii::createWebApplication($config);

// require_once dirname('../app/models/InstructorIdentification.php');
// require_once dirname('../app/models/InstructorDocumentsAndAddress.php');
// require_once dirname('../app/models/InstructorTeachingData.php');
// require_once dirname('../app/models/InstructorVariableData.php');


class InstructorBuilder {

    private $faker = null;
    private $fakerCustom = null;
    private $instructor = null;
    private $instructorTeaching = null;
    private $instructorVariable = null;

    private $instructorDocumentsAddress = null;

    /**
     * Summary of instructor
     * @var InstructorIdentification $instructor
     * @var InstructorTeachingData $instructorTeaching
     * @var InstructorVariableData $instructorVariable
     * @var InstructorDocumentsAndAddress $instructorDocumentsAddress
    */

    public function __construct() {
        $this->faker = Faker\Factory::create('pt_BR');
        $this->fakerCustom = new CustomProvider($this->faker);
        $this->instructor = new InstructorIdentification();
        $this->instructorTeaching = new InstructorTeachingData();
        $this->instructorVariable = new InstructorVariableData();
        $this->instructorDocumentsAddress = new InstructorDocumentsAndAddress();
    }

    public function build(): void
    {
        $this->instructor->name = $this->faker->name();
        $this->instructor->email = $this->faker->email();
        $this->instructor->nationality = '1';
        $this->instructorDocumentsAddress->cpf = $this->faker->cpf();
        $this->instructorDocumentsAddress->edcenso_uf_fk = '28';
        $this->instructorDocumentsAddress->edcenso_city_fk = '10';
        $this->instructor->nis = $this->fakerCustom->nisNumber();
        $this->instructor->birthday_date = $this->faker->date('d/m/Y');
        $this->instructor->sex = $this->faker->numberBetween(1,2);
        $this->instructor->color_race = $this->faker->numberBetween(0,5);
        $this->instructor->filiation = '0';
        $this->instructorDocumentsAddress->cep = '49000194';
        $this->instructorDocumentsAddress->address = $this->faker->address();
        $this->instructorDocumentsAddress->address_number = $this->faker->buildingNumber();
        $this->instructorDocumentsAddress->neighborhood = "Centro";
        $this->instructorDocumentsAddress->diff_location = "7";
        $this->instructorDocumentsAddress->area_of_residence = "1";
        $this->instructorVariable->scholarity = "6";
    }

}
?>
