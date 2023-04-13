<?php
/* @var $this DefaultController */

$this->setPageTitle('TAG - ' . Yii::t('default', 'SEDSP'));

$this->breadcrumbs = array(
    $this->module->id,
);
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($themeUrl . '/css/template2.css');
$cs->registerCssFile(Yii::app()->request->baseUrl . '/sass/css/main.css');
$cs->registerScriptFile($baseScriptUrl . '/common/js/functions.js?v=1.1', CClientScript::POS_END);
?>

<div id="mainPage" class="main">
    <div class="row">
        <div class="column">
            <h1>SEDSP</h1>
        </div>
    </div>
    <div class="alert alert-error alert-error-export" style="display: none;"></div>
    <?php if (Yii::app()->user->hasFlash('error')) : ?>
        <div class="alert alert-error">
            <?php echo Yii::app()->user->getFlash('error') ?>
        </div>
    <?php endif ?>
    <?php if (Yii::app()->user->hasFlash('success')) : ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
        <br />
    <?php endif ?>
    <div class="row">
        <div class="column">
            <a href="#" class="widget-stats" data-toggle="modal" data-target="#add-student-ra" target="_blank">
                <div><i class="fa fa-building-o fa-4x"></i></div>
                <span class="txt">Cadastrar Aluno</span>
                <div class="clearfix"></div>
            </a>
        </div>

        <div class="column">
            <a href="?r=sagres/default/export" id="exportLink" class="widget-stats">
                <span class="glyphicons file_export"><i></i></span>
                <span class="txt">Cadastrar Turma</span>
                <div class="clearfix"></div>
            </a>
        </div>
        <div class="column">
            <a href="<?php echo Yii::app()->createUrl('sagres/default/update', array('id' => 1)) ?>" href="?r=sagres/default/update&id=2" class="widget-stats">
                <div><i class="fa fa-edit fa-4x"></i></div>
                <span class="txt">Cadastrar Escola</span>
                <div class="clearfix"></div>
            </a>
        </div>

        <div class="column">
            <a href="<?php echo Yii::app()->createUrl('sedsp/default/manageRA') ?>" class="widget-stats">
                <span class="glyphicons user"><i></i></span>
                <span class="txt">Gerar RA</span>
                <div class="clearfix"></div>
            </a>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade modal-content" id="add-student-ra" tabindex="-1" role="dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
        </button>
        <h4 class="modal-title" id="myModalLabel">Digite o RA</h4>
    </div>
    <form class="form-vertical" id="addStudentRA" action="<?php echo yii::app()->createUrl('sedsp/default/AddStudentWithRA') ?>" method="post" onsubmit="return validateForm();">
        <div class="modal-body">
            <div class="row-fluid">
                <div class=" span12">
                    <?php echo CHtml::label(yii::t('default', 'RA'), 'year', array('class' => 'control-label')); ?>
                    <input name="ra" id="ra" type="number" placeholder="Digite o RA" style="width: 100%;" oninput="validateRA();" maxlength="12">
                    <div id="ra-warning" style="display: none;color:#D21C1C">O RA deve ter exatamente 12 dígitos.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="background: #EFF2F5; color:#252A31;">Voltar</button>
                <button class="btn btn-primary" url="<?php echo Yii::app()->createUrl('sedsp/default/AddStudentWithRA'); ?>" type="submit" value="Alterar" style="background: #3F45EA; color: #FFFFFF;"> Cadastrar </button>
            </div>
    </form>
</div>

<script>
    function validateForm() {
        var raInput = document.getElementById("ra");
        var warningDiv = document.getElementById("ra-warning");
        if (raInput.value.length < 12) {
            warningDiv.style.display = "block";
            return false;
        } else {
            warningDiv.style.display = "none";
            return true;
        }
    }

    function validateRA() {
        var raInput = document.getElementById("ra");
        var warningDiv = document.getElementById("ra-warning");
        if (raInput.value.length < 12) {
            warningDiv.style.display = "block";
        } else {
            warningDiv.style.display = "none";
        }
        if (raInput.value.length > raInput.maxLength) {
            raInput.value = raInput.value.slice(0, raInput.maxLength);
        }
    }

    var myForm = document.getElementById("myForm");
    myForm.addEventListener("submit", function(event) {
        if (!validateForm()) {
            event.preventDefault();
        }
    });
</script>