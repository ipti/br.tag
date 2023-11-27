<?php

/**
 * @property [] $instructor
 * @property [] $instructorTeaching
 * @property [] $instructorVariable
 * @property [] $instructorDocumentsAddress
 * @property Faker\Generator $faker
 * @property CustomProvider $fakerCustom
 */
class InstructorBuilder
{

    private $faker = null;
    private $fakerCustom = null;


    /**
     * Summary of instructor
     * @var $instructor
     * @var $instructorVariable
     * @var $instructorDocumentsAddress
     */

    public function __construct()
    {
        $this->faker = Faker\Factory::create('pt_BR');
        $this->fakerCustom = new CustomProvider($this->faker);
        $this->instructor = [];
        $this->instructorVariable = [];
        $this->instructorDocumentsAddress = [];
    }

    public function buildComplete()
    {
        $this->instructor['name'] = $this->faker->name();
        $this->instructor['email'] = $this->faker->email();
        $this->instructor['nationality'] = '1';
        $this->instructorDocumentsAddress['cpf'] = $this->faker->cpf();
        $this->instructor['edcenso_uf_fk'] = '28';
        $this->instructor['edcenso_city_fk'] = '2800308';
        $this->instructor['nis'] = $this->fakerCustom->nisNumber();
        $this->instructor['birthday_date'] = $this->faker->date('d/m/Y');
        $this->instructor['sex'] = $this->faker->randomElement(array(1, 2));
        $this->instructor['color_race'] = $this->faker->randomElement(array(0, 1, 2, 3, 4, 5));
        $this->instructor['filiation'] = '1';
        $this->instructor['filiation_1'] = $this->faker->name();
        $this->instructorDocumentsAddress['address'] = "RUA " . $this->faker->name();
        $this->instructorDocumentsAddress['address_number'] = $this->faker->buildingNumber();
        $this->instructorDocumentsAddress['neighborhood'] = "Centro";
        $this->instructorDocumentsAddress['diff_location'] = "7";
        $this->instructorDocumentsAddress['area_of_residence'] = "1";

        return $this;
    }

    public function scholarityDegree()
    {
        $this->instructorVariable['scholarity'] = '6';
        $this->instructorVariable['high_education_situation_1'] = '1';
        $this->instructorVariable['high_education_course_code_1_fk'] = '142P01';
        $this->instructorVariable['high_education_final_year_1'] = $this->faker->numberBetween(2000, 2023);

        return $this;
    }
}
