<?php
/* @var $this CoursePlanController */
/* @var $dataProvider CActiveDataProvider */

    $this->setPageTitle('TAG - ' . Yii::t('default', 'Course Plan'));    
?>

<div id="mainPage" class="main">
    <div class="row-fluid">
        <div class="span12">
            <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Course Plan') ?></h3>  
            <div class="buttons">
                <a href="<?php echo Yii::app()->createUrl('courseplan/create') ?>" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i><?= Yii::t('default', 'Create Plan'); ?> </a>
            </div>
        </div>
    </div>

    <div class="innerLR">
        <div class="columnone" style="padding-right: 1em">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?php echo Yii::app()->user->getFlash('success') ?>
                </div>
                <br/>
            <?php endif ?>
            <div class="widget">
                <div class="widget-body">
                    <?php $this->widget('zii.widgets.CListView', array(
                            'dataProvider'=>$dataProvider,
                            'itemView'=>'_view',
                    )); ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
        </div>

    </div>
</div>

