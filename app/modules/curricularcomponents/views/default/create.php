<?php
/** @var DefaultController $this DefaultController */
/** @var EdcensoDiscipline $model EdcensoDiscipline */

$this->breadcrumbs = [
    'Edcenso Disciplines' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List EdcensoDiscipline', 'url' => ['index']],
    ['label' => 'Manage EdcensoDiscipline', 'url' => ['admin']],
];

$this->setPageTitle('TAG - ' . Yii::t('default', 'Adicionar Componente Curricular'));
$title = Yii::t('default', 'Adicionar Componente Curricular');

?>



<div id="mainPage" class="main">
	<?php $this->renderPartial('_form', ['model' => $model, 'edcenso_base_disciplines' => $edcenso_base_disciplines,  'title' => $title]); ?>
</div>