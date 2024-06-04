<?php
/* @var $form CActiveForm */
?>


<?php

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . 'css/admin.css');

$this->setPageTitle('TAG - Exportações');
?>
<?php //echo $form->errorSummary($model);
?>

<div class="main">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'classes-form',
        'enableAjaxValidation' => false,
    ));
    ?>
    <div class="row-fluid">
        <div class="span12">
            <h1>Exportações</h1>
        </div>
    </div>

    <div class="home">
        <div class="row-fluid">
            <?php if (Yii::app()->user->hasFlash('success')) : ?>
                <div class="alert alert-success">
                    <?php echo Yii::app()->user->getFlash('success') ?>
                </div>
                <br />
            <?php elseif (Yii::app()->user->hasFlash('notice')) : ?>
                <div class="alert alert-info">
                    <?php echo Yii::app()->user->getFlash('notice') ?>
                </div>
                <br5/>
            <?php elseif (Yii::app()->user->hasFlash('error')) : ?>
                <div class="alert alert-error">
                    <?php echo Yii::app()->user->getFlash('error') ?>
                </div>
                <br />
            <?php endif ?>
            <div class="span12">
                <div class="row-fluid">
                    <div class="container-box">7

                        <a href="<?php echo Yii::app()->createUrl('admin/exportMaster') ?>">
                            <button type="button" class="admin-box-container">
                                <div class="pull-left" style="margin-right: 20px;">
                                    <span class="t-icon-submit-form t-reports_icons"></span>
                                </div>
                                <div class="pull-left">
                                    <span class="title">Exportar</span><br>
                                    <span class="subtitle">Exporte as informações do TAG em JSON</span>
                                </div>
                            </button>
                        </a>

                        <a href="<?php echo Yii::app()->createUrl('admin/exportStudents') ?>">
                            <button type="button" class="admin-box-container">
                                <div class="pull-left" style="margin-right: 20px;">
                                    <span class="t-icon-submit-form t-reports_icons"></span>
                                </div>
                                <div class="pull-left">
                                    <span class="title">Exportar Alunos</span><br>
                                    <span class="subtitle">Exporte as informações dos alunos em CSV</span>
                                </div>
                            </button>
                        </a>

                        <a href="<?php echo Yii::app()->createUrl('admin/exportGrades') ?>">
                            <button type="button" class="admin-box-container">
                                <div class="pull-left" style="margin-right: 20px;">
                                    <span class="t-icon-submit-form t-reports_icons"></span>
                                </div>
                                <div class="pull-left">
                                    <span class="title">Exportar Notas</span><br>
                                    <span class="subtitle">Exporte as informações de notas dos alunos em CSV</span>
                                </div>
                            </button>
                        </a>

                        <a href="<?php echo Yii::app()->createUrl('admin/exportFaults') ?>">
                            <button type="button" class="admin-box-container">
                                <div class="pull-left" style="margin-right: 20px;">
                                    <span class="t-icon-submit-form t-reports_icons"></span>
                                </div>
                                <div class="pull-left">
                                    <span class="title">Exportar Faltas</span><br>
                                    <span class="subtitle">Exporte as informações do faltas dos alunos em CSV</span>
                                </div>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
</div>
