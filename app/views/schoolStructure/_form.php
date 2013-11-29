<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'school-structure-form',
	'enableAjaxValidation'=>false,
)); ?>
        <div class="panelGroup form">
            <?php echo $form->errorSummary($model); ?>
            <div class="panelGroupHeader"><div class=""> <?php echo $title; ?>
</div></div>


        <div class="tab-content">

        <!-- Tab content -->
        <div class="tab-pane active" id="school-indentify">
            <div class="row-fluid">
                <div class=" span6">

                     <?php echo Yii::t('default', 'Fields with * are required.')?></div>

                        <div class="separator"></div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'register_type'); ?>
                        <?php echo $form->textField($model,'register_type',array('size'=>2,'maxlength'=>2)); ?>
                        <?php echo $form->error($model,'register_type'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'school_inep_id_fk'); ?>
                        <?php echo $form->textField($model,'school_inep_id_fk',array('size'=>8,'maxlength'=>8)); ?>
                        <?php echo $form->error($model,'school_inep_id_fk'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'manager_cpf'); ?>
                        <?php echo $form->textField($model,'manager_cpf',array('size'=>11,'maxlength'=>11)); ?>
                        <?php echo $form->error($model,'manager_cpf'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'manager_name'); ?>
                        <?php echo $form->textField($model,'manager_name',array('size'=>60,'maxlength'=>100)); ?>
                        <?php echo $form->error($model,'manager_name'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'manager_role'); ?>
                        <?php echo $form->textField($model,'manager_role'); ?>
                        <?php echo $form->error($model,'manager_role'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'manager_email'); ?>
                        <?php echo $form->textField($model,'manager_email',array('size'=>50,'maxlength'=>50)); ?>
                        <?php echo $form->error($model,'manager_email'); ?>
                    </div>


                    <div class="control-group">
                        <?php echo $form->labelEx($model,'operation_location_building'); ?>
                        <?php echo $form->checkBox($model,'operation_location_building', array('value'=>1, 'uncheckValue'=>0)); ?>
                        <?php echo $form->error($model,'operation_location_building'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'operation_location_temple'); ?>
                        <?php echo $form->textField($model,'operation_location_temple'); ?>
                        <?php echo $form->error($model,'operation_location_temple'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'operation_location_businness_room'); ?>
                        <?php echo $form->textField($model,'operation_location_businness_room'); ?>
                        <?php echo $form->error($model,'operation_location_businness_room'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'operation_location_instructor_house'); ?>
                        <?php echo $form->textField($model,'operation_location_instructor_house'); ?>
                        <?php echo $form->error($model,'operation_location_instructor_house'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'operation_location_other_school_room'); ?>
                        <?php echo $form->textField($model,'operation_location_other_school_room'); ?>
                        <?php echo $form->error($model,'operation_location_other_school_room'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'operation_location_barracks'); ?>
                        <?php echo $form->textField($model,'operation_location_barracks'); ?>
                        <?php echo $form->error($model,'operation_location_barracks'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'operation_location_socioeducative_unity'); ?>
                        <?php echo $form->textField($model,'operation_location_socioeducative_unity'); ?>
                        <?php echo $form->error($model,'operation_location_socioeducative_unity'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'operation_location_prison_unity'); ?>
                        <?php echo $form->textField($model,'operation_location_prison_unity'); ?>
                        <?php echo $form->error($model,'operation_location_prison_unity'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'operation_location_other'); ?>
                        <?php echo $form->textField($model,'operation_location_other'); ?>
                        <?php echo $form->error($model,'operation_location_other'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'building_occupation_situation'); ?>
                        <?php echo $form->textField($model,'building_occupation_situation'); ?>
                        <?php echo $form->error($model,'building_occupation_situation'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'shared_building_with_school'); ?>
                        <?php echo $form->textField($model,'shared_building_with_school'); ?>
                        <?php echo $form->error($model,'shared_building_with_school'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'shared_school_inep_id_1'); ?>
                        <?php echo $form->textField($model,'shared_school_inep_id_1',array('size'=>8,'maxlength'=>8)); ?>
                        <?php echo $form->error($model,'shared_school_inep_id_1'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'shared_school_inep_id_2'); ?>
                        <?php echo $form->textField($model,'shared_school_inep_id_2',array('size'=>8,'maxlength'=>8)); ?>
                        <?php echo $form->error($model,'shared_school_inep_id_2'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'shared_school_inep_id_3'); ?>
                        <?php echo $form->textField($model,'shared_school_inep_id_3',array('size'=>8,'maxlength'=>8)); ?>
                        <?php echo $form->error($model,'shared_school_inep_id_3'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'shared_school_inep_id_4'); ?>
                        <?php echo $form->textField($model,'shared_school_inep_id_4',array('size'=>8,'maxlength'=>8)); ?>
                        <?php echo $form->error($model,'shared_school_inep_id_4'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'shared_school_inep_id_5'); ?>
                        <?php echo $form->textField($model,'shared_school_inep_id_5',array('size'=>8,'maxlength'=>8)); ?>
                        <?php echo $form->error($model,'shared_school_inep_id_5'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'shared_school_inep_id_6'); ?>
                        <?php echo $form->textField($model,'shared_school_inep_id_6',array('size'=>8,'maxlength'=>8)); ?>
                        <?php echo $form->error($model,'shared_school_inep_id_6'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'consumed_water_type'); ?>
                        <?php echo $form->textField($model,'consumed_water_type'); ?>
                        <?php echo $form->error($model,'consumed_water_type'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'water_supply_public'); ?>
                        <?php echo $form->textField($model,'water_supply_public'); ?>
                        <?php echo $form->error($model,'water_supply_public'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'water_supply_artesian_well'); ?>
                        <?php echo $form->textField($model,'water_supply_artesian_well'); ?>
                        <?php echo $form->error($model,'water_supply_artesian_well'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'water_supply_well'); ?>
                        <?php echo $form->textField($model,'water_supply_well'); ?>
                        <?php echo $form->error($model,'water_supply_well'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'water_supply_river'); ?>
                        <?php echo $form->textField($model,'water_supply_river'); ?>
                        <?php echo $form->error($model,'water_supply_river'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'water_supply_inexistent'); ?>
                        <?php echo $form->textField($model,'water_supply_inexistent'); ?>
                        <?php echo $form->error($model,'water_supply_inexistent'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'energy_supply_public'); ?>
                        <?php echo $form->textField($model,'energy_supply_public'); ?>
                        <?php echo $form->error($model,'energy_supply_public'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'energy_supply_generator'); ?>
                        <?php echo $form->textField($model,'energy_supply_generator'); ?>
                        <?php echo $form->error($model,'energy_supply_generator'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'energy_supply_other'); ?>
                        <?php echo $form->textField($model,'energy_supply_other'); ?>
                        <?php echo $form->error($model,'energy_supply_other'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'energy_supply_inexistent'); ?>
                        <?php echo $form->textField($model,'energy_supply_inexistent'); ?>
                        <?php echo $form->error($model,'energy_supply_inexistent'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'sewage_public'); ?>
                        <?php echo $form->textField($model,'sewage_public'); ?>
                        <?php echo $form->error($model,'sewage_public'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'sewage_fossa'); ?>
                        <?php echo $form->textField($model,'sewage_fossa'); ?>
                        <?php echo $form->error($model,'sewage_fossa'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'sewage_inexistent'); ?>
                        <?php echo $form->textField($model,'sewage_inexistent'); ?>
                        <?php echo $form->error($model,'sewage_inexistent'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'garbage_destination_collect'); ?>
                        <?php echo $form->textField($model,'garbage_destination_collect'); ?>
                        <?php echo $form->error($model,'garbage_destination_collect'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'garbage_destination_burn'); ?>
                        <?php echo $form->textField($model,'garbage_destination_burn'); ?>
                        <?php echo $form->error($model,'garbage_destination_burn'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'garbage_destination_throw_away'); ?>
                        <?php echo $form->textField($model,'garbage_destination_throw_away'); ?>
                        <?php echo $form->error($model,'garbage_destination_throw_away'); ?>
                    </div>

                    <div class="control-group">
                        <?php echo $form->labelEx($model,'garbage_destination_recycle'); ?>
                        <?php echo $form->textField($model,'garbage_destination_recycle'); ?>
                        <?php echo $form->error($model,'garbage_destination_recycle'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'garbage_destination_bury'); ?>
                        <?php echo $form->textField($model,'garbage_destination_bury'); ?>
                        <?php echo $form->error($model,'garbage_destination_bury'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'garbage_destination_other'); ?>
                        <?php echo $form->textField($model,'garbage_destination_other'); ?>
                        <?php echo $form->error($model,'garbage_destination_other'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_principal_room'); ?>
                        <?php echo $form->textField($model,'dependencies_principal_room'); ?>
                        <?php echo $form->error($model,'dependencies_principal_room'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_instructors_room'); ?>
                        <?php echo $form->textField($model,'dependencies_instructors_room'); ?>
                        <?php echo $form->error($model,'dependencies_instructors_room'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_secretary_room'); ?>
                        <?php echo $form->textField($model,'dependencies_secretary_room'); ?>
                        <?php echo $form->error($model,'dependencies_secretary_room'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_info_lab'); ?>
                        <?php echo $form->textField($model,'dependencies_info_lab'); ?>
                        <?php echo $form->error($model,'dependencies_info_lab'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_science_lab'); ?>
                        <?php echo $form->textField($model,'dependencies_science_lab'); ?>
                        <?php echo $form->error($model,'dependencies_science_lab'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_aee_room'); ?>
                        <?php echo $form->textField($model,'dependencies_aee_room'); ?>
                        <?php echo $form->error($model,'dependencies_aee_room'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_indoor_sports_court'); ?>
                        <?php echo $form->textField($model,'dependencies_indoor_sports_court'); ?>
                        <?php echo $form->error($model,'dependencies_indoor_sports_court'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_outdoor_sports_court'); ?>
                        <?php echo $form->textField($model,'dependencies_outdoor_sports_court'); ?>
                        <?php echo $form->error($model,'dependencies_outdoor_sports_court'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_kitchen'); ?>
                        <?php echo $form->textField($model,'dependencies_kitchen'); ?>
                        <?php echo $form->error($model,'dependencies_kitchen'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_library'); ?>
                        <?php echo $form->textField($model,'dependencies_library'); ?>
                        <?php echo $form->error($model,'dependencies_library'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_reading_room'); ?>
                        <?php echo $form->textField($model,'dependencies_reading_room'); ?>
                        <?php echo $form->error($model,'dependencies_reading_room'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_playground'); ?>
                        <?php echo $form->textField($model,'dependencies_playground'); ?>
                        <?php echo $form->error($model,'dependencies_playground'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_nursery'); ?>
                        <?php echo $form->textField($model,'dependencies_nursery'); ?>
                        <?php echo $form->error($model,'dependencies_nursery'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_outside_bathroom'); ?>
                        <?php echo $form->textField($model,'dependencies_outside_bathroom'); ?>
                        <?php echo $form->error($model,'dependencies_outside_bathroom'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_inside_bathroom'); ?>
                        <?php echo $form->textField($model,'dependencies_inside_bathroom'); ?>
                        <?php echo $form->error($model,'dependencies_inside_bathroom'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_child_bathroom'); ?>
                        <?php echo $form->textField($model,'dependencies_child_bathroom'); ?>
                        <?php echo $form->error($model,'dependencies_child_bathroom'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_prysical_disability_bathroom'); ?>
                        <?php echo $form->textField($model,'dependencies_prysical_disability_bathroom'); ?>
                        <?php echo $form->error($model,'dependencies_prysical_disability_bathroom'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_physical_disability_support'); ?>
                        <?php echo $form->textField($model,'dependencies_physical_disability_support'); ?>
                        <?php echo $form->error($model,'dependencies_physical_disability_support'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_bathroom_with_shower'); ?>
                        <?php echo $form->textField($model,'dependencies_bathroom_with_shower'); ?>
                        <?php echo $form->error($model,'dependencies_bathroom_with_shower'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_refectory'); ?>
                        <?php echo $form->textField($model,'dependencies_refectory'); ?>
                        <?php echo $form->error($model,'dependencies_refectory'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_storeroom'); ?>
                        <?php echo $form->textField($model,'dependencies_storeroom'); ?>
                        <?php echo $form->error($model,'dependencies_storeroom'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_warehouse'); ?>
                        <?php echo $form->textField($model,'dependencies_warehouse'); ?>
                        <?php echo $form->error($model,'dependencies_warehouse'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_auditorium'); ?>
                        <?php echo $form->textField($model,'dependencies_auditorium'); ?>
                        <?php echo $form->error($model,'dependencies_auditorium'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_covered_patio'); ?>
                        <?php echo $form->textField($model,'dependencies_covered_patio'); ?>
                        <?php echo $form->error($model,'dependencies_covered_patio'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_uncovered_patio'); ?>
                        <?php echo $form->textField($model,'dependencies_uncovered_patio'); ?>
                        <?php echo $form->error($model,'dependencies_uncovered_patio'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_student_accomodation'); ?>
                        <?php echo $form->textField($model,'dependencies_student_accomodation'); ?>
                        <?php echo $form->error($model,'dependencies_student_accomodation'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_instructor_accomodation'); ?>
                        <?php echo $form->textField($model,'dependencies_instructor_accomodation'); ?>
                        <?php echo $form->error($model,'dependencies_instructor_accomodation'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_green_area'); ?>
                        <?php echo $form->textField($model,'dependencies_green_area'); ?>
                        <?php echo $form->error($model,'dependencies_green_area'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_laundry'); ?>
                        <?php echo $form->textField($model,'dependencies_laundry'); ?>
                        <?php echo $form->error($model,'dependencies_laundry'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'dependencies_none'); ?>
                        <?php echo $form->textField($model,'dependencies_none'); ?>
                        <?php echo $form->error($model,'dependencies_none'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'classroom_count'); ?>
                        <?php echo $form->textField($model,'classroom_count'); ?>
                        <?php echo $form->error($model,'classroom_count'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'used_classroom_count'); ?>
                        <?php echo $form->textField($model,'used_classroom_count'); ?>
                        <?php echo $form->error($model,'used_classroom_count'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'equipments_tv'); ?>
                        <?php echo $form->textField($model,'equipments_tv'); ?>
                        <?php echo $form->error($model,'equipments_tv'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'equipments_vcr'); ?>
                        <?php echo $form->textField($model,'equipments_vcr'); ?>
                        <?php echo $form->error($model,'equipments_vcr'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'equipments_dvd'); ?>
                        <?php echo $form->textField($model,'equipments_dvd'); ?>
                        <?php echo $form->error($model,'equipments_dvd'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'equipments_satellite_dish'); ?>
                        <?php echo $form->textField($model,'equipments_satellite_dish'); ?>
                        <?php echo $form->error($model,'equipments_satellite_dish'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'equipments_copier'); ?>
                        <?php echo $form->textField($model,'equipments_copier'); ?>
                        <?php echo $form->error($model,'equipments_copier'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'equipments_overhead_projector'); ?>
                        <?php echo $form->textField($model,'equipments_overhead_projector'); ?>
                        <?php echo $form->error($model,'equipments_overhead_projector'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'equipments_printer'); ?>
                        <?php echo $form->textField($model,'equipments_printer'); ?>
                        <?php echo $form->error($model,'equipments_printer'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'equipments_stereo_system'); ?>
                        <?php echo $form->textField($model,'equipments_stereo_system'); ?>
                        <?php echo $form->error($model,'equipments_stereo_system'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'equipments_data_show'); ?>
                        <?php echo $form->textField($model,'equipments_data_show'); ?>
                        <?php echo $form->error($model,'equipments_data_show'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'equipments_fax'); ?>
                        <?php echo $form->textField($model,'equipments_fax'); ?>
                        <?php echo $form->error($model,'equipments_fax'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'equipments_camera'); ?>
                        <?php echo $form->textField($model,'equipments_camera'); ?>
                        <?php echo $form->error($model,'equipments_camera'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'equipments_computer'); ?>
                        <?php echo $form->textField($model,'equipments_computer'); ?>
                        <?php echo $form->error($model,'equipments_computer'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'administrative_computers_count'); ?>
                        <?php echo $form->textField($model,'administrative_computers_count'); ?>
                        <?php echo $form->error($model,'administrative_computers_count'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'student_computers_count'); ?>
                        <?php echo $form->textField($model,'student_computers_count'); ?>
                        <?php echo $form->error($model,'student_computers_count'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'internet_access'); ?>
                        <?php echo $form->textField($model,'internet_access'); ?>
                        <?php echo $form->error($model,'internet_access'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'bandwidth'); ?>
                        <?php echo $form->textField($model,'bandwidth'); ?>
                        <?php echo $form->error($model,'bandwidth'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'employees_count'); ?>
                        <?php echo $form->textField($model,'employees_count'); ?>
                        <?php echo $form->error($model,'employees_count'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'feeding'); ?>
                        <?php echo $form->textField($model,'feeding'); ?>
                        <?php echo $form->error($model,'feeding'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'aee'); ?>
                        <?php echo $form->textField($model,'aee'); ?>
                        <?php echo $form->error($model,'aee'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'complementary_activities'); ?>
                        <?php echo $form->textField($model,'complementary_activities'); ?>
                        <?php echo $form->error($model,'complementary_activities'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'modalities_regular'); ?>
                        <?php echo $form->textField($model,'modalities_regular'); ?>
                        <?php echo $form->error($model,'modalities_regular'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'modalities_especial'); ?>
                        <?php echo $form->textField($model,'modalities_especial'); ?>
                        <?php echo $form->error($model,'modalities_especial'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'modalities_eja'); ?>
                        <?php echo $form->textField($model,'modalities_eja'); ?>
                        <?php echo $form->error($model,'modalities_eja'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_regular_education_creche'); ?>
                        <?php echo $form->textField($model,'stage_regular_education_creche'); ?>
                        <?php echo $form->error($model,'stage_regular_education_creche'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_regular_education_preschool'); ?>
                        <?php echo $form->textField($model,'stage_regular_education_preschool'); ?>
                        <?php echo $form->error($model,'stage_regular_education_preschool'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_regular_education_fundamental_eigth_years'); ?>
                        <?php echo $form->textField($model,'stage_regular_education_fundamental_eigth_years'); ?>
                        <?php echo $form->error($model,'stage_regular_education_fundamental_eigth_years'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_regular_education_fundamental_nine_years'); ?>
                        <?php echo $form->textField($model,'stage_regular_education_fundamental_nine_years'); ?>
                        <?php echo $form->error($model,'stage_regular_education_fundamental_nine_years'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_regular_education_high_school'); ?>
                        <?php echo $form->textField($model,'stage_regular_education_high_school'); ?>
                        <?php echo $form->error($model,'stage_regular_education_high_school'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_regular_education_high_school_integrated'); ?>
                        <?php echo $form->textField($model,'stage_regular_education_high_school_integrated'); ?>
                        <?php echo $form->error($model,'stage_regular_education_high_school_integrated'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_regular_education_high_school_normal_mastership'); ?>
                        <?php echo $form->textField($model,'stage_regular_education_high_school_normal_mastership'); ?>
                        <?php echo $form->error($model,'stage_regular_education_high_school_normal_mastership'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_regular_education_high_school_preofessional_education'); ?>
                        <?php echo $form->textField($model,'stage_regular_education_high_school_preofessional_education'); ?>
                        <?php echo $form->error($model,'stage_regular_education_high_school_preofessional_education'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_special_education_creche'); ?>
                        <?php echo $form->textField($model,'stage_special_education_creche'); ?>
                        <?php echo $form->error($model,'stage_special_education_creche'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_special_education_preschool'); ?>
                        <?php echo $form->textField($model,'stage_special_education_preschool'); ?>
                        <?php echo $form->error($model,'stage_special_education_preschool'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_special_education_fundamental_eigth_years'); ?>
                        <?php echo $form->textField($model,'stage_special_education_fundamental_eigth_years'); ?>
                        <?php echo $form->error($model,'stage_special_education_fundamental_eigth_years'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_special_education_fundamental_nine_years'); ?>
                        <?php echo $form->textField($model,'stage_special_education_fundamental_nine_years'); ?>
                        <?php echo $form->error($model,'stage_special_education_fundamental_nine_years'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_special_education_high_school'); ?>
                        <?php echo $form->textField($model,'stage_special_education_high_school'); ?>
                        <?php echo $form->error($model,'stage_special_education_high_school'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_special_education_high_school_integrated'); ?>
                        <?php echo $form->textField($model,'stage_special_education_high_school_integrated'); ?>
                        <?php echo $form->error($model,'stage_special_education_high_school_integrated'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_special_education_high_school_normal_mastership'); ?>
                        <?php echo $form->textField($model,'stage_special_education_high_school_normal_mastership'); ?>
                        <?php echo $form->error($model,'stage_special_education_high_school_normal_mastership'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_special_education_high_school_professional_education'); ?>
                        <?php echo $form->textField($model,'stage_special_education_high_school_professional_education'); ?>
                        <?php echo $form->error($model,'stage_special_education_high_school_professional_education'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_special_education_eja_fundamental_education'); ?>
                        <?php echo $form->textField($model,'stage_special_education_eja_fundamental_education'); ?>
                        <?php echo $form->error($model,'stage_special_education_eja_fundamental_education'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_special_education_eja_high_school_education'); ?>
                        <?php echo $form->textField($model,'stage_special_education_eja_high_school_education'); ?>
                        <?php echo $form->error($model,'stage_special_education_eja_high_school_education'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_education_eja_fundamental_education'); ?>
                        <?php echo $form->textField($model,'stage_education_eja_fundamental_education'); ?>
                        <?php echo $form->error($model,'stage_education_eja_fundamental_education'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_education_eja_fundamental_education_projovem'); ?>
                        <?php echo $form->textField($model,'stage_education_eja_fundamental_education_projovem'); ?>
                        <?php echo $form->error($model,'stage_education_eja_fundamental_education_projovem'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'stage_education_eja_high_school_education'); ?>
                        <?php echo $form->textField($model,'stage_education_eja_high_school_education'); ?>
                        <?php echo $form->error($model,'stage_education_eja_high_school_education'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'basic_education_cycle_organized'); ?>
                        <?php echo $form->textField($model,'basic_education_cycle_organized'); ?>
                        <?php echo $form->error($model,'basic_education_cycle_organized'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'different_location'); ?>
                        <?php echo $form->textField($model,'different_location'); ?>
                        <?php echo $form->error($model,'different_location'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'sociocultural_didactic_material_none'); ?>
                        <?php echo $form->textField($model,'sociocultural_didactic_material_none'); ?>
                        <?php echo $form->error($model,'sociocultural_didactic_material_none'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'sociocultural_didactic_material_quilombola'); ?>
                        <?php echo $form->textField($model,'sociocultural_didactic_material_quilombola'); ?>
                        <?php echo $form->error($model,'sociocultural_didactic_material_quilombola'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'sociocultural_didactic_material_native'); ?>
                        <?php echo $form->textField($model,'sociocultural_didactic_material_native'); ?>
                        <?php echo $form->error($model,'sociocultural_didactic_material_native'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'native_education'); ?>
                        <?php echo $form->textField($model,'native_education'); ?>
                        <?php echo $form->error($model,'native_education'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'native_education_language_native'); ?>
                        <?php echo $form->textField($model,'native_education_language_native'); ?>
                        <?php echo $form->error($model,'native_education_language_native'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'native_education_language_portuguese'); ?>
                        <?php echo $form->textField($model,'native_education_language_portuguese'); ?>
                        <?php echo $form->error($model,'native_education_language_portuguese'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'edcenso_native_languages_fk'); ?>
                        <?php echo $form->textField($model,'edcenso_native_languages_fk'); ?>
                        <?php echo $form->error($model,'edcenso_native_languages_fk'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'brazil_literate'); ?>
                        <?php echo $form->textField($model,'brazil_literate'); ?>
                        <?php echo $form->error($model,'brazil_literate'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'open_weekend'); ?>
                        <?php echo $form->textField($model,'open_weekend'); ?>
                        <?php echo $form->error($model,'open_weekend'); ?>
                    </div>

                                        <div class="control-group">
                        <?php echo $form->labelEx($model,'pedagogical_formation_by_alternance'); ?>
                        <?php echo $form->textField($model,'pedagogical_formation_by_alternance'); ?>
                        <?php echo $form->error($model,'pedagogical_formation_by_alternance'); ?>
                    </div>

                                    <div class="control-group buttonWizardBar">
                    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'),array('class' => 'buttonLink button')); ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
