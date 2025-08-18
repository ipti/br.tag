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



    <?php echo CHtml::beginForm('', 'post', ['enctype' => 'multipart/form-data']); ?>
<div class="row main">
	<div class="column">
		<h1>Importar arquivo de Identificação</h1>
        <span class="subheading">Baixe o arquivo no site do censo e atualize o INEP ID dos alunos. APENAS o arquivo "RESULTADO_ENCONTRADO"</span>
	</div>
</div>

    <div class="form">
        <div class="t-tabs row">
            <div class="column">
                <ul class="t-tabs__list">
                    <li class="active t-tabs__item">
                        <a data-toggle="tab" class="t-tabs__link">
                            <span class="t-tabs__numeration">1</span>
                            <?php echo Yii::t('default', 'Import') ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main form-content">

            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><?= Yii::app()->user->getFlash('success'); ?></strong>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (Yii::app()->user->hasFlash('error')): ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><?= Yii::app()->user->getFlash('error'); ?></strong>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($importModel->hasErrors()): ?>
                <div class="row">
                    <div class="col-md-12">
                        <?= CHtml::errorSummary($importModel); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="t-field-text">
                        <?= CHtml::activeLabel($importModel, 'file', ['class' => 't-field-text__label']); ?>
                        <div class="controls">
                            <?= CHtml::activeFileField($importModel, 'file'); ?>
                        </div>
                    </div>
                </div>
                <div class="row hidden">
                    <div class="col-md-4">
                        <div class="t-field-text">
                            <?= CHtml::activeLabel($importModel, 'probable', ['class' => 't-field-text__label']); ?>
                            <div class="controls">
                                <?= CHtml::activeCheckBox($importModel, 'probable'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="t-field-text">
                        <?= CHtml::submitButton('Importar', ['class' => 'btn btn-primary']); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if (Yii::app()->user->hasFlash('log')): ?>
            <div class="row">
                <div class="col-md-12">
                    <?= Yii::app()->user->getFlash('log'); ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php echo CHtml::endForm(); ?>

</div>
