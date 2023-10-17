<?php
/* @var $this AdminController */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/admin/index/dialogs.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/admin/index/global.js', CClientScript::POS_END);
$cs->registerCssFile($baseUrl . '/css/admin.css');

$this->pageTitle = 'TAG - ' . Yii::t('default', 'Administration');

?>

<div class="main">

    <div class="row-fluid">
        <div class="span12" style="margin-left: 20px;">
            <h1><?php echo Yii::t('default', 'Administration'); ?></h1>
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
                    <div class="container-box">

                        <a href="<?php echo Yii::app()->createUrl('admin/createUser') ?>">
                            <button type="button" class="admin-box-container">
                                <div class="pull-left" style="margin-right: 20px;">
                                <span class="t-icon-person_add t-reports_icons"></span>
                                <!-- <div class="t-icon-schedule report-icon"></div> -->
                                </div>
                                <div class="pull-left">
                                    <span class="title">Cadastrar usuário</span><br>
                                    <span class="subtitle">Cadastre um novo usuário</span>
                                </div>
                            </button>
                        </a>

                        <a href="<?php echo Yii::app()->createUrl('admin/gradesStructure') ?>">
                            <button type="button" class="admin-box-container">
                                <div class="pull-left" style="margin-right: 20px;">
                                    <span class="t-icon-diary t-reports_icons"></span>
                                    <!-- <div class="t-icon-schedule report-icon"></div> -->
                                </div>
                                <div class="pull-left">
                                    <span class="title">Estrutura de Unidades e Avaliações</span><br>
                                    <span class="subtitle">Informações das Unidades de uma Etapa</span>
                                </div>
                            </button>
                        </a>

                        <a href="<?php echo Yii::app()->createUrl('curricularcomponents') ?>">
                            <button type="button" class="admin-box-container">
                                <div class="pull-left" style="margin-right: 20px;">
                                    <span class="t-icon-copy t-reports_icons"></span>
                                </div>
                                <div class="pull-left">
                                    <span class="title">Componentes curriculares</span><br>
                                    <span class="subtitle">Gerencie os componetes curriculares da unidade</span>
                                </div>
                            </button>
                        </a>

                        <a href="<?php echo Yii::app()->createUrl('admin/editPassword', array("id" => Yii::app()->user->loginInfos->id)) ?>">
                            <button type="button" class="admin-box-container">
                                <div class="pull-left" style="margin-right: 20px;">
                                <span class="t-icon-lock t-reports_icons"></span>                                    <!-- <div class="t-icon-schedule report-icon"></div> -->
                                </div>
                                <div class="pull-left">
                                    <span class="title">Alterar Senha</span><br>
                                    <span class="subtitle">Altere a senha do seu usuário</span>
                                </div>
                            </button>
                        </a>

                        <a href="<?php echo Yii::app()->createUrl('admin/exportmaster') ?>">
                            <button type="button" class="admin-box-container">
                                <div class="pull-left" style="margin-right: 20px;">
                                    <span class="t-icon-submit-form t-reports_icons"></span>
                                    <!-- <div class="t-icon-schedule report-icon"></div> -->
                                </div>
                                <div class="pull-left">
                                    <span class="title">Exportar</span><br>
                                    <span class="subtitle">Exporte as informações do TAG em JSON</span>
                                </div>
                            </button>
                        </a>

                        <a href="<?php echo Yii::app()->createUrl('admin/manageUsers') ?>">
                            <button type="button" class="admin-box-container">
                                <div class="pull-left" style="margin-right: 20px;">
                                    <span class="t-icon-configuration-adm t-reports_icons"></span>
                                    <!-- <div class="t-icon-schedule report-icon"></div> -->
                                </div>
                                <div class="pull-left">
                                    <span class="title">Gerenciar Usuários</span><br>
                                    <span class="subtitle">Editar usuários do TAG</span>
                                </div>
                            </button>
                        </a>

                        <a href="<?php echo Yii::app()->createUrl('admin/activeDisableUser') ?>">
                            <button type="button" class="admin-box-container">
                                <div class="pull-left" style="margin-right: 20px;">
                                    <span class="t-icon-person_remove t-reports_icons"></span>
                                    <!-- <div class="t-icon-schedule report-icon"></div> -->
                                </div>
                                <div class="pull-left">
                                    <span class="title">Ativar/Desativar usuário</span><br>
                                    <span class="subtitle">Torne um usuário ativo ou inativo</span>
                                </div>
                            </button>
                        </a>

                        <a href="<?php echo Yii::app()->createUrl('admin/changelog') ?>">
                            <button type="button" class="admin-box-container">
                                <div class="pull-left" style="margin-right: 20px;">
                                    <span class="t-icon-update t-reports_icons"></span>
                                    <!-- <div class="t-icon-schedule report-icon"></div> -->
                                </div>
                                <div class="pull-left">
                                    <span class="title">Atualizações no TAG</span><br>
                                    <span class="subtitle">Confira todas as novidades do TAG</span>
                                </div>
                            </button>
                        </a>

                        <a href="<?php echo Yii::app()->createUrl('admin/auditory') ?>">
                            <button type="button" class="admin-box-container">
                                <div class="pull-left" style="margin-right: 20px;">
                                    <span class="t-icon-checklist t-reports_icons"></span>
                                    <!-- <div class="t-icon-schedule report-icon"></div> -->
                                </div>
                                <div class="pull-left">
                                    <span class="title">Auditoria</span><br>
                                    <span class="subtitle">Acompanhe o log das escolas</span>
                                </div>
                            </button>
                        </a>
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