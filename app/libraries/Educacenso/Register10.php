<?php

class Register10
{
    public static function export($year)
    {
        $registers = [];

        $register = [];

        $attributes = SchoolStructure::model()->findByPk(Yii::app()->user->school)->attributes;

        if ($attributes['operation_location_building'] == 1) {
            if ($attributes["building_occupation_situation"] == null) {
                $attributes["building_occupation_situation"] = '1';
            }
            if ($attributes['shared_building_with_school'] == null || $attributes['shared_building_with_school'] = '0') {
                $attributes['shared_building_with_school'] = '0';
                $attributes['shared_school_inep_id_1'] = '';
                $attributes['shared_school_inep_id_2'] = '';
                $attributes['shared_school_inep_id_3'] = '';
                $attributes['shared_school_inep_id_4'] = '';
                $attributes['shared_school_inep_id_5'] = '';
                $attributes['shared_school_inep_id_6'] = '';
            } else if ($attributes['shared_school_inep_id_1'] == null && $attributes['shared_school_inep_id_2'] == null && $attributes['shared_school_inep_id_3'] == null
                && $attributes['shared_school_inep_id_4'] == null && $attributes['shared_school_inep_id_5'] == null && $attributes['shared_school_inep_id_6'] == null) {
                $attributes['shared_building_with_school'] = '0';
            }
        } else {
            $attributes["building_occupation_situation"] = '';
            $attributes['shared_building_with_school'] = '';
            $attributes['shared_school_inep_id_1'] = '';
            $attributes['shared_school_inep_id_2'] = '';
            $attributes['shared_school_inep_id_3'] = '';
            $attributes['shared_school_inep_id_4'] = '';
            $attributes['shared_school_inep_id_5'] = '';
            $attributes['shared_school_inep_id_6'] = '';
        }

        if ($attributes['water_supply_inexistent'] == '1') {
            $attributes['water_supply_public'] = '0';
            $attributes['water_supply_artesian_well'] = '0';
            $attributes['water_supply_well'] = '0';
            $attributes['water_supply_river'] = '0';
        }

        if ($attributes['energy_supply_inexistent'] == '1') {
            $attributes['energy_supply_public'] = '0';
            $attributes['energy_supply_generator'] = '0';
            $attributes['energy_supply_generator_alternative'] = '0';
        }

        if ($attributes['sewage_inexistent'] == '1') {
            $attributes['sewage_public'] = '0';
            $attributes['sewage_fossa'] = '0';
            $attributes['sewage_fossa_common'] = '0';
        } else if ($attributes['sewage_fossa'] == '1' && $attributes['sewage_fossa_common'] == '1') {
            $attributes['sewage_fossa_common'] = '0';
        }

        if ($attributes['traetment_garbage_inexistent'] == '1') {
            $attributes['treatment_garbage_parting_garbage'] = '0';
            $attributes['treatment_garbage_resuse'] = '0';
            $attributes['garbage_destination_recycle'] = '0';
        }

        if ($attributes['acessabilty_inexistent'] == '1') {
            $attributes['acessability_handrails_guardrails'] = '0';
            $attributes['acessability_elevator'] = '0';
            $attributes['acessability_tactile_floor'] = '0';
            $attributes['acessability_doors_80cm'] = '0';
            $attributes['acessability_ramps'] = '0';
            $attributes['acessability_sound_signaling'] = '0';
            $attributes['acessability_tactile_singnaling'] = '0';
            $attributes['acessability_visual_signaling'] = '0';
        }

        //todo: fazer inexistent pra dependencies (é muito campo)
        //todo: fazer regras de segurança do 110 ao 115

        if ($attributes['equipments_qtd_desktop'] == null && $attributes['equipments_qtd_notebookstudent'] == null && $attributes['equipments_qtd_tabletstudent'] == null) {
            $attributes['internet_access_connected_desktop'] = '0';
        }

        if ($attributes['equipments_multimedia_collection'] == '') {
            $attributes['equipments_multimedia_collection'] = '0';
        }

        if ($attributes['equipments_toys_early'] == '') {
            $attributes['equipments_toys_early'] = '0';
        }

        if ($attributes['equipments_scientific_materials'] == '') {
            $attributes['equipments_scientific_materials'] = '0';
        }

        if ($attributes['equipments_equipment_amplification'] == '') {
            $attributes['equipments_equipment_amplification'] = '0';
        }

        if ($attributes['equipments_musical_instruments'] == '') {
            $attributes['equipments_musical_instruments'] = '0';
        }

        if ($attributes['equipments_educational_games'] == '') {
            $attributes['equipments_educational_games'] = '0';
        }

        if ($attributes['equipments_material_cultural'] == '') {
            $attributes['equipments_material_cultural'] = '0';
        }

        if ($attributes['equipments_material_sports'] == '') {
            $attributes['equipments_material_sports'] = '0';
        }

        if ($attributes['equipments_material_teachingindian'] == '') {
            $attributes['equipments_material_teachingindian'] = '0';
        }

        if ($attributes['equipments_material_teachingethnic'] == '') {
            $attributes['equipments_material_teachingethnic'] = '0';
        }

        if ($attributes['equipments_material_teachingrural'] == '') {
            $attributes['equipments_material_teachingrural'] = '0';
        }

        if ($attributes['native_education'] == '') {
            $attributes['native_education'] = '0';
        }

        if ($attributes['board_organ_association_parent'] == '') {
            $attributes['board_organ_association_parent'] = '0';
        }

        if ($attributes['board_organ_association_parentinstructors'] == '') {
            $attributes['board_organ_association_parentinstructors'] = '0';
        }

        if ($attributes['board_organ_board_school'] == '') {
            $attributes['board_organ_board_school'] = '0';
        }

        if ($attributes['board_organ_student_guild'] == '') {
            $attributes['board_organ_student_guild'] = '0';
        }

        if ($attributes['board_organ_others'] == '') {
            $attributes['board_organ_others'] = '0';
        }

        if ($attributes['board_organ_inexistent'] == '') {
            $attributes['board_organ_inexistent'] = '0';
        }

        if ($attributes['ppp_updated'] == '') {
            $attributes['ppp_updated'] = '0';
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

        $classrooms = Classroom::model()->with([
            'edcensoStageVsModalityFk' => [
                'select' => false,
                'joinType' => 'INNER JOIN',
                'condition' => 'edcensoStageVsModalityFk.stage IN (4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 41, 56)',
            ]
        ])->findAllByAttributes(['school_inep_fk' => yii::app()->user->school, 'school_year' => Yii::app()->user->year]);

        if (count($classrooms) == 0) {
            $attributes['basic_education_cycle_organized'] = '';
        }

        $edcensoAliases = EdcensoAlias::model()->findAll('year = :year and register = 10 order by corder', [":year" => $year]);
        foreach ($edcensoAliases as $edcensoAlias) {
            if ($edcensoAlias->corder == 43) {
                $register[$edcensoAlias->corder] =
                    $attributes["dependencies_prysical_disability_bathroom"] == 1 || $attributes["dependencies_child_bathroom"] == 1 ||
                    $attributes["dependencies_bathroom_workes"] == 1 || $attributes["dependencies_bathroom_with_shower"] == 1
                        ? 1 : 0;
            } else {
                $register[$edcensoAlias->corder] = $edcensoAlias->default;
                if ($edcensoAlias["attr"] != null && $attributes[$edcensoAlias["attr"]] !== $edcensoAlias->default) {
                    $register[$edcensoAlias->corder] = $attributes[$edcensoAlias["attr"]];
                }
            }
        }

        array_push($registers, implode('|', $register));

        return $registers;
    }
}