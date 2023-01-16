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
            <br />
        <?php elseif (Yii::app()->user->hasFlash('error')) : ?>
            <div class="alert alert-info">
                <?php echo Yii::app()->user->getFlash('error') ?>
            </div>
            <br />
        <?php endif ?>
        <div class="span6">
            <div class="row-fluid">
                <!--<div class="span3">
                    <a href="<?php echo Yii::app()->createUrl('admin/ACL') ?>" class="widget-stats">
                        <span class="glyphicons flag"><i></i></span>
                        <span class="txt">Config. Permissões</span>
                        <div class="clearfix"></div>
                    </a>
                </div>-->

                <!--                <div class="span3">-->
                <!--                    <a href="--><?php //echo Yii::app()->createUrl('admin/clearDB') 
                                                    ?><!--" class="widget-stats">-->
                <!--                        <span class="glyphicons database_minus"><i></i></span>-->
                <!--                        <span class="txt">Limpar Banco</span>-->
                <!--                        <div class="clearfix"></div>-->
                <!--                    </a>-->
                <!--                </div>-->
                <div class="span3">
                    <a href="<?php echo Yii::app()->createUrl('admin/createUser') ?>" class="widget-stats">
                        <span class="glyphicons user"><i></i></span>
                        <span class="txt">Cadastrar usuário</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span3">
                    <a href="<?php echo Yii::app()->createUrl('admin/editPassword', array("id" => Yii::app()->user->loginInfos->id)) ?>" class="widget-stats">
                        <span class="glyphicons keys"><i></i></span>
                        <span class="txt">Alterar Senha</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span3">
                    <a href="<?php echo Yii::app()->createUrl('admin/exportmaster') ?>" class="widget-stats">
                        <span class="glyphicons file_export"><i></i></span>
                        <span class="txt">Exportar</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span3">
                    <a href="#" data-toggle="modal" data-target="#disable-user" class="widget-stats" target="_blank">
                        <div><span class="glyphicons remove"><i></i></span></div>
                        <span class="report-title">Desativar usuário</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="modal fade" id="disable-user" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <h4 class="modal-title" id="myModalLabel">Escolha o Usuário</h4>
                            </div>
                            <form class="form-vertical" id="disableUser" action="/br.tag/?r=admin" method="post">
                                <div class="modal-body">
                                    <div class="row-fluid">
                                        <div class=" span12" style="margin: 10px 0 10px 0;">
                                            <div class="span4">
                                                <?php echo CHtml::label(yii::t('default', 'Users'), 'users', array('class' => 'control-label')); ?>
                                            </div>
                                            <div class="span8">
                                                <?php
                                                echo CHtml::dropDownList('users', '', CHtml::listData(Users::model()->findAll(array('condition' => 'active=1')), 'id', 'name'), array(
                                                    'key' => 'id',
                                                    'class' => 'select-search-on',
                                                    'prompt' => 'Selecione a turma',
                                                ));
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer" style="background-color:#FFF;">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-primary" id="disable-user-submit">Desativar</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    var btnImport = "<?php echo Yii::t('default', 'Import'); ?>";
    var btnCancel = "<?php echo Yii::t('default', 'Cancel'); ?>";
</script>