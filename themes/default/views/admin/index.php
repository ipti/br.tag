<?php
/* @var $this AdminController */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/admin/index/dialogs.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/admin/index/global.js', CClientScript::POS_END);

$this->pageTitle = 'TAG - ' . Yii::t('default', 'Administration');

?>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Administration'); ?></h3>
    </div>
</div>

<div class="innerLR home">
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
            <div class="alert alert-info">
                <?php echo Yii::app()->user->getFlash('error') ?>
            </div>
            <br />
        <?php endif ?>
        <div class="span10">
            <div class="row-fluid">
                <!--<div class="span3">
                    <a href="<?php echo Yii::app()->createUrl('admin/ACL') ?>" class="widget-stats">
                        <span class="glyphicons flag"><i></i></span>
                        <span class="txt">Config. Permissões</span>
                        <div class="clearfix"></div>
                5</a>
                </div>-->

                <!--                <div class="span3">-->
                <!--                    <a href="--><?php //echo Yii::app()->createUrl('admin/clearDB')
                                                    ?><!--" class="widget-stats">-->
                <!--                        <span class="glyphicons database_minus"><i></i></span>-->
                <!--       5                <span class="txt">Limpar Banco</span>-->
                <!--                        <div class="clearfix"></div>-->
                <!--                    </a>-->
                <!--                </div>-->
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('admin/createUser') ?>" class="widget-stats">
                        <span class="glyphicons user"><i></i></span>
                        <span class="txt">Cadastrar usuário</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('admin/editPassword', ['id' => Yii::app()->user->loginInfos->id]) ?>" class="widget-stats">
                        <span class="glyphicons keys"><i></i></span>
                        <span class="txt">Alterar Senha</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('admin/exportmaster') ?>" class="widget-stats">
                        <span class="glyphicons file_export"><i></i></span>
                        <span class="txt">Exportar</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('admin/activeDisableUser') ?>" class="widget-stats">
                        <span class="glyphicons remove"><i></i></span>
                        <span class="txt">Ativar/Desativar usuário</span>
                    </a>
                </div>
                <div class="span2">
                    <a href="<?php echo Yii::app()->createUrl('admin/changelog') ?>" class="widget-stats">
                        <span class="glyphicons settings"><i></i></span>
                        <span class="txt">Atualizações no TAG</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="changelogs">

    </div>

</div>

<script>
    var btnImport = "<?php echo Yii::t('default', 'Import'); ?>";
    var btnCancel = "<?php echo Yii::t('default', 'Cancel'); ?>";
</script>