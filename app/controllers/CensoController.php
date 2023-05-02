<?php

//@done S2 - Modularizar o código do import
//@done S2 - Criar o controller de Import
//@done S2 - Mover o código do import de SchoolController.php para AdminController.php
//@done S2 - Mover o código do configACL de SchoolController.php para AdminController.php
//@done S2 - Criar método de limparBanco
//@done S2 - Criar tela de index do AdminController.php
//@done S2 - Criar usuários padrões.
//@done S2 - Mensagens de retorno ao executar os scripts.


class CensoController extends Controller
{

    public $layout = 'fullmenu';

    public $export = array();
    public $tmpexp = array();

    public function accessRules()
    {
        return [
            [
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['CreateUser', 'index', 'conflicts'], 'users' => ['*'],
            ], [
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => [
                    'import', 'export', 'clearDB', 'acl', 'backup', 'data', 'exportStudentIdentify', 'syncExport',
                    'syncImport', 'exportToMaster', 'clearMaster', 'importFromMaster', 'downloadexportfile', 'ExportWithoutInepid'
                ], 'users' => ['@'],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->render('index');
    }

    public function areThereByModalitie($sql)
    {
        $people_by_modalitie = Yii::app()->db->createCommand($sql)->queryAll();
        $modalities_regular = false;
        $modalities_especial = false;
        $modalities_eja = false;
        $modalities_professional = false;
        foreach ($people_by_modalitie as $key => $item) {
            switch ($item['modalities']) {

                case '1':
                    if ($item['number_of'] > '0')
                        $modalities_regular = true;
                    break;
                case '2':
                    if ($item['number_of'] > '0')
                        $modalities_especial = true;
                    break;

                case '3':
                    if ($item['number_of'] > '0')
                        $modalities_eja = true;
                    break;

                case '4':
                    if ($item['number_of'] > '0')
                        $modalities_professional = true;
                    break;
            }
        }
        return array("modalities_regular" => $modalities_regular,
            "modalities_especial" => $modalities_especial,
            "modalities_eja" => $modalities_eja,
            "modalities_professional" => $modalities_professional);
    }

    public function validateSchool($collumn)
    {
        $log = array();
        $siv = new SchoolIdentificationValidation();

        //ok
        $result = $siv->isRegister("00", $collumn['register_type']);
        if (!$result["status"]) array_push($log, array("register_type" => $result["erro"]));

        //ok
        $result = $siv->isInepIdValid($collumn['inep_id']);
        if (!$result["status"]) array_push($log, array("inep_id" => $result["erro"]));

        //campo 3 - ok
        $result = $siv->isManagerCPFValid($collumn['manager_cpf']);
        if (!$result["status"]) array_push($log, array("manager_cpf" => $result["erro"]));

        //campo 4 - ok
        $result = $siv->isManagerNameValid($collumn['manager_name']);
        if (!$result["status"]) array_push($log, array("manager_name" => $result["erro"]));

        //campo 5 -ok
        $result = $siv->isManagerRoleValid(intval($collumn['manager_role']));
        if (!$result["status"]) array_push($log, array("manager_role" => $result["erro"]));

        //campo 6 - ok
        $result = $siv->isManagerEmailValid($collumn['manager_email']);
        if (!$result["status"]) array_push($log, array("manager_email" => $result["erro"]));

        //campo 7 - ok
        $result = $siv->isSituationValid($collumn['situation']);
        if (!$result["status"]) array_push($log, array("situation" => $result["erro"]));

        //campo 8 e 9
        /*@todo
        1. Deve ser preenchido quando o campo 7 (Situação de funcionamento) for igual a 1 (em atividade).
        2. Deve ser nulo quando o campo 7 (Situação de funcionamento) for diferente de 1 (em atividade).

        */
        $result = $siv->isSchoolYearValid($collumn['initial_date'], $collumn['final_date']);
        if (!$result["status"]) array_push($log, array("date" => $result["erro"]));

        //campo 10
        $result = $siv->isSchoolNameValid($collumn['name']);
        if (!$result["status"]) array_push($log, array("name" => $result["erro"]));

        //campo 11
        $result = $siv->isLatitudeValid($collumn['latitude']);
        if (!$result["status"]) array_push($log, array("latitude" => $result["erro"]));

        //campo 12
        $result = $siv->isLongitudeValid($collumn['longitude']);
        if (!$result["status"]) array_push($log, array("longitude" => $result["erro"]));

        //campo 13
        $result = $siv->isCEPValid($collumn['cep']);
        if (!$result["status"]) array_push($log, array("cep" => $result["erro"]));


        $result = $siv->isAddressValid($collumn['address'], 100, false);
        if (!$result["status"]) array_push($log, array("address" => $result["erro"]));

        //campo 15
        if ($collumn['address_number'] !== "" && $collumn["address_number"] !== null) {
            $result = $siv->isAddressValid($collumn['address_number'], 10, true);
            if (!$result["status"]) array_push($log, array("address_number" => $result["erro"]));
        }

        //campo 16
        if ($collumn['address_complement'] !== "" && $collumn["address_complement"] !== null) {
            $result = $siv->isAddressValid($collumn['address_complement'], 20, true);
            if (!$result["status"]) array_push($log, array("address_complement" => $result["erro"]));;
        }

        //campo 17
        if ($collumn['address_neighborhood'] !== "" && $collumn["address_neighborhood"] !== null) {
            $result = $siv->isAddressValid($collumn['address_neighborhood'], 50, true);
            if (!$result["status"]) array_push($log, array("address_neighborhood" => $result["erro"]));
        }

        //campo 18
        /* @todo
         * 1. Deve ser preenchido.
         * 2. Deve ser preenchido com o código do estado de acordo com a “Tabela de UF”.
         * 2. O valor preenchido deve ser o mesmo que está na base do Educacenso.
         * $result = $siv->isAddressValid($collumn['edcenso_uf_fk'], $might_not_be_null, 100);
         * if(!$result["status"]) array_push($log, array("edcenso_uf_fk"=>$result["erro"]));
         *
         * //campo 19
         * $result = $siv->isAddressValid($collumn['edcenso_city_fk'], $might_not_be_null, 100);
         * if(!$result["status"]) array_push($log, array("edcenso_city_fk"=>$result["erro"]));
         *
         * //campo 20
         * $result = $siv->isAddressValid($collumn['edcenso_district_fk'], $might_not_be_null, 100);
         * if(!$result["status"]) array_push($log, array("edcenso_district_fk"=>$result["erro"]));
         *
         * */
        //campo 21-25

        $result = $siv->checkPhoneNumbers($collumn['ddd'], $collumn['phone_number'], $collumn['other_phone_number']);
        if (!$result["status"]) array_push($log, array("DDD e Telefones" => $result["erro"]));

        //campo 26
        $result = $siv->isEmailValid($collumn['email']);
        if (!$result["status"]) array_push($log, array("email" => $result["erro"]));

        //campo 27


        //campo 28
        $result = $siv->isAdministrativeDependenceValid($collumn['administrative_dependence'], $collumn['edcenso_uf_fk']);
        if (!$result["status"]) array_push($log, array("administrative_dependence" => $result["erro"]));

        //campo 29
        $result = $siv->isLocationValid($collumn['location']);
        if (!$result["status"]) array_push($log, array("location" => $result["erro"]));

        //campo 30
        $result = $siv->checkPrivateSchoolCategory($collumn['private_school_category'],
            $collumn['situation'],
            $collumn['administrative_dependence']);
        if (!$result["status"]) array_push($log, array("private_school_category" => $result["erro"]));

        //campo 31
        $result = $siv->isPublicContractValid($collumn['public_contract'],
            $collumn['situation'],
            $collumn['administrative_dependence']);
        if (!$result["status"]) array_push($log, array("public_contract" => $result["erro"]));

        //campo 32 - 36
        $keepers = array($collumn['private_school_business_or_individual'],
            $collumn['private_school_syndicate_or_association'],
            $collumn['private_school_ong_or_oscip'],
            $collumn['private_school_non_profit_institutions'],
            $collumn['private_school_s_system']);

        /*
        $result = $siv->checkPrivateSchoolCategory($keepers,
            $collumn['situation'],
            $collumn['administrative_dependence']);
        if(!$result["status"]) array_push($log, array("keepers"=>$result["erro"]));
        */

        //campo 37
        $result = $siv->isCNPJValid($collumn['private_school_maintainer_cnpj'],
            $collumn['situation'],
            $collumn['administrative_dependence']);
        if (!$result["status"]) array_push($log, array("private_school_maintainer_cnpj" => $result["erro"]));

        //campo 38
        $result = $siv->isCNPJValid($collumn['private_school_cnpj'],
            $collumn['situation'],
            $collumn['administrative_dependence']);
        if (!$result["status"]) array_push($log, array("private_school_cnpj" => $result["erro"]));

        //campo 39
        $result = $siv->isRegulationValid($collumn['regulation'],
            $collumn['situation']);
        if (!$result["status"]) array_push($log, array("regulation" => $result["erro"]));

        //campo 40
        $result = $siv->isRegulationValid($collumn['offer_or_linked_unity'],
            $collumn['situation']);
        if (!$result["status"]) array_push($log, array("offer_or_linked_unity" => $result["erro"]));

        //campo 41
        $inep_head_school = $collumn['inep_head_school'];
        $sql = "SELECT 	si.inep_head_school, si.situation
			FROM 	school_identification AS si
			WHERE 	inep_id = '$inep_head_school';";
        $check = Yii::app()->db->createCommand($sql)->queryAll();
        if (!empty($check)) {
            $result = $siv->inepHeadSchool($collumn['inep_head_school'], $collumn['offer_or_linked_unity'],
                $collumn['inep_id'], $check[0]['situation'],
                $check[0]['inep_head_school']);
            if (!$result["status"]) array_push($log, array("inep_head_school" => $result["erro"]));
        }
        //campo 42
        $result = $siv->iesCode($collumn['ies_code'], $collumn['administrative_dependence'], $collumn['offer_or_linked_unity']);
        if (!$result["status"]) array_push($log, array("ies_code" => $result["erro"]));

        $result = $siv->isNotNullValid($collumn["id_difflocation"]);
        if (!$result["status"]) array_push($log, array("id_difflocation" => $result["erro"]));

        $result = $siv->isValidLinkedOrgan($collumn["administrative_dependence"], $collumn["linked_mec"], $collumn["linked_army"], $collumn["linked_helth"], $collumn["linked_other"]);
        if (!$result["status"]) array_push($log, array("Orgao ao qual a escola publica esta vinculada" => $result["erro"]));

        $result = $siv->regulationOrganSphere($collumn["administrative_dependence"], $collumn["regulation_organ_federal"], $collumn["regulation_organ_state"], $collumn["regulation_organ_municipal"]);
        if (!$result["status"]) array_push($log, array("Esfera do Orgao Regulador" => $result["erro"]));

        return $log;
    }

    public function validateSchoolStructure($collumn, $school)
    {
        $ssv = new SchoolStructureValidation();
        $school_inep_id_fk = $collumn["school_inep_id_fk"];
        $log = array();

        //campo 1
        $result = $ssv->isRegister("10", $collumn['register_type']);
        if (!$result["status"]) array_push($log, array("register_type" => $result["erro"]));

        //campo 2
        $sql = "SELECT inep_id FROM school_identification;";
        $inep_ids = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($inep_ids as $key => $value) {
            $allowed_school_inep_ids[] = $value['inep_id'];
        }

        $result = $ssv->isAllowed($school_inep_id_fk,
            $allowed_school_inep_ids);
        if (!$result["status"]) array_push($log, array("school_inep_id_fk" => $result["erro"]));

        //campo 3 à 11
        $operation_locations = array($collumn["operation_location_building"],
            $collumn["operation_location_temple"],
            $collumn["operation_location_businness_room"],
            $collumn["operation_location_instructor_house"],
            $collumn["operation_location_other_school_room"],
            $collumn["operation_location_barracks"],
            $collumn["operation_location_socioeducative_unity"],
            $collumn["operation_location_prison_unity"],
            $collumn["operation_location_other"]);
        $result = $ssv->atLeastOne($operation_locations);
        if (!$result["status"]) array_push($log, array("operation_locations" => $result["erro"]));

        //campo 12
        $result = $ssv->buildingOccupationStatus($collumn["operation_location_building"],
            $collumn["operation_location_barracks"],
            $collumn["building_occupation_situation"]);
        if (!$result["status"]) array_push($log, array("building_occupation_situation" => $result["erro"]));

        //campo 13
        $result = $ssv->sharedBuildingSchool($collumn["operation_location_building"],
            $collumn["shared_building_with_school"]);
        if (!$result["status"]) array_push($log, array("shared_building_with_school" => $result["erro"]));

        //campos 14 à 19
        $shared_school_inep_ids = array($collumn["shared_school_inep_id_1"],
            $collumn["shared_school_inep_id_2"],
            $collumn["shared_school_inep_id_3"],
            $collumn["shared_school_inep_id_4"],
            $collumn["shared_school_inep_id_5"],
            $collumn["shared_school_inep_id_6"]);
        $result = $ssv->sharedSchoolInep($collumn["shared_building_with_school"], $collumn["school_inep_id_fk"], $shared_school_inep_ids);
        if (!$result["status"]) array_push($log, array("Escolas com qual e compartilhada" => $result["erro"]));

        //campo 20
        $result = $ssv->oneOfTheValues($collumn["consumed_water_type"]);
        if (!$result["status"]) array_push($log, array("consumed_water_type" => $result["erro"]));

        //campos 21 à 25
        $water_supplys = array($collumn["water_supply_public"],
            $collumn["water_supply_artesian_well"],
            $collumn["water_supply_well"],
            $collumn["water_supply_river"],
            $collumn["water_supply_inexistent"]);
        $result = $ssv->supply($water_supplys);
        if (!$result["status"]) array_push($log, array("Suprimento de Agua" => $result["erro"]));

        //campos 26 à 29
        $energy_supplys = array($collumn["energy_supply_public"],
            $collumn["energy_supply_generator"],
            $collumn["energy_supply_generator_alternative"],
            $collumn["energy_supply_inexistent"]);
        $result = $ssv->supply($energy_supplys);
        if (!$result["status"]) array_push($log, array("Suprimento de Energia" => $result["erro"]));

        //campos 30 à 32
        $sewages = array($collumn["sewage_public"],
            $collumn["sewage_fossa"],
            $collumn["sewage_fossa_common"],
            $collumn["sewage_inexistent"]);
        $result = $ssv->supply($sewages);
        if (!$result["status"]) array_push($log, array("Esgoto" => $result["erro"]));

        //campos 33 à 38
        $garbage_destinations = array($collumn["garbage_destination_collect"],
            $collumn["garbage_destination_burn"],
            $collumn["garbage_destination_public"],
            $collumn["garbage_destination_throw_away"],
            $collumn["garbage_destination_bury"]);
        $result = $ssv->atLeastOne($garbage_destinations);
        if (!$result["status"]) array_push($log, array("Destino do lixo" => $result["erro"]));

        $garbageTreatment = array($collumn["treatment_garbage_parting_garbage"],
            $collumn["treatment_garbage_resuse"],
            $collumn["garbage_destination_recycle"],
            $collumn["traetment_garbage_inexistent"]);
        $result = $ssv->atLeastOne($garbageTreatment);
        if (!$result["status"]) array_push($log, array("Tratamento do lixo" => $result["erro"]));

        //campos 39 à 68
        $dependencies = array($collumn["dependencies_principal_room"],
            $collumn["dependencies_instructors_room"],
            $collumn["dependencies_secretary_room"],
            $collumn["dependencies_info_lab"],
            $collumn["dependencies_science_lab"],
            $collumn["dependencies_aee_room"],
            $collumn["dependencies_bathroom_workes"],
            $collumn["dependencies_pool"],
            $collumn["dependencies_indoor_sports_court"],
            $collumn["dependencies_outdoor_sports_court"],
            $collumn["dependencies_kitchen"],
            $collumn["dependencies_library"],
            $collumn["dependencies_reading_room"],
            $collumn["dependencies_playground"],
            $collumn["dependencies_child_bathroom"],
            $collumn["dependencies_prysical_disability_bathroom"],
            $collumn["dependencies_bathroom_with_shower"],
            $collumn["dependencies_refectory"],
            $collumn["dependencies_storeroom"],
            $collumn["dependencies_warehouse"],
            $collumn["dependencies_auditorium"],
            $collumn["dependencies_covered_patio"],
            $collumn["dependencies_uncovered_patio"],
            $collumn["dependencies_student_accomodation"],
            $collumn["dependencies_student_repose_room"],
            $collumn["dependencies_instructor_accomodation"],
            $collumn["dependencies_green_area"],
            $collumn["dependencies_arts_room"],
            $collumn["dependencies_music_room"],
            $collumn["dependencies_dance_room"],
            $collumn["dependencies_multiuse_room"],
            $collumn["dependencies_yardzao"],
            $collumn["dependencies_vivarium"],
            $collumn["dependencies_vocational_education_workshop"],
            $collumn["dependencies_none"]);
        $result = $ssv->supply($dependencies);
        if (!$result["status"]) array_push($log, array("Dependencias" => $result["erro"]));

        $acessibility = array($collumn["acessability_handrails_guardrails"],
            $collumn["acessability_elevator"],
            $collumn["acessability_tactile_floor"],
            $collumn["acessability_doors_80cm"],
            $collumn["acessability_ramps"],
            $collumn["acessability_sound_signaling"],
            $collumn["acessability_tactile_singnaling"],
            $collumn["acessability_visual_signaling"],
            $collumn["acessabilty_inexistent"]);
        $result = $ssv->atLeastOne($acessibility);
        if (!$result["status"]) array_push($log, array("Acessibilidade" => $result["erro"]));

        //campo 69
        $result = $ssv->schoolsCount($collumn["operation_location_building"],
            $collumn["classroom_count"]);
        if (!$result["status"]) array_push($log, array("classroom_count" => $result["erro"]));

        //campo 70
        $result = $ssv->isGreaterThan($collumn["used_classroom_count"], "0");
        if (!$result["status"]) array_push($log, array("used_classroom_count" => $result["erro"]));

        //campo 71 à 83
        $result = $ssv->isGreaterThan($collumn["used_classroom_count"], "0");
        if (!$result["status"]) array_push($log, array("used_classroom_count" => $result["erro"]));

        //campo 86
        $internetAccess = array($collumn["internet_access_administrative"],
            $collumn["internet_access_educative_process"],
            $collumn["internet_access_student"],
            $collumn["internet_access_community"],
            $collumn["internet_access_inexistent"]);
        $result = $ssv->atLeastOne($internetAccess);
        if (!$result["status"]) array_push($log, array("Acesso à Internet" => $result["erro"]));

        $instruments = array($collumn["equipments_multimedia_collection"],
            $collumn["equipments_toys_early"],
            $collumn["equipments_scientific_materials"],
            $collumn["equipments_equipment_amplification"],
            $collumn["equipments_musical_instruments"],
            $collumn["equipments_educational_games"],
            $collumn["equipments_material_cultural"],
            $collumn["equipments_material_sports"],
            $collumn["equipments_material_teachingindian"],
            $collumn["equipments_material_teachingethnic"],
            $collumn["equipments_material_teachingrural"],
            $collumn["instruments_inexistent"]);
        $result = $ssv->atLeastOne($instruments);
        if (!$result["status"]) array_push($log, array("Instrumentos, materiais socioculturais e/ou pedagogicos em uso na escola para o desenvolvimento de atividades de ensino aprendizagem" => $result["erro"]));

        $functioningOrgans = array($collumn["board_organ_association_parent"],
            $collumn["board_organ_association_parentinstructors"],
            $collumn["board_organ_board_school"],
            $collumn["board_organ_student_guild"],
            $collumn["board_organ_others"],
            $collumn["board_organ_inexistent"]);
        $result = $ssv->atLeastOne($functioningOrgans);
        if (!$result["status"]) array_push($log, array("Orgaos em Funcionamento na Escola" => $result["erro"]));

        //campo 87
        $result = $ssv->bandwidth($collumn["internet_access"],
            $collumn["bandwidth"]);
        if (!$result["status"]) array_push($log, array("bandwidth" => $result["erro"]));

        //campo 88
        $result = $ssv->isGreaterThan($collumn["employees_count"], "0");
        if (!$result["status"]) array_push($log, array("employees_count" => $result["erro"]));

        $school_inep_fk = $school['inep_id'];
        //campo 89
        $sql = 'SELECT  COUNT(pedagogical_mediation_type) AS number_of
		FROM 	classroom
		WHERE 	school_inep_fk = "$school_inep_id_fk" AND
				(pedagogical_mediation_type =  "1" OR pedagogical_mediation_type =  "2");';
        $pedagogical_mediation_type = Yii::app()->db->createCommand($sql)->queryAll();


        $result = $ssv->schoolFeeding($collumn["feeding"]);
        if (!$result["status"]) array_push($log, array("feeding" => $result["erro"]));

        //campo 96
        $sql = "SELECT 	DISTINCT  COUNT(esm.id) AS number_of, cr.school_inep_fk
			FROM 	classroom AS cr
						INNER JOIN
					edcenso_stage_vs_modality AS esm
						ON esm.id = cr.edcenso_stage_vs_modality_fk
			WHERE 	stage IN (2,3,7) AND cr.school_inep_fk = '$school_inep_fk';";
        $number_of_schools = Yii::app()->db->createCommand($sql)->queryAll();

        $result = $ssv->schoolCicle($collumn["basic_education_cycle_organized"], $number_of_schools);
        if (!$result["status"]) array_push($log, array("basic_education_cycle_organized" => $result["erro"]));

        //campo 97
        $result = $ssv->differentiatedLocation($school["inep_id"],
            $collumn["different_location"]);
        if (!$result["status"]) array_push($log, array("different_location" => $result["erro"]));

//        //101
//        $result = $ssv->isAllowed($collumn["native_education"], array("0", "1"));
//        if (!$result["status"]) array_push($log, array("native_education" => $result["erro"]));
//
//        //104
//        $result = $ssv->edcensoNativeLanguages($collumn["native_education_language_native"],
//            $collumn["edcenso_native_languages_fk"]);
//        if (!$result["status"]) array_push($log, array("edcenso_native_languages_fk" => $result["erro"]));

        //107
        $sql = "SELECT 	COUNT(esm.id ) AS number_of
			FROM 	classroom AS cr
						INNER JOIN
					edcenso_stage_vs_modality AS esm
						ON esm.id = cr.edcenso_stage_vs_modality_fk
			WHERE 	cr.assistance_type NOT IN (4,5) AND
					cr.school_inep_fk =  '$school_inep_id_fk' AND
					esm.stage NOT IN (1,2);";
        $pedagogical_formation_by_alternance = Yii::app()->db->createCommand($sql)->queryAll();

        $result = $ssv->pedagogicalFormation($collumn["pedagogical_formation_by_alternance"],
            $pedagogical_formation_by_alternance[0]["number_of"]);
        if (!$result["status"]) array_push($log, array("pedagogical_formation_by_alternance" => $result["erro"]));
        return $log;
    }

    public function validateClassroom($column, $school, $schoolstructure)
    {
        $crv = new ClassroomValidation();
        $log = array();

        //campo 1
        $result = $crv->isRegister('20', $column['register_type']);
        if (!$result['status']) array_push($log, array('register_type' => $result['erro']));

        //campo 2
        $result = $crv->isEqual($column['school_inep_fk'], $school['inep_id'], 'Inep ids sao diferentes');
        if (!$result['status']) array_push($log, array('school_inep_fk' => $result['erro']));

        //campo 3
        $result = $crv->isEmpty($column['inep_id']);
        if (!$result['status']) array_push($log, array('inep_id' => $result['erro']));

        //campo 4
        $result = $crv->checkLength($column['id'], 20);
        if (!$result['status']) array_push($log, array('id' => $result['erro']));

//        campo 5
        $result = $crv->isValidClassroomName($column['name']);
        if (!$result['status']) array_push($log, array('name' => $result['erro']));

        //campo 6
        $result = $crv->isValidMediation($column['pedagogical_mediation_type']);
        if (!$result['status']) array_push($log, array('pedagogical_mediation_type' => $result['erro']));

        //campos 7 a 10
        $result = $crv->isValidClassroomTime($column['initial_hour'], $column['initial_minute'],
            $column['final_hour'], $column['final_minute'],
            $column['pedagogical_mediation_type']);
        if (!$result['status']) array_push($log, array('classroom_time' => $result['erro']));
        //acima: imprimir "classroom_time" no erro ou um erro pra cada um dos campos?

        //campos 11 a 17
        $result = $crv->areValidClassroomDays(array($column['week_days_sunday'], $column['week_days_monday'], $column['week_days_tuesday'],
            $column['week_days_wednesday'], $column['week_days_thursday'], $column['week_days_friday'],
            $column['week_days_saturday']), $column['pedagogical_mediation_type']);
        if (!$result['status']) array_push($log, array('classroom_days' => $result['erro']));
        //acima: imprimir "classroom_days" no erro ou um erro pra cada um dos campos?

        //campo 18
//        $result = $crv->isValidAssistanceType($schoolstructure, $column['assistance_type'], $column['pedagogical_mediation_type']);
//        if (!$result['status']) array_push($log, array('assistance_type' => $result['erro']));

        //campos 20 a 25
        $activities = array($column['complementary_activity_type_1'], $column['complementary_activity_type_2'], $column['complementary_activity_type_3'],
            $column['complementary_activity_type_4'], $column['complementary_activity_type_5'], $column['complementary_activity_type_6']);
        $result = $crv->isValidComplementaryActivityType($activities, $column['complementary_activity']);
        if (!$result['status']) array_push($log, array('Atividades Complementares' => $result['erro']));

        //campos 26 a 36
        /*
		$aeeArray = array($column['aee_braille_system_education'], $column['aee_optical_and_non_optical_resources'],
			$column['aee_mental_processes_development_strategies'], $column['aee_mobility_and_orientation_techniques'],
			$column['aee_libras'], $column['aee_caa_use_education'], $column['aee_curriculum_enrichment_strategy'],
			$column['aee_soroban_use_education'], $column['aee_usability_and_functionality_of_computer_accessible_education'],
			$column['aee_teaching_of_Portuguese_language_written_modality'], $column['aee_strategy_for_school_environment_autonomy']);
			$result = $crv->isValidAEE($aeeArray, $column['assistance_type']);*/
        /*
		*	Ocultando a vilidação do AEE pois já está sendo tratada
		*	if (!$result['status']) array_push($log, array('aee' => $result['erro']));
		*/

        //campo 37

        $result = $crv->isValidModality($column['modality'], $column['pedagogical_mediation_type'], $column["complementary_activity"]);
        if (!$result['status']) array_push($log, array('modality' => $result['erro']));

        //campo 38

        //abaixo: $column['stage'] ou $column['edcenso_stage_vs_modality_fk']?
        $result = $crv->isValidStage($column['edcenso_stage_vs_modality_fk'], $column['complementary_activity'], $column['pedagogical_mediation_type'], $column["modality"], $column["diff_location"]);
        if (!$result['status']) array_push($log, array('stage' => $result['erro']));

        //campo 39
        $result = $crv->isValidProfessionalEducation($column['edcenso_professional_education_course_fk'], $column['edcenso_stage_vs_modality_fk']);
        if (!$result['status']) array_push($log, array('edcenso_professional_education_course' => $result['erro']));

        //campos 40 a 65
        $disciplinesArray = array($column['discipline_chemistry'], $column['discipline_physics'], $column['discipline_mathematics'], $column['discipline_biology'], $column['discipline_science'],
            $column['discipline_language_portuguese_literature'], $column['discipline_foreign_language_english'], $column['discipline_foreign_language_spanish'], $column['discipline_foreign_language_franch'], $column['discipline_foreign_language_other'],
            $column['discipline_arts'], $column['discipline_physical_education'], $column['discipline_history'], $column['discipline_geography'], $column['discipline_philosophy'],
            $column['discipline_social_study'], $column['discipline_sociology'], $column['discipline_informatics'], $column['discipline_professional_disciplines'], $column['discipline_special_education_and_inclusive_practices'],
            $column['discipline_sociocultural_diversity'], $column['discipline_libras'], $column['discipline_pedagogical'], $column['discipline_religious'], $column['discipline_native_language'],
            $column['discipline_others']);
        $result = $crv->isValidDiscipline($disciplinesArray, $column['pedagogical_mediation_type'], $column['assistance_type'], $column['edcenso_stage_vs_modality_fk']);
        if (!$result['status']) array_push($log, array('disciplines' => $result['erro']));

        $result = $crv->isValidAttendanceType($column["schooling"], $column["complementary_activity"], $column["aee"]);
        if (!$result['status']) array_push($log, array('schooling' => $result['erro']));

        $result = $crv->isValidDiffLocation($column['pedagogical_mediation_type'], $column["diff_location"]);
        if (!$result['status']) array_push($log, array('diff_location' => $result['erro']));

        $instructorsTeachingData = InstructorTeachingData::model()->findAll("classroom_id_fk = :classroom_id_fk", ["classroom_id_fk" => $column['id']]);
        $result = $crv->isValidRoleForInstructor($instructorsTeachingData, $column["complementary_activity"]);
        if (!$result['status']) array_push($log, array('Professores' => $result['erro']));

        $result = $crv->containsInstructors($instructorsTeachingData);
        if (!$result['status']) array_push($log, array('Turma' => $result['erro']));

        $result = $crv->isValidDisciplineForStage($column["edcenso_stage_vs_modality_fk"], $instructorsTeachingData);
        if (!$result['status']) array_push($log, array('Disciplinas' => $result['erro']));

        $studentsEnrollment = StudentEnrollment::model()->findAll("classroom_fk = :classroom_fk", ["classroom_fk" => $column['id']]);
        $result = $crv->containsStudents($studentsEnrollment);
        if (!$result['status']) array_push($log, array('Turma' => $result['erro']));


        return $log;
    }

    public function validateInstructor($collumn, $instructor_documents_and_address)
    {
        $sql = "SELECT inep_id FROM school_identification;";
        $inep_ids = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($inep_ids as $key => $value) {
            $allowed_school_inep_ids[] = $value['inep_id'];
        }

        $iiv = new InstructorIdentificationValidation();
        $school_inep_id_fk = $collumn["school_inep_id_fk"];
        $log = array();

        //campo 1
        $result = $iiv->isRegister("30", $collumn['register_type']);
        if (!$result["status"]) array_push($log, array("register_type" => $result["erro"]));

        //campo 2
        $result = $iiv->isAllowedInepId($school_inep_id_fk,
            $allowed_school_inep_ids);
        if (!$result["status"]) array_push($log, array("school_inep_id_fk" => $result["erro"]));

        //campo 3
        if ($collumn['inep_id'] != "" && $collumn['inep_id'] != null) {
            $result = $iiv->isValidPersonInepId($collumn['inep_id'], $school_inep_id_fk);
            if (!$result["status"]) array_push($log, array("inep_id" => $result["erro"]));
        }

        //campo 4
        $result = $iiv->isNotGreaterThan($collumn['id'], 20);
        if (!$result["status"]) array_push($log, array("id" => $result["erro"]));

        //campo 5
        $result = $iiv->isNameValid($collumn['name'], 100,
            $instructor_documents_and_address["cpf"]);
        if (!$result["status"]) array_push($log, array("name" => $result["erro"]));

        //campo 6
        $result = $iiv->isEmailValid($collumn['email'], 100);
        if (!$result["status"]) array_push($log, array("email" => $result["erro"]));

        //campo 8
        $year = Yii::app()->user->year;
        $result = $iiv->validateBirthday($collumn['birthday_date'], "13", "96", $year);
        if (!$result["status"]) array_push($log, array("birthday_date" => $result["erro"]));

        //campo 9
        $result = $iiv->oneOfTheValues($collumn['sex']);
        if (!$result["status"]) array_push($log, array("sex" => $result["erro"]));

        //campo 10
        $result = $iiv->isAllowed($collumn['color_race'], array("0", "1", "2", "3", "4", "5"));
        if (!$result["status"]) array_push($log, array("sex" => $result["erro"]));

        //campo 11, 12, 13
        $result = $iiv->isNameValid($collumn['filiation_1'], 100, $instructor_documents_and_address["cpf"]);
        if (!$result["status"]) array_push($log, array("filiation" => $result["erro"]));
        $result = $iiv->isNameValid($collumn['filiation_2'], 100, $instructor_documents_and_address["cpf"]);
        if (!$result["status"]) array_push($log, array("filiation" => $result["erro"]));
        $result = $iiv->validateFiliation($collumn['filiation'], $collumn['filiation_1'], $collumn['filiation_2'],
            $instructor_documents_and_address["cpf"], 100);
        if (!$result["status"]) array_push($log, array("filiation" => $result["erro"]));

        //campo 14, 15
        $result = $iiv->checkNation($collumn['nationality'], $collumn['edcenso_nation_fk'], array("1", "2", "3"));
        if (!$result["status"]) array_push($log, array("nationality_nation" => $result["erro"]));

        //campo 16
        $result = $iiv->ufcity($collumn['nationality'], $collumn['edcenso_nation_fk'], $collumn['edcenso_uf_fk']);
        if (!$result["status"]) array_push($log, array("edcenso_uf_fk" => $result["erro"]));

        //campo 17 -- @todo melhorar essa checagem
        /*
        $result = $iiv->ufcity($collumn['edcenso_city_fk'], $collumn['nationality']);
        if(!$result["status"]) array_push($log, array("edcenso_uf_fk"=>$result["erro"]));
        */

        //campo 18
        $result = $iiv->isAllowed($collumn['deficiency'], array("0", "1"));
        if (!$result["status"]) array_push($log, array("deficiency" => $result["erro"]));

        //campo 19 à 25
        $deficiencies = array($collumn['deficiency_type_blindness'],
            $collumn['deficiency_type_low_vision'],
            $collumn['deficiency_type_deafness'],
            $collumn['deficiency_type_disability_hearing'],
            $collumn['deficiency_type_deafblindness'],
            $collumn['deficiency_type_phisical_disability'],
            $collumn['deficiency_type_intelectual_disability']);

        $excludingdeficiencies = array($collumn['deficiency_type_blindness'] =>
            array($collumn['deficiency_type_low_vision'], $collumn['deficiency_type_deafness'],
                $collumn['deficiency_type_deafblindness']),
            $collumn['deficiency_type_low_vision'] =>
                array($collumn['deficiency_type_deafblindness']),
            $collumn['deficiency_type_deafness'] =>
                array($collumn['deficiency_type_disability_hearing'], $collumn['deficiency_type_disability_hearing']),
            $collumn['deficiency_type_disability_hearing'] =>
                array($collumn['deficiency_type_deafblindness']));

        if (!empty($collumn['deficiency'])) {
            $result = $iiv->checkDeficiencies($collumn['deficiency'], $deficiencies, $excludingdeficiencies);
            //if(!$result["status"]) array_push($log, array("deficiencies"=>$result["erro"]));
        }

        //campo 26

        $result = $iiv->checkMultiple($collumn['deficiency'], $collumn['deficiency_type_multiple_disabilities'], $deficiencies);
        //if(!$result["status"]) array_push($log, array("deficiency_type_multiple_disabilities"=>$result["erro"]));

        return $log;
    }

    public function validateInstructorDocuments($collumn)
    {
        $idav = new InstructorDocumentsAndAddressValidation();
        $log = array();

        $school_inep_id_fk = $collumn["school_inep_id_fk"];
        $instructor_inep_id = $collumn["inep_id"];

        //campo 1
        $result = $idav->isRegister("40", $collumn['register_type']);
        if (!$result["status"]) array_push($log, array("register_type" => $result["erro"]));

        $sql = "SELECT inep_id FROM school_identification;";
        $inep_ids = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($inep_ids as $key => $value) {
            $allowed_school_inep_ids[] = $value['inep_id'];
        }
        //campo 2
        $result = $idav->isAllowedInepId($school_inep_id_fk,
            $allowed_school_inep_ids);
        if (!$result["status"]) array_push($log, array("school_inep_id_fk" => $result["erro"]));

        //campo 3
        if (!empty($instructor_inep_id)) {
            $sql = "SELECT COUNT(inep_id) AS status FROM instructor_documents_and_address WHERE inep_id =  '$instructor_inep_id'";
            $check = Yii::app()->db->createCommand($sql)->queryAll();
            $result = $idav->isEqual($check[0]['status'], '1', "Não há tal inep_id $instructor_inep_id");
            //if(!$result["status"]) array_push($log, array("inep_id"=>$result["erro"]));
        }

        //campo 4
        $result = $idav->isNotGreaterThan($collumn['id'], 20);
        if (!$result["status"]) array_push($log, array("id" => $result["erro"]));

        if (empty($instructor_inep_id) || !empty($collumn['cpf'])) {
            $result = $idav->isCPFValid($collumn['cpf']);
            if (!$result["status"]) array_push($log, array("cpf" => $result["erro"]));
        }

        $result = $idav->isAreaOfResidenceValid($collumn['area_of_residence']);
        if (!$result["status"]) array_push($log, array("Localizacao/Zona de residencia" => $result["erro"]));

        return $log;
    }

    public function validateInstructorData($collumn)
    {
        $sql = "SELECT inep_id FROM school_identification;";
        $inep_ids = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($inep_ids as $key => $value) {
            $allowed_school_inep_ids[] = $value['inep_id'];
        }

        $itdv = new InstructorTeachingDataValidation();
        $school_inep_id_fk = $collumn["school_inep_id_fk"];
        $instructor_inep_id = $collumn["instructor_inep_id"];
        $instructor_fk = $collumn['instructor_fk'];
        $classroom_fk = $collumn['classroom_id_fk'];
        $log = array();

        //campo 1
        $result = $itdv->isRegister("51", $collumn['register_type']);
        if (!$result["status"]) array_push($log, array("register_type" => $result["erro"]));

        //campo 2
        $result = $itdv->isAllowedInepId($school_inep_id_fk,
            $allowed_school_inep_ids);
        if (!$result["status"]) array_push($log, array("school_inep_id_fk" => $result["erro"]));

        //campo 4
        $sql = "SELECT COUNT(id) AS status FROM instructor_identification WHERE id =  '$instructor_fk'";
        $check = Yii::app()->db->createCommand($sql)->queryAll();

        $result = $itdv->isEqual($check[0]['status'], '1', 'Não há tal instructor_fk $instructor_fk');
        if (!$result["status"]) array_push($log, array("instructor_fk" => $result["erro"]));

        //campo 5
        $result = $itdv->isNull($collumn['classroom_inep_id']);
        if (!$result["status"]) array_push($log, array("classroom_inep_id" => $result["erro"]));

        //campo 6
        $sql = "SELECT COUNT(id) AS status FROM classroom WHERE id = '$classroom_fk';";
        $check = Yii::app()->db->createCommand($sql)->queryAll();

        $result = $itdv->isEqual($check[0]['status'], '1', 'Não há tal classroom_id_fk $classroom_fk');
        if (!$result["status"]) array_push($log, array("classroom_id_fk" => $result["erro"]));

        //campo 7
        $sql = "SELECT assistance_type, pedagogical_mediation_type, edcenso_stage_vs_modality_fk
			FROM classroom
			WHERE id = '$classroom_fk';";
        $check = Yii::app()->db->createCommand($sql)->queryAll();;
        $assistance_type = $check[0]['assistance_type'];
        $pedagogical_mediation_type = $check[0]['pedagogical_mediation_type'];
        $edcenso_svm = $check[0]['edcenso_stage_vs_modality_fk'];

        $sql = "SELECT count(cr.id) AS status_instructor
			FROM 	classroom as cr
						INNER JOIN
					instructor_teaching_data AS itd
						ON itd.classroom_id_fk = cr.id
			WHERE 	cr.id = '$classroom_fk' AND itd.id != '$instructor_fk';";
        $check = Yii::app()->db->createCommand($sql)->queryAll();
        $status_instructor = $check[0]['status_instructor'];


        $sql = "SELECT count(si.id) AS status_student
			FROM 	classroom AS cr
						INNER JOIN
					instructor_teaching_data AS itd
						ON itd.classroom_id_fk = cr.id
						INNER JOIN
					instructor_identification as ii
						ON ii.id = itd.instructor_fk
						INNER JOIN
					student_enrollment AS se
						ON se.classroom_fk =cr.id
						INNER JOIN
					student_identification AS si
					 	on si.id = se.student_fk
			WHERE 	cr.id = '$classroom_fk' AND ii.id = '$instructor_fk'
					AND
					(ii.deficiency_type_deafness = '1' OR ii.deficiency_type_disability_hearing = '1' OR
					ii.deficiency_type_deafblindness = '1' OR si.deficiency_type_deafness = '1' OR
					si.deficiency_type_deafblindness = '1');";
        $check = Yii::app()->db->createCommand($sql)->queryAll();
        $status_student = $check[0]['status_student'];

        $result = $itdv->checkRole($collumn['role'], $pedagogical_mediation_type,
            $assistance_type, $status_instructor, $status_student);
        if (!$result["status"]) array_push($log, array("role" => $result["erro"]));

        //campo 08
        $sql = "SELECT se.administrative_dependence
			FROM school_identification AS se
			WHERE se.inep_id = '$school_inep_id_fk';";

        $check = Yii::app()->db->createCommand($sql)->queryAll();

        $administrative_dependence = $check[0]['administrative_dependence'];

        /*
		* Ocultando validação pois a mesma já está sendo tratada
		* $result = $itdv->checkContactType($collumn['contract_type'], $collumn['role'], $administrative_dependence);
		* if(!$result["status"]) array_push($log, array("contract_type"=>$result["erro"]));
		*/

        //campo 09
//        $result = $itdv->disciplineOne($collumn['discipline_1_fk'], $collumn['role'], $assistance_type, $edcenso_svm);
//        if (!$result["status"]) array_push($log, array("discipline_1_fk" => $result["erro"]));

        //campo 09 à 21

        $disciplines_codes = array($collumn['discipline_1_fk'],
            $collumn['discipline_2_fk'],
            $collumn['discipline_3_fk'],
            $collumn['discipline_4_fk'],
            $collumn['discipline_5_fk'],
            $collumn['discipline_6_fk'],
            $collumn['discipline_7_fk'],
            $collumn['discipline_8_fk'],
            $collumn['discipline_9_fk'],
            $collumn['discipline_10_fk'],
            $collumn['discipline_11_fk'],
            $collumn['discipline_12_fk'],
            $collumn['discipline_13_fk']);


        $sql = "SELECT discipline_chemistry, discipline_physics, discipline_mathematics, discipline_biology,
						discipline_science, discipline_language_portuguese_literature,
						discipline_foreign_language_english, discipline_foreign_language_spanish,
						discipline_foreign_language_franch, discipline_foreign_language_other,
						discipline_arts, discipline_physical_education, discipline_history, discipline_geography,
						discipline_philosophy, discipline_social_study, discipline_sociology, discipline_informatics,
						discipline_professional_disciplines, discipline_special_education_and_inclusive_practices,
						discipline_sociocultural_diversity, discipline_libras, discipline_pedagogical,
						discipline_religious, discipline_native_language, discipline_others
			FROM 		classroom
			WHERE 	id = '$classroom_fk';";

        $check = Yii::app()->db->createCommand($sql)->queryAll();

        $disciplines = array_values($check[0]);
        $result = $itdv->checkDisciplineCode($disciplines_codes, $collumn['role'], $assistance_type,
            $edcenso_svm, $disciplines);
        if (!$result["status"]) array_push($log, array("disciplines_codes" => $result["erro"]));

        return $log;
    }

    public function validateStudentIdentification($collumn, $studentdocument, $classroom)
    {

        $sql = "SELECT inep_id FROM school_identification;";
        $inep_ids = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($inep_ids as $key => $value) {
            $allowed_school_inep_ids[] = $value['inep_id'];
        }

        $stiv = new StudentIdentificationValidation();
        $school_inep_id_fk = $collumn["school_inep_id_fk"];
        $log = array();

        //campo 1
        $result = $stiv->isRegister("60", $collumn['register_type']);
        //if(!$result["status"]) array_push($log, array("register_type"=>$result["erro"]));

        //campo 2
        $result = $stiv->isAllowedInepId($school_inep_id_fk,
            $allowed_school_inep_ids);
        if (!$result["status"]) array_push($log, array("school_inep_id_fk" => $result["erro"]));

        //campo 3
        if ($collumn['inep_id'] != "" && $collumn['inep_id'] != null) {
            $result = $stiv->isValidPersonInepId($collumn['inep_id'], $school_inep_id_fk);
            if (!$result["status"]) array_push($log, array("inep_id" => $result["erro"]));
        }

        //campo 4
        $result = $stiv->isNotGreaterThan($collumn['id'], 20);
        if (!$result["status"]) array_push($log, array("id" => $result["erro"]));

        //campo 5
        $result = $stiv->isNameValid($collumn['name'], 100,
            $studentdocument["cpf"]);
        if (!$result["status"]) array_push($log, array("name" => $result["erro"]));

        $year = Yii::app()->user->year;
        //campo 6
        $result = $stiv->validateBirthday($collumn['birthday'], $year, $classroom["edcenso_stage_vs_modality_fk"]);
        if (!$result["status"]) array_push($log, array("birthday" => $result["erro"]));

        //campo 7
        $result = $stiv->oneOfTheValues($collumn['sex']);
        if (!$result["status"]) array_push($log, array("sex" => $result["erro"]));

        //campo 8
        $result = $stiv->isAllowed($collumn['color_race'], array("0", "1", "2", "3", "4", "5"));
        if (!$result["status"]) array_push($log, array("sex" => $result["erro"]));

        //campo 9, 10, 11
        $result = $stiv->isNameValid($collumn['filiation_1'], 100, $studentdocument["cpf"]);
        if (!$result["status"]) array_push($log, array("filiation" => $result["erro"]));
        $result = $stiv->isNameValid($collumn['filiation_2'], 100, $studentdocument["cpf"]);
        if (!$result["status"]) array_push($log, array("filiation" => $result["erro"]));
        $result = $stiv->validateFiliation($collumn['filiation'], $collumn['filiation_1'], $collumn['filiation_2'],
            $studentdocument["cpf"], 100);
        if (!$result["status"]) array_push($log, array("filiation" => $result["erro"]));

        //campo 12, 13
        $result = $stiv->checkNation($collumn['nationality'], $collumn['edcenso_nation_fk'], array("1", "2", "3"));
        if (!$result["status"]) array_push($log, array("nationality_nation" => $result["erro"]));

        //campo 14
        $result = $stiv->ufcity($collumn['nationality'], $collumn['edcenso_nation_fk'], $collumn['edcenso_uf_fk']);
        if (!$result["status"]) array_push($log, array("edcenso_uf_fk" => $result["erro"]));

        //campo 15
        // $result = $stiv->ufcity($collumn['nationality'], $collumn['edcenso_nation_fk'], $collumn['edcenso_city_fk']);
        // if (!$result["status"]) array_push($log, array("edcenso_uf_fk" => $result["erro"]));

        //campo 16
        $student_id = $collumn['id'];

        $sql = "SELECT 	COUNT(cr.id) AS status
			FROM 	student_identification as si
						INNER JOIN
					student_enrollment AS se
						ON si.id = se.student_fk
          				INNER JOIN
          			classroom AS cr
          				ON se.classroom_fk = cr.id
			WHERE si.id = '$student_id' AND (cr.assistance_type = 5 OR cr.modality = 2)
			GROUP BY si.id;";

        @$hasspecialneeds = Yii::app()->db->createCommand($sql)->queryAll();

        @$result = $stiv->specialNeeds($collumn['deficiency'], array("0", "1"),
            $hasspecialneeds["status"]);
        if (!$result["status"]) array_push($log, array("pedagogical_formation_by_alternance" => $result["erro"]));

        //campo 17 à 24 e 26 à 29

        $deficiencies_whole = array($collumn['deficiency_type_blindness'],
            $collumn['deficiency_type_low_vision'],
            $collumn['deficiency_type_deafness'],
            $collumn['deficiency_type_disability_hearing'],
            $collumn['deficiency_type_deafblindness'],
            $collumn['deficiency_type_phisical_disability'],
            $collumn['deficiency_type_intelectual_disability'],
            $collumn['deficiency_type_multiple_disabilities'],
            $collumn['deficiency_type_autism'],
            $collumn['deficiency_type_gifted']);

        $excludingdeficiencies = [
            ["Cegueira" => [$collumn['deficiency_type_blindness'] => [$collumn['deficiency_type_low_vision'], $collumn['deficiency_type_deafness'], $collumn['deficiency_type_deafblindness']]]],
            ["Baixa Visão" => [$collumn['deficiency_type_low_vision'] => [$collumn['deficiency_type_deafblindness']]]],
            ["Surdez" => [$collumn['deficiency_type_deafness'] => [$collumn['deficiency_type_disability_hearing'], $collumn['deficiency_type_deafblindness']]]],
            ["Deficiência Auditiva" => [$collumn['deficiency_type_disability_hearing'] => [$collumn['deficiency_type_deafblindness']]]]
        ];

        $result = $stiv->checkDeficiencies($collumn['deficiency'], $deficiencies_whole, $excludingdeficiencies);
        if (!$result["status"]) array_push($log, array("Tipos de Deficiencia" => $result["erro"]));

        $deficiencies_sample = array(
            $collumn['deficiency_type_blindness'],
            $collumn['deficiency_type_low_vision'],
            $collumn['deficiency_type_deafness'],
            $collumn['deficiency_type_disability_hearing'],
            $collumn['deficiency_type_deafblindness'],
            $collumn['deficiency_type_phisical_disability'],
            $collumn['deficiency_type_intelectual_disability']);

        $result = $stiv->checkMultiple($collumn['deficiency'], $collumn['deficiency_type_multiple_disabilities'], $deficiencies_sample);
        if (!$result["status"]) array_push($log, array("Tipos de Deficiencia" => $result["erro"]));

        $resources = array(
            $collumn['resource_aid_lector'],
            $collumn['resource_aid_transcription'],
            $collumn['resource_interpreter_guide'],
            $collumn['resource_interpreter_libras'],
            $collumn['resource_lip_reading'],
            $collumn['resource_zoomed_test_18'],
            $collumn['resource_zoomed_test_24'],
            $collumn['resource_cd_audio'],
            $collumn['resource_proof_language'],
            $collumn['resource_video_libras'],
            $collumn['resource_braille_test'],
            $collumn['resource_none']);

        array_pop($deficiencies_whole);
        $result = $stiv->inNeedOfResources($collumn['deficiency'], $deficiencies_whole, $resources);
        if (!$result["status"]) array_push($log, array("Recursos requeridos em avaliacoes do INEP" => $result["erro"]));

        return $log;
    }

    public function validateStudentDocumentsAddress($collumn, $studentident)
    {
        $student_inep_id = $collumn['student_fk'];
        $sql = "SELECT inep_id FROM school_identification;";
        $inep_ids = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($inep_ids as $key => $value) {
            $allowed_school_inep_ids[] = $value['inep_id'];
        }
        $sql = "SELECT inep_id FROM student_identification;";
        $array = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($array as $key => $value) {
            $allowed_students_inep_ids[] = $value['inep_id'];
        }

        $sda = new StudentDocumentsAndAddressValidation();
        $school_inep_id_fk = $collumn["school_inep_id_fk"];
        $student_inep_id_fk = $collumn["student_fk"];
        $log = array();

        $nationality = $studentident['nationality'];

        $foreign = $sda->isAllowed($nationality, array("3"));
        $field6006 = $collumn['birthday'];
        $field7005 = $collumn['rg_number'];

        date_default_timezone_set('America/Bahia');
        $date = date('d/m/Y');

        //$sda->isAllowed($collumn['civil_certification'], array("1", "2"));
        $civil_certification = $collumn['civil_certification'];


        //campo 1
        $result = $sda->isRegister("70", $collumn['register_type']);
        if (!$result["status"]) array_push($log, array("register_type" => $result["erro"]));

        //campo 2
        $result = $sda->isAllowedInepId($school_inep_id_fk,
            $allowed_school_inep_ids);
        if (!$result["status"]) array_push($log, array("school_inep_id_fk" => $result["erro"]));

        //campo 3
        $result = $sda->isAllowedInepId($student_inep_id_fk,
            $allowed_students_inep_ids);
        //if(!$result["status"]) array_push($log, array("student_inep_id"=>$result["erro"]));

        //campo 4
        $sql = "SELECT COUNT(inep_id) AS status FROM student_identification WHERE inep_id = '$student_inep_id';";
        $check = Yii::app()->db->createCommand($sql)->queryAll();
        $result = $sda->isEqual($check[0]['status'], '1', "Não há tal student_inep_id $student_inep_id");
        //if(!$result["status"]) array_push($log, array("student_fk"=>$result["erro"]));

        //campo 5
        /*if(!empty($collumn['rg_number'])){
			$result = $sda->isRgNumberValid($collumn['rg_number'], $field6012);
			if(!$result["status"]) array_push($log, array("rg_number"=>$result["erro"]));

			//campo 6
			$result = $sda->isRgEmissorOrganValid($collumn['rg_number_edcenso_organ_id_emitter_fk'], $field6012, $field7005);
			if(!$result["status"]) array_push($log, array("rg_number_edcenso_organ_id_emitter_fk"=>$result["erro"]));

			//campo 7
			$result = $sda->isRgUfValid($collumn['rg_number_edcenso_uf_fk'], $field6012, $field7005);
			if(!$result["status"]) array_push($log, array("rg_number_edcenso_uf_fk"=>$result["erro"]));
		}*/

        //campo 8
        /*$result = $sda->isDateValid($field6012, $collumn['rg_number_expediction_date'] ,$field6006, $date, 0, 8);
        if(!$result["status"]) array_push($log, array("rg_number_expediction_date"=>$result["erro"]));
        */

        //campo 9
        $result = $sda->isAllowed($collumn['civil_certification'], array("1", "2"));
        //if(!$result["status"]) array_push($log, array("rg_number_expediction_date"=>$result["erro"]));

        if ($civil_certification == 2) {
            if ($nationality == 1 || $nationality == 2) {
                $result = $sda->isCivilRegisterNumberValid($collumn['civil_register_enrollment_number'], $studentident['birthday']);
                if (!$result["status"]) array_push($log, array("civil_register_enrollment_number" => $result["erro"]));
            } else if ($collumn['civil_register_enrollment_number'] !== "") {
                return array("status" => false, "erro" => "Como foi preenchido o nº de matrícula da certidão nova, a nacionalidade do aluno deveria ser brasileira, nascido no exterior ou não.");
            }
        }

//        if (empty($collumn['cpf']) && empty($collumn['nis'])) {
//            //campo 10
//            $result = $sda->isCivilCertificationTypeValid($civil_certification, $field7005, $nationality, $field6006, $date);
//            if (!$result["status"]) array_push($log, array("civil_certification_type" => $result["erro"]));
//
//            //campo 11
//            $civil_certification = $collumn['civil_certification'];
//
//            if ($civil_certification == 1) {
//                array_push($log, array("civil_register_enrollment_number" => $result["erro"]));
////
////                $result = $sda->isFieldValid(8, $collumn['civil_certification_term_number'], $nationality, $civil_certification);
////                if (!$result["status"]) array_push($log, array("civil_certification_term_number" => $result["erro"]));
////
////                //campo 12
////                $result = $sda->isFieldValid(4, $collumn['civil_certification_sheet'], $nationality, $civil_certification);
////                if (!$result["status"]) array_push($log, array("civil_certification_sheet" => $result["erro"]));
////
////                //campo 13
////                $result = $sda->isFieldValid(8, $collumn['civil_certification_book'], $nationality, $civil_certification);
////                if (!$result["status"]) array_push($log, array("civil_certification_book" => $result["erro"]));
////
////                //campo 14
////                $result = $sda->isDateValid($nationality, $collumn['civil_certification_date'], $field6006, $date, 1, 14);
////                if (!$result["status"]) array_push($log, array("civil_certification_date" => $result["erro"]));
////
////                //campo 15
////                $result = $sda->isFieldValid(2, $collumn['notary_office_uf_fk'], $nationality, $civil_certification);
////                if (!$result["status"]) array_push($log, array("notary_office_uf_fk" => $result["erro"]));
////
////                //campo 16
////                $result = $sda->isFieldValid(7, $collumn['notary_office_city_fk'], $nationality, $civil_certification);
////                if (!$result["status"]) array_push($log, array("notary_office_city_fk" => $result["erro"]));
////
////                //campo 17
////                $result = $sda->isFieldValid(6, $collumn['edcenso_notary_office_fk'], $nationality, $civil_certification);
////                if (!$result["status"]) array_push($log, array("edcenso_notary_office_fk" => $result["erro"]));
//
//            } else {
//                //campo 18
//                $result = $sda->isCivilRegisterNumberValid($collumn['civil_register_enrollment_number'], $nationality, $civil_certification);
//                if (!$result["status"]) array_push($log, array("civil_register_enrollment_number" => $result["erro"]));
//            }
//        }
        //campo 19
        if (!empty($collumn['cpf'])) {
            $result = $sda->isCPFValid($collumn['cpf']);
            if (!$result["status"]) array_push($log, array("cpf" => $result["erro"]));
        }
        //campo 20
        $result = $sda->isPassportValid($collumn['foreign_document_or_passport'], $nationality);
        if (!$result["status"]) array_push($log, array("foreign_document_or_passport" => $result["erro"]));

        //campo 21
//        if (!empty($collumn['nis'])) {
//            $result = $sda->isNISValid($collumn['nis']);
//            if (!$result["status"]) array_push($log, array("nis" => $result["erro"]));
//        }

        $result = $sda->isAreaOfResidenceValid($collumn['residence_zone']);
        if (!$result["status"]) array_push($log, array("Localizacao/Zona de residencia" => $result["erro"]));

        return $log;
    }

    public function validateEnrollment($collumn)
    {
        $sql = "SELECT inep_id FROM school_identification;";
        $inep_ids = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($inep_ids as $key => $value) {
            $allowed_school_inep_ids[] = $value['inep_id'];
        }
        $sql = "SELECT inep_id FROM student_identification;";
        $array = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($array as $key => $value) {
            $allowed_students_inep_ids[] = $value['inep_id'];
        }

        $sev = new StudentEnrollmentValidation();
        $school_inep_id_fk = $collumn["school_inep_id_fk"];
        $student_inep_id_fk = $collumn["student_inep_id"];
        $classroom_fk = $collumn['classroom_fk'];
        $log = array();

        //campo 1
        $result = $sev->isRegister("80", $collumn['register_type']);
        if (!$result["status"]) array_push($log, array("register_type" => $result["erro"]));

        //campo 2
        $result = $sev->isAllowedInepId($school_inep_id_fk,
            $allowed_school_inep_ids);
        if (!$result["status"]) array_push($log, array("school_inep_id_fk" => $result["erro"]));

        //campo 4
        $sql = "SELECT COUNT(inep_id) AS status FROM student_identification WHERE inep_id = '$student_inep_id_fk';";
        $check = Yii::app()->db->createCommand($sql)->queryAll();

        if (!empty($student_inep_id)) {
            $result = $sev->isEqual($check[0]['status'], '1', "Não há tal student_inep_id $student_inep_id");
            if (!$result["status"]) array_push($log, array("student_fk" => $result["erro"]));
        }

        //campo 05
        $result = $sev->isNull($collumn['classroom_inep_id']);
        if (!$result["status"]) array_push($log, array("classroom_inep_id" => $result["erro"]));

        //campo 6

        $sql = "SELECT COUNT(id) AS status FROM classroom WHERE id = '$classroom_fk';";
        $check = Yii::app()->db->createCommand($sql)->queryAll();

        $result = $sev->isEqual($check[0]['status'], '1', 'Não há tal classroom_id $classroom_fk');
        if (!$result["status"]) array_push($log, array("classroom_fk" => $result["erro"]));

        //campo 07
        $result = $sev->isNull($collumn['enrollment_id']);
        if (!$result["status"]) array_push($log, array("enrollment_id" => $result["erro"]));

        //campo 8

        $sql = "SELECT COUNT(id) AS status FROM classroom WHERE id = '$classroom_fk' AND edcenso_stage_vs_modality_fk = '3';";
        $check = Yii::app()->db->createCommand($sql)->queryAll();

        //$result = $sev->ifDemandsCheckValues($check[0]['status'], $collumn['unified_class'], array('1', '2'));
        //$result['erro'] = str_replace(['value 2', 'value 1'], ['',''], $result['erro']);
        //if(!$result["status"]) array_push($log, array("unified_class"=>$result["erro"]));

        //campo 9

        $sql = "SELECT edcenso_stage_vs_modality_fk, aee FROM classroom WHERE id = '$classroom_fk';";
        $check = Yii::app()->db->createCommand($sql)->queryAll();

        $edcenso_svm = $check[0]['edcenso_stage_vs_modality_fk'];
        $aee = $check[0]['aee'];

        //$result = $sev->multiLevel($collumn['edcenso_stage_vs_modality_fk'], $edcenso_svm);
        //if(!$result["status"]) array_push($log, array("edcenso_stage_vs_modality_fk"=>$result["erro"]));

        //campo 10
        $sql = "SELECT pedagogical_mediation_type FROM classroom WHERE id = '$classroom_fk';";
        $check = Yii::app()->db->createCommand($sql)->queryAll();
        $pedagogical_mediation_type = $check[0]['pedagogical_mediation_type'];

        //campo 11
        //@todo setar nulo na exportação
        if (!empty($collumn['public_transport'])) {
            $result = $sev->publicTransportation($collumn['public_transport'], $pedagogical_mediation_type);
            if (!$result["status"]) array_push($log, array("public_transport" => $result["erro"]));

            //campo 12
            $result = $sev->ifDemandsCheckValues($collumn['public_transport'], $collumn['transport_responsable_government'], array('1', '2'));
            if (!$result["status"]) array_push($log, array("transport_responsable_government" => $result["erro"]));

            //campo 13 à 23

            $vehicules_types = array($collumn['vehicle_type_van'],
                $collumn['vehicle_type_microbus'],
                $collumn['vehicle_type_bus'],
                $collumn['vehicle_type_bike'],
                $collumn['vehicle_type_other_vehicle'],
                $collumn['vehicle_type_waterway_boat_5'],
                $collumn['vehicle_type_waterway_boat_5_15'],
                $collumn['vehicle_type_waterway_boat_15_35'],
                $collumn['vehicle_type_waterway_boat_35'],
                $collumn['vehicle_type_metro_or_train']);


            /*
			 * Ocultando validação pois a mesm já está sendo tratada
			 * $result = $sev->vehiculesTypes($collumn['public_transport'], $vehicules_types);
			 * if(!$result["status"]) array_push($log, array("vehicules_types"=>$result["erro"]));
			 */
        }

        //24

        $sql = "SELECT se.administrative_dependence
			FROM school_identification AS se
			WHERE se.inep_id = '$school_inep_id_fk';";

        $check = Yii::app()->db->createCommand($sql)->queryAll();

        $administrative_dependence = $check[0]['administrative_dependence'];


        $result = $sev->studentEntryForm($collumn['student_entry_form'], $administrative_dependence, $edcenso_svm);
        if (!$result["status"]) array_push($log, array("student_entry_form" => $result["erro"]));

        $result = $sev->isValidMultiClassroom($edcenso_svm, $collumn["edcenso_stage_vs_modality_fk"]);
        if (!$result["status"]) array_push($log, array("Etapa de Ensino" => $result["erro"]));

        $aeeTypes = array($collumn['aee_cognitive_functions'],
            $collumn['aee_autonomous_life'],
            $collumn['aee_curriculum_enrichment'],
            $collumn['aee_accessible_teaching'],
            $collumn['aee_libras'],
            $collumn['aee_portuguese'],
            $collumn['aee_soroban'],
            $collumn['aee_braille'],
            $collumn['aee_mobility_techniques'],
            $collumn['aee_caa'],
            $collumn['aee_optical_nonoptical']);

        $result = $sev->hasAEETypeSelected($aee, $aeeTypes);
        if (!$result["status"]) array_push($log, array("Tipo de Atendimento Educacional Especializado" => $result["erro"]));

        return $log;
    }

    public function actionValidate()
    {
        Yii::import('ext.Validator.*');
        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
        $schoolstructure = SchoolStructure::model()->findByPk(Yii::app()->user->school);
        $schoolcolumn = $school->attributes;
        $schoolstructurecolumn = $schoolstructure->attributes;
        $log['school']['info'] = $school->attributes;
        $log['school']['validate']['identification'] = $this->validateSchool($schoolcolumn);
        $log['school']['validate']['structure'] = $this->validateSchoolStructure($schoolstructurecolumn, $schoolcolumn);
        $classrooms = Classroom::model()->findAllByAttributes(["school_inep_fk" => yii::app()->user->school, "school_year" => Yii::app()->user->year]);
        foreach ($classrooms as $iclass => $classroom) {
            $log['classroom'][$iclass]['info'] = $classroom->attributes;
            $log['classroom'][$iclass]['validate']['identification'] = $this->validateClassroom($classroom, $schoolcolumn, $schoolstructure);
            foreach ($classroom->instructorTeachingDatas as $iteaching => $teachingData) {
                if (!isset($log['instructor'][$teachingData->instructor_fk])) {
                    $log['instructor'][$teachingData->instructor_fk]['info'] = $teachingData->instructorFk->attributes;
                    $log['instructor'][$teachingData->instructor_fk]['validate']['identification'] = $this->validateInstructor($teachingData->instructorFk->attributes, $teachingData->instructorFk->documents->attributes);
                    $log['instructor'][$teachingData->instructor_fk]['validate']['documents'] = $this->validateInstructorDocuments($teachingData->instructorFk->documents->attributes);
                }
                $log['instructor'][$teachingData->instructor_fk]['validate']['variabledata'][$iteaching]['id'] = $teachingData->classroomIdFk->id;
                $log['instructor'][$teachingData->instructor_fk]['validate']['variabledata'][$iteaching]['turma'] = $teachingData->classroomIdFk->name;
                $log['instructor'][$teachingData->instructor_fk]['validate']['variabledata'][$iteaching]['errors'] = $this->validateInstructorData($teachingData->attributes);
            }
            foreach ($classroom->studentEnrollments as $ienrollment => $enrollment) {
                if (!isset($log['student'][$enrollment->student_fk]['info'])) {
                    $log['student'][$enrollment->student_fk]['info'] = $enrollment->studentFk->attributes;
                    $log['student'][$enrollment->student_fk]['validate']['identification'] = $this->validateStudentIdentification($enrollment->studentFk->attributes, $enrollment->studentFk->documentsFk->attributes, $enrollment->classroomFk->attributes);
                    @$log['student'][$enrollment->student_fk]['validate']['documents'] = $this->validateStudentDocumentsAddress($enrollment->studentFk->documentsFk->attributes, $enrollment->studentFk->attributes);
                }
                $log['student'][$enrollment->student_fk]['validate']['enrollment'][$ienrollment]['id'] = $enrollment->id;
                $log['student'][$enrollment->student_fk]['validate']['enrollment'][$ienrollment]['turma'] = $enrollment->classroomFk->name;
                $log['student'][$enrollment->student_fk]['validate']['enrollment'][$ienrollment]['errors'] = $this->validateEnrollment($enrollment->attributes);
            }
        }
        $this->render('validate', ['log' => $log]);
    }

    public function fixName($name)
    {
        $name = preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($name));
        $name = str_replace(array('Á','À','Ã','Â','Ä'), 'A', $name); // substitui acentos em "A"
        $name = str_replace(array('É','È','Ê','Ë'), 'E', $name); // substitui acentos em "E"
        $name = str_replace(array('Í','Ì','Î','Ï'), 'I', $name); // substitui acentos em "I"
        $name = str_replace(array('Ó','Ò','Õ','Ô','Ö'), 'O', $name); // substitui acentos em "O"
        $name = str_replace(array('Ú','Ù','Û','Ü'), 'U', $name); // substitui acentos em "U"
        $name = str_replace(array('Ç'), 'C', $name); // substitui "Ç" por "C"
        return $name;
    }

    public function actionExportWithoutInepid()
    {
        $year = Yii::app()->user->year;
        $sql = "SELECT DISTINCT si.id,si.school_inep_id_fk ,sd.cpf,sd.civil_register_enrollment_number,sd.nis, si.inep_id , si.name , si.birthday , si.filiation_1 , si.filiation_2 , si.edcenso_uf_fk , si.edcenso_city_fk
			FROM (student_enrollment as se join classroom as c on se.classroom_fk = c.id ) join student_identification as si on se.student_fk = si.id
			JOIN student_documents_and_address as sd on(si.id=sd.id)
			where c.school_year = $year order by si.name";
        $sql2 = "SELECT DISTINCT id.id, id.school_inep_id_fk , id.inep_id , id.name , id.birthday_date as birthday , id.filiation_1 , id.filiation_2 , id.edcenso_uf_fk , id.edcenso_city_fk
                FROM (instructor_teaching_data as it join classroom as c on it.classroom_id_fk = c.id ) join instructor_identification as id on it.instructor_fk = id.id
                where c.school_year = $year order by id.name";
        $instructors = Yii::app()->db->createCommand($sql2)->queryAll();
        $students = Yii::app()->db->createCommand($sql)->queryAll();

        if (count($students) == 0) {
            echo "N&atilde;o h&aacute; alunos cadastrados nesta escola no ano de " . $year;
        } else {
            $fileName = "inepid.txt";
            $fileDir = "./app/export/" . $fileName;
            $file = fopen($fileDir, 'w');
            $linha = $this->mountItemExport($students);
            fwrite($file, $linha);
            $linha = $this->mountItemExport($instructors);
            fwrite($file, $linha);
            fclose($file);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileDir));
            readfile($fileDir);
        }


