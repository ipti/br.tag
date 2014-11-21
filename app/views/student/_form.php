<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/student/form/_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/student/form/validations.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/student/form/pagination.js', CClientScript::POS_END);

$cs->registerScriptFile($baseUrl . '/js/enrollment/form/_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/enrollment/form/validations.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/enrollment/form/functions.js', CClientScript::POS_END);

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'student',
    'enableAjaxValidation' => false,
        ));
//@done S1 - 08 - 11 - Não precisar selecionar a escola, ele já estará em uma

$MEs = array();
if(isset($modelEnrollment)){
	foreach ($modelEnrollment as $i=>$me){
		array_push($MEs, $me->attributes);
	}
}
?>

<div class="row-fluid">
	<div class="span12">
		<h3 class="heading-mosaic"><?php echo $title; ?><span> | <?php echo Yii::t('default', 'Fields with * are required.') ?></span>
		</h3>
		<div class="buttons">
			<a data-toggle="tab"
				class='btn btn-icon btn-default prev glyphicons circle_arrow_left'
				style="display: none;"><?php echo Yii::t('default', 'Previous') ?><i></i></a>
			<a data-toggle="tab"
				class='btn btn-icon btn-primary next glyphicons circle_arrow_right'><?php echo Yii::t('default', 'Next') ?><i></i></a>
            <?php echo CHtml::htmlButton('<i></i>' . ($modelStudentIdentification->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save')), array('class' => 'btn btn-icon btn-primary last glyphicons circle_ok', 'style' => 'display:none', 'type' => 'submit')); ?>
        </div>
	</div>
</div>

<div class="innerLR">
	<div class="widget widget-tabs border-bottom-none">
        <?php
        echo $form->errorSummary($modelStudentIdentification);
        echo $form->errorSummary($modelStudentDocumentsAndAddress);
        foreach ($modelEnrollment as $i=>$me){
        	echo $form->errorSummary($me);
        }
        ?>
        <div class="widget-head">
			<ul class="tab-student">
				<li id="tab-student-identify" class="active"><a
					class="glyphicons vcard" href="#student-identify" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Identification') ?></a></li>
				<li id="tab-student-documents"><a class="glyphicons credit_card"
					href="#student-documents" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Documents') ?></a></li>
				<li id="tab-student-address">
					<a class="glyphicons home" href="#student-address"    data-toggle="tab"><i></i><?php echo Yii::t('default', 'Address') ?></a>
				</li>
				<li id="tab-student-enrollment">
					<a class="glyphicons book_open" href="#student-enrollment" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Enrollment') ?></a>
				</li>
			</ul>
		</div>

		<div class="widget-body form-horizontal">
			<div class="tab-content">
				<!-- Tab content -->
				<!-- Tab Student Identify -->
				<div class="tab-pane active" id="student-identify">
					<div class="row-fluid">
						<div class=" span5">
							<div class="control-group">
								<div class="controls">
                                    <?php echo $form->hiddenField($modelStudentIdentification, 'school_inep_id_fk', array('value' => Yii::app()->user->school)); ?>
                                </div>
							</div>
							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'name', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'name', array('size' => 60, 'maxlength' => 100)); ?>
                                    <span
										class="btn-action single glyphicons circle_question_mark"
										data-toggle="tooltip" data-placement="top"
										data-original-title="<?php echo Yii::t('help', 'Student Full Name'); ?>"><i></i></span>
                                    <?php echo $form->error($modelStudentIdentification, 'name'); ?>
                                </div>
							</div>

							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'nis', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'nis', array('size' => 11, 'maxlength' => 11)); ?>
                                    <span
										class="btn-action single glyphicons circle_question_mark"
										data-toggle="tooltip" data-placement="top"
										data-original-title="<?php echo Yii::t('help', 'NIS') . ' ' . Yii::t('help', 'Only Numbers'); ?>"><i></i></span>
                                    <?php echo $form->error($modelStudentIdentification, 'nis'); ?>
                                </div>
							</div>
							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'birthday', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'birthday', array('size' => 10, 'maxlength' => 10)); ?>
                                    <span
										class="btn-action single glyphicons circle_question_mark"
										data-toggle="tooltip" data-placement="top"
										data-original-title="<?php echo Yii::t('help', 'Birthday') . ' ' . Yii::t('help', 'Date'); ?>"><i></i></span>
                                    <?php echo $form->error($modelStudentIdentification, 'birthday'); ?>
                                </div>
							</div>

							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'sex', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelStudentIdentification, 'sex', array(null => "Selecione o sexo", "1" => "Masculino", "2" => "Feminino"), array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'sex'); ?>
                                </div>
							</div>

							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'color_race', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->DropDownList($modelStudentIdentification, 'color_race', array(null => "Selecione a cor/raça",
                                        "0" => "Não declarada",
                                        "1" => "Branca",
                                        "2" => "Preta",
                                        "3" => "Parda",
                                        "4" => "Amarela",
                                        "5" => "Indígena"), array('class' => 'select-search-off'));
                                    ?>
                                    <?php echo $form->error($modelStudentIdentification, 'color_race'); ?>
                                </div>
							</div>
							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelStudentIdentification, 'filiation', array(null => "Selecione a filiação", "0" => "Não declarado/Ignorado", "1" => "Pai e/ou Mãe"), array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'filiation'); ?>
                                </div>
							</div>

							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'mother_name', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'mother_name', array('size' => 60, 'maxlength' => 100, "disabled" => "disabled")); ?>
                                    <span
										class="btn-action single glyphicons circle_question_mark"
										data-toggle="tooltip" data-placement="top"
										data-original-title="<?php echo Yii::t('help', 'Full name'); ?>"><i></i></span>
                                    <?php echo $form->error($modelStudentIdentification, 'mother_name'); ?>
                                </div>
							</div>

							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'father_name', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'father_name', array('size' => 60, 'maxlength' => 100, "disabled" => "disabled")); ?>
                                    <span
										class="btn-action single glyphicons circle_question_mark"
										data-toggle="tooltip" data-placement="top"
										data-original-title="<?php echo Yii::t('help', 'Full name'); ?>"><i></i></span>
                                    <?php echo $form->error($modelStudentIdentification, 'father_name'); ?>
                                </div>
							</div>

							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'nationality', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentIdentification, 'nationality', array(null => "Selecione a nacionalidade", "1" => "Brasileira", "2" => "Brasileira: Nascido no exterior ou Naturalizado", "3" => "Estrangeira"), array('class' => 'select-search-off'), array('ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('student/getnations'),
                                            'update' => '#StudentIdentification_edcenso_nation_fk'
                                    )));
                                    ?>
                                    <?php echo $form->error($modelStudentIdentification, 'nationality'); ?>
                                </div>
							</div>

							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'edcenso_nation_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentIdentification, 'edcenso_nation_fk', CHtml::listData(EdcensoNation::model()->findAll(array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione uma nação", 'class' => 'select-search-on nationality-sensitive no-br', 'disabled' => 'disabled'));
                                    ?>
                                    <?php echo $form->error($modelStudentIdentification, 'edcenso_nation_fk'); ?>
                                </div>
							</div>

							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'edcenso_uf_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentIdentification, 'edcenso_uf_fk', 
                                    		CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'), 
                                    		array(
		                                        'ajax' => array(
		                                            'type' => 'POST',
		                                            'url' => CController::createUrl('student/getcities&rt=0'),
		                                            'update' => '#StudentIdentification_edcenso_city_fk'
		                                        ),
		                                        "prompt" => "Selecione um estado",
		                                        "class" => "select-search-on nationality-sensitive br",
		                                        "disabled" => "disabled",
                                    ));
                                    ?>
                                    <?php echo $form->error($modelStudentIdentification, 'edcenso_uf_fk'); ?>
                                </div>
							</div>

							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'edcenso_city_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentIdentification, 'edcenso_city_fk', CHtml::listData(EdcensoCity::model()->findAllByAttributes(array('edcenso_uf_fk' => $modelStudentIdentification->edcenso_uf_fk), array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione uma cidade",
                                        "disabled" => "disabled", 'class' => 'select-search-on nationality-sensitive br'));
                                    ?>
                                    <?php echo $form->error($modelStudentIdentification, 'edcenso_city_fk'); ?>
                                </div>
							</div>


							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'send_year', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelStudentIdentification, 'send_year', array('value' => date('Y')+1, 'uncheckValue' => (date('Y')))); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'send_year'); ?>
                                </div>
							</div>
						</div>
						<div class=" span5">
							<div class="separator"></div>

							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'deficiency', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelStudentIdentification, 'deficiency', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'deficiency'); ?>
                                </div>
							</div>

							<div class="control-group" id="deficiency_type">
								<label class="control-label"><?php echo Yii::t('default', 'Deficiency Type'); ?></label>
								<div class="uniformjs margin-left">
									<label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_blindness']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_blindness', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_low_vision']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_low_vision', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_deafness']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_deafness', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_disability_hearing']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_disability_hearing', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_deafblindness']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_deafblindness', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_phisical_disability']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_phisical_disability', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_intelectual_disability']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_intelectual_disability', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox"
										style="display: none">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_multiple_disabilities']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_multiple_disabilities', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_autism']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_autism', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_aspenger_syndrome']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_aspenger_syndrome', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_rett_syndrome']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_rett_syndrome', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_childhood_disintegrative_disorder']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_childhood_disintegrative_disorder', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['deficiency_type_gifted']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'deficiency_type_gifted', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
								</div>
							</div>
							<div id="resource_null">
                                <?php echo CHtml::activeHiddenField($modelStudentIdentification, 'resource_aid_lector', array('value'=>null, 'disabled'=>'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelStudentIdentification, 'resource_aid_transcription', array('value'=>null, 'disabled'=>'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelStudentIdentification, 'resource_interpreter_guide', array('value'=>null, 'disabled'=>'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelStudentIdentification, 'resource_interpreter_libras', array('value'=>null, 'disabled'=>'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelStudentIdentification, 'resource_lip_reading', array('value'=>null, 'disabled'=>'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelStudentIdentification, 'resource_zoomed_test_16', array('value'=>null, 'disabled'=>'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelStudentIdentification, 'resource_zoomed_test_20', array('value'=>null, 'disabled'=>'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelStudentIdentification, 'resource_zoomed_test_24', array('value'=>null, 'disabled'=>'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelStudentIdentification, 'resource_braille_test', array('value'=>null, 'disabled'=>'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelStudentIdentification, 'resource_none', array('value'=>null, 'disabled'=>'disabled')); ?>
                            </div>

							<div class="control-group" id="resource_type">
								<label class="control-label"><?php echo Yii::t('default', 'Required Resources'); ?></label>
								<div class="uniformjs margin-left">
									<label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_aid_lector']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_aid_lector', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_aid_transcription']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_aid_transcription', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_interpreter_guide']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_interpreter_guide', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_interpreter_libras']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_interpreter_libras', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_lip_reading']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_lip_reading', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_zoomed_test_16']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_zoomed_test_16', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_zoomed_test_20']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_zoomed_test_20', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_zoomed_test_24']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_zoomed_test_24', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_braille_test']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_braille_test', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label> <label class="checkbox"
										style="display: none">
                                        <?php echo StudentIdentification::model()->attributeLabels()['resource_none']; ?>
                                        <?php echo $form->checkBox($modelStudentIdentification, 'resource_none', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Tab Student Documents -->
				<div class="tab-pane" id="student-documents">
					<div class="row-fluid">

						<div class=" span5">
							<div class="widget widget-scroll margin-bottom-none"
								data-toggle="collapse-widget" data-scroll-height="223px"
								data-collapse-closed="false">
								<div class="widget-head">
									<h4 class="heading glyphicons circle_question_mark">
										<i></i>Justificativa
									</h4>
								</div>
								<div class="widget-body in" style="height: auto;">
									<div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'document_failure_lack', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?php echo $form->DropDownList($modelStudentDocumentsAndAddress, 'document_failure_lack', array(null => "Selecione uma justificativa", "1" => "Aluno não possui documento", "2" => "Escola não possui informação de documento do aluno"), array('class' => 'select-search-off')); ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'document_failure_lack'); ?>
                                        </div>
									</div>
								</div>
							</div>

							<div class="separator"></div>

							<div class="widget widget-scroll margin-bottom-none"
								data-toggle="collapse-widget" data-scroll-height="223px"
								data-collapse-closed="false">
								<div class="widget-head">
									<h4 class="heading glyphicons book_open">
										<i></i>Certidão Civil
									</h4>
								</div>
								<div class="widget-body in" style="height: auto;">
									<div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?php echo $form->DropDownList($modelStudentDocumentsAndAddress, 'civil_certification', array(null => "Selecione o modelo", "1" => "Modelo Antigo", "2" => "Modelo Novo"), array("class" => "select-search-off nationality-sensitive br", "disabled" => "disabled")); ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification'); ?>
                                        </div>
									</div>
                                    <?php //@done S1 - Alterar tipo de certidão civil para dropdown?>
                                    <div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_type', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?php echo $form->DropdownList($modelStudentDocumentsAndAddress, 'civil_certification_type', array(null => "Selecione o tipo", "1" => "Nascimento", "2" => "Casamento"), array("class" => "select-search-off nationality-sensitive br", "disabled" => "disabled")); ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_type'); ?>
                                        </div>
									</div>
									<div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_term_number', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_term_number', array('size' => 8, 'maxlength' => 8, "disabled" => "disabled", "class" => "nationality-sensitive br")); ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_term_number'); ?>
                                        </div>
									</div>

									<div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_sheet', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_sheet', array('size' => 4, 'maxlength' => 4, "disabled" => "disabled", "class" => "nationality-sensitive br")); ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_sheet'); ?>
                                        </div>
									</div>

									<div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_book', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_book', array('size' => 8, 'maxlength' => 8, "disabled" => "disabled", "class" => "nationality-sensitive br")); ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_book'); ?>
                                        </div>
									</div>

									<div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_certification_date', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_certification_date', array('size' => 10, 'maxlength' => 10, "disabled" => "disabled", "class" => "nationality-sensitive br")); ?>
                                            <span
												class="btn-action single glyphicons circle_question_mark"
												data-toggle="tooltip" data-placement="top"
												data-original-title="<?php echo Yii::t('help', 'Date'); ?>"><i></i></span>

                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_certification_date'); ?>
                                        </div>
									</div>

									<div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'notary_office_uf_fk', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?php
                                            echo $form->dropDownList($modelStudentDocumentsAndAddress, 'notary_office_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'), array(
                                                'ajax' => array(
                                                    'type' => 'POST',
                                                    'url' => CController::createUrl('student/getcities&rt=1'),
                                                    'update' => '#StudentDocumentsAndAddress_notary_office_city_fk'
                                                ),
                                                "prompt" => "Selecione um estado",
                                                "class" => "select-search-on nationality-sensitive br",
                                                "disabled" => "disabled"));
                                            ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'notary_office_uf_fk'); ?>
                                        </div>
									</div>

									<div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'notary_office_city_fk', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?php
                                            echo $form->dropDownList($modelStudentDocumentsAndAddress, 'notary_office_city_fk', CHtml::listData(EdcensoCity::model()->findAllByAttributes(array('edcenso_uf_fk' => $modelStudentDocumentsAndAddress->notary_office_uf_fk), array('order' => 'name')), 'id', 'name'), array(
                                                'ajax' => array(
                                                    'type' => 'POST',
                                                    'url' => CController::createUrl('student/getnotaryoffice'),
                                                    'update' => '#StudentDocumentsAndAddress_edcenso_notary_office_fk'
                                                ),
                                                "prompt" => "Selecione uma cidade",
                                                "class" => "select-search-on nationality-sensitive br",
                                                "disabled" => "disabled"));
                                            ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'notary_office_city_fk'); ?>
                                        </div>
									</div>

									<div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'edcenso_notary_office_fk', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?php
                                            echo $form->dropDownList($modelStudentDocumentsAndAddress, 'edcenso_notary_office_fk', CHtml::listData(EdcensoNotaryOffice::model()->findAllByAttributes(array('city' => $modelStudentDocumentsAndAddress->notary_office_city_fk), array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione um cartório",
                                                "class" => "select-search-on nationality-sensitive br", "disabled" => "disabled"));
                                            ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'edcenso_notary_office_fk'); ?>
                                        </div>
									</div>

									<div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'civil_register_enrollment_number', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'civil_register_enrollment_number', array('size' => 32, 'maxlength' => 32, "disabled" => "disabled", "class" => "nationality-sensitive br")); ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'civil_register_enrollment_number'); ?>
                                        </div>
									</div>

								</div>
							</div>
						</div>

						<div class=" span5">
							<div class="widget widget-scroll margin-bottom-none"
								data-toggle="collapse-widget" data-scroll-height="223px"
								data-collapse-closed="false">
								<div class="widget-head">
									<h4 class="heading glyphicons nameplate">
										<i></i>Cadastro de Pessoa Física
									</h4>
								</div>
								<div class="widget-body in" style="height: auto;">
									<div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'cpf', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'cpf', array('size' => 11, 'maxlength' => 11, "disabled" => "disabled", "class" => "nationality-sensitive br")); ?>
                                            <span
												class="btn-action single glyphicons circle_question_mark"
												data-toggle="tooltip" data-placement="top"
												data-original-title="<?php echo Yii::t('help', 'Only Numbers'); ?>"><i></i></span>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'cpf'); ?>
                                        </div>
									</div>
								</div>
							</div>

							<div class="separator"></div>

							<div class="widget widget-scroll margin-bottom-none"
								data-toggle="collapse-widget" data-scroll-height="223px"
								data-collapse-closed="false">
								<div class="widget-head">
									<h4 class="heading glyphicons airplane">
										<i></i>Passaporte
									</h4>
								</div>
								<div class="widget-body in" style="height: auto;">
									<div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'foreign_document_or_passport', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'foreign_document_or_passport', array('size' => 20, 'maxlength' => 20, "disabled" => "disabled", "class" => "nationality-sensitive n-br")); ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'foreign_document_or_passport'); ?>
                                        </div>
									</div>
								</div>
							</div>

							<div class="separator"></div>

							<div class="widget widget-scroll margin-bottom-none"
								data-toggle="collapse-widget" data-scroll-height="223px"
								data-collapse-closed="false">
								<div class="widget-head">
									<h4 class="heading glyphicons nameplate">
										<i></i>Registro Geral
									</h4>
								</div>
								<div class="widget-body in" style="height: auto;">

									<div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'rg_number', array('size' => 20, 'maxlength' => 20, "disabled" => "disabled", "class" => "nationality-sensitive br")); ?>
                                            <span
												class="btn-action single glyphicons circle_question_mark"
												data-toggle="tooltip" data-placement="top"
												data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ª, º, space and -.'); ?>"><i></i></span>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number'); ?>
                                        </div>
									</div>
									<div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number_complement', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'rg_number_complement', array('size' => 4, 'maxlength' => 4, "disabled" => "disabled", "class" => "nationality-sensitive br")); ?>
                                            <span
												class="btn-action single glyphicons circle_question_mark"
												data-toggle="tooltip" data-placement="top"
												data-original-title="<?php echo Yii::t('help', 'Max length: ') . '20'; ?>"><i></i></span>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_complement'); ?>
                                        </div>
									</div>

									<div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number_edcenso_organ_id_emitter_fk', array('class' => 'control-label')); ?>
                                        <div class="controls">

                                            <?php echo $form->DropdownList($modelStudentDocumentsAndAddress, 'rg_number_edcenso_organ_id_emitter_fk', CHtml::listData(EdcensoOrganIdEmitter::model()->findAll(array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione um órgão emissor da identidade", "class" => "select-search-on nationality-sensitive br", "disabled" => "disabled"));
                                            ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_edcenso_organ_id_emitter_fk'); ?>
                                        </div>
									</div>

									<div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number_edcenso_uf_fk', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?php echo $form->dropDownList($modelStudentDocumentsAndAddress, 'rg_number_edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione um estado", "class" => "select-search-on nationality-sensitive br", "disabled" => "disabled"));
                                            ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_edcenso_uf_fk'); ?>
                                        </div>
									</div>

									<div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'rg_number_expediction_date', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'rg_number_expediction_date', array('size' => 10, 'maxlength' => 10, "disabled" => "disabled", "class" => "nationality-sensitive br")); ?>
                                            <span
												class="btn-action single glyphicons circle_question_mark"
												data-toggle="tooltip" data-placement="top"
												data-original-title="<?php echo Yii::t('help', 'Date'); ?>"><i></i></span>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'rg_number_expediction_date'); ?>
                                        </div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Tab Student Address -->
				<div class="tab-pane" id="student-address">
					<div class="row-fluid">
						<div class=" span6">

							<div class="separator"></div>

							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'residence_zone', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelStudentDocumentsAndAddress, 'residence_zone', array(null => "Selecione uma zona", "1" => "URBANA", "2" => "RURAL"), array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'residence_zone'); ?>
                                </div>
							</div>

							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'cep', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->textField($modelStudentDocumentsAndAddress, 'cep', 
                                    	array('size' => 8, 
                                    		'maxlength' => 8,
	                                        'ajax' => array(
	                                            'type' => 'POST',
	                                            'url' => CController::createUrl('Instructor/getcitybycep'),
	                                            'data' => array('cep' => 'js:this.value'),
	                                            'success' => "function(data){
	                                     				data = jQuery.parseJSON(data);
	                                     					if(data.UF == null) 	
	                                        					$(formDocumentsAndAddress+'cep').val('').trigger('focusout');
	                                     					$(formDocumentsAndAddress+'edcenso_uf_fk').val(data['UF']).trigger('change').select2('readonly',data.UF != null);
	                                     					setTimeout(function(){
	                                        					$(formDocumentsAndAddress+'edcenso_city_fk').val(data['City']).trigger('change').select2('readonly',data.City != null);
	                                        				}, 500);
	                                    			}"
                                   	)));
                                    ?>
                                    <span
										class="btn-action single glyphicons circle_question_mark"
										data-toggle="tooltip" data-placement="top"
										data-original-title="<?php echo Yii::t('help', 'Valid Cep') . " " . Yii::t('help', 'Only Numbers') . ' ' . Yii::t('help', 'Max length') . '8.'; ?>"><i></i></span>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'cep'); ?>
                                </div>
							</div>

							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'address', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'address', array('size' => 60, 'maxlength' => 100)); ?>
                                    <span
										class="btn-action single glyphicons circle_question_mark"
										data-toggle="tooltip" data-placement="top"
										data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ª, º, space and -.'); ?>"><i></i></span>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'address'); ?>
                                </div>
							</div>

							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'number', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'number', array('size' => 10, 'maxlength' => 10)); ?>
                                    <span
										class="btn-action single glyphicons circle_question_mark"
										data-toggle="tooltip" data-placement="top"
										data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'number'); ?>
                                </div>
							</div>

							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'complement', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'complement', array('size' => 20, 'maxlength' => 20)); ?>
                                    <span
										class="btn-action single glyphicons circle_question_mark"
										data-toggle="tooltip" data-placement="top"
										data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'complement'); ?>
                                </div>
							</div>

							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'neighborhood', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentDocumentsAndAddress, 'neighborhood', array('size' => 50, 'maxlength' => 50)); ?>
                                    <span
										class="btn-action single glyphicons circle_question_mark"
										data-toggle="tooltip" data-placement="top"
										data-original-title="<?php echo Yii::t('help', 'Only characters A-Z, 0-9, ., /, -, ª, º, space and ,.'); ?>"><i></i></span>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'neighborhood'); ?>
                                </div>
							</div>

							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'edcenso_uf_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentDocumentsAndAddress, 'edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'), array(
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('student/getcities&rt=2'),
                                            'update' => '#StudentDocumentsAndAddress_edcenso_city_fk'
                                        ),
                                        "prompt" => "Selecione um estado",
                                        "class" => "select-search-on"));
                                    ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'edcenso_uf_fk'); ?>
                                </div>
							</div>

							<div class="control-group">
                                <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'edcenso_city_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentDocumentsAndAddress, 'edcenso_city_fk', CHtml::listData(EdcensoCity::model()->findAllByAttributes(array('edcenso_uf_fk' => $modelStudentDocumentsAndAddress->edcenso_uf_fk), array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione uma cidade", "class" => "select-search-on"));
                                    ?>
                                    <?php echo $form->error($modelStudentDocumentsAndAddress, 'edcenso_city_fk'); ?>
                                </div>
							</div>
						</div>
					</div>
				</div>
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				<!-- Tab Student Enrollment -->
				<div class="tab-pane" id="student-enrollment">
                    <div class="row-fluid">
                        <div class="span5">
                            <div class="control-group">
                                <div class="controls">
                                <?php $modelEnrollment = $modelEnrollment[0]; ?>
                                    <?php echo $form->hiddenField($modelEnrollment,'school_inep_id_fk',array('value'=>Yii::app()->user->school)); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php  echo $form->labelEx($modelEnrollment, 'classroom_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($modelEnrollment, 'classroom_fk', CHtml::listData(Classroom::model()->findAll("school_year = ".Yii::app()->user->year."", array('order' => 'name')), 'id', 'name'), array("prompt" => "Selecione uma Turma", 'class'=>'select-search-on'));?>
                                    <?php echo $form->error($modelEnrollment, 'classroom_fk'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelEnrollment, 'unified_class', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelEnrollment, 'unified_class', array(null => "Selecione o tipo de turma infantil", "1" => "CRECHE", "2" => "PRÉ-ESCOLA"),array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelEnrollment, 'unified_class'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo CHtml::label("Etapa", 'Stage', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo CHtml::dropDownList("Stage", null, array(
                                            "0" => "Selecione a Modalidade", 
                                            "1" => "Infantil",    
                                            "2" => "Fundamental Menor",
                                            "3" => "Fundamental Maior",
                                            "4" => "Médio",
                                            "5" => "Profissional",
                                            "6" => "EJA",
                                            "7" => "Outros",
                                        ),array(
                                            'class' => 'select-search-off',
                                            'ajax' => array(
                                                'type' => 'POST',
                                                'url' => CController::createUrl('enrollment/getmodalities'),
                                                'update' => '#StudentEnrollment_edcenso_stage_vs_modality_fk'
                                            ),
                                        )); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelEnrollment, 'edcenso_stage_vs_modality_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($modelEnrollment, 'edcenso_stage_vs_modality_fk', CHtml::listData(EdcensoStageVsModality::model()->findAll(), 'id', 'name'), array("prompt" => "Selecione a etapa",'class'=>'select-search-on'));?>
                                    <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Edcenso Stage Vs Modality Fk Help'); ?>"><i></i></span>
                                    <?php echo $form->error($modelEnrollment, 'edcenso_stage_vs_modality_fk'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelEnrollment, 'another_scholarization_place', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelEnrollment, 'another_scholarization_place', array(null => "Selecione o espaço", "1" => "Em hospital", "2" => "Em domicílio", "3" => "Não recebe"),array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelEnrollment, 'another_scholarization_place'); ?>
                                </div>
                            </div>
                        </div>
                        <div class=" span6">
                        <div class="separator"></div>                        
                            <div class="control-group">
                                <?php 
                                echo $form->labelEx($modelEnrollment, 'public_transport', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelEnrollment, 'public_transport',array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelEnrollment, 'public_transport'); ?>
                                </div>
                            </div>
                            <div class="control-group" id="transport_responsable">
                                <?php echo $form->labelEx($modelEnrollment, 'transport_responsable_government', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($modelEnrollment, 'transport_responsable_government', array(null => "Selecione o poder público do transporte", "1" => "Estadual", "2" => "Municipal"),array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelEnrollment, 'transport_responsable_government'); ?>
                                </div>
                            </div>
                            <div class="control-group" id="transport_null">
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_van', array('value'=>null, 'disabled'=>'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_microbus', array('value'=>null, 'disabled'=>'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_bus', array('value'=>null, 'disabled'=>'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_bike', array('value'=>null, 'disabled'=>'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_animal_vehicle', array('value'=>null, 'disabled'=>'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_other_vehicle', array('value'=>null, 'disabled'=>'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_waterway_boat_5', array('value'=>null, 'disabled'=>'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_waterway_boat_5_15', array('value'=>null, 'disabled'=>'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_waterway_boat_15_35', array('value'=>null, 'disabled'=>'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_waterway_boat_35', array('value'=>null, 'disabled'=>'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_metro_or_train', array('value'=>null, 'disabled'=>'disabled')); ?>
                            </div>
                            <div class="control-group" id="transport_type">
                                <label class="control-label"><?php echo Yii::t('default', 'Transport Type'); ?></label>
                                <div class="uniformjs margin-left">
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_van']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_van', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_microbus']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_microbus', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_bus']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_bus', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_bike']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_bike', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_animal_vehicle']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_animal_vehicle', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_other_vehicle']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_other_vehicle', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_5']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_waterway_boat_5', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_5_15']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_waterway_boat_5_15', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_15_35']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_waterway_boat_15_35', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_waterway_boat_35']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_waterway_boat_35', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php echo StudentEnrollment::model()->attributeLabels()['vehicle_type_metro_or_train']; ?>
                                        <?php echo $form->checkBox($modelEnrollment, 'vehicle_type_metro_or_train', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span11">
							<div class="widget widget-scroll margin-bottom-none">
								<div class="widget-head">
									<h4 class="heading glyphicons book_open">
										<i></i><?php echo yii::t("default","Enrollments");  ?>
									</h4>
								</div>
								<div class="widget-body in" style="height: auto;">
	                        	<?php 
	                        	foreach($MEs as $key => $val){
		                       		if(isset($val['classroom_fk'])){
		                       			$classroom = Classroom::model()->findbyPK($val['classroom_fk']);
		                       			$name = strlen($classroom->name) < 12 ? substr($classroom->name, 0, 12) : substr($classroom->name, 0, 9)."...";
		                       			$text = $classroom->school_year . " - " . $name;
		                        		echo CHtml::htmlButton($text, array('class' => "btn btn-icon btn-default enrollmentButton", "cod" => "$key"));
		                        		echo " ";
		                       		}
	                        	} 
                        		echo CHtml::htmlButton(Yii::t('default', 'New Enrollment'), array('class' => "btn btn-icon btn-default enrollmentButton", "cod" => "-1"));
                        		?>
                        		</div>
                        	</div>
                        </div>
                    </div>
				</div>
			</div>
            <?php $this->endWidget(); ?>
        </div>
	</div>
</div>

<script type="text/javascript">
    var formIdentification = '#StudentIdentification_';
    var formDocumentsAndAddress = '#StudentDocumentsAndAddress_';
    var formEnrollment = '#StudentEnrollment_';
    var baseUrl = "<?php echo Yii::app()->baseUrl; ?>";
    var enr = '<?php echo json_encode($MEs) ?>';
    var enrollments = JSON.parse(enr);
    var filled = -1;
</script>
