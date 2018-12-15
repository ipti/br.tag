<?php
/* @var $this ClassesController */
/* @var $dataProvider CActiveDataProvider */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/classes/class-contents/_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/classes/class-contents/functions.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/classes/class-contents/dialogs.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Classes Contents'));

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'classes-form',
    'enableAjaxValidation' => false,
    'action' => CHtml::normalizeUrl(array('classes/saveClassContents')),
));

?>

<?php echo $form->errorSummary($model); ?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Class Contents'); ?></h3>  
        <div class="buttons span9">            
            <a id="add-content" class='btn btn-icon btn-success'><i class="fa fa-plus-square"></i> <?php echo Yii::t('default', 'Content') ?></a>

            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
            <a id="save" class='btn btn-icon btn-primary glyphicons circle_ok hidden-print'><?php echo Yii::t('default', 'Save') ?><i></i></a>
        </div>
    </div>
</div>

<?php
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
?>
<table class="table table-bordered table-striped visible-print">
    <tr>
        <th>Escola:</th>    <td colspan="7"><?php echo $school->inep_id . " - " . $school->name ?></td>
    <tr>
    <tr>
        <th>Estado:</th>    <td colspan="2"><?php echo $school->edcensoUfFk->name . " - " . $school->edcensoUfFk->acronym ?></td>
        <th>Municipio:</th> <td colspan="2"><?php echo $school->edcensoCityFk->name ?></td>
        <th>Endereço:</th>  <td colspan="2"><?php echo $school->address ?></td>
    <tr>
    <tr>
        <th>Localização:</th><td colspan="2"><?php echo ($school->location == 1 ? "URBANA" : "RURAL") ?></td>
        <th>Dependência Administrativa:</th><td colspan="4"><?php
            $ad = $school->administrative_dependence;
            echo ($ad == 1 ? "FEDERAL" :
                    ($ad == 2 ? "ESTADUAL" :
                            ($ad == 3 ? "MUNICIPAL" :
                                    "PRIVADA" )));
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
    <div class="filter-bar margin-bottom-none">
        <div>
            <?php echo CHtml::label(yii::t('default', 'Classroom'), 'classroom', array('class' => 'control-label')); ?>
            <?php
            echo CHtml::dropDownList('classroom', '', CHtml::listData(Classroom::model()->findAll(array('condition'=>'school_inep_fk=' . Yii::app()->user->school . ' && school_year = ' . Yii::app()->user->year,'order' => 'name')), 'id', 'name'), array(
                'key' => 'id',
                'class' => 'select-search-on',
                'prompt' => 'Selecione a turma',
                'ajax' => array(
                    'type' => 'POST',
                    'url' => CController::createUrl('classes/getDisciplines'),
                    'update' => '#disciplines',
            )));
            ?>
        </div>

        <div>    
            <?php echo CHtml::label(yii::t('default', 'Month'), 'month', array('class' => 'control-label')); ?>
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
        <div>
            <?php echo CHtml::label(yii::t('default', 'Disciplines'), 'disciplines', array('class' => 'control-label')); ?>
            <?php
            echo CHtml::dropDownList('disciplines', '', array(), array(
                'key' => 'id',
                'class' => 'select-search-on',
                'prompt' => 'Todas as disciplinas',
            ));
            ?>
        </div>
        <div class="pull-right">
            <a id="classesSearch" class='btn btn-icon btn-small btn-primary glyphicons search'><?php echo Yii::t('default', 'Search') ?><i></i></a>
        </div>



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
                    <?php echo CHtml::label(Yii::t('default', 'Name'), 'add-content-name', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?php echo CHtml::textField('add-content-name', ''); ?>
                    </div>
                </div>
                <div class="control-group">
                    <?php echo CHtml::label(Yii::t('default', 'Description'), 'add-content-description', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?php echo CHtml::textField('add-content-description', ''); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


<script>

<?php //@done s2 - não mostrar "Selecione a disciplina" como disciplina    ?>
<?php //@done s2 - inabilitar checkbox quando vier checado     ?>
<?php //@done s2 - desabilitar a coluna ao clicar em falta do professor    ?>
<?php //@done s2 - reabilitar apenas os que não estão checados     ?>
    var getClassesURL = "<?php echo Yii::app()->createUrl('classes/getClasses') ?>";
    var getContentsURL = "<?php echo Yii::app()->createUrl('classes/getContents') ?>";
    var saveContentURL = "<?php echo Yii::app()->createUrl('classes/saveContent')?>";
    
    var btnCreate = "<?php echo Yii::t('default', 'Create'); ?>";
    var btnCancel = "<?php echo Yii::t('default', 'Cancel'); ?>";
    
    var myAddContentForm;

</script>