        if (count($instructors) == 0) {
            echo "N&atilde;o h&aacute; professores cadastrados nesta escola no ano de " . $year;
        } else {
            $fileName = date("Y-i-d") . "_instructors_without_inep_id.txt";
            $fileDir = "./app/export/" . $fileName;
            $file = fopen($fileDir, 'w');


            fclose($file);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileDir));
            readfile($fileDir);
        }
    }

    public function mountItemExport($itens)
    {
        $linha = "";
        foreach ($itens as $s) {
            if ($s['id'] == null) {
                $linha .= "||";
            } else {
                $linha .= $s['id'] . "|";
            }

            if ($s['cpf'] == null) {
                $linha .= "|";
            } else {
                $linha .= $s['cpf'] . "|";
            }

            if ($s['civil_register_enrollment_number'] == null) {
                $linha .= "|";
            } else {
                $fill = true;
                if (strlen($s['civil_register_enrollment_number']) != 32) {
                    $fill = false;
                }
                if ($fill && strlen($s['civil_register_enrollment_number']) == 32) {
                    for ($i = 0; $i <= strlen($s['civil_register_enrollment_number']) - 1; $i++) {
                        $char = $s['civil_register_enrollment_number'][$i];
                        if (($i < 30 && !is_numeric($char)) || ($i >= 30 && (!is_numeric($char) && strtoupper($char) != "X"))) {
                            $fill = false;
                            break;
                        }
                    }
                }
                if ($fill && substr($s['civil_register_enrollment_number'], 10, 4) > date("Y")) {
                    $fill = false;
                }
                if ($fill && substr($s['civil_register_enrollment_number'], 10, 4) < substr($s['birthday'], 6, 4)) {
                    $fill = false;
                }
                $linha .= $fill ? strtoupper($s['civil_register_enrollment_number']) . "|" : "|";
            }

            $s['name'] = preg_replace("/[^A-Z ]/", "", htmlentities(strtoupper($s['name'])));
            if ($s['name'] == null) {
                $linha .= "|";
            } else {
                $linha .= strtoupper($s['name']) . "|";
            }

            if ($s['birthday'] == null) {
                $linha .= "|";
            } else {
                $linha .= $s['birthday'] . "|";
            }

            $s['filiation_1'] = preg_replace("/[^A-Z ]/", "", htmlentities(strtoupper($s['filiation_1'])));
            $s['filiation_2'] = preg_replace("/[^A-Z ]/", "", htmlentities(strtoupper($s['filiation_2'])));
            if ($s['filiation_1'] == null) {
                $linha .= "|";
            } else {
                $linha .= strtoupper($s['filiation_1']) . "|";
            }

            if ($s['filiation_2'] == null) {
                $linha .= "|";
            } else {
                $linha .= strtoupper($s['filiation_2']) . "|";
            }

            if ($s['edcenso_city_fk'] == null) {
                $linha .= "2806305|";
            } else {
                $linha .= $s['edcenso_city_fk'] . "|";
            }

            $linha .= "\n";
        }
        return $linha;
    }

    public function normalizeField2019($register, $attributes)
    {
        $reg = $register;
        if ($register == 0) {
            $attributes['id'] = $attributes['inep_id'];
            $pos = "a";
        } else if ($register == 10) {
            $attributes['id'] = $attributes['school_inep_id_fk'];
            $pos = "b";
            $ordens = EdcensoAlias::model()->findAllByAttributes(["register" => $register]);
            foreach ($ordens as $kord => $ord) {
                $evalin = '$this->tmpexp["' . $pos . '"][' . $attributes['id'] . '][' . $ord->corder . '] = "' . $ord->default . '";';
                eval($evalin);
            }
        } else if ($register == 20) {
            $pos = "c";
        }
        if ($register == 60 || $register == 70) {
            $register = 301;
            $pos = "d";
            $attributes['register_type'] = 30;
            $ordens = EdcensoAlias::model()->findAllByAttributes(["register" => $register]);
            if ($reg == 60) {
                foreach ($ordens as $kord => $ord) {
                    $evalin = '$this->tmpexp["' . $pos . '"][' . $attributes['id'] . '][' . $ord->corder . '] = "' . $ord->default . '";';
                    eval($evalin);
                }
            }

        } elseif ($register == 30 || $register == 40 || $register == 50) {
            $register = 302;
            $pos = "e";
            $attributes['register_type'] = 30;
            $ordens = EdcensoAlias::model()->findAllByAttributes(["register" => $register]);
            if ($reg == 30) {
                foreach ($ordens as $kord => $ord) {
                    $evalin = '$this->tmpexp["' . $pos . '"][' . $attributes['id'] . '][' . $ord->corder . '] = "' . $ord->default . '";';
                    eval($evalin);
                }
            }
        } elseif ($register == 80) {
            $register = 60;
            $pos = "h";
            $attributes['register_type'] = 60;
        } elseif ($register == 51) {
            $register = 50;
            $pos = "g";
            $attributes['register_type'] = 50;
            $ordens = EdcensoAlias::model()->findAllByAttributes(["register" => $register]);
            foreach ($ordens as $kord => $ord) {
                $evalin = '$this->tmpexp["' . $pos . '"][' . $attributes['id'] . '][' . $ord->corder . '] = "' . $ord->default . '";';
                eval($evalin);
            }
        }
        //@todo $register = 40;
        $attributes = $this->fixMistakesExport($reg, $attributes);
        foreach ($attributes as $key => $attr) {
            $ordem = EdcensoAlias::model()->findAllByAttributes(["register" => $register, "attr" => $key])[0];
            if (isset($ordem->corder)) {
                if (($reg == 70 || $reg == 40) && $key == 'edcenso_city_fk') {
                    $eval = '$this->tmpexp["' . $pos . '"][' . $attributes['id'] . '][44] = "' . $attr . '";';
                    eval($eval);
                } elseif (($reg == 60 || $reg == 30) && $key == 'edcenso_city_fk') {
                    $eval = '$this->tmpexp["' . $pos . '"][' . $attributes['id'] . '][15] = "' . $attr . '";';
                    eval($eval);
                } else {
                    $eval = '$this->tmpexp["' . $pos . '"][' . $attributes['id'] . '][' . $ordem->corder . '] = "' . $attr . '";';
                    eval($eval);
                }

            }
        }


    }

    public function normalizeFields($register, $attributes)
    {
        $FIELDS = array(
            '00' => array(
                'COLUMNS' => 42,
            ),
            '10' => array(
                'COLUMNS' => 107,
            ),
            '20' => array(
                'COLUMNS' => 65,
            ),
            '20' => array(
                'COLUMNS' => 65,
            ),
            '30' => array(
                'COLUMNS' => 26,
            ),
            '40' => array(
                'COLUMNS' => 13,
            ),
            '50' => array(
                'COLUMNS' => 43,
            ),
            '51' => array(
                'COLUMNS' => 21,
            ),
            '60' => array(
                'COLUMNS' => 39,
            ),
            '70' => array(
                'COLUMNS' => 29,
            ),
            '80' => array(
                'COLUMNS' => 24,
            ),
        );
        $attributes = $this->fixMistakesExport($register, $attributes);
        $qtdcolumns = $FIELDS[$register]['COLUMNS'];
        $qtdattrs = count($attributes);
        $total = $qtdattrs - $qtdcolumns;
        for ($i = 1; $i <= $total; $i++) {
            array_pop($attributes);
        }

        if (count($attributes)) {
            $this->export .= implode('|', $attributes);
            $this->export .= "\n";
        }
    }

    public function certVerify($codigo)
    {
        $result = str_split($codigo);
        $result = array_reverse($result);
        $cont = 9;
        foreach ($result as $r) {
            while ($cont >= 0) {
                $calculo = "$cont * $r";
                $calc = ($cont * $r);
                $total = $total + $calc;
                $cont--;
                if ($cont < 0) {
                    $cont = 10;
                    break;
                }
                break;
            }
        }
        $cont = 9;
        $valor = 0;
        $digUm = $total % 11;
        if ($digUm == 10) {
            $digUm = 1;
        }
        foreach ($result as $r) {
            while ($cont >= 0) {
                if ($valor == 0) {
                    $valor = 1;
                    $calc = ($cont * $digUm);
                    $total2 = $total2 + $calc;
                    $cont--;
                    if ($cont < 0) {
                        $cont = 10;
                        break;
                    }
                }
                $calc = ($cont * $r);
                $total2 = $total2 + $calc;
                $cont--;
                if ($cont < 0) {
                    $cont = 10;
                    break;
                }
                break;
            }
        }
        $digDois = $total2 % 11;
        if ($digDois == 10) {
            $digDois = 1;
        }
        $return = $digUm . $digDois;
        return $return;

    }

    public function sanitizeString($string)
    {
        $what = array('ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç', ' ', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', 'º');
        $by = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '', '');
        return str_replace($what, $by, $string);
    }

    public function findDisc($id)
    {
        $modelTeachingData = Classroom::model()->findByPk($id)->instructorTeachingDatas;
        $teachingDataDisciplines = array();
        foreach ($modelTeachingData as $key => $model) {
            $disciplines = ClassroomController::teachingDataDiscipline2array($model);
            foreach ($disciplines as $discipline) {
                if ($discipline->id > 99) {
                    $teachingDataDisciplines[99] = 99;
                }
                $teachingDataDisciplines[$discipline->id] = $discipline->id;
            }
        }
        return $teachingDataDisciplines;
    }

    public function fixMistakesExport($register, $attributes)
    {
        switch ($register) {
            case '00':
                foreach ($attributes as $i => $attr) {
                    if (empty($attr)) {
                        $ordem = EdcensoAlias::model()->findAllByAttributes(["register" => $register, "attr" => $i])[0];
                        $attributes[$i] = $ordem->default;
                    }
                }
                $attributes['initial_date'] = '25/02/2019';
                $attributes['final_date'] = '12/12/2019';
                if ($attributes['situation'] == '1') {
                    $attributes['regulation'] = '2';
                }
                /*if($attributes['ddd'] == '79'){
							$attributes['ddd'] = '079';
						}*/


                if (empty($attributes['inep_head_school'])) {
                    $attributes['offer_or_linked_unity'] = '0';
                }

                // Validação da latitude e longitude
                if (!empty($attributes['latitude']) && !empty($attributes['longitude'])) {
                    $attributes['latitude'] = str_replace(',', '.', $attributes['latitude']);
                    $attributes['longitude'] = str_replace(',', '.', $attributes['longitude']);

                    if (!($attributes['latitude'] >= -33.75208 && $attributes['latitude'] <= 5.271841 && $attributes['longitude'] >= -73.99045 && $attributes['longitude'] <= -32.39091)) {
                        $attributes['latitude'] = $attributes['longitude'] = '';
                    }

                } else {
                    $attributes['latitude'] = $attributes['longitude'] = '';
                }

                // Validação do distrito - Reimportar as tabelas de distrito
                if (!empty($attributes['edcenso_district_fk'])) {
                    $scholl = SchoolIdentification::model()->findByPk($attributes['inep_id']);
                    $attributes['edcenso_district_fk'] = $scholl->edcensoDistrictFk->code;
                    $attributes['edcenso_district_fk'] = 05;
                }


                break;
            case '10':
                foreach ($attributes as $i => $attr) {
                    if (empty($attr)) {
                        $attributes[$i] = 0;
                    }
                }
                $itens = ['equipments_tv', 'equipments_vcr', 'equipments_dvd',
                    'equipments_overhead_projector', 'equipments_stereo_system',
                    'equipments_data_show', 'equipments_printer', 'equipments_fax', 'equipments_camera', 'administrative_computers_count',
                    'student_computers_count', 'internet_access', 'bandwidth',
                    'workers_administrative_assistant', 'workers_service_assistant',
                    'workers_librarian', 'workers_firefighter', 'workers_coordinator_shift',
                    'workers_speech_therapist', 'workers_nutritionist', 'workers_psychologist',
                    'workers_cooker', 'workers_support_professionals', 'workers_school_secretary',
                    'workers_security_guards', 'workers_monitors', 'board_organ_association_parent',
                    'board_organ_association_parentinstructors', 'board_organ_board_school',
                    'board_organ_student_guild', 'board_organ_others', 'board_organ_inexistent',
                    'ppp_updated', 'dependencies_outside_roomspublic', 'dependencies_climate_roomspublic',
                    'dependencies_acessibility_roomspublic', 'equipments_qtd_blackboard', 'internet_access_local_cable',
                    'internet_access_local_wireless', 'internet_access_local_inexistet', 'equipments_computer' .
                    'garbage_destination_throw_away', 'treatment_garbage_parting_garbage',
                    'treatment_garbage_resuse', 'garbage_destination_recycle', 'equipments_qtd_notebookstudent',
                    'equipments_qtd_tabletstudent', 'internet_access_administrative',
                    'internet_access_educative_process', 'internet_access_student',
                    'internet_access_community', 'internet_access_inexistent',
                    'treatment_garbage_parting_garbage', 'traetment_garbage_inexistent',
                    'acessabilty_inexistent', 'acessability_visual_signaling',
                    'acessability_tactile_singnaling', 'acessability_sound_signaling',
                    'acessability_ramps', 'acessability_doors_80cm', 'acessability_tactile_floor',
                    'acessability_elevator', 'acessability_handrails_guardrails', 'dependencies_covered_patio',
                    'dependencies_principal_room', 'dependencies_instructors_room', 'dependencies_aee_room',
                    'equipments_copier', 'dependencies_uncovered_patio'
                ];
                foreach ($itens as $item) {
                    if ($attributes[$item] == 0) {
                        $attributes[$item] = '';
                    }
                }

                if ($attributes['native_education'] != 1) {
                    $attributes['native_education_language_native'] = '';
                    $attributes['native_education_language_portuguese'] = '';
                    $attributes['edcenso_native_languages_fk'] = '';
                    $attributes['edcenso_native_languages_fk2'] = '';
                    $attributes['edcenso_native_languages_fk3'] = '';
                }
                if ($attributes['select_adimission'] != 1) {
                    $attributes['booking_enrollment_self_declaredskin'] = '';
                    $attributes['booking_enrollment_income'] = '';
                    $attributes['booking_enrollment_public_school'] = '';
                    $attributes['booking_enrollment_disabled_person'] = '';
                    $attributes['booking_enrollment_others'] = '';
                    $attributes['booking_enrollment_inexistent'] = '';
                }
                if ($attributes['shared_building_with_school'] != 1) {
                    $attributes['shared_school_inep_id_1'] = '';
                    $attributes['shared_school_inep_id_2'] = '';
                    $attributes['shared_school_inep_id_3'] = '';
                    $attributes['shared_school_inep_id_4'] = '';
                    $attributes['shared_school_inep_id_5'] = '';
                    $attributes['shared_school_inep_id_6'] = '';
                }

                // Validação do ensino fundamental em ciclos
                $classrooms = Classroom::model()->with(array(
                    'edcensoStageVsModalityFk' => array(
                        'select' => false,
                        'joinType' => 'INNER JOIN',
                        'condition' => 'edcensoStageVsModalityFk.stage IN (4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 41, 56)',
                    )
                ))->findAllByAttributes(["school_inep_fk" => yii::app()->user->school, "school_year" => Yii::app()->user->year]);

                if (count($classrooms) == 0) {
                    $attributes['basic_education_cycle_organized'] = '';
                }
                foreach ($attributes as $i => $attr) {
                    if ($attr == '') {
                        $ordem = EdcensoAlias::model()->findAllByAttributes(["register" => $register, "attr" => $i])[0];
                        $attributes[$i] = $ordem->default;
                    }
                }

                break;
            case '80':
                /*$classroom = Classroom::model()->findByPk($attributes['classroom_fk']);
						if($classroom->edcensoStageVsModalityFk->stage == 6){
							$attributes['edcenso_stage_vs_modality_fk'] = $classroom->edcenso_stage_vs_modality_fk;
						}*/
                $classroom = Classroom::model()->findByPk($attributes['classroom_fk']);
                if ($classroom->assistance_type == '5') {
                    $attributes['another_scholarization_place'] = '';
                    $attributes['edcenso_stage_vs_modality_fk'] = '';
                }

                $classroom = Classroom::model()->findByPk($attributes['classroom_fk']);
                if ($classroom->edcensoStageVsModalityFk->stage == 1) {
                    //	$attributes['another_scholarization_place'] = '';
                }

                if ($classroom->aee == 0) {
                    foreach ($attributes as $i => $attr) {
                        $pos = strstr($i, 'aee_');
                        if ($pos) {
                            $attributes[$i] = '';
                        }
                    }
                    //	$attributes['another_scholarization_place'] = '';
                } else {
                    foreach ($classroom->attributes as $i => $attr) {
                        $pos = strstr($i, 'aee_');
                        if ($pos) {
                            $attributes[$i] = $attr;
                        }
                    }
                    $attributes['public_transport'] = '';
                    $attributes['vehicle_type_van'] = '';
                    $attributes['vehicle_type_microbus'] = '';
                    $attributes['vehicle_type_bus'] = '';
                    $attributes['vehicle_type_bike'] = '';
                    $attributes['vehicle_type_animal_vehicle'] = '';
                    $attributes['vehicle_type_other_vehicle'] = '';
                    $attributes['vehicle_type_waterway_boat_5'] = '';
                    $attributes['vehicle_type_waterway_boat_5_15'] = '';
                    $attributes['vehicle_type_waterway_boat_15_35'] = '';
                    $attributes['vehicle_type_waterway_boat_35'] = '';
                    $attributes['vehicle_type_metro_or_train'] = '';
                    $attributes['transport_responsable_government'] = '';

                }


                //@todo corrigir na base e no codigo depois
                if ($attributes['another_scholarization_place'] == '3') {
                    $attributes['another_scholarization_place'] = '1';
                } elseif ($attributes['another_scholarization_place'] == '1') {
                    $attributes['another_scholarization_place'] = '2';
                }

                if ($classroom->edcensoStageVsModalityFk->id != 3 &&
                    $classroom->edcensoStageVsModalityFk->id != 12 &&
                    $classroom->edcensoStageVsModalityFk->id != 13 &&
                    $classroom->edcensoStageVsModalityFk->id != 22 &&
                    $classroom->edcensoStageVsModalityFk->id != 23 &&
                    $classroom->edcensoStageVsModalityFk->id != 24 &&
                    $classroom->edcensoStageVsModalityFk->id != 72 &&
                    $classroom->edcensoStageVsModalityFk->id != 56 &&
                    $classroom->edcensoStageVsModalityFk->id != 64) {
                    $attributes['edcenso_stage_vs_modality_fk'] = '';
                }
                if ($classroom->edcensoStageVsModalityFk->id != 3) {
                    $attributes['unified_class'] = '';
                }
                //se a turma já tiver etapa não enviar a etapa do aluno.

                if ($attributes['public_transport'] == 0) {
                    //@todo fazer codigo que mudar a flag de 1 e 0 para 1 ou -1 se transporte foi setado
                    //@todo subtituir todos os valores -1 para String Vazia.
                    $attributes['vehicle_type_van'] = '';
                    $attributes['vehicle_type_microbus'] = '';
                    $attributes['vehicle_type_bus'] = '';
                    $attributes['vehicle_type_bike'] = '';
                    $attributes['vehicle_type_animal_vehicle'] = '';
                    $attributes['vehicle_type_other_vehicle'] = '';
                    $attributes['vehicle_type_waterway_boat_5'] = '';
                    $attributes['vehicle_type_waterway_boat_5_15'] = '';
                    $attributes['vehicle_type_waterway_boat_15_35'] = '';
                    $attributes['vehicle_type_waterway_boat_35'] = '';
                    $attributes['vehicle_type_metro_or_train'] = '';
                    $attributes['transport_responsable_government'] = '';
                } else {
                    $attributes['vehicle_type_van'] = '0';
                    $attributes['vehicle_type_microbus'] = '0';
                    $attributes['vehicle_type_bus'] = '0';
                    $attributes['vehicle_type_bike'] = '0';
                    $attributes['vehicle_type_animal_vehicle'] = '0';
                    $attributes['vehicle_type_other_vehicle'] = '0';
                    $attributes['vehicle_type_waterway_boat_5'] = '0';
                    $attributes['vehicle_type_waterway_boat_5_15'] = '0';
                    $attributes['vehicle_type_waterway_boat_15_35'] = '0';
                    $attributes['vehicle_type_waterway_boat_35'] = '0';
                    $attributes['vehicle_type_metro_or_train'] = '0';
                    $attributes['transport_responsable_government'] = '0';
                    $isset = 0;
                    foreach ($attributes as $i => $attr) {
                        $pos = strstr($i, 'vehicle_type_');
                        if ($pos) {
                            if (!empty($attributes[$i])) {
                                $isset = 1;
                            }
                        }
                    }
                    if (empty($isset)) {
                        $attributes['vehicle_type_bus'] = '1';
                        $attributes['transport_responsable_government'] = '2';
                    }
                }

                if (empty($attributes['student_inep_id'])) {
                    $student = StudentIdentification::model()->findByPk($attributes['student_fk']);
                    if (!is_null($student)) {
                        $attributes['student_inep_id'] = $student->inep_id;
                    }
                }

                break;
            case '60':
                if (!empty($attributes['inep_id'])) {
                    if (strlen($attributes['inep_id']) < 9) {
                        $attributes['inep_id'] = '';
                    }
                }
                $attributes['name'] = strtoupper($this->fixName($attributes['name']));
                $attributes['filiation_1'] = strtoupper($this->fixName($attributes['filiation_1']));
                $attributes['filiation_2'] = strtoupper($this->fixName($attributes['filiation_2']));


                if ($attributes['deficiency'] == 0) {
                    $attributes['deficiency_type_blindness'] = '';
                    $attributes['deficiency_type_low_vision'] = '';
                    $attributes['deficiency_type_deafness'] = '';
                    $attributes['deficiency_type_disability_hearing'] = '';
                    $attributes['deficiency_type_deafblindness'] = '';
                    $attributes['deficiency_type_phisical_disability'] = '';
                    $attributes['deficiency_type_intelectual_disability'] = '';
                    $attributes['deficiency_type_multiple_disabilities'] = '';
                    $attributes['deficiency_type_autism'] = '';
                    $attributes['deficiency_type_aspenger_syndrome'] = '';
                    $attributes['deficiency_type_rett_syndrome'] = '';
                    $attributes['deficiency_type_childhood_disintegrative_disorder'] = '';
                    $attributes['deficiency_type_gifted'] = '';
                    $attributes['resource_none'] = '';
                    $attributes['resource_aid_lector'] = '';
                    $attributes['resource_aid_transcription'] = '';
                    $attributes['resource_interpreter_guide'] = '';
                    $attributes['resource_interpreter_libras'] = '';
                    $attributes['resource_lip_reading'] = '';
                    $attributes['resource_zoomed_test_16'] = '';
                    $attributes['resource_zoomed_test_20'] = '';
                    $attributes['resource_zoomed_test_24'] = '';
                    $attributes['resource_braille_test'] = '';
                    $attributes['resource_zoomed_test_18'] = '';
                    $attributes['resource_cd_audio'] = '';
                    $attributes['resource_proof_language'] = '';
                    $attributes['resource_video_libras'] = '';

                } else {
                    $existone = false;
                    foreach ($attributes as $i => $attr) {
                        $pos = strstr($i, 'deficiency_');
                        if ($pos) {
                            if (empty($attributes[$i])) {
                                $attributes[$i] = '0';
                            }
                        }
                        $pos2 = strstr($i, 'resource_');
                        if ($pos2) {
                            if (empty($attributes[$i])) {
                                $attributes[$i] = '0';
                                if (!$existone) {
                                    $attributes['resource_none'] = '1';
                                }
                            } else {
                                if ($i != 'resource_none') {
                                    $existone = true;
                                    $attributes['resource_none'] = '0';
                                }
                            }
                        }

                    }
                    if (!empty($attributes['deficiency_type_gifted'])) {
                        $attributes['resource_none'] = '';
                        $attributes['resource_aid_lector'] = '';
                        $attributes['resource_aid_transcription'] = '';
                        $attributes['resource_interpreter_guide'] = '';
                        $attributes['resource_interpreter_libras'] = '';
                        $attributes['resource_lip_reading'] = '';
                        $attributes['resource_zoomed_test_16'] = '';
                        $attributes['resource_zoomed_test_20'] = '';
                        $attributes['resource_zoomed_test_24'] = '';
                        $attributes['resource_braille_test'] = '';
                    }


                }
                break;
            case '30':
                $attributes['name'] = strtoupper($this->fixName($attributes['name']));
                $attributes['filiation_1'] = strtoupper($this->fixName($attributes['filiation_1']));
                $attributes['filiation_2'] = strtoupper($this->fixName($attributes['filiation_2']));

                if (!empty($attributes['filiation_1']) && $attributes['filiation'] == 0) {
                    $attributes['filiation'] = 1;
                } else if ($attributes['filiation'] == 0) {
                    $attributes['filiation_1'] = '';
                    $attributes['filiation_2'] = '';
                }

                if ($attributes['deficiency'] == 0) {
                    $attributes['deficiency_type_blindness'] = '';
                    $attributes['deficiency_type_low_vision'] = '';
                    $attributes['deficiency_type_deafness'] = '';
                    $attributes['deficiency_type_disability_hearing'] = '';
                    $attributes['deficiency_type_deafblindness'] = '';
                    $attributes['deficiency_type_phisical_disability'] = '';
                    $attributes['deficiency_type_intelectual_disability'] = '';
                    $attributes['deficiency_type_multiple_disabilities'] = '';
                    $attributes['deficiency_type_autism'] = '';
                    $attributes['deficiency_type_gifted'] = '';
                }
                $attributes['nis'] = '';
                $attributes['email'] = '';
                //$attributes['email'] = strtoupper($attributes['email']);
                break;
            case '70':
                if (empty($attributes['address'])) {
                    //$attributes['cep'] = '';
                    //$attributes['edcenso_city_fk'] = '';
                    $attributes['edcenso_uf_fk'] = '';
                    $attributes['number'] = '';
                    $attributes['complement'] = '';
                    $attributes['neighborhood'] = '';
                }
                if (empty($attributes['cep'])) {
                    $attributes['address'] = '';
                    //$attributes['edcenso_city_fk'] = '';
                    $attributes['edcenso_uf_fk'] = '';
                    $attributes['number'] = '';
                    $attributes['complement'] = '';
                    $attributes['neighborhood'] = '';

                }
                if (empty($attributes['cep']) && isset($attributes['edcenso_city_fk'])) {
                    $attributes['edcenso_city_fk'] = '';
                }
                if (!empty($attributes['cep']) && !isset($attributes['edcenso_city_fk'])) {
                    $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
                    $attributes['edcenso_city_fk'] = $school->edcenso_city_fk;
                }
                if (!empty($attributes['cep']) && !isset($attributes['edcenso_uf_fk'])) {
                    $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
                    $attributes['edcenso_uf_fk'] = $school->edcenso_uf_fk;
                }

                $attributes['civil_register_enrollment_number'] = strtoupper($attributes['civil_register_enrollment_number']);

                if ($attributes['civil_certification'] == 1) {
                    if (empty($attributes['civil_certification_type'])) {
                        $attributes['civil_certification_type'] = '1';
                    }
                    $attributes['civil_register_enrollment_number'] = '';
                } else if ($attributes['civil_certification'] == 2) {
                    $attributes['civil_certification_type'] = '';
                    $attributes['civil_certification_term_number'] = '';
                    $attributes['civil_certification_sheet'] = '';
                    $attributes['civil_certification_book'] = '';
                    $attributes['civil_certification_date'] = '';
                } else {
                    $attributes['civil_register_enrollment_number'] = '';
                    $attributes['civil_certification_type'] = '';
                    $attributes['civil_certification_term_number'] = '';
                    $attributes['civil_certification_sheet'] = '';
                    $attributes['civil_certification_book'] = '';
                    $attributes['civil_certification_date'] = '';
                }
                if (empty($attributes['civil_certification'])) {
                    $attributes['civil_certification_type'] = '';

                }

                if (empty($attributes['rg_number'])) {
                    $attributes['rg_number_edcenso_organ_id_emitter_fk'] = '';
                    $attributes['rg_number_edcenso_uf_fk'] = '';
                    $attributes['rg_number_expediction_date'] = '';
                }
                if ($attributes['civil_certification'] == '2') {
                    $cert = substr($attributes['civil_register_enrollment_number'], 0, 30);
                    $testX = str_split($attributes['civil_register_enrollment_number']);
                    if ($testX[29] == 'x') {
                        $cert = substr($attributes['civil_register_enrollment_number'], 0, 29);
                        $cert = $cert . '0';
                    }
                    $certDv = $this->certVerify($cert);
                    $attributes['civil_register_enrollment_number'] = $cert . '' . $certDv;
                }
                if (empty($attributes['nis']) && empty($attributes['cpf']) && empty($attributes['civil_register_enrollment_number'])) {
                    $attributes['no_document_desc'] = 2;
                }


                break;
            case '50':
                $course1 = EdcensoCourseOfHigherEducation::model()->findByPk($attributes['high_education_course_code_1_fk']);
                if ($course1->degree == 'Licenciatura') {
                    $attributes['high_education_formation_1'] = '';
                }
                $course2 = EdcensoCourseOfHigherEducation::model()->findByPk($attributes['high_education_course_code_2_fk']);
                if ($course2->degree == 'Licenciatura') {
                    $attributes['high_education_formation_2'] = '';
                }
                if (isset($attributes['high_education_course_code_1_fk']) && empty($attributes['high_education_institution_code_1_fk'])) {
                    $attributes['high_education_institution_code_1_fk'] = '9999999';
                }

                $setothers = false;
                foreach ($attributes as $i => $attr) {
                    $pos = strstr($i, 'other_courses_');
                    if ($pos) {
                        //echo $i.'---'.$attributes[$i].'<br>';
                        if (empty($attributes[$i])) {
                            $attributes[$i] = '0';
                        } else {
                            //echo $i.'---'.$attributes[$i].'<br>';
                            if ($i != 'other_courses_none') {
                                //echo $i.'---'.$attributes[$i].'<br>';
                                $setothers = true;
                            }
                        }
                    }
                }
                if ($setothers) {
                    $attributes['other_courses_none'] = '0';
                } else {
                    $attributes['other_courses_none'] = '1';
                }
                /**
                 * $setothers = false;
                 * foreach ($attributes as $i => $attr){
                 * $pos = strstr($i, 'other_courses_');
                 * if (($pos) && !empty($attributes[$i])) {
                 * $setothers = true;
                 * }elseif(empty($attributes[$i])){
                 * $attributes[$i] = '';
                 * }
                 * }
                 * if($setothers){
                 * foreach ($attributes as $i => $attr){
                 * $pos = strstr($i, 'other_courses_');
                 * if ($pos) {
                 * if(empty($attributes[$i])){
                 * $attributes[$i] = '0';
                 * }
                 * }
                 * }
                 * }**/

                if ($attributes['high_education_situation_1'] == '2') {
                    $attributes['scholarity'] = 7;
                }

                if ($attributes['scholarity'] == 7) {
                    $attributes['high_education_situation_1'] = '1';
                } else {
                    $attributes['high_education_situation_1'] = '';
                }
                if ($attributes['scholarity'] != 6) {
                    $attributes['post_graduation_specialization'] = '';
                    $attributes['post_graduation_master'] = '';
                    $attributes['post_graduation_doctorate'] = '';
                    $attributes['post_graduation_none'] = '';
                    $attributes['high_education_course_code_1_fk'] = '';
                    $attributes['high_education_final_year_1'] = '';
                    $attributes['high_education_institution_code_1_fk'] = '';
                } else {
                    if (empty($attributes['post_graduation_specialization'])) {
                        $attributes['post_graduation_specialization'] = 0;
                    } elseif (empty($attributes['post_graduation_master'])) {
                        $attributes['post_graduation_master'] = 0;
                    } elseif (empty($attributes['post_graduation_doctorate'])) {
                        $attributes['post_graduation_doctorate'] = 0;
                    }
                    if (empty($attributes['post_graduation_specialization']) && empty($attributes['post_graduation_master']) && empty($attributes['post_graduation_doctorate'])) {
                        $attributes['post_graduation_none'] = '1';
                    }
                }


                if ($attributes['post_graduation_none'] == '1') {
                    $attributes['post_graduation_specialization'] = '0';
                    $attributes['post_graduation_master'] = '0';
                    $attributes['post_graduation_doctorate'] = '0';
                }
                if (empty($attributes['high_education_course_code_1_fk'])
                    || empty($attributes['high_education_final_year_1'])) {
                    $attributes['post_graduation_specialization'] = '';
                    $attributes['post_graduation_master'] = '';
                    $attributes['post_graduation_doctorate'] = '';
                    $attributes['post_graduation_none'] = '';
                    $attributes['high_education_formation_1'] = '';
                } else {
                    if (empty($attributes['post_graduation_specialization'])) {
                        $attributes['post_graduation_specialization'] = '0';
                    } elseif (empty($attributes['post_graduation_master'])) {
                        $attributes['post_graduation_master'] = '0';
                    } elseif (empty($attributes['post_graduation_doctorate'])) {
                        $attributes['post_graduation_doctorate'] = '0';
                    } elseif (empty($attributes['post_graduation_none'])) {
                        $attributes['post_graduation_none'] = '1';
                    }

                }
                if ($attributes['high_education_situation_2'] == 2
                    || empty($attributes['high_education_situation_2'])) {
                    $attributes['high_education_formation_2'] = '';
                }
                if ($attributes['high_education_situation_3'] == 2
                    || empty($attributes['high_education_situation_3'])) {
                    $attributes['high_education_formation_3'] = '';
                }
                break;
            case '51':
                /*
					 * O campo "Código da disciplina 4" não pode ser preenchido quando a turma à qual o docente
					 * está vinculado for de Educação infantil ou EJA - Fundamental - Projovem urbano.
					 */
                $classroom = Classroom::model()->findByPk($attributes['classroom_id_fk']);
                if ($classroom->edcenso_stage_vs_modality_fk == 1 ||
                    $classroom->edcenso_stage_vs_modality_fk == 2 ||
                    $classroom->edcenso_stage_vs_modality_fk == 3 ||
                    $classroom->edcenso_stage_vs_modality_fk == 65) {
                    foreach ($attributes as $i => $attr) {
                        $pos = strstr($i, 'discipline');
                        if ($pos) {
                            $attributes[$i] = '';
                        }
                    }
                }
                $countdisc = 1;
                foreach ($attributes as $i => $attr) {
                    $pos = strstr($i, 'discipline');
                    if ($pos) {
                        if (($attributes[$i] >= 99)) {
                            if ($countdisc == 1) {
                                $attributes[$i] = 99;
                            } else {
                                $attributes[$i] = '';
                            }
                            $countdisc++;
                        }
                    }
                }
                if ($attributes['role'] != '1' && $attributes['role'] != '5' && $attributes['role'] != '6') {
                    $attributes['contract_type'] = '';
                }

                break;
            case '20':
                $attributes['name'] = strtoupper($this->sanitizeString($attributes['name']));
                $attributes['filiation_1'] = strtoupper($this->fixName($attributes['filiation_1']));
                $attributes['filiation_2'] = strtoupper($this->fixName($attributes['filiation_2']));
                $dteacher = $this->findDisc($attributes['id']);
                $dclass = ClassroomController::classroomDiscipline2array2();
                $classroom = Classroom::model()->findByPk($attributes['id']);
                foreach ($attributes as $i => $attr) {
                    $pos = strstr($i, 'discipline');
                    if ($pos) {
                        $attributes[$i] = '';
                        if (isset($dteacher[$dclass[$i]])) {
                            $attributes[$i] = '1';
                        }

                    }
                }
                if ($attributes['assistance_type'] != '5') {
                    foreach ($attributes as $i => $attr) {
                        $pos = strstr($i, 'aee_');
                        if ($pos) {
                            $attributes[$i] = '';
                        }
                    }
                }
                $stage = EdcensoStageVsModality::model()->findByPk($attributes['edcenso_stage_vs_modality_fk']);
                if ($stage->stage == '6') {
                    $attributes['mais_educacao_participator'] = '';
                }
                if ($attributes['edcenso_stage_vs_modality_fk'] <= 4 &&
                    $attributes['edcenso_stage_vs_modality_fk'] >= 38 &&
                    $attributes['edcenso_stage_vs_modality_fk'] != 41) {
                    $attributes['mais_educacao_participator'] = '';
                }
                if ($attributes['edcenso_stage_vs_modality_fk'] == 1 ||
                    $attributes['edcenso_stage_vs_modality_fk'] == 2 ||
                    $attributes['edcenso_stage_vs_modality_fk'] == 3 ||
                    $attributes['edcenso_stage_vs_modality_fk'] == 65) {
                    foreach ($attributes as $i => $attr) {
                        $pos = strstr($i, 'discipline');
                        if ($pos) {
                            $attributes[$i] = '';
                        }
                    }
                    $attributes['mais_educacao_participator'] = '';
                } else {
                    if (!isset($attributes['mais_educacao_participator'])) {
                        $attributes['mais_educacao_participator'] = 0;
                    }
                    foreach ($attributes as $i => $attr) {
                        $pos = strstr($i, 'discipline');
                        if ($pos) {
                            if (empty($attributes[$i])) {
                                $attributes[$i] = '0';
                            }
                        }
                    }
                }
                if ($attributes['assistance_type'] == '5') {
                    $attributes['mais_educacao_participator'] = '';
                    $attributes['edcenso_stage_vs_modality_fk'] = '';
                    $attributes['modality'] = '';
                    foreach ($attributes as $i => $attr) {
                        $pos = strstr($i, 'discipline');
                        if ($pos) {
                            $attributes[$i] = '';
                        }
                    }
                }

                /*
						Verifica se a turma possui proffisional e alunos vinculado
						Deve permanecer no final das validações do registro 20
					*/
                foreach ($attributes as $i => $attr) {
                    if ($attr == '') {
                        $ordem = EdcensoAlias::model()->findAllByAttributes(["register" => $register, "attr" => $i])[0];
                        $attributes[$i] = $ordem->default;
                    }
                }

                if (isset($classroom) && (count($classroom->instructorTeachingDatas) < 1 && count($classroom->studentEnrollments) < 1)) {
                    $attributes = [];
                }

                break;
            case '40':
                if (empty($attributes['cep'])) {
                    $attributes['address'] = '';
                    $attributes['edcenso_city_fk'] = '';
                    $attributes['edcenso_uf_fk'] = '';
                }
                if (!empty($attributes['cep']) && !isset($attributes['edcenso_city_fk'])) {
                    $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
                    $attributes['edcenso_city_fk'] = $school->edcenso_city_fk;
                }
                if (!empty($attributes['cep']) && !isset($attributes['edcenso_uf_fk'])) {
                    $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
                    $attributes['edcenso_uf_fk'] = $school->edcenso_uf_fk;
                }
                $attributes['cep'] = '';
                $attributes['edcenso_city_fk'] = '';
                break;

        }
        return $attributes;

    }

    public function actionExport()
    {
        include dirname(__DIR__) . '/libraries/Educacenso/Educacenso.php';
        $Educacenso = new Educacenso;
        $export = $Educacenso->exportar(date("Y"));

        $fileDir = Yii::app()->basePath . '/export/' . date('Y_') . Yii::app()->user->school . '.TXT';

        Yii::import('ext.FileManager.fileManager');
        $fm = new fileManager();
        $result = $fm->write($fileDir, $export);

        if ($result) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Exportação Concluida com Sucesso.<br><a href="?r=/censo/DownloadExportFile" class="btn btn-mini" target="_blank"><i class="icon-download-alt"></i>Clique aqui para fazer o Download do arquivo de exportação!!!</a>'));
        } else {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Houve algum erro na Exportação.'));
        }

        return $this->redirect(array('validate'));

