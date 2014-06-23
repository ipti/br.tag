<?php
/* @var $this AdminController */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/admin/index/dialogs.js', CClientScript::POS_END);

$this->pageTitle = 'TAG - ' . Yii::t('default', 'Administration');
$this->breadcrumbs = array(
    Yii::t('default', 'Administration'),
);
?>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Administration'); ?></h3>  
    </div>
</div>

<div class="innerLR home">
    <div class="row-fluid">
        <?php if (Yii::app()->user->hasFlash('success')): ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
            <br/>
        <?php elseif (Yii::app()->user->hasFlash('notice')): ?>
            <div class="alert alert-info">
                <?php echo Yii::app()->user->getFlash('notice') ?>
            </div>
            <br/>
        <?php elseif (Yii::app()->user->hasFlash('error')): ?>
            <div class="alert alert-info">
                <?php echo Yii::app()->user->getFlash('error') ?>
            </div>
            <br/>
        <?php endif ?>
        <div class="span6">
            <div class="row-fluid">
                <div class="span3">
                    <a href="<?php echo Yii::app()->homeUrl; ?>?r=admin/ACL" class="widget-stats">
                        <span class="glyphicons flag"><i></i></span>
                        <span class="txt">Config. Permissões</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span3">
                    <a href="#" class="widget-stats" onclick='$("#import-file-dialog").dialog("open");'>
                        <span class="glyphicons database_plus"><i></i></span>
                        <span class="txt">Importar dados</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span3">
                    <a href="<?php echo Yii::app()->homeUrl; ?>?r=admin/clearDB" class="widget-stats">
                        <span class="glyphicons database_minus"><i></i></span>
                        <span class="txt">Limpar Banco</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span3">
                    <a href="<?php echo Yii::app()->homeUrl; ?>?r=admin/createUser" class="widget-stats">
                        <span class="glyphicons user"><i></i></span>
                        <span class="txt">Cadastrar usuário</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="span3">
                <a href="<?php echo Yii::app()->homeUrl; ?>?r=admin/export" class="widget-stats">
                    <span class="glyphicons file_export"><i></i></span>
                    <span class="txt">Exportar</span>
                    <div class="clearfix"></div>
                </a>
            </div>
            <div class="span3">
                <a href="<?php echo Yii::app()->homeUrl; ?>?r=admin/backup" class="widget-stats">
                    <span class="glyphicons file_export"><i></i></span>
                    <span class="txt">Gerar Backup</span>
                    <div class="clearfix"></div>
                </a>
            </div>
            <div class="span3">
                <a href="<?php echo Yii::app()->homeUrl; ?>?r=admin/data" class="widget-stats">
                    <span class="glyphicons charts"><i></i></span>
                    <span class="txt">Estatísticas</span>
                    <div class="clearfix"></div>
                </a>
            </div>
            <div class="span3">
                <a href="<?php echo Yii::app()->homeUrl; ?>?r=admin/updateDB" class="widget-stats">
                    <span class="glyphicons roundabout"><i></i></span>
                    <span class="txt">Atualizar Banco</span>
                    <div class="clearfix"></div>
                </a>
            </div>
        </div>
        <div class="span6">
            <div class="span3">
                <a href="<?php echo Yii::app()->homeUrl; ?>?r=admin/exportStudentIdentify" class="widget-stats">
                    <span class="glyphicons user"><i></i></span>
                    <span class="txt">Identificação do Aluno</span>
                    <div class="clearfix"></div>
                </a>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="import-file-dialog" title="<?php echo Yii::t('default', 'Import File Dialog'); ?>">
        <div class="row-fluid">
            <div class="span12">
                <form id="import-file-form" method="post" action="<?php echo CController::createUrl('admin/import'); ?>" enctype="multipart/form-data">
                    <div class="control-group">
                        <?php echo CHtml::label(Yii::t('default', 'Import File'), 'file', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <input type="file" name="file" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    var btnImport = "<?php echo Yii::t('default', 'Import'); ?>";
    var btnCancel = "<?php echo Yii::t('default', 'Cancel'); ?>";
</script>
