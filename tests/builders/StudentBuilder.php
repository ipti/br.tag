<?php

/**
 * @property [] $student
 * @property [] $studentDocument
 * @property [] $studentEnrollment
 * @property Faker\Generator $faker
 * @property CustomProvider $fakerCustom
 */
class StudentBuilder
{

    private $faker = null;
    private $fakerCustom = null;

    /**
     * Summary of student
     * @var $student
     * @var $studentDocument
     * @var $studentEnrollment
     */
    public function __construct()
    {
        $this->faker = Faker\Factory::create('pt_BR');
        $this->fakerCustom = new CustomProvider($this->faker);
        $this->student = [];
        $this->studentDocument = [];
        $this->studentEnrollment = [];
    }

    public function buildCompleted()
    {
        $this->student['name'] = $this->faker->name();
        $this->student['civil_name'] = $this->faker->name();
        $this->student['birthday'] = $this->faker->date('d/m/Y');
        $this->studentDocument['cpf'] = $this->faker->cpf();
        $this->student['sex'] = $this->faker->randomElement(array(1, 2));
        $this->student['color_race'] = $this->faker->randomElement(array(0, 1, 2, 3, 4, 5));
        $this->student['nationality'] = '1'; // Brasileira
        $this->student['state'] = '28'; // Sergipe
        $this->student['city'] = '2800308'; // Aracaju
        $this->student['id_email'] = $this->faker->email();
        $this->student['scholarity'] = $this->faker->randomElement(array(1, 2, 3, 4));
        $this->student['filiation_no_declared'] = '0'; // Não declarado/Ignorado
        $this->student['filiation_with_and_father'] = '1'; // Pai e/ou mãe
        $this->student['responsable'] = $this->faker->randomElement(array(0, 1, 2));
        $this->student['responsable_telephone'] = $this->faker->cellphoneNumber();
        $this->student['responsable_name'] = $this->faker->name();
        $this->student['responsable_email'] = $this->faker->email();
        $this->student['responsable_job'] = $this->faker->jobTitle();
        $this->student['responsable_scholarity'] = $this->faker->randomElement(array(1, 2, 3, 4));
        $this->student['responsable_rg'] = $this->faker->rg();
        $this->student['responsable_cpf'] = $this->faker->cpf();
        $this->student['filiation_1'] = $this->fakerCustom->filiationName();
        $this->student['filiation_1_cpf'] = $this->faker->cpf();
        $this->student['filiation_1_birthday'] = $this->faker->date('d/m/Y');
        $this->student['filiation_1_rg'] = $this->faker->rg();
        $this->student['filiation_1_scholarity'] = $this->faker->randomElement(array(0, 1, 2, 3, 4, 5, 6, 7));
        $this->student['filiation_1_job'] = $this->faker->jobTitle();
        $this->student['filiation_2'] = $this->fakerCustom->filiationName();
        $this->student['filiation_2_cpf'] = $this->faker->cpf();
        $this->student['filiation_2_birthday'] = $this->faker->date('d/m/Y');
        $this->student['filiation_2_rg'] = $this->faker->rg();
        $this->student['filiation_2_scholarity'] = $this->faker->randomElement(array(0, 1, 2, 3, 4, 5, 6, 7));
        $this->student['filiation_2_job'] = $this->faker->jobTitle();
        $this->studentDocument['civil_certification_type_old'] = '1';
        $this->studentDocument['civil_certification_type_new'] = '2';
        $this->studentDocument['civil_certification_type'] = $this->faker->randomElement(array(1, 2));
        $this->studentDocument['civil_certification_term_number'] = $this->fakerCustom->termCivil();
        $this->studentDocument['civil_certification_sheet'] = $this->fakerCustom->sheetCivil();
        $this->studentDocument['civil_certification_book'] = $this->fakerCustom->bookCivil();
        $this->studentDocument['civil_certification_date'] = $this->faker->date('d/m/Y');
        $this->studentDocument['notary_office_uf_fk']  = '28'; // Sergipe
        $this->studentDocument['notary_office_city_fk'] = '2800308'; // Aracaju
        $this->studentDocument['edcenso_notary_office_fk'] = '5573'; // 13º Oficio
        $this->studentDocument['civil_certification_term_number'] = $this->fakerCustom->matriculaRegistroCivil();
        $this->studentDocument['cns'] = $this->fakerCustom->cnsNumber();
        $this->studentDocument['rg_number'] = $this->faker->rg();
        $this->studentDocument['rg_number_edcenso_organ_id_emitter_fk'] = '10'; // SSP
        $this->studentDocument['rg_number_expediction_date'] = $this->faker->date('d/m/Y');
        $this->studentDocument['rg_number_edcenso_uf_fk'] = '28'; // Sergipe
        $this->studentDocument['justice_restriction'] = $this->faker->randomElement(array(0, 1, 2));
        $this->studentDocument['justification'] = $this->faker->randomElement(array(1, 2));
        $this->studentDocument['nis'] = $this->fakerCustom->nisNumber();
        $this->student['inep_id'] = $this->fakerCustom->inepId();
        $this->studentDocument['edcenso_uf_fk'] = '28'; // Sergipe
        $this->studentDocument['cep'] = $this->faker->postcode();
        $this->studentDocument['edcenso_city_fk'] = '2800308'; // Aracaju
        $this->studentDocument['address'] = $this->faker->streetName();
        $this->studentDocument['neighborhood'] = $this->faker->region();
        $this->studentDocument['number'] = $this->faker->buildingNumber();
        $this->studentDocument['complement'] = $this->fakerCustom->complementLocation();
        $this->studentDocument['diff_location'] = $this->faker->randomElement(array(1, 2, 3, 7));
        $this->studentDocument['residence_zone'] = $this->faker->randomElement(array(1, 2));
        $this->studentEnrollment['classroom_fk'] = $this->faker->randomElement(array(7, 3, 2, 1));
        $this->studentEnrollment['admission_type'] = $this->faker->randomElement(array(1, 2, 3));
        $this->studentEnrollment['school_admission_date'] = $this->faker->date('d/m/Y');
        $this->studentEnrollment['current_stage_situation'] = $this->faker->randomElement(array(0, 1, 2));
        $this->studentEnrollment['status'] = $this->faker->randomElement(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11));
        $this->studentEnrollment['previous_stage_situation'] = $this->faker->randomElement(array(0, 1, 2, 3, 4, 5));
        $this->studentEnrollment['unified_class'] = $this->faker->randomElement(array(1, 2));
        $this->studentEnrollment['another_scholarization_place'] = $this->faker->randomElement(array(1, 2, 3));
        $this->studentEnrollment['stage'] = $this->faker->randomElement(array(0, 1, 2, 3, 4, 5, 6, 7));
        $this->studentEnrollment['edcenso_stage_vs_modality_fk'] = '1';
        $this->studentEnrollment['transport_responsable_government'] = $this->faker->randomElement(array(1, 2));

        return $this;
    }
}
