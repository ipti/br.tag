    <?php
    /* @var $this DefaultController */
    /* @var $model EdcensoStageVsModality */

    $this->breadcrumbs=array(
        'Edcenso Stage Vs Modalities'=>array('index'),
        'Create',
    );

    $this->menu=array(
        array('label'=>'List EdcensoStageVsModality', 'url'=>array('index')),
        array('label'=>'Manage EdcensoStageVsModality', 'url'=>array('admin')),
    );
    ?>


    <div id="mainPage" class="main">
        <?php $this->renderPartial('_form', array('model'=>$model)); ?>
    </div>
