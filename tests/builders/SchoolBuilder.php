<?php

/**
 * @property [] $school
 * @property Faker\Generator $faker
 * @property CustomProvider $fakerCustom
 */
class SchoolBuilder
{
    private $faker = null;
    private $fakerCustom = null;

    /**
     * Summary of school
     * @var $school
     */
    public function __construct()
    {
        $this->faker = Faker\Factory::create('pt_BR');
        $this->fakerCustom = new CustomProvider($this->faker);
        $this->school = [];
    }

    public function buildCompleted()
    {
        $this->school['name'] = $this->fakerCustom->nameSchool();
        $this->school['inep_id'] = $this->fakerCustom->inepId();
        $this->school['administrative_dependence'] = $this->faker->randomElement(array(1, 2, 3, 4));
        $this->school['situation'] = $this->faker->randomElement(array(1, 2, 3));
        $this->school['regulation'] = $this->faker->randomElement(array(0, 1, 2));
        $this->school['ies_code'] = $this->fakerCustom->codIES();
        $this->school['inep_head_school'] = $this->fakerCustom->codSchool();
        $this->school['edcenso_uf_fk'] = '28'; // Sergipe
        $this->school['edcenso_city_fk'] = '2800308'; // Aracaju
        $this->school['location'] = $this->faker->randomElement(array(1, 2));
        $this->school['edcenso_district_fk'] = '5'; // Aracaju
        $this->school['id_difflocation'] = $this->faker->randomElement(array(1, 2, 3, 7));
        $this->school['linked_unity'] = $this->faker->randomElement(array(1, 2));
        $this->school['no_linked_unity'] = '0';
        $this->school['classroom_count'] = $this->faker->randomDigit();
        $this->school['building_occupation_situation'] = $this->faker->randomElement(array(1, 2));
        $this->school['feeding'] = $this->faker->randomElement(array(0, 1));
        $this->school['name_manager'] = $this->faker->name();
        $this->school['birthday_date_manager'] = $this->faker->date('d/m/Y');
        $this->school['color_race'] = $this->faker->randomElement(array(0, 1, 2, 3, 4, 5));
        $this->school['sex'] = $this->faker->randomElement(array(1, 2));
        $this->school['nationality'] = '1'; // Brasileira
        $this->school['filiation_no_declared'] = '0';
        $this->school['filiation_declared'] = '1';
        $this->school['residence_zone'] = $this->faker->randomElement(array(1, 2));

        return $this->school;
    }

}
