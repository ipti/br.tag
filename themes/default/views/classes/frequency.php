<?php
/* @var $this ClassesController */
/* @var $dataProvider CActiveDataProvider */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/classes/frequency/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Classes'));

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'classes-form',
    'enableAjaxValidation' => false,
    'action' => CHtml::normalizeUrl(array('classes/saveFrequency')),
));
?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Frequency'); ?><span> | Marcar apenas faltas.</span>
        </h3>
        <div class="buttons span9">
            <!--            <a id="print" class='btn btn-icon glyphicons print hidden-print'>-->
            <?php //echo Yii::t('default', 'Print') ?><!--<i></i></a>-->
            <!--            <a href="-->
            <?php //echo Yii::app()->createUrl('reports/bfreport') ?><!--" class='btn btn-icon glyphicons print hidden-print'>Bolsa Familia<i></i></a>-->
            <!-- <a id="save" class='btn btn-icon btn-primary glyphicons circle_ok hidden-print'><?php echo Yii::t('default', 'Save') ?><i></i></a> -->
        </div>
    </div>
</div>

<?php
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
?>
<table class="table table-bordered table-striped visible-print">
    <tr>
        <th>Escola:</th>
        <td colspan="7"><?php echo $school->inep_id . " - " . $school->name ?></td>
    <tr>
    <tr>
        <th>Estado:</th>
        <td colspan="2"><?php echo $school->edcensoUfFk->name . " - " . $school->edcensoUfFk->acronym ?></td>
        <th>Municipio:</th>
        <td colspan="2"><?php echo $school->edcensoCityFk->name ?></td>
        <th>Endereço:</th>
        <td colspan="2"><?php echo $school->address ?></td>
    <tr>
    <tr>
        <th>Localização:</th>
        <td colspan="2"><?php echo($school->location == 1 ? "URBANA" : "RURAL") ?></td>
        <th>Dependência Administrativa:</th>
        <td colspan="4"><?php
            $ad = $school->administrative_dependence;
            echo($ad == 1 ? "FEDERAL" :
                ($ad == 2 ? "ESTADUAL" :
                    ($ad == 3 ? "MUNICIPAL" :
                        "PRIVADA")));
            ?></td>
    <tr>
</table>
<br>

<div class="innerLR">

    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>
    <div class="alert-required-fields no-show alert alert-error">
        Os Campos com * são obrigatórios.
    </div>
    <div class="filter-bar margin-bottom-none">
        <div>
            <?php echo CHtml::label(yii::t('default', 'Classroom') . " *", 'classroom', array('class' => 'control-label required')); ?>
            <select class="select-search-on" id="classroom">
                <option>Selecione a turma</option>
                <?php foreach ($classrooms as $classroom): ?>
                    <option value="<?= $classroom->id ?>"
                            showdisciplines="<?= $classroom->edcenso_stage_vs_modality_fk >= 14 && $classroom->edcenso_stage_vs_modality_fk <= 16 ? 0 : 1 ?>"><?= $classroom->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <?php echo CHtml::label(yii::t('default', 'Month') . " *", 'month', array('class' => 'control-label required')); ?>
            <?php
            echo CHtml::dropDownList('month', '', array(1 => 'Janeiro',
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
                12 => 'Dezembro'), array(
                'key' => 'id',
                'class' => 'select-search-on',
                'prompt' => 'Selecione o mês',
            ));
            ?>
        </div>
        <div class="disciplines-container">
            <?php echo CHtml::label(yii::t('default', 'Discipline') . " *", 'disciplines', array('class' => 'control-label required')); ?>
            <?php
            echo CHtml::dropDownList('disciplines', '', array(), array(
                'key' => 'id',
                'class' => 'select-search-on',
            ));
            ?>
        </div>
        <div>
            <a id="classesSearch"
               class='btn btn-icon btn-small btn-primary glyphicons search'><?php echo Yii::t('default', 'Search') ?>
                <i></i></a>
        </div>
        <i class="loading-frequency fa fa-spin fa-spinner"></i>
    </div>

    <div class="alert-incomplete-data alert alert-warning display-hide"></div>
    <div id="frequency-container"></div>
    <?php $this->endWidget(); ?>
</div>


<script>

    <?php //@done s2 - não mostrar "Selecione a disciplina" como disciplina   ?>
    <?php //@done s2 - inabilitar checkbox quando vier checado    ?>
    <?php //@done s2 - desabilitar a coluna ao clicar em falta do professor   ?>
    <?php //@done s2 - reabilitar apenas os que não estão checados    ?>
    var getClassesForFrequencyURL = "<?php echo Yii::app()->createUrl('classes/getClassesForFrequency') ?>";
    var getClassesURLSave = "<?php echo Yii::app()->createUrl('classes/saveFrequency') ?>";

</script>