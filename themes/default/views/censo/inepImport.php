<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Import'));
    $title = Yii::t('default', 'Import');

    $listYears = [];
    $actualYear = date('Y');


    for ($i=0; $i < 5; $i++) {
        $year = $actualYear - $i;
        $listYears[$year] = $year;
    }

    ?>

    <div class="row-fluid hidden-print">
        <div class="span12">
            <h3 class="heading-mosaic">Importação de INEP ID</h3>
        </div>
    </div>

    <?php echo CHtml::beginForm('', 'post', ["enctype" => "multipart/form-data"]); ?>

    <div class="innerLR">

        <div class="widget widget-tabs border-bottom-none">
            <div class="widget-head">
                <ul class="tab-import">
                    <li id="tab-import-indentify" class="active">
                        <a class="glyphicons file" href="#import-indentify" ata-toggle="tab">
                            <i></i><?php echo Yii::t('default', 'Import') ?>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="widget-body form-horizontal">
                <div class="tab-content">
                    <div class="tab-pane active" id="import-indentify">
                        <?php if(Yii::app()->user->hasFlash('success')): ?>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong><?= Yii::app()->user->getFlash('success'); ?></strong>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if(Yii::app()->user->hasFlash('error')): ?>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="alert alert-error">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong><?= Yii::app()->user->getFlash('error'); ?></strong>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if($importModel->hasErrors()): ?>
                            <div class="row-fluid">
                                <div class="span12">
                                    <?= CHtml::errorSummary($importModel); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="row-fluid">
                            <div class="span4">
                                <div class="control-group">
                                    <?= CHtml::activeLabel($importModel, 'file', array('class' => 'control-label')); ?>
                                    <div class="controls">
                                        <?= CHtml::activeFileField($importModel, 'file'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span4">
                                    <div class="control-group">
                                        <?= CHtml::activeLabel($importModel, 'probable', array('class' => 'control-label')); ?>
                                        <div class="controls">
                                            <?= CHtml::activeCheckBox($importModel, 'probable'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="control-group">
                                    <?= CHtml::submitButton('Importar', ['class'=> 'btn btn-primary']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if(Yii::app()->user->hasFlash('log')): ?>
                        <div class="row-fluid">
                            <div class="span12">
                                <?= Yii::app()->user->getFlash('log'); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php echo CHtml::endForm(); ?>

</div>