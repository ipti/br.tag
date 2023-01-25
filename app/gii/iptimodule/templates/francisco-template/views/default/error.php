<?= "<?php\n"; ?>
/* @var $this DefaultController */
/* @var $error array */
$themeUrl = Yii::app()->theme->baseUrl;
$homeUrl = Yii::app()->controller->module->baseUrl;
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$cs = Yii::app()->getClientScript();
$cs->registerCss('mycss', "
.column{
    max-width: 900px!important;
    }
");

$this->pageTitle = Yii::app()->name . ' - Error';
?>
<div class="ui middle aligned center aligned grid ">
    <div class="ten wide column ">
        <div class="ui raised segments">
            <div class="ui tag-blue inverted segment">
                <h2 class="ui image header">
                    <img src="<?= '<?=' ?>$baseScriptUrl ?>/common/img/logo.png" class="image">
                    <div class="content">
                        <?= '<?=' ?> yii::t('<?php echo $this->moduleID; ?>Module.error', 'Something happen') ?>
                    </div>
                </h2>
            </div>
            <div class="ui left aligned segment negative message">
                <div class="header">  <?= '<?=' ?> yii::t('<?php echo $this->moduleID; ?>Module.error', 'Attention!') ?> Erro <?= '<?=' ?> $error['code'] ?>, <?= '<?=' ?> $error['message'] ?></div>
                <p> <?= '<?=' ?> yii::t('<?php echo $this->moduleID; ?>Module.error', 'Contact us') ?></p>
                <p>(79) 3255-1664 ou (79) 9680-3343</p>
            </div>
            <div class="ui left aligned segment" style="max-height: 300px; overflow-x:auto;overflow-y:auto;white-space:normal;">
                <p><?= '<?=' ?> yii::t('<?php echo $this->moduleID; ?>Module.error>', 'Technical Information') ?>:<p>
                <p><?= '<?=' ?> json_encode($error);?></p>
            </div>
            <div class="ui segment">
                <input type="submit" class="ui inverted fluid large tag-blue submit button" onclick="window.history.back();" value="Voltar"/>
            </div>
        </div>
    </div>
</div>