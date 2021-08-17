<?php
class Register10
{
    public static function export()
    {
        $registers = [];

        $register = [];

        $attributes = SchoolStructure::model()->findByPk(Yii::app()->user->school)->attributes;

        $attributes['id'] = $attributes['school_inep_id_fk'];

        $aliases = EdcensoAlias::model()->findAllByAttributes(['year' => 2021, 'register' => '10']);
        foreach ($aliases as $alias) {
            $register[$alias->corder] = $alias->default;
        }

        foreach ($attributes as $column => $value) {
            if (empty($value)) {
                $attributes[$column] = 0;
            }
        }

        $itens = [
            'equipments_tv', 'equipments_vcr', 'equipments_dvd',  
            'equipments_overhead_projector', 'equipments_stereo_system', 
            'equipments_data_show', 'equipments_printer', 'equipments_fax', 'equipments_camera','administrative_computers_count', 
            'student_computers_count', 'internet_access', 'bandwidth',
            'workers_administrative_assistant','workers_service_assistant',
            'workers_librarian','workers_firefighter','workers_coordinator_shift',
            'workers_speech_therapist','workers_nutritionist','workers_psychologist',
            'workers_cooker','workers_support_professionals','workers_school_secretary',
            'workers_security_guards','workers_monitors','board_organ_association_parent',
            'board_organ_association_parentinstructors','board_organ_board_school',
            'board_organ_student_guild','board_organ_others','board_organ_inexistent',
            'ppp_updated','dependencies_outside_roomspublic','dependencies_climate_roomspublic',
            'dependencies_acessibility_roomspublic','equipments_qtd_blackboard','internet_access_local_cable',
            'internet_access_local_wireless','internet_access_local_inexistet','equipments_computer'.
            'garbage_destination_throw_away','treatment_garbage_parting_garbage',
            'treatment_garbage_resuse','garbage_destination_recycle','equipments_qtd_notebookstudent',
            'equipments_qtd_tabletstudent','internet_access_administrative',
            'internet_access_educative_process','internet_access_student',
            'internet_access_community','internet_access_inexistent',
            'treatment_garbage_parting_garbage','traetment_garbage_inexistent',
            'acessabilty_inexistent','acessability_visual_signaling',
            'acessability_tactile_singnaling','acessability_sound_signaling',
            'acessability_ramps','acessability_doors_80cm','acessability_tactile_floor',
            'acessability_elevator','acessability_handrails_guardrails','dependencies_covered_patio',
            'dependencies_principal_room','dependencies_instructors_room','dependencies_aee_room',
            'equipments_copier','dependencies_uncovered_patio'
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

        if ($attributes['sewage_fossa'] == '1' && $attributes['sewage_fossa_common'] == '1') {
            $attributes['sewage_fossa_common'] = '0';
        }

        $classrooms = Classroom::model()->with([
            'edcensoStageVsModalityFk'=> [
                'select' => false,
                'joinType' => 'INNER JOIN',
                'condition' => 'edcensoStageVsModalityFk.stage IN (4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 41, 56)',
            ]
        ])->findAllByAttributes(['school_inep_fk' => yii::app()->user->school, 'school_year' => Yii::app()->user->year]);

        if (count($classrooms) == 0) {
            $attributes['basic_education_cycle_organized'] = '';
        }

        foreach ($attributes as $column => $value) {
            if ($value === '') {
                $alias = EdcensoAlias::model()->findByAttributes(['year' => 2021, 'register' => '10', 'attr' => $column]);
                $attributes[$column] = $alias->default;
            }
        }

        foreach ($attributes as $column => $value) {
            $alias = EdcensoAlias::model()->findByAttributes(['year' => 2021, 'register' => '10', 'attr' => $column]);
            if (isset($alias->corder)) {
                $register[$alias->corder] = $value;
            }
        }

        ksort($register);
        array_push($registers, implode('|', $register));

        return $registers;
    }
}