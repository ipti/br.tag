<?php

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
    public function __construct()
    {
        $this->faker = Faker\Factory::create('pt_BR');
        $this->fakerCustom = new CustomProvider($this->faker);
        $this->classroom = [];
    }

    public function buildCompleted()
    {
        $this->classroom['name'] = $this->fakerCustom->generateRandomClassName();
        $this->classroom['edcenso_stage_vs_modality_fk'] = '1'; // Educação Infantil - Creche (0 a 3 anos)
        $this->classroom['pedagogical_mediation_type_IN_PERSON'] = '1';
        $this->classroom['pedagogical_mediation_type_EAD'] = '3';
        $this->classroom['modality'] = $this->faker->randomElement(array(1, 2, 3, 4));
        $this->classroom['diff_location'] = $this->faker->randomElement(array(0, 1, 2, 3));
        $this->classroom['edcenso_professional_education_course_fk'] = '8120'; // Ações de Comandos
        $this->classroom['initial_time'] = $this->fakerCustom->generateRandomTime();
        $this->classroom['final_time'] = $this->fakerCustom->generateRandomEndTime($this->classroom['initial_time']);
        $this->classroom['turn'] = $this->faker->randomElement(array('M', 'T', 'N', 'I'));
        $this->classroom['week_days_monday'] = '#Classroom_week_days_monday'; // Segunda
        $this->classroom['week_days_tuesday'] = '#Classroom_week_days_tuesday'; // Terça
        $this->classroom['assistance_type_schooling'] = '#Classroom_schooling';
        $this->classroom['assistance_complementary_activity'] = '#Classroom_complementary_activity';
        $this->classroom['complementary_activity_type_1'] = '10102'; // Robótica Educacional
        $this->classroom['aee_braille'] = '#Ensino\ do\ Sistema\ Braille';
        $this->classroom['aee_optical_and_non'] = '#Ensino\ do\ uso\ de\ recursos\ ópticos\ e\ não\ ópticos';
        $this->classroom['Instructors'] = $this->faker->randomElement(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10));
        $this->classroom['Role'] = $this->faker->randomElement(array(1, 2, 3, 4, 5, 6, 7, 8));
        $this->classroom['ContractType'] = $this->faker->randomElement(array(1, 2, 3, 4));

        //sedsp

        $this->classroom['sedsp_school_unity_fk'] = '';
        $this->classroom['sedsp_acronym'] = $this->fakerCustom->identificationClass();
        $this->classroom['sedsp_classnumber'] = $this->faker->randomDigit();
        $this->classroom['sedsp_max_physical_capacity'] = $this->faker->randomDigit();

        return $this->classroom;
    }
}
