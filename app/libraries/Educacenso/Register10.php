<?php

class Register10
{
    public static function export($year)
    {
        $registers = [];

        $register = [];

        $attributes = SchoolStructure::model()->findByPk(Yii::app()->user->school)->attributes;

        if (1 == $attributes['operation_location_building']) {
            if (null == $attributes['building_occupation_situation']) {
                $attributes['building_occupation_situation'] = '1';
            }
            if (null == $attributes['shared_building_with_school'] || '0' == $attributes['shared_building_with_school']) {
                $attributes['shared_building_with_school'] = '0';
                $attributes['shared_school_inep_id_1'] = '';
                $attributes['shared_school_inep_id_2'] = '';
                $attributes['shared_school_inep_id_3'] = '';
                $attributes['shared_school_inep_id_4'] = '';
                $attributes['shared_school_inep_id_5'] = '';
                $attributes['shared_school_inep_id_6'] = '';
            } elseif (
                null == $attributes['shared_school_inep_id_1'] && null == $attributes['shared_school_inep_id_2'] && null == $attributes['shared_school_inep_id_3']
                && null == $attributes['shared_school_inep_id_4'] && null == $attributes['shared_school_inep_id_5'] && null == $attributes['shared_school_inep_id_6']
            ) {
                $attributes['shared_building_with_school'] = '0';
            }
        } else {
            $attributes['building_occupation_situation'] = '';
            $attributes['shared_building_with_school'] = '';
            $attributes['shared_school_inep_id_1'] = '';
            $attributes['shared_school_inep_id_2'] = '';
            $attributes['shared_school_inep_id_3'] = '';
            $attributes['shared_school_inep_id_4'] = '';
            $attributes['shared_school_inep_id_5'] = '';
            $attributes['shared_school_inep_id_6'] = '';
        }

        if ('1' == $attributes['water_supply_inexistent']) {
            $attributes['water_supply_public'] = '0';
            $attributes['water_supply_artesian_well'] = '0';
            $attributes['water_supply_well'] = '0';
            $attributes['water_supply_river'] = '0';
            $attributes['water_supply_car'] = '0';
        }

        if ('1' == $attributes['energy_supply_inexistent']) {
            $attributes['energy_supply_public'] = '0';
            $attributes['energy_supply_generator'] = '0';
            $attributes['energy_supply_generator_alternative'] = '0';
        }

        if ('1' == $attributes['sewage_inexistent']) {
            $attributes['sewage_public'] = '0';
            $attributes['sewage_fossa'] = '0';
            $attributes['sewage_fossa_common'] = '0';
        } elseif ('1' == $attributes['sewage_fossa'] && '1' == $attributes['sewage_fossa_common']) {
            $attributes['sewage_fossa_common'] = '0';
        }

        if ('1' == $attributes['traetment_garbage_inexistent']) {
            $attributes['treatment_garbage_parting_garbage'] = '0';
            $attributes['treatment_garbage_resuse'] = '0';
            $attributes['garbage_destination_recycle'] = '0';
        }

        if ('1' == $attributes['acessabilty_inexistent']) {
            $attributes['acessability_handrails_guardrails'] = '0';
            $attributes['acessability_elevator'] = '0';
            $attributes['acessability_tactile_floor'] = '0';
            $attributes['acessability_doors_80cm'] = '0';
            $attributes['acessability_ramps'] = '0';
            $attributes['acessability_light_signaling'] = '0';
            $attributes['acessability_sound_signaling'] = '0';
            $attributes['acessability_tactile_singnaling'] = '0';
            $attributes['acessability_visual_signaling'] = '0';
        }

        // todo: fazer inexistent pra dependencies (é muito campo)

        if ('0' == $attributes['classroom_count']) {
            $attributes['classroom_count'] = null;
        }
        if ('0' == $attributes['dependencies_outside_roomspublic']) {
            $attributes['dependencies_outside_roomspublic'] = null;
        }
        if ('0' == $attributes['dependencies_climate_roomspublic']) {
            $attributes['dependencies_climate_roomspublic'] = null;
        }
        if ('0' == $attributes['dependencies_acessibility_roomspublic']) {
            $attributes['dependencies_acessibility_roomspublic'] = null;
        }

        if ('0' == $attributes['equipments_dvd']) {
            $attributes['equipments_dvd'] = null;
        }
        if ('0' == $attributes['equipments_stereo_system']) {
            $attributes['equipments_stereo_system'] = null;
        }
        if ('0' == $attributes['equipments_tv']) {
            $attributes['equipments_tv'] = null;
        }
        if ('0' == $attributes['equipments_qtd_blackboard']) {
            $attributes['equipments_qtd_blackboard'] = null;
        }
        if ('0' == $attributes['equipments_overhead_projector']) {
            $attributes['equipments_overhead_projector'] = null;
        }

        if ('0' == $attributes['equipments_qtd_desktop']) {
            $attributes['equipments_qtd_desktop'] = null;
        }
        if ('0' == $attributes['equipments_qtd_notebookstudent']) {
            $attributes['equipments_qtd_notebookstudent'] = null;
        }
        if ('0' == $attributes['equipments_qtd_tabletstudent']) {
            $attributes['equipments_qtd_tabletstudent'] = null;
        }
        if (null == $attributes['equipments_qtd_desktop'] && null == $attributes['equipments_qtd_notebookstudent'] && null == $attributes['equipments_qtd_tabletstudent']) {
            $attributes['internet_access_connected_desktop'] = '0';
            if ('0' == $attributes['equipments_computer']) {
                $attributes['internet_access_local_cable'] = '';
                $attributes['internet_access_local_wireless'] = '';
                $attributes['internet_access_local_inexistet'] = '';
            }
        }

        if ('1' != $attributes['internet_access_local_wireless']) {
            $attributes['internet_access_connected_personaldevice'] = '0';
        }

        if ('1' == $attributes['internet_access_inexistent']) {
            $attributes['internet_access_broadband'] = '';
        }

        if ('0' == $attributes['workers_garden_planting_agricultural']) {
            $attributes['workers_garden_planting_agricultural'] = null;
        }
        if ('0' == $attributes['workers_administrative_assistant']) {
            $attributes['workers_administrative_assistant'] = null;
        }
        if ('0' == $attributes['workers_service_assistant']) {
            $attributes['workers_service_assistant'] = null;
        }
        if ('0' == $attributes['workers_librarian']) {
            $attributes['workers_librarian'] = null;
        }
        if ('0' == $attributes['workers_firefighter']) {
            $attributes['workers_firefighter'] = null;
        }
        if ('0' == $attributes['workers_coordinator_shift']) {
            $attributes['workers_coordinator_shift'] = null;
        }
        if ('0' == $attributes['workers_speech_therapist']) {
            $attributes['workers_speech_therapist'] = null;
        }
        if ('0' == $attributes['workers_nutritionist']) {
            $attributes['workers_nutritionist'] = null;
        }
        if ('0' == $attributes['workers_psychologist']) {
            $attributes['workers_psychologist'] = null;
        }
        if ('0' == $attributes['workers_cooker']) {
            $attributes['workers_cooker'] = null;
        }
        if ('0' == $attributes['workers_support_professionals']) {
            $attributes['workers_support_professionals'] = null;
        }
        if ('0' == $attributes['workers_school_secretary']) {
            $attributes['workers_school_secretary'] = null;
        }
        if ('0' == $attributes['workers_security_guards']) {
            $attributes['workers_security_guards'] = null;
        }
        if ('0' == $attributes['workers_monitors']) {
            $attributes['workers_monitors'] = null;
        }
        if ('0' == $attributes['workers_braille']) {
            $attributes['workers_braille'] = null;
        }

        $attributes['native_education'] = 0;
        if (1 != $attributes['native_education']) {
            $attributes['native_education_language_native'] = '';
            $attributes['native_education_language_portuguese'] = '';
            $attributes['edcenso_native_languages_fk'] = '';
            $attributes['edcenso_native_languages_fk2'] = '';
            $attributes['edcenso_native_languages_fk3'] = '';
        }

        if (1 != $attributes['select_adimission']) {
            $attributes['booking_enrollment_self_declaredskin'] = '';
            $attributes['booking_enrollment_income'] = '';
            $attributes['booking_enrollment_public_school'] = '';
            $attributes['booking_enrollment_disabled_person'] = '';
            $attributes['booking_enrollment_others'] = '';
            $attributes['booking_enrollment_inexistent'] = '';
        } else {
            if ('1' == $attributes['booking_enrollment_inexistent']) {
                $attributes['booking_enrollment_self_declaredskin'] = '0';
                $attributes['booking_enrollment_income'] = '0';
                $attributes['booking_enrollment_public_school'] = '0';
                $attributes['booking_enrollment_disabled_person'] = '0';
                $attributes['booking_enrollment_others'] = '0';
            }
        }

        if ('1' == $attributes['board_organ_inexistent']) {
            $attributes['board_organ_association_parent'] = '0';
            $attributes['board_organ_association_parentinstructors'] = '0';
            $attributes['board_organ_board_school'] = '0';
            $attributes['board_organ_student_guild'] = '0';
            $attributes['board_organ_others'] = '0';
        }

        if (empty($attributes['classroom_count']) || empty($attributes['dependencies_outside_roomspublic'])) {
            $attributes['dependencies_reading_corners'] = null;
        } elseif ($attributes['dependencies_reading_corners'] > 4) {
            $attributes['dependencies_reading_corners'] = 4;
        } elseif (0 === $attributes['dependencies_reading_corners']) {
            $attributes['dependencies_reading_corners'] = null;
        }

        $edcensoAliases = EdcensoAlias::model()->findAll('year = :year and register = 10 order by corder', [':year' => $year]);
        foreach ($edcensoAliases as $edcensoAlias) {
            if (44 == $edcensoAlias->corder) {
                $register[$edcensoAlias->corder] =
                    1 == $attributes['dependencies_prysical_disability_bathroom'] || 1 == $attributes['dependencies_child_bathroom'] ||
                    1 == $attributes['dependencies_bathroom_workes'] || 1 == $attributes['dependencies_bathroom_with_shower']
                    ? 1 : 0;
            } elseif (138 == $edcensoAlias->corder) {
                $register[$edcensoAlias->corder] = null;
                if (
                    null == $attributes['workers_garden_planting_agricultural'] && null == $attributes['workers_administrative_assistant']
                    && null == $attributes['workers_service_assistant'] && null == $attributes['workers_librarian']
                    && null == $attributes['workers_firefighter'] && null == $attributes['workers_coordinator_shift'] && null == $attributes['workers_speech_therapist']
                    && null == $attributes['workers_nutritionist'] && null == $attributes['workers_psychologist'] && null == $attributes['workers_cooker']
                    && null == $attributes['workers_support_professionals'] && null == $attributes['workers_school_secretary'] && null == $attributes['workers_security_guards']
                    && null == $attributes['workers_monitors'] && null == $attributes['workers_braille']
                ) {
                    $register[$edcensoAlias->corder] = 1;
                }
            } else {
                $register[$edcensoAlias->corder] = $edcensoAlias->default;
                if (null != $edcensoAlias['attr'] && $attributes[$edcensoAlias['attr']] !== $edcensoAlias->default) {
                    $register[$edcensoAlias->corder] = $attributes[$edcensoAlias['attr']] ?? $edcensoAlias->default;
                }
            }
        }

        $registers[] = implode('|', $register);

        return $registers;
    }
}
