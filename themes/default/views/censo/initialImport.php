<div id="mainPage" class="main">
    <?php
        $this->setPageTitle('TAG - ' . Yii::t('default', 'Import'));
        $title = Yii::t('default', 'Import');

        $listYears = [];
        $actualYear = date('Y');

        for ($i = 0; $i < 5; $i++) {
            $year = $actualYear - $i;
            $listYears[$year] = $year;
        }

    ?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic">Importação inicial</h3> 
    </div>
</div>

<?php echo CHtml::beginForm('', 'post', ['enctype' => 'multipart/form-data']); ?>

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
                        <?php if (Yii::app()->user->hasFlash('success')): ?>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="alert alert-primary">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong><?= Yii::app()->user->getFlash('success'); ?></strong>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?> 

                        <?php if ($importModel->hasErrors()): ?>
                            <div class="row-fluid">
                                <div class="span12">
                                    <?= CHtml::errorSummary($importModel); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="row-fluid">
                            <div class="span4">
                                <div class="control-group">
                                    <?= CHtml::activeLabel($importModel, 'year', ['class' => 'control-label']); ?>
                                    <div class="controls">
                                        <?= CHtml::activeDropDownList($importModel, 'year', $listYears); ?>
                                        <?= CHtml::error($importModel, 'year'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <?= CHtml::activeLabel($importModel, 'file', ['class' => 'control-label']); ?>
                                    <div class="controls">
                                        <?= CHtml::activeFileField($importModel, 'file'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="control-group">
                                    <?= CHtml::activeLabel($importModel, 'importWithError', ['class' => 'control-label']); ?>
                                    <div class="controls">
                                        <?= CHtml::activeCheckBox($importModel, 'importWithError'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="control-group">
                                    <?= CHtml::submitButton('Importar', ['class' => 'btn btn-primary']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<?php echo CHtml::endForm(); ?>

</div>