<?php
/** @var DefaultController $this DefaultController */
/** @var EdcensoDiscipline $model EdcensoDiscipline */

$this->breadcrumbs = [
    'Edcenso Disciplines' => ['index'],
    $model->name => ['view', 'id' => $model->id],
    'Update',
];

$this->setPageTitle('TAG - Atualizar Componente Curricular');
$title = Yii::t('default', 'Atualizar Componente: ' . $model->name);

?>

<div id="mainPage" class="main">
	<?php $this->renderPartial('_form', [
	    'model' => $model,
	    'edcensoBaseDisciplines' => $edcensoBaseDisciplines,
	    'title' => $title]); ?>
</div>
