<?php
/* @var $this DefaultController */

$baseUrl = Yii::app()->baseUrl;

$this->pageTitle = 'TAG - ' . Yii::t('default', 'Quiz');
$this->breadcrumbs = array(
    Yii::t('default', 'Quiz'),
);
?>

<style>
.block{
    display: block !important;
}
</style>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Quiz'); ?></h3>  
    </div>
</div>

<div class="innerLR home">
    <div class="row-fluid">
        <div class="span12">
            <div class="span2">
                <a href="<?php echo Yii::app()->createUrl('quiz/default/quiz')?>" class="widget-stats">
                    <span class="fa-list-ol fa fa-4x block"><i></i></span>
                    <span class="txt">Questionários</span>
                    <div class="clearfix"></div>
                </a>
            </div>
            <div class="span2">
                <a href="<?php echo Yii::app()->createUrl('quiz/default/question')?>" class="widget-stats">
                    <span class="fa fa-edit fa-4x block"></span>
                    <span class="txt">Perguntas</span>
                    <div class="clearfix"></div>
                </a>
            </div>
            <div class="span2">
                <a href="<?php echo Yii::app()->createUrl('quiz/default/group')?>" class="widget-stats">
                    <span class="fa fa-archive fa-4x block"><i></i></span>
                    <span class="txt">Grupos de Perguntas</span>
                    <div class="clearfix"></div>
                </a>
            </div>
            <div class="span2">
                <a href="<?php echo Yii::app()->createUrl('quiz/default/questionGroup')?>" class="widget-stats">
                    <span class="fa fa-list fa-4x block"><i></i></span>
                    <span class="txt">Agrupar Perguntas</span>
                    <div class="clearfix"></div>
                </a>
            </div>
        </div> <!-- .span12 -->
    </div> <!-- .row-fluid -->
</div> <!-- .innerLr -->