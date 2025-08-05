    <?php
    /* @var $this DefaultController */
    /* @var $model EdcensoStageVsModality */

    $this->breadcrumbs = [
        'Edcenso Stage Vs Modalities' => ['index'],
        'Create',
    ];

$this->menu = [
    ['label' => 'List EdcensoStageVsModality', 'url' => ['index']],
    ['label' => 'Manage EdcensoStageVsModality', 'url' => ['admin']],
];
?>


    <div id="mainPage" class="main">
        <?php $this->renderPartial('_form', ['model' => $model]); ?>
    </div>
