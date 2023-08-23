<?php
/* @var $this DefaultController */

$baseUrl = Yii::app()->baseUrl;

$this->pageTitle = 'TAG - ' . Yii::t('default', 'Quiz');
$this->breadcrumbs = array(
    Yii::t('default', 'Quiz'),
);
?>

<style>
    .block {
        display: block !important;
    }
</style>
<div class="main">
    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Quiz'); ?></h1>
        </div>
    </div>
    <div class="widget widget-tabs border-bottom-none">
        <div class="widget-body form-horizontal">
            <div class="tab-content">
                <div class="tab-pane row active">
                    <div class="row">
                        <div class="column">
                            <div class="row">
                                <div class="t-cards">
                                    <div class="t-cards-content">
                                        <a href="<?php echo Yii::app()->createUrl('quiz/default/quiz') ?>" class="t-cards-link">
                                            <span class="t-icon-list-form t-reports_icons align-items--center"></span>
                                            <div>
                                                <div class="t-cards-title">
                                                    Questionários
                                                </div>
                                                <div class="t-cards-text clear-margin--left">Adicione questões ao relatório.</div>
                                                <!-- <img class="t-icon-trash" alt="icone escola card" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/escola.svg" /> -->
                                                <!-- <span class="fa-list-ol fa fa-4x block"><i></i></span>
                                                <span class="txt">Questionários</span>
                                                <div class="clearfix"></div> -->
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="t-cards">
                                    <div class="t-cards-content">
                                        <a href="<?php echo Yii::app()->createUrl('quiz/default/question') ?>" class="t-cards-link">
                                            <span class="t-icon-balloon-question t-reports_icons align-items--center"></span>
                                            <div>
                                                <div class="t-cards-title">
                                                    Perguntas
                                                </div>
                                                <div class="t-cards-text clear-margin--left">Relacione novos questionários aos grupos.</div>
                                                <!-- <span class="fa fa-edit fa-4x block"></span>
                                                <span class="txt">Perguntas</span>
                                                <div class="clearfix"></div> -->
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="t-cards">
                                    <div class="t-cards-content">
                                        <a href="<?php echo Yii::app()->createUrl('quiz/default/group') ?>" class="t-cards-link">
                                            <span class="t-icon-box t-reports_icons align-items--center"></span>
                                            <div>
                                                <div class="t-cards-title">
                                                    Grupos de Perguntas
                                                </div>
                                                <div class="t-cards-text clear-margin--left">Crie grupos de perguntas.</div>
                                                <!-- <span class="fa fa-archive fa-4x block"><i></i></span>
                                                <span class="txt">Grupos de Perguntas</span>
                                                <div class="clearfix"></div> -->
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="t-cards">
                                   <div class="t-cards-content">
                                        <a href="<?php echo Yii::app()->createUrl('quiz/default/questionGroup') ?>" class="t-cards-link">
                                            <span class="t-icon-question-group t-reports_icons align-items--center"></span>
                                            <div>
                                                <div class="t-cards-title">
                                                    Agrupar Perguntas
                                                </div>
                                                <div class="t-cards-text clear-margin--left">Atualize os dados da Unidade.</div>
                                                <!-- <span class="fa fa-list fa-4x block"><i></i></span>
                                                <span class="txt">Agrupar Perguntas</span>
                                                <div class="clearfix"></div> -->
                                            </div>
                                        </a>
                                   </div>
                                </div>
                            </div>
                        </div>
                        <div class="column"></div>
                        <div class="column"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>