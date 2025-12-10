<?php
/* @var $this DefaultController */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/functions.js?v=' . TAG_VERSION, CClientScript::POS_END);

$this->breadcrumbs = [
    'Enrollment Online Student Identifications',
];

$this->menu = [
    ['label' => 'Create EnrollmentOnlineStudentIdentification', 'url' => ['create']],
    ['label' => 'Manage EnrollmentOnlineStudentIdentification', 'url' => ['admin']],
];
?>


<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Matricula Online'));
    ?>
    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Matricula Online') ?></h1>
        </div>
    </div>

    <div class="tag-inner">

        <div class="">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?php echo Yii::app()->user->getFlash('success') ?>
                </div>
            <?php elseif (Yii::app()->user->hasFlash('error')): ?>
                <div class="alert alert-error">
                    <?php echo Yii::app()->user->getFlash('error') ?>
                </div>
            <?php else: ?>
                <div class="alert no-show"></div>
            <?php endif; ?>
            <div>
                 <hr class="row t-separator" />
                <label for="js-filter-enrollment-status">Filtrar pre matrículas</label>
                <select id="js-filter-enrollment-status" class="select-search-on js-filter-enrollment-status">
                    <option value="1">Aguardando Processamento</option>
                    <option value="2">Matriculas Confirmadas</option>
                    <option value="3">Matriculas Rejeitadas</option>
                </select>
            </div>
            <hr class="row t-separator" />
            <div class="widget clearmargin">
                <div class="widget-body js-student-table-container">
                    <?php $this->renderPartial('_studentTable', ['dataProvider' => $dataProvider]); ?>
                </div>
            </div>
        </div>

    </div>
</div>