//        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
//        $this->normalizeField2019($school->register_type, $school->attributes);
//        $schoolStructure = SchoolStructure::model()->findByPk(Yii::app()->user->school);
//        $this->normalizeField2019($schoolStructure->register_type, $schoolStructure->attributes);
//        $classrooms = Classroom::model()->findAllByAttributes(["school_inep_fk" => yii::app()->user->school, "school_year" => Yii::app()->user->year]);
//        foreach ($classrooms as $iclass => $classroom) {
//            $log['classrooms'][$iclass] = $classroom->attributes;
//            foreach ($classroom->instructorTeachingDatas as $iteaching => $teachingData) {
//                if (!isset($log['instructors'][$teachingData->instructor_fk])) {
//                    //@Todo fazer o sistema atualizar automaticamente quando o o cadastro entrar na escola
//                    $teachingData->instructorFk->documents->school_inep_id_fk = $school->inep_id;
//                    $teachingData->instructorFk->instructorVariableData->school_inep_id_fk = $school->inep_id;
//                    $teachingData->instructorFk->school_inep_id_fk = $school->inep_id;
//                    $log['instructors'][$teachingData->instructor_fk]['identification'] = $teachingData->instructorFk->attributes;
//                    $log['instructors'][$teachingData->instructor_fk]['documents'] = $teachingData->instructorFk->documents->attributes;
//                    $instructor_inepid_id = isset($teachingData->instructorFk->inep_id) && !empty($teachingData->instructorFk->inep_id) ? $teachingData->instructorFk->inep_id : $teachingData->instructorFk->id;
//                    if (isset($teachingData->instructorFk->inep_id) && !empty($teachingData->instructorFk->inep_id)) {
//                        $variabledata = InstructorVariableData::model()->findByAttributes(['inep_id' => $instructor_inepid_id]);
//                    } else {
//                        $variabledata = InstructorVariableData::model()->findByPk($instructor_inepid_id);
//                    }
//                    $variabledata->id = $teachingData->instructorFk->id;
//                    $variabledata->inep_id = $teachingData->instructorFk->inep_id;
//                    $variabledata->school_inep_id_fk = $school->inep_id;
//                    $log['instructors'][$teachingData->instructor_fk]['variable'] = $variabledata->attributes;
//                } else {
//
//                }
//                $teachingData->instructor_inep_id = $teachingData->instructorFk->inep_id;
//                $teachingData->school_inep_id_fk = $school->inep_id;
//                $log['instructors'][$teachingData->instructor_fk]['teaching'][$classroom->id] = $teachingData->attributes;
//            }
//            foreach ($classroom->studentEnrollments as $ienrollment => $enrollment) {
//                if (!isset($log['students'][$enrollment->student_fk])) {
//                    $enrollment->studentFk->school_inep_id_fk = $school->inep_id;
//                    $enrollment->studentFk->documentsFk->school_inep_id_fk = $school->inep_id;
//                    $log['students'][$enrollment->student_fk]['identification'] = $enrollment->studentFk->attributes;
//                    $log['students'][$enrollment->student_fk]['documents'] = $enrollment->studentFk->documentsFk->attributes;
//                }
//                $enrollment->school_inep_id_fk = $school->inep_id;
//                $log['students'][$enrollment->student_fk]['enrollments'][$ienrollment] = $enrollment->attributes;
//            }
//        }
//        foreach ($log['classrooms'] as $classroom) {
//            $this->normalizeField2019($classroom['register_type'], $classroom);
//        }
//        foreach ($log['instructors'] as $instructor) {
//            $id = (String)'90' . $instructor['identification']['id'];
//            $instructor['identification']['id'] = $id;
//            $instructor['documents']['id'] = $id;
//            $instructor['variable']['id'] = $id;
//            $this->normalizeField2019($instructor['identification']['register_type'], $instructor['identification']);
//            $this->normalizeField2019($instructor['documents']['register_type'], $instructor['documents']);
//            $this->normalizeField2019($instructor['variable']['register_type'], $instructor['variable']);
//            foreach ($instructor['teaching'] as $teaching) {
//                $teaching['instructor_fk'] = $id;
//                $this->normalizeField2019($teaching['register_type'], $teaching);
//            }
//        }
//        foreach ($log['students'] as $student) {
//            $this->normalizeField2019($student['identification']['register_type'], $student['identification']);
//            $this->normalizeField2019($student['documents']['register_type'], $student['documents']);
//            foreach ($student['enrollments'] as $enrollment) {
//                $this->normalizeField2019($enrollment['register_type'], $enrollment);
//            }
//        }
//        //ksort($this->tmpexp['c'][5076]);
//        //print_r($this->tmpexp['c'][5076]);exit;
//
//        foreach ($this->tmpexp as $kpos => $pos) {
//            foreach ($pos as $kline => $line) {
//                ksort($line);
//                $this->export[$kpos] .= implode('|', $line);
//                $this->export[$kpos] .= "\n";
//            }
//        }
//        $this->export['e'] .= '30|' . Yii::app()->user->school . '|909999|183258253160|84278560591|RUANCELI DO NASCIMENTO SANTOS|23/05/1988|1|TANIA MARIA DO NASCIMENTO||2|3|1|76|2800670|0|||||||||||||||||||||||||||||1||6||145F01|2008|3||||||||||1|0|0|0|0|0|0|1|0|0|0|0|0|0|0|0|0|0|0|1|0|RUAN@IPTI.ORG.BR' . "\n";
//        $this->export['eb'] .= '40|' . Yii::app()->user->school . '|909999||1|2||1' . "\n";
//        $this->export["i"] .= '99|';
//
//        ksort($this->export);
//        foreach ($this->export as $key => $txtexport) {
//            $export .= $txtexport;
//        }
//        $fileDir = Yii::app()->basePath . '/export/' . date('Y_') . Yii::app()->user->school . '.TXT';
//
//        Yii::import('ext.FileManager.fileManager');
//        $fm = new fileManager();
//        $result = $fm->write($fileDir, $export);
//
//        if ($result) {
//            Yii::app()->user->setFlash('success', Yii::t('default', 'Exportação Concluida com Sucesso.<br><a href="?r=/censo/DownloadExportFile" class="btn btn-mini" target="_blank"><i class="icon-download-alt"></i>Clique aqui para fazer o Download do arquivo de exportação!!!</a>'));
//        } else {
//            Yii::app()->user->setFlash('error', Yii::t('default', 'Houve algum erro na Exportação.'));
//        }
//        $this->redirect(array('validate'));
    }

    public function actionDownloadExportFile()
    {
        $fileDir = Yii::app()->basePath . '/export/' . date('Y_') . Yii::app()->user->school . '.TXT';
        if (file_exists($fileDir)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($fileDir) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileDir));
            readfile($fileDir);
        } else {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Arquivo de exportação não encontrado!!! Tente exportar novamente.'));
            $this->render('index');
        }

    }

    public function actionInitialImport()
    {
        $import = new Import();

        if (!empty($_FILES['Import']['name'])) {
            $path = Yii::app()->basePath;
            $file = $_FILES['Import'];
            $uploadfile = $path . '/import/' . basename($file['name']['file']);
            move_uploaded_file($file['tmp_name']['file'], $uploadfile);
            $import->file = $uploadfile;

            $import->year = $_POST['Import']['year'];
            $import->importWithError = boolval($_POST['Import']['importWithError']);
            $import->run();
        }


        $this->render('initialImport', ['importModel' => $import]);
    }

    public function actionInepImport()
    {
        set_time_limit(0);
        ignore_user_abort();

        $import = new Import();

        if (!empty($_FILES['Import']['name'])) {
            $path = Yii::app()->basePath;
            $file = $_FILES['Import'];
            $uploadedFile = $path . '/import/' . basename($file['name']['file']);
            move_uploaded_file($file['tmp_name']['file'], $uploadedFile);
            if ($_POST["Import"]["probable"]) {
                self::fileImportProbableIneps($uploadedFile);
            } else {
                self::fileImportCorrectIneps($uploadedFile);
            }
        }
        $this->render('inepImport', ['importModel' => $import]);
    }

    public function fileImportCorrectIneps($uploadedFile)
    {
        try {
            $file = fopen($uploadedFile, 'r');
            if ($file == FALSE) {
                die('O arquivo não existe.');
            }
            while (TRUE) {
                $fileLine = fgets($file);
                if ($fileLine == NULL) {
                    break;
                }
                $lineFields_Aux = explode("|", $fileLine);
                $lineFields[] = $lineFields_Aux;
            }
            $imported = "";
            foreach ($lineFields as $index => $line) {
                $student = StudentIdentification::model()->findByPk($line[0]);
                if (isset($student)) {
                    $student->documentsFk->student_fk = $line[8];
                    $student->documentsFk->update(array('student_fk'));
                    $student->inep_id = $line[8];
                    $enrollments = $student->studentEnrollments;

                    if (count($enrollments) > 0) {
                        foreach ($enrollments as $enrollment) {
                            $enrollment->student_inep_id = $line[8];
                            $enrollment->update(array('student_inep_id'));
                        }
                    }

                    $student->update(array('inep_id'));
                    $imported .= $this->printImported('Student', $line);
                } else {
                    $instructor = InstructorIdentification::model()->findByPk($line[0]);
                    if (isset($instructor)) {
                        $instructor->documents->inep_id = $line[8];
                        $instructor->documents->update(array('inep_id'));
                        $instructor->inep_id = $line[8];
                        $instructor->update(array('inep_id'));
                        if ($instructor->instructorVariableData != null) {
                            $instructor->instructorVariableData->inep_id = $line[8];
                            $instructor->instructorVariableData->update(array('inep_id'));
                        }
                        $teachingDatas = $instructor->instructorTeachingDatas;

                        if (count($teachingDatas) > 0) {
                            foreach ($teachingDatas as $teachingData) {
                                $teachingData->instructor_inep_id = $line[8];
                                $teachingData->update(array('instructor_inep_id'));
                            }
                        }
                        $imported .= $this->printImported('Instructor', $line);
                    }
                }
            }
            Yii::app()->user->setFlash("success", "Importação realizada com sucesso!");
            Yii::app()->user->setFlash("log", $imported);
        } catch (Exception $e) {
            Yii::app()->user->setFlash("error", "Ocorreu um erro inesperado.");
        }
    }

    public function fileImportProbableIneps($uploadedFile)
    {
        try {
            $file = fopen($uploadedFile, 'r');
            if ($file == FALSE) {
                return false;
            }
            while (TRUE) {
                $fileLine = fgets($file);
                if ($fileLine == NULL) {
                    break;
                }
                $lineFields_Aux = explode("|", $fileLine);
                $lineFields[] = $lineFields_Aux;
            }

            $imported = "";
            foreach ($lineFields as $index => $line) {
                $student = StudentIdentification::model()->findByPk($line[0]);
                $score = 0;
                if (isset($student)) {
                    trim($student->documentsFk->cpf) == trim($line[1]) ? ++$score : '';
                    trim($student->documentsFk->civil_register_enrollment_number) == trim($line[2]) ? ++$score : '';
                    trim($student->name) == trim($line[3]) ? ++$score : '';
                    trim($student->birthday) == trim($line[4]) ? ++$score : '';
                    trim($student->filiation_1) == trim($line[5]) ? ++$score : '';
                    trim($student->filiation_2) == trim($line[6]) ? ++$score : '';
                    trim($student->edcenso_city_fk) == trim($line[7]) ? ++$score : '';

                    if ($score > 2) {
                        $student->documentsFk->student_fk = $line[8];
                        $student->documentsFk->update(array('student_fk'));
                        $student->inep_id = $line[8];
                        $enrollments = $student->studentEnrollments;

                        if (count($enrollments) > 0) {
                            foreach ($enrollments as $enrollment) {
                                $enrollment->student_inep_id = $line[8];
                                $enrollment->update(array('student_inep_id'));
                            }
                        }

                        $student->update(array('inep_id'));
                        $imported .= $this->printImported("Student ({$score}) :", $line);
                    }
                } else {
                    $instructor = InstructorIdentification::model()->findByPk($line[0]);
                    if (isset($instructor)) {
                        trim($instructor->documents->cpf) == trim($line[1]) ? ++$score : '';
                        trim($instructor->name) == trim($line[3]) ? ++$score : '';
                        trim($instructor->birthday_date) == trim($line[4]) ? ++$score : '';
                        trim($instructor->filiation_1) == trim($line[5]) ? ++$score : '';
                        trim($instructor->filiation_2) == trim($line[6]) ? ++$score : '';
                        trim($instructor->edcenso_city_fk) == trim($line[7]) ? ++$score : '';

                        if ($score > 2) {
                            $instructor->documents->inep_id = $line[8];
                            $instructor->documents->update(array('inep_id'));
                            $instructor->inep_id = $line[8];
                            $instructor->update(array('inep_id'));
                            if ($instructor->instructorVariableData != null) {
                                $instructor->instructorVariableData->inep_id = $line[8];
                                $instructor->instructorVariableData->update(array('inep_id'));
                            }
                            $teachingDatas = $instructor->instructorTeachingDatas;

                            if (count($teachingDatas) > 0) {
                                foreach ($teachingDatas as $teachingData) {
                                    $teachingData->instructor_inep_id = $line[8];
                                    $teachingData->update(array('instructor_inep_id'));
                                }
                            }
                            $imported .= $this->printImported("Instructor ({$score}) :", $line);
                        }
                    }
                }
            }
            Yii::app()->user->setFlash("success", "Importação realizada com sucesso!");
            Yii::app()->user->setFlash("log", $imported);
        } catch (Exception $e) {
            Yii::app()->user->setFlash("error", "Ocorreu um erro inesperado.");
        }

    }

    public function printImported($type, $register)
    {
        return "{$type}: " . implode(' | ', $register) . "<br>";
    }

    public function actionImport()
    {
        $lines = $this->readFileImport();

        $this->updateImport(30, $lines['30'], InstructorIdentification::model(), ['name', 'birthday_date', 'filiation_1', 'filiation_2']);
        $this->updateImport(40, $lines['40'], InstructorDocumentsAndAddress::model(), ['cpf', 'nis', 'address', 'address_number', 'complement', 'neighborhood', 'cep']);
        $this->updateImport(60, $lines['60'], StudentIdentification::model(), ['name', 'birthday', 'filiation_1', 'filiation_2']);
        $this->updateImport(70, $lines['70'], InstructorDocumentsAndAddress::model(), ['cpf', 'nis', 'address', 'number', 'complement', 'neighborhood', 'cep']);
    }

    public function readFileImport()
    {
        set_time_limit(0);
        ignore_user_abort();
        $path = Yii::app()->basePath;
        //Se não passar parametro, o valor será predefinido
        if (empty($_FILES['file']['name'])) {
            $fileDir = $path . '/import/1810601_24_98018493_14032019143014.TXT';
        } else {
            $myfile = $_FILES['file'];
            $uploadfile = $path . '/import/' . basename($myfile['name']);
            move_uploaded_file($myfile['tmp_name'], $uploadfile);
            $fileDir = $uploadfile;
        }


        $mode = 'r';

        //Abre o arquivo
        $file = fopen($fileDir, $mode);
        if ($file == FALSE) {
            die('O arquivo não existe.');
        }

        $registerLines = [];

        //Inicializa o contador de linhas
        $lineCount = [];
        $lineCount['00'] = 0;
        $lineCount['10'] = 0;
        $lineCount['20'] = 0;
        $lineCount['30'] = 0;
        $lineCount['40'] = 0;
        $lineCount['50'] = 0;
        $lineCount['51'] = 0;
        $lineCount['60'] = 0;
        $lineCount['70'] = 0;
        $lineCount['80'] = 0;

        //Pega campos do arquivo
        while (TRUE) {
            //Próxima linha do arquivo
            $fileLine = fgets($file);
            if ($fileLine == NULL) {
                break;
            }

            //Tipo do registro são os 2 primeiros caracteres
            $regType = $fileLine[0] . $fileLine[1];
            //Querba a linha nos caracteres |
            $lineFields_Aux = explode("|", $fileLine);
            $lineFields = [];

            //Troca os campos vazios por 'null'
            foreach ($lineFields_Aux as $key => $field) {
                $value = !(isset($field)) ? '' : trim($field);
                $lineFields[$key] = $value;
            }

            //passa os campos do arquivo para a matriz [tipo][linha][coluna]
            $registerLines[$regType][$lineCount[$regType]++] = $lineFields;
        }
        return $registerLines;
    }

    public function updateImport($register, $lines, CActiveRecord $model, $fieldsUpdate)
    {

        $fields = EdcensoAlias::model()->findAllByAttributes(["register" => $register, "version" => '2018']);
        $orderInepId = $this->getOrderInepId($fields) - 1;
        foreach ($lines as $iline => $line) {

            if (is_null($orderInepId) || !isset($line[$orderInepId]) || $line[$orderInepId] == '') {
                continue;
            }

            $ivariable = $model->findAllByAttributes(["inep_id" => $line[$orderInepId]]);
            $hasModified = false;

            if (count($ivariable)) {
                foreach ($fields as $field) {
                    $columnName = $field->attr;
                    $order = $field->corder - 1;
                    if (isset($line[$order]) && $line[$order] != '' && in_array($columnName, $fieldsUpdate)) {
                        $code = $ivariable->{$columnName} = $line[$order];
                        $hasModified = true;
                    }
                }

                if ($hasModified) {
                    $ivariable->save();
                }
            }
        }
    }

    public function getOrderInepId($fields)
    {
        $result = array_filter($fields, function ($field) {
            return $field->attr == 'inep_id';
        });
        return count($result) ? current($result)->corder : null;
    }
}

?>
