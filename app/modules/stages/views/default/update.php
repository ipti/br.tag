    <?php
    /* @var $this DefaultController */
    /* @var $model EdcensoStageVsModality */

    $this->breadcrumbs = [
        'Edcenso Stage Vs Modalities' => ['index'],
        $model->name => ['view', 'id' => $model->id],
        'Update',
    ];

$this->menu = [
    ['label' => 'List EdcensoStageVsModality', 'url' => ['index']],
    ['label' => 'Create EdcensoStageVsModality', 'url' => ['create']],
    ['label' => 'View EdcensoStageVsModality', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage EdcensoStageVsModality', 'url' => ['admin']],
];
?>

    <div id="mainPage" class="main">
        <?php $this->renderPartial('_form', ['model' => $model]); ?>
    </div>
