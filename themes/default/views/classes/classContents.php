<?php
/* @var $this ClassesController */
/* @var $dataProvider CActiveDataProvider */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/classes/class-contents/_initialization.js?v=1.0', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/classes/class-contents/functions.js?v=1.0', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/classes/class-contents/dialogs.js', CClientScript::POS_END);
$cs->registerCssFile($themeUrl . '/css/template2.css');
$this->setPageTitle('TAG - ' . Yii::t('default', 'Classes Contents'));

$form = $this->beginWidget('CActiveForm', [
    'id' => 'classes-form',
    'enableAjaxValidation' => false,
    'action' => CHtml::normalizeUrl(['classes/saveClassContents']),
]);

?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Class Contents'); ?></h3>
        <div class="buttons span9">
            <a id="add-content" class='tag-button medium-button'> <?php echo Yii::t('default', 'Content') ?></a>

            <a id="print" class='tag-button-light  medium-button'>
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/impressora.png" />
                <?php echo Yii::t('default', 'Print') ?>
            </a>
            <a id="save" class='tag-button medium-button'><?php echo Yii::t('default', 'Save') ?></a>
        </div>
    </div>
</div>
<?php
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
?>
<table class="table table-bordered table-striped visible-print">
    <tr>
        <th>Escola:</th>
        <td colspan="7"><?php echo $school->inep_id . ' - ' . $school->name ?></td>
    <tr>
    <tr>
        <th>Estado:</th>
        <td colspan="2"><?php echo $school->edcensoUfFk->name . ' - ' . $school->edcensoUfFk->acronym ?></td>
        <th>Municipio:</th>
        <td colspan="2"><?php echo $school->edcensoCityFk->name ?></td>
        <th>Endereço:</th>
        <td colspan="2"><?php echo $school->address ?></td>
    <tr>
    <tr>
        <th>Localização:</th>
        <td colspan="2"><?php echo($school->location == 1 ? 'URBANA' : 'RURAL') ?></td>
        <th>Dependência Administrativa:</th>
        <td colspan="4"><?php
                        $ad = $school->administrative_dependence;
                        echo($ad == 1 ? 'FEDERAL' : ($ad == 2 ? 'ESTADUAL' : ($ad == 3 ? 'MUNICIPAL' :
                            'PRIVADA')));
                        ?></td>
    <tr>
</table>
<br>

<div class="innerLR">

    <?php if (Yii::app()->user->hasFlash('success')) : ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>
    <div class="alert-save no-show alert alert-success">
        Aulas ministradas atualizadas com sucesso!
    </div>
    <div class="alert-required-fields no-show alert alert-error">
        Os campos com * são obrigatórios.
    </div>
    <div class="filter-bar margin-bottom-none">
        <div>
            <div>
                <div class="controls">
                <?php echo CHtml::label(yii::t('default', 'Classroom') . ' *', 'classroom', ['class' => 'control-label required', 'style' => 'width: 53px;']); ?>
                </div>
                <div class="controls">
                <select class="select-search-on control-input" id="classroom" name="classroom">
                    <option>Selecione a turma</option>
                    <?php foreach ($classrooms as $classroom) : ?>
                        <option value="<?= $classroom->id ?>" fundamentalmaior="<?= $classroom->edcenso_stage_vs_modality_fk >= 14 && $classroom->edcenso_stage_vs_modality_fk <= 16 ? 0 : 1 ?>"><?= $classroom->name ?></option>
                    <?php endforeach; ?>
                </select>
                </div>
            
            
                
                    <?php echo CHtml::label(yii::t('default', 'Month') . ' *', 'month', ['class' => 'control-label required', 'style' => 'width: 53px;']); ?>
                

                <?php
                echo CHtml::dropDownList('month', '', [
                    1 => 'Janeiro',
                    2 => 'Fevereiro',
                    3 => 'Março',
                    4 => 'Abril',
                    5 => 'Maio',
                    6 => 'Junho',
                    7 => 'Julho',
                    8 => 'Agosto',
                    9 => 'Setembro',
                    10 => 'Outubro',
                    11 => 'Novembro',
                    12 => 'Dezembro'
                ], [
                    'key' => 'id',
                    'class' => 'select-search-on control-input',
                    'prompt' => 'Selecione o mês',
                ]);
                ?>

            </div>
            <div class="disciplines-container">
                <?php echo CHtml::label(yii::t('default', 'Discipline') . ' *', 'disciplines', ['class' => 'control-label required']); ?>
                <?php
                echo CHtml::dropDownList('disciplines', '', [], [
                    'key' => 'id',
                    'class' => 'select-search-on control-input',
                ]);
                ?>
            </div>
            <div class="pull-right">
                <a id="classesSearch" class='tag-button small-button' style="margin: 0px;"><i class="fa-search fa icon-button-tag" style="margin-top:5px"></i><?php echo Yii::t('default', 'Search') ?>
                </a>
            </div>
            <i class="loading-class-contents fa fa-spin fa-spinner"></i>

        </div>
        <div class="widget" id="widget-class-contents" style="display:none; margin-top: 8px;">
            <div class="widget-head">
                <h4 class="heading"><span id="month_text"></span> - <span id="discipline_text"></span></h4>
            </div>
            <table id="class-contents" class="table table-bordered table-striped">
                <thead>
                </thead>
                <tbody>
                    <tr>
                        <td class="center">1</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php $this->endWidget(); ?>

    <!-- Modal -->
    <div id="add-content-form" class="hide" title="<?php echo Yii::t('default', 'Add Content'); ?>">
        <div class="row-fluid">
            <div class="span12">
                <div class="control-group">
                    <?php echo CHtml::label(Yii::t('default', 'Name'), 'add-content-name', ['class' => 'control-label']); ?>
                    <div class="controls">
                        <?php echo CHtml::textField('add-content-name', ''); ?>
                    </div>
                </div>
                <div class="control-group">
                    <?php echo CHtml::label(Yii::t('default', 'Description'), 'add-content-description', ['class' => 'control-label']); ?>
                    <div class="controls">
                        <?php echo CHtml::textField('add-content-description', ''); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        var btnCreate = "<?php echo Yii::t('default', 'Create'); ?>";
        var btnCancel = "<?php echo Yii::t('default', 'Cancel'); ?>";

        var myAddContentForm;
    </script>