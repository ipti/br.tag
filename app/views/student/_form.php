<?php
/**
 * @var $this StudentController
 * @var $form CActiveForm
 * @var $cs CClientScript
 *
*/
/* @var $modelStudentIdentification /app/models/StudentIdentification */
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
?>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo $title; ?></h3>
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
    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>
    <div class="widget widget-tabs border-bottom-none">
        <?php
        echo $form->errorSummary($modelStudentIdentification);
        echo $form->errorSummary($modelStudentDocumentsAndAddress);
        ?>
        <div class="widget-head">
            <ul class="tab-student" style="display:none">
                <li id="tab-student-identify" class="active">
                    <a class="glyphicons vcard" href="#student-identify" data-toggle="tab">
                        <i></i><?php echo Yii::t('default', 'Identification') ?>
                    </a>
                </li>
                <li id="tab-student-documents">
                    <a class="glyphicons credit_card" href="#student-documents" data-toggle="tab">
                        <i></i><?php echo Yii::t('default', 'Documents') ?>
                    </a>
                </li>
                <li id="tab-student-address">
                    <a class="glyphicons home" href="#student-address"    data-toggle="tab">
                        <i></i><?php echo Yii::t('default', 'Address') ?>
                    </a>
                </li>
                <li id="tab-student-enrollment">
                    <a class="glyphicons book_open" href="#student-enrollment" data-toggle="tab">
                        <i></i><?php echo Yii::t('default', 'Enrollment') ?>
                    </a>
                </li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">
            <div class="tab-content" style="display:none">
                <!-- Tab content -->
                <!-- Tab Student Identify -->
                <div class="tab-pane active" id="student-identify">
                    <div class="row-fluid">
                        <div class=" span6">
                            <div class="control-group">                                
                                <?php echo $form->labelEx($modelStudentIdentification, 'inep_id', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->hiddenField($modelStudentIdentification, 'school_inep_id_fk', array('value' => Yii::app()->user->school)); ?>
                                    <?php echo $form->textfield($modelStudentIdentification, 'inep_id', array('readonly'=>'readonly')); ?>
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
                                <?php echo $form->labelEx($modelStudentIdentification, 'filiation_1', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'filiation_1', array('size' => 60, 'maxlength' => 100, "disabled" => "disabled")); ?>
                                    <span
                                        class="btn-action single glyphicons circle_question_mark"
                                        data-toggle="tooltip" data-placement="top"
                                        data-original-title="<?php echo Yii::t('help', 'Full name'); ?>"><i></i></span>
                                        <?php echo $form->error($modelStudentIdentification, 'filiation_1'); ?>
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
                                    echo $form->dropDownList($modelStudentIdentification, 'edcenso_uf_fk', CHtml::listData(EdcensoUf::model()->findAll(array('order' => 'name')), 'id', 'name'), array(
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('student/getcities', array('rt' => 0)),
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


                        </div>                        
                        <div class=" span6">
                            <div class="separator"></div>
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
                                <?php echo $form->labelEx($modelStudentIdentification, 'inep_id', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'inep_id', array('size' => 60, 'maxlength' => 12)); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'inep_id'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentIdentification, 'responsable', array(0 => 'Pai', 1 => 'Mãe', 2 => 'Outro',), array('class' => 'select-search-off'));
                                    ?>
                                    <?php echo $form->error($modelStudentIdentification, 'responsable'); ?>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable_telephone', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'responsable_telephone', array('size' => 60, 'maxlength' => 15)); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'responsable_telephone'); ?>
                                </div>
                            </div>
                            
                            <div class="control-group" style="display:none;" id="responsable_name">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable_name', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'responsable_name', array('size' => 60, 'maxlength' => 100)); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'responsable_name'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable_rg', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'responsable_rg', array('size' => 60, 'maxlength' => 45)); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'responsable_rg'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable_cpf', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'responsable_cpf', array('size' => 60, 'maxlength' => 14)); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'responsable_cpf'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable_scholarity', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($modelStudentIdentification, 'responsable_scholarity', array(0 => 'Não sabe ler e escrever ', 1 => 'Sabe ler e escrever', 2 => 'Ens. Fund. Incompleto',
                                        3 => 'Ens. Fund. Completo', 4 => 'Ens. Médio Incompleto', 5 => 'Ens. Médio Completo',
                                        6 => 'Ens. Sup. Incompleto', 7 => 'Ens. Sup. Completo'), array('class' => 'select-search-off'));
                                    ?>
                                    <?php echo $form->error($modelStudentIdentification, 'responsable_scholarity'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'responsable_job', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelStudentIdentification, 'responsable_job', array('size' => 60, 'maxlength' => 100)); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'responsable_job'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'bf_participator', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelStudentIdentification, 'bf_participator'); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'bf_participator'); ?>
                                </div>
                            </div>


                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'food_restrictions', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textArea($modelStudentIdentification, 'food_restrictions'); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'food_restrictions'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo $form->labelEx($modelStudentIdentification, 'send_year', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelStudentIdentification, 'send_year', array('value' => date('Y') + 1, 'uncheckValue' => (date('Y')))); ?>
                                    <?php echo $form->error($modelStudentIdentification, 'send_year'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tab Student Documents -->
                <div class="tab-pane" id="student-documents">
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="widget widget-scroll margin-bottom-none"
                                 data-toggle="collapse-widget" data-scroll-height="223px"
                                 data-collapse-closed="false">
                                <div class="widget-head"><h4 class="heading glyphicons nameplate"><i></i>Documentos Pendentes</h4></div>
                                <div class="widget-body in" style="height: auto;">
                                    <div class="control-group" id="received">
                                        <div class="span3">
                                            <label class="checkbox">
                                                <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_cc']; ?>
                                                <?php echo $form->checkBox($modelStudentDocumentsAndAddress, 'received_cc', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            </label> 
                                            <label class="checkbox">
                                                <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_address']; ?>
                                                <?php echo $form->checkBox($modelStudentDocumentsAndAddress, 'received_address', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            </label> 
                                        </div>
                                        <div class="span3"> 
                                            <label class="checkbox">
                                                <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_photo']; ?>
                                                <?php echo $form->checkBox($modelStudentDocumentsAndAddress, 'received_photo', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            </label> 
                                            <label class="checkbox">
                                                <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_nis']; ?>
                                                <?php echo $form->checkBox($modelStudentDocumentsAndAddress, 'received_nis', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            </label> 
                                        </div>
                                        <div class="span3">
                                            <label class="checkbox">
                                                <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_responsable_rg']; ?>
                                                <?php echo $form->checkBox($modelStudentDocumentsAndAddress, 'received_responsable_rg', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            </label>
                                            <label class="checkbox">
                                                <?php echo StudentDocumentsAndAddress::model()->attributeLabels()['received_responsable_cpf']; ?>
                                                <?php echo $form->checkBox($modelStudentDocumentsAndAddress, 'received_responsable_cpf', array('value' => 1, 'uncheckValue' => 0)); ?>
                                            </label> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class=" span6">



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
                                    <?php //@done S1 - Alterar tipo de certidão civil para dropdown ?>
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
                                                    'url' => CController::createUrl('student/getcities', array('rt' => 1)),
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
                                            echo $form->dropDownList($modelStudentDocumentsAndAddress, 'edcenso_notary_office_fk', CHtml::listData(EdcensoNotaryOffice::model()->findAllByAttributes(array('city' => $modelStudentDocumentsAndAddress->notary_office_city_fk), array('order' => 'name')), 'cod', 'name')+array('7177'=>'OUTROS'), array("prompt" => "Selecione um cartório",
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
                            <div class="separator"></div>
                            <div class="widget widget-scroll margin-bottom-none"
                                 data-toggle="collapse-widget" data-scroll-height="223px"
                                 data-collapse-closed="false">
                                <div class="widget-head"><h4 class="heading glyphicons circle_question_mark"><i></i>Justificativa</h4></div>
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
                        </div>

                        <div class=" span6">
                            <div class="widget widget-scroll margin-bottom-none"
                                 data-toggle="collapse-widget" data-scroll-height="223px"
                                 data-collapse-closed="false">
                                <div class="widget-head">
                                    <h4 class="heading glyphicons nameplate">
                                        <i></i>Cartão Nacional de Saúde
                                    </h4>
                                </div>
                                <div class="widget-body in" style="height: auto;">
                                    <div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'cns', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'cns', array('size' => 11, 'maxlength' => 15, "disabled" => "disabled", "class" => "nationality-sensitive br")); ?>
                                            <span
                                                class="btn-action single glyphicons circle_question_mark"
                                                data-toggle="tooltip" data-placement="top"
                                                data-original-title="<?php echo Yii::t('help', 'Only Numbers'); ?>"><i></i></span>
                                                <?php echo $form->error($modelStudentDocumentsAndAddress, 'cns'); ?>
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
                                        <i></i>Cadastro de Pessoa Física
                                    </h4>
                                </div>
                                <div class="widget-body in" style="height: auto;">
                                    <div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'cpf', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?php echo $form->textField($modelStudentDocumentsAndAddress, 'cpf', array('size' => 11, 'maxlength' => 14, "disabled" => "disabled", "class" => "nationality-sensitive br")); ?>
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
                                                data-original-title="<?php echo Yii::t('help', 'Max length') . '20'; ?>"><i></i></span>
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
                            <div class="separator"></div>
                            <div class="widget widget-scroll margin-bottom-none"
                                 data-toggle="collapse-widget" data-scroll-height="223px"
                                 data-collapse-closed="false">
                                <div class="widget-head">
                                    <h4 class="heading glyphicons nameplate">
                                        <i></i>Justiça
                                    </h4>
                                </div>
                                <div class="widget-body in" style="height: auto;">
                                    <div class="control-group">
                                        <?php echo $form->labelEx($modelStudentDocumentsAndAddress, 'justice_restriction', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?php echo $form->DropDownList($modelStudentDocumentsAndAddress, 'justice_restriction', array(null=> "Selecione", "0" => "Não possui restrições", "1" => "LA - Liberdade Assistida", "2" => "PSC - Prestação de Serviços Comunitários"), array('class' => 'select-search-off')); ?>
                                            <?php echo $form->error($modelStudentDocumentsAndAddress, 'justice_restriction'); ?>
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
                                    echo $form->textField($modelStudentDocumentsAndAddress, 'cep', array('size' => 8,
                                        'maxlength' => 9
                                    ));
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
                                            'url' => CController::createUrl('student/getcities', array('rt' => 2)),
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
                                    <?php echo $form->hiddenField($modelEnrollment, 'school_inep_id_fk', array('value' => Yii::app()->user->school)); ?>

                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelEnrollment, 'classroom_fk', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php

                                    $stage = $modelStudentIdentification->getCurrentStageVsModality();
                                    $stages = implode(",", EdcensoStageVsModality::getNextStages($stage));
                                    $classrooms = Classroom::model()->findAll(
                                        "school_year = :year AND school_inep_fk = :school AND edcenso_stage_vs_modality_fk in ($stages)",
                                        [
                                            ':year' => Yii::app()->user->year,
                                            ':school' => Yii::app()->user->school,
                                        ]);

                                    echo $form->dropDownList($modelEnrollment, 'classroom_fk',
                                        CHtml::listData(
                                            $classrooms, 'id', 'name'),
                                        array("prompt" => "Selecione uma Turma", 'class' => 'select-search-on')); ?>
                                    <?php echo $form->error($modelEnrollment, 'classroom_fk'); ?>
                                </div>
                            </div>
                            <div id="multiclass">
                                <div class="control-group">
                                    <?php echo $form->labelEx($modelEnrollment, 'unified_class', array('class' => 'control-label')); ?>
                                    <div class="controls">
                                        <?php echo $form->DropDownList($modelEnrollment, 'unified_class', array(null => "Selecione o tipo de turma infantil", "1" => "CRECHE", "2" => "PRÉ-ESCOLA"), array('class' => 'select-search-off')); ?>
                                        <?php echo $form->error($modelEnrollment, 'unified_class'); ?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <?php echo CHtml::label("Etapa", 'Stage', array('class' => 'control-label')); ?>
                                    <div class="controls">
                                        <?php
                                        echo CHtml::dropDownList("Stage", null, array(
                                            "0" => "Selecione a Modalidade",
                                            "1" => "Infantil",
                                            "2" => "Fundamental Menor",
                                            "3" => "Fundamental Maior",
                                            "4" => "Médio",
                                            "5" => "Profissional",
                                            "6" => "EJA",
                                            "7" => "Outros",
                                                ), array(
                                            'class' => 'select-search-off',
                                            'ajax' => array(
                                                'type' => 'POST',
                                                'url' => CController::createUrl('enrollment/getmodalities'),
                                                'update' => '#StudentEnrollment_edcenso_stage_vs_modality_fk'
                                            ),
                                        ));
                                        ?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <?php echo $form->labelEx($modelEnrollment, 'edcenso_stage_vs_modality_fk', array('class' => 'control-label')); ?>
                                    <div class="controls">
                                        <?php echo $form->dropDownList($modelEnrollment, 'edcenso_stage_vs_modality_fk', CHtml::listData(EdcensoStageVsModality::model()->findAll(), 'id', 'name'), array("prompt" => "Selecione a etapa", 'class' => 'select-search-on')); ?>
                                        <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo Yii::t('help', 'Edcenso Stage Vs Modality Fk Help'); ?>"><i></i></span>
                                        <?php echo $form->error($modelEnrollment, 'edcenso_stage_vs_modality_fk'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelEnrollment, 'admission_type', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelEnrollment, 'admission_type', array("1" => "Rematrícula", "2" => "Transferência interna", "3" => "Transferência externa"), array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelEnrollment, 'admission_type'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelEnrollment, 'another_scholarization_place', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelEnrollment, 'another_scholarization_place', array("3" => "Não recebe", "1" => "Em hospital", "2" => "Em domicílio"), array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelEnrollment, 'another_scholarization_place'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelEnrollment, 'current_stage_situation', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelEnrollment, 'current_stage_situation',
                                        array(
                                            null => "Selecione",
                                            "0" => "Primeira matrícula no curso",
                                            "1" => "Promovido na série anterior do mesmo curso",
                                            "2" => "Repetente"
                                        ),
                                        array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelEnrollment, 'current_stage_situation'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelEnrollment, 'previous_stage_situation', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->DropDownList($modelEnrollment, 'previous_stage_situation',
                                        array(
                                            null => "Selecione",
                                            "0" => "Não frequentou",
                                            "1" => "Reprovado",
                                            "2" => "Afastado por transferência",
                                            "3" => "Afastado por abandono",
                                            "4" => "Matrícula final em Educação Infantil"
                                        ),
                                        array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelEnrollment, 'previous_stage_situation'); ?>
                                </div>
                            </div>
                        </div>
                        <div class=" span6">
                            <div class="separator"></div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelEnrollment, 'school_admission_date', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->textField($modelEnrollment, 'school_admission_date', array('size' => 10, 'maxlength' => 10)); ?>
                                    <?php echo $form->error($modelEnrollment, 'school_admission_date'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo $form->labelEx($modelEnrollment, 'public_transport', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->checkBox($modelEnrollment, 'public_transport', array('value' => 1, 'uncheckValue' => 0)); ?>
                                    <?php echo $form->error($modelEnrollment, 'public_transport'); ?>
                                </div>
                            </div>
                            <div class="control-group" id="transport_responsable">
                                <?php echo $form->labelEx($modelEnrollment, 'transport_responsable_government', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php echo $form->dropDownList($modelEnrollment, 'transport_responsable_government', array(null => "Selecione o poder público do transporte", "1" => "Estadual", "2" => "Municipal"), array('class' => 'select-search-off')); ?>
                                    <?php echo $form->error($modelEnrollment, 'transport_responsable_government'); ?>
                                </div>
                            </div>
                            <div class="control-group" id="transport_null">
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_van', array('value' => null, 'disabled' => 'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_microbus', array('value' => null, 'disabled' => 'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_bus', array('value' => null, 'disabled' => 'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_bike', array('value' => null, 'disabled' => 'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_animal_vehicle', array('value' => null, 'disabled' => 'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_other_vehicle', array('value' => null, 'disabled' => 'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_waterway_boat_5', array('value' => null, 'disabled' => 'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_waterway_boat_5_15', array('value' => null, 'disabled' => 'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_waterway_boat_15_35', array('value' => null, 'disabled' => 'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_waterway_boat_35', array('value' => null, 'disabled' => 'disabled')); ?>
                                <?php echo CHtml::activeHiddenField($modelEnrollment, 'vehicle_type_metro_or_train', array('value' => null, 'disabled' => 'disabled')); ?>
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
                            <div id="enrollment" class="widget widget-scroll margin-bottom-none">
                                <div class="widget-head">
                                    <h4 class="heading glyphicons book_open">
                                        <i></i><?php echo yii::t("default", "Enrollments"); ?>
                                    </h4>
                                </div>
                                <div class="widget-body in" style="height: auto;">
                                    <table class="table table-bordered table-striped">
                                        <thead><tr><td>Escola</td><td>Turma</td><td style="text-align: center">Ano</td><td style="text-align: center">Ficha Individual</td><td style="text-align: center">Declaração</td><td style="text-align: center">Excluir</td></tr></thead>
                                        <tbody>
                                        <?php
                                        foreach ($modelStudentIdentification->studentEnrollments as $me) {
                                            ?>
                                            <tr>
                                                <td><?php echo $me->schoolInepIdFk->name ?></td>
                                                <td><?php echo $me->classroomFk->name ?></td>
                                                <td style="text-align: center"><?php echo $me->classroomFk->school_year ?></td>
                                                <?php
                                                $type;
                                                if(isset($me->classroomFk->edcensoStageVsModalityFk->stage)) {
                                                    switch ($me->classroomFk->edcensoStageVsModalityFk->stage) {
                                                        // FALTA O CASO DE NECESSIDADES ESPECIAIS - ANALISAR COMO PODE SER TRATADO

                                                        //educação infantil
                                                        case 1:
                                                            $type = 0;
                                                            break;
                                                        //ensino fundamental
                                                        case 2:
                                                            $type = 1;
                                                        //ensino fundamental
                                                        case 3:
                                                            $type = 1;
                                                            break;
                                                        //ensino médio
                                                        case 4:
                                                            $type = 1;
                                                            break;
                                                        //educação profissional
                                                        case 5:
                                                            $type = 1;
                                                            break;
                                                        //educação de jovens e adultos
                                                        case 6:
                                                            $type = 2;
                                                            break;
                                                        //ensino fundamental OU multietapa
                                                        case 7:
                                                            $type = 1;
                                                            break;
                                                        case null:
                                                            $type = 1;
                                                            break;
                                                    }
                                                }
                                                ?>
                                                <td style="text-align: center"><a href='<?php echo Yii::app()->createUrl('reports/StudentsFileBoquimReport', array('type'=>$type, 'enrollment_id' => $me->id)) ?>' target="_blank"><i class="fa fa-file-text-o"></i></a></td>
                                                <td style="text-align: center"><a href='<?php echo Yii::app()->createUrl('reports/EnrollmentDeclarationReport', array('enrollment_id' => $me->id)) ?>' target="_blank"><i class="fa fa-file-text-o"></i></a></td>
                                                <td style="text-align: center"><a href='<?php echo Yii::app()->createUrl('enrollment/delete', array('id' => $me->id)) ?>'><i class="fa fa-trash-o"></i></a></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
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
    var updateDependenciesURL = '<?php echo yii::app()->createUrl('enrollment/updatedependencies') ?>';
    var filled = -1;    
</script>
