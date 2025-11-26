<?php

use PHPUnit\Extensions\Selenium2TestCase\ElementCommand\Value;
/* @var $this StudentIMCController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Student Imcs',
);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Alunos'));

$this->menu = array(
    array('label' => 'Create StudentIMC', 'url' => array('create')),
    array('label' => 'Manage StudentIMC', 'url' => array('admin')),
);
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/functions.js', CClientScript::POS_END);
?>
<div class="main">

    <h1>Alunos</h1>
    <div class="row justify-content--end">
        <a class="t-button-primary" href="<?php echo Yii::app()->createUrl('student/create', array('simple' => 1)) ?>">Adicionar Aluno</a>
        <div class="js-report-button hide">

        </div>
    </div>
    <hr class="row t-separator" />
    <div class="row justify-content--space-between">
        <div class="column clearleft justify-content-- is-one-third  row wrap">
            <div class="column clearleft">
                <div class="t-field-select">
                    <?php echo CHtml::label(yii::t('default', 'Filtrar por Turma'), 'classroom', array('class' => 't-field-select__label no-wrap')); ?>
                    <select name="classroom" id="classroom" class="select-search-on t-field-select__input select2-container js-classroom">
                        <option value="" selected>Selecione...</option>
                        <?php foreach ($classrooms as $classroom): ?>
                            <option value="<?= $classroom['id'] ?>"><?= htmlspecialchars($classroom['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <hr class="row t-separator" />
    <div class="js-studentTable">
        <?php $this->renderPartial('_studentTable', array('dataProvider' => $dataProvider)); ?>
    </div>
</div>
