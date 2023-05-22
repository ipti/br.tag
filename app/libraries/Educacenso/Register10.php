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
            if ($attributes['shared_building_with_school'] == null || $attributes['shared_building_with_school'] == '0') {
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

        if ($attributes['classroom_count'] == '0') {
            $attributes['classroom_count'] = null;
        }
        if ($attributes['dependencies_outside_roomspublic'] == '0') {
            $attributes['dependencies_outside_roomspublic'] = null;
        }
        if ($attributes['dependencies_climate_roomspublic'] == '0') {
            $attributes['dependencies_climate_roomspublic'] = null;
        }
        if ($attributes['dependencies_acessibility_roomspublic'] == '0') {
            $attributes['dependencies_acessibility_roomspublic'] = null;
        }

        if ($attributes['equipments_dvd'] == '0') {
            $attributes['equipments_dvd'] = null;
        }
        if ($attributes['equipments_stereo_system'] == '0') {
            $attributes['equipments_stereo_system'] = null;
        }
        if ($attributes['equipments_tv'] == '0') {
            $attributes['equipments_tv'] = null;
        }
        if ($attributes['equipments_qtd_blackboard'] == '0') {
            $attributes['equipments_qtd_blackboard'] = null;
        }
        if ($attributes['equipments_overhead_projector'] == '0') {
            $attributes['equipments_overhead_projector'] = null;
        }

        if ($attributes['equipments_qtd_desktop'] == '0') {
            $attributes['equipments_qtd_desktop'] = null;
        }
        if ($attributes['equipments_qtd_notebookstudent'] == '0') {
            $attributes['equipments_qtd_notebookstudent'] = null;
        }
        if ($attributes['equipments_qtd_tabletstudent'] == '0') {
            $attributes['equipments_qtd_tabletstudent'] = null;
        }
        if ($attributes['equipments_qtd_desktop'] == null && $attributes['equipments_qtd_notebookstudent'] == null && $attributes['equipments_qtd_tabletstudent'] == null) {
            $attributes['internet_access_connected_desktop'] = '0';
            if ($attributes['equipments_computer'] == '0') {
                $attributes['internet_access_local_cable'] = '';
                $attributes['internet_access_local_wireless'] = '';
                $attributes['internet_access_local_inexistet'] = '';
            }
        }

        if ($attributes['internet_access_local_wireless'] != '1') {
            $attributes['internet_access_connected_personaldevice'] = '0';
        }

        if ($attributes['internet_access_inexistent'] == '1') {
            $attributes['internet_access_broadband'] = '';
        }

        if ($attributes['workers_administrative_assistant'] == '0') {
            $attributes['workers_administrative_assistant'] = null;
        }
        if ($attributes['workers_service_assistant'] == '0') {
            $attributes['workers_service_assistant'] = null;
        }
        if ($attributes['workers_librarian'] == '0') {
            $attributes['workers_librarian'] = null;
        }
        if ($attributes['workers_firefighter'] == '0') {
            $attributes['workers_firefighter'] = null;
        }
        if ($attributes['workers_coordinator_shift'] == '0') {
            $attributes['workers_coordinator_shift'] = null;
        }
        if ($attributes['workers_speech_therapist'] == '0') {
            $attributes['workers_speech_therapist'] = null;
        }
        if ($attributes['workers_nutritionist'] == '0') {
            $attributes['workers_nutritionist'] = null;
        }
        if ($attributes['workers_psychologist'] == '0') {
            $attributes['workers_psychologist'] = null;
        }
        if ($attributes['workers_cooker'] == '0') {
            $attributes['workers_cooker'] = null;
        }
        if ($attributes['workers_support_professionals'] == '0') {
            $attributes['workers_support_professionals'] = null;
        }
        if ($attributes['workers_school_secretary'] == '0') {
            $attributes['workers_school_secretary'] = null;
        }
        if ($attributes['workers_security_guards'] == '0') {
            $attributes['workers_security_guards'] = null;
        }
        if ($attributes['workers_monitors'] == '0') {
            $attributes['workers_monitors'] = null;
        }

        $attributes['native_education'] = 0;
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
        } else {
            if ($attributes['booking_enrollment_inexistent'] == '1') {
                $attributes['booking_enrollment_self_declaredskin'] = '0';
                $attributes['booking_enrollment_income'] = '0';
                $attributes['booking_enrollment_public_school'] = '0';
                $attributes['booking_enrollment_disabled_person'] = '0';
                $attributes['booking_enrollment_others'] = '0';
            }
        }

        if ($attributes['board_organ_inexistent'] == '1') {
            $attributes['board_organ_association_parent'] = '0';
            $attributes['board_organ_association_parentinstructors'] = '0';
            $attributes['board_organ_board_school'] = '0';
            $attributes['board_organ_student_guild'] = '0';
            $attributes['board_organ_others'] = '0';
        }

        $edcensoAliases = EdcensoAlias::model()->findAll('year = :year and register = 10 order by corder', [":year" => $year]);
        foreach ($edcensoAliases as $edcensoAlias) {
            if ($edcensoAlias->corder == 43) {
                $register[$edcensoAlias->corder] =
                    $attributes["dependencies_prysical_disability_bathroom"] == 1 || $attributes["dependencies_child_bathroom"] == 1 ||
                    $attributes["dependencies_bathroom_workes"] == 1 || $attributes["dependencies_bathroom_with_shower"] == 1
                        ? 1 : 0;
            } else if ($edcensoAlias->corder == 133) {
                $register[$edcensoAlias->corder] = null;
                if ($attributes["workers_administrative_assistant"] == null && $attributes["workers_service_assistant"] == null && $attributes["workers_librarian"] == null
                    && $attributes["workers_firefighter"] == null && $attributes["workers_coordinator_shift"] == null && $attributes["workers_speech_therapist"] == null
                    && $attributes["workers_nutritionist"] == null && $attributes["workers_psychologist"] == null && $attributes["workers_cooker"] == null
                    && $attributes["workers_support_professionals"] == null && $attributes["workers_school_secretary"] == null && $attributes["workers_security_guards"] == null
                    && $attributes["workers_monitors"] == null) {
                    $register[$edcensoAlias->corder] = 1;
                }
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